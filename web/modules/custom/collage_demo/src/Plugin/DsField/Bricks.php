<?php

namespace Drupal\collage_demo\Plugin\DsField;

use Drupal\ds\Plugin\DsField\DsFieldBase;

/**
 * @DsField(
 *   id = "bricks_children",
 *   title = @Translation("Bricks Children"),
 *   entity_type = "media",
 *   provider = "collage_demo"
 * )
 */
class Bricks extends DsFieldBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $carousel_children = $this->getCarouselChildren('node', 'field_bricks');
    return entity_view_multiple($carousel_children, 'full');
  }

  public function isAllowed() {
    return $this->bundle() == 'carousel';
  }

  private function getCarouselChildren($entity_type, $field_name) {
    $query = db_select($entity_type . '__' . $field_name, 'f')
      ->condition('deleted', FALSE)
      ->condition($field_name . '_target_id', $this->entity()->id())
      ->orderBy('delta')
      ->fields('f', ['entity_id']);

    $result = $query->execute();

    $parent_ids = [];

    foreach ($result as $row) {
      $parent_ids[] = (int) $row->entity_id;
    }

    $host_entity_id = $parent_ids[0];

    $query = db_select($entity_type . '__' . $field_name, 'f')
      ->condition('deleted', FALSE)
      ->condition('entity_id', $host_entity_id)
      ->orderBy('delta')
      ->fields('f');

    $result = $query->execute();

    $host_bricks = [];

    foreach ($result as $row) {
      $row->brick = entity_load('media',$row->{$field_name . '_target_id'});
      $host_bricks[] = $row;
    }

    $found_self = FALSE;
    $self_depth = 0;
    $carousel_children = [];

    foreach ($host_bricks as $host_brick) {
      if (!$found_self && $host_brick->brick->id() == $this->entity()->id()) {
        $self_depth = (int) $host_brick->{$field_name . '_depth'};
        $found_self = TRUE;
      }

      if ($found_self && (int) $host_brick->{$field_name . '_depth'} > $self_depth) {
        $carousel_children[] = $host_brick->brick;
      }
    }

    return $carousel_children;
  }

  private function getCollageBricks($entity_type, $entity_id, $field_name, $collage_id) {
    $query = db_select($entity_type . '__' . $field_name, 'f')
      ->condition('deleted', FALSE)
      ->condition('entity_id', $entity_id)
      ->orderBy('delta')
      ->fields('f');

    $result = $query->execute();

    $started = FALSE;
    $start_depth = NULL;
    $stopped = FALSE;

    $children = [];

    foreach ($result as $row) {
      if ((int) $row->{$field_name . '_depth'} < $start_depth || ($started && (int) $row->{$field_name . '_depth'} > $start_depth + 1)) {
        $stopped = TRUE;
      }

      if (!$stopped && $started && (int) $row->{$field_name . '_depth'} == $start_depth) {
        $row->entity = entity_load('media', $row->{$field_name . '_target_id'});
        $children[] = $row;
      }

      if ($row->{$field_name . '_target_id'} == $collage_id) {
        $started = TRUE;
        $start_depth = (int) $row->{$field_name . '_depth'} + 1;
      }
    }

    return $children;
  }

}
