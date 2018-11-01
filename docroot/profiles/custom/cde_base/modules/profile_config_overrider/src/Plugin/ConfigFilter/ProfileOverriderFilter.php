<?php

namespace Drupal\profile_config_overrider\Plugin\ConfigFilter;

use Drupal\config_filter\Plugin\ConfigFilterBase;
use Drupal\Core\Config\ConfigManagerInterface;
use Drupal\Core\Config\DatabaseStorage;
use Drupal\Core\Config\FileStorage;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Config\StorageInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Component\PhpStorage\FileStorage as PhpFileStorage;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a SplitFilter.
 *
 * @ConfigFilter(
 *   id = "profile_config_overrider",
 *   label = "Profile Config Overrider",
 *   storages = {"config.storage.sync"},
 * )
 */
class ProfileOverriderFilter extends ConfigFilterBase implements ContainerFactoryPluginInterface {

  use DependencySerializationTrait;

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   *
   * @return \Drupal\Core\Plugin\ContainerFactoryPluginInterface|\Drupal\profile_config_overrider\Plugin\ConfigFilter\ProfileOverriderFilter
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {

    return new static(
      [],
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * {@inheritdoc}
   */
  public function filterReadMultiple(array $names, array $data) {

    //override the sync value for the profile which config split is not

    $data['core.extension']['module'][\Drupal::installProfile()] = 1000;
    $data['core.extension']['profile'] = \Drupal::installProfile();
    return $data;
  }
}

