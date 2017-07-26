<?php

namespace Drupal\deny_access;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\node\Entity\NodeType;

class DenyAcessPermissions implements ContainerInjectionInterface  {
  /**
   * Instantiates a new instance of this class.
   * This is a factory method that returns a new instance of this class. The
   * factory should pass any needed dependencies into the constructor of this
   * class, but not the container itself. Every call to this method must return
   * a new instance of this class; that is, it may not implement a singleton.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The service container this instance should use.
   */
  public static function create(ContainerInterface $container) {
    // TODO: Implement create() method.
  }

  /**
   * Returns an array of deny_access permissions.
   *
   * @return array
   */
  public function permissions() {
    $permissions = [];
    // Generate permissions for each text format. Warn the administrator that any
    // of them are potentially unsafe.
    foreach ($this->node_permissions_get_configured_types() as $type) {
      $info = NodeType::load($type);
      $permissions += array(
        "DENY accessing $type content" => array(
          'title' => t('@type_name: DENY access', array('@type_name' => $info->name)),
          'description' => t('Override content access granted by other modules for this content type.'),
        ));
    }

    return $permissions;
  }

  public function node_permissions_get_configured_types() {
    $configured_types = array();
    foreach (NodeType::loadMultiple() as $type => $info) {
      if (\Drupal::state()->get('node_permissions_' . $type, 1)) {
        $configured_types[] = $type;
      }
    }
    return $configured_types;
  }
}
