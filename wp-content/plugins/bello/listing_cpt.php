<?php

// LISTING custom post type and taxonomy
add_action( 'init', 'bt_create_listing' );
if ( ! function_exists( 'bt_create_listing' ) ) {
	
	function bt_create_listing() {
		
		register_post_type( 'listing',
			array(
				'labels' => array(
					'name'          => __( 'Listing', 'bt_plugin' ),
					'singular_name' => __( 'Listing Item', 'bt_plugin' )
				),
				'public'        => true,
				'has_archive'   => true,
				'menu_position' => 5,
				'supports'      => array( 'title', 'editor', 'revisions', 'thumbnail', 'author', 'comments', 'excerpt' ),
				'rewrite'       => array( 'with_front' => false, 'slug' => 'listing' )
			)
		);

		register_taxonomy( 
			'listing-category', 
			'listing', 
			array( 'hierarchical' => true, 
				'label' => __( 'Categories', 'bt_plugin' ), 
				'singular_name' => __( 'Category', 'bt_plugin' ), 
				'show_admin_column' => true
			) 
		);

		//register_taxonomy( 'listing-region', 'listing', array( 'rewrite' => false, 'hierarchical' => true, 'label' => __( 'Regions', 'bt_plugin' ), 'singular_name' => __( 'Region', 'bt_plugin' ), 'show_admin_column' => true ) );
		register_taxonomy( 'listing-region', 'listing', array( 'hierarchical' => true, 'label' => __( 'Regions', 'bt_plugin' ), 'singular_name' => __( 'Region', 'bt_plugin' ), 'show_admin_column' => true ) );

		register_taxonomy( 'listing-tag', 'listing', array( 'hierarchical' => false, 'label' => __( 'Tags', 'bt_plugin' ), 'singular_name' => __( 'Tag', 'bt_plugin' ) ) );

		if ( class_exists( 'RWMB_Core' ) ) {
			class BT_RWMB_Core extends RWMB_Core {
				public function init() {
					$this->register_meta_boxes();
				}
			}
		}
	}
}

// plugin activation
if ( ! function_exists( 'bt_rewrite_flush' ) ) {
	function bt_rewrite_flush() {
		// First, we "add" the custom post type via the above written function.
		// Note: "add" is written with quotes, as CPTs don't get added to the DB,
		// They are only referenced in the post_type column with a post entry, 
		// when you add a post of this CPT.
		bt_create_listing();

		// ATTENTION: This is *only* done during plugin activation hook in this example!
		// You should *NEVER EVER* do this on every page load!!
		flush_rewrite_rules();
	}
}
register_activation_hook( __FILE__, 'bt_rewrite_flush' );

// Add custom column / manage_{$post_type}_posts_columns
add_filter( 'manage_listing_posts_columns', 'bt_set_custom_listing_columns' );
function bt_set_custom_listing_columns( $columns ) {
    $columns['package'] = __( 'Package', 'bt_plugin' );
    return $columns;
}

// Add data to the custom column / manage_{$post_type}_posts_custom_column
add_action( 'manage_listing_posts_custom_column' , 'bt_custom_listing_column', 10, 2 );
function bt_custom_listing_column( $column, $post_id ) {
    switch ( $column ) {
        case 'package':
			$package = bello_get_listing_package( $post_id );
			if ( $package['saved_name'] ) {
				$subscription = $package['subscription'];
				$subscription_edit_link = get_edit_post_link( $subscription->get_id() );
                                $subscription_status_name = function_exists('wcs_get_subscription_status_name') ? ' - ' . wcs_get_subscription_status_name( $subscription->get_status() ) : '';
				echo $package['saved_name'] . '<a href="' . $subscription_edit_link . '" target="_blank"> #' . $subscription->get_id() . '</a>' . $subscription_status_name;
			}
            break;
    }
}