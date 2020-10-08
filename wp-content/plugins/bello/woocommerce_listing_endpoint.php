<?php

//https://github.com/woocommerce/woocommerce/wiki/Customising-account-page-tabs

//https://docs.woocommerce.com/documentation/plugins/woocommerce/woocommerce-extensions/woocommerce-subscriptions/developer-docs/

/**
 * Register new endpoint to use inside My Account page.
 *
 * @see https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
 */
function bello_custom_endpoints() {
        $account_listing_endpoint = bt_account_listing_endpoint();
	add_rewrite_endpoint( $account_listing_endpoint, EP_ROOT | EP_PAGES );
}
add_action( 'init', 'bello_custom_endpoints' );

/**
 * Add new query var.
 *
 * @param array $vars
 * @return array
 */
function bello_custom_query_vars( $vars ) {
         $account_listing_endpoint = bt_account_listing_endpoint();
	$vars[] = $account_listing_endpoint;
	return $vars;
}
add_filter( 'query_vars', 'bello_custom_query_vars', 0 );

/**
 * Flush rewrite rules on plugin activation.
 */
function bello_custom_flush_rewrite_rules() {
         $account_listing_endpoint = bt_account_listing_endpoint();
	add_rewrite_endpoint( $account_listing_endpoint, EP_ROOT | EP_PAGES );
	flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'bello_custom_flush_rewrite_rules' );
register_deactivation_hook( __FILE__, 'bello_custom_flush_rewrite_rules' );

/**
 * Insert the new endpoint into the My Account menu.
 *
 * @param array $items
 * @return array
 */
function bello_custom_my_account_menu_items( $items ) {
         $account_listing_endpoint = bt_account_listing_endpoint();
     
	// Remove the logout menu item.
	$logout = $items['customer-logout'];
	unset( $items['customer-logout'] );

	// Insert your custom endpoint.
	$items[$account_listing_endpoint] = __( 'My Listings', 'bt_plugin' );

	// Insert back the logout item.
	$items['customer-logout'] = $logout;

	return $items;
}
add_filter( 'woocommerce_account_menu_items', 'bello_custom_my_account_menu_items' );

/**
 * Check user listing.
 */
