<?php
/*
Plugin Name: Queen Elizabeth II Memorial Ribbon by Gadget Man
Version: 1.1.3
Description: Display mourning Queen Elizabeth II Black Ribbon at selected corner on every page of your website.
Author: Matt Porter
Author URI: htts://thegadgetman.org.uk
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: blackribbon
Domain Path: /languages

{Plugin Name} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {License URI}.
*/

global $blackribbon_options, $blackribbon_images, $blackribbon_css, $blackribbon_js, $jquery_ui_css;
// Array of options and their default values
$blackribbon_options = array (
	'blackribbon_location' => 'bottom-right',
	'blackribbon_schedule_enable' => 'false',
	'blackribbon_schedule_startdate' => 0,
	'blackribbon_schedule_enddate' => 0,
	'blackribbon_schedule_startdate_text' => '',
	'blackribbon_schedule_enddate_text' => '',
);

$blackribbon_images = array (
	'top-left'   => 'images/black_ribbon_top_left_hm.png',
	'top-right' => 'images/black_ribbon_top_right_hm.png',
	'bottom-left' => 'images/black_ribbon_bottom_left_hm.png',
	'bottom-right' => 'images/black_ribbon_bottom_right_hm.png'
);

$blackribbon_css = 'blackribbon.css';

$blackribbon_js = 'blackribbon.js';

$jquery_ui_css = 'jquery-ui-smoothness-1.12.1.css';

/**
 * Activation function. This function creates the required options and defaults.
 */
if (!function_exists('blackribbon_activate')) :
function blackribbon_activate() {
	global $blackribbon_options;
	// Create the required options...
	add_option('blackribbon_options', $blackribbon_options);
}
endif;

/**
 * Uninstall function. This function creates the required options and defaults.
 */
if (!function_exists('blackribbon_uninstall')) :
function blackribbon_uninstall() {
	// Delete the options...
	delete_option('blackribbon_options');
}
endif;

/**
 * This function outputs the plugin options page.
 */
if (!function_exists('blackribbon_options_page')) :
// Define the function
function blackribbon_options_page() {

  // check user capabilities
  if (!current_user_can('manage_options')) {
      return;
  }

	// Load the options
	global $blackribbon_options;
	$blackribbon_options = get_option('blackribbon_options');

	?>

  <div class="wrap">
      <h1><?= esc_html(get_admin_page_title()); ?></h1>
			<p>Determine the way you wanted to display the mourning ribbon. To completely hide the ribbon, please deactivate the plugin.</p>
      <form action="options.php" method="post">
          <?php
          // output security fields for the registered setting "blackribbon"
          settings_fields('blackribbon');

          // output setting sections and their fields
          // (sections are registered for "blackribbon", each field is registered to a specific section)
          do_settings_sections('blackribbon');
          // output save settings button
          submit_button('Save Settings');
          ?>
      </form>
  </div>

	<?php

}
endif;

/**
 * This function handles blackribbon_schedule_cb callback
 */
if (!function_exists('blackribbon_generals_cb')) :
function blackribbon_generals_cb($args)
{
	?>
	<p>The ribbon will be displayed at selected corner on every page of your website.</p>
	<?php
}
endif;

/**
 * This function handles blackribbon_schedule_cb callback
 */
if (!function_exists('blackribbon_schedule_cb')) :
function blackribbon_schedule_cb($args)
{
	?>
	<p>If enabled, the ribbon will only display between 0:00AM of Start Date and 11:59PM of End Date.</p>
	<?php
}
endif;

/**
 * This function handles blackribbon_location_cb callback
 */
