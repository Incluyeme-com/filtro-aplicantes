<?php
/**
 * Copyright (c) 2020.
 * Jesus Nuñez <Jesus.nunez2050@gmail.com>
 */

/*
Plugin Name: Incluyeme - Filtro aplicantes
Plugin URI: https://github.com/Cro22
Description: Extension de funciones para el Plugin WPJob Board
Author: Jesus Nuñez
Version: 1.5.2
Author URI: https://github.com/Cro22
Text Domain: incluyeme
Domain Path: /languages
*/

defined('ABSPATH') or exit;
require_once plugin_dir_path(__FILE__) . 'include/active_incluyeme.php';
require_once plugin_dir_path(__FILE__) . 'include/menus/incluyeme_filters_menu.php';
add_action('admin_init', 'incluyeme_requirements');

function plugin_name_i18n()
{
	load_plugin_textdomain('plugin-name', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

add_action('plugins_loaded', 'plugin_name_i18n');

function incluyeme_requirements()
{
	if (is_admin() && current_user_can('activate_plugins') && !is_plugin_active('wpjobboard/index.php')) {
		add_action('admin_notices', 'incluyeme_notice');
		deactivate_plugins(plugin_basename(__FILE__));
		
		if (isset($_GET['activate'])) {
			unset($_GET['activate']);
		}
	} else {
		incluyeme_load();
	}
}

function incluyeme_notice()
{
	?>
	<div class="error"><p> <?php echo __('Sorry, but Incluyeme plugin requires the WPJob Board plugin to be installed and
	                      active.', 'incluyeme'); ?> </p></div>
	<?php
}

require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/Incluyeme-com/filtro-aplicantes.git',
	__FILE__,
	'incluyeme-filters-applicants'
);

//Optional: If you're using a private repository, specify the access token like this:
$myUpdateChecker->setAuthentication('5968bf9ebc1e6616d9434d6d3822e86b08164668');

//Optional: Set the branch that contains the stable release.
$myUpdateChecker->setBranch('master');