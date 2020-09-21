<div id="<?php echo esc_attr( $el_id ); ?>" class="<?php echo esc_attr( implode( ' ', $class ) ); ?>"<?php if ( $el_style != '' ) { ?> style="<?php echo esc_attr( $el_style ); ?>" <?php } ?>data-columns="<?php echo esc_attr( $columns );?>">
<div class="bt_bb_listing_view_as_grid bt_bb_listing_view_as_list <?php echo esc_attr( $type );?>">

    <?php
    
    $show_title     = isset($show["title"]) && $show["title"] == 1 ? $show["title"] : 0;
    $show_featured  = isset($show["featured"]) && $show["featured"] == 1 ? $show["featured"] : 0;
    $show_sorting  = isset($show["sorting"]) && $show["sorting"] == 1 ? $show["sorting"] : 0;
    
    $search_orderby    = 'post_date';	
    $search_order      = 'DESC';
   
    if ( $sorting != '' && intval($sorting) > 0 ){
                switch ( $sorting ){
                   case '0':	$listing_orderby = 'post_date';	$listing_order = 'DESC';break;//Date, A-Z
                   case '1':	$listing_orderby = 'post_date';	$listing_order = 'ASC';	break;//Date, Z-A
                   case '2':	$listing_orderby = 'post_title';$listing_order = 'ASC';	break;//Name, A-Z
                   case '3':	$listing_orderby = 'post_title';$listing_order = 'DESC';break;//Name, Z-A
                   case '10':	$listing_orderby = 'post_name__in';     $listing_order = 'ASC';break;//Listings slugs
                   default:	$listing_orderby = 'post_date';	$listing_order = 'DESC';break;//Date, A-Z
               }
               $search_orderby    = $listing_orderby;
               $search_order      = $listing_order;
       }
    
    $search_post_slug = $listing;  
	
    if ( $category != '' && $region != "" ) {		
			$operator	= 'IN';
			$field		= 'slug';
			$cat_slug_arr = explode( ',', $category );
			$TxQuery = array(
					'relation' => 'AND',
					array(
							'taxonomy'      => 'listing-category', 
							'terms'			=> $cat_slug_arr,
							'field'			=> $field,
							'include_children'	=> true,
							'operator'		=> $operator
					),
					array(
							'taxonomy'      => 'listing-region',
							'terms'			=> $region,
							'field'			=> $field,
							'include_children'	=> true,
							'operator'		=> $operator
					),
			);
			/* wp query */	  
			$args = array(
					'post_type'                     => 'listing',
					'post_status'                   => 'publish',
					'order'                         => $search_order,
					'posts_per_page'                => $number,
					'tax_query'                     => $TxQuery,
			);
			$listing_query	= new WP_Query($args);
			$listings   = $listing_query->posts;
			//var_dump($listing_query->request);
			wp_reset_postdata();
			reset($listings);
			
			/* show only featured listings in serach results */			
			if ( $show_featured ){
				$listings_featured = array();
				foreach ( $listings as &$listing ) {
					$listing->featured = bello_listing_is_featured( $listing ) ? 1 : 0;	
					if ( $listing->featured == 1 ) {
							array_push( $listings_featured, $listing );
					}
				}			
				$listings =  $listings_featured;			
			}
			/* /show only featured listings in serach results */
	
		
	} else if ( $category != '' ) {
		$cat_slug_arr = explode( ',', $category ); 
		$params =  array( 
                    'taxonomy'      => 'listing-category', 
                    'listing_type'  => $cat_slug_arr, 
                    'limit'         => $number,
                    'orderby'       => $search_orderby, 
                     'order'        => $search_order,
                    'search_post_slug'   => $listing,
                    'featured_listing_bb_element' => 1
                );
				$listings = boldthemes_get_query_listings( $params, $show_featured, 0);   
	} else if ( $region != "") {
		$params =  array( 
                    'taxonomy'      => 'listing-region',
					'listing_type'  => $region, 
					'limit'         => $number,
					'orderby'       => $search_orderby, 
					'order'         => $search_order,
					'search_post_slug'   => $listing,
                    'featured_listing_bb_element' => 1
                 );
				$listings = boldthemes_get_query_listings( $params, $show_featured, 0);   
    } else {
		$params =  array( 
                    'taxonomy'      => 'listing-category', 
                    'limit'         => $number,
                    'orderby'       => $search_orderby, 
                    'order'         => $search_order,
                    'search_post_slug'   => $listing,
                    'featured_listing_bb_element' => 1
                 );
				$listings = boldthemes_get_query_listings( $params, $show_featured, 0);   
    }
    
    
    
    foreach( $listings as $listing ) {
            $id = get_post_thumbnail_id( $listing->ID );
            $img = wp_get_attachment_image_src( $id, 'boldthemes_listing_image' );
            $img_default    = bello_get_listing_default_image( 'boldthemes_listing_image' );
            
            $img_src = isset($img[0]) ? $img[0] : $img_default;
            $hw = 0;           
            if ( isset($img[1]) && isset($img[2]) ) {
                    $hw = $img[2] / $img[1];
            }
            $img_full = wp_get_attachment_image_src( $id, 'full' );	
            $img_src_full = isset($img_full[0]) ? $img_full[0] : $img_default;
            
            if ( function_exists( 'bt_is_https' ) ) {
                    if ( bt_is_https() ) {
                            $url_img_src       = parse_url($img_src);                                
                            if( isset($url_img_src['scheme']) && $url_img_src['scheme'] != 'https'){
                                $img_src       = str_replace( 'http', 'https', $img_src);
                            }
                            $url_img_src_full  = parse_url($img_src_full);
                            if( isset($url_img_src_full['scheme']) &&  $url_img_src_full['scheme'] != 'https'){
                                $img_src_full  = str_replace( 'http', 'https', $img_src_full);
                            }
                    }
            }

            $custom_fields		= get_post_custom( $listing->ID );
            $listing_fields		= bello_get_listing_fields( array( 'listing_id' => $listing->ID ) );
            $listing_categories = get_the_terms( $listing->ID, 'listing-category' );
            $listing_region		= get_the_terms( $listing->ID, 'listing-region' );
            $listing_region		= isset($listing_region[0]) ? $listing_region[0] : '';

            $boldthemes_theme_listing_contact_phone		= '';	
            $boldthemes_theme_listing_contact_phone_link	= '';
            $contact_location_position			= '';
            $working_times					= '';

            if ( isset($custom_fields['boldthemes_theme_listing-contact_phone']) && isset($listing_fields['contact_phone']) ){		
                    if ( bello_field_in_packages( $listing_fields['contact_phone'], $listing->ID) ) {
                            $boldthemes_theme_listing_contact_phone		= boldthemes_rwmb_meta('boldthemes_theme_listing-contact_phone', array(), $listing->ID );
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

            if ( isset($custom_fields['boldthemes_theme_listing-location_position']) && isset($listing_fields['location_position']) ){	
                    if ( bello_field_in_packages( $listing_fields['location_position'], $listing->ID) ) {	
                            $contact_location_position	= boldthemes_rwmb_meta('boldthemes_theme_listing-location_position', array(), $listing->ID );
                    }
            }

            if ( isset($custom_fields['boldthemes_theme_listing-working_time']) && isset($listing_fields['working_time']) ){	
                    if ( bello_field_in_packages( $listing_fields['working_time'], $listing->ID) ) {	
                            $working_times	= boldthemes_rwmb_meta('boldthemes_theme_listing-working_time', array(), $listing->ID);
                    }
            }

            $map_arr	= explode(",", $contact_location_position);
            $latitudeTo	= isset($map_arr[0]) ? $map_arr[0] : '0';
            $longitudeTo	= isset($map_arr[1]) ? $map_arr[1] : '0';
            $zoom		= isset($map_arr[2]) ? $map_arr[2] : '15';

            $open_hours         = bt_open_hours( $listing->ID );
            $current_open_hours = bt_open_hours_current_day( $listing->ID );

            $featured_class =  $listing->featured ? 'bt_bb_listing_featured' : '';

            $listing_search_distance_unit = boldthemes_get_option( 'listing_search_distance_unit' ) != '' ? boldthemes_get_option( 'listing_search_distance_unit' ) : 'mi';
    ?>
    <div class="bt_bb_listing_box <?php echo esc_attr( $featured_class ); ?>" data-postid="<?php echo esc_attr($listing->ID); ?>" data-posturl="<?php echo esc_url_raw( get_permalink( $listing->ID ) ); ?>">
            <div class="bt_bb_listing_box_inner">
                    <a href="<?php echo esc_url_raw( get_permalink( $listing->ID ) ); ?>"></a>
                    <div class="bt_bb_listing_image">
                        <div class="bt_bb_listing_top_meta">
                                <?php if ( ! empty( $listing_categories ) ) { ?>
                                    <div class="bt_bb_latest_posts_item_category">
                                            <ul class="post-categories">
                                                    <?php foreach ( $listing_categories as $listing_category ) { ?>
							<li><a href="<?php echo get_term_link($listing_category);?>" rel="category tag"><?php echo esc_html( $listing_category->name ); ?></a></li>	
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
                                            <span class="<?php echo esc_attr($bt_bb_listing_favourite_class);?>"><?php esc_html_e( 'Add to favourite', 'bello' ); ?></span>
                                    </div>
                                 <?php } ?>
                            
                            </div>
                            <div class="bt_bb_listing_photo">
                                    <img src="<?php echo esc_url_raw($img_src);?>" alt="<?php echo esc_attr($listing->post_title);?>">
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
                                                    <small class="bt_bb_listing_working_hours"><?php esc_html_e( 'Now closed', 'bello' ); ?></small>
                                            <?php }else{ 
                                                    $current_open_hours_text = '';							
                                                    if ( !empty($current_open_hours) ) {
                                                            $current_open_hours_text	= $current_open_hours[0];
                                                            if ( $current_open_hours[1] != '' ) { $current_open_hours_text	.= ' - ' . $current_open_hours[1];}
                                                            if ( $current_open_hours[2] != '' ) { $current_open_hours_text	.= '   ' . $current_open_hours[2];}
                                                            if ( $current_open_hours[3] != '' ) { $current_open_hours_text	.= ' - ' . $current_open_hours[3];}
                                                    }
                                                    ?>
                                                    <small class="bt_bb_listing_working_hours bt_bb_listing_working_hours_open"><?php echo wp_kses_post($current_open_hours_text);?></small>							
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
                            </div>

                            <div class="bt_bb_listing_rating">
                                    <?php echo boldthemes_rating_header_listing_single( $listing->ID );?>
                            </div>

                            <div class="bt_bb_listing_excerpt">
                                    <div class="bt_bb_listing_excerpt_description">
                                            <?php echo esc_html($listing->post_excerpt);?>
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
    ?>
</div>
</div>