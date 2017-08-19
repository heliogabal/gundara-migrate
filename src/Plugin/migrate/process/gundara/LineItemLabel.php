<?php

namespace Drupal\gundara_migrate\Plugin\migrate\process\gundara;

use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\Plugin\MigratePluginManagerInterface;
use Drupal\migrate\Plugin\MigrationPluginManagerInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Migrate integer based amount to new format.
 *
 * @MigrateProcessPlugin(
 *   id = "gundara_line_item_label"
 * )
 */
class LineItemLabel extends ProcessPluginBase implements ContainerFactoryPluginInterface {


  /**
   * The process plugin manager.
   *
   * @var \Drupal\migrate\Plugin\MigratePluginManager
   */
  protected $processPluginManager;

  /**
   * The migration plugin manager.
   *
   * @var \Drupal\migrate\Plugin\MigrationPluginManagerInterface
   */
  protected $migrationPluginManager;

  /**
   * The migration to be executed.
   *
   * @var \Drupal\migrate\Plugin\MigrationInterface
   */
  protected $migration;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration, MigrationPluginManagerInterface $migration_plugin_manager, MigratePluginManagerInterface $process_plugin_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->migrationPluginManager = $migration_plugin_manager;
    $this->migration = $migration;
    $this->processPluginManager = $process_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration = NULL) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $migration,
      $container->get('plugin.manager.migration'),
      $container->get('plugin.manager.migrate.process')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $source = $row->getSource();
    if (!empty($source['type']) && $source['type'] == 'product') {
      $source_id = $source['commerce_product'][0]['product_id'];
      $migration_id = $this->configuration['variation_migration'];
      /** @var \Drupal\migrate\Plugin\MigrationInterface[] $migrations */
      $migrations = $this->migrationPluginManager->createInstances([$migration_id]);
      foreach ($migrations as $migration_id => $migration) {
        $destination = $migration->getIdMap()->lookupDestinationIds([$source_id]);
        if (!empty($destination)) {
          $variation_id = reset($destination)[0];
          $product = ProductVariation::load($variation_id);
          return $product->label() . ' (' . $product->getSku() . ')';
        }
      }
    }
  }

}
