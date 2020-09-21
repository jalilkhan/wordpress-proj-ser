<?php
$boldthemes_options = get_option( BoldThemesFramework::$pfx . '_theme_options' );

$tmp_boldthemes_page_options = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_override' );
if ( ! is_array( $tmp_boldthemes_page_options ) ) $tmp_boldthemes_page_options = array();
$tmp_boldthemes_page_options = boldthemes_transform_override( $tmp_boldthemes_page_options );

if ( isset( $tmp_boldthemes_page_options[ BoldThemesFramework::$pfx . '_listing_settings_page_slug'] ) && $tmp_boldthemes_page_options[ BoldThemesFramework::$pfx . '_listing_settings_page_slug'] != '' ) {
	BoldThemesFramework::$page_for_header_id = boldthemes_get_id_by_slug( $tmp_boldthemes_page_options[ BoldThemesFramework::$pfx . '_listing_settings_page_slug' ] );
} else if ( isset( $boldthemes_options['listing_settings_page_slug'] ) && $boldthemes_options['listing_settings_page_slug'] != '' ) {
	BoldThemesFramework::$page_for_header_id = boldthemes_get_id_by_slug( $boldthemes_options['listing_settings_page_slug'] );
}

$listing_use_dash = boldthemes_get_option( 'listing_use_dash' );
BoldThemesFrameworkTemplate::$dash = $listing_use_dash ? 'bottom' : '';

$listing_single_view	= 'standard';
$gallery_type		= boldthemes_get_option( 'listing_single_list_gallery_type');

$terms = get_the_terms(  get_the_ID(), 'listing-category');

$media_term_id = 0;
if ( isset($terms) && is_array($terms) ) {
	$terms = end($terms);
	$media_term_id = $terms->term_id;
}

if ( bt_map_is_osm() ) {
    require_once( 'bold-page-builder/content_elements/bt_bb_openmap/openmap/include_map.php' );
}

if ( bt_map_is_leaflet() ) {
    require_once( 'bold-page-builder/content_elements/bt_bb_leaflet_map/leafletmap/include_map.php' );
}

get_header();

if ( have_posts() ) {
	
	while ( have_posts() ) {
	
		the_post();

		$featured_image = '';
		if ( has_post_thumbnail() && boldthemes_get_option( 'hide_headline' ) ) {
			$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
			$image = wp_get_attachment_image_src( $post_thumbnail_id, 'large' );
			$featured_image = $image[0];		
		}
		
		$images = function_exists( 'bello_get_listing_field_group' ) ? bello_get_listing_field_group ( 'Media' ) : array();
		if ( $images == null ) $images = array();
		$videos	= function_exists( 'bello_get_listing_field_group' ) ? bello_get_listing_field_group ( 'MediaVideo' ) : array();
		if ( $videos == null ) $videos = array();
		$audios	= function_exists( 'bello_get_listing_field_group' ) ? bello_get_listing_field_group ( 'MediaAudio' ) : array();
		if ( $audios == null ) $audios = array();

                if (function_exists( 'boldthemes_get_new_media_html_listing' )) {
                    if ( !empty($videos) ){
                            BoldThemesFrameworkTemplate::$media_video_html = boldthemes_get_new_media_html_listing( array( 'type' => 'single-listing', 'video' => $videos,  'size' => 'boldthemes_large_rectangle', 'gallery_type' => $gallery_type, 'featured_image' => $featured_image, 'showinfo' => 0, 'term_id' => $media_term_id ) );
                    }

                    if ( !empty($images) ){
                            BoldThemesFrameworkTemplate::$media_image_html = boldthemes_get_new_media_html_listing( array( 'type' => 'single-listing', 'images' => $images, 'size' => 'boldthemes_large_rectangle', 'gallery_type' => $gallery_type, 'featured_image' => $featured_image, 'term_id' => $media_term_id ) );
                    }

                    if ( !empty($audios) ){
                            BoldThemesFrameworkTemplate::$media_audio_html = boldthemes_get_new_media_html_listing( array( 'type' => 'single-listing', 'audio' => $audios, 'size' => 'boldthemes_large_rectangle', 'gallery_type' => $gallery_type, 'featured_image' => $featured_image, 'term_id' => $media_term_id ) );
                    }
                }
                
                 
		
		$permalink	= get_permalink();
		$post_format	= get_post_format();

		$content_html = apply_filters( 'the_content', get_the_content() );
		$content_html = str_replace( ']]>', ']]&gt;', $content_html );
		
		BoldThemesFrameworkTemplate::$content_html = $content_html;
		
		$post_categories = get_the_terms( get_the_ID(), 'listing-category' );
		BoldThemesFrameworkTemplate::$categories_html = boldthemes_get_post_categories( array( 'categories' => $post_categories ) );
		
                // listing tags
                BoldThemesFrameworkTemplate::$tags_html = '';
                $listing_show_tags_on_frontend_form   = boldthemes_get_option( 'listing_show_tags_on_frontend_form' ) != '' ? 
                        boldthemes_get_option( 'listing_show_tags_on_frontend_form' ) : false;
                if ( $listing_show_tags_on_frontend_form ) {
                    $tags = wp_get_object_terms( get_the_ID(), 'listing-tag' );    
                    if ( $tags ) {
                            foreach ( $tags as $tag ) {
                                    BoldThemesFrameworkTemplate::$tags_html .= '<li><a href="' . esc_url_raw( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a></li>';
                            }
                            BoldThemesFrameworkTemplate::$tags_html = rtrim( BoldThemesFrameworkTemplate::$tags_html, ', ' );
                            BoldThemesFrameworkTemplate::$tags_html = '<div class="btTags"><ul>' . BoldThemesFrameworkTemplate::$tags_html . '</ul></div>';
                    }
                }
                // /listing tags
		
		$comments_open = comments_open();
		$comments_number = get_comments_number();
		BoldThemesFrameworkTemplate::$show_comments_number = true;
		if ( ! $comments_open && $comments_number == 0 ) {
			BoldThemesFrameworkTemplate::$show_comments_number = false;
		}
		
		BoldThemesFrameworkTemplate::$class_array = array( );
		if ( BoldThemesFrameworkTemplate::$media_image_html == '' ) BoldThemesFrameworkTemplate::$class_array[] = 'noPhoto';
                
                if ( function_exists( 'bello_get_listing_field_value' ) && function_exists( 'bello_field_in_packages' ) ) {
                    $field =  bello_get_listing_field_value('faq');
                    $field_in_packages = bello_field_in_packages( $field, get_the_ID() );
                    if ( !empty($field) && $field_in_packages ) {
                        if ( isset($field["value"][0]) ) {
                            BoldThemesFrameworkTemplate::$listing_faq =  $field["value"][0];
                        }
                    }
                }

		BoldThemesFrameworkTemplate::$meta_html = boldthemes_get_post_meta();

		if ( $listing_single_view == 'columns' ) {
			get_template_part( 'views/listing/single/columns' );	
		} else {
			get_template_part( 'views/listing/single/standard' );
		}
		
		get_template_part( 'views/prev_next' );
                
	}

}

get_footer();
