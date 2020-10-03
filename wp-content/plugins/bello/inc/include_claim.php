<?php
// CLAIM custom post type

add_action( 'init', 'bt_create_claim' );
if ( ! function_exists( 'bt_create_claim' ) ) {
    function bt_create_claim() {
            $labels = array(
		'name' =>  __('Claims', 'post type general name', 'bt_plugin'),
		'singular_name' =>  __('Claim', 'post type singular name', 'bt_plugin'),
                'menu_name' => _x('Claims', 'admin menu', 'bt_plugin'),
                'name_admin_bar' => _x('Claim', 'add new on admin bar', 'bt_plugin'),
                'add_new' => _x('Add New', 'review', 'bt_plugin'),
                'add_new_item' => __('Add New Claim', 'bt_plugin'),
                'new_item' => __('New Claim', 'bt_plugin'),
                'edit_item' => __('Edit Claim', 'bt_plugin'),
                'view_item' => __('View Claim', 'bt_plugin'),
                'all_items' => __('All Claims', 'bt_plugin'),
                'search_items' => __('Search Claims', 'bt_plugin'),
                'parent_item_colon' => __('Parent Claims:', 'bt_plugin'),
                'not_found' => __('No reviews found.', 'bt_plugin'),
                'not_found_in_trash' => __('No reviews found in Trash.', 'bt_plugin')
           );

           $args = array(
               'labels'         => $labels,
               'menu_icon'      => 'dashicons-testimonial',
               'public'         => true,
               'has_archive'    => false,
               'menu_position'  => 5,
               'supports'       => array( 'title' ),
               'rewrite'        => array( 'with_front' => false, 'slug' => 'claim' ),
			   'publicly_queryable'  => false,
			   'exclude_from_search' => true,
           );

           register_post_type( 'claim', $args );
    }
}

/*  Claim custom post type: columns in table view  */
add_filter('manage_claim_posts_columns', 'bt_set_claim_columns');
if (!function_exists('bt_set_claim_columns')) {
    function bt_set_claim_columns($columns)
    {
        unset( $columns['author'] );
        $columns['cb']  = '<input type="checkbox" />';
        $columns['claimed_listing']  = __( 'Listing', 'bt_plugin' );
        $columns['owner']  = __( 'Author', 'bt_plugin' );
        $columns['claimer']  = __( 'Claimed by', 'bt_plugin' );
        $columns['claim_status']  = __( 'Status', 'bt_plugin' );
        $columns['date']    = __( 'Date', 'bt_plugin' );       
        return $columns;
    }    
}

/*   Claim custom post type: content for custom columns */
add_action( 'manage_claim_posts_custom_column' , 'bt_custom_claim_column', 10, 2 );
function bt_custom_claim_column( $column, $post_id ) {   
    switch ( $column ) {
        case 'claim_status' :
            $metabox = get_post_meta($post_id);
            if ( isset($metabox['claim_status']) && is_array($metabox['claim_status']) && count($metabox['claim_status']) > 0 ){
                echo strtoupper($metabox['claim_status'][0]);
            }else{
               _e( 'Unable to get claim status', 'bt_plugin' );
            }
            break;
         case 'claimed_listing' :
            $metabox = get_post_meta($post_id);
            if ( isset($metabox['claimed_listing']) && is_array($metabox['claimed_listing']) && count($metabox['claimed_listing']) > 0 ){
                $claimed_listing = get_post($metabox['claimed_listing'][0]);
                echo "<a href='" . get_post_permalink($metabox['claimed_listing'][0]) . "'>" . $claimed_listing->post_title . "</a>";
            }else{
                _e( 'Unable to get listing', 'bt_plugin' );
            }
            break;
        case 'owner' :
            $metabox = get_post_meta($post_id);
            if ( isset($metabox['owner']) && is_array($metabox['owner']) && count($metabox['owner']) > 0 ){
                $usermeta    = get_user_by('login', $metabox['owner'][0]);
                if ($usermeta) {
                    $new_author_name  = $usermeta->user_login;
                    $new_author_email = $usermeta->user_email;
                    $output_claimer = "<a href='" . admin_url( sprintf( 'edit.php?post_type=listing&author==%d',$usermeta->ID ))  . "'>" . strtoupper($new_author_name) . "</a>";
                    if( $new_author_email != ''){
                        $output_claimer .=  "<br /><a href='mailto:" . $new_author_email . "'>" . $new_author_email . "</a>";
                    }
                }else{
                    $output_claimer = $metabox['owner'][0];
                }
                echo $output_claimer;
            }else{
                _e( 'Unable to get author', 'bt_plugin' );
            }
            break;
        case 'claimer' :
            $metabox = get_post_meta($post_id);
            if ( isset($metabox['claimer']) && is_array($metabox['claimer']) && count($metabox['claimer']) > 0 ){
                $usermeta         = get_user_by('id', $metabox['claimer'][0]);
                if ($usermeta) {
                    $new_author_name  = $usermeta->user_login;
                    $new_author_email = $usermeta->user_email;
                    $output_claimer = "<a href='" . admin_url( sprintf( 'edit.php?post_type=listing&author==%d',$usermeta->ID ))  . "'>" . strtoupper($new_author_name) . "</a>";
                    if( $new_author_email != ''){
                        $output_claimer .=  "<br /><a href='mailto:" . $new_author_email . "'>" . $new_author_email . "</a>";
                    }
                }else{
                    $output_claimer = $metabox['claimer'][0];
                }
                echo $output_claimer;
            }else{
                _e( 'Unable to get claimer', 'bt_plugin' );
            }
            break;
    }
}   

