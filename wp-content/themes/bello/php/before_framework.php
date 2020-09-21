<?php
/**
 * Default headline size
 */
if ( ! function_exists( 'boldthemes_header_headline_size' ) ) {
	function boldthemes_header_headline_size( $size ) {
		return 'medium';
	}
}
add_filter( 'boldthemes_header_headline_size', 'boldthemes_header_headline_size' );

/**
 * Listing image sizes
 */
if ( ! function_exists( 'boldthemes_listing_image_sizes' ) ) {
	function boldthemes_listing_image_sizes() {
		add_image_size( 'boldthemes_listing_image', 640, 427, true );
                add_image_size( 'boldthemes_medium_vertical_rectangle', 480, 640, true );
                add_image_size( 'boldthemes_listing_image_medium_rectangle', 640, 320, true );
                add_image_size( 'boldthemes_listing_image_medium_vertical_rectangle', 320, 640, true );
	}
}
add_action( 'after_setup_theme', 'boldthemes_listing_image_sizes' );

/**
 * Header headline output
 */
if ( ! function_exists( 'boldthemes_header_headline' ) ) {
	function boldthemes_header_headline( $arg = array() ) {
		
		$extra_class = '';
		
		$dash  = '';
		$use_dash = boldthemes_get_option( 'sidebar_use_dash' );
		if ( is_singular() ) {
			$use_dash = boldthemes_get_option( 'blog_use_dash' );
		} else if ( is_singular( 'product' ) ) {
			$use_dash = boldthemes_get_option( 'shop_use_dash' );
		}  else if ( is_singular( 'portfolio' ) ) {
			$use_dash = boldthemes_get_option( 'pf_use_dash' );
		} 
		if ( $use_dash ) $dash  = apply_filters( 'boldthemes_header_headline_dash', 'top' );
		
		if ( is_front_page() ) {
			$title = get_bloginfo( 'description' );
		} else if ( is_singular() ) {
			$title = get_the_title();
		} else {
			$title = wp_title( '', false );
		}
                
		if ( BoldThemesFramework::$page_for_header_id != '' ) {
			$feat_image = wp_get_attachment_url( get_post_thumbnail_id( BoldThemesFramework::$page_for_header_id ) );
			
			$excerpt = boldthemes_get_the_excerpt( BoldThemesFramework::$page_for_header_id );
			if ( ! $feat_image ) {
				if ( is_singular() &&  !is_singular( "product" ) ) {
					$feat_image = wp_get_attachment_url( get_post_thumbnail_id() );
				} else {
					$feat_image = false;
				}
			}
		} else {
			if ( is_singular() ) {
				$feat_image = wp_get_attachment_url( get_post_thumbnail_id() );
			} else {
				$feat_image = false;
			}
			$excerpt = boldthemes_get_the_excerpt( get_the_ID() );
		}
		
		$parallax = isset( $arg['parallax'] ) ? $arg['parallax'] : '0.8';
		$parallax_class = 'bt_bb_parallax';
		if ( wp_is_mobile() ) {
			$parallax = 0;
			$parallax_class = '';
		}
		
		$supertitle = '';
		$subtitle = $excerpt;
		
		$breadcrumbs = isset( $arg['breadcrumbs'] ) ? $arg['breadcrumbs'] : true;
		
		if ( $breadcrumbs ) {
			$heading_args = boldthemes_breadcrumbs( false, $title, $subtitle );
                        
			$supertitle = $heading_args['supertitle'];
			$title = $heading_args['title'];
			$subtitle = $heading_args['subtitle'];
		}
                
		if ( is_singular( "listing" ) ){
			$listing_single_list_header_view = boldthemes_get_option( 'listing_single_list_header_view' );
			if ( $listing_single_list_header_view != '' ) {

				$excerpt		= boldthemes_get_the_excerpt( get_the_ID() );
				$listing_price	= intval( boldthemes_rwmb_meta( 'boldthemes_theme_listing_price' )); 

				BoldThemesFrameworkTemplate::$title				= $title;
				BoldThemesFrameworkTemplate::$supertitle		= $supertitle;
				BoldThemesFrameworkTemplate::$subtitle			= $subtitle;
				BoldThemesFrameworkTemplate::$feat_image		= $feat_image;
				BoldThemesFrameworkTemplate::$extra_class		= $extra_class;
				BoldThemesFrameworkTemplate::$parallax			= $parallax;
				BoldThemesFrameworkTemplate::$dash				= $dash;
				BoldThemesFrameworkTemplate::$parallax_class	= $parallax_class;
				BoldThemesFrameworkTemplate::$excerpt			= $excerpt;
				BoldThemesFrameworkTemplate::$listing_price		= $listing_price;

				get_template_part( 'views/listing/header/' . $listing_single_list_header_view );
			} 
		} else {
		
			if ( $title != '' || $supertitle != '' || $subtitle != '' ) {
				$extra_class .= $feat_image ? ' bt_bb_background_image ' . apply_filters( 'boldthemes_header_headline_gradient', '' ) . $parallax_class  : ' ';
				
				echo '<section class="bt_bb_section gutter bt_bb_vertical_align_top btPageHeadline">';
					echo '<div class="bt_bb_grayscale_image ' . esc_attr( $extra_class ) . '" style="background-image:url(' . esc_url_raw( $feat_image ) . ')" data-parallax="' . esc_attr( $parallax ) . '" data-parallax-offset="0"></div>';
					echo '<div class="bt_bb_port port">';
						echo '<div class="bt_bb_cell">';
							echo '<div class="bt_bb_cell_inner">';
								echo '<div class = "bt_bb_row">';
									echo '<div class="bt_bb_column">';
										echo '<div class="bt_bb_column_content">';
											echo boldthemes_get_heading_html( 
												array(
													'superheadline' => $supertitle,
													'headline' => $title,
													'subheadline' => $subtitle,
													'html_tag' => "h1",
													'size' => apply_filters( 'boldthemes_header_headline_size', 'medium' ),
													'dash' => $dash,
													'el_style' => '',
													'el_class' => ''
												)
											);
											echo '</div><!-- /rowItemContent -->' ;
										echo '</div><!-- /rowItem -->';
								echo '</div><!-- /boldRow -->';
							echo '</div><!-- boldCellInner -->';	
						echo '</div><!-- boldCell -->';			
					echo '</div><!-- port -->';
				echo '</section>';
			}
		}
		
	}
}

