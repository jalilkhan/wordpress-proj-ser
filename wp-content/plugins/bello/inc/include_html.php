<?php
/*
*
* Generate HTML for listing box
*/
if ( ! function_exists( 'boldthemes_listing_box_html' ) ) {
	function boldthemes_listing_box_html( $listings, $limit = 0, $get_post = 0, $listings_paged = 1, $ajax = 0 ) { 
            
              if ( empty($listings) ){
                    ?>
                        <div class="bt_bb_listing_box_empty">
                            <p><?php esc_html_e( 'Sorry! There are no listings matching your search.', 'bt_plugin' )?></p>
                            <p><?php esc_html_e( 'Try changing your search filters', 'bt_plugin' )?></p>
                        </div>
                    <?php
                }
                // banner in result list
                $listing_grid_listings_pagination = boldthemes_get_option( 'listing_grid_listings_pagination' ) != '' ?  boldthemes_get_option( 'listing_grid_listings_pagination' ) : 'paged';
		if ( is_active_sidebar( 'listing_with_map_banner' ) && BoldThemesFrameworkTemplate::$listing_list_view  == 'standard' ) { 
                    if ( $listing_grid_listings_pagination == 'loadmore' ) {
                        if ( $listings_paged == 1) {
                             ?>				
                                <div class="bt_bb_listing_box listing_without_map_banner">
                                        <div class="bt_bb_listing_photo">					
                                                <?php dynamic_sidebar( 'listing_without_map_banner' );?>
                                        </div>
                                </div>
                            <?php 
                        }
                    } else {
                        ?>				
			<div class="bt_bb_listing_box listing_with_map_banner">
				<div class="bt_bb_listing_photo">					
					<?php dynamic_sidebar( 'listing_with_map_banner' );?>
				</div>
			</div>
                        <?php 
                    }                    
		} 
               
		if ( is_active_sidebar( 'listing_without_map_banner' ) && BoldThemesFrameworkTemplate::$listing_list_view  == 'without_map' ) { 
                    if ( $listing_grid_listings_pagination == 'loadmore' ) {
                        if ( $listings_paged == 1) {
                             ?>				
                                <div class="bt_bb_listing_box listing_without_map_banner">
                                        <div class="bt_bb_listing_photo">					
                                                <?php dynamic_sidebar( 'listing_without_map_banner' );?>
                                        </div>
                                </div>
                            <?php 
                        }
                    }else{
                         ?>				
                            <div class="bt_bb_listing_box listing_without_map_banner">
                                    <div class="bt_bb_listing_photo">					
                                            <?php dynamic_sidebar( 'listing_without_map_banner' );?>
                                    </div>
                            </div>
                        <?php 
                    }                   
		}
                
                // listings
		$count = 1;
		foreach ( $listings as $listing){
                   
			if ( $get_post > 0 ){
				$listing	= get_post($listing["id"]);
			}
			$custom_fields		= get_post_custom( $listing->ID );
			$listing_fields		= bello_get_listing_fields( array( 'listing_id' => $listing->ID ) );
			$listing_categories     = get_the_terms( $listing->ID, 'listing-category' );
			$listing_region		= get_the_terms( $listing->ID, 'listing-region' );
			$listing_region		= isset($listing_region[0]) ? $listing_region[0] : '';

			$boldthemes_theme_listing_contact_phone		= '';	
			$boldthemes_theme_listing_contact_phone_link	= '';			
			if ( isset($custom_fields['boldthemes_theme_listing-contact_phone']) && isset($listing_fields['contact_phone']) ){		
				if ( bello_field_in_packages( $listing_fields['contact_phone'], $listing->ID) ) {
					$boldthemes_theme_listing_contact_phone	= boldthemes_rwmb_meta('boldthemes_theme_listing-contact_phone', array(), $listing->ID );
                                        $boldthemes_theme_listing_contact_phone_link	= bt_format_phone_number( $boldthemes_theme_listing_contact_phone );
                                }
			}
                        
                        $boldthemes_theme_listing_contact_mobile	= '';	
			$boldthemes_theme_listing_contact_mobile_link	= '';			
			if ( isset($custom_fields['boldthemes_theme_listing-contact_mobile']) && isset($listing_fields['contact_mobile']) ){		
				if ( bello_field_in_packages( $listing_fields['contact_mobile'], $listing->ID) ) {
					$boldthemes_theme_listing_contact_mobile	= boldthemes_rwmb_meta('boldthemes_theme_listing-contact_mobile', array(), $listing->ID );
                                        $boldthemes_theme_listing_contact_mobile_link	= bt_format_phone_number( $boldthemes_theme_listing_contact_mobile );
                                }
			}
                        
                        if ( $boldthemes_theme_listing_contact_phone == '' ){
                            $boldthemes_theme_listing_contact_phone         = $boldthemes_theme_listing_contact_mobile;
                            $boldthemes_theme_listing_contact_phone_link    = $boldthemes_theme_listing_contact_mobile_link;
                        }
			
                        $boldthemes_theme_listing_location_position = '';
			if ( isset($custom_fields['boldthemes_theme_listing-location_position']) && isset($listing_fields['location_position']) ){	
				if ( bello_field_in_packages( $listing_fields['location_position'], $listing->ID) ) {	
					$boldthemes_theme_listing_location_position = boldthemes_rwmb_meta('boldthemes_theme_listing-location_position', array(), $listing->ID );
				}
			}                        
                        $map_arr	= explode(",", $boldthemes_theme_listing_location_position);
                       
			$latitudeTo	= isset($map_arr[0]) ? $map_arr[0] : '';
			$longitudeTo	= isset($map_arr[1]) ? $map_arr[1] : '';
			$zoom		= isset($map_arr[2]) ? $map_arr[2] : '15';
			
                        $boldthemes_theme_listing_working_times = '';
			if ( isset($custom_fields['boldthemes_theme_listing-working_time']) && isset($listing_fields['working_time']) ){	
				if ( bello_field_in_packages( $listing_fields['working_time'], $listing->ID) ) {	
					$boldthemes_theme_listing_working_times	= boldthemes_rwmb_meta('boldthemes_theme_listing-working_time', array(), $listing->ID);
				}
			}
                        $open_hours         = bt_open_hours( $listing->ID );
			$current_open_hours = bt_open_hours_current_day( $listing->ID );
                        
                        $listing_default_image   = boldthemes_get_option( 'listing_default_image' ) != '' ? boldthemes_get_option( 'listing_default_image' )
                                : BoldThemes_Customize_Default::$data['listing_default_image'];
                        
                        $image_id = get_post_thumbnail_id( $listing->ID );                        
                        $img = wp_get_attachment_image_src( $image_id, 'boldthemes_listing_image' );
			
                        $img_src = isset($img[0]) ? $img[0] : $listing_default_image;
			$img_full = wp_get_attachment_image_src( $image_id, 'full' );
			$img_src_full = isset($img_full[0]) ? $img_full[0] : $listing_default_image;
                        
                        $listing_pin_normal     = boldthemes_listing_category_image( $listing->ID, true );
                        $listing_pin_selected   = boldthemes_listing_category_image( $listing->ID, true, true );

			$featured_class =  $listing->featured ? 'bt_bb_listing_featured' : '';
                        
                        BoldThemesFrameworkTemplate::$listing_search_autocomplete    = boldthemes_get_option( 'listing_search_autocomplete' ) != '' ? boldthemes_get_option( 'listing_search_autocomplete' ) : false;
                        BoldThemesFrameworkTemplate::$listing_search_distance_unit   = boldthemes_get_option( 'listing_search_distance_unit' ) != '' ? boldthemes_get_option( 'listing_search_distance_unit' ) : 'mi';

		?>
		<div class="bt_bb_listing_box <?php echo esc_attr($featured_class);?>" data-postid="<?php echo esc_attr($listing->ID); ?>" data-icon="<?php echo esc_attr($listing_pin_normal); ?>" data-iconselected="<?php echo esc_attr($listing_pin_selected); ?>" data-latitude="<?php echo esc_attr($latitudeTo); ?>" data-longitude="<?php echo esc_attr($longitudeTo); ?>" 
		data-posturl="<?php echo get_permalink( $listing->ID ); ?>"  data-unit="<?php echo  esc_attr( BoldThemesFrameworkTemplate::$listing_search_distance_unit );?>">
			<div class="bt_bb_listing_box_inner">
				<a href="<?php echo get_permalink( $listing->ID ); ?>"></a>
				<div class="bt_bb_listing_image">
					<div class="bt_bb_listing_top_meta">
						<?php if ( !empty($listing_categories) ) { ?>
						<div class="bt_bb_latest_posts_item_category">
							<ul class="post-categories">
								<?php foreach ($listing_categories as $listing_category){ ?>
									<li><a href="<?php echo get_term_link($listing_category);?>" rel="category tag"><?php echo esc_html($listing_category->name);?></a></li>	
								<?php } ?>
                                                        </ul>
						</div>
						<?php } ?>
						<?php
                                                if ( function_exists( 'get_user_favorites' ) ) {
                                                    $is_favourited = boldthemes_is_favourited( $listing->ID, get_current_blog_id() );
                                                    $bt_bb_listing_favourite_class = $is_favourited ? 'bt_bb_listing_favourite_on' : 'bt_bb_listing_favourite';
                                                    ?>
                                                    <div class="bt_bb_listing_favourite">                                                    
                                                            <span class="<?php echo esc_attr($bt_bb_listing_favourite_class);?>" >
                                                                <?php esc_html_e( 'Add to favourite', 'bt_plugin' ); ?>
                                                            </span>
                                                    </div>  
                                                <?php } ?>
					</div>
					<div class="bt_bb_listing_photo" data-src="<?php echo esc_attr($img_src);?>" data-src-full="<?php echo esc_attr($img_src_full);?>" data-alt="<?php echo esc_attr($listing->post_title);?>">
                                                <img class="bt_src_load" src="<?php echo esc_attr($listing_default_image);?>" alt="<?php echo esc_attr($listing->post_title);?>" data-src="<?php echo esc_attr($img_src);?>" data-loaded="0">
						<div class="bt_bb_listing_photo_overlay"><span></span></div>
                                        </div>
				</div>

				<div class="bt_bb_listing_details">
					<div class="bt_bb_listing_title">
						<h3><?php echo esc_html($listing->post_title);?></h3>
                                                
						<?php if ( !empty($boldthemes_theme_listing_working_times) ) { ?>
							<?php if ( $open_hours == 'closed' ) { ?>
                                                                <small class="bt_bb_listing_working_hours"></small>								
                                                        <?php }else if ( $open_hours || empty($current_open_hours) ){ ?>
                                                                <small class="bt_bb_listing_working_hours"><?php esc_html_e( 'Now closed', 'bt_plugin' ); ?></small>
							<?php }else{ 
								$current_open_hours_text = '';							
								if ( !empty($current_open_hours) ) {                                                                    
									$current_open_hours_text     = $current_open_hours[0];
									if ( $current_open_hours[1] != '' ) { $current_open_hours_text	.= ' - ' . $current_open_hours[1];}
									if ( $current_open_hours[2] != '' ) { $current_open_hours_text	.= ' , ' . $current_open_hours[2];}
									if ( $current_open_hours[3] != '' ) { $current_open_hours_text	.= ' - ' . $current_open_hours[3];}
								}
								?>
								<small class="bt_bb_listing_working_hours bt_bb_listing_working_hours_open">
                                                                    <?php echo esc_html(trim($current_open_hours_text));?>
                                                                </small>							
							<?php }?>
						<?php }?>
					</div>
                                    
                                        <?php bello_get_listing_subtitle_html( $listing->ID );?>
                                       
					<div class="bt_bb_listing_information">
						<?php if ( $boldthemes_theme_listing_contact_phone ) { ?>
							<span class="bt_bb_listing_phone">
								<a href="tel:<?php echo esc_attr($boldthemes_theme_listing_contact_phone_link);?>">
									<?php echo esc_html($boldthemes_theme_listing_contact_phone);?>
								</a>
							</span>
						<?php } ?>
                                                <?php
                                                    $listing_show_distance   = boldthemes_get_option( 'listing_show_distance' ) != '' ? boldthemes_get_option( 'listing_show_distance' )
                                                            : BoldThemes_Customize_Default::$data['listing_show_distance'];
                                                ?>
						<?php if ( $latitudeTo != ''  && $longitudeTo != '' && $listing_show_distance ){ ?>
							<span class="bt_bb_listing_distance" id="bt_bb_listing_distance_<?php echo esc_html($listing->ID);?>"></span>																						
						<?php } ?>
					</div>
					<div class="bt_bb_listing_rating">
						<?php echo boldthemes_rating_header_listing_single( $listing->ID );?>
					</div>
					<div class="bt_bb_listing_excerpt">
						<div class="bt_bb_listing_excerpt_description">
							<?php echo wp_strip_all_tags($listing->post_excerpt);?>
						</div>
					</div>
					<div class="bt_bb_listing_bottom_meta">
						<?php							
							$no_of_comments =  count(boldthemes_comments_listing( $listing->ID ));						
							if ( $no_of_comments > 0  ) {
								echo '<span class="bt_bb_listing_comments">' . $no_of_comments . '</span>';
							}
							$rating = boldthemes_get_average_rating( $listing->ID );	
							if ( !empty($rating) ) {
								$average	= $rating["rating"];
								$total		= $rating["total"];
								$no		= $rating["no"];
								if ( $average > 0  ) {
									echo '<span class="bt_bb_listing_ratings">' . $average . '</span>';
								}
							}
						?>						
						<?php echo boldthemes_price_header_listing_single( $listing->ID );?>
					</div>
				</div>
			</div>
		</div>		
		<?php 
		$count++;								
		}
                
                $listing_grid_listings_pagination = boldthemes_get_option( 'listing_grid_listings_pagination' ) != '' ? boldthemes_get_option( 'listing_grid_listings_pagination' ) : 'paged';
                if ( $listing_grid_listings_pagination == 'paged' ){
                    boldthemes_listing_pagination( $listings_paged );
                }
			
	}
}

