<?php
class bt_bb_featured_listings extends BT_BB_Element {
        
	function __construct() {
		parent::__construct(  );
		add_action( 'wp_ajax_bt_bb_get_featured_listing', array( __CLASS__, 'bt_bb_get_featured_listing_callback' ) );
		add_action( 'wp_ajax_nopriv_bt_bb_get_featured_listing', array( __CLASS__, 'bt_bb_get_featured_listing_callback' ) );
	}

	static function bt_bb_get_featured_listing_callback() {	
        check_ajax_referer( 'bt-bb-featured-listings-nonce', 'bt-nonce' );
		if(isset($_POST['listing']) && $_POST['listing'] != "") {
			echo bt_bb_featured_listings::dump_grid( 
							intval( $_POST['number'] ), 
							sanitize_text_field( $_POST['category'] ), 
							$_POST['show'], 
							sanitize_text_field( $_POST['format'] ),
							sanitize_text_field( $_POST['region'] ),
							intval( $_POST['sorting'] ),
							sanitize_text_field( $_POST['listing'] )
					);
		} else {
			echo bt_bb_featured_listings::dump_grid( 
							intval( $_POST['number'] ), 
							sanitize_text_field( $_POST['category'] ), 
							$_POST['show'], 
							sanitize_text_field( $_POST['format'] ),
							sanitize_text_field( $_POST['region'] ),
							intval( $_POST['sorting'] ),
							''
					);
		}
                	
		die();
	}

