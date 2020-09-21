<?php

class bt_bb_search extends BT_BB_Element {

	function __construct() {
		parent::__construct();
	}

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(			
			'show_keyword'				=> '',
			'show_location'				=> '',                        
			'show_category'				=> '',
			'show_text'                 => '',
			'show_advance_search_link'  => '',
			'advance_search_link_url'   => '',
			'advance_search_link_text'  => '',
			'advance_search_link_icon'  => '',
			'default_category_slug'		=> ''
		) ), $atts, $this->shortcode ) );
                
		if ( !post_type_exists( 'listing' ) ) {
			return '';
		}

		if ( $show_keyword == '' && $show_location == '' && $show_category == '' && $show_text == '' && $show_advance_search_link == ''){
			return '';
		}

		$form_code =  md5( $show_keyword . $show_location . $show_category . $show_text . mt_rand() );	

		$bt_bb_listing_field_my_lat     = boldthemes_get_option( 'listing_search_distance_lat' )    != '' ? boldthemes_get_option( 'listing_search_distance_lat' )      : '0';
		$bt_bb_listing_field_my_lng     = boldthemes_get_option( 'listing_search_distance_lng' )    != '' ? boldthemes_get_option( 'listing_search_distance_lng' )      : '0';
		$bt_bb_listing_distance_unit	= boldthemes_get_option( 'listing_search_distance_unit' )   != '' ? boldthemes_get_option( 'listing_search_distance_unit' )     : 'mi';
		$bt_bb_listing_distance_radius	= boldthemes_get_option( 'listing_search_distance_radius' ) != '' ? boldthemes_get_option( 'listing_search_distance_radius' )   : '100000';
		$listing_root_category_slug     = boldthemes_get_option( 'listing_search_root_category_slug' ) != '' ? boldthemes_get_option( 'listing_search_root_category_slug' ) : '_listing_root';
		$listing_list_view              = boldthemes_get_option( 'listing_list_view') != '' ? boldthemes_get_option('listing_list_view') : '';

		$bt_bb_listing_distance_max             = boldthemes_get_option( 'listing_distance_max' ) != '' ? boldthemes_get_option( 'listing_distance_max' ) : '100000'; 
		$bt_bb_listing_distance_max_in_slider	= boldthemes_get_option( 'listing_distance_max_in_slider' ) != '' ? boldthemes_get_option( 'listing_distance_max_in_slider' ) : false; 

		$bt_bb_listing_distance_max = $bt_bb_listing_distance_max_in_slider ? $bt_bb_listing_distance_max : 0;

		if ( $advance_search_link_url == ''){
			$advance_search_link_url  = get_post_type_archive_link( 'listing' ) ? get_post_type_archive_link( 'listing' ) : '#' ;
		}
                
		$show_location_autocomplete     = bt_is_autocomplete();
		if ( $show_location == 'show_location' && $show_location_autocomplete ){  
                    $listing_api_key	= boldthemes_get_option( 'listing_api_key' ) != '' ? boldthemes_get_option( 'listing_api_key' ) : '';
                    if ( $listing_api_key != '' ) {
                            if ( !wp_script_is( 'gmaps_api_autocomplete_search', 'enqueued' ) ) {
                                    wp_enqueue_script( 
                                            'gmaps_api_autocomplete_search',
                                            'https://maps.googleapis.com/maps/api/js?key=' . $listing_api_key . '&libraries=places'
                                    );
                            }
                    }  
		}
                
                wp_register_script( 'bt_bb_search_js', get_template_directory_uri() . '/bold-page-builder/content_elements/bt_bb_search/bt_bb_search.js' );
                wp_localize_script( 'bt_bb_search_js', 'ajax_sh_object', array(
                    'ajax_sh_lat'		=> $bt_bb_listing_field_my_lat, 
                    'ajax_sh_lng'		=> $bt_bb_listing_field_my_lng,
                    'ajax_sh_unit'		=> $bt_bb_listing_distance_unit,
                    'ajax_sh_radius'            => $bt_bb_listing_distance_radius
                    )
                );
                wp_enqueue_script( 'bt_bb_search_js' );
                
                $icon_set   = substr( $advance_search_link_icon, 0, -5 );
		$icon       = substr( $advance_search_link_icon, -4 );
                
		$class = array( $this->shortcode );

		if ( $el_class != '' ) {
			$class[] = $el_class;
		}	

		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . esc_attr( $el_id ) . '"';
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
		}

                $count_posts = wp_count_posts('listing');
                $count_listings = 0;
                if ( isset($count_posts) && !empty($count_posts)  ){
                    $count_listings = $count_posts->publish;
                }

		$cats = '';
		$listing_categories = get_terms(array( 'taxonomy' => 'listing-category', 'hide_empty' => false, 'parent' => 0 ));
		foreach ( $listing_categories as $listing_cat ){
            if ( $listing_cat->slug == $listing_root_category_slug ) continue;
                        
            $cats .= '<option value="' . esc_attr( $listing_cat->slug ) . '">' . $listing_cat->name . '</option>';
			foreach( get_terms( 'listing-category', array( 'hide_empty' => false, 'parent' => $listing_cat->term_id ) ) as $child_term ) {
				$selected_category = ( $child_term->slug == $default_category_slug) ? " selected='selected'" : '';
				$cats .=  '<option value="' . esc_attr( $child_term->slug ) . '"' .$selected_category . '>&nbsp;&nbsp;&nbsp;&nbsp;' . $child_term->name . '</option>';
			}
		}
               
		$locs = ''; 
                $listing_regions = get_terms( array( 'taxonomy' => 'listing-region','hide_empty' => false, 'parent' => 0) );
		foreach ( $listing_regions as $listing_region ){
			$locs .= '<option value="' . esc_attr( $listing_region->slug ) . '">' . $listing_region->name . '</option>';
                        foreach( get_terms( 'listing-region', array( 'hide_empty' => false, 'parent' => $listing_region->term_id ) ) as $child_term ) {
				$locs .=  '<option value="' . esc_attr( $child_term->slug ) . '">&nbsp;&nbsp;&nbsp;&nbsp;' . $child_term->name . '</option>';                                
                                foreach( get_terms( 'listing-region', array( 'hide_empty' => false, 'parent' => $child_term->term_id ) ) as $child_term2 ) {
                                    $locs .=  '<option value="' . esc_attr( $child_term2->slug ) . '">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $child_term2->name . '</option>';                                                                                                                           
                                
                                    foreach( get_terms( 'listing-region', array( 'hide_empty' => false, 'parent' => $child_term2->term_id ) ) as $child_term3 ) {
                                        $locs .=  '<option value="' . esc_attr( $child_term3->slug ) . '">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $child_term3->name . '</option>';                                                                                                                           
                                    }
                                }
			}
		}
                
                $listing_form_action_page  = get_post_type_archive_link( 'listing' ) ? get_post_type_archive_link( 'listing' ) : '' ;
                $listing_form_action_page  .= ($listing_list_view != 'standard') ? '?listing_list_view=' . $listing_list_view : '';
                
		$output = '<div' . $id_attr . ' class="' . esc_attr( implode( ' ', $class ) ) . '"' . $style_attr . '>';
		$output .= '
		<div class="bt_bb_listing_search_form">
			<form id="simple-search-form-' . esc_attr( $form_code ) . '" autocomplete="off" action="' . esc_attr( $listing_form_action_page ) . '" method="get" accept-charset="UTF-8">';
                                if ( $show_location && $show_location_autocomplete ){
                                        $output .= '<input type="hidden" name="bt_bb_listing_field_my_lat" id="bt_bb_listing_field_my_lat-' . esc_attr( $form_code ) . '" value="' . esc_attr( $bt_bb_listing_field_my_lat ) . '" />
                                        <input type="hidden" name="bt_bb_listing_field_my_lng" id="bt_bb_listing_field_my_lng-' . esc_attr( $form_code ) . '" value="' . esc_attr( $bt_bb_listing_field_my_lng ) . '" />
                                        <input type="hidden" name="bt_bb_listing_field_distance_value" id="bt_bb_listing_field_distance_value-' . esc_attr( $form_code ) . '" value="' . esc_attr( $bt_bb_listing_distance_max ) . '" />
                                        <input type="hidden" name="bt_bb_listing_field_my_lat_default" id="bt_bb_listing_field_my_lat_default-' . esc_attr( $form_code ) . '" value="' . esc_attr( $bt_bb_listing_field_my_lat ) . '" />
                                        <input type="hidden" name="bt_bb_listing_field_my_lng_default" id="bt_bb_listing_field_my_lng_default-' . esc_attr( $form_code ) . '" value="' . esc_attr( $bt_bb_listing_field_my_lng ) . '" />';
                                 }
				$output .= '<div class="bt_bb_row bt_bb_column_gap_10">';

                                if ( $show_keyword ){
                                   $output .= '<div class="bt_bb_column col-lg-3 col-md-2 col-sm-12 bt_bb_vertical_align_top bt_bb_padding_normal bt_bb_spaced bt_bb_listing_search_col" data-width="4">
                                             <div class="bt_bb_listing_search_element">
                                                     <label>'.esc_html__( 'What you\'d like to find?', 'bello' ).'</label>
                                                     <input type="text" name="bt_bb_listing_field_keyword" id="bt_bb_listing_field_keyword_simple-' . esc_attr( $form_code ) . '" placeholder="'. esc_attr__( 'Keyword to search...?', 'bello' ).'">
                                             </div>
                                     </div>';
                                }
                                
                                if ( $show_location ){
                                        if ( $show_location_autocomplete ) {
                                                $output .= '<div class="bt_bb_column col-lg-3 col-md-2 col-sm-12 bt_bb_vertical_align_top bt_bb_padding_normal bt_bb_spaced bt_bb_listing_search_col" data-width="4">
                                                       <div class="bt_bb_listing_search_element bt_bb_control_container">
                                                               <label>'.esc_html__( 'Where to look for?', 'bello' ).'
                                                                  <span class="bt_bb_listing_note bt_bb_category_help" title="'.esc_attr__( 'Search for a location or let us detect your location', 'bello' ).'"></span>  
                                                               </label>
                                                               <a href="#" class="bt_bb_show_location_help" id="bt_bb_show_location-' . esc_attr( $form_code ) . '"  title="' . esc_attr__( 'Click here to detect your location or reset to default location', 'bello' ). '"></a>
                                                               <input id="bt_bb_listing_field_location_autocomplete-' . esc_attr( $form_code ) . '" name="bt_bb_listing_field_location_autocomplete" type="text" placeholder="'. esc_attr__( 'Search for location', 'bello' ).'">
                                                       </div>
                                               </div>';
                                        }else{
                                                $output .= '<div class="bt_bb_column col-lg-3 col-md-2 col-sm-12 bt_bb_vertical_align_top bt_bb_padding_normal bt_bb_spaced bt_bb_listing_search_col" data-width="4">
                                                       <div class="bt_bb_listing_search_element">
                                                               <label>'.esc_html__( 'Where to look for?', 'bello' ).'</label>
                                                               <select name="bt_bb_listing_field_region" id="listing_field_region_simple-' . esc_attr( $form_code ) . '">
                                                                       <option value="all">'.esc_html__( 'Everywhere', 'bello' ).'</option>'
                                                                       . $locs .
                                                               '</select>
                                                       </div>
                                               </div>';
                                        }
                                }
                                 
                                if ( $show_category ){
                                      $output .= '<div class="bt_bb_column col-lg-3 col-md-2 col-sm-12 bt_bb_vertical_align_top bt_bb_padding_normal bt_bb_spaced bt_bb_listing_search_col" data-width="4">
						<div class="bt_bb_listing_search_element">
							<label>'.esc_html__( 'In which category?', 'bello' ).'</label>
							<select name="bt_bb_listing_field_category" id="bt_bb_listing_field_category_simple-' . esc_attr( $form_code ) . '">
								<option value="all">' . esc_html__( 'All categories, please', 'bello' ) . '</option>'
								. $cats .
							'</select>
						</div>
					</div>';
                                }
                                        
				$output .= '</div>

				<div class="bt_bb_row bt_bb_column_gap_10">';
                                        if ( $show_text ){ 
                                            $output .= '<div class="bt_bb_column col-md-6 col-sm-12 bt_bb_align_left bt_bb_vertical_align_middle bt_bb_padding_normal bt_bb_spaced bt_bb_listing_results_announce" data-width="6">
                                                    <div class="bt_bb_column_content">
                                                            <p>' . esc_html__( 'There are over', 'bello' ) . ' <strong>' . $count_listings . '</strong> ' . esc_html__( 'listings for you to search from.', 'bello' ) . '</p>
                                                    </div>
                                            </div>';
                                        }
					$output .= '<div class="bt_bb_column col-md-6 col-sm-12 bt_bb_align_right bt_bb_vertical_align_middle bt_bb_padding_normal bt_bb_spaced bt_bb_listing_search_button" data-width="6">
						<div class="bt_bb_column_content">';
                                                        if ( $show_advance_search_link ){ 
                                                            $output .= '<div class="bt_bb_icon bt_bb_color_scheme_3 bt_bb_style_borderless bt_bb_size_xsmall bt_bb_shape_circle bt_bb_align_inherit bt_bb_advanced_search">
                                                                                    <a href="' . esc_url_raw( $advance_search_link_url ) . '" target="_self" data-ico-' . esc_attr( $icon_set ) . '="&#x' . esc_attr( $icon ) . ';" class="bt_bb_icon_holder bt_bb_listing_options_additional_filters" id="bt_bb_listing_options_additional_filters-' . esc_attr( $form_code ) . '">
                                                                                        <span>' . $advance_search_link_text . '</span>
                                                                                     </a>
                                                            </div>';
                                                        }
							$output .= '<div class="bt_bb_button bt_bb_icon_position_left bt_bb_color_scheme_6 bt_bb_style_filled bt_bb_size_normal bt_bb_width_inline bt_bb_shape_inherit bt_bb_align_inherit">
									<a href="#" id="bt_bb_link_simple_search-' . esc_attr( $form_code ) . '" class="bt_bb_link_search bt_bb_link" data-formid=' . esc_attr( $form_code ) . '>
											<span class="bt_bb_button_text">' . esc_html__( 'Search', 'bello' ) . '</span><span data-ico-fontawesome="&#xf002;" class="bt_bb_icon_holder"></span>
									</a>
							</div>';
                                                       
						$output .= '</div>
					</div>
				</div>
			</form>
		</div>
		';		
		$output .= '</div>';
                
                if ( $show_location == 'show_location' && $show_location_autocomplete ){                      
                    $output_script_location_autocomplete = 'document.addEventListener("readystatechange", function() {';
                        $output_script_location_autocomplete .= 'if ( typeof(jQuery) !== "undefined" && ( document.readyState === "interactive" || document.readyState === "complete" ) ) {';
                                $output_script_location_autocomplete .= 'jQuery( window ).load(function() {';
                                    $output_script_location_autocomplete .= 'bt_bb_autocomplete_change_sh_location(  "' .$form_code . '" );';
                                $output_script_location_autocomplete .= '});';
                                $output_script_location_autocomplete .= 'jQuery( document ).ready(function() {';
                                        $output_script_location_autocomplete .= 'jQuery(document).on("click", "#bt_bb_show_location-' . $form_code . '", function(event) {';
                                               $output_script_location_autocomplete .= 'event.preventDefault();';
                                               $output_script_location_autocomplete .= 'var user_position_' . $form_code . ' = jQuery(this).hasClass("location_detected") ? 0 : 1;';
                                               $output_script_location_autocomplete .= 'bt_sh_get_my_position(user_position_' . $form_code . ', "' . $form_code . '");';	
                                        $output_script_location_autocomplete .= '});';
                                $output_script_location_autocomplete .= '});';
                         $output_script_location_autocomplete .= '}';
                     $output_script_location_autocomplete .= '}, false);';
                    
                    wp_register_script( 'bt-bb-search-script-location-autocomplete', '' );
                    wp_enqueue_script( 'bt-bb-search-script-location-autocomplete' );
                    wp_add_inline_script( 'bt-bb-search-script-location-autocomplete', $output_script_location_autocomplete );
                }

            return $output;
	}

	function map_shortcode() {           
            if ( function_exists('boldthemes_get_icon_fonts_bb_array') ) {
                    $icon_arr = boldthemes_get_icon_fonts_bb_array();
            } else {
                    require_once( dirname(dirname(dirname(__FILE__))) . '/content_elements_misc/fa_icons.php' );
                    require_once( dirname(dirname(dirname(__FILE__))) . '/content_elements_misc/s7_icons.php' );
                    $icon_arr = array( 'Font Awesome' => bt_bb_fa_icons(), 'S7' => bt_bb_s7_icons() );
            }

            bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Search Form', 'bello' ), 'description' => esc_html__( 'Simple search', 'bello' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
                    'params' => array(
                            array( 'param_name' => 'show_keyword',  'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'bello' ) => 'show_keyword' ), 'heading' => esc_html__( 'Show keyword field', 'bello' ), 'preview' => true
                            ),
                            array( 'param_name' => 'show_location', 'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'bello' ) => 'show_location' ), 'heading' => esc_html__( 'Show location field', 'bello' ), 'preview' => true
                            ),
                            array( 'param_name' => 'show_category', 'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'bello' ) => 'show_category' ), 'heading' => esc_html__( 'Show category field', 'bello' ), 'preview' => true
                            ),
							array( 'param_name' => 'default_category_slug', 'type'  => 'textfield',  'heading' => esc_html__( 'Default category slug', 'bello' ) ),
                            array( 'param_name' => 'show_text', 'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'bello' ) => 'show_text' ), 'heading' => esc_html__( 'Show text with number of listings', 'bello' ), 'preview' => true
                            ),
                            array( 'param_name' => 'show_advance_search_link', 'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'bello' ) => 'show_advance_search_link' ), 'heading' => esc_html__( 'Show advance search link', 'bello' ), 'preview' => true
                            ),
                            array( 'param_name' => 'advance_search_link_url', 'type'  => 'textfield',  'heading' => esc_html__( 'Advance search link URL', 'bello' ) ),
                            array( 'param_name' => 'advance_search_link_text', 'type' => 'textfield',  'heading' => esc_html__( 'Advance search link Text', 'bello' ) ),
                            array( 'param_name' => 'advance_search_link_icon', 'type' => 'iconpicker', 'heading' => esc_html__( 'Advance search link Icon', 'bello' ), 'value' => $icon_arr, 'preview' => true )
                    )
            ) );
	}
}
