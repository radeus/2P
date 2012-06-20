<?php

/**
 * @file
 *   Commerce Line Item migration.
 *
 *   @todo Do we need to fill out the serialized data column as well?
 *         It holds the path to the product node and an entity object,
 *         not sure how important it is.
 */

class CommerceMigrateUbercartLineItem extends Migration {
  public function __construct() {
    parent::__construct();
    $this->description = t('Import order line items from Ubercart.');
    $this->dependencies = array('CommerceMigrateUbercartOrder');

    // Create a map object for tracking the relationships between source rows
    $this->map = new MigrateSQLMap($this->machineName,
        array(
          'order_product_id' => array(
            'type' => 'int',
            'unsigned' => TRUE,
            'not null' => TRUE,
            'description' => 'Ubercart order product ID',
          ),
        ),
        MigrateDestinationEntityAPI::getKeySchema('commerce_line_item', 'product')
      );

    // Create a MigrateSource object, which manages retrieving the input data.
    $connection = commerce_migrate_ubercart_get_source_connection();

    $query = $connection->select('uc_order_products', 'uop');
    $query->innerJoin('uc_orders', 'uo', 'uop.order_id = uo.order_id');
    $query->condition('uo.order_status', 'in_checkout', '<>');
    $query->fields('uop', array('order_product_id', 'order_id', 'nid', 'model', 'qty', 'price'));
    $this->source = new MigrateSourceSQL($query, array(), NULL, array('map_joinable' => FALSE));
    $this->destination = new MigrateDestinationEntityAPI('commerce_line_item', 'product');

    // Properties
    $this->addFieldMapping('order_id', 'order_id')
         ->sourceMigration('CommerceMigrateUbercartOrder');
    $this->addFieldMapping('line_item_label', 'model');
    $this->addFieldMapping('quantity', 'qty');
    // Fields
    $this->addFieldMapping('commerce_unit_price', 'price');
    $this->addFieldMapping('commerce_total', 'price');
    $this->addFieldMapping('commerce_product', 'nid')
      ->defaultValue(0)
      ->sourceMigration(CommerceMigrateUbercartProductMigration::getProductMigrations());
    // @todo
    //$this->addFieldMapping('commerce_display_path', '');
  }

  function prepare($line_item, stdClass $row) {
    $line_item->type = 'product';
  }

  /**
   * A line item has been saved. Reference it from the order.
   */
  function complete($line_item, stdClass $row) {
    $order_id = $line_item->order_id['destid1'];
    $delta = db_query("SELECT COUNT(entity_id) FROM {field_data_commerce_line_items} WHERE entity_id = :order_id",
                      array(':order_id' => $order_id))->fetchField();

    db_insert('field_data_commerce_line_items')
      ->fields(array(
        'entity_id' => $order_id,
        'revision_id' => $order_id,
        'entity_type' => 'commerce_order',
        'bundle' => 'commerce_order',
        'deleted' => 0,
        'language' => LANGUAGE_NONE,
        'delta' => $delta,
        'commerce_line_items_line_item_id' => $line_item->line_item_id,
      ))
      ->execute();
  }

  /**
   * The line item has been deleted, delete its references.
   */
  function completeRollback($line_item_id) {
    db_delete('field_data_commerce_line_items')
      ->condition('commerce_line_items_line_item_id', $line_item_id)
      ->execute();
  }

}
