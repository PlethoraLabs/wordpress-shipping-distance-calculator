<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://plethorathemes.com
 * @since             1.0.0
 * @package           Wordpress_Shipping_Distance_Calculator
 *
 * @wordpress-plugin
 * Plugin Name:       Shipping Distance Calculator
 * Plugin URI:        https://github.com/PlethoraLabs/wordpress-shipping-distance-calculator
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            PlethoraThemes
 * Author URI:        http://plethorathemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wordpress-shipping-distance-calculator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wordpress-shipping-distance-calculator-activator.php
 */
function activate_wordpress_shipping_distance_calculator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wordpress-shipping-distance-calculator-activator.php';
	Wordpress_Shipping_Distance_Calculator_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wordpress-shipping-distance-calculator-deactivator.php
 */
function deactivate_wordpress_shipping_distance_calculator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wordpress-shipping-distance-calculator-deactivator.php';
	Wordpress_Shipping_Distance_Calculator_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wordpress_shipping_distance_calculator' );
register_deactivation_hook( __FILE__, 'deactivate_wordpress_shipping_distance_calculator' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wordpress-shipping-distance-calculator.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wordpress_shipping_distance_calculator() {

	$plugin = new Wordpress_Shipping_Distance_Calculator();
	$plugin->run();

}
run_wordpress_shipping_distance_calculator();
