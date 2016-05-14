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
 * @package    Wordpress_Shipping_Distance_Calculator
 * @subpackage Wordpress_Shipping_Distance_Calculator/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wordpress_Shipping_Distance_Calculator
 * @subpackage Wordpress_Shipping_Distance_Calculator/includes
 * @author     PlethoraThemes <plethorathemes@gmail.com>
 */
class Wordpress_Shipping_Distance_Calculator_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wordpress-shipping-distance-calculator',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
