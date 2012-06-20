<?php

/**
 * @file
 *   Commerce Product Type migration.
 */

class CommerceMigrateUbercartProductTypeMigration extends Migration {
  public function __construct() {
    parent::__construct();
    $this->description = t('Import product types from Ubercart.');

    // Create a map object for tracking the relationships between source rows
    $this->map = new MigrateSQLMap($this->machineName,
        array(
          'type' => array('type' => 'varchar',
                           'length' => 32,
                           'not null' => TRUE,
                           'description' => 'The node type identifier.',
                          ),
        ),
        MigrateDestinationCommerceProductType::getKeySchema()
      );

    // This nastiness grabs all the 'product' types named in the
    // uc_product_classes table and adds to it the 'product' type, if it
    // exists in the node table. It evaluates to
    // SELECT DISTINCT n.type AS type, ucp.pcid AS pcid, ucp.name AS name, ucp.description AS description
    //  FROM
    //  {node} n
    //  LEFT OUTER JOIN {uc_product_classes} ucp ON n.type = ucp.pcid
    //  WHERE ( (type IN  ('product')) OR (pcid IS NOT NULL ) )

    $connection = commerce_migrate_ubercart_get_source_connection();
    $query = $connection->select('node', 'n')
      ->fields('n', array('type'))
      ->distinct();

    $query->leftJoin('uc_product_classes', 'ucp', 'n.type = ucp.pcid');
    $query->addField('ucp', 'pcid');
    $query->addField('ucp', 'name');
    $query->addField('ucp', 'description');
    $query->condition(
      db_or()
      ->condition('type', array('product'), 'IN')
      ->isNotNull('pcid'));
    $this->source = new MigrateSourceSQL($query, array(), NULL, array('map_joinable' => FALSE));
    $this->destination = new MigrateDestinationCommerceProductType();

    $this->addFieldMapping('type', 'type');
    $this->addFieldMapping('name', 'name')->defaultValue(t('Product'));
    $this->addFieldMapping('description', 'description')->defaultValue(t('A basic Product'));
  }

  function prepare($type, stdClass $row) {
    // Based on this, the save function selects between an INSERT and an UPDATE.
    // Done here instead of in the plugin so that an UPDATE could be done by changing
    // the line bellow.
    $type->is_new = TRUE;
  }

  /**
   * A new type has been created, create necessary imagefields and register the
   * appropriate migrations.
   */
  public function complete($entity, stdClass $row) {
    $type = $entity->type;

    $image_fields = preg_split('/ *, */', variable_get('commerce_migrate_ubercart_image_fields', 'image_field'));
    foreach ($image_fields as $fieldname) {
      MigrateDestinationCommerceProductType::createFieldImageInstance($type, $fieldname);
    }

    $product_migration = 'CommerceMigrateUbercartProduct' . ucfirst($type);
    $node_migration = 'CommerceMigrateUbercartNode' . ucfirst($type);
    Migration::registerMigration('CommerceMigrateUbercartProductMigration', $product_migration, array('type' => $type));
    Migration::registerMigration('CommerceMigrateUbercartNodeMigration', $node_migration, array('type' => $type));
  }

  /**
   * The type has been deleted, deregister the migration.
   */
  public function completeRollback($type) {
    $product_migration = 'CommerceMigrateUbercartProduct' . ucfirst($type);
    $node_migration = 'CommerceMigrateUbercartNode' . ucfirst($type);
    Migration::deregisterMigration($product_migration);
    Migration::deregisterMigration($node_migration);
  }
}
