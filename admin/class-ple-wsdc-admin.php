<?php

class Ple_Wsdc_Admin {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ple-wsdc-admin.css', array(), $this->version, 'all' );

	}

	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ple-wsdc-admin.js', array( 'jquery' ), $this->version, false );

	}

	/*** PLE-WSDC CODE ***/

	private $option_name = 'ple_wsdc';	

	public function add_plugin_admin_menu() {

    add_options_page( 'Shipping Distance Calculator Settings', 'Shipping Calculator', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
    );

	}

	// Add settings action link to the plugins page
	public function add_action_links( $links ) {

	   $settings_link = array(
	    '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
	   );
	   return array_merge(  $settings_link, $links );

	}

	public function register_settings() {

		// Register a settings section
		add_settings_section(
			$this->option_name . '_general',
			__( 'General', '' ),
			array( $this, $this->option_name . '_general_cb' ),
			$this->plugin_name
		);

		// Settings API -> Register CC TLD field
		add_settings_field(
			$this->option_name . '_cc_tld',
			__( 'Country Code', '' ),
			array( $this, 'render_tld_input' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_cc_tld' )
		);

		// Settings API -> Register Zip Code field
		add_settings_field(
			$this->option_name . '_zipcode',
			__( 'Zip Code', '' ),
			array( $this, $this->option_name . '_zipcode_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_zipcode' )
		);

		// Settings API -> Register Google API Key field 
		add_settings_field(
			$this->option_name . '_google_api',
			__( 'Google API Key', '' ),
			array( $this, $this->option_name . '_google_api_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_google_api' )
		);

		// Settings API -> Register checkbox field
		add_settings_field(
			$this->option_name . '_position',
			__( 'Text position', '' ),
			array( $this, $this->option_name . '_position_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_position' )
		);

		// REGISTER SETTINGS
		register_setting( $this->plugin_name, $this->option_name . '_cc_tld', array( $this, $this->option_name . '_sanitize_cc_tld' ) );
		register_setting( $this->plugin_name, $this->option_name . '_zipcode', array( $this, $this->option_name . '_sanitize_zipcode' ) ); 
		register_setting( $this->plugin_name, $this->option_name . '_google_api', array( $this, $this->option_name . '_sanitize_google_api' ) );
		register_setting( $this->plugin_name, $this->option_name . '_position', array( $this, $this->option_name . '_sanitize_position' ) );
	}

	// RENDER GENERAL SECTION TEXT
	public function ple_wsdc_general_cb() {
		echo '<p>' . __( 'Please change the settings accordingly.', 'outdated-notice' ) . '</p>';
	}

	// RENDER RADIO INPUT FIELD  
	public function ple_wsdc_position_cb() {
		$position = get_option( $this->option_name . '_position' );
		?>
			<fieldset>
				<label>
					<input type="radio" name="<?php echo $this->option_name . '_position' ?>" id="<?php echo $this->option_name . '_position' ?>" value="before" <?php checked( $position, 'before' ); ?>>
					<?php _e( 'Before the content', 'outdated-notice' ); ?>
				</label>
				<br>
				<label>
					<input type="radio" name="<?php echo $this->option_name . '_position' ?>" value="after" <?php checked( $position, 'after' ); ?>>
					<?php _e( 'After the content', 'outdated-notice' ); ?>
				</label>
			</fieldset>
		<?php
	}

	// RENDER ZIP CODE INPUT 
	public function ple_wsdc_zipcode_cb() {
		$zipcode = get_option( $this->option_name . '_zipcode' );
		echo '<input type="text" name="' . $this->option_name . '_zipcode' . '" id="' . $this->option_name . '_zipcode' . '" value="' . $zipcode . '"> ';
	}	

	public function ple_wsdc_google_api_cb() {
		$google_api = get_option( $this->option_name . '_google_api' );
		echo '<input style="width:320px;" type="text" name="' . $this->option_name . '_google_api' . '" id="' . $this->option_name . '_google_api' . '" value="' . $google_api . '"> ';
	}	

	// RENDER SELECT INPUT 
	public function render_tld_input() {
		$country_code  = get_option( $this->option_name . '_cc_tld' );
		$file          = file_get_contents( WP_PLUGIN_DIR . '/wordpress-shipping-distance-calculator/includes/cctld.json' ); 
		$country_codes = json_decode($file);
		echo '<select name="' . $this->option_name . '_cc_tld' . '" id="' . $this->option_name . '_cc_tld' . '">';
		foreach ( $country_codes as $value) {
			$selected = ( $country_code == $value[1] )? 'selected="selected"' : '';
			echo '<option value="'. $value[1].'" ' . $selected . '>'.$value[0] . " (" . $value[1] . ")" .'</option>';
		} 
		echo '</select>';

	}

	public function display_plugin_setup_page() {
	  include_once( plugin_dir_path( __FILE__ ) . 'partials/ple-wsdc-admin-display.php' );
	}

	/*** SANITIZE FUNCTIONS ***/

	public function ple_wsdc_sanitize_position( $position ) {
		if ( in_array( $position, array( 'before', 'after' ), true ) ) { return $position; }
	}

	public function ple_wsdc_sanitize_cc_tld( $country_code ) {
		if ( preg_match('/[A-Z]{2}/', $country_code) == 1 ) {
			return $country_code;
		} else {
			return "US";
		}
	}

	public function ple_wsdc_sanitize_zipcode( $zipcode ) {
		return sanitize_text_field( $zipcode );
	}

	public function ple_wsdc_sanitize_google_api( $google_api ) {
		return sanitize_text_field( $google_api );
	}

	public function get_retailer_place_id( $old_value, $new_value ){

		$zipcode              = get_option( $this->option_name . '_zipcode' );
		$country_code         = get_option( $this->option_name . '_cc_tld' );
		$destination_place_id = Ple_Wsdc_Helpers::zip_code_to_place_id( $zipcode, $country_code );
    update_option( 'ple_wsdc_zipcode_destination', $destination_place_id );

	}

}
