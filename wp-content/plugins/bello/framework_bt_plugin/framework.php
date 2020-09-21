<?php

add_filter( 'register_post_type_args', 'boldthemes_update_portfolio_slug', 10, 2 );
add_action( 'init', 'boldthemes_portfolio_category_slug', 11 );

add_filter( 'wp_get_attachment_image_src', 'boldthemes_fix_wp_get_attachment_image_svg', 10, 4 );

// CUSTOM JS
if ( ! function_exists( 'boldthemes_customize_custom_js' ) ) {
	function boldthemes_customize_custom_js( $wp_customize ) {
		if ( ! class_exists( 'BoldThemes_Customize_Default' ) ) {
			return;
		}
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[custom_js]', array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_custom_js'
		));
		$wp_customize->add_control( new BoldThemes_Customize_Textarea_Control( 
			$wp_customize, 
			'custom_js', array(
				'label'    => esc_html__( 'Custom JS', 'bt_plugin' ),
				'section'  => BoldThemesFramework::$pfx . '_general_section',
				'priority' => 120,
				'settings' => BoldThemesFramework::$pfx . '_theme_options[custom_js]'
			)
		));
	}
}
add_action( 'customize_register', 'boldthemes_customize_custom_js', 20 );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_custom_js', 20 );

/* BLOG */

// SHARE ON FACEBOOK
if ( ! function_exists( 'boldthemes_customize_blog_share_facebook' ) ) {
	function boldthemes_customize_blog_share_facebook( $wp_customize ) {
		if ( ! class_exists( 'BoldThemes_Customize_Default' ) ) {
			return;
		}
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[blog_share_facebook]', array(
			'default'           => BoldThemes_Customize_Default::$data['blog_share_facebook'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_checkbox'
		));
		$wp_customize->add_control( 'blog_share_facebook', array(
			'label'    => esc_html__( 'Share on Facebook', 'bt_plugin' ),
			'section'  => BoldThemesFramework::$pfx . '_blog_section',
			'settings' => BoldThemesFramework::$pfx . '_theme_options[blog_share_facebook]',
			'priority' => 18,
			'type'     => 'checkbox'
		));
	}
}
add_action( 'customize_register', 'boldthemes_customize_blog_share_facebook' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_blog_share_facebook' );
		
// SHARE ON TWITTER
if ( ! function_exists( 'boldthemes_customize_blog_share_twitter' ) ) {
	function boldthemes_customize_blog_share_twitter( $wp_customize ) {
		if ( ! class_exists( 'BoldThemes_Customize_Default' ) ) {
			return;
		}
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[blog_share_twitter]', array(
			'default'           => BoldThemes_Customize_Default::$data['blog_share_twitter'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_checkbox'
		));
		$wp_customize->add_control( 'blog_share_twitter', array(
			'label'    => esc_html__( 'Share on Twitter', 'bt_plugin' ),
			'section'  => BoldThemesFramework::$pfx . '_blog_section',
			'settings' => BoldThemesFramework::$pfx . '_theme_options[blog_share_twitter]',
			'priority' => 20,
			'type'     => 'checkbox'
		));
	}
}