	static function dump_grid( $number, $category, $show, $format, $region, $sorting, $listing ) {
                
		$show           = unserialize( urldecode( $show ) );
		$show_title     = isset($show["title"]) ? $show["title"] : 0;
		$show_featured  = isset($show["featured"]) ? $show["featured"] : 0;
		$show_sorting   = isset($show["sorting"]) ? $show["sorting"] : 0;

		$search_orderby    = 'post_date';	
		$search_order      = 'DESC';
		if ( $sorting != '' && intval($sorting) > -1 ){
				 switch ( $sorting ){
					case '0':	$listing_orderby = 'post_date';	$listing_order = 'ASC';break;
					case '1':	$listing_orderby = 'post_date';	$listing_order = 'DESC';break;
					case '2':	$listing_orderby = 'post_title';$listing_order = 'ASC';	break;
					case '3':	$listing_orderby = 'post_title';$listing_order = 'DESC';break;
					case '10':	$listing_orderby = 'post_name__in';$listing_order = 'ASC';break;
					default:	$listing_orderby = 'post_date';	$listing_order = 'DESC';break;
				}
				$search_orderby    = $listing_orderby;
				$search_order      = $listing_order;
		}

		$listing_region	= $region != '' ? $region : '';
		$output = ''; 
		if ( $category != '' && $listing_region != "" ) {			
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
							'terms'			=> $listing_region,
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
			/* show only featured listings in serach results */
			 
		 } else if ( $category != '' ){
			$cat_slug_arr = explode( ',', $category );                        
			$listings = boldthemes_get_query_listings( array( 
					'taxonomy'      => 'listing-category', 
					'listing_type'  => $cat_slug_arr,
					'taxonomy2'     => 'listing-region',
					'listing_type2' => $listing_region, 
					'limit'         => $number,
					'orderby'       => $search_orderby, 
					'order'         => $search_order,
					'search_post_slug'   => $listing//slugs
				), 
				$show_featured, 
				0
			 );	
		}else{
			 $listings = boldthemes_get_query_listings( array( 
					'taxonomy'      => 'listing-region',
					'listing_type'  => $listing_region, 
					'limit'         => $number,
					'orderby'       => $search_orderby, 
					'order'         => $search_order,
					'search_post_slug'   => $listing//slugs
				), 
				$show_featured, 
				0
			 );	
		}
                
		$format_arr = array();
		if ( $format != '' ) {
			$format_arr = explode( ',', $format );
		}		
                
		$n = 0;
		foreach( $listings as $listing ) {
			$id             = get_post_thumbnail_id( $listing->ID );
			$img            = wp_get_attachment_image_src( $id, 'boldthemes_medium_square' );
			$img_default    = bello_get_listing_default_image( 'boldthemes_medium_square' );
			$hw             = 1;
			if ( isset( $format_arr[ $n ] ) ) {
				switch ( $format_arr[ $n ] ){
					case '11': 
						$img            = wp_get_attachment_image_src( $id, 'boldthemes_medium_square' );//640, 640
                                                $img_default    = bello_get_listing_default_image( 'boldthemes_medium_square' );
                                                $hw = 1;
						break;
					case '21': 
						$img            = wp_get_attachment_image_src( $id, 'boldthemes_listing_image_medium_rectangle' );//640, 320
                                                $img_default    = bello_get_listing_default_image( 'boldthemes_listing_image_medium_rectangle' );
                                                $hw = 0.5;
						break;
					case '12': 
						$img            = wp_get_attachment_image_src( $id, 'boldthemes_listing_image_medium_vertical_rectangle' );//320, 640
                                                $img_default    = bello_get_listing_default_image( 'boldthemes_listing_image_medium_vertical_rectangle' );
                                                $hw = 2;
						break;
					case '22': 
						$img            = wp_get_attachment_image_src( $id, 'boldthemes_large_square' );//1280, 1280
                                                $img_default    = bello_get_listing_default_image( 'boldthemes_large_square' );
                                                $hw = 1;
						break;
					default: 
						$img            = wp_get_attachment_image_src( $id, 'boldthemes_medium_square' );
                                                $img_default    = bello_get_listing_default_image( 'boldthemes_medium_square' );
                                                $hw = 1;
						break;
				}
			}
                        
            $img_src = isset($img[0]) ? $img[0] : $img_default;
			$hw = isset($img[0]) ? 0 : $hw;
                        
			if ( isset($img[1]) && isset($img[2]) ) {
				$hw = $img[2] / $img[1];
			}
                        
			$img_full = wp_get_attachment_image_src( $id, 'full' );	
			$img_src_full = isset($img_full[0]) ? $img_full[0] : $img_default;                       

		   if ( function_exists( 'bt_is_https' ) ) {
					if ( bt_is_https() ) {
							$url_img_src    = parse_url($img_src);                                
							if( isset($url_img_src['scheme']) && $url_img_src['scheme'] != 'https' ){
								$img_src       = str_replace( 'http', 'https', $img_src);
							}
							$url_img_src_full  = parse_url($img_src_full);
							if( isset($url_img_src_full['scheme']) && $url_img_src_full['scheme'] != 'https' ){
								$img_src_full  = str_replace( 'http', 'https', $img_src_full);
							}
					}
			}

			$tile_format = 'bt_bb_tile_format';
			if ( isset( $format_arr[ $n ] ) ) {				
				if ( $format_arr[ $n ] == '21' || $format_arr[ $n ] == '12' || $format_arr[ $n ] == '22' ) {
					$tile_format .= "_" . $format_arr[ $n ];
				} else {
					$tile_format .= '11';
				}
			}else{
				$tile_format .= '11'; 
			 }


			$custom_fields		= get_post_custom( $listing->ID );
			$listing_fields		= bello_get_listing_fields( array( 'listing_id' => $listing->ID ) );
			$listing_categories     = get_the_terms( $listing->ID, 'listing-category' );
			$listing_region		= get_the_terms( $listing->ID, 'listing-region' );
			$listing_region		= isset($listing_region[0]) ? $listing_region[0] : '';

			$distance = "0";

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

			$open_hours	    = bt_open_hours( $listing->ID );
			$current_open_hours = bt_open_hours_current_day( $listing->ID );

			$featured_class =  $listing->featured ? 'bt_bb_listing_featured' : '';

			$listing_search_distance_unit = boldthemes_get_option( 'listing_search_distance_unit' ) != '' ? boldthemes_get_option( 'listing_search_distance_unit' ) : 'mi';

			$output .= '<div class="bt_bb_grid_item ' . $tile_format . '" data-hw="' .  esc_attr( $hw )  . '" data-src="' . esc_url_raw( $img_src ) . '" data-src-full="' . esc_url_raw( $img_src_full ) . '" data-title="">							
							<div class="bt_bb_grid_item_inner" data-hw="' . esc_attr( $hw )  . '" >								
								<div class="bt_bb_grid_item_inner_content ">';

									$output .= '
									<div class="bt_bb_listing_box ' . $featured_class . '" data-postid="' . esc_attr( $listing->ID ) . '" data-latitude="' . esc_attr( $latitudeTo ) . '" 
									data-longitude="' . esc_attr( $longitudeTo ) . '" data-posturl="' . esc_url_raw( get_permalink( $listing->ID ) ). '"  data-unit="' . esc_attr( $listing_search_distance_unit ) . '" data-distance="' . esc_attr( $distance ) . '">
										<div class="bt_bb_listing_box_inner">
											<a href="' . esc_url_raw( get_permalink( $listing->ID ) ) . '"></a>';

												$output .= '<div class="bt_bb_listing_image">												
													<div class="bt_bb_listing_top_meta">';
															if ( ! empty( $listing_categories ) ) { 
																	$output .= '<div class="bt_bb_latest_posts_item_category">';
																			$output .= '<ul class="post-categories">';
																					foreach ( $listing_categories as $listing_category ) { 
																							$output .= '<li><a href="' . esc_url_raw( get_term_link( $listing_category ) ) . '" rel="category tag">' . esc_html( $listing_category->name ) . '</a></li>';                                                                                                                                                
																					}
																			$output .= '</ul>';	
																	$output .= '</div>';

															} 
													if ( function_exists( 'get_user_favorites' ) ) {
														$is_favourited = boldthemes_is_favourited( $listing->ID, get_current_blog_id() );
														$bt_bb_listing_favourite_class = $is_favourited ? 'bt_bb_listing_favourite_on' : 'bt_bb_listing_favourite';

														$output .= '<div class="bt_bb_listing_favourite">';
																$output .= '<span class="' . esc_attr( $bt_bb_listing_favourite_class ) . '"></span>';
														$output .= '</div>';
													}
													$output .= '</div>

													<div class="bt_bb_listing_photo">
														<div class="bt_bb_listing_photo_wrapper"><img class="bt_bb_grid_item_inner_image" src="' . esc_url_raw( $img_src ) . '" alt="' . esc_attr( $listing->post_title ) . '"></div>
														<div class="bt_bb_listing_photo_overlay"></div>
													</div>';
												$output .= '</div>';

											$output .= '<div class="bt_bb_listing_details">';
												$output .= '<div class="bt_bb_listing_title">';
													$output .= '<h3>' . esc_html( $listing->post_title ) . '</h3>';
                                                                                                        if ( ! empty( $working_times ) ) {                                                                                                            
                                                                                                               if ( $open_hours == 'closed' ) {
                                                                                                                         $output .= '<small class="bt_bb_listing_working_hours"></small>';	
                                                                                                               } else if ( $open_hours || empty($current_open_hours) ) {
															 $output .= '<small class="bt_bb_listing_working_hours bt_bb_listing_working_hours_open">' . esc_html__( "Now closed", "bello" ) . '</small>';
                                                                                                               } else { 
															$current_open_hours_text = '';							
															if ( !empty($current_open_hours) ) {                                                                                                                            
																$current_open_hours_text	= $current_open_hours[0];
																if ( $current_open_hours[1] != '' ) { $current_open_hours_text	.= ' - ' . $current_open_hours[1];}
																if ( $current_open_hours[2] != '' ) { $current_open_hours_text	.= '<br />' . $current_open_hours[2];}
																if ( $current_open_hours[3] != '' ) { $current_open_hours_text	.= ' - ' . $current_open_hours[3];}
															}															
															$output .= '<small class="bt_bb_listing_working_hours bt_bb_listing_working_hours_open">' . $current_open_hours_text . '</small>';							
														 }
													}												
												$output .= '</div>';


												$output .= '<div class="bt_bb_listing_information">';
                                                                                                             if ( $boldthemes_theme_listing_contact_phone ) {
                                                                                                                    $output .= '<span class="bt_bb_listing_phone">';
                                                                                                                            $output .= '<a href="tel:' . $boldthemes_theme_listing_contact_phone_link . '">';
                                                                                                                                    $output .= $boldthemes_theme_listing_contact_phone;
                                                                                                                            $output .= '</a>';
                                                                                                                    $output .= '</span>';
                                                                                                             } 

                                                                                                             if ( $latitudeTo != ''  && $longitudeTo != '' ){	
                                                                                                                    $output .= '<span class="bt_bb_listing_distance" id="bt_bb_listing_distance_' . $listing->ID. '" ></span>';															
                                                                                                             }													
												 $output .= '</div>';

												 $output .= '<div class="bt_bb_listing_rating">';
													 $output .= boldthemes_rating_header_listing_single( $listing->ID );
												 $output .= '</div>';


												$output .= '<div class="bt_bb_listing_excerpt">';
													$output .= '<div class="bt_bb_listing_excerpt_description">';
														 $output .= $listing->post_excerpt;
													$output .= '</div>';
												$output .= '</div>';
												
												$output .= '<div class="bt_bb_listing_bottom_meta">';

														$no_of_comments =  count(boldthemes_comments_listing( $listing->ID ));						
														if ( $no_of_comments > 0  ) {
															$output .= '<span class="bt_bb_listing_comments">' . esc_html( $no_of_comments ) . '</span>';
														}

														$rating = boldthemes_get_average_rating( $listing->ID );	
														if ( !empty($rating) ) {
															$average	= $rating["rating"];
															$total		= $rating["total"];
															$no			= $rating["no"];
															if ( $average > 0  ) {
																$output .= '<span class="bt_bb_listing_ratings">' . esc_html( $average ) . '</span>';
															}
														}
														
														$output .= boldthemes_price_header_listing_single( $listing->ID );
													
												$output .= '</div>
											</div>
										</div>
									</div>';
								
								$output .= '</div>							
							</div>';
			$output .= '</div>';
			$n++;
		}
			return $output;

	}
        
       

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(	
             'show_featured'   => '',
			'number'          => '',
			'columns'         => '',
             'type'            => 'bt_bb_featured_listing_grid',  
			'format'	  => '',
			'gap'             => '',
			'category'        => '',
			'region'          => '',
			'listing'         => '',
			'sorting'         => ''
		) ), $atts, $this->shortcode ) );
                
		if ( !function_exists( 'boldthemes_get_query_listings' ) ){
			return '';
		}

		$show_featured  = sanitize_text_field( $show_featured );
		$number         = sanitize_text_field( $number );
		$columns        = sanitize_text_field( $columns );
		$type           = sanitize_text_field( $type );
		$format         = sanitize_text_field( $format );
		$gap            = sanitize_text_field( $gap );
		$category       = sanitize_text_field( $category );
		$region         = sanitize_text_field( $region );
		$listing        = sanitize_text_field( $listing );
		$sorting        = sanitize_text_field( $sorting );

		$el_style       = sanitize_text_field( $el_style );
		$el_class       = sanitize_text_field( $el_class );

		if ( $number > 1000 || $number == '' ) {
				$number = 1000;
		} else if ( $number < 1 ) {
				$number = 1;
		} 
                
        $listing_search_distance_lat	= boldthemes_get_option( 'listing_search_distance_lat' )  != '' ? boldthemes_get_option( 'listing_search_distance_lat' ) : '0';
		$listing_search_distance_lng	= boldthemes_get_option( 'listing_search_distance_lng' )  != '' ? boldthemes_get_option( 'listing_search_distance_lng' ) : '0';
		$listing_search_distance_unit	= boldthemes_get_option( 'listing_search_distance_unit' ) != '' ? boldthemes_get_option( 'listing_search_distance_unit' ) : 'mi';
		$listing_search_distance_radius	= boldthemes_get_option( 'listing_search_distance_radius' ) != '' ? boldthemes_get_option( 'listing_search_distance_radius' ) : '100';

		if ( $type == 'bt_bb_featured_listing_grid' ){ 
			$class = array( $this->shortcode, 'bt_bb_grid_container', $type );
		}else{
			$class = array( $this->shortcode, $type );
		}

		if ( $el_class != '' ) {
			$class[] = $el_class;
		}
                
        if ( $columns != '' ) {
			$class[] = $this->prefix . 'columns' . '_' . $columns;
		}
		
		if ( $gap != '' ) {
			$class[] = $this->prefix . 'gap' . '_' . $gap;
		}

		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . esc_attr( $el_id ) . '"';
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
		}
                
		if ( $number > 1000 || $number == '' ) {
			$number = 1000;
		} else if ( $number < 1 ) {
			$number = 1;
		}

		$featured = $show_featured ? true : false;
               
		$show = array( 'excerpt' => false, 'title' => false, 'featured' => $featured, 'sorting' => $sorting );                
                
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );

		if ( $type != 'bt_bb_featured_listing_grid' ) { 
				ob_start();                        
				require "templates/bt_bb_featured_listings_nogrid_template.php";
				return ob_get_clean();                         
				die;
		}

		wp_enqueue_script( 'jquery-masonry' );
		wp_register_script( 
			'bt_bb_featured_listings_js', 
			get_template_directory_uri() . '/bold-page-builder/content_elements/bt_bb_featured_listings/bt_bb_featured_listings.js',
			array( 'jquery' )
		);
		wp_localize_script( 'bt_bb_featured_listings_js', 'ajax_object', array( 
			'ajax_url'	=> admin_url( 'admin-ajax.php' ),
			'ajax_lat'	=> $listing_search_distance_lat, 
			'ajax_lng'	=> $listing_search_distance_lng,
			'ajax_unit'	=> $listing_search_distance_unit,
			'ajax_radius'	=> $listing_search_distance_radius,
             'listing_slugs'	=> $listing,
			'ajax_label_m'         => esc_html__( 'm', 'bello' ),
			'ajax_label_km'         => esc_html__( 'km', 'bello' ),
			'ajax_label_mi'         => esc_html__( 'mi', 'bello' ),
			) 
		);
		wp_enqueue_script( 'bt_bb_featured_listings_js' );

		wp_enqueue_style( 
			'bt_bb_featured_listings', 
			get_template_directory_uri() . '/bold-page-builder/content_elements_misc/css/bt_bb_featured_listings.css', 
			array(), 
			false, 
			'screen' 
		);
                

		$output = '';
		
		$output .= '<div class="bt_bb_post_grid_loader"></div>';

		$output .= '<div class="bt_bb_masonry_post_grid_content bt_bb_grid_hide" data-bt-nonce="' . esc_attr( wp_create_nonce( 'bt-bb-featured-listings-nonce' ) ) . '" data-sorting-listing="' . esc_attr( $sorting ) . '" data-number-listing="' . esc_attr( $number ) . '" data-format-listing="' . esc_attr( $format ) . '" data-category-listing="' . esc_attr( $category ) . '"  data-post-type="listing" data-show-listing="' . esc_attr( urlencode( serialize( $show ) ) ) . '" data-show-type="' . esc_attr( $type ) . '" data-region-listing="' . esc_attr( $region ) . '"><div class="bt_bb_grid_sizer"></div></div>';

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . ' data-columns="' . esc_attr( $columns ) . '">' . $output . '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;
	}
        


	function map_shortcode() {
               
                $regionArray = array();
                $regionArray[ esc_html__( 'All regions' , 'bello' )  ] = '' ;
                
                $taxonomy_listing_region_exist = taxonomy_exists('listing-region');                
                if ( $taxonomy_listing_region_exist ){
                    $listing_regions = get_terms( array(
                        'taxonomy' => 'listing-region',
                        'hide_empty' => true,
                    ) );                     
                    foreach ( $listing_regions as $listing_region ) {
                        if ( isset( $listing_region ) && isset($listing_region-> name) && isset($listing_region-> slug) ){
                            $regionArray[ $listing_region-> name ] = $listing_region-> slug;
                        }
                    }
                }
               
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Featured Listings', 'bello' ), 'description' => esc_html__( 'Featured Listings', 'bello' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
                array( 'param_name' => 'show_featured',  'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'bello' ) => 'show_featured' ), 'heading' => esc_html__( 'Show only featured', 'bello' ), 'preview' => true
				),
                array( 'param_name' => 'type', 'type' => 'dropdown', 'heading' => esc_html__( 'Type', 'bello' ), 'preview' => true,
					'value' => array(
						esc_html__( 'Grid', 'bello' )           => 'bt_bb_featured_listing_grid',
						esc_html__( 'List', 'bello' )           => 'bt_bb_featured_listing_list',
						esc_html__( 'Image+content', 'bello' )  => 'bt_bb_featured_listing_image_content'
					)
				),
				array( 'param_name' => 'category', 'type' => 'textfield', 'heading' => esc_html__( 'Category', 'bello' ), 'description' => esc_html__( 'Enter category slug or leave empty to show all', 'bello' ), 'preview' => true ),
                 array( 'param_name' => 'region', 'type' => 'dropdown', 'heading' => esc_html__( 'Region', 'bello' ), 'description' => esc_html__( 'Leave category slug empty to filter by region', 'bello' ),'preview' => true,
					'value' => $regionArray
				),
				array( 'param_name' => 'listing', 'type' => 'textfield', 'heading' => esc_html__( 'Listing', 'bello' ), 'description' => esc_html__( 'Enter listing slugs separated by ;. If listing slugs are entered, categories and regions could be empty.', 'bello' ) ),
				array( 'param_name' => 'sorting', 'type' => 'dropdown', 'heading' => esc_html__( 'Sort by', 'bello' ),'preview' => true,
						'value' => array(
								esc_html__( 'Sort by date ascending', 'bello' )       => '0',
								esc_html__( 'Sort by date descending', 'bello' )      => '1',
								esc_html__( 'Sort by name - A to Z', 'bello' )        => '2',
								esc_html__( 'Sort by name - Z to A', 'bello' )        => '3',
								esc_html__( 'Sort by entered Listing slugs order ( if Listing is not empty  )', 'bello' )        => '10'
						)
				),  
				array( 'param_name' => 'number', 'type' => 'textfield', 'heading' => esc_html__( 'Number of items', 'bello' ), 'description' => esc_html__( 'Enter number of items or leave empty to show all (up to 1000)', 'bello' ), 'preview' => true ),
				array( 'param_name' => 'columns', 'type' => 'dropdown', 'heading' => esc_html__( 'Columns', 'bello' ), 'preview' => true,
					'value' => array(
						esc_html__( '1', 'bello' ) => '1',
						esc_html__( '2', 'bello' ) => '2',
						esc_html__( '3', 'bello' ) => '3',
						esc_html__( '4', 'bello' ) => '4'
					)
				),
				array( 'param_name' => 'gap', 'type' => 'dropdown', 'heading' => esc_html__( 'Gap', 'bello' ),
					'value' => array(
						esc_html__( 'No gap', 'bello' ) => 'no_gap',
						esc_html__( 'Small', 'bello' ) => 'small',
						esc_html__( 'Normal', 'bello' ) => 'normal',
						esc_html__( 'Large', 'bello' ) => 'large'
					)
				),				
				array( 'param_name' => 'format', 'type' => 'textfield', 'preview' => true, 'heading' => esc_html__( 'Tiles format', 'bello' ), 'description' => esc_html__( 'e.g. 11, 21, 12, 22', 'bello' ), 'preview' => true
				),
                                                              	
			)
		) );
	}
}

