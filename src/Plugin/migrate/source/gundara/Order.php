<?php

namespace Drupal\gundara_migrate\Plugin\migrate\source\gundara;

use Drupal\commerce_migrate_commerce\Plugin\migrate\source\d7\Order as CommerceMigrateOrder;

/**
 * Drupal 7 commerce_order source from database.
 *
 * @MigrateSource(
 *   id = "gundara_order",
 *   source = "order"
 * )
 */
class Order extends CommerceMigrateOrder {
  /**
   * Overwrite the query to only fetch pending and completed orders.
   */
  public function query() {

    $statuses = [
      'pending',
      'completed',
    ];

    $query = $this->select('commerce_order', 'ord')
      ->fields('ord', array_keys($this->fields()))
      ->condition('ord.status', $statuses, 'IN');

    return $query;
  }

}
