<?php
/**
* Simple Bold Favorite button function
* if ( function_exists( 'bt_simple_favorites_button' ) ) { 
*       bt_simple_favorites_button( $post_id, $site_id, $user_id )
* }
*/

function bt_simple_favorites_button($post_id = 0, $site_id = 0, $user_id = 0){   
   $post_id = bt_get_post_id($post_id);
   $site_id = bt_get_site_id($site_id);
   $user_id = bt_get_user_id($user_id);
   
   $options    = get_option( 'bt_favorites_settings' );   
   $class      = array('bt-simplefavorite-button');
   $style_attr = '';
   
   if (  $options && array_key_exists( 'bt_favorites_simple_button_class', $options ) && $options[ 'bt_favorites_simple_button_class' ] != '') {
        $class[] = $options[ 'bt_favorites_simple_button_class' ];
   }
   if (  $options && array_key_exists( 'bt_favorites_simple_button_style', $options ) && $options[ 'bt_favorites_simple_button_style' ] != '') {
        $style_attr = $options[ 'bt_favorites_simple_button_style' ];
    }
   
   $bt_favorites_button_text = '';
   if (  $options && array_key_exists( 'bt_favorites_simple_button_text', $options ) && $options[ 'bt_favorites_simple_button_text' ] != '') {
        $bt_favorites_button_text = $options[ 'bt_favorites_simple_button_text' ];
   }
   $bt_favorites_button_text_added = '';
   if (  $options && array_key_exists( 'bt_favorites_simple_button_text_added', $options ) && $options[ 'bt_favorites_simple_button_text_added' ] != '') {
        $bt_favorites_button_text_added = $options[ 'bt_favorites_simple_button_text_added' ];
   }
   
   $is_favorite = bt_is_favorite($post_id,$site_id, $user_id); 
   
   $favourite_class  = $is_favorite ? 'bt_bb_listing_favourite_on' : 'bt_bb_listing_favourite';
   $active           = $is_favorite ? ' active' : '';
   $button_text      = $is_favorite ? $bt_favorites_button_text_added : $bt_favorites_button_text;

   $output = bt_favorites_button_html( $button_text, $favourite_class );
   
   $output = '<div class="' . implode( ' ', $class ) . ' simplefavorite-button' . $active . '" data-postid="' . $post_id .'" data-siteid="' . $site_id . '" data-userid="' . $user_id .'" style="">' . $output . '</div>';

   return $output;    
}



