<?php
/*
*
* callback for ajax loading listing with map
*/
if ( ! function_exists( 'boldthemes_listing_results_leafletmap_html' ) ) {
	function boldthemes_listing_results_leafletmap_html(  $listings, $limit ) { 
                
            	$listing_pin_normal         = boldthemes_get_option( 'listing_pin_normal' )     != '' ? boldthemes_get_option( 'listing_pin_normal' )   : '';
		$listing_pin_selected       = boldthemes_get_option( 'listing_pin_selected' )   != '' ? boldthemes_get_option( 'listing_pin_selected' ) : '';
		$listing_default_image      = boldthemes_get_option( 'listing_default_image' )  != '' ? boldthemes_get_option( 'listing_default_image' )
                        : BoldThemes_Customize_Default::$data['listing_default_image'];
		$custom_map_style           = boldthemes_get_option( 'custom_map_style' )       != '' ? boldthemes_get_option( 'custom_map_style' )     : '';
                $osm_map_style              = boldthemes_get_option( 'osm_map_style' )  != '' ?  boldthemes_get_option( 'osm_map_style' )     : '0';
                $custom_osm_map_style       = boldthemes_get_option( 'custom_osm_map_style' )  != '' ?  boldthemes_get_option( 'custom_osm_map_style' )     : '';                
		$map_center                 = boldthemes_get_listings_google_map_center($listings );
                $zoom                       = $map_center["zoom"];                               
                ?>
                <div id="bt_bb_listing_search_google_map"></div>
                <script>                    
                    function bt_bb_listing_gmap_init() { 
                        return true;
                    }
                    
                    var myMarkers = [];
                    var map;
                    var custom_style   = <?php echo $osm_map_style;?>;
                    var lat_center     = <?php echo esc_html($map_center["lat_center"]);?>;
                    var lng_center     = <?php echo esc_html($map_center["lng_center"]);?>;
                    var zoom           = <?php echo esc_html($zoom);?>;   
                    
                    var markerClusters = L.markerClusterGroup();
                    var lat_sum = 0;
                    var lng_sum = 0; 
                    <?php
                            $count = 0;
                            foreach ( $listings as $listing){
                                    $boldthemes_theme_listing_location_position	 = boldthemes_rwmb_meta('boldthemes_theme_listing-location_position', array(),$listing->ID);
                                    $boldthemes_theme_listing_location_position	 = explode(",", $boldthemes_theme_listing_location_position);
                                    $listing_pin_normal     = boldthemes_listing_category_image( $listing->ID , true );

                                    if ( isset($boldthemes_theme_listing_location_position[0]) && isset($boldthemes_theme_listing_location_position[1]) ) {
                                        ?>											
                                            /* marker */
                                            var lng = <?php echo esc_html($boldthemes_theme_listing_location_position[0]);?>;
                                            var lat = <?php echo esc_html($boldthemes_theme_listing_location_position[1]);?>;                                            
                                            lat_sum += lat;
                                            lng_sum += lng;
                                            
                                            var myIcon = L.icon({
                                                iconUrl: '<?php echo esc_html($listing_pin_normal);?>',
                                                iconRetinaUrl: '<?php echo esc_html($listing_pin_normal);?>',
                                                iconSize: [45, 58],
                                                iconAnchor: [9, 21],
                                                popupAnchor: [0, -14]
                                              });

                                            var m = L.marker( [ lng, lat ], { icon: myIcon, id: <?php echo json_encode( $listing->ID );?>,  lat: lat, lng:lng } )
                                                .on("click", openInfoWindow);

                                            markerClusters.addLayer( m ); 
                                        <?php
                                    }
                                    $count++;
                            }
                            wp_reset_postdata();
                        ?>
                        var n = <?php echo $count;?>;
                        if ( n > 0 ){
                            lng_center  =  lng_sum / n;    
                            lat_center   = lat_sum / n;
                        }                        
                            
                        map = L.map(document.getElementById( "bt_bb_listing_search_google_map" )).setView([lng_center, lat_center], zoom);                       
                        var tile_url =  map_leaflet_source_arr[custom_style];
                        var attribution = '';

                        L.tileLayer( tile_url, {
                            attribution: attribution,
                            subdomains: ['a','b','c']
                        }).addTo( map );

                        map.addLayer( markerClusters );
                        
                        jQuery('.leaflet-control-attribution').hide();
                        
                        function openInfoWindow(e){
                            var attributes = e.target.options;
                            var id = attributes.id;
                            jQuery.ajax({													
                                     url: '<?php echo admin_url( 'admin-ajax.php' );?>',
                                     type: 'post',
                                     data: {
                                             action:        'bt_get_listing_marker_details_action',
                                             listing_id:     id,
                                     },
                                     success: function( response ) {
                                             jQuery('#bt_bb_listing_marker_details_container').html( response );
                                     }
                             })
                        }  
                </script>
                <?php
            
        }
}