if ( ! function_exists( 'boldthemes_customize_register' ) ) {
	function boldthemes_customize_register( $wp_customize ) {
		
		global $wpdb;
		
		if ( isset( $_GET['boldthemes_reset'] ) && $_GET['boldthemes_reset'] == 'reset' ) {
			$wpdb->query( 'delete from ' . $wpdb->options . ' where option_name = "' . BoldThemesFramework::$pfx . '_theme_options"' );
			header( 'Location: ' . wp_customize_url());
		}

		$wp_customize->remove_section( 'colors' );
		
		$wp_customize->add_section( BoldThemesFramework::$pfx . '_general_section' , array(
			'title'      => esc_html__( 'General Settings', 'bello' ),
			'priority'   => 10,
		));
		$wp_customize->add_section( BoldThemesFramework::$pfx . '_header_footer_section' , array(
			'title'      => esc_html__( 'Header and Footer', 'bello' ),
			'priority'   => 20,
		));
		$wp_customize->add_section( BoldThemesFramework::$pfx . '_typo_section' , array(
			'title'      => esc_html__( 'Typography', 'bello' ),
			'priority'   => 30,
		));
		$wp_customize->add_section( BoldThemesFramework::$pfx . '_blog_section' , array(
			'title'      => esc_html__( 'Blog', 'bello' ),
			'priority'   => 40,
		));
		$wp_customize->add_section( BoldThemesFramework::$pfx . '_listing_general_section' , array(
			'title'      => esc_html__( 'Bello General Options', 'bello' ),
			'priority'   => 50,
		));
		$wp_customize->add_section( BoldThemesFramework::$pfx . '_listing_section' , array(
			'title'      => esc_html__( 'Single Listing', 'bello' ),
			'priority'   => 51,
		));
		$wp_customize->add_section( BoldThemesFramework::$pfx . '_listing_search_section' , array(
			'title'      => esc_html__( 'Search Listings', 'bello' ),
			'priority'   => 52,
		));
		$wp_customize->add_section( BoldThemesFramework::$pfx . '_listing_map_section' , array(
			'title'      => esc_html__( 'Listing Map', 'bello' ),
			'priority'   => 53,
		));
		$wp_customize->add_section( BoldThemesFramework::$pfx . '_shop_section' , array(
			'title'      => esc_html__( 'Shop', 'bello' ),
			'priority'   => 60,
		));

		require_once( get_template_directory() . '/framework/web_fonts.php' );
	}
}

