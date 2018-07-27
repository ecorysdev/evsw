<?php
/**
 * @file
 * evsw_news.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function evsw_news_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function evsw_news_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_image_default_styles().
 */
function evsw_news_image_default_styles() {
  $styles = array();

  // Exported image style: news_teaser.
  $styles['news_teaser'] = array(
    'label' => 'News teaser',
    'effects' => array(
      1 => array(
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 350,
          'height' => 230,
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
function evsw_news_node_info() {
  $items = array(
    'news' => array(
      'name' => t('News Item'),
      'base' => 'node_content',
      'description' => t('News articles'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
    'news_banner' => array(
      'name' => t('News Banner'),
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
