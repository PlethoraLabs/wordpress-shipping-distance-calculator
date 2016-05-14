<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://plethorathemes.com
 * @since      1.0.0
 *
 * @package    Ple_Wsdc
 * @subpackage Ple_Wsdc/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ple_Wsdc
 * @subpackage Ple_Wsdc/includes
 * @author     PlethoraThemes <plethorathemes@gmail.com>
 */
class Ple_Wsdc_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ple-wsdc',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