/*
*
* callback for ajax loading listing with map
*/
if ( ! function_exists( 'boldthemes_listing_results_map_html' ) ) {
	function boldthemes_listing_results_map_html(  $listings, $limit ) {
                if ( bt_map_is_osm() ) {
                    boldthemes_listing_results_openmap_html(  $listings, $limit );
                    return false;
                }
                if ( bt_map_is_leaflet() ) {
                    boldthemes_listing_results_leafletmap_html(  $listings, $limit );
                    return false;
                }
                
		$listing_pin_normal                 = boldthemes_get_option( 'listing_pin_normal' )     != '' ? boldthemes_get_option( 'listing_pin_normal' )   : '';
		$listing_pin_selected               = boldthemes_get_option( 'listing_pin_selected' )   != '' ? boldthemes_get_option( 'listing_pin_selected' ) : '';
		$listing_default_image              = boldthemes_get_option( 'listing_default_image' )  != '' ? boldthemes_get_option( 'listing_default_image' ) : BoldThemes_Customize_Default::$data['listing_default_image'];
		$custom_map_style                   = boldthemes_get_option( 'custom_map_style' )       != '' ? boldthemes_get_option( 'custom_map_style' )     : '';
                $custom_hidding_map_features_style  = boldthemes_get_option( 'custom_hidding_map_features_style' )  != '' ? boldthemes_get_option( 'custom_hidding_map_features_style' )     : '';
                
		$map_center                 = boldthemes_get_listings_google_map_center($listings );

                $bound  = count($listings) > 0 ? 1 : 0;
                $zoom   = $map_center["zoom"];
                
                $listing_map_disable_default_ui     = boldthemes_get_option( 'listing_map_disable_default_ui' )  == '' ? 0 : 1;               
                $listing_map_full_screen_control    = boldthemes_get_option( 'listing_map_full_screen_control' )  == '' ? 0 : 1;
                $listing_map_scroll_wheel           = boldthemes_get_option( 'listing_map_scroll_wheel' )  == '' ? 0 : 1;
                $listing_map_street_view_control    = boldthemes_get_option( 'listing_map_street_view_control' )  == '' ? 0 : 1;
                $listing_map_zoom_control           = boldthemes_get_option( 'listing_map_zoom_control' )  == '' ? 0 : 1;
                $listing_map_draggable              = boldthemes_get_option( 'listing_map_draggable' )  == '' ? 0 : 1;
                $listing_map_type_control           = boldthemes_get_option( 'listing_map_type_control' )  == '' ? 0 : 1;
                
                if ( $listing_map_disable_default_ui == 1 ){
                    $listing_map_full_screen_control    = 1;
                    $listing_map_scroll_wheel           = 1;
                    $listing_map_street_view_control    = 1;
                    $listing_map_zoom_control           = 1;
                    $listing_map_draggable              = 1;
                    $listing_map_type_control           = 1;
                }
               
                if ( $listing_map_scroll_wheel == 1 ){
                    $listing_map_draggable  = 1;
                }
                ?>
		
		<div id="bt_bb_listing_search_google_map"></div>
                <style>
                        
                        .map-control {
                          background-color: #fff;
                          border: 1px solid #ccc;
                          box-shadow: 0 2px 2px rgba(33, 33, 33, 0.4);
                          font-family: 'Roboto','sans-serif';
                          margin: 10px;
                          /* Hide the control initially, to prevent it from appearing
                             before the map loads. */
                          display: none;
                        }
                        /* Display the control once it is inside the map. */
                        #map .map-control { display: block; }

                        .selector-control {
                          font-size: 14px;
                          line-height: 30px;
                          padding-left: 5px;
                          padding-right: 5px;
                        }
                      </style>
			<script>
			  var myMarkers = [];
                          var myMarkersOriginal = [];
			  var map;
			  var custom_style = '';
			  var markerClusterer = null;
                          var zoomLevel = <?php echo esc_html($zoom);?>;
                              
			  function bt_bb_listing_gmap_init() { 
                               var map_center_lat = <?php echo esc_html($map_center["lat_center"]);?>;
                               var map_center_lng = <?php echo esc_html($map_center["lng_center"]);?>;
                                       
                                <?php if ( $bound == 0 ) { ?>
                                  map_center_lat = document.getElementById('bt_bb_listing_field_my_lat').value != 0 ? document.getElementById('bt_bb_listing_field_my_lat').value : map_center_lat;
                                  map_center_lng = document.getElementById('bt_bb_listing_field_my_lng').value != 0 ? document.getElementById('bt_bb_listing_field_my_lng').value : map_center_lng;
                                <?php } ?>
                                       
                                map = new google.maps.Map(document.getElementById('bt_bb_listing_search_google_map'), {
                                    center: new google.maps.LatLng(map_center_lat,map_center_lng),
                                    zoom:zoomLevel, 
                                    mapTypeId: 'roadmap',
                                    gestureHandling: 'greedy',
                                    mapTypeControl: <?php echo $listing_map_type_control;?>,
                                    fullscreenControl: <?php echo $listing_map_full_screen_control;?>,
                                    draggable: <?php echo $listing_map_draggable;?>,
                                    zoomControl: <?php echo $listing_map_zoom_control;?>,
                                    streetViewControl: <?php echo $listing_map_street_view_control;?>,
                                    scrollwheel: <?php echo $listing_map_scroll_wheel;?>,
                                    disableDefaultUI: <?php echo $listing_map_disable_default_ui;?>
                                });
                                
                                  
                                 <?php if ($custom_hidding_map_features_style != '') { ?>
                                        <?php echo $custom_hidding_map_features_style;?>
                                        map.setOptions({styles: styles['hide']});
                                 <?php } ?> 
                                        
                                <?php if ($custom_map_style != '') { ?>
                                    var styledMapType = new google.maps.StyledMapType(                                                        
                                        <?php echo $custom_map_style;?>,                                                       
                                        {name: 'Styled Map'}
                                    );                                        
                                    map.mapTypes.set('custom_style', styledMapType);
                                    map.setMapTypeId('custom_style');
                                 <?php } ?> 

                                    var icons = {
                                        normal: { icon: '<?php echo esc_html($listing_pin_normal);?>'},
                                        selected: {icon: '<?php echo esc_html($listing_pin_selected);?>' }
                                    };
				
                                    var features = [
                                        <?php
                                            $count = 1;
                                            foreach ( $listings as $listing){
                                                    $boldthemes_theme_listing_location_position	 = boldthemes_rwmb_meta('boldthemes_theme_listing-location_position', array(),$listing->ID);
                                                    $boldthemes_theme_listing_location_position	 = explode(",", $boldthemes_theme_listing_location_position);

                                                    $listing_pin_normal     = boldthemes_listing_category_image( $listing->ID , true );
                                                    $listing_pin_selected   = boldthemes_listing_category_image( $listing->ID , true, true );

                                                    if ( isset($boldthemes_theme_listing_location_position[0]) && isset($boldthemes_theme_listing_location_position[1]) ) {
                                                        ?>											
                                                             {
                                                                    position: new google.maps.LatLng(<?php echo esc_html($boldthemes_theme_listing_location_position[0]);?>,<?php echo esc_html($boldthemes_theme_listing_location_position[1]);?>),
                                                                    type:	  'normal',
                                                                    id:	  <?php echo json_encode( $listing->ID );?>,
                                                                    label:    <?php echo json_encode( $listing->post_title);?>,
                                                                    icon:     '<?php echo $listing_pin_normal;?>',
                                                                    icon_selected:    '<?php echo $listing_pin_selected;?>'
                                                              }, 	
                                                        <?php
                                                    }
                                                    $count++;
                                            }
                                            wp_reset_postdata();
                                        ?>
                                    ];
                                    
                                    var $mapDiv = jQuery('#bt_bb_listing_search_google_map');
                                    var mapDim = {
                                           height: $mapDiv.height(),
                                           width: $mapDiv.width() - $mapDiv.width()*0.4
                                    }
                                    
                                    var mapOffset = Math.round(( 0 - $mapDiv.width()*0.4 )/2);
                                    
                                    //Create bounds.
                                    <?php if ($bound) { ?>
                                        var bounds = new google.maps.LatLngBounds();
                                        for (var i = 0; i < features.length; i++) {
                                             bounds.extend(features[i].position);
                                        }                                            
                                             
                                        var ne = bounds.getNorthEast();
                                        var sw = bounds.getSouthWest();
                                        
                                       
                                        var aaa = sw.lat();
                                        var bbb = sw.lng();
                                        var ccc = ne.lat();
                                        var ddd = ne.lng();
                                        
                                        var sw_position = new google.maps.LatLng( parseFloat(aaa) , parseFloat(bbb) );
                                        var ne_position = new google.maps.LatLng( parseFloat(ccc) , parseFloat(ddd) );                                      
                                       
                                        var bounds2 = new google.maps.LatLngBounds();
                                        bounds2.extend(sw_position);  
                                        bounds2.extend(ne_position);
                                        
                                        var ne2 = bounds2.getNorthEast();
                                        var sw2 = bounds2.getSouthWest();
                                        
                                        if ( features.length == 0 ){
                                             map.setZoom(12); 
                                        } else if ( features.length == 1 ){
                                             map.setZoom(<?php echo esc_html($zoom);?>); 
                                             map.setCenter(bounds2.getCenter());
                                        }else{
                                             var zoom_level = getBoundsZoomLevel(bounds2, mapDim);
                                             map.setZoom(zoom_level); 
                                             map.fitBounds(bounds2);
                                             var newCenter2 = bounds2.getCenter();
                                             map.setCenter(newCenter2);
                                        }
                                                                               
                                   <?php } ?> 
                                   var LatLng = map.getCenter();
                                   map.panBy(mapOffset, 0);
                                                     
					// Create markers.
					var markers = [];
					var i = 0;
					features.forEach(function(feature) {
                                            var marker = new google.maps.Marker({
                                                  position: feature.position,
                                                  title: feature.label,
                                                  icon: feature.icon,
                                                  icon_original: feature.icon,	
                                                  id: feature.id,
                                                  map: map
                                            });
                                            markers.push(marker);                                                  
                                            myMarkers[i]          = marker;
                                            myMarkersOriginal[i]  = marker;
                                            i++;
                                            google.maps.event.addListener( marker, 'click', function(e){
                                                  if ( feature.id > 0 ) {
                                                      jQuery.ajax({													
                                                              url: '<?php echo admin_url( 'admin-ajax.php' );?>',
                                                              type: 'post',
                                                              data: {
                                                                      action:	'bt_get_listing_marker_details_action',
                                                                      listing_id:	feature.id,
                                                              },
                                                              success: function( response ) {
                                                                      jQuery('#bt_bb_listing_marker_details_container').html( response );
                                                                      for(var i=0;i<markers.length;i++){
                                                                          markers[i].setIcon(myMarkers[i].icon_original);
                                                                      }
                                                                      marker.setIcon(feature.icon_selected);
                                                              }
                                                      })
                                                  }
                                              }.bind( marker ) );
					   });
 
					var fontFamily	= 'Nunito Sans';
					var textColor	= '#ffffff';
					var url = '<?php echo get_template_directory_uri(); ?>/gfx/';

					var clusterStyles = [
					  {
						url: url + 'm1.png',
							fontFamily: fontFamily,
							height: 50, 
							width: 50, 
							anchor: [-18, 0], 
							textColor: textColor, 
							textSize: 10,
							iconAnchor: [15, 48]
					  },
					 {
						url: url + 'm2.png',
							fontFamily: fontFamily,
							height: 56, 
							width: 56, 
							anchor: [-18, 0], 
							textColor: textColor, 
							textSize: 11, 
							iconAnchor: [15, 48]
					  },
					 {
						url: url + 'm3.png',
							fontFamily: fontFamily,
							height: 66, 
							width: 66, 
							anchor: [-18, 0], 
							textColor: textColor, 
							textSize: 11, 
							iconAnchor: [15, 48]
					  },
						 {
						url: url + 'm4.png',
							fontFamily: fontFamily,
							height: 78, 
							width: 78, 
							anchor: [-18, 0], 
							textColor: textColor, 
							textSize: 12, 
							iconAnchor: [15, 48]
					  },
					 {
						url: url + 'm5.png',
							fontFamily: fontFamily,
							height: 90, 
							width: 90, 
							anchor: [-18, 0], 
							textColor: textColor, 
							textSize: 12, 
							iconAnchor: [15, 48]
					  }
					];	
					var mcOptions = {gridSize: 50, maxZoom: 12, styles: clusterStyles, zoomOnClick: true, averageCenter: true};
                                        
                                        if ( typeof MarkerClusterer !== 'undefined' ){
                                            var markerClusterer = new MarkerClusterer(map, markers, mcOptions);
                                        }else{
                                            document.addEventListener('readystatechange', function() {
                                                if ( typeof MarkerClusterer !== 'undefined' && (document.readyState === 'interactive' || document.readyState === 'complete') ) {
                                                        var markerClusterer = new MarkerClusterer(map, markers, mcOptions);
                                                }
                                            }, false);
                                        }
                                }
                                
                                function getBoundsZoomLevel(bounds, mapDim) {
                                    var WORLD_DIM = { height: 256, width: 256 };
                                    var ZOOM_MAX = 21;

                                    function latRad(lat) {
                                        var sin = Math.sin(lat * Math.PI / 180);
                                        var radX2 = Math.log((1 + sin) / (1 - sin)) / 2;
                                        return Math.max(Math.min(radX2, Math.PI), -Math.PI) / 2;
                                    }

                                    function zoom(mapPx, worldPx, fraction) {
                                        return Math.floor(Math.log(mapPx / worldPx / fraction) / Math.LN2);
                                    }

                                    var ne = bounds.getNorthEast();
                                    var sw = bounds.getSouthWest();

                                    var latFraction = (latRad(ne.lat()) - latRad(sw.lat())) / Math.PI;

                                    var lngDiff = ne.lng() - sw.lng();
                                    var lngFraction = ((lngDiff < 0) ? (lngDiff + 360) : lngDiff) / 360;

                                    var latZoom = zoom(mapDim.height, WORLD_DIM.height, latFraction);
                                    var lngZoom = zoom(mapDim.width, WORLD_DIM.width, lngFraction);

                                    return Math.min(latZoom, lngZoom, ZOOM_MAX);
                                }
                                
                                function fromLatLngToPoint(latLng, bounds) {
                                    var scale = Math.pow(2, map.getZoom());
                                    var ne = bounds.getNorthEast();
                                    var sw = bounds.getSouthWest();
                                   
                                    var nw = new google.maps.LatLng(ne.lat(), sw.lng());
                                    var worldCoordinateNW = map.getProjection().fromLatLngToPoint(nw);
                                    var worldCoordinate = map.getProjection().fromLatLngToPoint(latLng);

                                    return new google.maps.Point(Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale), Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale));
                                }
			</script>
		<?php
	}
}

