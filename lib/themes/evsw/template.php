<?php

/**
 * @file
 * Default theme functions.
 */

/**
 * Implements template_preprocess_page().
 */
function evsw_preprocess_page(&$variables) {
  $title = drupal_get_title();
  // Format regions.
  $regions = array();
  $regions['header_right'] = (isset($variables['page']['header_right']) ? render($variables['page']['header_right']) : '');
  $regions['header_top'] = (isset($variables['page']['header_top']) ? render($variables['page']['header_top']) : '');
  $regions['featured'] = (isset($variables['page']['featured']) ? render($variables['page']['featured']) : '');
  $regions['sidebar_left'] = (isset($variables['page']['sidebar_left']) ? render($variables['page']['sidebar_left']) : '');
  $regions['tools'] = (isset($variables['page']['tools']) ? render($variables['page']['tools']) : '');
  $regions['content_top'] = (isset($variables['page']['content_top']) ? render($variables['page']['content_top']) : '');
  $regions['help'] = (isset($variables['page']['help']) ? render($variables['page']['help']) : '');
  $regions['content'] = (isset($variables['page']['content']) ? render($variables['page']['content']) : '');
  $regions['content_right'] = (isset($variables['page']['content_right']) ? render($variables['page']['content_right']) : '');
  $regions['content_bottom'] = (isset($variables['page']['content_bottom']) ? render($variables['page']['content_bottom']) : '');
  $regions['sidebar_right'] = (isset($variables['page']['sidebar_right']) ? render($variables['page']['sidebar_right']) : '');
  $regions['footer'] = (isset($variables['page']['footer']) ? render($variables['page']['footer']) : '');

  // Check if there is a responsive sidebar or not.
  $has_responsive_sidebar = ($regions['header_right'] || $regions['sidebar_left'] || $regions['sidebar_right'] ? 1 : 0);

  // Calculate size of regions.
  $cols = array();
  // Sidebars.
  $cols['sidebar_left'] = array(
    'lg' => (!empty($regions['sidebar_left']) ? 3 : 0),
    'md' => (!empty($regions['sidebar_left']) ? 4 : 0),
    'sm' => 0,
    'xs' => 0,
  );
  $cols['sidebar_right'] = array(
    'lg' => (!empty($regions['sidebar_right']) ? 3 : 0),
    'md' => (!empty($regions['sidebar_right']) ? (!empty($regions['sidebar_left']) ? 12 : 4) : 0),
    'sm' => 0,
    'xs' => 0,
  );

  // Content.
  $cols['content_main'] = array(
    'lg' => 12 - $cols['sidebar_left']['lg'] - $cols['sidebar_right']['lg'],
    'md' => ($cols['sidebar_right']['md'] == 4 ? 8 : 12 - $cols['sidebar_left']['md']),
    'sm' => 12,
    'xs' => 12,
  );
  $cols['content_right'] = array(
    'lg' => (!empty($regions['content_right']) ? 6 : 0),
    'md' => (!empty($regions['content_right']) ? 6 : 0),
    'sm' => (!empty($regions['content_right']) ? 12 : 0),
    'xs' => (!empty($regions['content_right']) ? 12 : 0),
  );
  $cols['content'] = array(
    'lg' => 12 - $cols['content_right']['lg'],
    'md' => 12 - $cols['content_right']['md'],
    'sm' => 12,
    'xs' => 12,
  );

  // Tools.
  $cols['sidebar_button'] = array(
    'sm' => ($has_responsive_sidebar ? 2 : 0),
    'xs' => ($has_responsive_sidebar ? 2 : 0),
  );
  $cols['tools'] = array(
    'lg' => (empty($title) ? 12 : 4),
    'md' => (empty($title) ? 12 : 4),
    'sm' => 12,
    'xs' => 12,
  );

  // Title.
  $cols['title'] = array(
    'lg' => 12 - $cols['tools']['lg'],
    'md' => 12 - $cols['tools']['md'],
    'sm' => 12,
    'xs' => 12,
  );

  // Add variables to template file.
  $variables['regions'] = $regions;
  $variables['cols'] = $cols;
  $variables['has_responsive_sidebar'] = $has_responsive_sidebar;

  $variables['menu_visible'] = FALSE;
  if (!empty($variables['page']['featured'])) {
    foreach ($variables['page']['featured'] as $key => $value) {
      if ($key == 'system_main-menu' ||
        strpos($key, 'om_maximenu') !== FALSE
      ) {
        $variables['menu_visible'] = TRUE;
      }
    }
  }
  // Update logo for interinstitutional theme option.
  if (theme_get_setting('enable_interinstitutional_theme')) {
    $variables['logo'] = file_create_url(drupal_get_path('theme', 'ec_resp') . '/logo_europa.png');
  }
  elseif (theme_get_setting('default_logo')) {
    $variables['svg_logo'] = file_create_url(drupal_get_path('theme', 'ec_resp') . '/logo.svg');
  }

  // Adding pathToTheme for Drupal.settings to be used in js files.
  $base_theme = multisite_drupal_toolbox_get_base_theme();
  drupal_add_js('jQuery.extend(Drupal.settings, { "pathToTheme": "' . drupal_get_path('theme', $base_theme) . '" });', 'inline');
}

/**
 * Implements template_preprocess_node().
 */
