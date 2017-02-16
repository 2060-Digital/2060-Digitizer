<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://2060digital.com
 * @since      1.0.0
 *
 * @package    Twentysixty_Digitizer
 * @subpackage Twentysixty_Digitizer/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Twentysixty_Digitizer
 * @subpackage Twentysixty_Digitizer/includes
 * @author     Ryan Benhase <rbenhase@2060digital.com>
 */
class Twentysixty_Digitizer_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'twentysixty-digitizer',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
