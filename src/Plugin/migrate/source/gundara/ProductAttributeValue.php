<?php

namespace Drupal\gundara_migrate\Plugin\migrate\source\gundara;

use Drupal\migrate\Row;
use Drupal\migrate_drupal\Plugin\migrate\source\d7\FieldableEntity;

/**
 * Drupal 7 commerce product attributes source.
 *
 * @MigrateSource(
 *   id = "gundara_product_attribute_value",
 *   source = "taxonomy_term"
 * )
 */
class ProductAttributeValue extends FieldableEntity {

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return [
      'tid' => t('Term Id'),
      'vid' => t('Vocabulary Id'),
      'name' => t('Term Name'),
      'machine_name' => t('Vocabulary Value'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids['tid']['type'] = 'integer';
    $ids['tid']['alias'] = 't';

    return $ids;
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('taxonomy_term_data', 't');
    $query->leftJoin('taxonomy_vocabulary', 'tv', '(t.vid = tv.vid)');
    $query->condition('tv.machine_name', $this->configuration['vocabulary'], 'IN');
    $query->fields('t', ['tid', 'name'])
      ->fields('tv', ['vid', 'machine_name']);
    return $query;
  }
}
