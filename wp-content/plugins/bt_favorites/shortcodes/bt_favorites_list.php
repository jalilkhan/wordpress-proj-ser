<?php
// [bt_favorites_list site_id="1" class="bt_bb_favs" style="background-color: red;"]
function bt_favorites_list_action( $atts ){
    $shortcode_atts = shortcode_atts( array(
        'site_id'   => '0',
        'class'     => '',
        'style'     => '',
    ), $atts );
    
    $options    = get_option( 'bt_favorites_settings' );
    $class      = array('bt_favorites_list');
    $style_attr = '';
    
    $site_id = bt_get_site_id($shortcode_atts["site_id"]);
    $user_id = bt_get_user_id();
    
        
    $bt_favorites_div_class = '';
    if (  $options && array_key_exists( 'bt_favorites_div_class', $options ) && $options[ 'bt_favorites_div_class' ] != '') {
        $bt_favorites_div_class = $options[ 'bt_favorites_div_class' ];
    }
    $class[] = $shortcode_atts["class"] != '' ? $shortcode_atts["class"] : $bt_favorites_div_class;
    
    $bt_favorites_div_style = '';
    if (  $options && array_key_exists( 'bt_favorites_div_style', $options ) && $options[ 'bt_favorites_div_style' ] != '') {
        $bt_favorites_div_style = $options[ 'bt_favorites_div_style' ];
    }
    $style_attr = $shortcode_atts["style"] != '' ? ' style="' . $shortcode_atts["style"] . '"' : ' style="' . $bt_favorites_div_style . '"';    
   
    if ( $user_id ){
        $favorites = bt_get_meta_favorites( $user_id, $site_id );
    }else{
        $favorites = bt_get_cookie_favorites( $site_id );
    }
    
    $output = '';
    if ( $favorites ){
        $output .= bt_favorites_list_html($favorites);
        $output .= bt_favorites_clear_html($user_id, $site_id);
        $output .= bt_favorites_list_empty_html(1);
    }else{
        $output .= bt_favorites_list_empty_html(0);
    }
    
    $class = apply_filters( 'bt_favorites_list_class', $class, $atts );
    
    $output = '<div class="' . implode( ' ', $class ) . '"' .  $style_attr . '>' . $output . '</div>';
    
    $output = apply_filters( 'bt_bb_general_output', $output, $atts );
    $output = apply_filters( 'bt_favorites_list_output', $output, $atts );
    
    return $output;
}
add_shortcode('bt_favorites_list', 'bt_favorites_list_action');

