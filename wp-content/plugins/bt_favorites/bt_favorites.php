<?php
/*
Plugin Name: BT Favorites
Description: Simple and flexible favorite buttons for any post type.
Version: 1.0.0
Author: BoldThemes
Author URI: http://bold-themes.com
Text Domain: bt_favorites
Domain Path: /languages/
*/

register_activation_hook( __FILE__, 'bt_favorites_check_versions' );

function bt_favorites_check_versions( $wp = '3.9', $php = '5.3.2' ) {
    global $wp_version;
    if ( version_compare( PHP_VERSION, $php, '<' ) ) $flag = 'PHP';
    elseif ( version_compare( $wp_version, $wp, '<' ) ) $flag = 'WordPress';
    else return;
    $version = 'PHP' == $flag ? $php : $wp;
    
    if (function_exists('deactivate_plugins')){
        deactivate_plugins( basename( __FILE__ ) );
    }
    
    wp_die('<p>The <strong>BT Favorites</strong> plugin requires'.$flag.'  version '.$version.' or greater.</p>','Plugin Activation Error',  array( 'response'=>200, 'back_link'=>TRUE ) );
}

bt_favorites_check_versions();

function bt_favorites_enqueue_scripts() {

	if ( wp_style_is( 'bold_favorites_plugin_css', 'enqueued' ) ) {
		wp_dequeue_style( 'bold_favorites_plugin_css' );
	}
    
    wp_enqueue_style( 
            'bt_favorites_plugin_css', 
            plugin_dir_url( __FILE__ ) . 'assets/css/bt_favorites.css', 
            array(), 
            false, 
            'screen' 
    );
    
    $options = get_option( 'bt_favorites_settings' );
    
    $bt_favorites_simple_button_text = '';
    if (  $options && array_key_exists( 'bt_favorites_simple_button_text', $options ) && $options[ 'bt_favorites_simple_button_text' ] != '') {
        $bt_favorites_simple_button_text = $options[ 'bt_favorites_simple_button_text' ];
    }

    $bt_favorites_simple_button_text_added = '';
    if (  $options && array_key_exists( 'bt_favorites_simple_button_text_added', $options ) && $options[ 'bt_favorites_simple_button_text_added' ] != '') {
        $bt_favorites_simple_button_text_added = $options[ 'bt_favorites_simple_button_text_added' ];
    }
    
    $bt_favorites_button_text = '';
    if (  $options && array_key_exists( 'bt_favorites_button_text', $options ) && $options[ 'bt_favorites_button_text' ] != '') {
        $bt_favorites_button_text = $options[ 'bt_favorites_button_text' ];
    }
    $bt_favorites_button_text_added = '';
    if (  $options && array_key_exists( 'bt_favorites_button_text_added', $options ) && $options[ 'bt_favorites_button_text_added' ] != '') {
        $bt_favorites_button_text_added = $options[ 'bt_favorites_button_text_added' ];
    }
    $bt_favorites_no_favorites_text = '';
    if (  $options && array_key_exists( 'bt_favorites_no_favorites_text', $options ) && $options[ 'bt_favorites_no_favorites_text' ] != '') {
        $bt_favorites_no_favorites_text = $options[ 'bt_favorites_no_favorites_text' ];
    }
   
    wp_register_script( 'bt_favorites_plugin_js', plugin_dir_url( __FILE__ ) . 'assets/js/bt_favorites.js' );
    wp_localize_script( 'bt_favorites_plugin_js', 'ajax_object_favorites', array(
            'ajax_url'      => admin_url( 'admin-ajax.php' ),
            'text_add'      => esc_html( $bt_favorites_simple_button_text, 'bt_favorites' ),
            'text_added'    => esc_html( $bt_favorites_simple_button_text_added, 'bt_favorites' ),
            'text_add_sh'   => esc_html( $bt_favorites_button_text, 'bt_favorites' ),
            'text_added_sh' => esc_html( $bt_favorites_button_text_added, 'bt_favorites' )
            )
    );
    wp_enqueue_script( 'bt_favorites_plugin_js' );
    
    wp_register_script( 'bt_favorites_list_js', plugin_dir_url( __FILE__ ) . 'assets/js/bt_favorites_list.js' );
    wp_localize_script( 'bt_favorites_list_js', 'ajax_object_favorites_list', array(
            'ajax_url'      => admin_url( 'admin-ajax.php' ),
             'text_empty'   => esc_html( $bt_favorites_no_favorites_text, 'bt_favorites' )
            )
    );
    wp_enqueue_script( 'bt_favorites_list_js' );
}
add_action( 'wp_enqueue_scripts', 'bt_favorites_enqueue_scripts' );


require_once(__DIR__ . '/config.php');

require_once(__DIR__ . '/app/favorites.php');
require_once(__DIR__ . '/app/ajax.php');
require_once(__DIR__ . '/app/helpers.php');

require_once(__DIR__ . '/shortcodes/bt_settings.php');
require_once(__DIR__ . '/shortcodes/bt_helpers.php');
require_once(__DIR__ . '/shortcodes/bt_favorites_list.php');
require_once(__DIR__ . '/shortcodes/bt_favorites_button.php');