<?php

class Ple_Wsdc_Public {

	private $plugin_name;

	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ple-wsdc-public.css', array(), $this->version, 'all' );

	}

	public function enqueue_scripts() {

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

      return $p . "<br /><span style='font-size:80%' class='pro_price_extra_info'> Shipping Costs: " . get_woocommerce_currency_symbol() . $cost . "</span> <span class='delete_zip_code_cookie'> (X) </span>";

    } else {

      return $p;

    }

  }

}
