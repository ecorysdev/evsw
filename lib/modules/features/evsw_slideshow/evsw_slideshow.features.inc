<?php
/**
 * @file
 * evsw_slideshow.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function evsw_slideshow_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function evsw_slideshow_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_node_info().
 */
function evsw_slideshow_node_info() {
  $items = array(
    'slideshow_item' => array(
      'name' => t('Slideshow Item'),
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
