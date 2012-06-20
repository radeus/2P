<?php

/**
 * @file
 *   Commerce Product migration.
 *   This is a dynamic migration, reused for every product type
 *   (so that products of each type can be imported separately)
 */

class CommerceMigrateUbercartProductMigration extends DynamicMigration {

  protected $file_public_path = "";
  protected $file_private_path = "";
  protected $file_temporary_path = "";
  protected $source_drupal_root = "";
  protected $image_fields = "";

  public function __construct(array $arguments) {
    $this->arguments = $arguments;
    parent::__construct();
    $this->description = t('Import products from Ubercart.');
    $this->dependencies = array('CommerceMigrateUbercartProductType');
    $this->image_fields = preg_split('/ *[, ]+ */', variable_get('commerce_migrate_ubercart_image_fields', 'field_image_cache'));

    $this->file_public_path = variable_get('commerce_migrate_ubercart_public_files_directory', variable_get('file_public_path', 'sites/default/files'));
    $this->source_drupal_root = variable_get('commerce_migrate_ubercart_source_drupal_root', DRUPAL_ROOT);

    // Create a map object for tracking the relationships between source rows
    $this->map = new MigrateSQLMap($this->machineName,
      array(
          'nid' => array(
            'type' => 'int',
            'unsigned' => TRUE,
            'not null' => TRUE,
            'description' => 'Ubercart node ID',
            'alias' => 'ucp',
          ),
      ),
      MigrateDestinationEntityAPI::getKeySchema('commerce_product', $arguments['type'])
    );
    // Create a MigrateSource object, which manages retrieving the input data.
    $connection = commerce_migrate_ubercart_get_source_connection();
    $query = $connection->select('node', 'n');
    $query->innerJoin('uc_products', 'ucp', 'n.nid = ucp.nid AND n.vid = ucp.vid');
    $query->fields('n', array('nid', 'vid', 'type', 'title', 'created', 'changed'))
          ->fields('ucp', array('model', 'sell_price'))
          ->condition('n.type', $arguments['type'])
          ->distinct();
    if (variable_get('commerce_migrate_ubercart_user_map_ok', FALSE)) {
      $query->addField('n', 'uid', 'uid');
    }

    $this->source = new MigrateSourceSQL($query, array(), NULL, array('map_joinable' => FALSE));

    $this->destination = new MigrateDestinationEntityAPI('commerce_product', $arguments['type']);

    // Properties
    $this->addFieldMapping('sku', 'model');
    $this->addFieldMapping('type', 'type')
      ->sourceMigration('CommerceMigrateUbercartProductType');
    $this->addFieldMapping('title', 'title');
    $this->addFieldMapping('created', 'created');
    $this->addFieldMapping('changed', 'changed');
    // Default uid to 0 if we're not mapping it.
    if (variable_get('commerce_migrate_ubercart_user_map_ok', FALSE) && variable_get('commerce_migrate_ubercart_user_migration_class', "") != "") {
      $this->addFieldMapping('uid', 'uid')->sourceMigration(variable_get('commerce_migrate_ubercart_user_migration_class', ""))->defaultValue(0);
    }
    else {
      $this->addFieldMapping('uid', 'uid')->defaultValue(0);
    }
    $this->addFieldMapping('status')
      ->defaultValue(1);
    $this->addFieldMapping('product_id')->issueGroup(t('DNM'));
    $this->addFieldMapping('path')->issueGroup(t('DNM'));
    // Fields
    $this->addFieldMapping('commerce_price', 'sell_price');
    $this->addFieldMapping('field_commerce_file_s3', 'commerce_file_s3')
      ->arguments(array(
        'skip_source_file_check' => TRUE,
        'preserve_files' => TRUE,
        'file_function' => 'file_link',
      ));
    foreach($this->image_fields as $field) {
      $this->addFieldMapping($field, $field)
        ->arguments(array(
          'file_function' => 'file_copy',
          'file_replace' => FILE_EXISTS_REPLACE,
        ));
    }
    // @todo: This entire section should be overridden in a subclass
    if (module_exists('commerce_migrate_uc_file_s3')) {
      $this->addFieldMapping('field_commerce_file_s3', 'commerce_file_s3')
        ->arguments(array(
          'skip_source_file_check' => TRUE,
          'preserve_files' => TRUE,
          'file_function' => 'file_link',
        ));
    }
  }

