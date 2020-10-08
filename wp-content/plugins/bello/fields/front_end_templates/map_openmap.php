<?php
if ( isset($field) ) {
       if ( isset($field['name']) && isset($field['value']) ) {
                
                $default_lat = boldthemes_get_option( 'listing_search_distance_lat' )  != '' ? boldthemes_get_option( 'listing_search_distance_lat' ) : '0';
                $default_lng = boldthemes_get_option( 'listing_search_distance_lng' )  != '' ? boldthemes_get_option( 'listing_search_distance_lng' ) : '0';
        
		$map_str	= $field['value'][0];
		$map_arr	= explode( ",", $map_str );
		$lat		= isset( $map_arr[0] ) ? $map_arr[0] : $default_lat;
		$lng		= isset( $map_arr[1] ) ? $map_arr[1] : $default_lng;
		$zoom		= '12';
               
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
                            var mapOptions = {controls: [
                                    new OpenLayers.Control.Navigation(),             
                                    new OpenLayers.Control.Zoom()
                            ]};
                            var map = new OpenLayers.Map("bt_bb_listing_search_google_map", mapOptions);
                            var style = setVectorSource( <?php echo $osm_map_style;?> );
                            <?php 
                                if ( $osm_map_style == 0 ){
                                    if ( $custom_osm_map_style != '' ){
                                        ?>
                                        style = [<?php echo $custom_osm_map_style;?>];  
                                        <?php
                                    }
                                } 
                            ?>                           
                            var g = new OpenLayers.Layer.OSM("Simple OSM Map", style, {layers: 'basic'});
                            map.addLayers([g]);   
                            
                            var lat     = <?php echo $lat;?>;
                            var lon     = <?php echo $lng;?>;
                            var zoom    = <?php echo $zoom;?>;                            

                            var fromProjection = new OpenLayers.Projection("EPSG:4326");  
                            var toProjection   = new OpenLayers.Projection("EPSG:900913");
                            var lonLat         = new OpenLayers.LonLat(lon, lat).transform( fromProjection, toProjection);

                            map.setCenter (lonLat, zoom);
                        
                            var defaultStyle = new OpenLayers.Style({ 'cursor': 'pointer'});
                            var selectStyle = new OpenLayers.Style({'cursor': ''});

                            var styleMap  = new OpenLayers.StyleMap({
                                'default': defaultStyle,
                                'select': selectStyle
                             });

                            var vectorLayer = new OpenLayers.Layer.Vector("VectorLayer", {
                                styleMap: styleMap,
                                strategies: []
                            });                            
                        
                            var features = new Array(1);

                            var lonMarker = <?php echo $lng;?>;
                            var latMarker = <?php echo $lat;?>;
                            features[0] =  new OpenLayers.Feature.Vector(
                                new OpenLayers.Geometry.Point( lonMarker, latMarker  ).transform(fromProjection, toProjection),
                                {
                                    id:'<?php echo get_the_ID();?>',
                                    description:<?php echo json_encode(get_the_title());?>
                                } ,                                                    
                                { 
                                    title: "Open in Google Map: " + <?php echo json_encode(get_the_title());?>,
                                    cursor:'pointer', 
                                    externalGraphic: '<?php echo esc_html($listing_pin_normal);?>', 
                                    graphicHeight: 58, 
                                    graphicWidth: 45, 
                                    graphicXOffset:-12, 
                                    graphicYOffset:-25 
                                }
                            ); 
                
                            vectorLayer.addFeatures(features);     
                            map.addLayer(vectorLayer);
                        
                            var controls = {
                                  selector: new OpenLayers.Control.SelectFeature(vectorLayer, {
                                      onSelect: openGetDirectionLink
                                  })
                            };
                            map.addControl(controls['selector']);
                            controls['selector'].activate();

                            var NavigationControls = map.getControlsByClass('OpenLayers.Control.Navigation') , i;
                            for ( i = 0; i < NavigationControls.length; i++ ) {
                                NavigationControls[i].disableZoomWheel();
                            }
                        
                            function openGetDirectionLink(features){
                                var link = "https://www.google.com/maps/dir//<?php echo $lat;?>,<?php echo $lng;?>/@<?php echo $lat;?>,<?php echo $lng;?>,<?php echo $zoom;?>z";
                                window.location.href = link;
                            }
                            
                            function setVectorSource( custom_style ) { 
                                var style = map_openmap_source_arr[custom_style];
                                return style;
                            }
                </script>
              <?php
       }
}