if ( ! class_exists( 'BoldThemesFrameworkTemplate' ) ) {
	// Override BoldThemesFrameworkTemplate class
	class BoldThemesFrameworkTemplate {
		public static $blog_author;
		public static $blog_date;
		public static $author_url;
		public static $show_comments_number;
		public static $blog_use_dash;
		public static $class_array;
		public static $blog_side_info;
		public static $media_html;
		public static $categories_html;
		public static $tags_html;
		public static $content_final_html;
		public static $post_format;
		public static $content_html;
		public static $meta_html;
		public static $dash;
		public static $cf;
		public static $listing_use_dash;

		public static $media_image_html;
		public static $media_video_html;
		public static $media_audio_html;

		public static $title;
		public static $supertitle;
		public static $subtitle;
		public static $feat_image;
		public static $extra_class;
		public static $parallax;
		public static $parallax_class;
		public static $excerpt;
		public static $listing_price;
		public static $listing_faq;

		public static $listing_category;
		public static $listing_tag;
                public static $listing_region;
		public static $listings;
		public static $found;
		public static $posts_per_page;
		public static $max_page;
		public static $limit;
		public static $custom_map_style;
		public static $order_by;

		public static $listing_list_view;
                public static $listing_list_grid_view;
                public static $listing_grid_columns;
		public static $listing_search_type;
		public static $paged;

		public static $keyword;
		public static $location;

		public static $listing_gets = array();
		public static $listing_grid_nearby_category;

		public static $listing_search_distance_unit;
                public static $listing_distance_max;
                
                public static $listing_search_autocomplete;
                public static $location_autocomplete_distance;

		public static $bt_bb_listing_field_my_lat;
		public static $bt_bb_listing_field_my_lng;
                public static $bt_bb_listing_field_my_lat_default;
		public static $bt_bb_listing_field_my_lng_default;
		public static $bt_bb_listing_field_distance;
                public static $bt_bb_listing_field_location_autocomplete;
                
                public static $listing_root_slug;                
                public static $currency_symbol;
                public static $listing_currency_after_price;
                public static $listing_currency_thousand_separator;
                public static $listing_currency_decimal_separator;
                
                public static $listing_distance_max_in_slider;
                public static $ajax_random_distance;
                
                public static $listing_search_sort;
	}
}


/**
 * Creates override of global options for individual posts
 */
