<?php

namespace Drupal\cde_base\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Acquia\Blt\Robo\Config\ConfigInitializer;
use Symfony\Component\Console\Input\ArgvInput;


/**
 * Enable splits for this profile
 */
class SplitEnableSubscriber implements EventSubscriberInterface {

  /**
   * Redirect pattern based url
   * @param GetResponseEvent $event
   */
  public function splitEnable(GetResponseEvent $event) {

    global $config;

    $split_filename_prefix = 'config_split.config_split';

    $active_profile = \Drupal::installProfile();
    $config["$split_filename_prefix.$active_profile"]['status'] = TRUE;
  }

  /**
   * Listen to kernel.request events and call splitEnable.
   * {@inheritdoc}
   * @return array Event names to listen to (key) and methods to call (value)
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('splitEnable');
    return $events;
  }
}