<?php
/*
*
*	Custom Styles in AMP
*
*/

if ( ! function_exists( 'boldthemes_get_color' ) ) {
    function boldthemes_get_color( $amp_customizer_setting = '' ) {
        $amp_customizer     = get_option('amp_customizer');
        $amp_color_scheme   = $amp_customizer['color_scheme'];// light / dark        
        if ( $amp_color_scheme == 'light' ){
             $arr = array(
                'theme_color'               => '#FFFFFF',
                'text_color'                => '#181818',
                'muted_text_color'          => '#696969',
                'border_color'              => '#c2c2c2',
                'link_color'                => '#c2c2c2',
                'header_background_color'   => '#c2c2c2',
                'header_color'              => '#c2c2c2',
            );            
        }else{
             $arr = array(
                'theme_color'               => '#181818',
                'text_color'                => '#FFFFFF',
                'muted_text_color'          => '#b1b1b1',
                'border_color'              => '#707070',
                'link_color'                => '#c2c2c2',
                'header_background_color'   => '#c2c2c2',
                'header_color'              => '#c2c2c2',
            );            
        }        
        return isset($arr[$amp_customizer_setting]) ? $arr[$amp_customizer_setting] : '';
    }
}

add_action( 'amp_post_template_css', 'boldthemes_amp_additional_css_styles' );
function boldthemes_amp_additional_css_styles( $amp_template ) {

	// Get Customize CSS   
	$logo = boldthemes_get_option('logo');
	if ( '' === $logo) {
		$logo	= boldthemes_get_option('alt_logo');
	}

	$body_font =  urldecode(boldthemes_get_option('body_font'));
	if (  $body_font == 'no_change' ){
		$body_font	= "Roboto";
	}

	$heading_font =  urldecode(boldthemes_get_option('heading_font'));
	if (  $heading_font == 'no_change' ){
		$heading_font	= "Roboto Slab";
	}

	$accent_color =  boldthemes_get_option('accent_color');
	if ( '' === $accent_color ){
		$accent_color = '#dc0003';
	}

	$alternate_color =  boldthemes_get_option('alternate_color');
	if ( '' === $alternate_color ){
		$alternate_color = '#616161';
	}
        
        $colors_from_customizer = false;
        
        if ( $colors_from_customizer ){
                /* colors from amp customizer settings */
                $accent_color_amp	 	 = boldthemes_sanitize_hex_text_color( 'text_color' );	
                $alternate_color_amp	 = boldthemes_sanitize_hex_text_color( 'text_color' );	

                $theme_color             = boldthemes_sanitize_hex_text_color( 'theme_color' );
                $text_color              = boldthemes_sanitize_hex_text_color( 'text_color' );
                $muted_text_color        = boldthemes_sanitize_hex_text_color( 'muted_text_color' );
                $border_color            = boldthemes_sanitize_hex_text_color( 'border_color' );

                $link_color              = boldthemes_sanitize_hex_text_color( 'link_color' );
                $header_background_color = boldthemes_sanitize_hex_text_color( 'header_background_color' );
                $header_color            = boldthemes_sanitize_hex_text_color( 'header_color' );
        }else{        
                /* custom colors settings from boldthemes_get_color */        
                $accent_color_amp	 	 = boldthemes_get_color('text_color');
                $alternate_color_amp	 = boldthemes_get_color('text_color');

                $theme_color             = boldthemes_get_color('theme_color');
                $text_color              = boldthemes_get_color('text_color');
                $muted_text_color        = boldthemes_get_color('muted_text_color');
                $border_color            = boldthemes_get_color('border_color');

                $link_color              = boldthemes_get_color('link_color');
                $header_background_color = boldthemes_get_color('header_background_color');
                $header_color            = boldthemes_get_color('header_color');
        }
	?>

	body {
		background: <?php echo wp_kses_post( $theme_color ); ?>;
		font: 16px/26px <?php echo wp_kses_post( $body_font ); ?>;
		color: <?php echo wp_kses_post( $text_color ); ?>;
	}
	header.amp-wp-header {
		box-shadow: 0 0 35px 0 rgba(24,24,24,.15);
	}
	.amp-wp-header .amp-wp-site-icon {
		display: none;
	}

	<?php
		if ( $logo ) {
	?>
			header.amp-wp-header a {
				background: url( '<?php echo esc_url( $logo);?>' ) no-repeat;
				background-size: contain;
				display: block;
				height: 70px;
				margin: 0 auto;
				text-indent: -9999px;
			}
	<?php
		}
	?>

	.amp-wp-header div {
		padding: 10px 50px;
	}

	.amp-wp-meta,
	.amp-wp-header div,
	.amp-wp-title,
	.wp-caption-text,
	.amp-wp-tax-category,
	.amp-wp-tax-tag,
	.amp-wp-comments-link,
	.amp-wp-footer p,
	.back-to-top {
		font-family: <?php echo wp_kses_post( $body_font ); ?>;
	}
	.amp-wp-article-header {
		margin: 1.5em 50px 1.5em;
	}
	.amp-wp-article {
		color: <?php echo wp_kses_post( $text_color ); ?>;
	}
	.amp-wp-title {
		font-family: <?php echo wp_kses_post( $heading_font ); ?>;
		line-height: 1em;
		font-size: 2em;
		margin: 0;
		color: <?php echo wp_kses_post( $text_color ); ?>;
	}
	.amp-wp-meta.amp-wp-byline, .amp-wp-meta.amp-wp-posted-on {
		display: none;
	}
	.amp-wp-article-featured-image {
		margin-bottom: 30px;
	}
	.amp-wp-article-content {
		margin: 0 50px 30px;
		border-bottom: 1px solid rgba(0,0,0,.1);
	}
	.amp-wp-meta {
		font-size: .6875em;
	}
	.amp-wp-byline amp-img {
		border: 2px solid rgba(0,0,0,.1);
	}
	.amp-wp-article-content h1,
	.amp-wp-article-content h2,
	.amp-wp-article-content h3,
	.amp-wp-article-content h4,
	.amp-wp-article-content h5,
	.amp-wp-article-content h6 {
		font-family: <?php echo wp_kses_post( $heading_font ); ?>;
		font-weight: 700;
		line-height: 1.5;
		font-size: 1.25em;		
	}
	blockquote {
		font-family: <?php echo wp_kses_post( $heading_font ); ?>;
		background: transparent;
		border: 0;
		padding: 1.25em 0 0;
		position: relative;
		font-size: 1.4em;
		line-height: 1.5em;
		font-style: italic;
		font-weight: 400;
		color: <?php echo wp_kses_post( $text_color ); ?>;
	}
	blockquote:before {
		content: '”';
		display: block;
		font-size: 5.33em;
		line-height: 1;
		position: absolute;
		top: -1.125rem;
		left: -5px;
		font-style: normal;
		font-weight: 500;
		opacity: .1;
	}


	a {
		color: <?php echo wp_kses_post( $accent_color ); ?>;
		transition: color 300ms ease;
	}

	a:hover,
	a:active,
	a:focus {
		color: <?php echo wp_kses_post( $alternate_color ); ?>;
		transition: color 300ms ease;
	}
	.amp-wp-tax-category, .amp-wp-tax-tag {
		margin: 1.5em 50px;
	}
	.amp-wp-tax-category a {
		background: <?php echo wp_kses_post( $alternate_color ); ?>;
		color: #FFF;
		text-decoration: none;
		text-transform: uppercase;
		padding: .625em 1em;
		border-radius: 2px;
		transition: opacity 300ms ease;
		margin-right: -3px;
	}
	.amp-wp-tax-category a:hover,
	.amp-wp-tax-category a:active,
	.amp-wp-tax-category a:focus {
		opacity: .5;
	}
	.amp-wp-tax-tag a {
		color: #181818;
		background: rgba(0,0,0,.1);
		text-decoration: none;
		padding: .625em 1em;
		border-radius: 2px;
		transition: background 300ms ease, color 300ms ease;
		margin-right: 2px;
	}
	.amp-wp-tax-tag a:hover,
	.amp-wp-tax-tag a:active,
	.amp-wp-tax-tag a:focus {
		background: #181818;
		color: #FFF;
		transition: background 300ms ease, color 300ms ease;
	}
	.amp-wp-comments-link a {
		font-weight: 700;
		background: <?php echo wp_kses_post( $accent_color ); ?>;
		color: #FFF;
		border: 0;
		border-radius: 2px;
		transition: all 300ms ease;
		padding: .675em .923em;
		font-size: 14px;
	}
	.amp-wp-comments-link a:hover,
	.amp-wp-comments-link a:active,
	.amp-wp-comments-link a:focus {
		background: <?php echo wp_kses_post( $alternate_color ); ?>;
		transition: all 300ms ease;
		box-shadow: 0 1px 5px rgba(0,0,0,.35);
	}

	.amp-wp-footer {
		background: rgba(0,0,0,.07);
		box-shadow: rgba(0,0,0,.2) 0 -40px 0 0 inset;
		color: #FFF;
		border: 0;
		border-bottom: 4px solid <?php echo wp_kses_post( $accent_color ); ?>;
	}
	.amp-wp-footer div {
		padding: 1.5em 50px .5em;
	}
	.amp-wp-footer h2 {
		text-indent: -999999px;
		background: url( '<?php echo esc_url( $logo);?>' ) no-repeat 50%;
		background-size: contain;
		height: 50px;
		margin: 0 0 2em;
	}
	.amp-wp-footer a {
		color: #FFF;
	}
	.back-to-top {
		bottom: 4px;
		text-transform: uppercase;
	}

	<?php
}

