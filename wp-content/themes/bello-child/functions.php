<?php
function my_theme_enqueue_styles() {

    $parent_style = 'bello-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

/**
 * Write session to disk to prevent cURL time-out which may occur with
 * WordPress (since 4.9.2, see 
),
 * or plugins such as "Health Check".
 */
function custom_wp_fix_pre_http_request($preempt, $r, $url)
{
    // CUSTOM_WP_FIX_DISABLE_SWC can be defined in wp-config.php (undocumented):
    if ( !defined('CUSTOM_WP_FIX_DISABLE_SWC ') && isset($_SESSION)) {
        if (function_exists('get_site_url')) {
            $parse = parse_url(get_site_url());
            $s_url = @$parse['scheme'] . "://{$parse['host']}";
            if (strpos($url, $s_url) === 0) {
                @session_write_close();
            }
        }
    }
 
    return false;
}
add_filter('pre_http_request', 'custom_wp_fix_pre_http_request', 10, 3);

add_filter( 'rwmb_meta_boxes', 'your_prefix_register_meta_boxes' );

function your_prefix_register_meta_boxes( $meta_boxes ) {
    $prefix = 'prefix-';

    $meta_boxes[] = [
        'title'      => esc_html__( 'Untitled', 'online-generator' ),
        'id'         => 'untitled',
        'post_types' => ['post'],
        'context'    => 'normal',
        'priority'   => 'high',
        'fields'     => [
            [
                'type'             => 'video',
                'id'               => $prefix . 'video_yxk1bg8s6ms',
                'name'             => esc_html__( 'Video', 'online-generator' ),
                'max_file_uploads' => 4,
                'max_status'       => true,
            ],
        ],
    ];

    return $meta_boxes;
}

function bello_child_custom_my_account_menu_items( $items ) {
    $user = wp_get_current_user();
    $role = ( array ) $user->roles;

    if(in_array('customer',$role)) {
        unset( $items['orders'] );
        unset( $items['subscriptions'] );
        unset( $items['downloads'] );
        unset( $items['edit-address'] );
        unset( $items['bello-listing-endpoint'] );
    }

    return $items;
}
add_filter( 'woocommerce_account_menu_items', 'bello_child_custom_my_account_menu_items' );

?>