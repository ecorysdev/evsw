<?php
/**
 * @file
 * evsw_videos.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function evsw_videos_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function evsw_videos_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_image_default_styles().
 */
function evsw_videos_image_default_styles() {
  $styles = array();

  // Exported image style: video_teaser.
  $styles['video_teaser'] = array(
    'label' => 'Video Teaser',
    'effects' => array(
      2 => array(
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 480,
          'height' => 270,
        ),
        'weight' => 1,
      ),
    ),
  );

  return $styles;
}

/**
 * Implements hook_node_info().
 */
function evsw_videos_node_info() {
  $items = array(
    'video' => array(
      'name' => t('Video'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