/*
*
*	Loading fonts in AMP
*
*/

add_action( 'amp_post_template_head', 'boldthemes_amp_post_template_add_fonts' );

function boldthemes_amp_post_template_add_fonts( ) {

	$body_font	= urldecode(boldthemes_get_option('body_font'));
	$heading_font	= urldecode(boldthemes_get_option('heading_font'));

	if ( $body_font != 'no_change' ) {
		$url_body_font = $body_font . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
	} else {
		$body_font_state = _x( 'on', 'Roboto font: on or off', 'bello' );
		if ( 'off' !== $body_font_state ) {
			$url_body_font = 'Roboto' . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
		}
	}

	if ( $heading_font != 'no_change' ) {
		$url_heading_font = $heading_font . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
	} else {
		$heading_font_state = _x( 'on', 'Roboto Slab font: on or off', 'bello' );
		if ( 'off' !== $heading_font_state ) {
			$url_heading_font = 'Roboto Slab' . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
		}
	}
	
	$url_body_font		= 'https://fonts.googleapis.com/css?family=' . $url_body_font;
	$url_heading_font	= 'https://fonts.googleapis.com/css?family=' . $url_heading_font;
	
	?>
		<link rel="stylesheet" href="<?php echo esc_url_raw ( $url_body_font ); ?>" type='text/css'>
		<link rel="stylesheet" href="<?php echo esc_url_raw ( $url_heading_font ); ?>" type='text/css'>
	<?php

	$meta_tags = array(
		sprintf( '<link rel="icon" href="%s" sizes="32x32" />', esc_attr( get_site_icon_url( 32 ) ) ),
		sprintf( '<link rel="icon" href="%s" sizes="192x192" />', esc_attr( get_site_icon_url( 192 ) ) ),
		sprintf( '<link rel="apple-touch-icon-precomposed" href="%s" />', esc_attr( get_site_icon_url( 180 ) ) ),
		sprintf( '<meta name="msapplication-TileImage" content="%s" />', esc_attr( get_site_icon_url( 270 ) ) ),
	);
	
	foreach ( $meta_tags as $meta_tag ) {
		echo "$meta_tag\n";
	}
}

/*
*
*	Helper function for colors in AMP
*
*/

if ( ! function_exists( 'boldthemes_sanitize_hex_text_color' ) ) {
	function boldthemes_sanitize_hex_text_color( $amp_customizer_setting = 'text_color' ) {
		$template = new AMP_Post_Template( get_queried_object_id() );
		$color	  = $template->get_customizer_setting( $amp_customizer_setting );		
		// 3 or 6 hex digits, or the empty string.
		if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
			return $color;
		}else{
			return '';
		}
		
	}
}

/* plugin default
 function get_color_schemes() {
        return array(
                'light' => array(
                        // Convert colors to greyscale for light theme color; see <http://goo.gl/2gDLsp>.
                        'theme_color'      => '#fff',
                        'text_color'       => '#353535',
                        'muted_text_color' => '#696969',
                        'border_color'     => '#c2c2c2',
                ),
                'dark' => array(
                        // Convert and invert colors to greyscale for dark theme color; see <http://goo.gl/uVB2cO>.
                        'theme_color'      => '#0a0a0a',
                        'text_color'       => '#dedede',
                        'muted_text_color' => '#b1b1b1',
                        'border_color'     => '#707070',
                ),
        );
}
 */









