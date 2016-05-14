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
 * @package           Ple_Wsdc
 *
 * @wordpress-plugin
 * Plugin Name:       Wordpress Shipping Distance Calculator
 * Plugin URI:        https://github.com/PlethoraLabs/wordpress-shipping-distance-calculator
 * Description:       Calculate shipping distance costs using zip code
 * Version:           1.0.0
 * Author:            PlethoraThemes
 * Author URI:        http://plethorathemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ple-wsdc
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ple-wsdc-activator.php
 */
function activate_ple_wsdc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ple-wsdc-activator.php';
	Ple_Wsdc_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ple-wsdc-deactivator.php
 */
function deactivate_ple_wsdc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ple-wsdc-deactivator.php';
	Ple_Wsdc_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ple_wsdc' );
register_deactivation_hook( __FILE__, 'deactivate_ple_wsdc' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ple-wsdc.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ple_wsdc() {

	$plugin = new Ple_Wsdc();
	$plugin->run();

}
run_ple_wsdc();
