<?php
// Register action/filter callbacks
add_action( 'after_setup_theme', 'bello_register_menus' );
add_action( 'wp_enqueue_scripts', 'bello_enqueue_scripts_styles' );
add_action( 'tgmpa_register', 'bello_register_plugins' );
add_action( 'wp_enqueue_scripts', 'bello_load_fonts' );

add_action( 'admin_enqueue_scripts', 'bello_load_admin_style' );

add_filter( 'bt_bb_color_scheme_arr', 'bello_color_schemes' );

add_filter( 'body_class', 'bello_body_class' );

add_theme_support( 'customize-selective-refresh-widgets' );

add_editor_style( 'admin-style.css' );

// callbacks



/**
 * Register navigation menus
 */
if ( ! function_exists( 'bello_register_menus' ) ) {
	function bello_register_menus() {
		register_nav_menus( array (
			'primary' => esc_html__( 'Primary Menu', 'bello' ),
			'footer'  => esc_html__( 'Footer Menu', 'bello' )
		));
	}
}

/**
 * Enqueue scripts and styles
 */
if ( ! function_exists( 'bello_enqueue_scripts_styles' ) ) {
	function bello_enqueue_scripts_styles() {
		
		BoldThemesFramework::$crush_vars_def = array( 'accentColor', 'alternateColor', 'bodyFont', 'menuFont', 'headingFont', 'headingSuperTitleFont', 'headingSubTitleFont', 'logoHeight' );
		
		// Custom accent color and font style
		$boldthemes_add_override_css = false;		
		
		$accent_color = boldthemes_get_option( 'accent_color' );
		$alternate_color = boldthemes_get_option( 'alternate_color' );
		$body_font = urldecode( boldthemes_get_option( 'body_font' ) );
		$menu_font = urldecode( boldthemes_get_option( 'menu_font' ) );
		$heading_font = urldecode( boldthemes_get_option( 'heading_font' ) );
		$heading_supertitle_font = urldecode( boldthemes_get_option( 'heading_supertitle_font' ) );
		$heading_subtitle_font = urldecode( boldthemes_get_option( 'heading_subtitle_font' ) );
		$logo_height = urldecode( boldthemes_get_option( 'logo_height' ) );

		if ( $accent_color != '' ) {
			BoldThemesFramework::$crush_vars['accentColor'] = $accent_color;
			if ( $accent_color != BoldThemes_Customize_Default::$data['accent_color'] ) {
				$boldthemes_add_override_css = true;
			}
		}

		if ( $alternate_color != '' ) {
			BoldThemesFramework::$crush_vars['alternateColor'] = $alternate_color;
			if ( $alternate_color != BoldThemes_Customize_Default::$data['alternate_color'] ) {
				$boldthemes_add_override_css = true;
			}
		}
		if ( $body_font != '' ) {
			if ( $body_font == 'no_change' ) {
				$body_font = BoldThemes_Customize_Default::$data['body_font'];
			}
			BoldThemesFramework::$crush_vars['bodyFont'] = $body_font;
			if ( $body_font != BoldThemes_Customize_Default::$data['body_font'] ) {
				$boldthemes_add_override_css = true;
			}
		}

		if ( $menu_font != '' ) {
			if ( $menu_font == 'no_change' ) {
				$menu_font = BoldThemes_Customize_Default::$data['menu_font'];
			}
			BoldThemesFramework::$crush_vars['menuFont'] = $menu_font;
			if ( $menu_font != BoldThemes_Customize_Default::$data['menu_font'] ) {
				$boldthemes_add_override_css = true;
			}
		}

		if ( $heading_font != '' ) {
			if ( $heading_font == 'no_change' ) {
				$heading_font = BoldThemes_Customize_Default::$data['heading_font'];
			}
			BoldThemesFramework::$crush_vars['headingFont'] = $heading_font;
			if ( $heading_font != BoldThemes_Customize_Default::$data['heading_font'] ) {
				$boldthemes_add_override_css = true;
			}
		}

		if ( $heading_supertitle_font != '' ) {
			if ( $heading_supertitle_font == 'no_change' ) {
				$heading_supertitle_font = BoldThemes_Customize_Default::$data['heading_supertitle_font'];
			}
			BoldThemesFramework::$crush_vars['headingSuperTitleFont'] = $heading_supertitle_font;
			if ( $heading_supertitle_font != BoldThemes_Customize_Default::$data['heading_supertitle_font'] ) {
				$boldthemes_add_override_css = true;
			}
		}

		if ( $heading_subtitle_font != '' ) {
			if ( $heading_subtitle_font == 'no_change' ) {
				$heading_subtitle_font = BoldThemes_Customize_Default::$data['heading_subtitle_font'];
			}
			BoldThemesFramework::$crush_vars['headingSubTitleFont'] = $heading_subtitle_font;
			if ( $heading_subtitle_font != BoldThemes_Customize_Default::$data['heading_subtitle_font'] ) {
				$boldthemes_add_override_css = true;
			}
		}
		
		if ( $logo_height != '' ) {
			BoldThemesFramework::$crush_vars['logoHeight'] = $logo_height;
			if ( $logo_height != BoldThemes_Customize_Default::$data['logo_height'] ) {
				$boldthemes_add_override_css = true;
			}
		}

		// Create override file without local settings

		if ( function_exists( 'boldthemes_csscrush_file' ) ) {
			boldthemes_csscrush_file( get_stylesheet_directory() . '/style.crush.css', array( 'source_map' => true, 'minify' => false, 'output_file' => 'style', 'formatter' => 'block', 'boilerplate' => false, 'vars' => BoldThemesFramework::$crush_vars, 'plugins' => array( 'loop', 'ease' ) ) );
		}

		// custom theme css
		wp_enqueue_style( 'bello-style', get_template_directory_uri() . '/style.css', array(), false, 'screen' );
		wp_enqueue_style( 'bello-print', get_template_directory_uri() . '/print.css', array(), false, 'print' );

		// external js
		wp_enqueue_script( 'fancySelect', get_template_directory_uri() . '/framework/js/fancySelect.js', array( 'jquery' ), '', true );

		// custom theme js
		wp_enqueue_script( 'bello-header', get_template_directory_uri() . '/framework/js/header.misc.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'bello-misc', get_template_directory_uri() . '/framework/js/misc.js', array( 'jquery' ), '', true );
		
		wp_add_inline_script( 'bello-header', boldthemes_set_global_uri(), 'before' );
		
		// dequeue Cost Calculator plugin style
		wp_dequeue_style( 'bt_cc_style' );
		
		if ( file_exists( get_template_directory() . '/css-override.php' ) && $boldthemes_add_override_css ) {
			require_once( get_template_directory() . '/css-override.php' );
			wp_add_inline_style( 'bello-style', $css_override );
		}
		
		if ( file_exists( get_template_directory() . '/icons.php' ) ) {
			require_once( get_template_directory() . '/icons.php' );
			wp_add_inline_style( 'bello-style', $icons );
		}

		if ( boldthemes_get_option( 'custom_js' ) != '' ) {
			wp_add_inline_script( 'bello-misc', boldthemes_get_option( 'custom_js' ) );
		}
                
		if ( bt_map_is_google() ) {		
			wp_register_script( 'markerclusterer', get_template_directory_uri() . '/views/listing/js/markerclusterer.js' );
			wp_localize_script( 'markerclusterer', 'gmaps_markerclusterer_object', array( 'image_path' => get_template_directory_uri() . '/images/m' ) );
			wp_enqueue_script( 'markerclusterer' );
		}
               
	}
}

/**
 * Register the required plugins for this theme
 */
if ( ! function_exists( 'bello_register_plugins' ) ) {
	function bello_register_plugins() {

		$plugins = array(
	 
			array(
				'name'               => esc_html__( 'Bello', 'bello' ), // The plugin name.
				'slug'               => 'bello', // The plugin slug (typically the folder name).
				'source'             => get_template_directory() . '/plugins/bello.zip', // The plugin source.
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '1.5.1', ///!do not change this comment! E.g. 1.0.0. If set, the active plugin must be this version or higher.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			),
			array(
				'name'               => esc_html__( 'BT Favorites', 'bello' ), // The plugin name.
				'slug'               => 'bt' . '_favorites', // The plugin slug (typically the folder name).
				'source'             => get_template_directory() . '/plugins/' . 'bt' . '_favorites.zip', // The plugin source.
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '1.0.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			),
			array(
				'name'               => esc_html__( 'Cost Calculator', 'bello' ), // The plugin name.
				'slug'               => 'bt' . '_cost_calculator', // The plugin slug (typically the folder name).
				'source'             => get_template_directory() . '/plugins/' . 'bt' . '_cost_calculator.zip', // The plugin source.
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '2.0.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			),
			array(
				'name'               => esc_html__( 'Bold Builder', 'bello' ), // The plugin name.
				'slug'               => 'bold-page-builder', // The plugin slug (typically the folder name).
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			),
			array(
				'name'               => esc_html__( 'BoldThemes WordPress Importer (Bello)', 'bello' ), // The plugin name.
				'slug'               => 'bt' . '_wordpress_importer_bello', // The plugin slug (typically the folder name).
				'source'             => get_template_directory() . '/plugins/' . 'bt' . '_wordpress_importer_bello.zip', // The plugin source.
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '2.1.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			),
			array(
				'name'               => esc_html__( 'Meta Box', 'bello' ), // The plugin name.
				'slug'               => 'meta-box', // The plugin slug (typically the folder name).
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			),
			array(
				'name'               => esc_html__( 'MB Frontend Submission', 'bello' ), // The plugin name.
				'slug'               => 'mb-frontend-submission', // The plugin slug (typically the folder name).
				'source'             => get_template_directory() . '/plugins/' . 'mb-frontend-submission.zip', // The plugin source.
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '2.2.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			),
			array(
				'name'               => esc_html__( 'Contact Form 7', 'bello' ), // The plugin name.
				'slug'               => 'contact-form-7', // The plugin slug (typically the folder name).
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			),
			array(
				'name'               => esc_html__( 'WooCommerce', 'bello' ), // The plugin name.
				'slug'               => 'woocommerce', // The plugin slug (typically the folder name).
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			),
			array(
				'name'               => esc_html__( 'WooSidebars', 'bello' ), // The plugin name.
				'slug'               => 'woosidebars', // The plugin slug (typically the folder name).
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			),
			array(
				'name'               => esc_html__( 'WooCommerce Subscriptions', 'bello' ), // The plugin name.
				'slug'               => 'woocommerce-subscriptions', // The plugin slug (typically the folder name).
				'source'             => get_template_directory() . '/plugins/' . 'woocommerce-subscriptions.zip', // The plugin source.
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '2.3.7', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			)
		);
	 
		$config = array(
			'default_path' => '',                      // Default absolute path to pre-packaged plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => false,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
			'strings'      => array(
				'page_title'                      => esc_html__( 'Install Required Plugins', 'bello' ),
				'menu_title'                      => esc_html__( 'Install Plugins', 'bello' ),
				'installing'                      => esc_html__( 'Installing Plugin: %s', 'bello' ), // %s = plugin name.
				'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'bello' ),
				'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'bello' ), // %1$s = plugin name(s).
				'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'bello' ), // %1$s = plugin name(s).
				'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'bello' ), // %1$s = plugin name(s).
				'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'bello' ), // %1$s = plugin name(s).
				'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'bello' ), // %1$s = plugin name(s).
				'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'bello' ), // %1$s = plugin name(s).
				'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'bello' ), // %1$s = plugin name(s).
				'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'bello' ), // %1$s = plugin name(s).
				'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'bello' ),
				'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'bello' ),
				'return'                          => esc_html__( 'Return to Required Plugins Installer', 'bello' ),
				'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'bello' ),
				'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'bello' ), // %s = dashboard link.
				'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
			)
		);
	 
		tgmpa( $plugins, $config );
	 
	}
}

/**
 * Loads custom Google Fonts
 */
if ( ! function_exists( 'bello_load_fonts' ) ) {
	function bello_load_fonts() {
		$body_font = urldecode( boldthemes_get_option( 'body_font' ) );
		$heading_font = urldecode( boldthemes_get_option( 'heading_font' ) );
		$menu_font = urldecode( boldthemes_get_option( 'menu_font' ) );
		$heading_subtitle_font = urldecode( boldthemes_get_option( 'heading_subtitle_font' ) );
		$heading_supertitle_font = urldecode( boldthemes_get_option( 'heading_supertitle_font' ) );
		
		$font_families = array();
		
		if ( $body_font != '' ) {
			if ( $body_font == 'no_change' ) {
				$body_font = BoldThemes_Customize_Default::$data['body_font'];
			}
			$font_families[] = $body_font . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
		} else {
			/*
			Translators: If there are characters in your language that are not supported
			by chosen font(s), translate this to 'off'. Do not translate into your own language.
			 */
			if ( 'off' !== _x( 'on', 'Lato font: on or off', 'bello' ) ) {
				$font_families[] = 'Lato' . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
			}
		}
		
		if ( $heading_font != '' ) {
			if ( $heading_font == 'no_change' ) {
				$heading_font = BoldThemes_Customize_Default::$data['heading_font'];
			}
			$font_families[] = $heading_font . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
		} else {
			/*
			Translators: If there are characters in your language that are not supported
			by chosen font(s), translate this to 'off'. Do not translate into your own language.
			 */
			if ( 'off' !== _x( 'on', 'Lato font: on or off', 'bello' ) ) {
				$font_families[] = 'Lato' . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
			}
		}
		
		if ( $menu_font != '' ) {
			if ( $menu_font == 'no_change' ) {
				$menu_font = BoldThemes_Customize_Default::$data['menu_font'];
			}
			$font_families[] = $menu_font . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
		} else {
			/*
			Translators: If there are characters in your language that are not supported
			by chosen font(s), translate this to 'off'. Do not translate into your own language.
			 */
			if ( 'off' !== _x( 'on', 'Lato font: on or off', 'bello' ) ) {
				$font_families[] = 'Lato' . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
			}
		}

		if ( $heading_subtitle_font != '' ) {
			if ( $heading_subtitle_font == 'no_change' ) {
				$heading_subtitle_font = BoldThemes_Customize_Default::$data['heading_subtitle_font'];
			}
			$font_families[] = $heading_subtitle_font . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
		} else {
			/*
			Translators: If there are characters in your language that are not supported
			by chosen font(s), translate this to 'off'. Do not translate into your own language.
			 */
			if ( 'off' !== _x( 'on', 'Lato font: on or off', 'bello' ) ) {
				$font_families[] = 'Lato' . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
			}
		}

		if ( $heading_supertitle_font != '' ) {
			if ( $heading_supertitle_font == 'no_change' ) {
				$heading_supertitle_font = BoldThemes_Customize_Default::$data['heading_supertitle_font'];
			}
			$font_families[] = $heading_supertitle_font . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
		} else {
			/*
			Translators: If there are characters in your language that are not supported
			by chosen font(s), translate this to 'off'. Do not translate into your own language.
			 */
			if ( 'off' !== _x( 'on', 'Lato font: on or off', 'bello' ) ) {
				$font_families[] = 'Lato' . ':100,200,300,400,500,600,700,800,900,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic';
			}
		}

		if ( count( $font_families ) > 0 ) {
			$query_args = array(
				'family' => urlencode( implode( '|', $font_families ) ),
				'subset' => urlencode( 'latin,latin-ext' ),
			);
			$font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
			wp_enqueue_style( 'bello-fonts', $font_url, array(), '1.0.0' );
		}
	}
}

if ( ! function_exists( 'bello_load_admin_style' ) ) {
	function bello_load_admin_style() {
		if ( function_exists( 'boldthemes_csscrush_file' ) ) {
			boldthemes_csscrush_file( get_theme_file_path( 'admin-style.crush.css' ), array( 'source_map' => true, 'minify' => false, 'output_file' => 'admin-style', 'formatter' => 'block', 'boilerplate' => false, 'vars' => BoldThemesFramework::$crush_vars, 'plugins' => array( 'loop', 'ease' ) ) );
		}		
		wp_enqueue_style( 'bello-admin-style', get_template_directory_uri() . '/admin-style.css' );
		require_once( get_template_directory() . '/admin-style.php' );
		wp_add_inline_style( 'bello-admin-style', $admin_style );
	}
}

/**
 * Add class to body
 *
 * @return string 
 */
if ( ! function_exists( 'bello_body_class' ) ) {
	function bello_body_class( $extra_class ) {
		if ( boldthemes_get_option( 'heading_style' ) ) {
			$extra_class[] =  'btHeadingStyle_' . boldthemes_get_option( 'heading_style' );
		}
		return $extra_class;
	}
}

if ( ! function_exists( 'bello_get_image_id' ) ) {
    function bello_get_image_id($image_url) {
            global $wpdb;
            $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
            return isset($attachment[0]) ? $attachment[0] : 0; 
    }
}

if ( ! function_exists( 'bello_get_listing_default_image' ) ) {
    function bello_get_listing_default_image($image_size = 'boldthemes_medium_square'){
        $listing_default_image   = boldthemes_get_option( 'listing_default_image' ) != '' ? boldthemes_get_option( 'listing_default_image' )
                                : BoldThemes_Customize_Default::$data['listing_default_image'];                        
        $listing_default_image_id = bello_get_image_id($listing_default_image);
        $img_default = wp_get_attachment_image_src( $listing_default_image_id, $image_size );
        return  isset($img_default[0]) ? $img_default[0] : $img_default;
    }
}

if ( ! function_exists( 'bt_map_is_google' ) ) {
    function bt_map_is_google() {
        if ( function_exists( 'boldthemes_get_option' ) ) {
            $listing_search_map_type = boldthemes_get_option( 'listing_search_map_type' ) != '' ? boldthemes_get_option( 'listing_search_map_type' ) : 'google';
            if ( $listing_search_map_type != 'google' ){
                return 0;
            }
        }
        return 1;
    }
}

if ( ! function_exists( 'bt_map_is_osm' ) ) {
    function bt_map_is_osm() {
        if ( function_exists( 'boldthemes_get_option' ) ) {
            $listing_search_map_type = boldthemes_get_option( 'listing_search_map_type' ) != '' ? boldthemes_get_option( 'listing_search_map_type' ) : 'google';
            if ( $listing_search_map_type == 'osm' ){
                return 1;
            }
        }
        return 0;
    }
}
if ( ! function_exists( 'bt_map_is_leaflet' ) ) {
    function bt_map_is_leaflet() {
        if ( function_exists( 'boldthemes_get_option' ) ) {
            $listing_search_map_type = boldthemes_get_option( 'listing_search_map_type' ) != '' ? boldthemes_get_option( 'listing_search_map_type' ) : 'google';
            if ( $listing_search_map_type == 'leaflet' ){
                return 1;
            }
        }
        return 0;
    }
}
if ( ! function_exists( 'bt_is_autocomplete' ) ) {
    function bt_is_autocomplete() {
        if ( function_exists( 'boldthemes_get_option' ) ) {
            $show_location_autocomplete  = boldthemes_get_option( 'listing_search_autocomplete' ) != '' ? boldthemes_get_option( 'listing_search_autocomplete' ) : false;
            if ( $show_location_autocomplete ){
                return 1;
            }  
        }
        return 0;
    }
}

require_once( get_template_directory() . '/php/before_framework.php' );

require_once( get_template_directory() . '/framework/framework.php' );

require_once( get_template_directory() . '/php/config.php' );

require_once( get_template_directory() . '/php/after_framework.php' );

require_once( get_template_directory() . '/amp/amp.php' );
