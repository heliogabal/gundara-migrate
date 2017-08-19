<?php

namespace Drupal\gundara_migrate\Plugin\migrate\process\gundara;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Migrate integer based amount to new format.
 *
 * @MigrateProcessPlugin(
 *   id = "gundara_amount"
 * )
 */
class Amount extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    /** @var \Drupal\commerce_price\Entity\CurrencyInterface $currency */
    $currency = \Drupal::service('commerce_price.currency_importer')->import('EUR');
    return bcdiv($value, '100', $currency->getFractionDigits());
  }

}