/*
*
* Generate HTML for listing boxes in featured tiles grid
*/
if ( ! function_exists( 'bt_bb_listing_box_html_tiles' ) ) {
	function boldthemes_listing_box_html_tiles( $listing, $number, $category, $show, $format ) {

			$custom_fields		= get_post_custom( $listing->ID );
			$listing_fields		= bello_get_listing_fields( array( 'listing_id' => $listing->ID ) );
			$listing_categories = get_the_terms( $listing->ID, 'listing-category' );
			$listing_region		= get_the_terms( $listing->ID, 'listing-region' );
			$listing_region		= isset($listing_region) ? $listing_region[0] : '';

			$distance = "0";

			$boldthemes_theme_listing_contact_phone		= '';	
			$boldthemes_theme_listing_contact_phone_link	= '';
			$boldthemes_theme_listing_contact_mobile	= '';	
			$boldthemes_theme_listing_contact_mobile_link	= '';	
			$contact_location_position			= '';
			$working_times					= '';
			
			if ( isset($custom_fields['boldthemes_theme_listing-contact_phone']) ){		
				if ( bello_field_in_packages( $listing_fields['contact_phone'], $listing->ID) ) {
					$boldthemes_theme_listing_contact_phone		= boldthemes_rwmb_meta('boldthemes_theme_listing-contact_phone', array(), $listing->ID );
					$boldthemes_theme_listing_contact_phone_link	= bt_format_phone_number( $boldthemes_theme_listing_contact_phone );
				}
			}                        
                        		
			if ( isset($custom_fields['boldthemes_theme_listing-contact_mobile']) && isset($listing_fields['contact_mobile']) ){		
				if ( bello_field_in_packages( $listing_fields['contact_mobile'], $listing->ID) ) {
					$boldthemes_theme_listing_contact_mobile	= boldthemes_rwmb_meta('boldthemes_theme_listing-contact_mobile', array(), $listing->ID );
					$boldthemes_theme_listing_contact_mobile_link	= bt_format_phone_number( $boldthemes_theme_listing_contact_mobile );
				}
			}                        
                        
			if ( $boldthemes_theme_listing_contact_phone == '' ) {
				$boldthemes_theme_listing_contact_phone         = $boldthemes_theme_listing_contact_mobile;
				$boldthemes_theme_listing_contact_phone_link    = $boldthemes_theme_listing_contact_mobile_link;
			}
			
			if ( isset($custom_fields['boldthemes_theme_listing-location_position']) ){	
				if ( bello_field_in_packages( $listing_fields['location_position'], $listing->ID) ) {	
					$contact_location_position	= boldthemes_rwmb_meta('boldthemes_theme_listing-location_position', array(), $listing->ID );
				}
			}
			
			if ( isset($custom_fields['boldthemes_theme_listing-working_time']) ){	
				if ( bello_field_in_packages( $listing_fields['working_time'], $listing->ID) ) {	
					$working_times	= boldthemes_rwmb_meta('boldthemes_theme_listing-working_time', array(), $listing->ID);
				}
			}

			$map_arr	= explode(",", $contact_location_position);
			$latitudeTo	= isset($map_arr[0]) ? $map_arr[0] : '0';
			$longitudeTo	= isset($map_arr[1]) ? $map_arr[1] : '0';
			$zoom		= isset($map_arr[2]) ? $map_arr[2] : '15';

			$id = get_post_thumbnail_id( $listing->ID );
			$img = wp_get_attachment_image_src( $id, 'boldthemes_large_square' );

			if ( isset( $format_arr[ $count ] ) ) {
				switch ( $format_arr[ $count ] ){
					case '11': 
						$img = wp_get_attachment_image_src( $id, 'boldthemes_large_square' );
						break;
					case '21': 
						$img = wp_get_attachment_image_src( $id, 'boldthemes_large_rectangle' );
						break;
					case '12': 
						$img = wp_get_attachment_image_src( $id, 'boldthemes_large_vertical_rectangle' );
						break;
					case '22': 
						$img = wp_get_attachment_image_src( $id, 'boldthemes_large_square' );
						break;
					default: 
						$img = wp_get_attachment_image_src( $id, 'boldthemes_large_square' );
						break;
				}
			}

			$img_src = $img[0];

			$hw = 0;
			if ( $img_src != '' ) {
				$hw = $img[2] / $img[1];
			}

			$img_full = wp_get_attachment_image_src( $id, 'full' );
			
			$img_src_full = $img_full[0];
			if ( isset( $format_arr[ $count ] ) ) {
                            $tile_format = 'bt_bb_tile_format';
                            if ( $format_arr[ $count ] == '21' || $format_arr[ $count ] == '12' || $format_arr[ $count ] == '22' ) {
                                    $tile_format .= "_" . $format_arr[ $count ];
                            } else {
                                    $tile_format .= '11';
                            }
			}
                        
                        $listing_default_image   = boldthemes_get_option( 'listing_default_image' ) != '' ? boldthemes_get_option( 'listing_default_image' )
                                : BoldThemes_Customize_Default::$data['listing_default_image'];

			$featured_image     = isset( $image ) && !empty( $image ) ? $image[0] : $listing_default_image;
			
			$open_hours	    = bt_open_hours( $listing->ID );
			$current_open_hours = bt_open_hours_current_day( $listing->ID );

			$featured_class     =  $listing->featured ? 'bt_bb_listing_featured' : '';

			BoldThemesFrameworkTemplate::$listing_search_distance_unit = boldthemes_get_option( 'listing_search_distance_unit' ) != '' ? boldthemes_get_option( 'listing_search_distance_unit' ) : 'mi';
			
		?>
		<div class="bt_bb_listing_box <?php echo esc_attr($featured_class);?> bt_bb_grid_item <?php echo esc_attr($tile_format);?>" data-postid="<?php echo esc_attr($listing->ID); ?>" data-lattitue="<?php echo esc_attr($latitudeTo); ?>" data-longitute="<?php echo esc_attr($longitudeTo); ?>" 
		data-posturl="<?php echo get_permalink( $listing->ID ); ?>" data-distance="<?php echo esc_attr($distance); ?>">
			<div class="bt_bb_listing_box_inner">
				<a href="<?php echo get_permalink( $listing->ID ); ?>"></a>
				<div class="bt_bb_listing_image">
					<div class="bt_bb_listing_top_meta">
						<?php if ( !empty($listing_categories) ) { ?>
						<div class="bt_bb_latest_posts_item_category">
							<ul class="post-categories">
								<?php foreach ($listing_categories as $listing_category){ ?>
									<li><a href="<?php echo get_term_link($listing_category);?>" rel="category tag"><?php echo esc_html($listing_category->name);?></a></li>	
								<?php } ?>
								<?php if ( !empty($listing_region) ) { ?>							
									<li><a href="<?php echo get_term_link($listing_region->term_id, 'listing-region');?>" rel="category tag"><?php echo esc_html($listing_region->name);?></a></li>							
								<?php } ?>
							</ul>
							
						</div>
						<?php } ?>
						<?php
                                                if ( function_exists( 'get_user_favorites' ) ) {
                                                    $is_favourited = boldthemes_is_favourited( $listing->ID, get_current_blog_id() );
                                                    $bt_bb_listing_favourite_class = $is_favourited ? 'bt_bb_listing_favourite_on' : 'bt_bb_listing_favourite';
                                                    ?>
                                                    <div class="bt_bb_listing_favourite">
                                                            <a href="#" class="<?php echo esc_attr($bt_bb_listing_favourite_class);?>"><?php esc_html_e( 'Add to favourite', 'bt_plugin' ); ?></a>
                                                    </div>	
                                                <?php } ?>
					</div>
					<div class="bt_bb_listing_photo">
						<img src="<?php echo esc_attr($featured_image);?>" alt="<?php echo esc_attr($listing->post_title);?>">
						<div class="bt_bb_listing_photo_overlay"><span></span></div>
					</div>
				</div>

				<div class="bt_bb_listing_details">
					<div class="bt_bb_listing_title">
						<h3><?php echo esc_html($listing->post_title);?></h3>	
						
						<?php if ( !empty($working_times) ) { ?>
							 <?php if ( $open_hours == 'closed' ) { ?>
                                                                <small class="bt_bb_listing_working_hours"></small>
                                                        <?php }else if ( $open_hours || empty($current_open_hours) ) { ?>
								<small class="bt_bb_listing_working_hours"><?php esc_html_e( 'Now closed', 'bt_plugin' ); ?></small>
							<?php }else{ 
								$current_open_hours_text = '';							
								if ( !empty($current_open_hours) ) {
									$current_open_hours_text	= $current_open_hours[0];
									if ( $current_open_hours[1] != '' ) { $current_open_hours_text	.= ' - ' . $current_open_hours[1];}
									if ( $current_open_hours[2] != '' ) { $current_open_hours_text	.= ' , ' . $current_open_hours[2];}
									if ( $current_open_hours[3] != '' ) { $current_open_hours_text	.= ' - ' . $current_open_hours[3];}
								}
								?>
								<small class="bt_bb_listing_working_hours bt_bb_listing_working_hours_open"><?php echo esc_html($current_open_hours_text);?></small>							
							<?php }?>
						<?php }?>
					</div>
					<div class="bt_bb_listing_information">
						<?php if ( $boldthemes_theme_listing_contact_phone ) { ?>
							<span class="bt_bb_listing_phone">
								<a href="tel:<?php echo esc_attr($boldthemes_theme_listing_contact_phone_link);?>">
									<?php echo esc_html($boldthemes_theme_listing_contact_phone);?>
								</a>
							</span>
						<?php } ?>

						<?php if ( $latitudeTo != ''  && $longitudeTo != '' ){ 
							
							?>
							<span class="bt_bb_listing_distance" id="bt_bb_listing_distance_<?php echo esc_attr($listing->ID);?>"></span>	
							<script>
								<?php if ( $ajax == 1 ) { ?>
									jQuery( window ).load(function() {
										bt_get_distance(  <?php echo esc_attr($latitudeTo);?>, <?php echo esc_attr($longitudeTo);?>, <?php echo esc_attr($listing->ID);?>, '<?php echo BoldThemesFrameworkTemplate::$listing_search_distance_unit;?>' );									
									});
								<?php }else{ ?>
									jQuery( document ).ready(function() {
										bt_get_distance(  <?php echo esc_attr($latitudeTo);?>, <?php echo esc_attr($longitudeTo);?>, <?php echo esc_attr($listing->ID);?>, '<?php echo BoldThemesFrameworkTemplate::$listing_search_distance_unit;?>' );									
									});
								<?php } ?>
								
							</script>																
						<?php } ?>
					</div>

					<div class="bt_bb_listing_rating">
						<?php echo boldthemes_rating_header_listing_single( $listing->ID );?>
					</div>

					<div class="bt_bb_listing_excerpt">
						<div class="bt_bb_listing_excerpt_description">
							<?php echo wp_strip_all_tags($listing->post_excerpt);?>
						</div>
					</div>

					<div class="bt_bb_listing_bottom_meta">
						<?php							
							$no_of_comments =  count(boldthemes_comments_listing( $listing->ID ));
						
							if ( $no_of_comments > 0  ) {
								echo '<span class="bt_bb_listing_comments">' . $no_of_comments . '</span>';
							}
							$rating = boldthemes_get_average_rating( $listing->ID );	
							if ( !empty($rating) ) {
								$average	= $rating["rating"];
								$total		= $rating["total"];
								$no			= $rating["no"];
								if ( $average > 0  ) {
									echo '<span class="bt_bb_listing_ratings">' . $average . '</span>';
								}
							}
							?>
						
						<?php echo boldthemes_price_header_listing_single( $listing->ID );?>
					</div>
				</div>
			</div>
		</div>		
		<?php 	
	}
}

