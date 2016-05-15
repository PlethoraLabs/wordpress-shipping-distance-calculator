<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://plethorathemes.com
 * @since      1.0.0
 *
 * @package    Ple_Wsdc
 * @subpackage Ple_Wsdc/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ple_Wsdc
 * @subpackage Ple_Wsdc/public
 * @author     PlethoraThemes <plethorathemes@gmail.com>
 */
class Ple_Wsdc_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ple_Wsdc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ple_Wsdc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ple-wsdc-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ple_Wsdc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ple_Wsdc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		/*** PLE-WSDC CODE ***/
		wp_enqueue_script( 'js-cookie', plugin_dir_url( __FILE__ ) . 'js/js.cookie.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ple-wsdc-public.js', array( 'jquery' ), $this->version, false );

	}

	/*** PLE-WSDC CODE ***/

  // ADD ZIP CODE POKE SECTION 
  public function plethora_add_markup_section($content) {

    if ( is_product() || is_shop() || is_product_category() ) {

      $content = 

      '<div id="zipCodeWrapper">
        <a href="#" id="zipCodeDismiss">Close</a>
        <div class="plethora-zip-code-input">
          <span>Please enter your zip code to receive shipping costs:</span>
          <input id="zipCode" type="text" />
          <input id="zipCodeSubmitter" type="submit" value="Submit" />
        </div>
      </div>';

    } 

    echo $content;

  }

  // DISPLAY SHIPPING COSTS 
  public function add_custom_price_front($p, $obj) {

    // $post_id = $obj->post->ID;

    if ( isset($_COOKIE['zipCode']) ){

      $zipCode = $_COOKIE['zipCode'];

      // Do we already have this zip code stored?
      if ( get_option( 'ple_wsdc_zipcode_' . $zipCode ) ){

        $cost = get_option( 'ple_wsdc_zipcode_' . $zipCode );
        $cost = $cost['cost'];

      // Let's get the distance using Google API
      } else {

        $place_id = Ple_Wsdc_Helpers::zip_code_to_place_id( $zipCode, get_option( Ple_Wsdc_Helpers::$option_name . '_cc_tld' ) );
        $distance = Ple_Wsdc_Helpers::get_distance( $place_id['place_id'], get_option('ple_wsdc_zipcode_destination'));
        $cost     = Ple_Wsdc_Helpers::calc_distance_cost($distance);
        update_option( 'ple_wsdc_zipcode_' . $zipCode, array( 'cost' => $cost, 'address' => $place_id['formatted_address']  ), '', 'yes' );

      }

      return $p . "<br /><span style='font-size:80%' class='pro_price_extra_info'> Shipping Costs: " . get_woocommerce_currency_symbol() . $cost . "</span>";

    } else {

      return $p;

    }

  }

}
