<?php

namespace Drupal\profile_config_overrider\Plugin\ConfigFilter;

use Drupal\config_filter\Plugin\ConfigFilterBase;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Extension\ProfileExtensionList;

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
   * @var ProfileExtensionList
   */
  private $profileExtensionList;

  private $syncProfile;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, $profileExtensionList) {
    $this->profileExtensionList = $profileExtensionList;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

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
      $plugin_definition,
      $container->get('extension.list.profile')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function filterReadMultiple(array $names, array $data) {

    $this->syncProfile = $data['core.extension']['profile'];

    //override the sync value for the profile which config split is not
    $data['core.extension']['module'][\Drupal::installProfile()] = 1000;
    $data['core.extension']['profile'] = \Drupal::installProfile();
    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function filterWrite($name, array $data) {

    //make sure the core.extension details match the base profile
    if ($name == 'core.extension') {
      print_r($data);
      $profileInfo = $this->profileExtensionList->getExtensionInfo(\Drupal::installProfile());

      if ($profileInfo['base profile'] != 'lightning') {
        $data['profile'] = $profileInfo['base profile'];
      }
    }

    return $data;
  }
}

