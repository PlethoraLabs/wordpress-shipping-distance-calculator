<?php

class Ple_Wsdc {

	protected $loader;

	protected $plugin_name;

	protected $version;

	public function __construct() {

		$this->plugin_name = 'ple-wsdc';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ple-wsdc-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ple-wsdc-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ple-wsdc-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ple-wsdc-public.php';
		/*** PLE_WSDC CODE ***/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ple-wsdc-helpers.php';

		$this->loader = new Ple_Wsdc_Loader();

	}

	private function set_locale() {

		$plugin_i18n = new Ple_Wsdc_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function define_admin_hooks() {

		$plugin_admin = new Ple_Wsdc_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		/*** PLE-WSDC CODE ***/

		// ADD SETTINGS LINK TO THE PLUGIN PAGE
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

		// ADD MENU ITEM(S)
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// REGISTER SETTINGS + ADD SETTINGS PAGE
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );

	  $this->loader->add_action('update_option_ple_wsdc_zipcode', $plugin_admin, 'get_retailer_place_id', 10, 2);
	  $this->loader->add_action('update_option_ple_wsdc_cc_tld', $plugin_admin, 'get_retailer_place_id', 10, 2);
	  $this->loader->add_action('update_option_ple_wsdc_google_api', $plugin_admin, 'get_retailer_place_id', 10, 2);

	}

	private function define_public_hooks() {

		$plugin_public = new Ple_Wsdc_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// PLE-WSDC CODE
	  $this->loader->add_filter( 'wp_footer', $plugin_public, 'plethora_add_markup_section', 2 );
	  $this->loader->add_filter( 'woocommerce_get_price_html', $plugin_public, 'add_custom_price_front', 10, 2 );

	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

}