  public function prepareRow($row) {

    // Prepare the imagefields, from http://drupal.org/node/1159234
    $connection = commerce_migrate_ubercart_get_source_connection();

    foreach ($this->image_fields as $fieldname) {

      switch (SOURCE_DATABASE_DRUPAL_VERSION) {
        case 'd7':
          $query = $connection->select("field_data_$fieldname", 'img');
          $query->innerJoin('file_managed', 'f', "img.{$fieldname}_fid = f.fid");
          $query->fields('f', array('fid', 'uri'));
          $query->fields('img', array("{$fieldname}_alt", "{$fieldname}_title"));
          $query->condition('img.entity_id', $row->nid)
            ->condition('img.revision_id', $row->vid);
          break;
        case 'd6':
          $query = $connection->select("content_{$fieldname}", 'img');
          $query->innerJoin('files', 'f', "{$fieldname}_fid = f.fid");
          $query->addField('f', 'filepath', 'uri'); // Pretend it's D7 uri.
          $query->addField('f', 'fid', 'fid');
          $query->addField('img', "{$fieldname}_data");
          $query->condition('img.nid', $row->nid)
            ->condition('img.vid', $row->vid);
          break;
      }

      $result = $query->execute();
      foreach ($result as $image_row) {
        $path = $image_row->uri;
        // We have to munge the path because migrate's 'source_path' doesn't
        // really work right with streamwrapper paths.
        switch (SOURCE_DATABASE_DRUPAL_VERSION) {
          case 'd7':
            $path = str_replace('public://', $this->file_public_path . '/', $path);
            break;
          case 'd6':
            $path = $this->source_drupal_root . '/' . $path;
            break;
        }
        $row->{$fieldname}[] = $path;
      }
    }
    // @todo: MUST be moved into a subclass!
    // module existence. But for now, this will have to do it.
    if (module_exists('commerce_migrate_uc_file_s3')) {
      // Prepare the commerce_file fields
      $query = $connection->select('uc_product_features', 'upf')
        ->condition('upf.fid', 'file_s3');
      $query->innerJoin('node', 'n', 'upf.nid = n.nid');
      $query->leftJoin('uc_file_s3_products', 'ufsp', 'upf.pfid = ufsp.pfid');
      $query->innerJoin('uc_files_s3', 'ufs', 'ufs.fid = ufsp.fid');
      $query->fields('n', array('nid', 'vid', 'type', 'title', 'created', 'changed'))
            ->fields('ufsp', array('model', 'description', 'download_limit', 'address_limit', 'time_quantity'))
            ->fields('ufs', array('filename'))
            ->distinct();
      $query->condition('n.nid', $row->nid)
        ->condition('n.vid', $row->vid);
      $result = $query->execute();
      foreach ($result as $file_row) {
        $path = 's3://' . $file_row->filename;
        $value = array(
          'path' => $path,
          'download_limit' => $file_row->download_limit,
          'address_limit' => $file_row->address_limit,
          'duration' => $file_row->time_quantity,
        );
        $row->commerce_file_s3[] = drupal_json_encode($value);
      }
    }
  }

   /**
   * Return a list of all product migrations.
   */
  public static function getProductMigrations() {
    $migrations = array();
    foreach (commerce_product_types() as $type => $product_type) {
      $migrations[] = 'CommerceMigrateUbercartProduct' . ucfirst($type);
    }
    return $migrations;
  }


  /**
   * Construct the machine name (identifying the migration in "drush ms" and other places).
   */
  protected function generateMachineName($class_name = NULL) {
    return 'CommerceMigrateUbercartProduct' . ucfirst($this->arguments['type']);
  }
}
