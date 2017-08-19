<?php

namespace Drupal\gundara_migrate\Plugin\migrate\source\d7;

use Drupal\commerce_migrate_commerce\Plugin\migrate\source\d7\LineItem as CommerceMigrateLineItem;


/**
 * Drupal 7 commerce_line_item source from database.
 *
 * @MigrateSource(
 *   id = "gundara_line_item",
 *   source = "commerce_line_item"
 * )
 */
class LineItem extends CommerceMigrateLineItem {
  /**
   * {@inheritdoc}
   */
  public function query() {
    // Only migrate line items that belong to pending or
    // completed orders.
    $statuses = [
      'pending',
      'completed',
    ];

    $query = $this->select('commerce_line_item', 'li');
    $query->innerJoin('commerce_order', 'co', 'li.order_id = co.order_id');
    $query->fields('li', array_keys($this->fields()))
    ->condition('ord.status', $statuses, 'IN');

    return $query;
  }

}
