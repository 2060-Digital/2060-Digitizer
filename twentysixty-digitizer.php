<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://2060digital.com
 * @since             1.0.0
 * @package           Twentysixty_Digitizer
 *
 * @wordpress-plugin
 * Plugin Name:       2060 Digitizer
 * Plugin URI:        https://bitbucket.org/rbenhase1/2060-digitizer
 * Description:       Used on all 2060 Digital websites, this plugin allows us to rapidly deploy important changes and keep your website secure.
 * Version:           1.1.4
 * Author:            Ryan Benhase
 * Author URI:        http://2060digital.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       twentysixty-digitizer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Disable WP Plugin & Theme Editor for Better Security
define( 'DISALLOW_FILE_EDIT', true );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-twentysixty-digitizer-activator.php
 */
function activate_twentysixty_digitizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-twentysixty-digitizer-activator.php';
	Twentysixty_Digitizer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-twentysixty-digitizer-deactivator.php
 */
function deactivate_twentysixty_digitizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-twentysixty-digitizer-deactivator.php';
	Twentysixty_Digitizer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_twentysixty_digitizer' );
register_deactivation_hook( __FILE__, 'deactivate_twentysixty_digitizer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-twentysixty-digitizer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_twentysixty_digitizer() {

	$plugin = new Twentysixty_Digitizer( __FILE__ );
	$plugin->run();

}
run_twentysixty_digitizer();
