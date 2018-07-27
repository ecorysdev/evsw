<?php
/**
 * @file
 * evsw_front.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function evsw_front_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_node_info().
 */
function evsw_front_node_info() {
  $items = array(
    'front' => array(
      'name' => t('Front'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
    'front_banner' => array(
      'name' => t('Front Banner'),
      'base' => 'node_content',
      'description' => t('The banner that will appear at the top of the homepage'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