if ( ! function_exists( 'boldthemes_set_override' ) ) {
	function boldthemes_set_override() {
		global $boldthemes_options;
		$boldthemes_options = get_option( BoldThemesFramework::$pfx . '_theme_options' );

		global $boldthemes_page_options;
		$boldthemes_page_options = array();
		
		if ( ! is_404() ) {
			$tmp_boldthemes_page_options = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_override' );
			if ( ! is_array( $tmp_boldthemes_page_options ) ) $tmp_boldthemes_page_options = array();
			$tmp_boldthemes_page_options = boldthemes_transform_override( $tmp_boldthemes_page_options );
			$tmp_boldthemes_page_options1 = '';
			
			if ( ( is_search() || is_archive() || is_home() ) && get_option( 'page_for_posts' ) != 0 ) {
				$tmp_boldthemes_page_options1 = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_override', array(), get_option( 'page_for_posts' ) );
			} 

			if ( is_singular( 'post' ) && isset( $tmp_boldthemes_page_options[ BoldThemesFramework::$pfx . '_blog_settings_page_slug'] ) && $tmp_boldthemes_page_options[ BoldThemesFramework::$pfx . '_blog_settings_page_slug'] != '' ) { 
				// override with override
				$tmp_boldthemes_page_options1 = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_override', array(), boldthemes_get_id_by_slug( $tmp_boldthemes_page_options[ BoldThemesFramework::$pfx . '_blog_settings_page_slug'] ) );
			} else if ( is_singular( 'post' ) && isset( $boldthemes_options['blog_settings_page_slug'] ) && $boldthemes_options['blog_settings_page_slug'] != '' ) {
				$tmp_boldthemes_page_options1 = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_override', array(), boldthemes_get_id_by_slug( $boldthemes_options['blog_settings_page_slug'] ) );
			} else if ( BoldThemes_Customize_Default::$data['blog_settings_page_slug'] != '' ) {
				$tmp_boldthemes_page_options1 = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_override', array(), boldthemes_get_id_by_slug( BoldThemes_Customize_Default::$data['blog_settings_page_slug'] ) );
			}

			if ( is_singular( 'listing' ) && isset( $tmp_boldthemes_page_options[ BoldThemesFramework::$pfx . '_listing_settings_page_slug'] ) && $tmp_boldthemes_page_options[ BoldThemesFramework::$pfx . '_listing_settings_page_slug'] != '' ) { 
				// override with override
				$tmp_boldthemes_page_options1 = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_override', array(), boldthemes_get_id_by_slug( $tmp_boldthemes_page_options[ BoldThemesFramework::$pfx . '_listing_settings_page_slug'] ) );
			} else if ( is_singular( 'listing' ) && isset( $boldthemes_options['listing_settings_page_slug'] ) && $boldthemes_options['listing_settings_page_slug'] != '' ) {
				$tmp_boldthemes_page_options1 = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_override', array(), boldthemes_get_id_by_slug( $boldthemes_options['listing_settings_page_slug'] ) );
			}
			
			if ( is_post_type_archive( 'listing' ) ) {
				if ( !is_null( boldthemes_get_id_by_slug('listing') ) && boldthemes_get_id_by_slug('listing') != '' ) {
					$tmp_boldthemes_page_options1 = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_override', array(), boldthemes_get_id_by_slug( 'listing' ) );
				}
			}
		
			if ( function_exists( 'bt_is_listing_category' ) && bt_is_listing_category() && boldthemes_get_id_by_slug('listing') != '' ) {                               
				$tmp_boldthemes_page_options1 = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_override', array(), boldthemes_get_id_by_slug( 'listing' ) );
			}
			
			if ( function_exists( 'bt_is_listing_tag' ) && bt_is_listing_tag() && boldthemes_get_id_by_slug('listing') != '' ) {
				$tmp_boldthemes_page_options1 = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_override', array(), boldthemes_get_id_by_slug( 'listing' ) );
			}
                        
                        if ( function_exists( 'bt_is_listing_region' ) && bt_is_listing_region() && boldthemes_get_id_by_slug('listing') != '' ) {
				$tmp_boldthemes_page_options1 = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_override', array(), boldthemes_get_id_by_slug( 'listing' ) );
			}
			
			if ( function_exists( 'is_shop' ) && is_shop() && get_option( 'woocommerce_shop_page_id' ) ) {
				$tmp_boldthemes_page_options1 = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_override', array(), get_option( 'woocommerce_shop_page_id' ) );
			}
			
			if ( function_exists( 'is_product_category' ) && is_product_category() && get_option( 'woocommerce_shop_page_id' ) ) {
				$tmp_boldthemes_page_options1 = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_override', array(), get_option( 'woocommerce_shop_page_id' ) );
			}
			
			if ( function_exists( 'is_product' ) && is_product() && isset( $boldthemes_options['shop_settings_page_slug'] ) && $boldthemes_options['shop_settings_page_slug'] != '' ) {
				$tmp_boldthemes_page_options1 = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_override', array(), boldthemes_get_id_by_slug( $boldthemes_options['shop_settings_page_slug'] ) );
			}
			
			$post_type = get_post_type();

			if ( ( $post_type == 'tribe_events' || $post_type == 'tribe_venue' || $post_type == 'tribe_organizer' ) && isset( $boldthemes_options['events_settings_page_slug'] ) && $boldthemes_options['events_settings_page_slug'] != '' ) {
				BoldThemesFramework::$page_for_header_id = boldthemes_get_id_by_slug( $boldthemes_options['events_settings_page_slug'] );
				$tmp_boldthemes_page_options1 = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_override', array(), boldthemes_get_id_by_slug( $boldthemes_options['events_settings_page_slug'] ) );
			} 

			if ( is_array( $tmp_boldthemes_page_options1 ) ) {
				if ( is_singular() ) {
					$tmp_boldthemes_page_options = array_merge( boldthemes_transform_override( $tmp_boldthemes_page_options1 ), $tmp_boldthemes_page_options );
				} else {
					$tmp_boldthemes_page_options = boldthemes_transform_override( $tmp_boldthemes_page_options1 );
				}
			}

			foreach ( $tmp_boldthemes_page_options as $key => $value ) {
				$boldthemes_page_options[ $key ] = $value;
			}
                        
		}
	}
}

/**
 * Returns custom header class
 *
 * @return string
 */