function evsw_preprocess_node(&$variables) {
  $node = $variables['node'];

  $function_name = 'evsw_preprocess_node_' . $node->type;
  if (function_exists($function_name)) {
    call_user_func($function_name, $variables);
  }

  $variables['prefix_display'] = FALSE;
  $variables['suffix_display'] = FALSE;

  if (isset($variables['group_content_access']) && isset($variables['group_content_access'][LANGUAGE_NONE]) && $variables['group_content_access'][LANGUAGE_NONE][0]['value'] == 2) {
    $variables['prefix_display'] = TRUE;
  }

  if ($variables['display_submitted']) {
    $variables['suffix_display'] = TRUE;
  }

  // Alter date format.
  $custom_date = format_date($variables['created'], 'custom', 'l, d/m/Y');
  $variables['submitted'] = t('Published by !username on !datetime', array(
    '!username' => $variables['name'],
    '!datetime' => $custom_date,
  ));

  // Add classes.
  if ($variables['view_mode'] == 'full' && node_is_page($variables['node'])) {
    $variables['classes_array'][] = 'node-full';
  }
  if ($variables['teaser'] || !empty($variables['content']['comments']['comment_form'])) {
    unset($variables['content']['links']['comment']['#links']['comment-add']);
  }

  switch ($variables['type']) {
    case 'idea':
      $variables['watched'] = $variables['field_watching'][0]['value'];
      break;

    case 'gallerymedia':
      unset($variables['content']['field_picture_upload']);
      unset($variables['content']['field_video_upload']);
      break;
  }

  // Display last update date.
  if ($variables['display_submitted']) {
    $node = $variables['node'];

    // Append the revision information to the submitted by text.
    $revision_account = user_load($node->revision_uid);
    $variables['revision_name'] = theme('username', array('account' => $revision_account));
    $variables['revision_date'] = format_date($node->changed);
    $variables['submitted'] .= "<br />" . t('Last modified by !revision-name on !revision-date',
        array(
          '!revision-name' => $variables['revision_name'],
          '!revision-date' => $variables['revision_date'],
        )
      );
  }

}

/**
 * Custom preprocess function for nodes of type 'front'.
 */
function evsw_preprocess_node_front(&$variables) {
  global $language;

  $nid = db_query("SELECT nid from {node} WHERE type = :type LIMIT 1", array(":type" => 'front_banner'))->fetchField();
  $node = node_load($nid);
  $variables['content']['banner'] = node_view($node, 'full', $language->language);
}

/**
 * Custom preprocess function for nodes of type 'news'.
 */
function evsw_preprocess_node_news(&$variables) {
  global $language;

  if ($variables['view_mode'] != 'teaser') {
    $nid = db_query("SELECT nid from {node} WHERE type = :type LIMIT 1", array(":type" => 'news_banner'))->fetchField();
    $node = node_load($nid);
    $variables['content']['banner'] = node_view($node, 'full', $language->language);
  }
}

/**
 * Custom preprocess function for nodes of type 'video'.
 */
function evsw_preprocess_node_video(&$variables) {
  $node = $variables['node'];
  $variables['video_url'] = $node->field_video_url[LANGUAGE_NONE][0]['url'];
  $variables['image_url'] = file_create_url($node->field_video_image[LANGUAGE_NONE][0]['uri']);
}

/**
 * Custom preprocess function for nodes of type 'webform'.
 */
function evsw_preprocess_node_webform(&$variables) {
  global $language;

  if ($variables['view_mode'] != 'teaser') {
    $nid = db_query("SELECT nid from {node} WHERE type = :type LIMIT 1", array(":type" => 'webform_banner'))->fetchField();
    $node = node_load($nid);
    $variables['content']['banner'] = node_view($node, 'full', $language->language);
  }
}

/**
 * Custom preprocess function for nodes of type 'front_banner'.
 */
function evsw_preprocess_node_front_banner(&$variables) {
  $node = $variables['node'];
  $variables['banner_url'] = file_create_url($node->field_banner_image[LANGUAGE_NONE][0]['uri']);
  $variables['banner_classes'] = $node->field_css_class[LANGUAGE_NONE][0]['safe_value'];
  $variables['site_slogan'] = variable_get('site_slogan', '');
}

/**
 * Custom preprocess function for nodes of type 'news_banner'.
 */
function evsw_preprocess_node_news_banner(&$variables) {
  $node = $variables['node'];
  $variables['banner_url'] = file_create_url($node->field_banner_image[LANGUAGE_NONE][0]['uri']);
  $variables['banner_classes'] = $node->field_css_class[LANGUAGE_NONE][0]['safe_value'];
  $variables['site_slogan'] = variable_get('site_slogan', '');
}

/**
 * Custom preprocess function for nodes of type 'webform_banner'.
 */
function evsw_preprocess_node_webform_banner(&$variables) {
  $node = $variables['node'];
  $variables['banner_url'] = file_create_url($node->field_banner_image[LANGUAGE_NONE][0]['uri']);
  $variables['banner_classes'] = $node->field_css_class[LANGUAGE_NONE][0]['safe_value'];
  $variables['site_slogan'] = variable_get('site_slogan', '');
}

/**
 * Custom preprocess function for nodes of type 'landing_page'.
 */
function evsw_preprocess_node_landing_page(&$variables) {
  $node = $variables['node'];
  $variables['banner_url'] = file_create_url($node->field_banner_image[LANGUAGE_NONE][0]['uri']);
  $variables['banner_classes'] = $node->field_css_class[LANGUAGE_NONE][0]['safe_value'];
  $variables['site_slogan'] = variable_get('site_slogan', '');
}

/**
 * Implements template_preprocess_field().
 */
function evsw_preprocess_field(&$variables) {
  if ($variables['element']['#field_name'] == 'field_button_1_link') {
    $variables['button_classes'] = $variables['element']['#object']->field_button_1_css[LANGUAGE_NONE][0]['safe_value'];
  }

  if ($variables['element']['#field_name'] == 'field_button_2_link') {
    $variables['button_classes'] = $variables['element']['#object']->field_button_2_css[LANGUAGE_NONE][0]['safe_value'];
  }
}
