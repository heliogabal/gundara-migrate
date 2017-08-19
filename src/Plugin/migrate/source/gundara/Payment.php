<?php

namespace Drupal\gundara_migrate\Plugin\migrate\source\gundara;

use Drupal\commerce_migrate_commerce\Plugin\migrate\source\d7\PaymentTransaction;

/**
 * Drupal 7 commerce_payment_transaction source from database.
 *
 * @MigrateSource(
 *   id = "gundara_payment",
 *   source = "payment"
 * )
 */
class Payment extends PaymentTransaction {
  /**
   * Overwrite the query to only fetch pending and completed orders.
   */
  public function query() {

    $statuses = [
      'pending',
      'completed',
    ];

    $query = $this->select('commerce_payment_transaction', 'pt')
      ->fields('pt', array_keys($this->fields()));
    $query->innerJoin('commerce_order', 'co', 'pt.order_id = co.order_id');
    $query->condition('co.status', $statuses, 'IN');
    return $query;
  }

}
