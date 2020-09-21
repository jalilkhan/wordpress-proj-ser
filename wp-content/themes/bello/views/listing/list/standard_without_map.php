<?php 
get_template_part( 'views/listing/list/params' ); 

$listing_grid_listings_pagination   = boldthemes_get_option( 'listing_grid_listings_pagination' ) != '' ?  boldthemes_get_option( 'listing_grid_listings_pagination' ) : 'paged';
$listing_search_distance_unit       = boldthemes_get_option( 'listing_search_distance_unit' ) != '' ? boldthemes_get_option( 'listing_search_distance_unit' ) : 'mi';
$listing_search_distance_radius     = boldthemes_get_option( 'listing_search_distance_radius' ) != '' ? boldthemes_get_option( 'listing_search_distance_radius' ) : '100';
$listing_default_image              = boldthemes_get_option( 'listing_default_image' )	!= '' ? boldthemes_get_option( 'listing_default_image' )
        : BoldThemes_Customize_Default::$data['listing_default_image'];

$listing_search_map_type = boldthemes_get_option( 'listing_search_map_type' ) != '' ? boldthemes_get_option( 'listing_search_map_type' ) : 'google';

$listing_map_localization              = boldthemes_get_option( 'listing_map_localization' )	!= '' ? boldthemes_get_option( 'listing_map_localization' )
        : BoldThemes_Customize_Default::$data['listing_map_localization'];

wp_register_script( 'markerclusterer', get_template_directory_uri() . '/views/listing/js/markerclusterer.js' );
wp_localize_script( 'markerclusterer', 'gmaps_markerclusterer_object', array( 'image_path' => get_template_directory_uri() . '/images/m' ) );
wp_enqueue_script( 'markerclusterer' );

/* google autocomplete */
$listing_api_key	= boldthemes_get_option( 'listing_api_key' ) != '' ? boldthemes_get_option( 'listing_api_key' ) : '';
if ( $listing_api_key != '' ) {   
	if ( !wp_script_is( 'gmaps_api_autocomplete_search', 'enqueued' ) ) {
		wp_enqueue_script( 
			'gmaps_api_autocomplete_search',
			'https://maps.googleapis.com/maps/api/js?key=' . $listing_api_key . '&language=' . $listing_map_localization . '&libraries=places'
		);
	}
}else {
     if ( ! wp_script_is( 'gmaps_api', 'enqueued' ) ) {
	wp_enqueue_script( 
		'gmaps_api',
		'https://maps.googleapis.com/maps/api/js?callback=bt_bb_gmap_init&language=' . $listing_map_localization . '#asyncload'
	);
     }
}

wp_register_script( 'bello_listing_custom_no_map_js', get_template_directory_uri() . '/views/listing/js/custom_no_map.js' );
wp_localize_script( 'bello_listing_custom_no_map_js', 'ajax_object', array( 
	'ajax_lat'	=> BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lat, 
	'ajax_lng'	=> BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lng,
	'ajax_unit'	=> $listing_search_distance_unit,
	'ajax_radius'	=> $listing_search_distance_radius
	) 
);
wp_enqueue_script( 'bello_listing_custom_no_map_js' );

