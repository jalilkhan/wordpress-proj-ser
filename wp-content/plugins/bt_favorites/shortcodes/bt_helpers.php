<?php
function bt_get_meta_favorites( $user_id, $site_id ){
    $favorites = get_user_meta( $user_id,  BT_FAVORITES_META_NAME );    
    if ( empty($favorites) )  return array();
    $favorites =  json_decode(stripslashes($favorites[0]), true);
    $favorites = $favorites[0];
    if ( $favorites['siteid'] == $site_id ){
        return $favorites;
    } 
    return array();
}

function bt_get_cookie_favorites( $site_id ){
     if ( isset($_COOKIE[BT_FAVORITES_COOKIE_NAME]) ) {
        $favorites =  json_decode(stripslashes($_COOKIE[BT_FAVORITES_COOKIE_NAME]), true);
        if ( empty($favorites) )  return array();
        $favorites = $favorites[0];
        if ( $favorites['siteid'] == $site_id ){
            return $favorites;
        }        
    }
    
    return array();
}

function bt_get_favorites_post_types() {
    $options = get_option( 'bt_favorites_settings' );
    if (  $options && array_key_exists( 'bt_favorites_post_types', $options ) && $options[ 'bt_favorites_post_types' ] != '') {
         $favorites_post_types = explode("," , $options[ 'bt_favorites_post_types'] );
         return $favorites_post_types;
    }
    
    return array();
}

function bt_get_favorites_show() {
    $options = get_option( 'bt_favorites_settings' );
    if (  $options && array_key_exists( 'bt_favorites_show', $options ) && $options[ 'bt_favorites_show' ] != '') {
         $favorites_show = explode("," , $options[ 'bt_favorites_show'] );
         return $favorites_show;
    }
    
    return array();
}

function bt_favorites_list_html($favorites = null){
    $html = '';
    if ( $favorites ){
        $posts = explode("," , $favorites['posts']);
        if ( count($posts) > 0 ){
            $html .= '<div id="bt_favorites_list">';
                $html .= '<ul>';
                foreach ($posts as $post_id) {
                    $content_post = get_post($post_id);
                    $title = get_post_field('post_title', $post_id);
                    $link  = get_permalink( $post_id );
                    $html .= '<li><a href="' . esc_url($link) . '">' . $title . '</a></li>';
                }
                $html .= '</ul>';
            $html .= '</div>';
        }
    } 
    
    return $html;
}

function bt_favorites_list_empty_html($empty = 1){
    $options = get_option( 'bt_favorites_settings' );
    $bt_favorites_no_favorites_text = '';
    if (  $options && array_key_exists( 'bt_favorites_no_favorites_text', $options ) && $options[ 'bt_favorites_no_favorites_text' ] != '') {
        $bt_favorites_no_favorites_text = $options[ 'bt_favorites_no_favorites_text' ];
    }
                                                                    
    $html = '';
    $button_text = $empty == 1 ? '' : $bt_favorites_no_favorites_text;
    $html = "<div class='bt_favorites_empty'>" . esc_html( $button_text, 'bt_favorites' ) . "</div>";
    return $html;
}

function bt_favorites_clear_html( $user_id, $site_id){
    $options = get_option( 'bt_favorites_settings' );
    $bt_favorites_clear_button_text = '';
    if (  $options && array_key_exists( 'bt_favorites_clear_button_text', $options ) && $options[ 'bt_favorites_clear_button_text' ] != '') {
        $bt_favorites_clear_button_text = $options[ 'bt_favorites_clear_button_text' ];
    }
    $button_text = $bt_favorites_clear_button_text == '' ? '' : $bt_favorites_clear_button_text;
    $html = '';    
    $html .= '<p>';
    $html .= '<a href="#" id="bt_favorites_clear" class="bt_favorites_clear" title="' . esc_html( $button_text, 'bt_favorites' ) . '" data-userid="'.$user_id.'" data-siteid="'.$site_id.'">'
            . esc_html( $button_text, 'bt_favorites' )
            . '</a>';
    $html .= '</p>';
    
    return $html;
}

function bt_favorites_button_html( $button_text, $favourite_class ){
    $html = '';    
    $html .= '<a href="#" class="' . $favourite_class . ' bt_bb_listing_marker_add_favourite" title="' . esc_html( $button_text, 'bt_favorites' ) . '">'
           . '<span>' . esc_html( $button_text, 'bt_favorites' ) . '</span>'
           . '</a>';
    
    return $html;
}

function bt_favorites_custom_css(){
    $options = get_option( 'bt_favorites_settings' );
    $bt_favorites_custom_css = '';
    if (  $options && array_key_exists( 'bt_favorites_custom_css', $options ) && $options[ 'bt_favorites_custom_css' ] != '') {
        $bt_favorites_custom_css = sanitize_text_field($options[ 'bt_favorites_custom_css' ]);
        wp_enqueue_style(
            'bt-favorites-custom-style',
             plugin_dir_url( __FILE__ )  . '/assets/css/bt_favprites_custom.css'
        );
        wp_add_inline_style( 'bt-favorites-custom-style', $bt_favorites_custom_css );
    }   
}

function bt_get_favorites_excluded_pages() {
    $options = get_option( 'bt_favorites_settings' );
    $bt_favorites_exclude_pages = '';
    if (  $options && array_key_exists( 'bt_favorites_exclude_pages', $options ) && $options[ 'bt_favorites_exclude_pages' ] != '') {
        $bt_favorites_exclude_pages = $options[ 'bt_favorites_exclude_pages' ];
    }   return $bt_favorites_exclude_pages != '' ? explode( "," , $bt_favorites_exclude_pages) : array();
    
    return array();
}

function bt_display_favorites_button( $post, $page, $page_count){     
    $favorites_post_types_arr       = bt_get_favorites_post_types();
    
    if ( in_array( $post->post_type, $favorites_post_types_arr ) ) {
        if ( ($post->post_type == 'page') ){   
            $bt_favorites_exclude_page_ids = bt_get_favorites_excluded_pages();  
            if ( $bt_favorites_exclude_page_ids ){
                foreach ( $bt_favorites_exclude_page_ids as $bt_favorites_exclude_page_id ){
                    if ( $post->ID == $bt_favorites_exclude_page_id ) {
                         return false;
                    }
                }
            }
        }
        
        if ( ($post->post_type == 'product') ){   
            if ( is_shop() ) {
                return false;
            }
            if ( $page_count > 1 ){
                return false;
            }
        }
        if ( ($post->post_type == 'attachment') ){  
            
        }
        
        if ( ($post->post_type == 'listing') ){  
            
        }
        
        
        if ( $page > 1 ){
            return false;
        }
        return true;
    }
    return false;
   
}