/* marker details results ajax */
add_action('wp_ajax_bt_get_listing_marker_details_action', 'bt_get_listing_marker_details_action_callback'); 
add_action('wp_ajax_nopriv_bt_get_listing_marker_details_action', 'bt_get_listing_marker_details_action_callback'); 
function bt_get_listing_marker_details_action_callback(){
    $listing_id = '';
    if( isset($_POST['listing_id']) && $_POST['listing_id'] != "" ){
        $listing_id = $_POST['listing_id'];
    }	  
    bt_dump_listing_marker_details_results( $listing_id ); 
    die; 
}

/*
*
* Generate HTML for marker details
*/
if ( ! function_exists( 'bt_dump_listing_marker_details_results' ) ) {
	function bt_dump_listing_marker_details_results( $listing_id ) {
		$listing		= get_post($listing_id);
		$listing_fields		= bello_get_listing_fields( array( 'listing_id' => $listing->ID ) );
		$custom_fields		= get_post_custom( $listing->ID );	
		$listing_categories     = get_the_terms( $listing->ID, 'listing-category' );
		$permalink		= get_permalink( $listing->ID );
                
                
                
                $listing_pin_normal     = boldthemes_listing_category_image( $listing->ID , true );
                $listing_pin_selected   = boldthemes_listing_category_image( $listing->ID , true, true );
                

		$contact_phone			= '';	
		$contact_phone_link		= '';
		$contact_mobile			= '';
		$contact_mobile_link		= '';
		$contact_address		= '';
		$contact_website		= '';
		$contact_email			= '';
		$contact_price			= '';
		$contact_description		= '';
		$contact_location_position	= '';
		$working_times			= '';

		if ( isset($custom_fields['boldthemes_theme_listing-contact_phone']) ){		
			if ( isset($listing_fields['contact_phone']) && bello_field_in_packages( $listing_fields['contact_phone'], $listing->ID) ) {		
				$contact_phone		= boldthemes_rwmb_meta('boldthemes_theme_listing-contact_phone', array(), $listing->ID );
                                $contact_phone_link	= bt_format_phone_number( $contact_phone );
			}
		}

		if ( isset($custom_fields['boldthemes_theme_listing-contact_mobile']) ){		
			if ( isset($listing_fields['contact_mobile']) && bello_field_in_packages( $listing_fields['contact_mobile'], $listing->ID) ) {		
				$contact_mobile		= boldthemes_rwmb_meta('boldthemes_theme_listing-contact_mobile', array(), $listing->ID );
                                $contact_mobile_link	= bt_format_phone_number( $contact_mobile );
			}
		}
		
		if ( isset($custom_fields['boldthemes_theme_listing-contact_address']) ){	
			if ( isset($listing_fields['contact_address']) && bello_field_in_packages( $listing_fields['contact_address'], $listing->ID) ) {	
				$contact_address = boldthemes_rwmb_meta('boldthemes_theme_listing-contact_address', array(), $listing->ID );
			}
		}

		if ( isset($custom_fields['boldthemes_theme_listing-contact_website']) ){	
			if ( isset($listing_fields['contact_website']) && bello_field_in_packages( $listing_fields['contact_website'], $listing->ID) ) {	
				$contact_website = boldthemes_rwmb_meta('boldthemes_theme_listing-contact_website', array(), $listing->ID );
			}
		}
		
		if ( isset($custom_fields['boldthemes_theme_listing-contact_email']) ){	
			if ( isset($listing_fields['contact_email']) && bello_field_in_packages( $listing_fields['contact_email'], $listing->ID) ) {	
				$contact_email = boldthemes_rwmb_meta('boldthemes_theme_listing-contact_email', array(), $listing->ID );
			}
		}
		
		if ( isset($custom_fields['boldthemes_theme_listing-contact_price']) ){	
			if ( isset($listing_fields['contact_price']) && bello_field_in_packages( $listing_fields['contact_price'], $listing->ID) ) {	
				$contact_price = boldthemes_rwmb_meta('boldthemes_theme_listing-contact_price', array(), $listing->ID );
			}
		}

		if ( isset($custom_fields['boldthemes_theme_listing-contact_description']) ){	
			if ( isset($listing_fields['contact_description']) && bello_field_in_packages( $listing_fields['contact_description'], $listing->ID) ) {	
				$contact_description = boldthemes_rwmb_meta('boldthemes_theme_listing-contact_description', array(), $listing->ID );	
			}
		}

		if ( isset($custom_fields['boldthemes_theme_listing-location_position']) ){	
			if ( isset($listing_fields['location_position']) && bello_field_in_packages( $listing_fields['location_position'], $listing->ID) ) {	
				$contact_location_position	= boldthemes_rwmb_meta('boldthemes_theme_listing-location_position', array(), $listing->ID );
			}
		}

		if ( isset($custom_fields['boldthemes_theme_listing-working_time']) ){	
			if ( isset($listing_fields['working_time']) && bello_field_in_packages( $listing_fields['working_time'], $listing->ID) ) {	
				$working_times	= boldthemes_rwmb_meta('boldthemes_theme_listing-working_time', array(), $listing->ID);
			}
		}
                
                $listing_default_image   = boldthemes_get_option( 'listing_default_image' ) != '' ? boldthemes_get_option( 'listing_default_image' )
                                : BoldThemes_Customize_Default::$data['listing_default_image'];                

		$map_arr	= explode(",", $contact_location_position);
		$lat		= isset($map_arr[0]) ? $map_arr[0] : '';
		$lng		= isset($map_arr[1]) ? $map_arr[1] : '';
		$zoom		= isset($map_arr[2]) ? $map_arr[2] : '15';

		$image		= wp_get_attachment_image_src( get_post_thumbnail_id( $listing->ID ), 'large' );
                
		$featured_image = isset( $image ) && !empty( $image ) ? $image[0] : $listing_default_image;	

		$open_hours		= bt_open_hours( $listing->ID );
		$current_open_hours     = bt_open_hours_current_day( $listing->ID );
		$day_name		= bt_day_name();

		$comment_args = array(
			'order'              => 'DESC',
			'number'             => 3,
			'post_id'            => $listing->ID,
			'include_unapproved' => ''
		);
		$comment_query = new WP_Comment_Query( $comment_args );
		$comments = $comment_query->comments;
                
                
               
		?>
			<div class="bt_bb_listing_marker_details">
				<div class="bt_bb_listing_marker_preview">
					<div class="bt_bb_listing_box">
						<div class="bt_bb_listing_box_inner">
							<a href="<?php echo get_permalink( $listing->ID ); ?>"></a>
							<div class="bt_bb_listing_image">
								<div class="bt_bb_listing_top_meta">
									<?php if ( !empty($listing_categories) ) { ?>
									<div class="bt_bb_latest_posts_item_category">
										<ul class="post-categories">
											<?php foreach ($listing_categories as $listing_category){ ?>
												<li><a href="<?php echo get_term_link($listing_category);?>" rel="category tag"><?php echo esc_html($listing_category->name);?></a></li>	
											<?php } ?>
										</ul>
									</div>
									<?php } ?>
									<div class="bt_listing_close_details" data-postid="<?php echo esc_attr($listing->ID);?>">
										<a data-postid="<?php echo esc_attr($listing->ID);?>" href="#"><?php esc_html_e( 'Close details', 'bt_plugin' ); ?></a>
									</div>
								</div>
								<?php if (  $featured_image ){ ?>
								<div class="bt_bb_listing_photo">
									<img src="<?php echo esc_attr($featured_image);?>" alt="<?php echo esc_attr($listing->post_title);?>">
									<div class="bt_bb_listing_photo_overlay"></div>
								</div>
								<?php } ?>
							</div>
							<div class="bt_bb_listing_details">
								<div class="bt_bb_listing_title"><h3><?php echo esc_html($listing->post_title);?></h3></div>
								<div class="bt_bb_listing_rating">
									<?php echo boldthemes_rating_header_listing_single( $listing->ID );?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="bt_bb_listing_marker_data">

					<div class="bt_bb_listing_marker_options">
						<ul>
                                                       
							<?php if ( $lat != '' && $lng != '' ) { ?>
							<li>
                                                            <a href="https://www.google.com/maps/dir//<?php echo esc_attr($lat);?>,<?php echo esc_attr($lng);?>/@<?php echo esc_attr($lat);?>,<?php echo esc_attr($lng);?>,15z" target="_blank" class="bt_bb_listing_marker_get_directions">
                                                                    <span><?php esc_html_e( 'Get directions', 'bt_plugin' ); ?></span>
                                                            </a>
							</li>
							<?php } ?>
                                                        <?php 
                                                        $comments_open = $listing->comment_status;
                                                        if ( $comments_open == 'open' ) { ?>
                                                         <li>
                                                             <a href="<?php echo esc_attr($permalink);?>#btCommentsForm" class="bt_bb_listing_marker_write_review">
                                                                 <span><?php esc_html_e( 'Write a review', 'bt_plugin' ); ?></span> 
                                                             </a>
                                                         </li>	
							<?php } ?>
                                                        <?php if ( $contact_phone != '' && $contact_phone_link != '' ) { ?>
								<li><a href="tel:<?php echo esc_attr($contact_phone_link);?>" class="bt_bb_listing_marker_make_call"><span><?php esc_html_e( 'Make a call', 'bt_plugin' ); ?></span></a></li>
							<?php } ?>
                                                                
                                                        <?php if ( ($lat != '' && $lng != '') || ( $contact_phone != '' && $contact_phone_link != '' ) ) { ?>
                                                                <li></li>  
                                                        <?php } ?>	
                                                  </ul>
					</div>

					<div class="bt_bb_listing_marker_excerpt">
						<div class="bt_bb_separator bt_bb_top_spacing_small bt_bb_bottom_spacing_small bt_bb_border_style_solid"></div>
						<div class="bt_bb_listing_marker_excerpt_description"><?php echo wp_strip_all_tags($listing->post_excerpt);?></div>
					</div>

					<div class="bt_bb_listing_marker_meta_data">
						<div class="bt_bb_separator bt_bb_top_spacing_small bt_bb_bottom_spacing_small bt_bb_border_style_solid"></div>
						<ul class="bt_bb_listing_marker_meta_data_items">
							<?php if ( $contact_address != '' ) { ?>
								<li class="bt_bb_listing_marker_meta_address"><span><?php echo esc_html($contact_address);?></span></li>
							<?php } ?>
							<?php if ( $contact_phone != '' && $contact_phone_link != '' ) { ?>
								<li class="bt_bb_listing_marker_meta_phone"><a href="tel:<?php echo esc_html($contact_phone_link);?>"><?php echo esc_html($contact_phone);?></a></li>
							<?php } ?>
							<?php if ( $contact_mobile != '' && $contact_mobile_link != '' ) { ?>
								<li class="bt_bb_listing_marker_meta_phone"><a href="tel:<?php echo esc_html($contact_mobile_link);?>"><?php echo esc_html($contact_mobile);?></a></li>
							<?php } ?>
							<?php if ( $contact_email != '' ) { ?>
								<li class="bt_bb_listing_marker_meta_email"><a href="mailto:<?php echo esc_html($contact_email);?>" target="_blank"><?php echo esc_html($contact_email);?></a></li>
							<?php } ?>
							<?php if ( $contact_website != '' ) { ?>
								<li class="bt_bb_listing_marker_meta_web_site"><a href="<?php echo esc_html($contact_website);?>" target="_blank"><?php echo esc_html($contact_website);?></a></li>
							<?php } ?>

							<?php if ( !empty($working_times) ) { ?>
                                                                            <?php if ( $open_hours != '' && $open_hours != 'closed' ) { ?>
                                                                                    <li class="bt_bb_listing_marker_meta_working_hours">
                                                                                    <span><?php esc_html_e( 'Now closed', 'bt_plugin' ); ?> 
                                                                                        <span class="bt_bb_listing_marker_meta_show_working_hours">
                                                                                            <?php esc_html_e( 'Show working hours', 'bt_plugin' ); ?>
                                                                                        </span> 
                                                                                         <?php if ( $open_hours != '00:00' && $open_hours != '12:00 am') { ?>
                                                                                        <small class="bt_bb_listing_marker_meta_opens_at">
                                                                                            <?php esc_html_e( 'Opens  at', 'bt_plugin' ); ?> 
                                                                                            <strong><?php echo esc_html($open_hours);?></strong>
                                                                                        </small>
                                                                                         <?php } ?>
                                                                                    </span>
                                                                             <?php }else if ($open_hours == 'closed'){ ?>
                                                                                    <li class="bt_bb_listing_marker_meta_working_hours"><span><?php esc_html_e( 'Now closed', 'bt_plugin' ); ?>  </span>
                                                                            <?php }else{ ?>
                                                                                    <li class="bt_bb_listing_marker_meta_working_hours bt_bb_listing_marker_meta_now_working">
                                                                                        <span><?php esc_html_e( 'Now open', 'bt_plugin' ); ?> 
                                                                                            <span class="bt_bb_listing_marker_meta_show_working_hours">
                                                                                                <?php esc_html_e( 'Show working hours', 'bt_plugin' ); ?>
                                                                                            </span>
                                                                                        </span>
                                                                            <?php } ?>
                                                                            
                                                                                <dl>
                                                                                        <?php
                                                                                        $listing_search_time_format = get_option( 'time_format' ) != '' ?  get_option( 'time_format' ) : 'H:i';  
                                                                                        $i = 0;					
                                                                                        foreach(  $working_times as  $working_time){
                                                                                                $klasa = ( $working_time["start"] == '' && $working_time["end"] =='' && $working_time["start2"] == '' && $working_time["end2"] =='' ) ? ' class="bt_bb_listing_marker_meta_working_hours_closed"' : '';

                                                                                                $hours1_start = '';
                                                                                                if ( isset($working_time["start"]) && $working_time["start"] != ''  ){
                                                                                                        $hours1_start = date($listing_search_time_format, strtotime($working_time["start"]));
                                                                                                }

                                                                                                $hours1_end = '';
                                                                                                if ( isset($working_time["end"]) && $working_time["end"] != ''  ){
                                                                                                        $hours1_end	= ' - ' . date($listing_search_time_format, strtotime($working_time["end"]));
                                                                                                }

                                                                                                $hours2_start = '';
                                                                                                if ( isset($working_time["start2"]) && $working_time["start2"] != ''  ){
                                                                                                        $hours2_start = ' ' . date($listing_search_time_format, strtotime($working_time["start2"]));
                                                                                                }

                                                                                                $hours2_end = '';
                                                                                                if ( isset($working_time["end2"]) && $working_time["end2"] != ''  ){
                                                                                                        $hours2_end = ' - ' . date($listing_search_time_format, strtotime($working_time["end2"]));
                                                                                                }

                                                                                                $hours_all = '';
                                                                                                if ( isset($working_time["all"]) && $working_time["all"] == 1  ){
                                                                                                        $hours1_start = '24h';
                                                                                                        $hours1_end   = '';  
                                                                                                }

                                                                                                $hours = $hours1_start . $hours1_end . $hours2_start . $hours2_end;

                                                                                                if ( $hours == '' ) {
                                                                                                        $hours =  __( 'CLOSED', 'bt_plugin' );									
                                                                                                }

                                                                                                echo '<dt>' . esc_html(date_i18n( 'l', $day_name[ $i ] )) . '</dt>' . '<dd' . esc_attr($klasa) . '>' . esc_html($hours) . '</dd>';

                                                                                                $i++;

                                                                                        }
                                                                                        ?>									
                                                                                </dl>
                                                                        </li>
							<?php } ?>

							<?php if ( $contact_price != '' ) { ?>
								<li class="bt_bb_listing_marker_meta_price"><span><?php echo $contact_price;?></span></li>
							<?php } ?>
							<?php if ( $contact_description != '' ) { ?>
								<li class="bt_bb_listing_marker_meta_distance"><span><?php echo $contact_description;?></span></li>
							<?php } ?>
						</ul>
					</div>

					<div class="bt_bb_listing_marker_view_more">
						<div class="bt_bb_separator bt_bb_top_spacing_small bt_bb_bottom_spacing_small bt_bb_border_style_solid"></div>
						<div class="bt_bb_button bt_bb_icon_position_left bt_bb_color_scheme_10 bt_bb_style_filled bt_bb_size_normal bt_bb_width_inline bt_bb_shape_inherit bt_bb_align_inherit">
							<a href="<?php echo esc_url($permalink);?>" class="bt_bb_link">
								<span class="bt_bb_button_text"><?php esc_html_e( 'View details', 'bt_plugin' ); ?></span><span data-ico-fontawesome="&#xf101;" class="bt_bb_icon_holder"></span>
							</a>
						</div>
					</div>

					<?php 
                                        $comments_open = $listing->comment_status;
                                        if (  $comments && $comments_open == 'open'  ) { ?>
						<div class="bt_bb_listing_marker_reviews">
							<div class="bt_bb_separator bt_bb_top_spacing_small bt_bb_bottom_spacing_small bt_bb_border_style_solid"></div>
							<h4><?php esc_html_e( 'User reviews', 'bt_plugin' ); ?></h4>
							<ul class="comments">
								<?php	
								foreach ( $comments as $comment ) {
                                                                    
									$GLOBALS['comment'] = $comment;
									if ( '0' != $comment->comment_approved ) {
										$rating			= intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
										$comment_title		= get_comment_meta( $comment->comment_ID, 'title', $single = true );
										$comment_content	= get_comment_meta( $comment->comment_ID, 'contnet', $single = true );
										$avatar_html		= get_avatar( $comment, 140 ); 
										?>
										<li class="comment even thread-even depth-1" id="li-comment-8">
											<article>
												<?php if ( $avatar_html != '' ) { ?>
													<div class="commentAvatar"><?php echo wp_kses_post( $avatar_html );?></div>
												<?php } ?>

												<div class="commentTxt">
													<div class="vcard divider">
													<?php printf( '<h5 class="author"><span class="fn">%1$s</span></h5>', get_comment_author_link() );?>
														
													<?php if ( $rating > 0 ) { ?>
															<div itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf( esc_attr__( 'Rated %d out of 5', 'bt_plugin' ), $rating ) ?>">
																<span style="width:<?php echo wp_kses_post( ( $rating / 5 ) * 100 ); ?>%"><strong><?php echo wp_kses_post( $rating ); ?></strong>
																<?php esc_html( 'out of 5', 'bt_plugin' ); ?></span>
															</div>
													<?php } ?>	

													</div>
													<div class="comment">
														<p><?php echo esc_html($comment_title); ?></p>
													</div>
												</div>
											</article>
										</li>
									<?php
									}
								}
								?>
							</ul>
							<div class="bt_bb_listing_marker_review_options">
								<div class="bt_bb_separator bt_bb_top_spacing_extra_small bt_bb_bottom_spacing_extra_small bt_bb_border_style_solid"></div>
								<div class="bt_bb_button bt_bb_icon_position_left bt_bb_color_scheme_7 bt_bb_style_clean bt_bb_size_small bt_bb_width_inline bt_bb_shape_inherit bt_bb_align_inherit">
									<a href="<?php echo esc_url($permalink);?>#btCommentsAll" target="_self" class="bt_bb_link">
										<span class="bt_bb_button_text"><?php esc_html_e( 'Show all reviews', 'bt_plugin' ); ?></span><span data-ico-fontawesome="&#xf10e;" class="bt_bb_icon_holder"></span>
									</a>
								</div>
                                                                    <div class="bt_bb_button bt_bb_icon_position_left bt_bb_color_scheme_3 bt_bb_style_clean bt_bb_size_small bt_bb_width_inline bt_bb_shape_inherit bt_bb_align_inherit">
                                                                    <a href="<?php echo esc_url($permalink);?>#btCommentsForm" target="_self" class="bt_bb_link">
                                                                    <span class="bt_bb_button_text"><?php esc_html_e( 'Write a review', 'bt_plugin' ); ?></span><span data-ico-fontawesome="&#xf040;" class="bt_bb_icon_holder"></span>
                                                                    </a></div>                                                               
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<script>
				(function( $ ) {
					$( document ).ready(function() {
						$('.bt_listing_close_details a').on( 'click', function(e) { 
							for(var i=0;i<myMarkers.length;i++){
                                                            myMarkers[i].setIcon(myMarkersOriginal[i].icon_original)
							}
							$(this).data('postid', 0);     
							$('.bt_bb_listing_marker_details').addClass('hidden');
							e.preventDefault();
						});
						$('.bt_bb_listing_marker_meta_show_working_hours').on( 'click', function() {
							$(this).toggleClass('on');
							$('.bt_bb_listing_marker_meta_working_hours dl').toggleClass('on');
						});
					});
			}( jQuery ));

			</script>
		<?php
	}
}

/*
*
* Generate HTML for amenities list
*/
if ( ! function_exists( 'boldthemes_amenities_html' ) ) {
	function boldthemes_amenities_html(){
		$custom_fields = bello_get_listing_fields();
		$amenities  = array();
                $_amenities = array();
		foreach( $custom_fields as &$field ) {
                    if ( isset($field) ) {				
                        if (strpos( $field['slug'], 'amenities') !== false) {
                            $field["type"] = "amenities";
                            if (!empty($field["value"])){
                                if ( $field["value"][0] == 1 ) {
                                    array_push( $amenities , $field);
                                }
                            }
                        }
                    }
		}  
                                
		if ( !empty($amenities) ) {
                    $_amenities = boldthemes_amenities_group_by($amenities, 'group');                     
                    if ( !empty($_amenities) ) { 
                        ksort($_amenities);
                        foreach ($_amenities as $key => $value ) {
                        ?>
                            <div class="btSingleListingAmenities">                                
                                <h6><?php esc_html_e( $key, 'bt_plugin' ); ?></h6>
                                <div class="btAmenities">
                                        <ul>
                                            <?php 
                                            if ( !empty($value) ) { 
                                                foreach ($value as $field ) {
                                                        bello_show_field( $field, '' );
                                                } 
                                            }
                                            ?>
                                       </ul>
                                </div>
                            </div>
                        <?php 
                        }
                    }
		} 
	}
}

function boldthemes_amenities_group_by($array, $key) {
    $return = array();
    foreach($array as $val) {
        $return[$val[$key]][] = $val;
    }
    return $return;
}

/*
*
* Generate HTML for amenities list old function
*/
if ( ! function_exists( 'boldthemes_amenities_html_old' ) ) {
	function boldthemes_amenities_html_old(){
		$custom_fields = bello_get_listing_fields();
		$amenities = array();
		foreach( $custom_fields as &$field ) {
			if ( isset($field) ) {	
                            
				if ( $field['group'] == 'Amenities' ) {
                                       
					$field["type"] = "amenities";
                                        if (!empty($field["value"])){
                                            if ( $field["value"][0] == 1 ) {
                                                    array_push( $amenities , $field);
                                            }
                                        }
				}
			}
		}
                
		if ( !empty($amenities) ) { ?>
			<div class="btSingleListingAmenities">
				<h6><?php esc_html_e( 'Amenities', 'bt_plugin' ); ?></h6>
				<div class="btAmenities">
					<ul>
						<?php 	
							foreach ($amenities as $field ) {
								bello_show_field( $field, '' );
							} 
						 ?>
					</ul>
				</div>
			</div>
		<?php 
		} 
	}
}


/*
*
* Generate HTML for pagination in search result list
*/
if ( ! function_exists( 'boldthemes_listing_pagination' ) ) {
    function boldthemes_listing_pagination( $listings_paged = 1){
        $listing_grid_listings_pagination = boldthemes_get_option( 'listing_grid_listings_pagination' ) != '' ? 
                boldthemes_get_option( 'listing_grid_listings_pagination' ) : 'paged';
        
        BoldThemesFrameworkTemplate::$paged = $listings_paged;
        BoldThemesFrameworkTemplate::$listing_search_autocomplete = boldthemes_get_option( 'listing_search_autocomplete' ) ? 
                boldthemes_get_option( 'listing_search_autocomplete' ) : false;

        if ( BoldThemesFrameworkTemplate::$max_page > 1 && BoldThemesFrameworkTemplate::$found > 0 ) {
            if ( $listing_grid_listings_pagination == 'loadmore' ){                             
                 echo '<div class="bt_bb_listing_box bt_bb_loadmore_box"><div class="bt_bb_loadmore" data-paged="1" data-maxpage="' .BoldThemesFrameworkTemplate::$max_page . '">' . esc_html__('Load more listings', 'bt_plugin') . '</div></div>';   
            }else{                                
                $format = BoldThemesFrameworkTemplate::$listing_search_autocomplete ? '&page=%#%' : '/page/%#%';
                
                
                echo '<div class="bt_bb_listing_box bt_bb_loadmore_box">';
                    echo paginate_links(array(
                            'base'		 => get_pagenum_link(1) . '%_%',
                            'format'             => $format,
                            'current'            => max(1, BoldThemesFrameworkTemplate::$paged),
                            'total'		 => BoldThemesFrameworkTemplate::$max_page,
                            'show_all'           => false,
                            'end_size'           => 1,
                            'mid_size'           => 2,
                            'prev_next'          => true,
                            'prev_text'          => max(1, BoldThemesFrameworkTemplate::$paged - 1),
                            'next_text'          => max(1, BoldThemesFrameworkTemplate::$paged + 1),                            
                            'type'               => 'plain',
                            'add_args'           => true,
                            'add_fragment'       => '',
                            'before_page_number' => '',
                            'after_page_number'  => ''

                    ));
                echo '</div>';
            }
        }
    }
}


/**
 * Listing Single Header Rating
 */
if ( ! function_exists( 'boldthemes_rating_header_listing_single' ) ) {
	function boldthemes_rating_header_listing_single( $listing_id = null ) {
                $output  = ' ';	
               
                $listing_show_rating	= boldthemes_get_option( 'listing_show_rating' );
                
                if ($listing_show_rating){
                    $listing_id = isset(  $listing_id ) ?  $listing_id : get_the_ID();		
                    $rating = boldthemes_get_average_rating($listing_id);		

                    if ( $rating['rating'] > 0 ){
                            $percent = ( $rating['rating'] / 5 ) * 100;			
                            if ( $percent > 0 ) {			
                                    $output .= '<div itemscope itemtype="http://schema.org/Rating" class="star-rating" title="' . sprintf( esc_attr__( 'Rated %d out of 5', 'bt_plugin' ), $rating['rating'] ) . '"><span style="width:' .  $percent . '%">Rated <strong>' . wp_kses_post( $rating['rating'] ) . '</strong> ' . esc_html__( 'out of 5', 'bt_plugin' ) . '</span></div>';		
                            }
                    }
                }
		return $output;
	}
}

/**
 * Listing Single Header Price
 */
if ( ! function_exists( 'boldthemes_price_header_listing_single' ) ) {
	function boldthemes_price_header_listing_single( $listing_id = null) {            
                BoldThemesFrameworkTemplate::$currency_symbol	= boldthemes_get_option( 'listing_search_currency_symbol' ) ? boldthemes_get_option( 'listing_search_currency_symbol' ) : ''; 
                                  
                $arg = isset(  $listing_id ) ?  array( 'listing_id' => $listing_id ) :  array( 'listing_id' => get_the_ID() );               
		$custom_fields	= bello_get_listing_fields( $arg ); 
                
                // start: change - prices and packages - 11.01
                $field_in_packages = 0;                
                foreach( $custom_fields as $field ) {
                        if ( isset($field) ) {                               
                                if ( $field['slug'] == 'price' || $field['slug'] == 'price_from' || $field['slug'] == 'price_to' || $field['slug'] == 'price_free') {
                                        $_listing_id = isset(  $listing_id ) ?  $listing_id : 0;   
                                        $field_in_packages = bello_field_in_packages( $field, $_listing_id );
                                        if ( $field_in_packages == 1 )
                                            break;
                                }
                        }
                }                
                if ( $field_in_packages == 0 )
                    return "";                              
                // end: change - prices and packages - 11.01
               
                
		$price_fixed	= isset($custom_fields["price"]["value"] ) ? $custom_fields["price"]["value"][0] : "";
		$price_from	= isset($custom_fields["price_from"]["value"]) ? $custom_fields["price_from"]["value"][0] : "";
		$price_to	= isset($custom_fields["price_to"]["value"]) ? $custom_fields["price_to"]["value"][0] : "";
                $price_free	= isset($custom_fields["price_free"]["value"]) ? $custom_fields["price_free"]["value"][0] : 0;
                
                if ( $price_from == $price_to ){
                    $price_fixed = $price_from;
                }
                
                BoldThemesFrameworkTemplate::$listing_currency_after_price = boldthemes_get_option( 'listing_currency_after_price' ) ? boldthemes_get_option( 'listing_currency_after_price' ) : ''; 
		$currency_symbol_before = BoldThemesFrameworkTemplate::$listing_currency_after_price ? '' : BoldThemesFrameworkTemplate::$currency_symbol;
                $currency_symbol_after  = BoldThemesFrameworkTemplate::$listing_currency_after_price ? BoldThemesFrameworkTemplate::$currency_symbol : ''; 
                
                BoldThemesFrameworkTemplate::$listing_currency_thousand_separator = boldthemes_get_option( 'listing_currency_thousand_separator' ) ? boldthemes_get_option( 'listing_currency_thousand_separator' ) : '';
                if ( BoldThemesFrameworkTemplate::$listing_currency_thousand_separator != 'none' ) {
                    BoldThemesFrameworkTemplate::$listing_currency_decimal_separator = BoldThemesFrameworkTemplate::$listing_currency_thousand_separator == ',' ? '.' : ',';
                    $price_fixed    = $price_fixed != '' && is_numeric($price_fixed) ? number_format($price_fixed, 2, BoldThemesFrameworkTemplate::$listing_currency_decimal_separator, BoldThemesFrameworkTemplate::$listing_currency_thousand_separator) : '';
                    $price_from     = $price_from  != '' && is_numeric($price_from)  ? number_format($price_from, 2, BoldThemesFrameworkTemplate::$listing_currency_decimal_separator, BoldThemesFrameworkTemplate::$listing_currency_thousand_separator) : '';
                    $price_to       = $price_to    != '' && is_numeric($price_to)    ? number_format($price_to, 2, BoldThemesFrameworkTemplate::$listing_currency_decimal_separator, BoldThemesFrameworkTemplate::$listing_currency_thousand_separator) : '';
                }
                
		$output         = '';
		$output_fixed   = '';
		$output_from    = '';
		$output_to      = '';
               
                if ( intval($price_free) == 1){
                      $output .= '<span class="bt_bb_listing_price">' . esc_html__( 'Free', 'bt_plugin' ) . '</span>';
                      return $output;
                }
                
                $currency_symbol_after = ' ' . $currency_symbol_after;
                
		if ( intval($price_fixed) > 0 ){ 
                    $output_fixed	=  ' ' . esc_html__( 'Price', 'bt_plugin' ) . ' <strong>'. $currency_symbol_before . wp_kses_post($price_fixed) . $currency_symbol_after . '</strong>';
                }else{
                    if ( intval($price_from) > 0 )
                            $output_from	= ' ' . esc_html__( 'From', 'bt_plugin' ) . ' <strong>'. $currency_symbol_before . wp_kses_post($price_from) . $currency_symbol_after . '</strong>';

                    if ( intval($price_to) > 0 ) 
                            $output_to		= ' ' . esc_html__( 'To', 'bt_plugin' ) . ' <strong>'. $currency_symbol_before . wp_kses_post($price_to) . $currency_symbol_after . '</strong>';
                }
		if ( intval($price_fixed) > 0 || intval($price_from) > 0 || intval($price_to) > 0 ) {
			$output .= '<span class="bt_bb_listing_price">' . $output_fixed .  $output_from . $output_to . '</span>';
		}
			
		return $output;
	}
}


/**
 * Listing Comments
 */
if ( ! function_exists( 'boldthemes_comments_listing' ) ) {
	function boldthemes_comments_listing( $listing_id = null  ) {
		$listing_id = isset(  $listing_id ) ?  $listing_id : get_the_ID();
		
		$retComments = array();		
                $comment_args = array(
                        'order'				 => 'DESC',
                        'post_id'			 => $listing_id,
                        'include_unapproved' => ''
                ); 

                $comment_query = new WP_Comment_Query( $comment_args );
                $_comments = $comment_query->comments;				

                $i = 1;
                if (  $_comments  ) {
                        foreach ( $_comments as $_comment ) {						
                                if ( $_comment->comment_approved && $_comment->comment_parent == 0  ) {
                                        array_push($retComments, $_comment);
                                        $i++;
                                }
                        }
                }
		return $retComments;
	}
}

/**
 * Listing Single Header Comments
 */
if ( ! function_exists( 'boldthemes_comments_header_listing_single_comments' ) ) {
	function boldthemes_comments_header_listing_single_comments() {			
		if ( is_singular( 'listing' ) ) {

			if ( comments_open() ) {
				$comment_args = array(
					'order'				 => 'DESC',
					'post_id'			 => get_the_ID(),
					'include_unapproved' => ''
				); 

				$comment_query = new WP_Comment_Query( $comment_args );
				$_comments = $comment_query->comments;
				$i = 1;
				if (  $_comments  ) {
					foreach ( $_comments as $_comment ) {
						if ( $i > 3 )
								break;
						if ( $_comment->comment_approved && $_comment->comment_parent == 0  ) {
							boldthemes_listing_single_header_comment_template($_comment, null, 1);
							$i++;
						}
					}
				}				
			}
		}
	}
}

/**
 * Listing Single Header Comment Template
 */
if ( ! function_exists( 'boldthemes_listing_single_header_comment_template' ) ) {
	function boldthemes_listing_single_header_comment_template($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		if ( '0' != $comment->comment_approved ) {
                        $listing_show_rating	= boldthemes_get_option( 'listing_show_rating' );
			$rating			= $listing_show_rating ? intval( get_comment_meta( $comment->comment_ID, 'rating', true ) ) : 0;
			$comment_title		= get_comment_meta( $comment->comment_ID, 'title', $single = true );
                        
			?>
			<li <?php comment_class(); ?> id="li-header-<?php comment_ID(); ?>">
				<article id="comment-header-<?php comment_ID(); ?>" class = "">
					<?php $avatar_html = get_avatar( $comment, 140 ); 
						if ( $avatar_html != '' ) {
							echo '<div class="commentAvatar">' . wp_kses_post( $avatar_html ) . '</div>';
						}
					?>
					<div class="commentTxt">
						<div class="vcard divider">
							<?php printf( '<h5 class="author"><span class="fn">%1$s</span></h5>', get_comment_author_link() );?>
							<?php if ( $rating > 0 ) { ?>
									<div itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf( esc_html__( 'Rated %d out of 5', 'bt_plugin' ), $rating ) ?>">
										<span style="width:<?php echo wp_kses_post( ( $rating / 5 ) * 100 ); ?>%"><strong><?php echo wp_kses_post( $rating ); ?></strong> <?php esc_html_e( 'out of 5', 'bt_plugin' ); ?></span>
									</div>
							<?php } ?>					
						</div>
					</div>
				</article>
				<div class="commentTitle">
					<p class="comment-title"><?php echo esc_html($comment_title); ?></p>			
				</div>
			</li>
			<?php
		} 
	}
}

/**
 * Custom comments HTML output for listing single
 */
if ( ! function_exists( 'boldthemes_theme_comment_listing' ) ) {
	function boldthemes_theme_comment_listing( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;

		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
				// Display trackbacks differently than normal comments.
				?>
				<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
					<p><?php esc_html_e( 'Pingback:', 'bt_plugin' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( '(Edit)', 'bt_plugin' ), '<span class="edit-link">', '</span>' ); ?></p>
				<?php
				break;
			default :
				// Proceed with normal comments.
				global $post;

				$listing_show_rating	= boldthemes_get_option( 'listing_show_rating' );		
				$rating			= $listing_show_rating ? intval( get_comment_meta( $comment->comment_ID, 'rating', true ) ) : 0;
				$comment_title		= get_comment_meta( $comment->comment_ID, 'title', $single = true );
				$comment_comment	= $comment->comment_content;
                                
				$attachment_html   = '';
				if ( class_exists('BT_Comment_Attachment') ){
					if ( BT_ATT_ENABLED ){
						$images_number = BT_ATT_MAX_IMGS > 0 ? BT_ATT_MAX_IMGS : 4;                                
						$bt_attachment_ids   = get_comment_meta($comment->comment_ID, 'bt_attachment_id', false);
						
						$i = rand(10000, 100000);
						$images = is_array( $bt_attachment_ids ) ? implode( ',', $bt_attachment_ids ) : $bt_attachment_ids; 
						
						if ( $images != "" ) {
							$attachment_html   .= '<div class="commentImages">';
								$attachment_html .= '<div class="btArticleMedia"><div class="btMediaBox">';
									$attachment_html .= do_shortcode( '[bt_comment_imgs el_id="listing_grid_' . $i . '" images="' . $images . '" images_number="' . $images_number . '" columns="4" gap="' . boldthemes_get_option( 'listing_grid_gallery_gap' ) .  '"]' );
								 $attachment_html .= '</div></div>';    
							$attachment_html .= '</div>';
						}
					}
				}
                               
				?>
				<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
					<article id="comment-<?php comment_ID(); ?>" class = "">
						<?php $avatar_html = get_avatar( $comment, 140 ); 
							if ( $avatar_html != '' ) {
								echo '<div class="commentAvatar">' . wp_kses_post( $avatar_html ) . '</div>';
							}
						?>
						<div class="commentTxt">
							<div class="vcard divider">
								<?php
									printf( '<h5 class="author"><span class="fn">%1$s</span></h5>', get_comment_author_link($comment->comment_ID) );
									if ( $rating > 0 ) { ?>
										<div itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf( esc_html__( 'Rated %d out of 5', 'bt_plugin' ), $rating ) ?>">
											<span style="width:<?php echo wp_kses_post( ( $rating / 5 ) * 100 ); ?>%"><strong><?php echo wp_kses_post( $rating ); ?></strong> 
											<?php esc_html_e( 'out of 5', 'bt_plugin' ); ?></span>
										</div>
									<?php }
								?>
							</div>

							<?php if ( '0' == $comment->comment_approved ) : ?>
								<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'bt_plugin' ); ?></p>
							<?php endif; ?>

							<div class="comment">
								<?php
									if ( $comment_title != '' ){
										echo '<p class="comment-title">' . wp_kses_post( $comment_title ) . '</p>';
									}
									if ( $comment_comment != '' ){
										echo '<div class="comment-content"><p>' . wp_kses_post( $comment_comment ) . '</p></div>';
									}	
									if ( strlen($comment_comment) > 300 ) { 
										?>
											<div class="comment-read-further">
												<span data-text-off="<?php esc_html_e( 'Keep reading', 'bt_plugin' ); ?>" data-text-on="<?php esc_html_e( 'Collapse review', 'bt_plugin' ); ?>"><?php esc_html_e( 'Keep reading', 'bt_plugin' ); ?></span>
											</div>
										<?php 
									}
                                                                        
									if ( $attachment_html != '' ) {
										echo $attachment_html;
									}
									if ( comments_open() ) {
										echo '<p class="posted"><span>' . sprintf( esc_html__( '%s', 'bt_plugin' ), get_comment_date() ) . '</span></p>';
										echo '<p class="reply">';
											comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Reply', 'bt_plugin' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
										echo '</p>';								
									}
									edit_comment_link( esc_html__( 'Edit', 'bt_plugin' ), '<p class="edit-link">', '</p>' ); 
								?>
							</div>
						</div>
					</article>
				<?php
			break;
		endswitch;
	}
}

