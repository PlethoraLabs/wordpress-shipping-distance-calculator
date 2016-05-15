<div class="wrap">

  <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

  <p>
    <a target="_blank" href="https://console.developers.google.com/flows/enableapi?apiid=distance_matrix_backend&keyType=SERVER_SIDE&reusekey=true">Click here to generate an API key.</a>
  </p>
  
  <form action="options.php" method="post">
  
    <?php
        settings_fields( $this->plugin_name );
        do_settings_sections( $this->plugin_name );
        $zip_code_destination = get_option( 'ple_wsdc_zipcode_destination', 'Not Set' ); 
    ?>

    <p>
      Place ID: <strong><?php echo $zip_code_destination['place_id']; ?></strong><br/>
      Formatted Address: <strong><?php echo $zip_code_destination['formatted_address']; ?></strong>
    </p>

    <script src="https://maps.googleapis.com/maps/api/js?callback=initMap" async defer></script>
    <div id="map" class="map" style="height:400px; width:400px; border: 1px solid black;"></div>
    <script>
      function initMap() {
        var mapDiv = document.getElementById('map');
        var map = new google.maps.Map(mapDiv, {
          center: {lat: 44.540, lng: -78.546},
          zoom: 12
        });
        /*** GEOCODER ***/
        var geocoder = new google.maps.Geocoder();
        var address = '<?php echo $zip_code_destination['formatted_address']; ?>';
        if ( geocoder ) {
            geocoder.geocode({
              'address': address
            }, function(results, status) {
              if (status == google.maps.GeocoderStatus.OK) {
                if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
                  map.setCenter(results[0].geometry.location);

                  var infowindow = new google.maps.InfoWindow({
                    content: '<b>' + address + '</b>',
                    size: new google.maps.Size(150, 50)
                  });

                  var marker = new google.maps.Marker({
                    position: results[0].geometry.location,
                    map: map,
                    title: address
                  });
                  google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map, marker);
                  });

                } else {
                  alert("No results found");
                }
              } else {
                alert("Geocode was not successful for the following reason: " + status);
              }
            });
        }
        /*** GEOCODER ***/
      }
    </script>

    <?php submit_button( __('Save Settings', $this->plugin_name), 'primary','submit' ); ?>
  
  </form>

</div>