if ( ! function_exists( 'boldthemes_get_body_class' ) ) {
	function boldthemes_get_body_class( $extra_class ) {
		
		$extra_class[] = 'bodyPreloader'; 
		
		if ( boldthemes_get_option( 'alt_logo' ) ) {
			$extra_class[] = 'btHasAltLogo';
		}
		
		$menu_type = boldthemes_get_option( 'menu_type' );
		if ( $menu_type == 'horizontal-center' ) {
			$extra_class[] = 'btMenuCenterEnabled'; 
		} else if ( $menu_type == 'horizontal-left' ) {
			$extra_class[] = 'btMenuLeftEnabled';
		}  else if ( $menu_type == 'horizontal-right' ) {
			$extra_class[] = 'btMenuRightEnabled';
		} else if ( $menu_type == 'horizontal-below-left' ) {
			$extra_class[] = 'btMenuLeftEnabled';
			$extra_class[] = 'btMenuBelowLogo';
		} else if ( $menu_type == 'horizontal-below-center' ) {
			$extra_class[] = 'btMenuCenterBelowEnabled';
			$extra_class[] = 'btMenuBelowLogo';
		} else if ( $menu_type == 'horizontal-below-right' ) {
			$extra_class[] = 'btMenuRightEnabled';
			$extra_class[] = 'btMenuBelowLogo';
		} else if ( $menu_type == 'vertical-left' ) {
			$extra_class[] = 'btMenuVerticalLeftEnabled';
		} else if ( $menu_type == 'vertical-right' ) {
			$extra_class[] = 'btMenuVerticalRightEnabled';
		} else {
			$extra_class[] = 'btMenuRightEnabled';
		}

		if ( boldthemes_get_option( 'sticky_header' ) ) {
			$extra_class[] = 'btStickyEnabled';
		}

		if ( boldthemes_get_option( 'hide_menu' ) ) {
			$extra_class[] = 'btHideMenu';
		}

		if ( boldthemes_get_option( 'hide_headline' ) ) {
			$extra_class[] = 'btHideHeadline';
		}

		if ( boldthemes_get_option( 'template_skin' ) == 'dark' ) {
			$extra_class[] = 'btDarkSkin';
		} else {
			$extra_class[] = 'btLightSkin';
		}

		if ( boldthemes_get_option( 'below_menu' ) ) {
			$extra_class[] = 'btBelowMenu';
		}

		if ( ! boldthemes_get_option( 'sidebar_use_dash' ) ) {
			$extra_class[] = 'btNoDashInSidebar';
		}

		if ( boldthemes_get_option( 'disable_preloader' ) ) {
			$extra_class[] = 'btRemovePreloader';
		}
		
		$buttons_shape = boldthemes_get_option( 'buttons_shape' );
		if ( $buttons_shape != '' ) {
			$extra_class[] = 'bt' . boldthemes_convert_param_to_camel_case( $buttons_shape ) . 'Buttons';
		}
		
		$header_style = boldthemes_get_option( 'header_style' );
		if ( $header_style != '' ) {
			$extra_class[] =  'bt' . boldthemes_convert_param_to_camel_case( $header_style ) . 'Header';
		} else {
			$extra_class[] =  'btTransparentDarkHeader';
		}
		
		if ( boldthemes_get_option( 'page_width' ) == 'boxed' ) {
			$extra_class[] = 'btBoxedPage';
		}

		BoldThemesFramework::$sidebar = boldthemes_get_option( 'sidebar' );
		
		if ( ! ( ( BoldThemesFramework::$sidebar == 'left' || BoldThemesFramework::$sidebar == 'right' ) && ! is_404() ) ) {
			BoldThemesFramework::$has_sidebar = false;
			$extra_class[] = 'btNoSidebar';
		} else {
			BoldThemesFramework::$has_sidebar = true;
			if ( BoldThemesFramework::$sidebar == 'left' ) {
				$extra_class[] = 'btWithSidebar btSidebarLeft';
			} else {
				$extra_class[] = 'btWithSidebar btSidebarRight';
			}
		}
		
		$animations = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_animations' );
		if ( $animations == 'half_page' ) {
			$extra_class[] = 'btHalfPage';
		}

		if ( is_singular( "listing" ) ){
			if ( boldthemes_get_option( 'listing_single_list_header_view' ) ) {
				$extra_class[] = 'btSingleListHeaderStyle_' . boldthemes_get_option( 'listing_single_list_header_view' );
			}
		}
                
                if ( is_post_type_archive( 'listing' ) || is_tax( 'listing-category' ) || is_tax( 'listing-region' ) || is_tax( 'listing-tag' ) ) {
                    $listing_list_view = boldthemes_get_option( 'listing_list_view' ) ? boldthemes_get_option( 'listing_list_view' ) : '';
                    $listing_list_view = isset($_GET['listing_list_view']) && $_GET['listing_list_view'] != '' ? $_GET['listing_list_view'] : $listing_list_view;
                    if ( $listing_list_view == 'without_map' ) {
                        $extra_class[] = 'btListingViewWithoutMap';                    
                    }else{
                        $extra_class[] = 'btListingViewWithMap';
                    }
                }		
		$extra_class = apply_filters( 'boldthemes_extra_class', $extra_class );
		
		return $extra_class;
	}
}

