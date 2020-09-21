<?php

if ( isset($field) ) {    
	if ( isset($field['name']) && isset($field['value']) ) { 
            
            $default_lat = boldthemes_get_option( 'listing_search_distance_lat' )  != '' ? boldthemes_get_option( 'listing_search_distance_lat' ) : '0';
            $default_lng = boldthemes_get_option( 'listing_search_distance_lng' )  != '' ? boldthemes_get_option( 'listing_search_distance_lng' ) : '0';

            $map_str	= $field['value'][0];
            $map_arr	= explode( ",", $map_str );
            $lat		= isset( $map_arr[0] ) ? $map_arr[0] : $default_lat;
            $lng		= isset( $map_arr[1] ) ? $map_arr[1] : $default_lng;
            $zoom		= '14';

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
            $custom_map_style       = boldthemes_get_option( 'custom_map_style' )       != '' ? boldthemes_get_option( 'custom_map_style' )     : '';
                
           if ( $field['type'] == 'map' && bt_map_is_osm() ){
               require_once( 'map_openmap.php' );               
           } else if ( $field['type'] == 'map' && bt_map_is_leaflet() ) {
               require_once( 'map_leafletmap.php' );
           }else{
                $random = rand();
                $el_class_name = 'bt_bb_listing_search_google_map_random_id_' . $random;
                $listing_api_key            = boldthemes_get_option( 'listing_api_key' ) != '' ? boldthemes_get_option( 'listing_api_key' ) : '';
                $listing_map_localization   = boldthemes_get_option( 'listing_map_localization' ) != '' ? boldthemes_get_option( 'listing_map_localization' ) : 'en';
                $listing_map_type           = boldthemes_get_option( 'listing_map_type' ) != '' ? boldthemes_get_option( 'listing_map_type' ) : 'dynamic';
                        
                $show_dynamic = $listing_map_type == 'dynamic' ? true : false;
                if ( $show_dynamic ){
                    if ( $listing_api_key != '' ) {
                            wp_enqueue_script( 
                                    'gmaps_api',
                                    'https://maps.googleapis.com/maps/api/js?v=3.38&key=' . $listing_api_key . '&callback=bt_bb_gmap_init_'.$random.'&language=' . $listing_map_localization . '#asyncload',
                                    array(), 
                                    '', 
                                    true 
                            );
                    } else {
                            wp_enqueue_script( 
                                    'gmaps_api',
                                    'https://maps.googleapis.com/maps/api/js?v=3.38&&callback=bt_bb_gmap_init_'.$random.'&language=' . $listing_map_localization . '#asyncload'
                            );
                    }
                }else{
                    $listing_single_list_header_view = boldthemes_get_option( 'listing_single_list_header_view' ) != '' ? boldthemes_get_option( 'listing_single_list_header_view' ) : 'standard';
                    // 664x280 , 672x502
                    $static_dimensions = '800x340';
                    switch($listing_single_list_header_view){
                        case 'standard':$static_dimensions = '800x340';break;
                        case 'standard_review':$static_dimensions = '800x340';break;
                        case 'full_screen':$static_dimensions = '800x340';break;
                        case 'image_map':$static_dimensions = '800x800';break;
                        default:$static_dimensions = '800x340';break;
                    }
                }

                ?>        
		<div class="widget_bt_bb_listing_marker_map <?php echo $field['group'];?>">
			<div class="widget_bt_bb_listing_marker_map_wrapper">
				<div id="bt_bb_listing_search_google_map" class="<?php echo $el_class_name;?>">
					<?php if ( !$show_dynamic ){ ?>
					 <span class="bt_bb_widget_static_map"><img border="0" src="https://maps.googleapis.com/maps/api/staticmap?zoom=<?php echo $zoom;?>&size=<?php echo $static_dimensions;?>&language=<?php echo $listing_map_localization;?>&maptype=roadmap&markers=icon:<?php echo $listing_pin_selected;?>|<?php echo $lat;?>,<?php echo $lng;?>&key=<?php echo $listing_api_key;?>" alt="<?php esc_html_e( get_the_title(), 'bt_plugin' ); ?>"></span>
					 <?php } ?>
				</div>
			</div>	
		</div>
                <?php if ( $show_dynamic ){ ?>
                        <script>
                                
                                
                                var map;
                                var custom_style = '';

                                function bt_bb_gmap_init_<?php echo $random;?>() { 
                                                                      
                                      var mapClass = jQuery('.<?php echo $el_class_name;?>');
                                      
                                      //map = new google.maps.Map(document.getElementById('bt_bb_listing_search_google_map'), {
                                      map = new google.maps.Map(mapClass[0], {
                                        zoom: <?php echo $zoom;?>,
                                        center: new google.maps.LatLng(<?php echo $lat;?>, <?php echo $lng;?>),
                                        gestureHandling: 'greedy',
                                        mapTypeId: 'roadmap',
                                        zoomControl: true,
                                        mapTypeControl: false,
                                        scaleControl: false,
                                        rotateControl: false,
                                        fullscreenControl: false,
                                        fullscreenControlOptions: {
                                              position: google.maps.ControlPosition.LEFT_TOP
                                        },
                                        streetViewControl: false,
                                        streetViewControlOptions: {
                                              position: google.maps.ControlPosition.LEFT_BOTTOM
                                        }
                                      });

                                       <?php if ($custom_map_style != '') { ?>
                                         var styledMapType = new google.maps.StyledMapType(                                                        
                                              <?php echo $custom_map_style;?>,                                                       
                                              {name: 'Styled Map'}
                                         );

                                         map.mapTypes.set('custom_style', styledMapType);
                                         map.setMapTypeId('custom_style');
                                       <?php } ?>


                                      var icons = {
                                        normal: {
                                              icon: '<?php echo $listing_pin_normal;?>'
                                        },
                                        selected: {
                                              icon: '<?php echo $listing_pin_selected;?>'
                                        }
                                      };

                                      var features = [
                                        {
                                              position: new google.maps.LatLng(<?php echo $lat;?>, <?php echo $lng;?>),
                                              type: 'selected'
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
            <?php } } ?>
            <?php
            
             $listing_show_map_shortcuts  = boldthemes_get_option( 'listing_show_map_shortcuts' ) != '' ? true : false;               
             if ( $listing_show_map_shortcuts ) {
             ?>
                    <div class="widget_bt_bb_listing_marker_options">	
                        <ul>
                                <?php if ( function_exists( 'bt_simple_favorites_button' ) ) { 
                                        echo '<li>' .  bt_simple_favorites_button( get_the_ID() ) . '</li>';
                                } ?>

                                <?php if ( !empty($map_arr) ){ ?>
                                        <li>
                                                <a href="https://www.google.com/maps/dir//<?php echo $lat;?>,<?php echo $lng;?>/@<?php echo $lat;?>,<?php echo $lng;?>,<?php echo $zoom;?>z" class="bt_bb_listing_marker_get_directions" target="_blank">
                                                        <span><?php esc_html_e( 'Get directions', 'bt_plugin' ); ?></span>
                                                </a>
                                        </li>
                                <?php } ?>
                                <?php 
                                $comments_open = comments_open();
                                if ( $comments_open ) { ?>
                                <li>
                                        <a href="#btCommentsForm" class="bt_bb_listing_marker_write_review"><span><?php esc_html_e( 'Write a review', 'bt_plugin' ); ?></span>
                                                <em class="bt_bb_listing_marker_small_circle"><?php echo boldthemes_get_all_comments_of_post_type('listing', get_the_ID());?></em>
                                        </a>
                                </li>
                                <?php } ?>
                                <?php if ( $boldthemes_theme_listing_contact_phone_link != '') { ?>
                                        <li>
                                                <a href="tel:<?php echo $boldthemes_theme_listing_contact_phone_link;?>" class="bt_bb_listing_marker_make_call">
                                                        <span><?php esc_html_e( 'Make a call', 'bt_plugin' ); ?></span>
                                                </a>
                                        </li>
                                <?php } ?>
                                <?php if ( (empty($map_arr)) || ( $boldthemes_theme_listing_contact_phone_link != '' || !comments_open() ) ) { ?>
                                        <li></li>  
                                <?php } ?>	
                        </ul>
                </div>
            <?php   
          }
    }
}