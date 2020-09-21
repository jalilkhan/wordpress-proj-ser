<?php
$custom_fields = bello_get_listing_fields();

$location_position = bello_get_listing_field_value ( 'location_position' );

if ( !empty($location_position) ){

	$map = explode(",", $location_position);
	$lat	= isset($map[0]) ? $map[0] : '40.604620';
	$lng	= isset($map[1]) ? $map[1] : '-74.161917';
	$zoom	= isset($map[2]) ? $map[2] : '14';
        
        $listing_pin_normal	= boldthemes_get_option( 'listing_pin_normal' ) != '' ? boldthemes_get_option( 'listing_pin_normal' ) : '';
	$listing_pin_selected	= boldthemes_get_option( 'listing_pin_selected' ) != '' ? boldthemes_get_option( 'listing_pin_selected' ) : '';
		
	?>
	
		<div class="widget_bt_bb_listing_marker_map_wrapper">
			<div id="bt_bb_listing_search_google_map"></div>
			<script>
			  var map;
			  function initMap() {
				map = new google.maps.Map(document.getElementById('bt_bb_listing_search_google_map'), {
				  zoom: <?php echo $zoom;?>,
				  center: new google.maps.LatLng(<?php echo $lat;?>, <?php echo $lng;?>),
				  gestureHandling: 'greedy',
				  mapTypeId: 'roadmap',
				  zoomControl: false,
				  mapTypeControl: false,
				  scaleControl: false,
				  rotateControl: false,
				  fullscreenControl: true,
				  fullscreenControlOptions: {
					position: google.maps.ControlPosition.LEFT_TOP
				  },
				  streetViewControl: true,
				  streetViewControlOptions: {
					position: google.maps.ControlPosition.LEFT_BOTTOM
				  }
				});

				var iconBase = '<?php echo $listing_pin_normal;?>';
				var icons = {
				  normal: {
					icon: iconBase
				  }
				};

				var features = [
				  {
					position: new google.maps.LatLng(<?php echo $lat;?>, <?php echo $lng;?>),
					type: 'normal'
				  }
				];

				// Create markers.
				features.forEach(function(feature) {
				  var marker = new google.maps.Marker({
					position: feature.position,
					icon: icons[feature.type].icon,
					map: map
				  });
				});
			  }
			</script>
			<script async defer
				src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDM_OQX_hwM0Zz1sctnTQlsYZCEc2mGNA&callback=initMap">
			</script>
		</div>
	

<?php } ?>