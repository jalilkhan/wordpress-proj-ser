<?php

function bt_enqueue_favorites_scripts() {
    wp_enqueue_script( 
            'bello_listing_favourite_js',
            plugin_dir_url( __FILE__ ) . 'my-account/js/favourite.js',
            array( 'jquery' )
    );
}
add_action( 'wp_enqueue_scripts', 'bt_enqueue_favorites_scripts' );

/* listing favourites ajax */
add_action('wp_ajax_bt_favourites_action', 'bt_favourites_action_callback'); 
add_action('wp_ajax_nopriv_bt_favourites_action', 'bt_favourites_action_callback'); 

if ( ! function_exists( 'bt_favourites_action_callback' ) ) {
    function bt_favourites_action_callback(){
        $params = array(); 
        if (isset($_POST)){
               foreach($_POST as $field => $value) {               
                   if ( $value != '' && $value != null){
                       $params[$field] = $value;
                   }
               }
        }	 
        bt_custom_update_favourite( $params ); 
        die; 
    }
}

if ( ! function_exists( 'bt_custom_update_favourite' ) ) {
    function bt_custom_update_favourite( $params ){
        $post_id    = isset($params['postid']) ? $params['postid'] : 0; 
        $site_id    = isset($params['siteid']) ? $params['siteid'] : 0; 
        $favorited  = isset($params['favourited']) ? $params['favourited'] : 1; 
        $group_id   = isset($params['groupid']) ? $params['groupid'] : 0;

        $status = $favorited == 1 ? 'inactive' : 'active';
        $value  = $favorited == 1 ? 0 : 1;

        require_once plugins_dir() . '/favorites/favourites.php';
        require_once( get_template_directory() . '/php/before_framework_query.php' );
        $fav = new Favorite();
        $fav->update($post_id, $status, $site_id, $group_id);

        $is_favourited = boldthemes_is_favourited( $post_id, get_current_blog_id() ); 
        $bt_bb_listing_favourite_class = $is_favourited ? 'bt_bb_listing_favourite_on' : 'bt_bb_listing_favourite';    
        $html = '';    
        $html .= '<a href="#" class="' . esc_attr( $bt_bb_listing_favourite_class ) . ' simplefavorite-button2"
            data-postid="' . esc_attr( $post_id ) . '" 
            data-siteid="' . get_current_blog_id() . '" 
            data-groupid="1" 
            data-favoritecount="1"
            data-favourited="' . esc_attr( $value ) . '"
           >';
                if ( $is_favourited ) {
                    $html .=  esc_html__( 'Added to favourite', 'bt_plugin' );
                } else {
                   $html .=  esc_html__( 'Add to favourite', 'bt_plugin' ); 
                }
            $html .= '</a>';
        echo esc_html($html);
    }
}

/**
* Customize the Favorites Button HTML
*/
add_filter( 'favorites/button/html', 'boldthemes_custom_favorites_button_html', 10, 4 );
if ( ! function_exists( 'boldthemes_custom_favorites_button_html' ) ) {
    function boldthemes_custom_favorites_button_html($html, $post_id, $favorited, $site_id)  {     
            if ( function_exists( 'get_user_favorites' ) ) {
                    $bt_bb_listing_favourite_class = $favorited ? 'bt_bb_listing_favourite_on' : 'bt_bb_listing_favourite';	
                    return '<a href="#" class="' . esc_attr( $bt_bb_listing_favourite_class ) . ' bt_bb_listing_marker_add_favourite"><span>' . $html . '</span></a>';
            }else{
                    return $html;
            }

    }
}

if ( ! function_exists( 'boldthemes_is_favourited' ) ) {
    function boldthemes_is_favourited( $post_id, $site_id ) {
            if ( ! function_exists( 'get_user_favorites' ) ) {
                    return 0;
            }
            $favorite_post_ids = get_user_favorites( get_current_user_id(), get_current_blog_id());
            if ( in_array($post_id, $favorite_post_ids) ){
                    return 1;
            }
            return 0;
    }
}

  

