<?php
// [bt_favorites_button site_id="1" post_id="2909" user_id="1" class="bt_bb_favs" style="background-color: red;"]
function bt_favorites_button_action( $atts ){
    $shortcode_atts = shortcode_atts( array(
        'site_id'   => '0',
        'post_id'   => '0',
        'user_id'   => '0',
        'class'     => '',
        'style'     => '',
    ), $atts );
    
    $options    = get_option( 'bt_favorites_settings' );
    $class      = array('bt_favorites_button');
    $style_attr = '';
    
    $post_id = bt_get_post_id($shortcode_atts["post_id"]);
    $site_id = bt_get_site_id($shortcode_atts["site_id"]);
    $user_id = bt_get_user_id($shortcode_atts["user_id"]);
        
    $bt_favorites_button_class = '';
    if (  $options && array_key_exists( 'bt_favorites_button_class', $options ) && $options[ 'bt_favorites_button_class' ] != '') {
        $bt_favorites_button_class = $options[ 'bt_favorites_button_class' ];
    }
    $class[] = $shortcode_atts["class"] != '' ? $shortcode_atts["class"] : $bt_favorites_button_class;
    
    $bt_favorites_button_style = '';
    if (  $options && array_key_exists( 'bt_favorites_button_style', $options ) && $options[ 'bt_favorites_button_style' ] != '') {
        $bt_favorites_button_style = $options[ 'bt_favorites_button_style' ];
    }
    $style_attr = $shortcode_atts["style"] != '' ? ' style="' . $shortcode_atts["style"] . '"' : ' style="' . $bt_favorites_button_style . '"';
   
    $bt_favorites_button_text = '';
    if (  $options && array_key_exists( 'bt_favorites_button_text', $options ) && $options[ 'bt_favorites_button_text' ] != '') {
        $bt_favorites_button_text = $options[ 'bt_favorites_button_text' ];
    }
    $bt_favorites_button_text_added = '';
    if (  $options && array_key_exists( 'bt_favorites_button_text_added', $options ) && $options[ 'bt_favorites_button_text_added' ] != '') {
        $bt_favorites_button_text_added = $options[ 'bt_favorites_button_text_added' ];
    }
    
    $is_favorite    = bt_is_favorite($post_id,$site_id, $user_id);
    
    $favourite_class = $is_favorite ? 'bt_bb_listing_favourite_on' : 'bt_bb_listing_favourite';
    $active          = $is_favorite ? ' active' : '';
    $button_text     = $is_favorite ? $bt_favorites_button_text_added : $bt_favorites_button_text;
    
    $class = apply_filters( 'bt_favorites_button_class', $class, $atts );
    
    $output = bt_favorites_button_html( $button_text, $favourite_class );
    
    $output = '<div class="' . implode( ' ', $class ) . ' bt-simplefavorite-button-sh' . $active  . '"' .  $style_attr . ' data-postid="' . $post_id .'" data-siteid="' . $site_id . '" data-userid="' . $user_id .'">' . $output . '</div>';

    return $output;    
    
}
add_shortcode('bt_favorites_button', 'bt_favorites_button_action');

function bt_favorites_button_the_content($content){ 
    global $post;
    global $page;
    global $page_count;
    
    $favorites_show = bt_get_favorites_show();
    
    if ( !$post ) return $content;
    if ( !$favorites_show ) return $content;
    
    $original_output    = $content;
    $output             = ''; 
    
    $display = bt_display_favorites_button($post, $page, $page_count);        
    if ($display){
        if ( !empty($favorites_show) ){                
            if ( in_array('before', $favorites_show) ){
                $output .= '<p>[bt_favorites_button]</p>';
            }            
            $output .= $content;
            
            if ( in_array('after', $favorites_show) ){
                $output .=  '<p>[bt_favorites_button]</p>';
            }
        }
        $page++;
        $page_count++;
        
        return $output;       
   }
   
   return $original_output;
}
add_filter('the_content', 'bt_favorites_button_the_content');
