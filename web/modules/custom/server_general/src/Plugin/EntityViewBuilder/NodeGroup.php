<?php

namespace Drupal\server_general\Plugin\EntityViewBuilder;

use Drupal\node\NodeInterface;
use Drupal\server_general\EntityDateTrait;
use Drupal\server_general\EntityViewBuilder\NodeViewBuilderAbstract;
use Drupal\server_general\ThemeTrait\ElementLayoutThemeTrait;
use Drupal\server_general\ThemeTrait\ElementNodeGroupThemeTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The "Node News" plugin.
 *
 * @EntityViewBuilder(
 *   id = "node.group",
 *   label = @Translation("Node - Group"),
 *   description = "Node view builder for News bundle."
 * )
 */
class NodeGroup extends NodeViewBuilderAbstract {

  use ElementLayoutThemeTrait;
  use ElementNodeGroupThemeTrait;
  use EntityDateTrait;

  /**
   * The renderer.
   *
   * This is not used in this file, but the `SearchThemeTrait` uses it.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * The current user service.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $plugin = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $plugin->renderer = $container->get('renderer');
    $plugin->currentUser = $container->get('current_user');

    return $plugin;
  }

  /**
   * Build full view mode.
   *
   * @param array $build
   *   The existing build.
   * @param \Drupal\node\NodeInterface $entity
   *   The entity.
   *
   * @return array
   *   Render array.
   */
  public function buildFull(array $build, NodeInterface $entity) {
    // The hero responsive image.
    $medias = $entity->get('field_featured_image')->referencedEntities();
    $image = $this->buildEntities($medias, 'hero');

    $element = $this->buildElementNodeGroup(
      $entity->label(),
      $this->getFieldOrCreatedTimestamp($entity, (string) $entity->getCreatedTime()),
      $image,
      $this->buildProcessedText($entity),
      $this->currentUser,
      group_id: $entity->id()
    );

    $build[] = $element;

    return $build;
  }

}
