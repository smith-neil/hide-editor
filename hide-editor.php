<?php

/*
Plugin Name: Hide Editor
Plugin Description: Hides the WYSIWYG editor for specified templates.
Plugin Author: Neil Smith
Version: 1.0.0
*/

defined( 'ABSPATH' ) or die( 'Direct access to hide-editor is blocked. ');

add_action( 'admin_menu', 'hideeditor_add_options_page' );
function hideeditor_add_options_page() {
  add_options_page(
    'Hide Editor Settings',
    'Hide Editor',
    'manage_options',
    'hideeditor',
    'hideeditor_render_options_page'
  );
}

function hideeditor_render_options_page() {
  ?>
  <div class="wrap">
    <h2><?php print $GLOBALS['title']; ?><h2>
    <form action="options.php" method="POST">
      <?php
      settings_fields( 'plugin:hideeditor_option_group' );
      do_settings_sections( 'hideeditor' );
      submit_button();
      ?>
    </form>
  </div>
  <?php
}

if ( !empty( $GLOBALS['pagenow'] )
  and ( 'options-general.php' === $GLOBALS['pagenow']
    or 'options.php' === $GLOBALS['pagenow']
  )
) {
  add_action( 'admin_init', 'hideeditor_register_settings' );
}

function hideeditor_register_settings() {
  $option_name = 'plugin:hideeditor';

  register_setting(
    'plugin:hideeditor_option_group',
    $option_name
  );

  add_settings_section(
    'templates_section',
    'Templates',
    'hideeditor_render_templates_section',
    'hideeditor'
  );

  add_settings_field(
    'templates_field',
    'Templates',
    'hideeditor_render_templates_field',
    'hideeditor',
    'templates_section',
    array (
      'label_for' => 'templates',
      'name' => 'templates',
      'option_name' => $option_name
    )
  );
}

function hideeditor_render_templates_section() {
  print '<p>Here you can choose which templates will hide the WYSIWYG editor.</p>';
}

function hideeditor_render_templates_field( $args ) {
  $option_name = 'plugin:hideeditor';
  $option_fields = get_option( $option_name );
  $option_values = $option_fields['templates'];
  $templates = get_page_templates();

  printf(
    '<select multiple name="%1$s[%2$s][]" id="%3$s">',
    $args['option_name'],
    $args['name'],
    $args['label_for']
  );

  foreach ( $templates as $title => $filename ) {
    printf(
      '<option value="%1$s" %2$s>%3$s</option>',
      $title,
      (in_array( $title, $option_values )) ? 'selected' : '',
      $title
    );
  }

  print('</select>');
}