/**
 * Custom comments average review box HTML output for listing single header
 */
if ( ! function_exists( 'boldthemes_get_average_ratings_html' ) ) {
	function boldthemes_get_average_ratings_html($listing_id) {

                $listing_show_rating	= boldthemes_get_option( 'listing_show_rating' );
                
                $rating = boldthemes_get_average_rating($listing_id);	
                if ( !empty($rating) ) {
                        $average	= $rating["rating"];
                        $total		= $rating["total"];
                        $no		= $rating["no"];
                        
                        if ( $average > 0 && $no > 0 ){
                        ?>
                        <li class="comment comment-sum">
                                <?php if ( $listing_show_rating ) { ?>
                                <div class="allComments">
                                        <p><?php esc_html_e( 'Average review score:', 'bt_plugin' ); ?> <strong><?php echo esc_html($average);?></strong> <?php esc_html_e( 'for', 'bt_plugin' ); ?>
                                        <strong><?php echo esc_html($no);?></strong> <?php esc_html_e( 'reviews', 'bt_plugin' ); ?></p>
                                </div>
                                <?php } ?>
                                <?php if ( comments_open() ) { ?>
                                    <div class="bt_bb_button bt_bb_icon_position_left bt_bb_color_scheme_7 bt_bb_style_clean bt_bb_size_small bt_bb_width_inline bt_bb_shape_inherit bt_bb_align_inherit">
                                            <a href="#btCommentsAll" target="_self" class="bt_bb_link">
                                                    <span class="bt_bb_button_text"><?php esc_html_e( 'Show all reviews', 'bt_plugin' ); ?></span><span data-ico-fontawesome="ï„Ž" class="bt_bb_icon_holder"></span>
                                            </a>
                                    </div>                                
                                    <div class="bt_bb_button bt_bb_icon_position_left bt_bb_color_scheme_3 bt_bb_style_clean bt_bb_size_small bt_bb_width_inline bt_bb_shape_inherit bt_bb_align_inherit">
                                            <a href="#btCommentsForm" target="_self" class="bt_bb_link">
                                            <span class="bt_bb_button_text"><?php esc_html_e( 'Write a review', 'bt_plugin' ); ?></span><span data-ico-fontawesome="ï�€" class="bt_bb_icon_holder"></span>
                                            </a>
                                    </div>
                                <?php } ?>
                        </li>
                        <?php
                        }
                }
               
	}
}