if ( ! function_exists( 'boldthemes_show_footer' ) ) {
    function boldthemes_show_footer() {   
            $ret = 1;
            if ( is_post_type_archive( 'listing' ) || is_tax( 'listing-category' ) || is_tax( 'listing-region' ) || is_tax( 'listing-tag' ) ) {  
                    $listing_list_view = boldthemes_get_option( 'listing_list_view' ) ? boldthemes_get_option( 'listing_list_view' ) : '';
                    $listing_list_view = isset($_GET['listing_list_view']) && $_GET['listing_list_view'] != '' ? $_GET['listing_list_view'] : $listing_list_view;
                    if ( is_post_type_archive( 'listing' ) ) { 
                        if ( $listing_list_view == 'without_map' ) {
                            $ret = 1;
                        }else{
                            add_action('wp_footer', 'boldthemes_script_set_body_with_map_class');
                            $ret = 0;
                        }
                    }
            } 
            return $ret;
    }
}


/**
 * Breadcrumbs
 */
if ( ! function_exists( 'boldthemes_breadcrumbs' ) ) {
	function boldthemes_breadcrumbs( $simple = false, $title, $subtitle ) {
		$home_link = home_url( '/' );
		$output  = '';
		$item_prefix = '<span>';
		$item_suffix = '</span>';
		if ( $simple ) {
			$item_prefix = '';
			$item_suffix = ' / ';
		}
                 
		if ( ! is_404() && ! is_front_page() ) {
		
			if ( ! $simple ) {
				$output .= '<span class="btBreadCrumbs">';
				if ( ! is_singular() || is_page() ) {
					$output .= '<span><a href="' . esc_url_raw( $home_link ) . '">' . esc_html__( 'Home', 'bello' ) . '</a></span>';
				}
			} else {
				if ( ! is_singular() || is_page() ) {
					$output .= '<a href="' . esc_url_raw( $home_link ) . '">' . esc_html__( 'Home', 'bello' ) . '</a>';
				}
			}
			
			if ( is_home() ) {
				
				$subtitle = '';
				
				$page_for_posts = get_option( 'page_for_posts' );
				if ( $page_for_posts ) {
					$page = get_post( $page_for_posts );
					$subtitle = $page->post_excerpt;
				}
			
			} else if ( is_page() ) {

				$ancestors = get_ancestors( get_the_ID(), 'page' );
				$ancestors = array_reverse( $ancestors );
			
				foreach( $ancestors as $ancestor ) {
					$output .= wp_kses_post( $item_prefix ) . '<a href="' . esc_url_raw( get_permalink( $ancestor ) ) . '">' . wp_kses_post( get_the_title( $ancestor ) ) . '</a>' . wp_kses_post( $item_suffix );
				}
				
				$page = get_post( get_the_ID() );
				$subtitle = $page->post_excerpt;
		  
			} else if ( is_singular( 'post' ) ) {
				
				$output .= boldthemes_get_post_categories();
				
				$subtitle = boldthemes_get_post_meta();
				
			} else if ( is_singular( 'portfolio' ) ) {
				
				$categories = wp_get_post_terms( get_the_ID(), 'portfolio_category' );
				$output .= boldthemes_get_post_categories( array( 'categories' => $categories ) );
				
				$subtitle = boldthemes_get_the_excerpt( get_the_ID() );
				
			} else if ( is_singular( 'product' ) ) {
				
				$id = get_queried_object_id();
				$categories = wp_get_post_terms( $id, 'product_cat' );
				$output .= boldthemes_get_post_categories( array( 'categories' => $categories ) );
				
				$pf = new WC_Product_Factory();
				$product = $pf->get_product( $id );
				$rating_count = $product->get_rating_count();
				if ( $rating_count > 0 ) {
					$subtitle = wc_get_rating_html( $product->get_average_rating() );
				}			
				
			} else if ( is_post_type_archive( 'portfolio' ) ) {
				
				$output .= $item_prefix . esc_html__( 'Portfolio', 'bello' ) . $item_suffix;
				
			} else if ( is_attachment() ) {
			
				$output .= $item_prefix . get_the_title() . $item_suffix;
				
			} else if ( is_category() ) {

				$output .= $item_prefix . esc_html__( 'Category', 'bello' ) . $item_suffix;

				$subtitle = '';
				
			} else if ( is_tax() ) {
				
				$output .= $item_prefix . esc_html__( 'Category', 'bello' ) . $item_suffix;
				
				$title = single_term_title( '', false );
				$subtitle = '';				
		  
			} else if ( is_tag() ) {
			
				$output .= $item_prefix . esc_html__( 'Tag', 'bello' ) . $item_suffix;
				
				$subtitle = '';
		  
			} else if ( is_author() ) {
			
				$output .= $item_prefix . esc_html__( 'Author', 'bello' ) . $item_suffix;
				
				$subtitle = '';
				
			} else if ( is_day() ) {

				$output .= $item_prefix . get_the_time( 'Y / m / d' ) . $item_suffix;
		  
			} else if ( is_month() ) {
			
				$output .= $item_prefix . get_the_time( 'Y / m' ) . $item_suffix;
		  
			} else if ( is_year() ) {
			
				$output .= $item_prefix . get_the_time( 'Y' ) . $item_suffix;			
				
			} else if ( is_search() ) {
				
				$output .= $item_prefix . esc_html__( 'Search', 'bello' ) . $item_suffix;

				$title = get_query_var( 's' );
				$subtitle = '';
				
			} else if ( is_singular( 'listing' ) ) {
				
				//$categories = boldthemes_hierarchy_get_taxonomu_terms('listing-category');
                                $categories = wp_get_post_terms( get_the_ID(), 'listing-category' );   
				$output .= boldthemes_get_post_categories( array( 'categories' => $categories ) );
				
				$subtitle = boldthemes_get_the_excerpt( get_the_ID() );
			}
			
			if ( ! $simple ) {
				$output .= '</span>';
			}
			
		}
		
		return array( 'supertitle' => $output, 'title' => $title, 'subtitle' => $subtitle );
	
	}
}

