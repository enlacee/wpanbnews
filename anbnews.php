<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link               null
 * @since             1.0.0
 * @package           Anbnews
 *
 * @wordpress-plugin
 * Plugin Name:       anbnews
 * Plugin URI:        null
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Anibal
 * Author URI:         null
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       anbnews
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-anbnews-activator.php
 */
function activate_anbnews() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-anbnews-activator.php';
	Anbnews_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-anbnews-deactivator.php
 */
function deactivate_anbnews() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-anbnews-deactivator.php';
	Anbnews_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_anbnews' );
register_deactivation_hook( __FILE__, 'deactivate_anbnews' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-anbnews.php';

/**
 * Begins execution of the plugin.
*/
$plugin = Anbnews::getInstance();
$plugin->run();


/*
* Usefull functions prefix: an = anbNews
*/
function an_get_file_path()
{
	return __FILE__;
}

function an_get_dir_path()
{
	return plugin_dir_path(__FILE__);
}
