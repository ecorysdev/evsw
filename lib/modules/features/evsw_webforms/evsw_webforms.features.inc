<?php
/**
 * @file
 * evsw_webforms.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function evsw_webforms_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_node_info().
 */
function evsw_webforms_node_info() {
  $items = array(
    'webform_banner' => array(
      'name' => t('Webform Banner'),
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
