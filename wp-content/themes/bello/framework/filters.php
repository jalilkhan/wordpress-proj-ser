<?php

// BoldThemes framework related
add_filter( 'get_search_form', 'boldthemes_search_form' );
add_filter( 'wp_video_shortcode', 'boldthemes_wp_video_shortcode', 10, 5 );
add_filter( 'embed_oembed_html', 'boldthemes_embed_html', 10, 3 );
add_filter( 'video_embed_html', 'boldthemes_jetpack_embed_html' ); // Jetpack
add_filter( 'wp_video_shortcode_library', 'boldthemes_wp_video_shortcode_library' );
add_filter( 'wp_audio_shortcode_library', 'boldthemes_wp_audio_shortcode_library' );
add_filter( 'body_class', 'boldthemes_get_body_class' );

add_filter( 'pre_get_document_title', 'boldthemes_get_html_title', 10, 1 );

add_filter( 'wp_kses_allowed_html', 'boldthemes_allowed_html' );

/**
 * HTML title fix for portfolio
 */
 
 function boldthemes_get_html_title( $title = NULL, $sep = ' - ', $seplocation = NULL ) {

	if ( is_post_type_archive( 'portfolio' ) ) {
		$pf_title = '';
		if ( ! is_null( boldthemes_get_option( 'pf_slug' ) ) && boldthemes_get_option( 'pf_slug' ) != '' ) {
			$pf_slug_id = boldthemes_get_id_by_slug( boldthemes_get_option( 'pf_slug' ) );
			if ( $pf_slug_id > 0 ) {
				$pf_title = get_the_title( $pf_slug_id );	
			} else {
				$pf_title = $pf_slug_id . ucwords( boldthemes_get_option( 'pf_slug' ) );	
			}
		}
		
		if ( ! is_null( boldthemes_get_id_by_slug( 'portfolio' ) ) && boldthemes_get_id_by_slug( 'portfolio' ) != '' ) {
			$pf_title = get_the_title( boldthemes_get_id_by_slug( 'portfolio' ) );
		}
		if ( $pf_title != '' ) {
			return $pf_title . $sep . get_bloginfo( 'name', 'display' ) ;
		} else {
			return NULL;
		}
	}
 }


/**
 * Custom search form
 *
 * @return string
 */
if ( ! function_exists( 'boldthemes_search_form' ) ) {
	function boldthemes_search_form( $form ) {
		$form = '<div class="btSearch">';
		$form .= boldthemes_get_icon_html( array( 'icon' => 'fa_f002', 'url' => '#' ) );
		$form .= '
		<div class="btSearchInner gutter" role="search">
			<div class="btSearchInnerContent port">
				<form action="' . esc_attr( home_url( '/' ) ) . '" method="get"><input type="text" name="s" placeholder="' . esc_attr__( 'Looking for...', 'bello' ) . '" class="untouched">
				<button type="submit" data-icon="&#xf105;"></button>
				</form>
				<div class="btSearchInnerClose">' . boldthemes_get_icon_html( array( 'icon' => 'fa_f00d', 'url' => '#' ) ) . '</div>
			</div>
		</div>';
		$form .= '</div>';
		return $form;
	}
}

/**
 * Video shortcode custom HTML
 *
 * @return string
 */
if ( ! function_exists( 'boldthemes_wp_video_shortcode' ) ) {
	function boldthemes_wp_video_shortcode( $item_html, $atts, $video, $post_id, $library ) {
		$replace_value = 'width: ' . $atts['width'] . 'px';
		$replace_with  = 'width: 100%';
		$item_html = str_ireplace( $replace_value, $replace_with, $item_html );
		return '<div class="bt-video-container">' . $item_html . '</div>';
	}
}

/**
 * Enqueue video shortcode custom JS
 *
 * @return string 
 */
if ( ! function_exists( 'boldthemes_wp_video_shortcode_library' ) ) {
	function boldthemes_wp_video_shortcode_library() {
		wp_enqueue_style( 'wp-mediaelement' );
		wp_enqueue_script( 'boldthemes-video-shortcode', get_parent_theme_file_uri( 'framework/js/video_shortcode.js' ), array( 'mediaelement' ), '', true );
		return 'boldthemes_mejs';
	}
}

/**
 * Enqueue audio shortcode custom JS
 *
 * @return string 
 */
if ( ! function_exists( 'boldthemes_wp_audio_shortcode_library' ) ) {
	function boldthemes_wp_audio_shortcode_library() {
		wp_enqueue_style( 'wp-mediaelement' );
		wp_enqueue_script( 'boldthemes-audio-shortcode', get_parent_theme_file_uri( 'framework/js/audio_shortcode.js' ), array( 'mediaelement' ), '', true );
		return 'boldthemes_mejs';
	}
}

/*  Add responsive container to embeds
 *
 * @return string 
/* ------------------------------------ */ 
function boldthemes_embed_html( $cache, $url, $attr ) {
	if ( false !== strpos( $url, 'vimeo.com' ) || false !== strpos( $url, 'youtube.com' ) ) { 
		return '<div class="bt-video-container">' . $cache . '</div>';
	} else {
		return $cache;
	}
}

/*  Add responsive container to embeds
 *
 * @return string 
/* ------------------------------------ */ 
function boldthemes_jetpack_embed_html( $html ) {
	return '<div class="bt-video-container">' . $html . '</div>';
}

/**
 * Alowed html
 */
if ( ! function_exists( 'boldthemes_allowed_html' ) ) {
	function boldthemes_allowed_html( $tags ) {
		$allowed_attributes = array(
			'class' => true,
			'id' => true,
			'class' => true,
			'target' => true,
			'title' => true,
			'src' => true,
			'style' => true,
			'data-ico-fa' => true,
			'data-ico-icon7stroke' => true,
			'id' => true,
			'href' => true
        );
		$tags['span'] = $allowed_attributes;
		$tags['div'] = $allowed_attributes;
		$tags['a'] = $allowed_attributes;
		$tags['iframe'] = array(
			'src'         => true,
			'height'      => true,
			'width'       => true,
			'scrolling'   => true,
			'frameborder' => true,
		);
		return $tags;
	}
}