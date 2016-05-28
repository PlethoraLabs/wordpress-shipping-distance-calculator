<?php

class Ple_Wsdc_Helpers {

  /*** GOOGLE API + MAIN FUNCTIONALITY ***/

  public static $option_name = 'ple_wsdc';

  // Convert Zip Code to Place ID (https://developers.google.com/places/place-id)
  public static function zip_code_to_place_id($zipCode,$region){

    // Geocoding API
    // https://developers.google.com/maps/documentation/geocoding/intro

    $google_api = get_option( self::$option_name . '_google_api' );

    $response = wp_remote_get( "https://maps.googleapis.com/maps/api/geocode/json?address=".$zipCode."&region=".$region."&key=" . $google_api );

    if( is_array($response) ) {
      $json_res = json_decode($response['body']);
      if ( $json_res->status == "OK" ){
        return array( 'place_id' => $json_res->results[0]->place_id, 'formatted_address' => $json_res->results[0]->formatted_address );
      } 
    }

  }

  public static function get_distance($origin_place_id, $destination_place_id){

    // Distance Matrix API
    // https://developers.google.com/maps/documentation/distance-matrix/

    $google_api = get_option( self::$option_name . '_google_api' );

    $response = wp_remote_get( "https://maps.googleapis.com/maps/api/distancematrix/json?origins=place_id:".$origin_place_id."&destinations=place_id:".$destination_place_id['place_id']."&key=" . $google_api );

    if( is_array($response) ) {
      $json_res = json_decode($response['body']);
      if ( $json_res->status == "OK" ){
        return $json_res->rows[0]->elements[0]->distance->value;  // Get distance in kilometers
      } 
    }

  }

  public static function calc_distance_cost($meters){

    $km = round($meters/1000);

    $cost = NULL; 
    $cost_table = array(
      "2"       => 0,   // 0~2 km
      "10"      => 10,  // 2~10 km
      "20"      => 15,  // 10~20 km
      "50"      => 25   // 10~50 km
    );

    foreach ($cost_table as $key => $value) {
      if ( $km <= intval($key) ){
        $cost = $value;
        break; 
      } 
    }

    if ( is_null($cost) ){ $cost = end($cost_table); }

    return $cost; // Debug output $cost . " (distance: ".$km.")";

  }

  public static function get_shortest_distance(){

    /* GET SHORTEST DISTANCE FROM ALTERNATIVES */
    /*
    
    (1) request the directions (with alternatives)
    
    $routes = json_decode(file_get_contents('http://maps.googleapis.com/maps/api/directions/json?origin=bomerstraat%2018,%20peer&destination=kievitwijk%2028,%20helchteren&alternatives=true&sensor=false'))->routes;
    
    (2) sort the routes based on the distance
    
    usort($routes,create_function('$a,$b','return intval($a->legs[0]->distance->value) - intval($b->legs[0]->distance->value);'));
    
    (3) print the shortest distance
    
    echo $routes[0]->legs[0]->distance->text;//returns 9.0 km
    
    */    
  }


}