if (!function_exists('blackribbon_location_cb')) :
function blackribbon_location_cb($args)
{
	global $blackribbon_options;
  // output the field
  ?>
  <select id="<?= esc_attr($args['label_for']); ?>"
          name="blackribbon_options[<?= esc_attr($args['label_for']); ?>]"
 	>
	  <option value="top-left" <?= isset($blackribbon_options[$args['label_for']]) ? (selected($blackribbon_options[$args['label_for']], 'top-left', false)) : (''); ?>>
	      <?= esc_html('Top Left', 'blackribbon'); ?>
	  </option>
	  <option value="top-right" <?= isset($blackribbon_options[$args['label_for']]) ? (selected($blackribbon_options[$args['label_for']], 'top-right', false)) : (''); ?>>
	      <?= esc_html('Top Right', 'blackribbon'); ?>
	  </option>
	  <option value="bottom-left" <?= isset($blackribbon_options[$args['label_for']]) ? (selected($blackribbon_options[$args['label_for']], 'bottom-left', false)) : (''); ?>>
	      <?= esc_html('Bottom Left', 'blackribbon'); ?>
	  </option>
	  <option value="bottom-right" <?= isset($blackribbon_options[$args['label_for']]) ? (selected($blackribbon_options[$args['label_for']], 'bottom-right', false)) : (''); ?>>
	      <?= esc_html('Bottom Right', 'blackribbon'); ?>
	  </option>
  </select>
  <?php
}
endif;

/**
 * This function handles blackribbon_schedule_enable_cb callback
 */
if (!function_exists('blackribbon_schedule_enable_cb')) :
function blackribbon_schedule_enable_cb($args)
{
	global $blackribbon_options;
  // output the field
  ?>
  <input name="blackribbon_options[<?= esc_attr($args['label_for']); ?>]"
  			 type="checkbox"
  			 id="<?= esc_attr($args['label_for']); ?>"
  			 value="true" <?php checked('true', $blackribbon_options[$args['label_for']]); ?> 
  >
  <?php
}
endif;

/**
 * This function handles blackribbon_schedule_startenddate_cb callback
 */
if (!function_exists('blackribbon_schedule_startenddate_cb')) :
function blackribbon_schedule_startenddate_cb($args)
{
	global $blackribbon_options;
  // output the field
  ?>
  <input type="text"
  			 class="blackribbon-datepicker"
  			 name="blackribbon_options[<?= esc_attr($args['label_for']); ?>_text]"
  			 id="<?= esc_attr($args['label_for']); ?>"
  			 value="<?php echo($blackribbon_options[$args['label_for'].'_text']); ?>"
  />
  <input type="hidden"
  			 class="blackribbon-datepicker-output"
  			 name="blackribbon_options[<?= esc_attr($args['label_for']); ?>]"
  			 id="<?= esc_attr($args['label_for']); ?>"
  			 value="<?php echo($blackribbon_options[$args['label_for']]); ?>"
  />
  <?php
}
endif;

/**
 * custom option and settings
 */
function blackribbon_init_settings()
{
		// initialize datepicker
		blackribbon_datepicker_init();
	
    // register a new setting for "blackribbon_options" page
    register_setting('blackribbon', 'blackribbon_options');

		// register a new section in the "blackribbon" page
		add_settings_section(
		    'blackribbon_generals',
		    __('General', 'blackribbon'),
		    'blackribbon_generals_cb',
		    'blackribbon'
		);

		// register a new field in the "blackribbon_generals" section, inside the "blackribbon" page
		add_settings_field(
		    'blackribbon_location', // id
		    __('Location', 'blackribbon'),
		    'blackribbon_location_cb',
		    'blackribbon',
		    'blackribbon_generals',
		    array(
		        'label_for'         => 'blackribbon_location',
		        'class'             => 'blackribbon_row'
		    )
		);

		// register a new section in the "blackribbon" page
		add_settings_section(
		    'blackribbon_schedule',
		    __('Schedule', 'blackribbon'),
		    'blackribbon_schedule_cb',
		    'blackribbon'
		);

		// register new fields in the "blackribbon_schedule" section, inside the "blackribbon" page
		add_settings_field(
		    'blackribbon_schedule_enable', // id
		    __('Enable Schedule', 'blackribbon'),
		    'blackribbon_schedule_enable_cb',
		    'blackribbon',
		    'blackribbon_schedule',
		    array(
		        'label_for'         => 'blackribbon_schedule_enable',
		        'class'             => 'blackribbon_row'
		    )
		);
		add_settings_field(
		    'blackribbon_schedule_startdate', // id
		    __('Start Date', 'blackribbon'),
		    'blackribbon_schedule_startenddate_cb',
		    'blackribbon',
		    'blackribbon_schedule',
		    array(
		        'label_for'         => 'blackribbon_schedule_startdate',
		        'class'             => 'blackribbon_row'
		    )
		);
		add_settings_field(
		    'blackribbon_schedule_enddate', // id
		    __('End Date', 'blackribbon'),
		    'blackribbon_schedule_startenddate_cb',
		    'blackribbon',
		    'blackribbon_schedule',
		    array(
		        'label_for'         => 'blackribbon_schedule_enddate',
		        'class'             => 'blackribbon_row'
		    )
		);

}