/*
 * get taxonomies by hierarchy
 */
if ( ! function_exists( 'boldthemes_hierarchy_get_taxonomu_terms' ) ) {
    function boldthemes_hierarchy_get_taxonomu_terms($taxonomy) {
            $return_taxonomy = array();
            $terms = wp_get_post_terms(  get_the_ID(), $taxonomy );        
            if ( isset($terms) && !empty($terms) )  {
                $i = 0;
                foreach ( $terms as $term )
                {
                    if ($term->parent == 0) {
                        $return_taxonomy [$i] = $term;
                        foreach ( $terms as $term2 ){
                            if ( $term2->parent == $term->term_id ){
                                $return_taxonomy [$i] = $term2;
                                foreach ( $terms as $term3 ){
                                    if ( $term3->parent == $term2->term_id ){
                                        $return_taxonomy [$i] = $term3;
                                        foreach ( $terms as $term4 ){
                                            if ( $term4->parent == $term3->term_id ){
                                                 $return_taxonomy [$i] = $term4;
                                                 foreach ( $terms as $term5 ){
                                                     if ( $term5->parent == $term4->term_id ){
                                                         $return_taxonomy [$i] = $term5;
                                                     }
                                                 }
                                                 $i++;
                                            }
                                            $i++;
                                        }
                                    }
                                    $i++;
                                }                                
                            }
                            $i++;
                            
                        }
                    }
                }
                $return_taxonomy = array_reverse(array_values($return_taxonomy));
            }        
            return $return_taxonomy;
    }
}

if ( ! function_exists( 'boldthemes_script_set_body_with_map_class' ) ) {
    function boldthemes_script_set_body_with_map_class(){
            wp_register_script( 'boldthemes-script-set-body-with-map-class', '' );
            wp_enqueue_script( 'boldthemes-script-set-body-with-map-class' );
            wp_add_inline_script( 'boldthemes-script-set-body-with-map-class', 'var body = document.body;body.classList.add("btMapSearch");' );
    }
}

add_filter( 'boldthemes_shop_share_settings', 'boldthemes_shop_share_settings_function' );
if ( ! function_exists( 'boldthemes_shop_share_settings_function' ) ) {
	function boldthemes_shop_share_settings_function( $extra_class ) {		
		return array( 'xsmall', 'filled', 'circle' );
	}
}

/**
 * WooCommerce related products
 */

if ( ! function_exists( 'boldthemes_related_products_args' ) ) {
	function boldthemes_related_products_args( $args ) {
		$args['posts_per_page'] = 3; // n related products
		$args['columns'] = 3; // arranged in n columns
		return $args;
	}
}

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_upsells', 15 );

if ( ! function_exists( 'woocommerce_output_upsells' ) ) {
	function woocommerce_output_upsells() {
	    woocommerce_upsell_display( 3,3 ); // Display 3 products in rows of 3
	}
}


