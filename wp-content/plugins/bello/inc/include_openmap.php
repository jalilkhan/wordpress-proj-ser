<?php

/*
*
* callback for ajax loading listing with map
*/
if ( ! function_exists( 'boldthemes_listing_results_openmap_html' ) ) {
	function boldthemes_listing_results_openmap_html(  $listings, $limit ) {
            
            	$listing_pin_normal         = boldthemes_get_option( 'listing_pin_normal' )     != '' ? boldthemes_get_option( 'listing_pin_normal' )   : '';
		$listing_pin_selected       = boldthemes_get_option( 'listing_pin_selected' )   != '' ? boldthemes_get_option( 'listing_pin_selected' ) : '';
		$listing_default_image      = boldthemes_get_option( 'listing_default_image' )  != '' ? boldthemes_get_option( 'listing_default_image' )
                        : BoldThemes_Customize_Default::$data['listing_default_image'];
		$custom_map_style           = boldthemes_get_option( 'custom_map_style' )       != '' ? boldthemes_get_option( 'custom_map_style' )     : '';
                $osm_map_style              = boldthemes_get_option( 'osm_map_style' )  != '' ?  boldthemes_get_option( 'osm_map_style' )     : '0';
                $custom_osm_map_style       = boldthemes_get_option( 'custom_osm_map_style' )  != '' ?  boldthemes_get_option( 'custom_osm_map_style' )     : '';
                
		$map_center                 = boldthemes_get_listings_google_map_center($listings );

                $bound  = count($listings) > 0 ? 1 : 0;
                $zoom   = $map_center["zoom"];
               
                
                ?>
                    <div id="bt_bb_listing_search_google_map"></div>
                    <script>
                          function bt_bb_listing_gmap_init() { 
                              return true;
                          }
                            
                          var myMarkers = [];
                          var myMarkersOriginal = [];
			  var map;
			  var custom_style = '';
			  var markerClusterer = null;                          
                         
                          var mapOptions = {controls: [
                                    new OpenLayers.Control.Navigation(),             
                                    new OpenLayers.Control.Zoom()
                          ]};
                          
                          map = new OpenLayers.Map("bt_bb_listing_search_google_map", mapOptions);
                          var style = setVectorSource( <?php echo $osm_map_style;?> );  
                          <?php  if ( $osm_map_style == 0 ){
                                    if ( $custom_osm_map_style != '' ){ 
                                      ?>
                                           style = [<?php echo $custom_osm_map_style;?> ];
                                       <?php
                                    }
                                 } 
                          ?>
                               
                                var g = new OpenLayers.Layer.OSM("Simple OSM Map", style, {layers: 'basic'});
                                map.addLayers([g]);   
                            
                                var lat     = <?php echo esc_html($map_center["lat_center"]);?>;
                                var lon     = <?php echo esc_html($map_center["lng_center"]);?>;
                                var zoom    = <?php echo esc_html($zoom);?>;                            

                                var fromProjection = new OpenLayers.Projection("EPSG:4326"); 
                                var toProjection   = new OpenLayers.Projection("EPSG:900913");
                                var lonLat         = new OpenLayers.LonLat(lon, lat).transform( fromProjection, toProjection);

                                map.setCenter (lonLat, zoom);

                                var features = new Array(<?php echo count($listings);?>);
                            <?php
                                $count = 0;
                                foreach ( $listings as $listing){
                                        $boldthemes_theme_listing_location_position	 = boldthemes_rwmb_meta('boldthemes_theme_listing-location_position', array(),$listing->ID);
                                        $boldthemes_theme_listing_location_position	 = explode(",", $boldthemes_theme_listing_location_position);

                                        $listing_pin_normal     = boldthemes_listing_category_image( $listing->ID , true );
                                        $listing_pin_selected   = boldthemes_listing_category_image( $listing->ID , true, true );

                                        if ( isset($boldthemes_theme_listing_location_position[0]) && isset($boldthemes_theme_listing_location_position[1]) ) {
                                            ?>											
                                                /* marker */
                                                var lonMarker = <?php echo esc_html($boldthemes_theme_listing_location_position[0]);?>;
                                                var latMarker = <?php echo esc_html($boldthemes_theme_listing_location_position[1]);?>;
                                                features[<?php echo $count;?>] =  new OpenLayers.Feature.Vector(
                                                    new OpenLayers.Geometry.Point( latMarker, lonMarker ).transform(fromProjection, toProjection),
                                                    {
                                                        id:'<?php echo json_encode( $listing->ID );?>', 
                                                        description:<?php echo json_encode( $listing->post_title);?>
                                                    } ,                                                    
                                                    {
                                                        title: <?php echo json_encode( $listing->post_title);?>,
                                                        cursor:'pointer', 
                                                        externalGraphic: '<?php echo esc_html($listing_pin_normal);?>', 
                                                        graphicHeight: 58, 
                                                        graphicWidth: 45, 
                                                        graphicXOffset:-12, 
                                                        graphicYOffset:-25 
                                                    }
                                                ); 
                                            <?php
                                        }
                                        $count++;
                                }
                                wp_reset_postdata();
                            ?>
                                var defaultStyle = new OpenLayers.Style({'cursor': 'pointer' });                              
                                var selectStyle = new OpenLayers.Style({'cursor': ''});                              
                                var styleMap  = new OpenLayers.StyleMap({
                                   'default': defaultStyle,
                                   'select': selectStyle
                                });
                             
                                var strategies = [];
                                strategies.push(new OpenLayers.Strategy.Cluster({distance: parseInt(200)} ));  
                                 
                                var vectorLayer = new OpenLayers.Layer.Vector("VectorLayer", {
                                    styleMap: styleMap,
                                    strategies: []
                                });
                            
                                vectorLayer.addFeatures(features);     
                                map.addLayer(vectorLayer);
                           
                                var controls = {
                                     selector: new OpenLayers.Control.SelectFeature(vectorLayer, {
                                         onSelect: openInfoWindow
                                     })
                                };                           
                                map.addControl(controls['selector']);
                                controls['selector'].activate();
                            
                                function openInfoWindow(feature){
                                    var myMarkers = [];
                                    jQuery.ajax({													
                                             url: '<?php echo admin_url( 'admin-ajax.php' );?>',
                                             type: 'post',
                                             data: {
                                                     action:	'bt_get_listing_marker_details_action',
                                                     listing_id:	feature.attributes.id,
                                             },
                                             success: function( response ) {
                                                     jQuery('#bt_bb_listing_marker_details_container').html( response );
                                             }
                                     })
                                }  
                                
                                function setVectorSource( custom_style ) { 
                                    var style = map_openmap_source_arr[custom_style];
                                    return style;
                                }
                    </script>
                <?php
            
        }
}