/**
 * This function adds the required page (only 1 at the moment).
 */
if (!function_exists('blackribbon_add_settings_menu')) :
function blackribbon_add_settings_menu() {
	
	// Add Black Ribbon as the sub-menu of Settings menu
	if (function_exists('add_submenu_page')) {
		add_options_page(
			__('Black Ribbon Settings', 'blackribbon'),
			__('Black Ribbon', 'blackribbon'),
			'manage_options',
			__FILE__,
			'blackribbon_options_page'
		);
	}
	
} // End of blackribbon_add_settings_menu() function definition
endif;

/**
 * This function add Settings link from plugin page.
 */
function blackribbon_plugin_action_links( $links, $file ) {
	if ( $file != plugin_basename( __FILE__ ))
		return $links;

	$settings_link = '<a href="options-general.php?page=' . plugin_basename(__FILE__) . '">' . __( 'Settings', 'blackribbon' ) . '</a>';

	array_unshift( $links, $settings_link );

	return $links;
}

/**
 * This function display the ribbon on the website.
 */
function blackribbon_display_ribbon() {
	global $blackribbon_options, $blackribbon_images, $blackribbon_css;
	$blackribbon_options = get_option('blackribbon_options');

	// Load css file
	wp_enqueue_style( 'blackribbon', plugins_url( $blackribbon_css, __FILE__ ) );

	$time = time();
	if($blackribbon_options['blackribbon_schedule_enable'] != "true" || ($blackribbon_options['blackribbon_schedule_enable'] == "true" && $time >= $blackribbon_options['blackribbon_schedule_startdate'] && $time <= ($blackribbon_options['blackribbon_schedule_enddate'] + 86399))) {
		// Display ribbon
		echo '<img src="' .
					plugins_url( $blackribbon_images[$blackribbon_options['blackribbon_location']], __FILE__ ) .
					'" class="blackribbon blackribbon-'. $blackribbon_options['blackribbon_location']. '">';
	}

}

function blackribbon_datepicker_init() {
		global $blackribbon_js, $jquery_ui_css;
    wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_enqueue_style ( 'jquery-ui-datepicker-style', plugins_url( $jquery_ui_css, __FILE__ ) );
    wp_enqueue_script( 'blackribbon-datepicker', plugins_url( $blackribbon_js, __FILE__ ) );
}

// Add an activation hook for this plugin when activating the plugin
register_activation_hook(__FILE__, 'blackribbon_activate');
// Add an uninstall hook for this plugin when uninstalling the plugin
register_uninstall_hook(__FILE__, 'blackribbon_uninstall');
// Add blackribbon settings functionality
add_action('admin_init', 'blackribbon_init_settings');
// Add the create pages options during admin menu load
add_action('admin_menu','blackribbon_add_settings_menu');
// Adds "Settings" link to the plugin action page
add_filter( 'plugin_action_links', 'blackribbon_plugin_action_links',10,2);
// Display Ribbon
add_filter( 'wp_footer', 'blackribbon_display_ribbon');
// Add text domain
load_plugin_textdomain('blackribbon', false, dirname(plugin_basename(__FILE__)) . '/languages');
?>