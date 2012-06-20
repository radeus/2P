<?php

/**
 * @file
 * Support for processing commerce fields (product reference, customer profile reference, price)
 */

class MigrateCommerceCustomerProfileReferenceFieldHandler extends MigrateFieldHandler {
  public function __construct() {
    $this->registerTypes(array('commerce_customer_profile_reference'));
  }

  public function prepare($entity, array $field_info, array $instance, array $values) {
    $migration = Migration::currentMigration();
    $arguments = (isset($values['arguments']))? $values['arguments']: array();
    $language = $this->getFieldLanguage($entity, $field_info, $arguments);
    // Setup the standard Field API array for saving.
    $delta = 0;
    foreach (array_filter($values) as $value) {
      $return[$language][$delta]['profile_id'] = $value;
      $delta++;
    }
    if (!isset($return)) {
      $return = NULL;
    }
    return $return;
  }
}

class MigrateCommerceLineItemReferenceFieldHandler extends MigrateFieldHandler {
  public function __construct() {
    $this->registerTypes(array('commerce_line_item_reference'));
  }

  public function prepare($entity, array $field_info, array $instance, array $values) {
    $migration = Migration::currentMigration();
    $arguments = (isset($values['arguments']))? $values['arguments']: array();
    $language = $this->getFieldLanguage($entity, $field_info, $arguments);
    // Setup the standard Field API array for saving.
    $delta = 0;
    foreach (array_filter($values) as $value) {
      $return[$language][$delta]['line_item_id'] = $value;
      $delta++;
    }
    if (!isset($return)) {
      $return = NULL;
    }
    return $return;
  }
}

class MigrateCommerceProductReferenceFieldHandler extends MigrateFieldHandler {
  public function __construct() {
    $this->registerTypes(array('commerce_product_reference'));
  }

  public function prepare($entity, array $field_info, array $instance, array $values) {
    $migration = Migration::currentMigration();
    $arguments = (isset($values['arguments']))? $values['arguments']: array();
    $language = $this->getFieldLanguage($entity, $field_info, $arguments);
    // Setup the standard Field API array for saving.
    $delta = 0;
    foreach (array_filter($values) as $value) {
      $return[$language][$delta]['product_id'] = $value;
      $delta++;
    }
    if (!isset($return)) {
      $return = NULL;
    }
    return $return;
  }
}

class MigrateCommercePriceFieldHandler extends MigrateFieldHandler {
  public function __construct() {
    $this->registerTypes(array('commerce_price'));
  }

  static function arguments($currency_code = NULL) {
    $arguments = array();
    if (!is_null($currency_code)) {
      $arguments['currency_code'] = $currency_code;
    }
    return $arguments;
  }

  public function prepare($entity, array $field_info, array $instance, array $values) {
    $migration = Migration::currentMigration();
    $arguments = (isset($values['arguments']))? $values['arguments']: array();
    $language = $this->getFieldLanguage($entity, $field_info, $arguments);
    // Setup the standard Field API array for saving.
    $delta = 0;
    foreach ($values as $value) {
      $currency_code = isset($arguments['currency_code']) ? $arguments['currency_code'] : commerce_default_currency();
      // Convert the price amount to an integer based on the currency.
      $return[$language][$delta]['amount'] = commerce_currency_decimal_to_amount($value, $currency_code);
      $return[$language][$delta]['currency_code'] = $currency_code;
      $return[$language][$delta]['data'] = '';
      $delta++;
    }
    if (!isset($return)) {
      $return = NULL;
    }
    return $return;
  }
}