add_action( 'customize_register', 'boldthemes_customize_blog_share_twitter' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_blog_share_twitter' );

// SHARE ON LINKEDIN
if ( ! function_exists( 'boldthemes_customize_blog_share_linkedin' ) ) {
	function boldthemes_customize_blog_share_linkedin( $wp_customize ) {
		if ( ! class_exists( 'BoldThemes_Customize_Default' ) ) {
			return;
		}
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[blog_share_linkedin]', array(
			'default'           => BoldThemes_Customize_Default::$data['blog_share_linkedin'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_checkbox'
		));
		$wp_customize->add_control( 'blog_share_linkedin', array(
			'label'    => esc_html__( 'Share on LinkedIn', 'bt_plugin' ),
			'section'  => BoldThemesFramework::$pfx . '_blog_section',
			'settings' => BoldThemesFramework::$pfx . '_theme_options[blog_share_linkedin]',
			'priority' => 40,
			'type'     => 'checkbox'
		));
	}
}
add_action( 'customize_register', 'boldthemes_customize_blog_share_linkedin' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_blog_share_linkedin' );
		
// SHARE ON VK
if ( ! function_exists( 'boldthemes_customize_blog_share_vk' ) ) {
	function boldthemes_customize_blog_share_vk( $wp_customize ) {
		if ( ! class_exists( 'BoldThemes_Customize_Default' ) ) {
			return;
		}
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[blog_share_vk]', array(
			'default'           => BoldThemes_Customize_Default::$data['blog_share_vk'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_checkbox'
		));
		$wp_customize->add_control( 'blog_share_vk', array(
			'label'    => esc_html__( 'Share on VK', 'bt_plugin' ),
			'section'  => BoldThemesFramework::$pfx . '_blog_section',
			'settings' => BoldThemesFramework::$pfx . '_theme_options[blog_share_vk]',
			'priority' => 50,
			'type'     => 'checkbox'
		));
	}
}
add_action( 'customize_register', 'boldthemes_customize_blog_share_vk' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_blog_share_vk' );
		
// SHARE ON WHATSAPP
if ( ! function_exists( 'boldthemes_customize_blog_share_whatsapp' ) ) {
	function boldthemes_customize_blog_share_whatsapp( $wp_customize ) {
		if ( ! class_exists( 'BoldThemes_Customize_Default' ) ) {
			return;
		}
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[blog_share_whatsapp]', array(
			'default'           => BoldThemes_Customize_Default::$data['blog_share_whatsapp'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_checkbox'
		));
		$wp_customize->add_control( 'blog_share_whatsapp', array(
			'label'    => esc_html__( 'Share on WhatsApp', 'bt_plugin' ),
			'section'  => BoldThemesFramework::$pfx . '_blog_section',
			'settings' => BoldThemesFramework::$pfx . '_theme_options[blog_share_whatsapp]',
			'priority' => 50,
			'type'     => 'checkbox'
		));
	}
}
add_action( 'customize_register', 'boldthemes_customize_blog_share_whatsapp' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_blog_share_whatsapp' );

/* PORTFOLIO */

// SHARE ON FACEBOOK
if ( ! function_exists( 'boldthemes_customize_pf_share_facebook' ) ) {
	function boldthemes_customize_pf_share_facebook( $wp_customize ) {
		if ( ! class_exists( 'BoldThemes_Customize_Default' ) ) {
			return;
		}
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[pf_share_facebook]', array(
			'default'           => BoldThemes_Customize_Default::$data['pf_share_facebook'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_checkbox'
		));
		$wp_customize->add_control( 'pf_share_facebook', array(
			'label'    => esc_html__( 'Share on Facebook', 'bt_plugin' ),
			'section'  => BoldThemesFramework::$pfx . '_pf_section',
			'settings' => BoldThemesFramework::$pfx . '_theme_options[pf_share_facebook]',
			'priority' => 10,
			'type'     => 'checkbox'
		));
	}
}
add_action( 'customize_register', 'boldthemes_customize_pf_share_facebook' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_pf_share_facebook' );
	
// SHARE ON TWITTER
if ( ! function_exists( 'boldthemes_customize_pf_share_twitter' ) ) {
	function boldthemes_customize_pf_share_twitter( $wp_customize ) {
		if ( ! class_exists( 'BoldThemes_Customize_Default' ) ) {
			return;
		}
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[pf_share_twitter]', array(
			'default'           => BoldThemes_Customize_Default::$data['pf_share_twitter'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_checkbox'
		));
		$wp_customize->add_control( 'pf_share_twitter', array(
			'label'    => esc_html__( 'Share on Twitter', 'bt_plugin' ),
			'section'  => BoldThemesFramework::$pfx . '_pf_section',
			'settings' => BoldThemesFramework::$pfx . '_theme_options[pf_share_twitter]',
			'priority' => 20,
			'type'     => 'checkbox'
		));
	}
}
add_action( 'customize_register', 'boldthemes_customize_pf_share_twitter' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_pf_share_twitter' );


// SHARE ON LINKEDIN
if ( ! function_exists( 'boldthemes_customize_pf_share_linkedin' ) ) {
	function boldthemes_customize_pf_share_linkedin( $wp_customize ) {
		if ( ! class_exists( 'BoldThemes_Customize_Default' ) ) {
			return;
		}
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[pf_share_linkedin]', array(
			'default'           => BoldThemes_Customize_Default::$data['pf_share_linkedin'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_checkbox'
		));
		$wp_customize->add_control( 'pf_share_linkedin', array(
			'label'    => esc_html__( 'Share on LinkedIn', 'bt_plugin' ),
			'section'  => BoldThemesFramework::$pfx . '_pf_section',
			'settings' => BoldThemesFramework::$pfx . '_theme_options[pf_share_linkedin]',
			'priority' => 40,
			'type'     => 'checkbox'
		));
	}
}
add_action( 'customize_register', 'boldthemes_customize_pf_share_linkedin' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_pf_share_linkedin' );

// SHARE ON VK
if ( ! function_exists( 'boldthemes_customize_pf_share_vk' ) ) {
	function boldthemes_customize_pf_share_vk( $wp_customize ) {
		if ( ! class_exists( 'BoldThemes_Customize_Default' ) ) {
			return;
		}
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[pf_share_vk]', array(
			'default'           => BoldThemes_Customize_Default::$data['pf_share_vk'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_checkbox'
		));
		$wp_customize->add_control( 'pf_share_vk', array(
			'label'    => esc_html__( 'Share on VK', 'bt_plugin' ),
			'section'  => BoldThemesFramework::$pfx . '_pf_section',
			'settings' => BoldThemesFramework::$pfx . '_theme_options[pf_share_vk]',
			'priority' => 50,
			'type'     => 'checkbox'
		));
	}
}
add_action( 'customize_register', 'boldthemes_customize_pf_share_vk' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_pf_share_vk' );
		
// SHARE ON WHATSAPP
if ( ! function_exists( 'boldthemes_customize_pf_share_whatsapp' ) ) {
	function boldthemes_customize_pf_share_whatsapp( $wp_customize ) {
		if ( ! class_exists( 'BoldThemes_Customize_Default' ) ) {
			return;
		}
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[pf_share_whatsapp]', array(
			'default'           => BoldThemes_Customize_Default::$data['pf_share_whatsapp'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_checkbox'
		));
		$wp_customize->add_control( 'pf_share_whatsapp', array(
			'label'    => esc_html__( 'Share on WhatsApp', 'bt_plugin' ),
			'section'  => BoldThemesFramework::$pfx . '_pf_section',
			'settings' => BoldThemesFramework::$pfx . '_theme_options[pf_share_whatsapp]',
			'priority' => 50,
			'type'     => 'checkbox'
		));
	}
}
add_action( 'customize_register', 'boldthemes_customize_pf_share_whatsapp' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_pf_share_whatsapp' );

/* SHOP */

// SHARE ON FACEBOOK
if ( ! function_exists( 'boldthemes_customize_shop_share_facebook' ) ) {
	function boldthemes_customize_shop_share_facebook( $wp_customize ) {
		if ( ! class_exists( 'BoldThemes_Customize_Default' ) ) {
			return;
		}
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[shop_share_facebook]', array(
			'default'           => BoldThemes_Customize_Default::$data['shop_share_facebook'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_checkbox'
		));
		$wp_customize->add_control( 'shop_share_facebook', array(
			'label'    => esc_html__( 'Share on Facebook', 'bt_plugin' ),
			'section'  => BoldThemesFramework::$pfx . '_shop_section',
			'settings' => BoldThemesFramework::$pfx . '_theme_options[shop_share_facebook]',
			'priority' => 10,
			'type'     => 'checkbox'
		));
	}
}
add_action( 'customize_register', 'boldthemes_customize_shop_share_facebook' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_shop_share_facebook' );

// SHARE ON TWITTER
if ( ! function_exists( 'boldthemes_customize_shop_share_twitter' ) ) {
	function boldthemes_customize_shop_share_twitter( $wp_customize ) {
		if ( ! class_exists( 'BoldThemes_Customize_Default' ) ) {
			return;
		}
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[shop_share_twitter]', array(
			'default'           => BoldThemes_Customize_Default::$data['shop_share_twitter'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_checkbox'
		));
		$wp_customize->add_control( 'shop_share_twitter', array(
			'label'    => esc_html__( 'Share on Twitter', 'bt_plugin' ),
			'section'  => BoldThemesFramework::$pfx . '_shop_section',
			'settings' => BoldThemesFramework::$pfx . '_theme_options[shop_share_twitter]',
			'priority' => 20,
			'type'     => 'checkbox'
		));
	}
}
add_action( 'customize_register', 'boldthemes_customize_shop_share_twitter' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_shop_share_twitter' );

// SHARE ON LINKEDIN
if ( ! function_exists( 'boldthemes_customize_shop_share_linkedin' ) ) {
	function boldthemes_customize_shop_share_linkedin( $wp_customize ) {
		if ( ! class_exists( 'BoldThemes_Customize_Default' ) ) {
			return;
		}
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[shop_share_linkedin]', array(
			'default'           => BoldThemes_Customize_Default::$data['shop_share_linkedin'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_checkbox'
		));
		$wp_customize->add_control( 'shop_share_linkedin', array(
			'label'    => esc_html__( 'Share on LinkedIn', 'bt_plugin' ),
			'section'  => BoldThemesFramework::$pfx . '_shop_section',
			'settings' => BoldThemesFramework::$pfx . '_theme_options[shop_share_linkedin]',
			'priority' => 40,
			'type'     => 'checkbox'
		));
	}
}
add_action( 'customize_register', 'boldthemes_customize_shop_share_linkedin' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_shop_share_linkedin' );

// SHARE ON VK
if ( ! function_exists( 'boldthemes_customize_shop_share_vk' ) ) {
	function boldthemes_customize_shop_share_vk( $wp_customize ) {
		if ( ! class_exists( 'BoldThemes_Customize_Default' ) ) {
			return;
		}
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[shop_share_vk]', array(
			'default'           => BoldThemes_Customize_Default::$data['shop_share_vk'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_checkbox'
		));
		$wp_customize->add_control( 'shop_share_vk', array(
			'label'    => esc_html__( 'Share on VK', 'bt_plugin' ),
			'section'  => BoldThemesFramework::$pfx . '_shop_section',
			'settings' => BoldThemesFramework::$pfx . '_theme_options[shop_share_vk]',
			'priority' => 50,
			'type'     => 'checkbox'
		));	
	}
}
add_action( 'customize_register', 'boldthemes_customize_shop_share_vk' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_shop_share_vk' );

// SHARE ON WHATSAPP
if ( ! function_exists( 'boldthemes_customize_shop_share_whatsapp' ) ) {
	function boldthemes_customize_shop_share_whatsapp( $wp_customize ) {
		if ( ! class_exists( 'BoldThemes_Customize_Default' ) ) {
			return;
		}
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[shop_share_whatsapp]', array(
			'default'           => BoldThemes_Customize_Default::$data['shop_share_whatsapp'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_checkbox'
		));
		$wp_customize->add_control( 'shop_share_whatsapp', array(
			'label'    => esc_html__( 'Share on WhatsApp', 'bt_plugin' ),
			'section'  => BoldThemesFramework::$pfx . '_shop_section',
			'settings' => BoldThemesFramework::$pfx . '_theme_options[shop_share_whatsapp]',
			'priority' => 50,
			'type'     => 'checkbox'
		));	
	}
}
add_action( 'customize_register', 'boldthemes_customize_shop_share_whatsapp' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_shop_share_whatsapp' );

/**
 * Returns share icons HTML
 *
 * @return string
 */
if ( ! function_exists( 'boldthemes_get_share_html2' ) ) {
	function boldthemes_get_share_html2( $permalink, $type = 'blog', $size = 'small', $style = 'filled', $shape = 'circle', $color_scheme = '' ) {
		
		$share_facebook = boldthemes_get_option( $type . '_share_facebook' );
		$share_twitter = boldthemes_get_option( $type . '_share_twitter' );
		$share_linkedin = boldthemes_get_option( $type . '_share_linkedin' );
		$share_vk = boldthemes_get_option( $type . '_share_vk' );
		$share_whatsapp = boldthemes_get_option( $type . '_share_whatsapp' );

		$share_html = '';
		if ( $share_facebook || $share_twitter || $share_linkedin || $share_vk || $share_whatsapp ) {

			if ( $share_facebook ) {
				$share_html .= boldthemes_get_icon_html( 
					array ( 
						'icon' => 'fa_f09a',
						'url' => boldthemes_get_share_link( 'facebook', $permalink ), 
						'url_title' => esc_html__( 'Share on Facebook', 'bt_plugin' ), 
						'style' => $style,
						'shape' => $shape,
						'size' => $size,
						'color_scheme' => $color_scheme, 
						'el_class' => 'btIcoFacebook'
					)
				);
			}

			if ( $share_twitter ) {
				$share_html .= boldthemes_get_icon_html( 
					array ( 
						'icon' => 'fa_f099', 
						'url' => boldthemes_get_share_link( 'twitter', $permalink ), 
						'url_title' => esc_html__( 'Share on Twitter', 'bt_plugin' ), 
						'style' => $style,
						'shape' => $shape,
						'size' => $size,
						'color_scheme' => $color_scheme, 
						'el_class' => 'btIcoTwitter' 
					)
				);
			}
			
			if ( $share_linkedin ) {
				$share_html .= boldthemes_get_icon_html( 
					array ( 
						'icon' => 'fa_f0e1', 
						'url' => boldthemes_get_share_link( 'linkedin', $permalink ), 
						'url_title' => esc_html__( 'Share on Linkedin', 'bt_plugin' ), 
						'style' => $style,
						'shape' => $shape,
						'size' => $size, 
						'color_scheme' => $color_scheme,
						'el_class' => 'btIcoLinkedin' 
					)
				);
			}
			
			
			if ( $share_vk ) {
				$share_html .= boldthemes_get_icon_html( 
					array ( 
						'icon' => 'fa_f189', 
						'url' => boldthemes_get_share_link( 'vk', $permalink ), 
						'url_title' => esc_html__( 'Share on VK', 'bt_plugin' ), 
						'style' => $style,
						'shape' => $shape,
						'size' => $size,  
						'color_scheme' => $color_scheme,
						'el_class' => 'btIcoVK' 
					)
				);
			}
			
			
			if ( $share_whatsapp ) {
				$share_html .= boldthemes_get_icon_html( 
					array ( 
						'icon' => 'fa_f232', 
						'url' => boldthemes_get_share_link( 'whatsapp', $permalink ), 
						'url_title' => esc_html__( 'Share on WhatsApp', 'bt_plugin' ), 
						'style' => $style,
						'shape' => $shape,
						'size' => $size,  
						'color_scheme' => $color_scheme,
						'el_class' => 'btIcoWhatsApp' 
					)
				);
			}
		}
		
		return $share_html;
	}
}

/**
 * Change portfolio slug
 *
 * @return array
 */

function boldthemes_update_portfolio_slug( $args, $post_type ) {
	if ( function_exists( 'boldthemes_get_option' ) ) {
		if ( 'portfolio' === $post_type && boldthemes_get_option( 'pf_slug' ) != '' ) {
			$new_args = array(
				'rewrite' => array( 'slug' => boldthemes_get_option( 'pf_slug' ) )
			);
			return array_merge( $args, $new_args );
		}
	}
	return $args;
}

function boldthemes_portfolio_category_slug() {
	if ( function_exists( 'boldthemes_get_option' ) ) {
		if ( boldthemes_get_option ( 'pf_category_slug' ) != '' ) {
			$portfolio_category_args = get_taxonomy( 'portfolio_category' ); // returns an object
			if ( isset( $portfolio_category_args->rewrite['slug'] )) {
				$portfolio_category_args->rewrite['slug'] = boldthemes_get_option( 'pf_category_slug' );
			}
			register_taxonomy( 'portfolio_category', 'portfolio', (array) $portfolio_category_args );
		}
	}
}
// hook it up to 11 so that it overrides the original register_taxonomy function


// helper for decode

function boldthemes_decode( $code ) {
    return base64_decode( $code );
}

//helper for curl data
function boldthemes_get_curl($args) {
	$retValue	=  array();
	$curl_url	=  isset($args['curl_url']) && $args['curl_url'] != '' ? $args['curl_url'] : '';
	$curl_data	=  isset($args['curl_data']) && !empty($args['curl_data']) ? $args['curl_data'] : array();

	if ( $curl_url != '' ) {
		$session = curl_init($curl_url);
		curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
		$json = curl_exec($session);
		if ( $json === false ) {
			$retValue = $curl_data;
		}else{
			$retValue = json_decode( $json, true );
		}
		curl_close($session);		
	}

	return $retValue;
}

/*
 * Fix svg attachment dimensions
 */
function boldthemes_fix_wp_get_attachment_image_svg($image, $attachment_id, $size, $icon) {
        if ( is_array($image) && preg_match('/\.svg$/i', $image[0]) && $image[1] <= 1 ) {
                if ( !file_exists( $image[0] ) ) {
                    return '';
                }                
                $image[1] = $image[2] = null;                
                if ( is_array($size) ) {
                        $image[1] = $size[0];
                        $image[2] = $size[1];
                } else { 
                        $xml = '';
                        $allow_url = ini_get('allow_url_fopen');
                        if ( $allow_url ){                            
                            $xml = simplexml_load_file($image[0]);                           
                        } else {
                            $args['curl_url'] = $image[0];
                            $xml = boldthemes_get_curl($args);
                            $xml = simplexml_load_string($xml);
                        }
                        if ( $xml ) {
                            $attr = $xml->attributes();
                            $viewbox = explode(' ', $attr->viewBox);
                            $image[1] = isset($attr->width) && preg_match('/\d+/', $attr->width, $value) ? (int) $value[0] : (count($viewbox) == 4 ? (int) $viewbox[2] : null);
                            $image[2] = isset($attr->height) && preg_match('/\d+/', $attr->height, $value) ? (int) $value[0] : (count($viewbox) == 4 ? (int) $viewbox[3] : null);
                        }
                }
        }
        return $image;
}

// Remove styles one by one

add_action( 'wp_enqueue_scripts', 'boldthemes_remove_woo_scripts', 100 );

function boldthemes_remove_woo_scripts() {
	if ( class_exists( 'woocommerce' ) ) {
		wp_dequeue_style( 'select2' );
		wp_deregister_style( 'select2' );
		wp_dequeue_script( 'select2' );
		wp_deregister_script( 'select2' );
		wp_dequeue_style( 'selectWoo' );
		wp_deregister_style( 'selectWoo' );
		wp_dequeue_script( 'selectWoo' );
		wp_deregister_script( 'selectWoo' );
	}
}