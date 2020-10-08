<?php
if ( isset($field) ) {
       if ( isset($field['name']) && isset($field['value']) ) {   
                if ( ! function_exists( 'bt_include_scripts_leaflet' ) ) {
                    return false;
                }
                $default_lat = boldthemes_get_option( 'listing_search_distance_lat' )  != '' ? boldthemes_get_option( 'listing_search_distance_lat' ) : '0';
                $default_lng = boldthemes_get_option( 'listing_search_distance_lng' )  != '' ? boldthemes_get_option( 'listing_search_distance_lng' ) : '0';
        
		$map_str	= isset($field['value'][0]) ? $field['value'][0] : '';
		$map_arr	= explode( ",", $map_str );
		$lat		= isset( $map_arr[0] ) ? $map_arr[0] : $default_lat;
		$lng		= isset( $map_arr[1] ) ? $map_arr[1] : $default_lng;
		$zoom		= '8';
               
		$custom_fields = get_post_custom( get_the_ID());
		$listing_fields = bello_get_listing_fields( array( 'listing_id' => get_the_ID() ) );
                
		$boldthemes_theme_listing_contact_phone = '';	
		$boldthemes_theme_listing_contact_phone_link = '';
		$boldthemes_theme_listing_contact_mobile = '';	
		$boldthemes_theme_listing_contact_mobile_link = '';
                
		if ( isset( $custom_fields['boldthemes_theme_listing-contact_phone'] ) ) {
			if ( isset( $listing_fields['contact_phone'] ) && bello_field_in_packages( $listing_fields['contact_phone'], get_the_ID()) ) {
				$boldthemes_theme_listing_contact_phone = boldthemes_rwmb_meta('boldthemes_theme_listing-contact_phone', array(), get_the_ID() );
				$boldthemes_theme_listing_contact_phone_link = bt_format_phone_number( $boldthemes_theme_listing_contact_phone );
			}
		}  
		if ( isset( $custom_fields['boldthemes_theme_listing-contact_mobile'] ) && isset( $listing_fields['contact_mobile'] ) ) {
			if ( bello_field_in_packages( $listing_fields['contact_mobile'], get_the_ID()) ) {
				$boldthemes_theme_listing_contact_mobile = boldthemes_rwmb_meta('boldthemes_theme_listing-contact_mobile', array(), get_the_ID() );
				$boldthemes_theme_listing_contact_mobile_link = bt_format_phone_number( $boldthemes_theme_listing_contact_mobile );
			}
		}  
		if ( $boldthemes_theme_listing_contact_phone == '' ) {
			$boldthemes_theme_listing_contact_phone = $boldthemes_theme_listing_contact_mobile;
			$boldthemes_theme_listing_contact_phone_link = $boldthemes_theme_listing_contact_mobile_link;
		}
                
                $listing_pin_normal     = boldthemes_listing_category_image( get_the_ID(), true );
                $listing_pin_selected   = boldthemes_listing_category_image( get_the_ID(), true, true );
                $custom_map_style       = boldthemes_get_option( 'custom_map_style' ) != '' ? boldthemes_get_option( 'custom_map_style' )     : '';
                $osm_map_style          = boldthemes_get_option( 'osm_map_style' )  != '' ?  boldthemes_get_option( 'osm_map_style' )     : '0';
                $custom_osm_map_style   = boldthemes_get_option( 'custom_osm_map_style' )  != '' ?  boldthemes_get_option( 'custom_osm_map_style' )     : '';
                ?>
                <div class="widget_bt_bb_listing_marker_map <?php echo $field['group'];?>">
			<div class="widget_bt_bb_listing_marker_map_wrapper">
				<div id="bt_bb_listing_search_google_map"></div>
			</div>	
		</div>
                <script> 
                    var lng     = <?php echo $lng;?>;
                    var lat     = <?php echo $lat;?>;                    
                    var zoom    = <?php echo $zoom;?>; 
                    var custom_style = <?php echo $osm_map_style;?>;
                    
                    var map = L.map(document.getElementById( "bt_bb_listing_search_google_map" )).setView([lat, lng], zoom);
                    
                    var tile_url =  map_leaflet_source_arr[custom_style];
                    var attribution = '';
                    L.tileLayer( tile_url, {
                            attribution: attribution,
                            subdomains: ['a','b','c']
                    }).addTo( map );
                    
                    var markerClusters = L.markerClusterGroup();
                    
                    var myIcon = L.icon({
                        iconUrl: '<?php echo esc_html($listing_pin_normal);?>',
                        iconRetinaUrl: '<?php echo esc_html($listing_pin_normal);?>',
                        iconSize: [45, 58],
                        iconAnchor: [9, 21],
                        popupAnchor: [0, -14]
                   });

                    var m = L.marker( [ lat, lng ], { icon: myIcon, id: <?php echo json_encode( get_the_ID() );?>,  lat: lat, lng:lng, zoom: zoom } )
                        .on("click", openGetDirectionLink);
                    m.addTo(map);
                    
                    jQuery('.leaflet-control-attribution').hide();
                     
                    function openGetDirectionLink(e){
                          var attributes = e.target.options;
                          var id = attributes.id;
                          var lat = attributes.lat;
                          var lng = attributes.lng;
                          var zoom = attributes.zoom;
                          var link = "https://www.google.com/maps/dir//" + lat + "," + lng + "/@" + lat + "," + lng + "," + zoom + "z";
                          window.location.href = link;
                   }
                    
                </script>                
         <?php
       }
}