if ( ! function_exists( 'boldthemes_get_new_media_html' ) ) {
	function boldthemes_get_new_media_html( $arg = array() ) { 

		$type = isset( $arg['type'] ) ? $arg['type'] : '';

		$featured_image = isset( $arg['featured_image'] ) ? $arg['featured_image'] : '';
		$images = isset( $arg['images'] ) ? $arg['images'] : '';
		$format = isset( $arg['format'] ) ? $arg['format'] : '';
		$gallery_type = isset( $arg['gallery_type'] ) ? $arg['gallery_type'] : '';
		$video = isset( $arg['video'] ) ? $arg['video'] : '';
		$audio = isset( $arg['audio'] ) ? $arg['audio'] : '';
		$quote = isset( $arg['quote'] ) ? $arg['quote'] : '';
		$quote_author = isset( $arg['quote'] ) ? $arg['quote_author'] : '';
		$link_title = isset( $arg['quote'] ) ? $arg['link_title'] : '';
		$link_url = isset( $arg['quote'] ) ? $arg['link_url'] : '';
		$size = isset( $arg['size'] ) ? $arg['size'] : 'full';

		$html = '';
		
		if ( $video != '' ) {
			
			$hw = 9 / 16;
			
			$html = '<div class="btMediaBox video" data-hw="' . esc_attr( $hw ) . '"><div class="bt-video-container">';

			if ( strpos( $video, 'vimeo.com/' ) > 0 ) {
				$video_id = substr( $video, strpos( $video, 'vimeo.com/' ) + 10 );
				$html .= '<ifra' . 'me src="' . esc_attr( 'http://player.vimeo.com/video/' . $video_id ) . '" allowfullscreen></ifra' . 'me>';
			} else {
				$yt_id_pattern = '~(?:http|https|)(?::\/\/|)(?:www.|)(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/ytscreeningroom\?v=|\/feeds\/api\/videos\/|\/user\S*[^\w\-\s]|\S*[^\w\-\s]))([\w\-]{11})[a-z0-9;:@#?&%=+\/\$_.-]*~i';
				$youtube_id = ( preg_replace( $yt_id_pattern, '$1', $video ) );
				if ( strlen( $youtube_id ) == 11 ) {
					$html .= '<ifra' . 'me width="560" height="315" src="' . esc_attr( 'https://www.youtube.com/embed/' . $youtube_id ) . '" allowfullscreen></ifra' . 'me>';
				} else {
					$html = '<div class="btMediaBox video" data-hw="' . esc_attr( $hw ) . '">';				
					$html .= do_shortcode( $video );
				}
			}
			$html .= '</div></div>';
			
		} else if ( $audio != '' ) {
		
			if ( strpos( $audio, '</ifra' . 'me>' ) > 0 ) {
				$html = '<div class="btMediaBox audio">' . wp_kses( $audio, array( 'iframe' => array( 'height' => array(), 'src' =>array() ) ) ) . '</div>';
			} else {
				$html = '<div class="btMediaBox audio">' . do_shortcode( $audio ) . '</div>';
			}
	
		} else if ( $link_url != '' ) {
			$bt_link_bg_image = "";
			if ( $featured_image != '' ) $bt_link_bg_image .= " style='background-image: url(" . esc_url_raw( $featured_image ) . ")'";
			$html = '<div class="btMediaBox btDarkSkin btLink"' . $bt_link_bg_image . '><blockquote><p><a href="' . esc_url_raw( $link_url ) . '">' . wp_kses_post( $link_title ) . '</a></p><cite><a href="' . esc_url_raw( $link_url ) . '">' . wp_kses_post( $link_url ) . '</a></cite></blockquote></div>';
	
		}  else if ( $quote != '' ) {
			$bt_quote_bg_image = "";
			if ( $featured_image != '' ) $bt_quote_bg_image .= " style='background-image: url(" . esc_url_raw( $featured_image ) . ")'";
			$html = '<div class="btMediaBox btQuote btDarkSkin"' . $bt_quote_bg_image . '><blockquote><p>' . wp_kses_post( $quote ) . '</p><cite>' . wp_kses_post( $quote_author ) . '</cite></blockquote></div>';
	
		} else if ( count( $images ) > 0 ) {
			if ( $gallery_type == 'carousel' ) {
				$html = '<div class="btMediaBox">';
					if ( shortcode_exists( 'bt_bb_slider' ) ) {
						$image_ids = array();
						foreach( $images as $image ) {
							$image_ids[] = $image['ID'];
						}
						$html .= do_shortcode( '[bt_bb_slider images="' . implode( ',', $image_ids ) . '" show_dots="bottom" height="auto" auto_play="3000"]' );
					}
				$html .= '</div>';
			} else {
				$html = '<div class="btMediaBox">';
					if ( shortcode_exists( 'bt_bb_masonry_image_grid' ) ) {
						$image_ids = array();
						foreach( $images as $image ) {
							$image_ids[] = $image['ID'];
						}
						$prefix = 'blog';
						if ( $type == 'single-portfolio' ) {
							$prefix = 'pf';
						}
						$html .= do_shortcode( '[bt_bb_masonry_image_grid images="' . implode( ',', $image_ids ) . '" columns="' . boldthemes_get_option( $prefix . '_grid_gallery_columns' ) .  '" gap="' . boldthemes_get_option( $prefix . '_grid_gallery_gap' ) .  '"]' );
					}
				$html .= '</div>';
			}
		} else if ( $featured_image != '' ) {
			$html = '<div class="btMediaBox"><img src="' . esc_attr( $featured_image ) . '" alt="' . esc_attr( $featured_image ) . '"/></div>';
		}

		return $html;
		
	}
}


