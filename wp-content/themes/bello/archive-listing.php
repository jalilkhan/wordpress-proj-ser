<?php 
$boldthemes_options = get_option( BoldThemesFramework::$pfx . '_theme_options' );

if ( is_post_type_archive( 'listing' ) ) {
	if ( !is_null( boldthemes_get_id_by_slug('listing') ) && boldthemes_get_id_by_slug('listing') != '' ) {
		BoldThemesFramework::$page_for_header_id = boldthemes_get_id_by_slug( 'listing' );
	}
}
if ( isset( $boldthemes_options['listing_settings_page_slug'] ) && $boldthemes_options['listing_settings_page_slug'] != '' ) {
	BoldThemesFramework::$page_for_header_id = boldthemes_get_id_by_slug( $boldthemes_options['listing_settings_page_slug'] );
}

if ( bt_map_is_osm() ) {
    require_once( 'bold-page-builder/content_elements/bt_bb_openmap/openmap/include_map.php' );
}

if ( bt_map_is_leaflet() ) {
    require_once( 'bold-page-builder/content_elements/bt_bb_leaflet_map/leafletmap/include_map.php' );
}

$listing_list_view_option = isset($boldthemes_options['listing_list_view']) && $boldthemes_options['listing_list_view'] != '' ?  
                $boldthemes_options['listing_list_view'] : BoldThemes_Customize_Default::$data['listing_list_view'];
        
BoldThemesFrameworkTemplate::$listing_list_view = isset($_GET['listing_list_view']) && $_GET['listing_list_view'] != '' ? 
        $_GET['listing_list_view'] : $listing_list_view_option;

get_header();

if ( have_posts() ) {        
        if ( BoldThemesFrameworkTemplate::$listing_list_view == 'standard' || BoldThemesFrameworkTemplate::$listing_list_view == 'with_map'  ) {
            get_template_part( 'views/listing/list/standard' );		
	} else {
            get_template_part( 'views/listing/list/standard_without_map' );		
	}        
} else {
	if ( is_search() ) { ?>
		<article class="btNoSearchResults boldSection gutter bottomSemiSpaced topSemiSpaced ">
			<div class="port">
			<?php 
			echo boldthemes_get_heading_html(
				array(
					'headline' => esc_html__( 'We are sorry, no results for: ', 'bello' ) . get_search_query(),
					'subheadline' => esc_html__( 'Back to homepage', 'bello' ),
					'url' => site_url(),
					'size' => 'medium'
				)									 
			);
			?>
			</div>
		</article>
	<?php }else{
            if ( BoldThemesFrameworkTemplate::$listing_list_view == 'standard' || BoldThemesFrameworkTemplate::$listing_list_view == 'with_map'  ) {
                get_template_part( 'views/listing/list/standard' );		
            } else {
                get_template_part( 'views/listing/list/standard_without_map' );		
            }    
        }
}

get_footer();