function bello_check_user_listing( $user_id, $listing_id ) {
	 $args = array(
		'include'     => $listing_id,
		'post_type'   => 'listing',
		'author'      => $user_id,
		'post_status' => 'any'
	);
	$posts_array = get_posts( $args );
	if ( is_array( $posts_array ) && count( $posts_array ) == 1 ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Select package.
 */
function bello_select_package( $listing_id = false ) {

	$saved_package = '';
	if ( $listing_id !== false ) {
		$saved_custom_fields = get_post_custom( $listing_id );
		if ( isset( $saved_custom_fields['boldthemes_theme_listing-bello-listing-package'] ) ) {
			$saved_package = $saved_custom_fields['boldthemes_theme_listing-bello-listing-package'][0];
		}
	}

	$user_subscr = wcs_get_users_subscriptions( get_current_user_id() );

	$user_packages = array();

	foreach ( $user_subscr as $subscr ) {

		if ( $subscr->get_status() != 'active' ) {
			continue;
		}

		$subscr_id = $subscr->get_order_number(); // WC_Subscription extends WC_Order

		foreach ( $subscr->get_items() as $item_id => $item_data ) {

			// Get an instance of corresponding the WC_Product object
			$product = $item_data->get_product();
			
			$product_slug = $product->get_slug();
			$product_name = $product->get_name();

			//$user_packages[ $product_slug ] = array( 'name' => $product_name, 'quantity' => $item_quantity );
			$user_packages[] = array( 'name' => $product_name . ' #' . $subscr_id, 'slug' => $product_slug . '#' . $subscr_id );
			
		}
	}

	$used_packages = bello_get_meta_values( array( 'meta_key' => 'boldthemes_theme_listing-bello-listing-package', 'exclude' => array( $listing_id ) ) );

	echo '<h3>' . __( 'Select package', 'bt_plugin' ) . '</h3>';
	echo '<div class="package_selector"><div class="package_selector_choose"><select id="bello_select_package">';
		echo '<option value="bello-default-package">' . __( 'Default', 'bt_plugin' ) . '</option>';
		foreach ( $user_packages as $package ) {
			if ( ! in_array( $package['slug'], $used_packages ) ) {
				echo '<option value="' . $package['slug'] . '"' . ( $saved_package == $package['slug'] ? ' ' . 'selected' : '' ) . '>' . $package['name'] . '</option>';
			}
		}
	echo '</select></div>';
	$package_url = '#';
	if ( boldthemes_get_option( 'listing_packages_page_slug' ) ) {
		$package_url = boldthemes_get_permalink_by_slug( boldthemes_get_option( 'listing_packages_page_slug' ) );
	}
	echo '<div class="bt_get_new_package"><a href="' . $package_url . '" target="_blank" class="button">' . __( 'Get new package', 'bt_plugin' ) . '</a></div></div>';
}

/**
 * Only one package per order.
 */
add_action( 'woocommerce_add_to_cart_validation','bello_add_to_cart_validation', 10, 3 );
function bello_add_to_cart_validation( $true, $product_id, $quantity ) {

	$cart_items = WC()->cart->get_cart();

	if ( count( $cart_items ) == 0 ) {
		return true;
	}

	$package_in_cart = false;
	foreach ( $cart_items as $item ) {
		$terms = wp_get_post_terms( $item['product_id'], 'product_cat' );
		foreach ( $terms as $term ) {
			$cat_slug = $term->slug;
			if ( $cat_slug == 'listing-package' ) {
				$package_in_cart = true;
				break 2;
			}
		}
	}

	$terms = wp_get_post_terms( $product_id, 'product_cat' );
	foreach ( $terms as $term ) {
		$cat_slug = $term->slug;
		break;
	}
	
	if ( $cat_slug == 'listing-package' || $package_in_cart ) {
		wc_add_notice( __( 'You can only add one listing package per order. ', 'bt_plugin' ), 'error' );
		return false;
	}

	return true;
}

/**
 * Remove listing-package category from product categories widget.
 */
add_filter( 'woocommerce_product_categories_widget_args', 'bello_product_cat_filter' );
function bello_product_cat_filter( $list_args ) {
	if ( class_exists( 'WC_Product_Cat_List_Walker' ) ) {
		class Bello_WC_Product_Cat_List_Walker extends WC_Product_Cat_List_Walker {

			public function start_el( &$output, $cat, $depth = 0, $args = array(), $current_object_id = 0 ) {
				if ( $cat->slug == 'listing-package' ) {
					return;
				}
				$output .= '<li class="cat-item cat-item-' . $cat->term_id;

				if ( $args['current_category'] == $cat->term_id ) {
					$output .= ' current-cat';
				}

				if ( $args['has_children'] && $args['hierarchical'] ) {
					$output .= ' cat-parent';
				}

				if ( $args['current_category_ancestors'] && $args['current_category'] && in_array( $cat->term_id, $args['current_category_ancestors'] ) ) {
					$output .= ' current-cat-parent';
				}

				$output .= '"><a href="' . get_term_link( (int) $cat->term_id, $this->tree_type ) . '">' . _x( $cat->name, 'product category name', 'woocommerce' ) . '</a>';

				if ( $args['show_count'] ) {
					$output .= ' <span class="count">(' . $cat->count . ')</span>';
				}
			}

			public function end_el( &$output, $cat, $depth = 0, $args = array() ) {
				if ( $cat->slug == 'listing-package' ) {
					return;
				}
				$output .= "</li>\n";
			}

		}
	}
	$list_args['walker'] = new Bello_WC_Product_Cat_List_Walker;
	return $list_args;
}

function bello_get_meta_values( $a ) {

	$meta_key = $a['meta_key'];
	$post_type = isset( $a['post_type'] ) ? $a['post_type'] : 'listing';
	$exclude = isset( $a['exclude'] ) ? $a['exclude'] : array();

    $posts = get_posts(
        array(
            'post_type' => $post_type,
            'meta_key' => $meta_key,
            'posts_per_page' => -1,
			'post_status' => 'any',
			'exclude' => $exclude
        )
    );

    $meta_values = array();
    foreach( $posts as $post ) {
        $meta_values[] = get_post_meta( $post->ID, $meta_key, true );
    }

    return $meta_values;
}


/**
 * Endpoint HTML content.
 */
function bello_endpoint_content() {

	$author_id = get_current_user_id();
    $user = wp_get_current_user();
    $role = ( array ) $user->roles;

    if(in_array('customer',$role)) {
        return '';
    }
	if ( isset( $_GET['listing_id'] ) && isset( $_GET['cat'] ) ) { // EDIT
		
		$listing_id = $_GET['listing_id'];
		
		if ( bello_check_user_listing( $author_id, $listing_id ) ) {

			if ( ! isset( $_GET['rwmb-form-submitted'] ) ) {
				bello_select_package( $listing_id );
				echo '<h3>' . __( 'Edit listing', 'bt_plugin' ) . '</h3>';
			}
                        
			echo do_shortcode( '[mb_frontend_form id="listing_cf" post_fields="title,content,excerpt,thumbnail" '
                                . 'label_title="'       . __( 'Title', 'bt_plugin' )        .'" '
                                . 'label_content="'     . __( 'Content', 'bt_plugin' )      .'" '
                                . 'label_excerpt="'     . __( 'Excerpt', 'bt_plugin' )      .'" '
                                . 'label_thumbnail="'   . __( 'Thumbnail', 'bt_plugin' )    .'" '
                                . 'post_id="' . $listing_id . '" submit_button="' . __( 'Submit', 'bt_plugin' ) . '"]' );
		}

	} else if ( isset( $_GET['cat'] ) ) { // NEW

		$cat_fields = bello_get_listing_category_fields( $_GET['cat'] );
                
		foreach ( $cat_fields as $cat_cf ) {
			if ( isset( $cat_cf['cf_settings'] ) ) {
				foreach ( $cat_cf['cf_settings'] as $k => $cf ) {
                                     
					if ( $cf['mandatory'] ) {
                                           
						add_filter( 'rwmb_boldthemes_theme_listing-' . $k . '_outer_html', function( $html, $field, $meta ) {
							$html = str_replace( '"rwmb-field', '"rwmb-field required', $html );
							return $html;
						}, 10, 3 );
                                                
					}

				}
			}
		} 
                
		if ( boldthemes_get_option( 'listing_required_title' ) ) {
			add_filter( 'rwmb_frontend_post_title', function( $field ) {
				$field['required'] = true;
				return $field;
			} );
		}
                                                                        
		if ( boldthemes_get_option( 'listing_required_content' ) ) {
			add_filter( 'rwmb_frontend_post_content', function( $field ) {
				$field['required'] = true;
				return $field;
			} );
		}
		if ( boldthemes_get_option( 'listing_required_excerpt' ) ) {
			add_filter( 'rwmb_frontend_post_excerpt', function( $field ) {
				$field['required'] = true;
				return $field;
			} );
		}
		if ( boldthemes_get_option( 'listing_required_thumbnail' ) ) {
			add_filter( 'rwmb_frontend_post_thumbnail', function( $field ) {
				$field['required'] = true;
				return $field;
			} );
		}
	
		if ( ! isset( $_GET['rwmb-form-submitted'] ) ) {
			bello_select_package();
			echo '<h3>' . __( 'Edit listing', 'bt_plugin' ) . '</h3>';
		}
                

		echo do_shortcode( '[mb_frontend_form id="listing_cf" post_fields="title,content,excerpt,thumbnail" '
                        . 'label_title="'       . __( 'Title', 'bt_plugin' )        .'" '
                        . 'label_content="'     . __( 'Content', 'bt_plugin' )      .'" '
                        . 'label_excerpt="'     . __( 'Excerpt', 'bt_plugin' )      .'" '
                        . 'label_thumbnail="'   . __( 'Thumbnail', 'bt_plugin' )    .'" '
                        . 'post_status="pending" submit_button="' . __( 'Submit', 'bt_plugin' ) . '"]' );
		
	} else { // START

		if ( isset( $_GET['listing_id'] ) && isset( $_GET['delete'] ) && $_GET['delete'] == 'delete' ) { // DELETE
			$listing_id = $_GET['listing_id'];
			if ( bello_check_user_listing( $author_id, $listing_id ) ) {
				wp_delete_post( $listing_id );
			}

		}

		$args = array(
			'author' => $author_id,
			'post_type' => 'listing',
			'post_status' => 'any',
			'posts_per_page' => -1
		);

		$author_posts = new WP_Query( $args );

		echo '<form class="bt_select_category">';
			echo '<h3>' . __( 'Add new listing', 'bt_plugin' ) . '</h3>';
			echo '<div class="bt_select_category_form"><div class="bt_select_category_select">' . __( 'Please select category:', 'bt_plugin' ) . '';
			$root = get_term_by( 'slug', '_listing_root', 'listing-category' );
			if ( $root ) {
				$args = array(
					'hide_empty'   => false,
					'hierarchical' => true,
					'taxonomy'     => 'listing-category',
					'orderby'      => 'name',
					'id'            => 'listing_category',
					'exclude'      => $root->term_id
				);
			} else {
				$args = array(
					'hide_empty'   => false,
					'hierarchical' => true,
					'taxonomy'     => 'listing-category',
					'orderby'      => 'name',
					'id'            => 'listing_category',
					'exclude'      => $root_cat_id
				);
			}
			wp_dropdown_categories( $args );
			echo '</div><div class="bt_select_category_submit"><input type="submit" value="' . __( 'Submit', 'bt_plugin' ) . '"></div></div>';
		echo '</form>';

		if ( $author_posts->have_posts() ) {
			global $wp;
			echo '<h3>' . __( 'Your listings', 'bt_plugin' ) . '</h3>';
			echo '<ul class="bt_my_listings_list">';
				while ( $author_posts->have_posts() ) {
					$author_posts->the_post();

					$listing_id = get_the_ID();
					$status = get_post_status( $listing_id );
					
					$terms = wp_get_post_terms( $listing_id, 'listing-category', array( 'orderby' => 'ID' ) );

					$term_id = isset($terms[0]) ? $terms[0]->term_id : 0;

					foreach ( $terms as $term ) {
						if ( $term->parent != 0 ) {
							$term_id = $term->term_id;
						}
					}
			
					$url = remove_query_arg( array( 'listing_id', 'cat', 'delete' ) );

					$preview_url = get_permalink( $listing_id );
					$edit_url = add_query_arg( array( 'listing_id' => $listing_id, 'cat' => $term_id ), $url );
					$delete_url = add_query_arg( array( 'listing_id' => $listing_id, 'delete' => 'delete' ), $url );
	
					$title = get_the_title();

					$thumb = get_the_post_thumbnail( $listing_id, 'thumbnail' );
					$excerpt = get_the_excerpt( $listing_id );

					$subscr_url = false;
					$subscr_id = false;
					$subscr_link = '';

					$listing_arr = bello_get_listing_package( $listing_id );

					if ( $listing_arr['subscription'] !== false ) {
						$subscription = $listing_arr['subscription'];
						$subscr_link = ' ' . '<span class="bt_single_package">' . $listing_arr['saved_name'] . ' ' . '<a href="' . $subscription->get_view_order_url() . '" target="_blank">#' . $subscription->get_order_number() . '</a>' . ' - <span class="bt_listing_package_status">' . wcs_get_subscription_status_name( $subscription->get_status() ) . '</span></span>';
						//$subscr_link = ' ' . '(' . __( 'Subscription', 'bt_plugin' ) . '<a href="' . $subscription->get_view_order_url() . '" target="_blank"> #' . $subscription->get_order_number() . '</a>' . ' - ' . wcs_get_subscription_status_name( $subscription->get_status() ) . ')';
					}

					echo '<li>';
						echo '<div class="bt_single_listing_thumb"><a href="' . $edit_url . '">' . $thumb . '</a></div>';
						echo '<div class="bt_single_list_data"><div class="bt_single_listing_heading"><span class="bt_single_listing_title">' . $title . $subscr_link . '</span>';
						if ( $status == 'publish' ) {
							echo '<a class="bt_listing_preview" href="' . $preview_url . '" target="_blank">' . __( 'Preview', 'bt_plugin' ) . '</a>';
						} else {
							echo '<i class="bt_pending_approval">' . __( 'Pending approval', 'bt_plugin' ) . '</i>';
						}
						echo '<a class="bt_listing_edit" href="' . $edit_url . '">' . __( 'Edit', 'bt_plugin' ) . '</a>';
						echo '<a class="bt_listing_delete" href="' . $delete_url . '" class="bello_delete_listing" onclick="return confirm( \'' . __( 'Are you sure you want to delete ' . $title . '?', 'bt_plugin' ) . '\' )">' . __( 'Delete', 'bt_plugin' ) . '</a>';
						echo '</div>';

						echo '<span class="bt_single_listing_excerpt">' . $excerpt . '</span>';
						echo '</div>';
					echo '</li>';

				}
			echo '</ul>';
			wp_reset_postdata();
		}
	}
}

$account_listing_endpoint = bt_account_listing_endpoint();
add_action( 'woocommerce_account_' . $account_listing_endpoint . '_endpoint', 'bello_endpoint_content' );