if ( ! function_exists( 'boldthemes_get_new_media_html_listing' ) ) {
	function boldthemes_get_new_media_html_listing( $arg = array() ) { 
		$type			= isset( $arg['type'] ) ? $arg['type'] : '';
		$featured_image         = isset( $arg['featured_image'] ) ? $arg['featured_image'] : '';
		$galleries		= isset( $arg['images'] ) ? $arg['images'] : '';
		$format			= isset( $arg['format'] ) ? $arg['format'] : '';
		$gallery_type           = isset( $arg['gallery_type'] ) ? $arg['gallery_type'] : '';
		$videos			= isset( $arg['video'] ) ? $arg['video'] : '';
		$audios			= isset( $arg['audio'] ) ? $arg['audio'] : '';
		$quote			= isset( $arg['quote'] ) ? $arg['quote'] : '';
		$quote_author           = isset( $arg['quote_author'] ) ? $arg['quote_author'] : '';
		$link_title		= isset( $arg['link_title'] ) ? $arg['link_title'] : '';
		$link_url		= isset( $arg['link_url'] ) ? $arg['link_url'] : '';
		$size			= isset( $arg['size'] ) ? $arg['size'] : 'full';

		$showinfo		= isset( $arg['showinfo'] ) ? $arg['showinfo'] : '1';
		$term_id		= isset( $arg['term_id'] ) ? $arg['term_id'] : 0;
                
                
		$html = '';		
		 
		if (  is_array( $videos ) ) {
                    $html .= '<div class="btArticleMediaVideos">';
                        foreach( $videos as &$field ) {	                                   
                                $field['showinfo'] = $showinfo;
                                $html .= bello_show_field( $field, '1' );					
                        }
                    $html .= '</div>';
		} 
		
		if (  is_array( $audios ) ) {	
                    $html .= '<div class="btArticleMediaAudios">';
                        foreach( $audios as &$field ) {
                            $field['showinfo'] = $showinfo;                            
                            $html .= bello_show_field( $field, '1' );
                        }
                    $html .= '</div>';                   
		} 

		if ( $link_url != '' ) {		
			$html .= '<div class="btMediaBox btDarkSkin btLink"><blockquote><p><a href="' . esc_url_raw( $link_url ) . '">' . wp_kses_post( $link_title ) . '</a></p><cite><a href="' . esc_url_raw( $link_url ) . '">' . wp_kses_post( $link_url ) . '</a></cite></blockquote></div>';	
		} 
		
		if ( $quote != '' ) {		
			$html .= '<div class="btMediaBox btQuote btDarkSkin"><blockquote><p>' . wp_kses_post( $quote ) . '</p><cite>' . wp_kses_post( $quote_author ) . '</cite></blockquote></div>';	
		}
               

		if ( is_array( $galleries ) ) {
			$gallery_fields_in_packages = array();
			foreach( $galleries as &$gallery ) {
				if (  bello_field_in_packages( $gallery ) == 1 ){
					array_push( $gallery_fields_in_packages, $gallery );
				}
			}                        
                        $html .= bello_show_field_galleries( $gallery_fields_in_packages, $showinfo, $gallery_type );		
		}   
		return $html;
		
	}
}