// Execute the action only if woocommerce installed
if ( class_exists( 'WooCommerce' ) ) {
    if ( function_exists( 'bello_ajax_login_init' ) ) {
	add_action('init', 'bello_ajax_login_init');
    }
}

if ( ! function_exists( 'boldthemes_get_my_account_form' ) ) {
    function boldthemes_get_my_account_form() {
        if ( function_exists( 'bt_get_my_account_form' ) ) {
            bt_get_my_account_form();
        }
    }
}

if ( ! function_exists( 'boldthemes_custom_query_vars' ) ) {
    function boldthemes_custom_query_vars( $vars ) {
            $vars[] = 'listing_list_view';
            return $vars;
    }
}
add_filter( 'query_vars', 'boldthemes_custom_query_vars', 0 );

if ( ! function_exists( 'boldthemes_custom_controls' ) ) {
	function boldthemes_custom_controls() {
		class BoldThemes_Customize_Textarea_Control extends WP_Customize_Control {
			public $tooltip;
			public $example;                       
			public function render_content() {
				wp_register_style( 'boldthemes-custom-controls-style', false );
				wp_enqueue_style( 'boldthemes-custom-controls-style' );
				wp_add_inline_style( 'boldthemes-custom-controls-style', '.customize-control-example { display: none; padding: 5px; background-color: #008ec2; color: #fff; margin-bottom: 10px; width: 98%; font-size: .9em; box-sizing: border-box; word-wrap: break-word; }' );
				?>
				<label>
					<span class="customize-control-title" data-id="<?php echo esc_attr($this->id);?>"><?php echo esc_html( $this->label ); ?></span>
                    <span id="_customize-description-listing_search_distance_lng" class="description customize-control-description"><?php echo wp_kses_post($this->description); ?></span>
					<?php if ( $this->example ) { ?>
						<span id="<?php echo esc_attr($this->id);?>" class="tooltip customize-control-example"><?php echo esc_html($this->example); ?></span>
                    <?php } ?>
                    <textarea rows="5" style="width:98%;" <?php esc_attr($this->link()); ?>><?php echo esc_textarea( $this->value()); ?></textarea>
					<?php if ( $this->tooltip ) { ?>
						<span  id="<?php echo esc_attr($this->id);?>" class="tooltip customize-control-tooltip"><?php echo esc_html($this->tooltip); ?></span>
					<?php } ?>
                                        
				</label>
				<?php
			}
		}
		
		class BoldThemes_Reset_Control extends WP_Customize_Control {
			public function render_content() {
				?>
				<div style="margin: 5px 0px 10px 0px">
				<label><span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span></label>                                
					<input type="submit" onclick="var c = confirm('<?php echo esc_js( esc_html__( 'Reset theme settings to default values?', 'bello' ) ); ?>'); if (c != true) return false;var href=window.location.href;if (href.indexOf('?') > -1) {window.location.replace(href + '&boldthemes_reset=reset')} else {window.location.replace(href + '?boldthemes_reset=reset')};return false;" name="boldthemes_reset" id="boldthemes_reset" class="button" value="Reset">
				</div>
				<?php
			}
		}
	}
}
add_action( 'customize_register', 'boldthemes_custom_controls' );
add_action( 'boldthemes_customize_register', 'boldthemes_custom_controls' );

/**
 * Show the single product title 
 */
if (  ! function_exists( 'woocommerce_template_product_title' ) ) {
	function woocommerce_template_product_title( $supertitle, $title, $subtitle, $dash ) {
		if ( boldthemes_get_option( 'hide_headline' ) == '1' ) {
			echo boldthemes_get_heading_html( 
				array( 
					'superheadline' => $supertitle, 
					'headline' => $title, 
					'subheadline' => $subtitle, 
					'size' => apply_filters( 'boldthemes_product_headline_size', 'normal' ), 
					'dash' => $dash,
					'html_tag' => 'h1'
				)  
			);
		} else {
			global $post, $product;
			if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) {
				$sku = $product->get_sku() ? $product->get_sku() : esc_html__( 'N/A', 'bello' );
				if ( boldthemes_get_option( 'hide_headline' ) == '1' ) {
					echo '<span class = "btProductSKU"> ' . esc_html__( 'SKU:', 'bello' ) . ' ' . $sku . '</span>';	
				}
				echo '<div class="bt_bb_separator bt_bb_bottom_spacing_small bt_bb_border_style_none"></div>';
			}	
		}
	}
}