wp_register_script( 'bello_listing_search_js', get_template_directory_uri() . '/views/listing/js/search.js' );
wp_localize_script( 'bello_listing_search_js', 'ajax_object', array( 
	'ajax_url'				=> admin_url( 'admin-ajax.php' ), 
	'ajax_listing_view'			=> BoldThemesFrameworkTemplate::$listing_list_view,
	'ajax_listing_category'			=> BoldThemesFrameworkTemplate::$listing_category,
        'ajax_listing_region'			=> BoldThemesFrameworkTemplate::$listing_region,
	'ajax_listing_tag'			=> BoldThemesFrameworkTemplate::$listing_tag,
	'ajax_listing_search_term'		=> BoldThemesFrameworkTemplate::$keyword,
	'ajax_action'				=> 'bt_get_listing_results_action',
	'ajax_action_count'			=> 'bt_get_listing_results_count_action',	
	'ajax_action_additional_filter'         => 'bt_get_listing_additional_filter_action',
        'ajax_action_search'			=> 'bt_get_listing_search_action',
        'ajax_lat'                              => BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lat, 
	'ajax_lng'                              => BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lng,
        'ajax_unit'                             => $listing_search_distance_unit,
	'ajax_radius'                           => $listing_search_distance_radius,
	'current_page'				=> 1,
	'max_page'				=> BoldThemesFrameworkTemplate::$max_page,
	'listing_search_type'			=> BoldThemesFrameworkTemplate::$listing_search_type,
	'ajax_listing_gets'			=> BoldThemesFrameworkTemplate::$listing_gets,
        'posts_per_page'                        => BoldThemesFrameworkTemplate::$posts_per_page,
        'ajax_pagination'			=> $listing_grid_listings_pagination,
        'ajax_random_distance'			=> BoldThemesFrameworkTemplate::$ajax_random_distance,
        'ajax_listing_search_map_type'		=> $listing_search_map_type,
        'ajax_label_found'                      => esc_html__( 'Found', 'bello' ),
        'ajax_label_results'                    => esc_html__( 'results', 'bello' ),
        'ajax_label_loading_listings'           => esc_html__( 'Loading listings', 'bello' ),
        'ajax_label_load_more_listings'         => esc_html__( 'Load more listings', 'bello' ),
		'ajax_label_m'         => esc_html__( 'm', 'bello' ),
		'ajax_label_km'         => esc_html__( 'km', 'bello' ),
		'ajax_label_mi'         => esc_html__( 'mi', 'bello' ),
	) 
);
wp_enqueue_script( 'bello_listing_search_js' );

wp_register_script( 'boldthemes-script-standard-without-map-1', '' );
wp_enqueue_script( 'boldthemes-script-standard-without-map-1' );
wp_add_inline_script( 'boldthemes-script-standard-without-map-1', 'var myMarkers = [];var map;var custom_style = "";var markerClusterer = null;' );
?>
	
<div class="bt_bb_wrapper">
	<section class="bt_bb_section gutter bt_bb_layout_boxed_1200 bt_bb_vertical_align_top">
		<div class="bt_bb_port">
			<div class="bt_bb_cell">
				<div class="bt_bb_cell_inner">
					<div>
						<div class="bt_bb_listing_search_parameters">
							<div class="bt_bb_listing_search_inner">								
								<?php get_template_part( 'views/listing/list/search' ); ?>														
								<div class="bt_bb_post_grid_loader" style="display: none;"></div>
                                                                <?php
                                                                    $list_view_class = '';
                                                                    $list_grid_class = '';  
                                                                    
                                                                    if (BoldThemesFrameworkTemplate::$listing_list_grid_view == 'list' ){
                                                                        $list_view_class = '  bt_bb_listing_view_as_list';
                                                                    }else{
                                                                        if ( BoldThemesFrameworkTemplate::$listing_grid_columns != '2' ) {
                                                                            $list_grid_class = ' bt_bb_listing_grid_one_column';
                                                                        }
                                                                    }
                                                                    
                                                                ?>
								<div class="bt_bb_listing_view_as_grid<?php echo esc_attr( $list_view_class );?><?php echo esc_attr($list_grid_class);?>"  id="bt_bb_listing_view_container" data-columns="<?php echo BoldThemesFrameworkTemplate::$listing_grid_columns;?>" data-maxpage="<?php echo BoldThemesFrameworkTemplate::$max_page;?>" data-number="<?php echo BoldThemesFrameworkTemplate::$posts_per_page;?>" data-offset="1">	
                                                                
                                                                <?php                                                                
                                                                    boldthemes_listing_box_html( 
                                                                                BoldThemesFrameworkTemplate::$listings, 
                                                                                BoldThemesFrameworkTemplate::$limit, 
                                                                                0, 
                                                                                BoldThemesFrameworkTemplate::$paged, 
                                                                                1 
                                                                            );
                                                                ?>                                                                
								</div><!-- /bt_bb_listing_view_container -->                                                                
                                                                 <?php  
                                                                 
                                                                 if ( $listing_grid_listings_pagination == 'loadmore' ){
                                                                    boldthemes_listing_pagination();
                                                                 }
                                                                ?>                                                               
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div id="bt_listing_loading"></div>
</div>