/**
 * Get post author
 */
if ( ! function_exists( 'boldthemes_get_post_author' ) ) {
	function boldthemes_get_post_author( $author_url = false ) {
		$post = get_post();
		$post_author_id = $post->post_author;
		if ( ! $author_url ) {
			$author_url = get_author_posts_url( get_the_author_meta( 'ID', $post_author_id ) );
		}
		return '<a href="' . esc_url_raw ( $author_url ) . '" class="btArticleAuthor">' .  esc_html( get_the_author_meta( 'display_name', $post_author_id )  ) . '</a>';
	}
}

/**
 * Get more listings widgets in content area
 */
if ( ! function_exists( 'bello_get_listing_groups_more_widgets_html' ) ) {
    function bello_get_listing_groups_more_widgets_html() {
        if ( function_exists( 'bello_get_listing_field_groups' ) ) {
            $widgets = bello_get_listing_field_groups();
            if ($widgets){
                ?>
                <div class="btListingContentWidgets">
                    <div class="bt_bb_wrapper">         
                        <?php
                        foreach ($widgets as $key => $value ) {
                             $args['before_widget']  = '<div class="btListingContentWidget">';
                             $args['after_widget']   = '</div>';
                             $args['before_title']   = '<h6>';
                             $args['after_title']    = '</h6>';
                             //$instance['title']      = $key;
                             $instance['title']      = $value;
                             $instance['slug']       = $value;
                             $instance['content']    = 1;
                             the_widget( 'BT_Listing_Widget', $instance, $args );
                        }
                        ?>
                    </div>
                </div>                         
                <?php
            }
        }
    }
}

if ( ! function_exists( 'bello_get_listing_subtitle_html' ) ) {
    function bello_get_listing_subtitle_html( $listing_id = 0 ) {
        $listing_show_subtitles_in_search   = boldthemes_get_option( 'listing_show_subtitles_in_search' ) != '' ? boldthemes_get_option( 'listing_show_subtitles_in_search' )
                        : BoldThemes_Customize_Default::$data['listing_show_subtitles_in_search'];
        $listing_subtitle = boldthemes_rwmb_meta( 'boldthemes_theme_listing-subtitle', array(), $listing_id );
        
        if ( $listing_subtitle != '' && $listing_show_subtitles_in_search ) {             
            echo '<div class="bt_bb_listing_subtitle">';
                    echo esc_html($listing_subtitle);
            echo '</div>';
        } 
    } 
}