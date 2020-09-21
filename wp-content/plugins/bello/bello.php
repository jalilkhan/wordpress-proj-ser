<?php
/**
 * Plugin Name: Bello Plugin
 * Description: Shortcodes and widgets by BoldThemes.
 * Version: 1.5.1
 * Author: BoldThemes
 * Author URI: http://bold-themes.com
 * Text Domain: bt_plugin 
 */

require_once( 'framework_bt_plugin/framework.php' );

require_once( 'inc/claim-metaboxes.php' );
require_once( 'inc/listing-category-metaboxes.php' );
require_once( 'inc/comment-metaboxes.php' );
require_once( 'inc/include_helpers.php' );
require_once( 'inc/include_html.php' );
require_once( 'inc/include_popup.php' );
require_once( 'inc/include_query.php' );
require_once( 'inc/include_favorites.php' );
require_once( 'inc/include_claim.php' );
require_once( 'inc/include_ajax.php' );
require_once( 'inc/include_openmap.php' );
require_once( 'inc/include_leafletmap.php' );

require_once( 'shortcodes/bt_comment_imgs.php' );
require_once( 'listing_cpt.php' );

require_once( 'localization/localization.php' );

define( 'BELLO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'BELLO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

$bt_plugin_dir = plugin_dir_path( __FILE__ );

function bt_enqueue_scripts() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'bello-plugin', plugin_dir_url( __FILE__ ) . 'bello-plugin.js' );
    
    // custom modernizr js
    wp_enqueue_script( 'bello_modernizr_js', plugin_dir_url( __FILE__ ) . 'inc/js/modernizr-custom.js', array( 'jquery' ), '', false );
}
add_action( 'wp_enqueue_scripts', 'bt_enqueue_scripts' );

function bt_load_plugin_textdomain() {
	$domain = 'bt_plugin';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );        
	load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'bt_load_plugin_textdomain' );

function bt_widget_areas() {
	register_sidebar( array (
		'name' 		=> esc_html__( 'Header Left Widgets', 'bt_plugin' ),
		'id' 		=> 'header_left_widgets',
		'before_widget' => '<div class="btTopBox %2$s">', 
		'after_widget' 	=> '</div>'
	));
	register_sidebar( array (
		'name' 		=> esc_html__( 'Header Right Widgets', 'bt_plugin' ),
		'id' 		=> 'header_right_widgets',
		'before_widget' => '<div class="btTopBox %2$s">',
		'after_widget' 	=> '</div>'
	));
	register_sidebar( array (
		'name' 		=> esc_html__( 'Header Menu Widgets', 'bt_plugin' ),
		'id' 		=> 'header_menu_widgets',
		'before_widget' => '<div class="btTopBox %2$s">',
		'after_widget' 	=> '</div>'
	));
	register_sidebar( array (
		'name' 		=> esc_html__( 'Header Logo Widgets', 'bt_plugin' ),
		'id' 		=> 'header_logo_widgets',
		'before_widget' => '<div class="btTopBox %2$s">',
		'after_widget' 	=> '</div>'
	));
	register_sidebar( array (
		'name' 		=> esc_html__( 'Footer Widgets', 'bt_plugin' ),
		'id' 		=> 'footer_widgets',
		'before_widget' => '<div class="btBox %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h4><span>',
		'after_title' 	=> '</span></h4>'
	));
	register_sidebar( array (
		'name' 		=> esc_html__( 'Listing Banner', 'bt_plugin' ),
		'id' 		=> 'listing_banner',
		'before_widget' => '<div class="btSingleListingLargeBanner">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h4><span>',
		'after_title' 	=> '</span></h4>'
	));
	register_sidebar( array (
		'name' 		=> esc_html__( 'Listing With Map Banner', 'bt_plugin' ),
		'id' 		=> 'listing_with_map_banner',
		'before_widget' => '<div class="btSingleListingLargeBanner">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h4><span>',
		'after_title' 	=> '</span></h4>'
	));
	register_sidebar( array (
		'name' 		=> esc_html__( 'Listing Without Map Banner', 'bt_plugin' ),
		'id' 		=> 'listing_without_map_banner',
		'before_widget' => '<div class="btSingleListingLargeBanner">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h4><span>',
		'after_title' 	=> '</span></h4>'
	));
}
add_action( 'widgets_init', 'bt_widget_areas', 30 );

if ( ! function_exists( 'bt_is_listing_category' ) ) {
	function bt_is_listing_category( $term = '' ) {
		return is_tax( 'listing-category', $term );
	}
}

if ( ! function_exists( 'bt_is_listing_tag' ) ) {
	function bt_is_listing_tag( $term = '' ) {
		return is_tax( 'listing-tag', $term );
	}
}

if ( ! function_exists( 'bt_is_listing_region' ) ) {
	function bt_is_listing_region( $term = '' ) {
		return is_tax( 'listing-region', $term );
	}
}

/**
 * My Listing Endpoint settings
 * Endpoint is active on plugin or theme activation
 * After listing endpoint change, It is necessary to re-save permalinks via Settings > Permalinks so that the new listing endpoint is active
 */ 
if ( ! function_exists( 'bt_account_listing_endpoint' ) ) {
	function bt_account_listing_endpoint() {
             $boldthemes_options = get_option( 'boldthemes_theme_theme_options' );
             $listing_account_endpoint  = isset($boldthemes_options["listing_account_endpoint"]) ? $boldthemes_options["listing_account_endpoint"] : ''; 
             if ( $listing_account_endpoint ) {
                 return $listing_account_endpoint;
             }else{
                 return 'bello-listing-endpoint';
             }
	}
}

// edit custom fields definition in category
add_action( 'listing-category_edit_form_fields', 'bello_listing_category_taxonomy_custom_fields', 10, 2 );
function bello_listing_category_taxonomy_custom_fields( $tag ) {
	$t_id = $tag->term_id; 
	$term_meta = get_option( "taxonomy_term_$t_id" ); 
	?>
		<tr class="form-field">  
			<th scope="row">  
				<label for="listing_fields"><?php _e( 'Custom Fields', 'bt_plugin' ); ?></label>  
			</th>  
			<td>  
				<textarea name="term_meta[listing_fields]" id="term_meta_listing_fields" rows="20" cols="50" class="large-text"><?php echo isset( $term_meta['listing_fields'] ) ? stripcslashes( $term_meta['listing_fields'] ) : ''; ?></textarea>
				<p class="bt-admin-description bt-background-description bt-link-underline-bold"><span class="dashicons dashicons-editor-help"></span> <?php _e( 'List of custom fields for the category. Please define the fields with the help of our extensive documentation.<br><a href="http://documentation.bold-themes.com/bello/creating-pages-and-posts/#custom-fields" target="_blank">Find out more about defining a category</a><br><a href="http://documentation.bold-themes.com/bello/groups-fields-types/" target="_blank">Find out more about field types</a>', 'bt_plugin' ); ?></p>
			</td>  
		</tr>
	<?php
}

// get all packages
function bello_get_packages() {
	$args = array(
	'post_type'             => 'product',
	'post_status'           => 'publish',
	'posts_per_page'        => -1,
	'tax_query'             => array(
		array(
			'taxonomy'      => 'product_cat',
			'field'         => 'slug',
			'terms'         => array( 'listing-package' ),
			'operator'      => 'IN'
		)
	) );
	$packages = new WP_Query( $args );
	return $packages->posts;
}

// get listing package
function bello_get_listing_package( $listing_id = false ) {
	if ( $listing_id == false ) {
		$listing_id = get_the_ID();
	}
	$saved_custom_fields = get_post_custom( $listing_id );
	$saved_package = '';
	if ( isset(  $saved_custom_fields['boldthemes_theme_listing-bello-listing-package'] ) ) {
		$saved_package = $saved_custom_fields['boldthemes_theme_listing-bello-listing-package'][0];
	}

	$subscr_arr = explode( '#', $saved_package );

	$default_package = 'bello-default-package'; // default

	$current_package = $default_package;

	$subscription = false;
	$saved_package_name = false;

	if ( count( $subscr_arr ) == 2 ) {
                
		$saved_package = $subscr_arr[0];
		$subscription = function_exists( 'wcs_get_subscription' ) ? wcs_get_subscription( $subscr_arr[1] ) : false;
                
		if ( $subscription ) {
			if ( $subscription->get_status() == 'active' ) {
				$current_package = $saved_package;
			}
			foreach ( $subscription->get_items() as $item_id => $item_data ) {
				// Get an instance of corresponding the WC_Product object
				$product = $item_data->get_product();
				$saved_package_name = $product->get_name();
			}
		}
	}

	$saved_featured = false;
	if ( $saved_package != 'bello-default-package' ) {
		$product_obj = get_page_by_path( $saved_package, OBJECT, 'product' );
                
		if ( $product_obj ) {
			$saved_featured = get_post_meta( $product_obj->ID, '_featured_listing_package', true ) == 'yes' ? true : false;
		}
	}

	return array( 'saved' => $saved_package, 'current' => $current_package, 'saved_featured' => $saved_featured, 'saved_name' => $saved_package_name, 'subscription' => $subscription );
}

// featured listing package
add_action( 'woocommerce_product_options_general_product_data', 'bello_add_custom_general_fields' );
add_action( 'woocommerce_process_product_meta', 'bello_add_custom_general_fields_save' );
function bello_add_custom_general_fields() {
	global $woocommerce, $post;
	echo '<div class="options_group show_if_subscription">';
		woocommerce_wp_checkbox( 
			array( 
				'id'            => '_featured_listing_package',
				'wrapper_class' => 'show_if_subscription',
				'label'         => __( 'Featured listing package', 'woocommerce' ),
				'description'   => __( '', 'woocommerce' )
			)
		);
	echo '</div>';
}
function bello_add_custom_general_fields_save( $post_id ) {
	$checkbox = isset( $_POST['_featured_listing_package'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_featured_listing_package', $checkbox );
}

// custom fields extra options
add_action( 'listing-category_edit_form_fields', 'bello_listing_category_taxonomy_custom_fields_extra', 10, 2 );
function bello_listing_category_taxonomy_custom_fields_extra( $tag ) {
	$t_id = $tag->term_id;
	$term_meta = get_option( "taxonomy_term_$t_id" );
 
	if ( isset( $term_meta['listing_fields'] ) && isset( $term_meta['cf_settings'] ) && count( $term_meta['cf_settings'] ) > 0 ) {

		add_action( 'admin_footer', function() { ?>
			<script>
				jQuery( document ).ready( function() {
					jQuery( '.bello_cf_search' ).click(function() {
						if ( jQuery( this ).is( ':checked' ) ) {
							jQuery( this ).parent().next().find( 'input' ).attr( 'checked', false );
						}
					});
					jQuery( '.bello_cf_search_advanced' ).click(function() {
						if ( jQuery( this ).is( ':checked' ) ) {
							jQuery( this ).parent().prev().find( 'input' ).attr( 'checked', false );
						}
					}); 
				});
			</script>
		<?php } );

	?>

		<tr class="form-field bello_custom_fields_extra">
			<th scope="row">
				<label for="listing_fields"><?php _e( 'Custom Field Settings', 'bt_plugin' ); ?></label>
				<p class="description" style="font-weight: normal;"><?php _e( 'Set your custom field settings for this category, where will they show up, if they\'re required and in which package will the show up.<br><a href="http://documentation.bold-themes.com/bello/creating-pages-and-posts/#custom-field-settings" target="_blank"><b>Find out more</b></a>', 'bt_plugin' ); ?></p>
			</th>
			<td>
				<?php

					$arr = bello_get_listing_field_arr( $term_meta['listing_fields'], $t_id, true );
                                       

					echo '<table>';
						echo '<th>' . __( 'Custom Field Slug', 'bt_plugin' ) . '</th>';
						echo '<th>' . __( 'Search', 'bt_plugin' ) . '</th>';
						echo '<th>' . __( 'Advanced Search', 'bt_plugin' ) . '</th>';
						echo '<th>' . __( 'Mandatory', 'bt_plugin' ) . '</th>';
						echo '<th>' . __( 'Packages', 'bt_plugin' ) . '</th>';

						$packages = bello_get_packages();

						foreach( $arr as $item ) {                                                    
							echo '<tr>';
								echo '<td>' . $item['slug'] . '</td>';
								echo '<td><input type="checkbox" class="bello_cf_search" name="' . $item['slug'] . '_search"' . ( $term_meta['cf_settings'][ $item['slug'] ]['search'] ? ' ' . 'checked' : '' ) . '></td>';
								echo '<td><input type="checkbox" class="bello_cf_search_advanced" name="' . $item['slug'] . '_search_advanced"' . ( $term_meta['cf_settings'][ $item['slug'] ]['search_advanced'] ? ' ' . 'checked' : '' ) . '></td>';
								echo '<td><input type="checkbox" name="' . $item['slug'] . '_mandatory"' . ( $term_meta['cf_settings'][ $item['slug'] ]['mandatory'] ? ' ' . 'checked' : '' ) . '></td>';
								echo '<td>';

								$default_package_slug = 'bello-default-package';

								echo '<input type="checkbox" name="' . $item['slug'] . '_' . $default_package_slug . '" id="' . $item['slug'] . '_' . $default_package_slug . '"' . ( $term_meta['cf_settings'][ $item['slug'] ]['packages'][ $default_package_slug ] ? ' ' . 'checked' : '' ) . '><label for="' . $item['slug'] . '_' . $default_package_slug . '">' . __( 'Default', 'bt_plugin' )  . '</label><br>';
								foreach( $packages as $package ) {
									if ( isset( $term_meta['cf_settings'][ $item['slug'] ]['packages'][ $package->post_name ] ) ) {
										echo '<input type="checkbox" name="' . $item['slug'] . '_' . $package->post_name . '" id="' . $item['slug'] . '_' . $package->post_name . '"' . ( $term_meta['cf_settings'][ $item['slug'] ]['packages'][ $package->post_name ] ? ' ' . 'checked' : '' ) . '><label for="' . $item['slug'] . '_' . $package->post_name . '">' . $package->post_title . '</label><br>';
									} else {
										echo '<input type="checkbox" name="' . $item['slug'] . '_' . $package->post_name . '" id="' . $item['slug'] . '_' . $package->post_name . '"><label for="' . $item['slug'] . '_' . $package->post_name . '">' . $package->post_title . '</label><br>';
									}
								}
								echo '</td>';
							echo '</tr>';
						}

					echo '</table>';
					
				?>
			</td>  
		</tr>

	<?php }
}


// get category amenities
function bello_get_listing_category_amenities( $cat ) {
	$arr = bello_get_listing_category_fields( $cat, 0 );	
	$amenities = array();	
	if ( isset($arr) && !empty($arr) ) {
		$custom_fields = $arr[0]["listing_fields"];
		if ( isset($arr[1]["listing_fields"]) && !empty($arr[1]["listing_fields"]) ) {
			$custom_fields .= $arr[1]["listing_fields"];
		}
		$custom_fields_arr = explode(PHP_EOL, $custom_fields);
		foreach(  $custom_fields_arr as  $custom_field){
			if ( substr($custom_field, 0, 9) === 'amenities' ){
				array_push($amenities, $custom_field);
			}
		}
	}
       
	return $amenities;
}

// get category fields
function bello_get_listing_category_fields( $cat, $include_root = 1 ) {
     
        $arr = array();
        if ( function_exists( 'boldthemes_get_option' ) ){
            $listing_root_category      = get_term_by( 'slug', '_listing_root', 'listing-category' );
            $listing_root_category_id   = isset($listing_root_category) && isset($listing_root_category->term_id) ? $listing_root_category->term_id : 0; 
            
            if ( $listing_root_category_id > 0  && $include_root == 1 ){
                $arr[] = get_option( "taxonomy_term_$listing_root_category_id" );
            }
         
            if ( $cat == 0  ){
                $listing_search_show_fields_for_select_all_categories  = boldthemes_get_option( 'listing_search_show_fields_for_select_all_categories' );            
                if ( $listing_search_show_fields_for_select_all_categories == 1 ) {
                    return $arr;
                }
                $terms =  bello_get_listing_terms_all($listing_root_category_id);// helpers 496
                
            }else{
               $terms = bello_get_listing_terms( array( $cat ) );// helpers 481
            }
           

            if ( isset($terms) && !empty($terms) ) {
                    foreach ( $terms as $term ) {
                            $term_id  = $term->term_id;
                            $arr[]	  = get_option( "taxonomy_term_$term_id" );
                    }
            }
             
        }

	return $arr;
}

// get category search fields
function bello_get_listing_category_search_fields( $cat, $field_name = '', $type = '' ) {
	$fields = array();	
	if ( $type == 'search' ){
            $arr = bello_get_listing_category_fields( $cat, 0 );//330
        }else{
            $arr = bello_get_listing_category_fields( $cat );//330
        }
        
        $custom_fields = "";
	if ( isset($arr) && !empty($arr) ) {                
                for ( $i = 0; $i < count($arr); $i++){
                    if ( isset($arr[$i]["listing_fields"]) && !empty($arr[$i]["listing_fields"]) ) {
			$custom_fields .= PHP_EOL . $arr[$i]["listing_fields"];
                    }
                }
                
		$custom_fields_arr = explode(PHP_EOL, $custom_fields);
                
		if ( isset($custom_fields_arr) && !empty($custom_fields_arr) ) {
			foreach(  $custom_fields_arr as  $custom_field){
                                if ( $custom_field != '' ){
                                    $custom_field_arr = explode(';' , $custom_field);
                                    if ( isset($custom_field_arr) && !empty($custom_field_arr) ) {
                                            if ( $field_name == '' || $custom_field_arr[0] == $field_name){
                                                    array_push($fields, $custom_field_arr);
                                            }
                                    }
                                }
			}
		}
                
	}
        
	return $fields;
}

// get category cf settings
// type: 'search , 'search_advanced',  'mandatory' 
function bello_get_listing_category_cf_settings( $cat, $type = '' ) {	
	$cf_settings	= array();
	$custom_fields	= array();
        
        if ( $type == 'search' ){
            $arr = bello_get_listing_category_fields( $cat, 0 );//330
        }else{
            $arr = bello_get_listing_category_fields( $cat );//330
        }
        
	if ( isset($arr) && !empty($arr) ) {
                for ( $i = 0; $i < count($arr); $i++){
                    if ( isset($arr[$i]["cf_settings"]) && !empty($arr[$i]["cf_settings"]) ) {
                        $custom_fields = array_merge( $custom_fields, $arr[$i]["cf_settings"]);
                    }
                }
		
                if ( isset($custom_fields) && !empty($custom_fields) ) {
                        $uniqueKeys = array();
                        foreach(  $custom_fields as $key => &$custom_field){
                                if (array_key_exists($key, $uniqueKeys)) {
                                    continue;
                                }
                                $uniqueKeys[] = $key;
                                if ( $custom_field[$type] == 1 ){
                                        $field = bello_get_listing_category_search_fields( $cat, $key, $type );//353
                                        
                                        
                                        if ( isset($field) && !empty($field) ) {
                                                $custom_field['type'] = $field[0][0];
                                                $custom_field['text'] = $field[0][1];
                                                $custom_field['control'] = $field[0][2];
                                                $custom_field['position'] = $field[0][3];
                                        }
                                        
                                        array_push($cf_settings, $custom_field);						
                                }

                        }
                        
                }
		
	}
	
	
	return $cf_settings;
}

/* search filter */
add_action('wp_ajax_bt_get_listing_search_action', 'bt_get_listing_search_action_callback'); 
add_action('wp_ajax_nopriv_bt_get_listing_search_action', 'bt_get_listing_search_action_callback'); 
function bt_get_listing_search_action_callback(){
    $params = array();  
    if (isset($_POST)){            
       foreach($_POST as $field => $value) {
               $params[$field] = $value;
        }
    } 
    bello_dump_listing_search( $params );  
    die; 
}
if ( ! function_exists( 'bello_dump_listing_search' ) ) {
	function bello_dump_listing_search( $params = array() ) {
		$listing_slug	= $params["listing_slug"] ? $params["listing_slug"] : '';
		$listing_gets	= isset($params["listing_gets"]) ? $params["listing_gets"] : array();
                
		if ( $listing_slug != '' ) {
                    $listing = get_term_by('slug', $listing_slug, 'listing-category');
                    $listing_category_id = !empty($listing) ? $listing->term_id : 0;
                    $listing_gets['hide_control'] = 'working_time';
                    bello_get_listing_search( $listing_category_id, 'search', $listing_gets );
		}
	}
}

function bello_get_listing_search(  $cat, $type = 'search', $get_values = array() ) {  
       
        $show_controls = isset($get_values['show_control']) ? array($get_values['show_control']) : array();
        $hide_controls = isset($get_values['hide_control']) ? array($get_values['hide_control']) : array();
                
        $search_filter_item = array('text', 'social', 'checkbox','wysiwyg', 'url',
            'email','phone','textarea','time','date','datetime','image_advanced','oembed','single_image','number','opentable',
            'timekit','resurva','faq','divider','test');
        
		$cf_settings =  bello_get_listing_category_cf_settings( $cat, 'search' );        
        
        $listing_root_category      = get_term_by( 'slug', '_listing_root', 'listing-category' );
        $listing_root_category_id   = isset($listing_root_category) && !empty($listing_root_category) ? $listing_root_category->term_id : 0; 
        
        
        $field_root_class = ( $cat == $listing_root_category_id ) ? ' bt_root_field' : '';
        
	if ( !empty( $cf_settings) ){
		foreach(   $cf_settings as  $field ){ 
                        
                        if ( !$field['search'] ){
                            continue;
                        }
                    
                        $search_item = isset( $field['control'] ) ? $field['control'] : 'nocontrol';
                    
                        if ( !empty($show_controls) ){
                            if ( !in_array( $search_item,$show_controls) ){
				continue;
                            }
                        }
                        
                        if ( !empty($hide_controls) ){
                            if ( in_array( $search_item,$hide_controls) ){
				continue;
                            }
                        }
                    
			
			if ( in_array( $search_item, $search_filter_item) ){
				$search_item = 'checkbox';
			}
			$map_class = '';
			if ( $search_item == 'map' ){
				BoldThemesFrameworkTemplate::$listing_search_autocomplete	= bt_is_autocomplete();
				if (BoldThemesFrameworkTemplate::$listing_search_autocomplete){ 
					$map_class = ' bt_bb_search_map_control_autocomplete';
				}else{
					$map_class = ' bt_bb_search_map_control_select'; 
					continue;
				}                            
			}

			if (file_exists( plugin_dir_path( __FILE__ ) . "fields/front_end_templates/search/{$search_item}.php")) {
			?>
			<div style="min-height:0px;" class="bt_bb_column bt_bb_vertical_align_top bt_bb_padding_normal bt_bb_listing_search_col bt_bb_listing_search_fields bt_bb_spaced<?php echo $map_class;?><?php echo $field_root_class;?>" data-control-type="<?php echo $search_item;?>">
				<div class="bt_bb_column_content">
					<?php 
						require "fields/front_end_templates/search/{$search_item}.php";	
					?>	
				</div>
			</div>
			<?php
                        }else{
                            ?>
                            <div class="bt_bb_column bt_bb_vertical_align_top bt_bb_padding_normal bt_bb_listing_search_col" data-control-type="<?php echo $search_item;?>">
                                    <div class="bt_bb_column_content">					
                                            <?php 
                                                    require "fields/front_end_templates/search/advance_filter_item.php";
                                            ?>	
                                    </div>
                            </div>
                            <?php
                        }
                       
		}
	}
}

add_action('wp_ajax_bt_get_listing_additional_filter_action', 'bt_get_listing_additional_filter_action_callback'); 
add_action('wp_ajax_nopriv_bt_get_listing_additional_filter_action', 'bt_get_listing_additional_filter_action_callback'); 
function bt_get_listing_additional_filter_action_callback(){
	$params = array();
	if (isset($_POST)){
		   foreach($_POST as $field => $value) {
				   $params[$field] = $value;
		   }
	}
	bello_dump_listing_additional_filter( $params ); 
    die; 
}

if ( ! function_exists( 'bello_dump_listing_additional_filter' ) ) {
	function bello_dump_listing_additional_filter( $params = array() ) {
		$listing_slug	= $params["listing_slug"] ? $params["listing_slug"] : '';
		$listing_gets	= isset($params["listing_gets"]) ? $params["listing_gets"] : array();
		if ( $listing_slug != '' ) {
                    $listing = get_term_by('slug', $listing_slug, 'listing-category');
                    $listing_category_id = !empty($listing) ? $listing->term_id : 0;
                    bello_get_listing_search_advanced( $listing_category_id, 'search_advanced', $listing_gets );//551
		}
	}
}

// advance search filter controls
function bello_get_listing_search_advanced(  $cat, $type = 'search_advanced', $get_values = array() ) {
        
	$fields = array();
	$advance_filter_item = array('text', 'social', 'timekit','checkbox','wysiwyg', 'url','working_time',
            'email','phone','textarea','time','date','datetime','image_advanced','oembed','single_image','number',
            'opentable','timekit','resurva','faq','divider','test', 'map', 'price', 'working_time');
	$cf_settings_search =  bello_get_listing_category_cf_settings( $cat, 'search' );
	$cf_settings        =  bello_get_listing_category_cf_settings( $cat, 'search_advanced' );//385

	if ( !empty($cf_settings) ){	
		foreach( $cf_settings as $cf_setting){
			if ( !in_array( $cf_setting, $cf_settings_search ) ){
				array_push($fields, $cf_setting);
			}
		}
	}

	if ( !empty($fields) ){
		foreach(  $fields as  $field){
                    if ( isset($field['control']) ){
			$search_item = $field['control'];
			if (  in_array( $field['control'], $advance_filter_item ) ){
				$search_item = 'advance_filter_item';
			}
                        if (file_exists( plugin_dir_path( __FILE__ ) . "fields/front_end_templates/search/{$search_item}.php")) {
                            ?>
                            <div class="bt_bb_column bt_bb_vertical_align_top bt_bb_padding_normal bt_bb_listing_search_col" data-control-type="<?php echo $search_item;?>">
                                    <div class="bt_bb_column_content">					
                                            <?php 
                                                    require "fields/front_end_templates/search/{$search_item}.php";
                                            ?>	
                                    </div>
                            </div>
                            <?php
                        }else{
                            ?>
                            <div class="bt_bb_column bt_bb_vertical_align_top bt_bb_padding_normal bt_bb_listing_search_col" data-control-type="<?php echo $search_item;?>">
                                    <div class="bt_bb_column_content">					
                                            <?php 
                                                    require "fields/front_end_templates/search/advance_filter_item.php";
                                            ?>	
                                    </div>
                            </div>
                            <?php
                        }
                    }
		}
	}
}


/* listing results ajax */
add_action('wp_ajax_bt_get_listing_results_map_action', 'bt_get_listing_results_map_action_callback'); 
add_action('wp_ajax_nopriv_bt_get_listing_results_map_action', 'bt_get_listing_results_map_action_callback'); 
function bt_get_listing_results_map_action_callback(){
    $params = array();   
    if (isset($_POST)){
           foreach($_POST as $field => $value) {               
               if ( $value != '' && $value != null){
                   $params[$field] = $value;
               }
           }
    }	 
    bt_dump_listing_results( $params, 0, 1 ); 	
    die; 
}


/* listing count results ajax */
add_action('wp_ajax_bt_get_listing_results_count_action', 'bt_get_listing_results_count_action_callback'); 
add_action('wp_ajax_nopriv_bt_get_listing_results_count_action', 'bt_get_listing_results_count_action_callback'); 
function bt_get_listing_results_count_action_callback(){
    $params = array();
    if (isset($_POST)){
           foreach($_POST as $field => $value) {
                   $params[$field] = $value;
           }
    }
    echo bt_dump_listing_results( $params, 1, 0, 1 );
    die; 
}

/* listing results ajax */
add_action('wp_ajax_bt_get_listing_results_action', 'bt_get_listing_results_action_callback'); 
add_action('wp_ajax_nopriv_bt_get_listing_results_action', 'bt_get_listing_results_action_callback'); 
function bt_get_listing_results_action_callback(){
    $params = array(); 
    if (isset($_POST)){
           foreach($_POST as $field => $value) {               
               if ( $value != '' && $value != null){
                   $params[$field] = $value;
               }
           }
    }	 
    bt_dump_listing_results( $params ); 
    die; 
}

/*
*
* callback for ajax loading listing
*/
if ( ! function_exists( 'bt_dump_listing_results' ) ) {
	function bt_dump_listing_results( $params = array(), $only_count = 0, $only_map = 0, $all_count = 0) {
            if ( function_exists( 'boldthemes_get_option' ) ){
		BoldThemesFrameworkTemplate::$listing_list_view = isset( $params["listing_view"] ) ? $params["listing_view"] : 'standard';
		$orderby	  =  isset( $params["orderby"] ) ? $params["orderby"] : '';
		$listing_category =  isset( $params["listing_category"] ) ? $params["listing_category"] : '';
                $listing_region   =  isset( $params["listing_region"] ) ? $params["listing_region"] : '';
		$listing_tag	  =  isset( $params["listing_tag"] ) ? $params["listing_tag"] : '';
		$search_term	  =  isset( $params["search_term"] ) ? $params["search_term"] : '';
		$data_form        =  isset( $params["data_form"] ) ? $params["data_form"] : '';                
                $offset           =  isset( $params["offset"] ) ? $params["offset"] : '1';
                
                
		$meta_query_params = array();
                $form_query_params = array();
		if (isset($data_form)){			
			for($i = 0; $i < count($data_form); $i++) {
				if (isset($data_form[$i])){		
					$key_name  = $data_form[$i]["name"];
					$key_value = $data_form[$i]["value"];
					if ( $key_value != '' || $key_value > 0  ) {
						if (substr($key_name, 0, 17) === 'boldthemes_field_') {
								 $key_name = substr( $key_name, 17, strlen($key_name) );
								 $meta_query_params[$key_name] = $key_value;
						}  
						if ( substr($key_name, 0, 20) === 'bt_bb_listing_field_'){
								 $form_query_params[$key_name] = $key_value;
						}
					}
				}
			}			
		}
             
		switch ( $orderby ){
			case '-1':	$listing_orderby = 'post_date';	$listing_order = 'DESC';break;//Date, A-Z
			case '0':	$listing_orderby = 'post_date';	$listing_order = 'ASC';	break;//Date, Z-A
			case '1':	$listing_orderby = 'post_title';$listing_order = 'ASC';	break;//Name, A-Z
			case '2':	$listing_orderby = 'post_title';$listing_order = 'DESC';break;//Name, Z-A
            case '3':	$listing_orderby = 'price_from';$listing_order = 'DESC';break;//Price, Z-A
			case '4':	$listing_orderby = 'price_from';$listing_order = 'ASC'; break;//Price, A-Z
			default:	$listing_orderby = 'rand';	$listing_order = 'DESC';break;//Random
		}            

		if ( $listing_category != '' && $listing_region != '' ) {
			BoldThemesFrameworkTemplate::$listings = boldthemes_get_query_listings(  
				array(
					'taxonomy'          => 'listing-category', 
					'listing_type'      => $listing_category, 
					'taxonomy2'         => 'listing-region', 
					'listing_type2'     => $listing_region, 
					'search_term'	    => $search_term,
					'orderby'	        => $listing_orderby, 
					'order'             => $listing_order,
					'form_query_params' => $form_query_params,
					'meta_query'        => $meta_query_params,
				) 
			);
		} else if ( $listing_category != '' ) {
                   
			BoldThemesFrameworkTemplate::$listings	=	boldthemes_get_query_listings(
				array( 
					'taxonomy'          => 'listing-category', 
					'listing_type'      => $listing_category, 
					'search_term'       => $search_term,
					'orderby'           => $listing_orderby, 
					'order'             => $listing_order,
					'form_query_params' =>  $form_query_params,
					'meta_query'        => $meta_query_params,
				) 
			);	
		} else if ( $listing_region != '' ) {
			BoldThemesFrameworkTemplate::$listings	=	boldthemes_get_query_listings(
				array( 
					'taxonomy'	        => 'listing-region', 
					'listing_type'      => $listing_region, 
					'search_term'	    => $search_term,
					'orderby'	        => $listing_orderby, 
					'order'		        => $listing_order,
					'form_query_params' =>  $form_query_params,
					'meta_query'        => $meta_query_params,
				) 
			);	
		} else if ( $listing_tag != '' ) {
			BoldThemesFrameworkTemplate::$listings = boldthemes_get_query_listings(
				array( 
					'taxonomy'              => 'listing-tag', 
					'listing_type'          => $listing_tag, 
					'search_term'           => $search_term,
					'orderby'               => $listing_orderby, 
					'order'                 => $listing_order,
					'form_query_params'     => $form_query_params,
					'meta_query'            => $meta_query_params,
				) 
			);		
		}else{
			BoldThemesFrameworkTemplate::$listings = boldthemes_get_query_listings(
				array( 
					'search_term'           => $search_term,
					'orderby'               => $listing_orderby, 
					'order'                 => $listing_order,
					'form_query_params'     => $form_query_params,
					'meta_query'            => $meta_query_params,
				) 
			);
		}
                
		BoldThemesFrameworkTemplate::$found = count( BoldThemesFrameworkTemplate::$listings );

		BoldThemesFrameworkTemplate::$paged = isset( $params["paged"] ) ? $params["paged"] : 1;		
		BoldThemesFrameworkTemplate::$posts_per_page = boldthemes_get_option( 'listing_grid_listings_per_page' ) > 0 ? boldthemes_get_option( 'listing_grid_listings_per_page' ) : 1000;
		BoldThemesFrameworkTemplate::$max_page = ceil( BoldThemesFrameworkTemplate::$found / BoldThemesFrameworkTemplate::$posts_per_page );
		BoldThemesFrameworkTemplate::$custom_map_style = boldthemes_get_option( "custom_map_style" );
		BoldThemesFrameworkTemplate::$limit = BoldThemesFrameworkTemplate::$paged > 0 ?
		BoldThemesFrameworkTemplate::$posts_per_page * BoldThemesFrameworkTemplate::$paged : BoldThemesFrameworkTemplate::$posts_per_page;

                
        // only count of all listings
		if ($only_count == 1) { 
			return count(BoldThemesFrameworkTemplate::$listings);
		}
                
		// listings on map append when loadmore
		$listing_grid_listings_pagination = boldthemes_get_option( 'listing_grid_listings_pagination' ) != '' ?  boldthemes_get_option( 'listing_grid_listings_pagination' ) : 'paged';
		if ( $only_map == 1 && $listing_grid_listings_pagination == 'loadmore' ){ 
			BoldThemesFrameworkTemplate::$listings = array_slice( BoldThemesFrameworkTemplate::$listings, 0, BoldThemesFrameworkTemplate::$limit, true);
			boldthemes_listing_results_map_html( BoldThemesFrameworkTemplate::$listings, BoldThemesFrameworkTemplate::$limit );
			die;
		}
	   
		if ( BoldThemesFrameworkTemplate::$paged > 0 ) {
			$start = ( BoldThemesFrameworkTemplate::$paged - 1 ) * BoldThemesFrameworkTemplate::$posts_per_page;
			$end = BoldThemesFrameworkTemplate::$posts_per_page;
			BoldThemesFrameworkTemplate::$listings = array_slice( BoldThemesFrameworkTemplate::$listings, $start, $end, true );
		} else {
			BoldThemesFrameworkTemplate::$listings = array_slice( BoldThemesFrameworkTemplate::$listings, 0, BoldThemesFrameworkTemplate::$limit, true );
		}               
		
		if ( $only_map == 1 ) {
			//listings on map new when paged
			boldthemes_listing_results_map_html( BoldThemesFrameworkTemplate::$listings, BoldThemesFrameworkTemplate::$limit );		
		} else {
			//listings new when paged or loadmore
			boldthemes_listing_box_html( BoldThemesFrameworkTemplate::$listings, BoldThemesFrameworkTemplate::$limit, 0, BoldThemesFrameworkTemplate::$paged );
		}
            }
	}
}

/*
*
* nearby locations ajax
*/
add_action( 'wp_ajax_bt_get_listing_nearby_action', 'bt_get_listing_nearby_action_callback' ); 
add_action( 'wp_ajax_nopriv_bt_get_listing_nearby_action', 'bt_get_listing_nearby_action_callback' ); 
function bt_get_listing_nearby_action_callback() { 
    $params = array();    
    if ( isset( $_POST ) ) {
           foreach( $_POST as $field => $value ) {               
               if ( $value != '' && $value != null ) {
                   $params[ $field ] = $value;
               }
           }
    } 
    
    $categories = isset( $params['categories'][0] ) ? $params['categories'][0] : array();    
    bt_dump_listing_nearby_results( $params['listing_id'], $params['lat'], $params['lng'], $categories );
    
    die; 
}

function bt_dump_listing_nearby_results( $listing_id, $latitudeFrom, $longitudeFrom, $listing_categories = array()) { 
    if ( function_exists( 'boldthemes_get_option' ) ){
	BoldThemesFrameworkTemplate::$listing_grid_nearby_category	= boldthemes_get_option( 'listing_grid_nearby_category' ) > 0 ? boldthemes_get_option( 'listing_grid_nearby_category' ) : 0;
	if ( BoldThemesFrameworkTemplate::$listing_grid_nearby_category == 1 ) {
		$form_query_params['bt_bb_listing_field_distance_value'] = 0;
		$listings = boldthemes_get_query_listings( array( 'form_query_params' => $form_query_params, 'taxonomy' => 'listing-category', 'listing_type' => $listing_categories ) );	
	}else{
		$listings = boldthemes_get_query_listings( array( 'taxonomy' => 'listing-category' ) );	
	}	
	
   BoldThemesFrameworkTemplate::$posts_per_page = boldthemes_get_option( 'listing_grid_nearby_locations' ) > 0 ? boldthemes_get_option( 'listing_grid_nearby_locations' ) : 0;
	if ( BoldThemesFrameworkTemplate::$posts_per_page > 0 ){
			$nearby_positions = array();
			foreach ( $listings as $listing ) {		
					$location_position = boldthemes_rwmb_meta('boldthemes_theme_listing-location_position', array(), $listing->ID );		
					if ( $location_position != '' && $listing->ID != $listing_id ) {
							$location_position = explode( ',', $location_position );
							$distance = boldthemes_get_distance( $latitudeFrom, $longitudeFrom, $listing->ID );
							$nearby_position = array( 'id' => $listing->ID, 'title' => $listing->post_title, 'lat' => $location_position[0], 'lng' => $location_position[1], 'distance' => $distance );
							array_push( $nearby_positions, $nearby_position );
					}
			}

			if ( ! empty( $nearby_positions ) ) {
					$nearby_positions = array_slice( bt_sort_multiarray( $nearby_positions, 'distance' ), 0, boldthemes_get_option( 'listing_grid_nearby_locations' ), true );	
					BoldThemesFrameworkTemplate::$found = count( $nearby_positions );

					?>
					<section class="btNearbyLocations gutter">
									<div class="port">
													<div class="btNearbyLocationsContent">
																	<div class="btNearbyLocationsTitle">
																			<h3><?php esc_html_e( 'Nearby locations', 'bt_plugin' ); ?></h3>
																	</div>

																	<div class="bt_bb_listing_view_as_grid">
																					<?php
																							boldthemes_listing_box_html( $nearby_positions, boldthemes_rwmb_meta('listing_grid_nearby_locations' ), 1 );					
																					?>
																	</div>
													</div>
									</div>
					</section>
					<?php
			}
        }
    }
}

// save custom fields definition in category
add_action( 'edited_listing-category', 'bello_save_listing_category_custom_fields', 10, 2 );
function bello_save_listing_category_custom_fields( $term_id ) {  
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_term_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach( $cat_keys as $key ) { // $key = listing_fields
			if ( isset( $_POST['term_meta'][ $key ] ) ) {
				$term_meta[ $key ] = $_POST['term_meta'][ $key ];
			}
		}

		$packages = bello_get_packages();

		if ( isset( $_POST['term_meta']['listing_fields'] ) ) {
			$cf_arr = bello_get_listing_field_arr( $_POST['term_meta']['listing_fields'], $t_id, true );                        
			$cf_settings_arr = array();
       
			foreach( $cf_arr as $item ) {
				$cf_settings_arr[ $item['slug'] ]['search'] = false;
				if ( isset( $_POST[ $item['slug'] . '_' . 'search' ] ) ) {
					$cf_settings_arr[ $item['slug'] ]['search'] = true;
				}
				$cf_settings_arr[ $item['slug'] ]['search_advanced'] = false;
				if ( isset( $_POST[ $item['slug'] . '_' . 'search_advanced' ] ) ) {
					$cf_settings_arr[ $item['slug'] ]['search_advanced'] = true;
				}
				$cf_settings_arr[ $item['slug'] ]['mandatory'] = false;
				if ( isset( $_POST[ $item['slug'] . '_' . 'mandatory' ] ) ) {
					$cf_settings_arr[ $item['slug'] ]['mandatory'] = true;
				}
				$cf_settings_arr[ $item['slug'] ]['mandatory'] = false;
				if ( isset( $_POST[ $item['slug'] . '_' . 'mandatory' ] ) ) {
					$cf_settings_arr[ $item['slug'] ]['mandatory'] = true;
				}

				$cf_settings_arr[ $item['slug'] ]['packages'] = array();
				
				foreach( $packages as $package ) {
					$cf_settings_arr[ $item['slug'] ]['packages'][ $package->post_name ] = false;
					if ( isset( $_POST[ $item['slug'] . '_' . $package->post_name ] ) ) {
						$cf_settings_arr[ $item['slug'] ]['packages'][ $package->post_name ] = true;
					}
				}

				$default_package_slug = 'bello-default-package';

				$cf_settings_arr[ $item['slug'] ]['packages'][ $default_package_slug ] = false;
				if ( isset( $_POST[ $item['slug'] . '_' . $default_package_slug ] ) ) {
					$cf_settings_arr[ $item['slug'] ]['packages'][ $default_package_slug ] = true;
				}
			}
                        
		}

		$term_meta['cf_settings'] = $cf_settings_arr;
		update_option( "taxonomy_term_$t_id", $term_meta );                
               //update_term_meta( $t_id, "taxonomy_term_$t_id", $term_meta);

	}
}

// LISTING META BOX CUSTOM FIELDS

// save custom fields on listing item save
add_action( 'save_post_listing', 'bello_listing_save', 5 );
function bello_listing_save( $post_id ) {
	if ( isset( $_POST['post_ID'] ) ) {
		$post_terms = wp_get_post_terms( $_POST['post_ID'], 'listing-category' );
                
		if ( $post_terms ) {                        
            $terms = array();
			foreach( $post_terms as $term ) {                           
					if ( $term->parent > 0 ) {
							$term_arr = array( $term->term_id );
							$terms = bello_get_listing_terms( $term_arr );
					}
			} 
                        
                        
			if ( $terms ) {
				bello_helper_add_mb( $terms );
			}else{
				foreach( $post_terms as $term ) {
					$term_arr = array( $term->term_id );
					$terms = bello_get_listing_terms( $term_arr );
				}
				bello_helper_add_mb( $terms );
			}
                        
			if ( ! isset( $_POST['boldthemes_theme_listing-bello-listing-package'] ) ) {
				$meta_key = 'boldthemes_theme_listing-bello-listing-package';
				$meta_value = 'bello-default-package';
				if ( ! update_post_meta( $_POST['post_ID'], $meta_key, $meta_value) ) { 
					add_post_meta( $_POST['post_ID'], $meta_key, $meta_value, true );
				}
			}
		}
	}
}

// show custom fields (edit listing item)
function bello_listing_category() {
	$screen = get_current_screen();
	if ( ! ( is_object( $screen ) && 'listing' == $screen->post_type ) || ! isset( $_GET['post'] ) ) {
		return;
	}
        
        $args = array('orderby' => 'parent', 'order' => 'ASC', 'fields' => 'all');
	$post_terms = wp_get_post_terms( $_GET['post'], 'listing-category', $args );  
        
	if ( $post_terms ) {
                bello_helper_add_mb( $post_terms );              
	}
}
add_action( 'current_screen', 'bello_listing_category' );

function bello_listing_category_fe() {	     
        if ( isset($_GET['listing_id']) ){
            $args = array('orderby' => 'parent', 'order' => 'ASC', 'fields' => 'all');
            $post_terms = wp_get_post_terms( $_GET['listing_id'], 'listing-category', $args );             
            if ( $post_terms ) {
                    bello_helper_add_mb( $post_terms, true );
            }
        }else{
            $term_arr = array( $_GET['cat'] );
            $terms = bello_get_listing_terms( $term_arr );
            bello_helper_add_mb( $terms, true );
        }
}

$account_listing_endpoint = bt_account_listing_endpoint();
if (  isset( $_GET['cat'] ) && strpos( $_SERVER['REQUEST_URI'], $account_listing_endpoint ) ) {
	add_action( 'init', 'bello_listing_category_fe' );
}

// helper function add metabox
function bello_helper_add_mb( $post_terms, $front_end = false ) {
    if ( function_exists( 'boldthemes_get_option' ) ){
	boldthemes_add_mb(
		array( // id, title, post_type, autosave
			'id' => 'listing_cf',
			'title' => esc_html__( 'Custom Fields', 'bt_plugin' ),
			'post_type' => 'listing',
			'autosave' => false
		)
	);
        
        $listing_show_subtitles_in_search   = boldthemes_get_option( 'listing_show_subtitles_in_search' ) != '' ? boldthemes_get_option( 'listing_show_subtitles_in_search' )
                                                 : BoldThemes_Customize_Default::$data['listing_show_subtitles_in_search'];
        
        // subtitle metabox for listing
        if ( $listing_show_subtitles_in_search ) {        
            $mb_field_arr_subtitle_heading = array(
                    'mb_id' => 'listing_cf',
                    'field_id' => 'listing' . '-' . 'bello-listing-subtitle-heading',
                    'name' => esc_html__( 'Listing Subtitle', 'bt_plugin' ),
                    'type' => 'heading',
                    'order' => 0
            );
            boldthemes_add_mb_field( $mb_field_arr_subtitle_heading );	

            $mb_field_arr_subtitle = array( 
                'mb_id' => 'listing_cf', 
                'field_id' => 'listing-subtitle', 
                'name' => __( 'Subtitle', 'bt_plugin' ), 
                'type' => 'text',  
                'attributes' => array( 'disabled' => false ),
                'order' => 0
            );
            boldthemes_add_mb_field( $mb_field_arr_subtitle );
        }
	// /subtitle metabox for listing
        
	$has_listing_fields = false;
	$root = get_term_by( 'slug', '_listing_root', 'listing-category' );
	array_unshift( $post_terms, $root );  
        
	foreach ( $post_terms as $term ) {
            
		$term_id = isset($term->term_id) ? $term->term_id : 0;;
                
		$cf = get_option( "taxonomy_term_$term_id" );
		$listing_fields = isset($cf['listing_fields']) ? $cf['listing_fields'] : "";
		$listing_fields = trim( $listing_fields );

		if ( $listing_fields != '' ) {
			
			$has_listing_fields = true;

			$cf_items = explode( PHP_EOL, $listing_fields );
                        
			$cf_settings = $cf['cf_settings'];
                        
		
			$mb_field_arr = array();
			$current_suboption = '';
                        
                        

			if ( ! is_admin() ) { // FE listing package
				
				$mb_field_arr = array( 'mb_id' => 'listing_cf', 'field_id' => 'listing' . '-' . 'bello-listing-package', 'type' => 'hidden' );
				boldthemes_add_mb_field( $mb_field_arr );
				
			} else { // BE listing package
				
				$listing_id = isset( $_GET['post'] ) ? $_GET['post'] : 0;
                                if ( $listing_id == 0 ){
                                    $listing_id = isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : 0;
                                }                                
				$listing = get_post( $listing_id );
				$author_id = $listing->post_author;
                                
				
				$saved_package = '';
				$saved_custom_fields = get_post_custom( $listing_id );
				if ( isset( $saved_custom_fields['boldthemes_theme_listing-bello-listing-package'] ) ) {
					$saved_package = $saved_custom_fields['boldthemes_theme_listing-bello-listing-package'][0];
				}

				$user_subscr = function_exists( 'wcs_get_users_subscriptions' ) ? wcs_get_users_subscriptions( $author_id ) : array();
				
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

						$user_packages[] = array( 'name' => $product_name . ' #' . $subscr_id, 'slug' => $product_slug . '#' . $subscr_id );
						
					}
				}
				
				$used_packages = bello_get_meta_values( array( 'meta_key' => 'boldthemes_theme_listing-bello-listing-package', 'exclude' => array( $listing_id ) ) );
				
				$mb_options = array( 'bello-default-package' => __( 'Default', 'bt_plugin' ) );
				foreach ( $user_packages as $package ) {
					//if ( ! in_array( $package['slug'], $used_packages ) ) {
						$mb_options[ $package['slug'] ] = $package['name'];
					//}
				}
                                
                                
				
				$mb_field_arr = array(
					'mb_id' => 'listing_cf',
					'field_id' => 'listing' . '-' . 'bello-listing-package-heading',
					'name' => esc_html__( 'Listing Package', 'bt_plugin' ),
					'type' => 'heading',
					'order' => 0
				);
				
				boldthemes_add_mb_field( $mb_field_arr );				
				
				$mb_field_arr = array(
					'mb_id' => 'listing_cf',
					'field_id' => 'listing' . '-' . 'bello-listing-package',
					'name' => esc_html__( 'Package', 'bt_plugin' ),
					'type' => 'select',
					'order' => 0,
					'options' => $mb_options
				);
				
				boldthemes_add_mb_field( $mb_field_arr );
			}
                        
                       
			$i = 0;
			foreach ( $cf_items as $cf_item ) {
                                
				$i++;
				if ( trim( $cf_item ) != '' ) {
                                    
					if ( substr( $cf_item, 0, 1 ) != ' ' ) { // start field
						$current_suboption = '';
						if ( count( $mb_field_arr ) > 0 ) { // save prev
							if ( ! ( $mb_field_arr['type'] == 'taxonomy' && is_admin() ) ) {
								bello_add_mb_field( $mb_field_arr );
							}
						}
						$cf_item = trim( $cf_item );
						$cf_item_arr = explode( ';', $cf_item );
                                                
                                                if ( isset($cf_item_arr[0]) && isset($cf_item_arr[1]) && isset($cf_item_arr[2]) ) {                                                    
                                                    $mb_field_arr = array( 'mb_id' => 'listing_cf', 'field_id' => 'listing' . '-' . $cf_item_arr[0], 'name' => $cf_item_arr[1], 'type' => $cf_item_arr[2] );
                                                }else{
                                                    echo esc_html__( '<div class="updated" style="border-left: 4px solid #ffba00"><p>Custom Field is not well formatted: ', 'bt_plugin' ) . $cf_item . esc_html__('</p></div>', 'bt_plugin' );
                                                }
						// required
						if ( isset( $cf_settings[ $cf_item_arr[0] ] ) && $cf_settings[ $cf_item_arr[0] ][ 'mandatory' ] && ! is_admin() ) {
							$mb_field_arr['attributes'] = array( 'required' => true );
						}

					} else if ( count( $mb_field_arr ) > 0 ) { // field suboptions
						if ( substr( $cf_item, 0, 1 ) == ' ' && substr( $cf_item, 1, 1 ) != ' ' ) { // suboption key
							$cf_item = trim( $cf_item );
							$current_suboption = $cf_item;
							if ( $current_suboption == 'options' ) { // options (select, radio, etc.)
								$mb_field_arr[ $current_suboption ] = array();
							}
						} else if ( substr( $cf_item, 0, 2 ) == '  ' ) { // suboption value
							$cf_item = trim( $cf_item );
							if ( $current_suboption == 'options' ) { // options (select, radio, etc.)
								$suboption_value_arr = explode( ':', $cf_item, 2 );
								$mb_field_arr[ $current_suboption ][ $suboption_value_arr[0] ] = $suboption_value_arr[1];
							} else {
								$mb_field_arr[ $current_suboption ] = $cf_item;
							}
						}
					}
                                        
					if ( isset( $mb_field_arr['name'] ) ) {
						$mb_field_arr['name'] = stripcslashes( $mb_field_arr['name'] );
					}

					if ( isset( $mb_field_arr['desc'] ) ) {
						$mb_field_arr['desc'] = stripcslashes( $mb_field_arr['desc'] );
					}
                                        
					if ( $i == count( $cf_items ) ) { // save last
						if ( ! ( $mb_field_arr['type'] == 'taxonomy' && is_admin() ) ) {
							bello_add_mb_field( $mb_field_arr );
						}
					}
                                        
				}
			}
		}
	}

	if ( $front_end ) {

		add_action( 'wp_head', function() { ?>
			<script>
                            /*jQuery( document ).ready( function() { 
                                 jQuery( '.rwmb-taxonomy-wrapper input' ).change();
                            } )*/
                            
                            document.addEventListener('readystatechange', function() { 
                                if ( typeof(jQuery) !== 'undefined' && ( document.readyState === 'interactive' || document.readyState === 'complete' ) ) {
                                    jQuery( '.rwmb-taxonomy-wrapper input' ).change();
                                }
                            } )
                          </script>
		<?php } );

		add_filter( 'rwmb_boldthemes_theme_listing-category-' . $term_id . '_field_meta', 
			function( $meta, $field, $saved ) {
				$term_arr = array( substr( $field['id'], strrpos( $field['id'], '-' ) + 1 ) );
				$terms = bello_get_listing_terms( $term_arr );
				$ret_arr = array();
				foreach( $terms as $term ) {
					$ret_arr[] = $term->term_id;
				}
				return $ret_arr;
			}, 10, 3
		);

		$mb_field_arr = array( 'mb_id' => 'listing_cf', 'field_id' => 'listing' . '-' . 'category' . '-' . $term_id, 'name' => __( 'Category', 'bt_plugin' ), 'type' => 'taxonomy', 'taxonomy' => 'listing-category', 'field_type' => 'checkbox_tree', 'attributes' => array( 'disabled' => false ), 'class' => 'bello_category_listing_new' );
					   
		boldthemes_add_mb_field( $mb_field_arr );
                
                 // Listing Tags on Frontend Submission Form
                $listing_show_tags_on_frontend_form   = boldthemes_get_option( 'listing_show_tags_on_frontend_form' ) != '' ? 
                        boldthemes_get_option( 'listing_show_tags_on_frontend_form' ) : BoldThemes_Customize_Default::$data['listing_show_tags_on_frontend_form'];
                
                if ( $listing_show_tags_on_frontend_form ){                   
                    if ( isset( $_GET['listing_id'] ) && isset( $_GET['cat'] ) ) { // EDIT
                        $mb_field_arr = array(
                                'mb_id' => 'listing_cf',
                                'field_id' => 'listing' . '-' . 'bello-listing-tags-heading',
                                'name' => esc_html__( 'Listing Tags', 'bt_plugin' ),
                                'desc' => esc_html__( 'If you want to associate Your Published Listing with TAGS simply choose from a tags shown below.', 'bt_plugin' ),
                                'type' => 'heading',
                                'order' => 10000
                        );
                        boldthemes_add_mb_field( $mb_field_arr );

                        $mb_field_arr = array(
                                'mb_id' => 'listing_cf',
                                'field_id' => 'listing-bello-listing-tag',
                                'name' => esc_html__( '', 'bt_plugin' ),
                                'type' => 'tag',
                                'order' => 10001
                        );
                        bello_add_mb_field( $mb_field_arr );
                    }else{ // NEW
                        $mb_field_arr = array(
                                'mb_id' => 'listing_cf',
                                'field_id' => 'listing' . '-' . 'bello-listing-tags-heading',
                                'name' => esc_html__( 'Listing Tags', 'bt_plugin' ),
                                'desc' => esc_html__( 'You will be able to associate This Listing with TAGS when Listing get published.', 'bt_plugin' ),
                                'type' => 'heading',
                                'order' => 10000
                        );
                        boldthemes_add_mb_field( $mb_field_arr );
                    }
                }
                // /Listing Tags on Frontend Submission Form

	}
        
	if ( $has_listing_fields ) {
            if ( class_exists( 'BT_RWMB_Core' ) ) {
		$bt_mb_core = new BT_RWMB_Core;
		$bt_mb_core->init();
            }
	}
    }

}

// bello mb preprocessing
function bello_add_mb_field( $mb_field_arr ) {
    //map
     
    if ( function_exists( 'boldthemes_get_option' ) ){
	if ( $mb_field_arr['type'] == 'map' && ! isset( $mb_field_arr['std'] ) ) {
		$lat = boldthemes_get_option( 'listing_search_distance_lat' );
		$lng = boldthemes_get_option( 'listing_search_distance_lng' );
		if ( $lat != '' && $lng != '' ) {
			$mb_field_arr['std'] = $lat . ',' . $lng;
		}
	}
        
        // Listing Tags on Frontend Submission Form
        if ( $mb_field_arr['type'] == 'tag' && isset( $_GET['rwmb-form-submitted'] ) ) {
            $tag_meta_values = array();
            $id = 0;
            if ( isset($_GET["post"]) ){
                $id = $_GET["post"];
                $tag_meta_values = get_post_meta($_GET["post"], BoldThemesFramework::$pfx . '_' .$mb_field_arr['field_id'], true); 
            }
            if ( isset($_GET["listing_id"]) ){
                $id = $_GET["listing_id"];
                $tag_meta_values = get_post_meta($_GET["listing_id"], BoldThemesFramework::$pfx . '_' .$mb_field_arr['field_id'], true);   
            }            
            if ( intval($id) > 0 ){
                global $wpdb;
                // select all listing-tags for listing
                $where             = 'tr.object_id = %d and tt.taxonomy = "listing-tag"';                        
                $where_fields      = array( $id );
                $result =  $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->term_relationships as tr "
                        . " inner join $wpdb->term_taxonomy as tt on tr.term_taxonomy_id=tt.term_taxonomy_id WHERE $where", $where_fields ) );
                // remove all listing-tags from listing
                foreach( $result as $r ) {
                    if ( isset($r->term_taxonomy_id) ){
                        $sql = "DELETE FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d;";
                        $wpdb->query($wpdb->prepare($sql, array($r->term_taxonomy_id)));
                    }
                }
                // add all listing-tags from frontendform to listing
                if ( $tag_meta_values ) {
                foreach( $tag_meta_values as $value ) {
                    if ( isset($value) ){
                        $table  = $wpdb->term_relationships;
                        $data   = array('object_id' => $id, 'term_taxonomy_id' => $value, 'term_order' => 0);
                        $format = array('%d','%d');
                        $wpdb->insert($table,$data,$format);
                        $insert_id = $wpdb->insert_id;
                    }
                }
                }
            }            
        }
        // /Listing Tags on Frontend Submission Form
    }
    
    boldthemes_add_mb_field( $mb_field_arr );
}

// get all listing fields
function bello_get_listing_fields( $params = array() ) {
	$id = isset( $params['listing_id'] ) ? $params['listing_id'] : get_the_ID();
        $post_terms = bt_wp_get_post_terms( array( 'listing_id' => $id ) );

	$arr = array();
	$prefix = BoldThemesFramework::$pfx . '_' . 'listing' . '-';
	$saved_custom_fields = get_post_custom( $id );
       
	foreach ( $post_terms as $term ) {
		$term_id = isset($term) && !empty($term) ? $term->term_id : 0;
                
		$cf = get_option( "taxonomy_term_$term_id" );
		$listing_fields = isset($cf['listing_fields']) ? $cf['listing_fields'] : '';
		$listing_fields = trim( $listing_fields );
                
		if ( $listing_fields != '' ) {
			$arr = $arr + bello_get_listing_field_arr( $listing_fields, $term_id );
		}
                
		foreach( $saved_custom_fields as $key => $value ) {
			if ( 0 === strpos( $key, $prefix ) ) {
				$arr_key = str_replace( $prefix, '', $key );
				if ( isset( $arr[ $arr_key ] ) ) {
					$arr[ $arr_key ]['value'] = $value;
				}
			}
		}
	}
	return $arr;
}

// get listing fields array
function bello_get_listing_field_arr( $listing_fields, $term_id = false, $is_admin = false ) {    
	$arr = array();
	$cf_items = explode( PHP_EOL, $listing_fields ); 
        
	foreach ( $cf_items as $cf_item ) {
            if ( $cf_item != '' && substr( $cf_item, 0, 1 ) != ' ' ) {
                $cf_item = trim( $cf_item );                       
                $cf_item_arr = explode( ';', $cf_item ); 
                
                if ( is_array( $cf_item_arr ) && count( $cf_item_arr ) > 3 ) {
                    if ($cf_item_arr[2] == 'price' && !$is_admin ){
                        //specific for price: 1 custom filed, 2 controls
                        $arr[ 'price_from' ]    = array( 'slug' => 'price_from', 'name' => 'Price from', 'type' => 'price', 'group' => $cf_item_arr[3], 'term_id' => $term_id );
                        $arr[ 'price_to' ]      = array( 'slug' => 'price_to', 'name' => 'Price to', 'type' => 'price', 'group' => $cf_item_arr[3], 'term_id' => $term_id );
                        $arr[ 'price_free' ]    = array( 'slug' => 'price_free', 'name' => 'Price free', 'type' => 'price', 'group' => $cf_item_arr[3], 'term_id' => $term_id );
                        $arr[ $cf_item_arr[0] ] = array( 'slug' => $cf_item_arr[0], 'name' => $cf_item_arr[1], 'type' => $cf_item_arr[2], 'group' => $cf_item_arr[3], 'term_id' => $term_id );
                    }else{
                        $arr[ $cf_item_arr[0] ] = array( 'slug' => $cf_item_arr[0], 'name' => $cf_item_arr[1], 'type' => $cf_item_arr[2], 'group' => $cf_item_arr[3], 'term_id' => $term_id );
                    }
                }
            }
	}
	return $arr;
}

// get field value
function bello_get_listing_field_value( $custom_field, $listing_id = null ) {
          $arg = isset( $listing_id ) ? array( 'listing_id' => $listing_id ) : null;
	  $custom_fields = bello_get_listing_fields( $arg );
          
	  if ( isset($custom_fields[$custom_field]) ) {
		  $my_custom_field = $custom_fields[$custom_field];
		  if ( $custom_field == 'faq' ) {
			return $my_custom_field;
		  }
		  if ( isset( $my_custom_field['value'] ) ) {
				if ( count( $my_custom_field['value'] ) == 1 ) {
					return $my_custom_field['value'][0];
				} else {
					return $my_custom_field['value'];
				}
		  }
	  }
	  return '';
}

// get field group
function bello_get_listing_field_group( $custom_field_group ) {
	$custom_fields = bello_get_listing_fields();

	$ret_arr = array();
	foreach ($custom_fields as $key => $value ) {
		if ( isset($value['value']) ) {
			if ( isset($value['group']) && isset($value['name']) && isset($value['value']) ) {
				if ( $value['group'] == $custom_field_group && !empty($value['value']) ) {
					
					if ( $value['group'] == 'Media'){
						array_push($ret_arr, $value);
					}else if ( $value['group'] == 'MediaVideo'){
						array_push($ret_arr, $value);
					}else if ( $value['group'] == 'MediaAudio'){
						array_push($ret_arr, $value);
					}else{
						$key = $value['name'];

						if ( count($value['value']) == 1 ){
								$ret_arr[ $key ] = $value['value'][0];
						} else {
								$ret_arr[ $key ] = $value['value'];
						}
					}

				}
			}
		}
	}

	return $ret_arr;
}

function bello_get_listing_field_groups() {
    $custom_fields = bello_get_listing_fields();
    $ret_arr = array();    
    $i = 0;
    
    foreach ($custom_fields as $key => $value ) {
        if ( isset($value['group']) ) {
            $group = $value['group'];
            $name = $value['name'];
          
            if ( isset($value['value']) ){
                if (strpos($group, 'widget_') === false && 
                        strpos($group, 'Amenities') === false &&
                        strpos($group, 'FAQ') === false &&
                        strpos($group, 'Media') === false &&
                        strpos($group, 'MediaVideo') === false &&
                        strpos($group, 'MediaAudio') === false &&
                        strpos($value['slug'],'amenities') === false
                    ) {
                    if ( $group != '' ) {
                        $ret_arr[$name] =  $group;
                        $i++;
                    }
                }
            }
        }
    }
    $ret_arr = array_unique($ret_arr);  
    return $ret_arr;
}



// get field type
function bello_get_listing_field_type( $custom_field_type ) {
	  $custom_fields = bello_get_listing_fields();
	  
	  $ret_arr = array();
	  foreach ( $custom_fields as $key => $value ) {
			
			if ( isset($value['value']) ) {
				if ( isset($value['type']) && isset($value['name']) && isset($value['value']) ) {
					if ( $value['type'] == $custom_field_type && !empty($value['value']) ) {				
						$key = $value['name'];
						if ( count($value['value']) == 1 ){
							$ret_arr[$key] = $value['value'][0];
						}else{
							$ret_arr[$key] = $value['value'];
						}
					}
				}
			}
	  }

	  return $ret_arr;
}

//show fields galleries
function bello_show_field_galleries( $fields, $showinfo, $gallery_type ) {    
    $_html = '';
    require "fields/front_end_templates/image_advanced2.php";
    return $_html;
}

// show field
function bello_show_field( $field, $return = '' ) {
        $_html = '';
	$saved_custom_fields = get_post_custom( get_the_ID() );	
  
	if ( isset($saved_custom_fields['boldthemes_theme_listing-bello-listing-package']) ) {
		
		$saved_package = $saved_custom_fields['boldthemes_theme_listing-bello-listing-package'][0];
                
		$subscr_arr = explode( '#', $saved_package );
		
		$package = 'bello-default-package'; // default
		if ( count( $subscr_arr ) == 2 ) {
                        if (function_exists( 'wcs_get_subscription' )) {
                                $subscr = wcs_get_subscription( $subscr_arr[1] );
                                if ( $subscr ){
                                        if ( $subscr->get_status() == 'active' ) {
                                                $package = $subscr_arr[0];
                                        }
                                }
                         }
		}
		$title = $return;

		$t_id = $field['term_id'];                
		$arr = bello_get_listing_category_fields( $t_id, 0 ); 
               
		//$term_meta = $arr[0];
               
			$count = count($arr);                
			for ( $i = 0; $i < $count; $i++ ) {
					$term_meta = $arr[$i];                       
					if ( isset( $term_meta['cf_settings'] ) && count( $term_meta['cf_settings'] ) > 0 ) {	
						if ( isset( $term_meta['cf_settings'][ $field['slug'] ]['packages'][ $package ] ) && $term_meta['cf_settings'][ $field['slug'] ]['packages'][ $package ] ) {
							
							switch ($field['slug']) {
											case 'faq':			require "fields/front_end_templates/faq.php"; break;
											case 'contact_form_email':	require "fields/front_end_templates/contact_form.php"; break;
											default:
												if (file_exists( plugin_dir_path( __FILE__ ) . "/fields/front_end_templates/{$field['type']}.php")) {
													require "fields/front_end_templates/{$field['type']}.php";
												}else{
													require "fields/front_end_templates/text.php";
												}
												break;
									}

									if ( $return ){
											return $_html;
									}
							}
					}  

			}
	}

}

function bello_field_in_packages( $field, $listing_id = 0 ) {
    $listing_id = $listing_id > 0 ? $listing_id : get_the_ID();
	$saved_custom_fields = get_post_custom( $listing_id );	
	
	if ( isset($saved_custom_fields['boldthemes_theme_listing-bello-listing-package']) ) {		
		$saved_package = $saved_custom_fields['boldthemes_theme_listing-bello-listing-package'][0];		
		$subscr_arr = explode( '#', $saved_package );		
		$package = 'bello-default-package'; // default
		if ( count( $subscr_arr ) == 2 ) {
                        if (function_exists( 'wcs_get_subscription' )) {
                            $subscr = wcs_get_subscription( $subscr_arr[1] );
                            if ( $subscr ){
                                    if ( $subscr->get_status() == 'active' ) {
                                            $package = $subscr_arr[0];
                                    }
                            }
                        }
		}
               
		$t_id = isset($field['term_id']) ? $field['term_id'] : 0;
		$term_meta = get_option( "taxonomy_term_$t_id" );
		
		if ( isset( $term_meta['cf_settings'] ) && count( $term_meta['cf_settings'] ) > 0 ) {			
			if ( isset( $term_meta['cf_settings'][ $field['slug'] ]['packages'][ $package ] ) && $term_meta['cf_settings'][ $field['slug'] ]['packages'][ $package ] ) {
				return 1;
			}
		}
	}
	return 0;
}

function bello_listing_is_featured( $listing ) {

	$listing_package = bello_get_listing_package( $listing->ID );
	
        $featured = boldthemes_rwmb_meta( 'boldthemes_theme_featured_listing', array(), $listing->ID );
        if ( $featured == 'yes' ) {
		return 1;
	} else if ( $featured == 'no' ) {
		return 0;
	}
        
	$featured = boldthemes_rwmb_meta( 'boldthemes_theme_listing-featured_listing', array(), $listing->ID );
        
	if ( $featured == 'yes' ) {
		return 1;
	} else if ( $featured == 'no' ) {
		return 0;
	}

	if ( $listing_package['saved_featured'] ) {
		return 1;
	}
	return 0;
}

// show search field
function bello_show_search_field( $field ) {
	require "fields/front_end_templates/search/{$field['type']}.php";
}

// CUSTOM FIELDS

add_action( 'init', 'bello_custom_fields', 1 );
function bello_custom_fields() {
    require 'fields/social.php';
    require 'fields/working_time.php';
    require 'fields/opentable.php';
    require 'fields/resurva.php';
    require 'fields/timekit.php';
    require 'fields/phone.php';
    require 'fields/region.php';
    require 'fields/price.php';
    require 'fields/tag.php';
}

//
// MY ACCOUNT LISTING TAB
//

require_once( 'woocommerce_listing_endpoint.php' );

//
// WIDGETS
//

// BANNER WIDGET
if ( ! class_exists( 'BT_Banner_Widget' ) ) {
	// BANNER
	class BT_Banner_Widget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'bt_banner_widget', // Base ID
				__( 'BT Banner', 'bt_plugin' ), // Name
				array( 
					'description' => __( 'Banner widget designed to be used in header, sidebar or Single Listing.', 'bt_plugin' )
				) 
			);
		}

		public function widget( $args, $instance ) {

			if (  $instance['code'] != '' || $instance['title'] != '' ) {			
				echo $args['before_widget'];

				if ( ! empty( $instance['title'] ) ) {
					echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
				}

				$code		= !empty( $instance['code'])	 ? base64_encode( $instance['code']) : '';
				$el_class	= !empty( $instance['el_class']) ? $instance['el_class'] : '';

				$class = array( 'btBanner' );

				$class_attr = implode( ' ', $class );			
				if (  $el_class != '' ) {
					$class_attr = $class_attr . ' ' . $el_class;
				}

				echo '<div class="' .  $class_attr . '">';
					echo  base64_decode(  $code  );
				echo  '</div>';

				echo $args['after_widget'];
			}
		}

		public function form( $instance ) {
			$title		= ! empty( $instance['title'] ) ? $instance['title'] : '';
			$el_class	= ! empty( $instance['el_class'] ) ? $instance['el_class'] : '';
			$code		= ! empty( $instance['code'] ) ? $instance['code'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'el_class' ) ); ?>"><?php _e( 'Custom class:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'el_class' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'el_class' ) ); ?>" type="text" value="<?php echo esc_attr( $el_class ); ?>">
			</p>			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'code' ) ); ?>"><?php _e( 'Banner code:', 'bt_plugin' ); ?></label> 
				<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'code' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'code' ) ); ?>" ><?php echo $code; ?></textarea>
			</p>
			<p class="bt-admin-description bt-background-description bt-link-underline-bold"><span class="dashicons dashicons-editor-help"></span> <?php _e( 'For more help with this widget, please <a href="http://documentation.bold-themes.com/bello/widgets/#bt-banner" target="_blank">click here</a>.','bt_plugin' ); ?></p>
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['el_class'] = ( ! empty( $new_instance['el_class'] ) ) ? strip_tags( $new_instance['el_class'] ) : '';
			$instance['code'] = ( ! empty( $new_instance['code'] ) ) ? $new_instance['code'] : '';

			return $instance;
		}
	}	
}

// LISTING WIDGET
if ( ! class_exists( 'BT_Listing_Widget' ) ) {

	class BT_Listing_Widget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'bt_listing_widget', // Base ID
				__( 'BT Listing', 'bt_plugin' ), // Name
				array( 'description' => __( 'Widget designed to show all sorts of fields you define throught the categories.', 'bt_plugin' ) ) // Args
			);
		}

		public function widget( $args, $instance ) {                    
			$title          = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$slug           = ! empty( $instance['slug'] ) ? $instance['slug'] : '';
                        $position       = ! empty( $instance['position'] ) ? $instance['position'] : 'bt_widget_sidebar';
                        $in_content     = ! empty( $instance['content']) && $instance['content'] == 1 ? 1 : 0;
                       
                        if ( $slug == 'location' ){
                            $position = $position . '_map';
                        }
                        if ( $in_content == 0 ) {
                            $args['before_widget'] = '<div class="btBox widget_bt_listing_widget ' . $position . '">';
                            $args['after_widget'] = '</div>';
                        }

			$custom_fields = bello_get_listing_fields();                        

			$field_in_packages = 0;
			foreach( $custom_fields as $field ) {
                                
				if ( isset($field) ) {
					if ( $field['group'] == 'widget_' . $slug || $field['group'] == $slug ) {
						$field_in_packages = bello_field_in_packages( $field );
					}
				}
			}

			$show_widget = 0;
			foreach( $custom_fields as $field ) {
				if ( $field['group'] == 'widget_' . $slug || $field['group'] == $slug  ) {					
					if ( isset($field) ) {							
						if ( isset($field['name']) && isset($field['value']) ) {
							$show_widget = 1;
							break;
						}
					}
				}
			}

			if ( $slug == 'timekit'){
				$timekit =  boldthemes_rwmb_meta('boldthemes_theme_listing-timekit');
				if ( is_array($timekit) ){
					$show_widget = array_filter($timekit) ?  1 : 0;
				}
			}

			if ( $slug == 'opentable'){
				$opentable		= boldthemes_rwmb_meta('boldthemes_theme_listing-opentable');
				if ( !empty($opentable) ){
					$rid			= $opentable[0];
					$show_widget = $rid != '' ?  1 : 0;
				}				
			}
                        
                        if ( $slug == 'resurva'){
				$resurva		= boldthemes_rwmb_meta('boldthemes_theme_listing-resurva');
				if ( !empty($resurva) ){
					$rid			= $resurva[0];
					$show_widget = $rid != '' ?  1 : 0;
				}				
			}
                        
                        if ( $slug == 'working_time'){
                            $open_hours = bt_open_hours( get_the_ID() );
                            if ( $open_hours == 'closed' ){
                                $show_widget = 0;
                            }
                        }                 
                                              
			if ( $show_widget ) {                                                
				echo $args['before_widget'];
				if ( ! empty( $instance['title'] ) && $field_in_packages == 1 ) {
					echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
				}

				if ( ! empty( $instance['slug'] ) ) { 
					require( 'widgets/bt_listing_widget.php' );
				}

				echo $args['after_widget'];
			}
		}

		public function form( $instance ) {
			$title      = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$slug       = ! empty( $instance['slug'] ) ? $instance['slug'] : '';
                        $position   = ! empty( $instance['position'] ) ? $instance['position'] : 'bt_widget_sidebar';
                       
			?>	
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bt_plugin' ); ?></label> 
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'slug' ) ); ?>"><?php _e( 'Slug:', 'bt_plugin' ); ?></label> 
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'slug' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slug' ) ); ?>" type="text" value="<?php echo esc_attr( $slug ); ?>">
				</p>
                                <p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'position' ) ); ?>"><?php _e( 'Responsive Position:', 'bt_plugin' ); ?></label> 
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'position' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'position' ) ); ?>">
					<option value="bt_widget_sidebar" <?php if ( $position == 'bt_widget_sidebar'){ echo 'selected';}?>><?php _e( 'Sidebar', 'bt_plugin' ); ?></option>
					<option value="bt_widget_before" <?php if ( $position == 'bt_widget_before'){ echo 'selected';}?>><?php _e( 'Before Content', 'bt_plugin' ); ?></option>
					<option value="bt_widget_after" <?php if ( $position == 'bt_widget_after'){ echo 'selected';}?>><?php _e( 'After Content', 'bt_plugin' ); ?></option>					
				</select>
				<p class="bt-admin-description bt-background-description bt-link-underline-bold"><span class="dashicons dashicons-editor-help"></span> <?php _e( 'For more help with this widget, please <a href="http://documentation.bold-themes.com/bello/widgets/#bt-listing" target="_blank">click here</a>.','bt_plugin' ); ?></p>
			</p>
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['slug'] = ( ! empty( $new_instance['slug'] ) ) ? strip_tags( $new_instance['slug'] ) : '';
			$instance['slug'] = str_replace( ' ', '_', $instance['slug'] );
                        $instance['position'] = ( ! empty( $new_instance['position'] ) ) ? strip_tags( $new_instance['position'] ) : 'sidebar';
			return $instance;
		}
	}	
}

// USER WIDGET
if ( ! class_exists( 'BT_User_Widget' ) ) {

	class BT_User_Widget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'bt_user_widget', // Base ID
				__( 'BT User', 'bt_plugin' ), // Name
				array( 'description' => __( 'Widged designed to be used in header with Login/Register button when not signed in and My Account and Add a new listing when logged in.', 'bt_plugin' ) ) // Args
			);
		}

		public function widget( $args, $instance ) {		

			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}

			$user = wp_get_current_user();
			$logged_in = false;
			if ( in_array( 'customer', $user->roles ) || in_array( 'shop_manager', $user->roles ) || in_array( 'administrator', $user->roles ) ) {
				$logged_in = true;
			}
	
			$my_account_permalink = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		
			require( 'widgets/bt_user_widget.php' );

			echo $args['after_widget'];

		}

		public function form( $instance ) {			
			?>
			<p class="bt-admin-description bt-background-description bt-link-underline-bold"><span class="dashicons dashicons-editor-help"></span> <?php _e( 'For more help with this widget, please <a href="//documentation.bold-themes.com/bello/widgets/#bt-user" target="_blank">click here</a>.','bt_plugin' ); ?></p>
			
			<?php
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();

			return $instance;
		}
	}	
}

// CLAIM WIDGET
if ( ! class_exists( 'BT_Claim_Widget' ) ) {
	
	class BT_Claim_Widget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'bt_claim_widget', // Base ID
				__( 'BT Claim', 'bt_plugin' ), // Name
				array( 
					'description' => __( 'Widget used to show Claim this business button.', 'bt_plugin' )
				) 
			);
		}

		public function widget( $args, $instance ) {
			if (  $instance['button_text'] != '' ) {                            
                                $logged         = 0;
                                $user_claimed   = 0;
                                if ( is_user_logged_in() ) {
                                    $logged = 1;
                                    $authorID = get_the_author_meta( 'ID' );     
                                    if ( get_current_user_id() == $authorID ){
                                        $user_claimed   = 2; //logged user is author of the listing
                                    } else{  
                                        $user_claimed   = bt_get_user_claim(get_current_user_id(), get_the_ID());
                                    }
                                }
                                if ( $user_claimed != 2 ) {
                                    echo $args['before_widget'];
                                    if ( $user_claimed == 0 ) {                                    
                                        $button_text    = !empty( $instance['button_text'])	 ?  $instance['button_text'] : '';
                                        $button_url     = '#';
                                        echo '<div class="widget_button_wrapper">';
                                                echo '<div class="bt_bb_button bt_bb_icon_position_left bt_bb_color_scheme_6 bt_bb_style_filled bt_bb_size_normal bt_bb_width_full bt_bb_shape_inherit bt_bb_align_inherit">';
                                                        echo '<a href="'.$button_url.'" class="bt_bb_link bt_bb_link_claim" data-logged="' . $logged . '"><span class="bt_bb_button_text">'.$button_text.'</span><span data-ico-fontawesome="&#xf0b1;" class="bt_bb_icon_holder"></span></a>';
                                                echo '</div>';
                                        echo '</div>';

                                    }else{
                                        if ( is_user_logged_in() ) {
                                            echo __( '<span class="bt_claim_pending">Your claim for this listing is pending.</span>', 'bt_plugin' );
                                        }
                                    }
                                    echo $args['after_widget'];
                                }
			}
		}

		public function form( $instance ) {
			$button_text	= ! empty( $instance['button_text'] ) ? $instance['button_text'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php _e( 'Custom text:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>">
			</p>
			<p class="bt-admin-description bt-background-description bt-link-underline-bold"><span class="dashicons dashicons-editor-help"></span> <?php _e( 'For more help with this widget, please <a href="http://documentation.bold-themes.com/bello/widgets/#bt-claim" target="_blank">click here</a>.','bt_plugin' ); ?></p>
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['button_text']	= ( ! empty( $new_instance['button_text'] ) ) ? strip_tags( $new_instance['button_text'] ) : '';

			return $instance;
		}
	}	
}

// LISTING FORM WIDGET
if ( ! class_exists( 'BT_Listing_Form_Widget' ) ) {

	class BT_Listing_Form_Widget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'bt_listing_form_widget', // Base ID
				__( 'BT Listing Form', 'bt_plugin' ), // Name
				array( 'description' => __( 'Widget designed to be used for a Contact form, so that visitors can contact directly with Listing Business owner.', 'bt_plugin' ) ) // Args
			);
		}

		public function widget( $args, $instance ) {
			$custom_fields = bello_get_listing_fields();
			$field_in_packages = 0;
			foreach( $custom_fields as $field ) {
				if ( isset($field) ) {
					if ( $field['group'] == 'widget_contact_form' ) {
						$field_in_packages = bello_field_in_packages( $field );
					}
				}
			}

			$contact_form_email	= isset($custom_fields["contact_form_email"]["value"]) ? $custom_fields["contact_form_email"]["value"][0] : '';
			
			if ( $contact_form_email != '') {

				$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

				echo $args['before_widget'];

				if ( ! empty( $instance['title'] ) && $field_in_packages == 1 ) {
					echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
				}

				$field = $custom_fields["contact_form_email"];
				bello_show_field( $field );
				
				echo $args['after_widget'];			
			
			}

		}

		public function form( $instance ) {			
			$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
			?>	
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bt_plugin' ); ?></label> 
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
				</p>
				<p class="bt-admin-description bt-background-description bt-link-underline-bold"><span class="dashicons dashicons-editor-help"></span> <?php _e( 'For more help with this widget, please <a href="http://documentation.bold-themes.com/bello/widgets/#bt-listing-form" target="_blank">click here</a>.','bt_plugin' ); ?></p>
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			return $instance;
		}
	}	
}

// OPENTABLE WIDGET
if ( ! class_exists( 'BT_Opentable_Widget' ) ) {
	class BT_Opentable_Widget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'bt_opentable_widget', // Base ID
				__( 'BT OpenTable', 'bt_plugin' ), // Name
				array( 
					'description' => __( 'OpenTable Widget designed to be used with OpenTable booking website.', 'bt_plugin' )
				) 
			);
		}

		public function widget( $args, $instance ) {
			
			if (  $instance['rid'] != '' ) {
				
				echo $args['before_widget'];
				if ( ! empty( $instance['title'] ) ) {
					echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
				}

				$rid			= !empty( $instance['rid'])	 ?  $instance['rid'] : '';
				$show_labels	= !empty( $instance['show_labels'])	 ?  $instance['show_labels'] : '';
				$show_icons		= !empty( $instance['show_icons'])	 ?  $instance['show_icons'] : '';
				$orientation	= 'btVerticalOrientation';
				$domain_ext		= !empty( $instance['domain_ext'])	 ?  $instance['domain_ext'] : '';

				$el_style = '';
				$el_class = '';

				$date_format = 'MM/DD/YYYY';
				$style_attr = '';
				if ( $el_style != '' ) {
					$style_attr = ' style="' . $el_style . '"';
				}
				
				$el_class .= $orientation;
				
				if ( $show_icons != '' ) {
					$el_class .= ' btShowIcons';
				}

				require( 'widgets/bt_bb_open_table_reservation_widget.php' );

				echo $args['after_widget'];
			}
		}

		public function form( $instance ) {
			$title			= ! empty( $instance['title'] ) ? $instance['title'] : '';
			$rid			= ! empty( $instance['rid'] ) ? $instance['rid'] : '';
			$show_labels	= ! empty( $instance['show_labels'] ) ? $instance['show_labels'] : '';
			$show_icons		= ! empty( $instance['show_icons'] ) ? $instance['show_icons'] : '';
			$domain_ext		= ! empty( $instance['domain_ext'] ) ? $instance['domain_ext'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'rid' ) ); ?>"><?php _e( 'OpenTable Restaurant ID:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'rid' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'rid' ) ); ?>" type="text" value="<?php echo esc_attr( $rid ); ?>">
			</p>			
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['show_labels'], 'on' ); ?> id="<?php echo $this->get_field_id('show_labels'); ?>" name="<?php echo $this->get_field_name('show_labels'); ?>" /> 
				<label for="<?php echo $this->get_field_id('show_labels'); ?>"><?php _e( 'Show labels', 'bt_plugin' ); ?></label>
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['show_icons'], 'on' ); ?> id="<?php echo $this->get_field_id('show_icons'); ?>" name="<?php echo $this->get_field_name('show_icons'); ?>" /> 
				<label for="<?php echo $this->get_field_id('show_icons'); ?>"><?php _e( 'Show icons', 'bt_plugin' ); ?></label>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'domain_ext' ) ); ?>"><?php _e( 'Country:', 'bt_plugin' ); ?></label> 
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'domain_ext' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'domain_ext' ) ); ?>">
					<option value=""></option>;
					<?php
					$domain_ext_arr = array("Global / U.S." => "com", "United Kingdom" => "co.uk", "Japan" => "jp", "Germany" => "de", "Mexico" => "com.mx");
					foreach( $domain_ext_arr as $key => $value ) {
						if ( $value == $domain_ext ) {
							echo '<option value="' . $value . '" selected>' . $key . '</option>';
						} else {
							echo '<option value="' . $value . '">' . $key . '</option>';
						}
					}
					?>
				</select>
			</p>
			<p class="bt-admin-description bt-background-description bt-link-underline-bold"><span class="dashicons dashicons-editor-help"></span> <?php _e( 'For more help with this widget, please <a href="http://documentation.bold-themes.com/bello/widgets/#bt-opentable" target="_blank">click here</a>.','bt_plugin' ); ?></p>
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();

			$instance['title']			= ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['rid']			= ! empty( $new_instance['rid'] ) ? strip_tags($new_instance['rid']) : '';
			$instance['show_labels']	= $new_instance['show_labels'];
			$instance['show_icons']		= $new_instance['show_icons'];
			$instance['domain_ext']		= ! empty( $new_instance['domain_ext'] ) ? strip_tags($new_instance['domain_ext']) : '';

			return $instance;
		}
	}	
}

// RESURVA WIDGET
if ( ! class_exists( 'BT_Resurva_Widget' ) ) {
	class BT_Resurva_Widget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'bt_resurva_widget', // Base ID
				__( 'BT Resurva', 'bt_plugin' ), // Name
				array( 
					'description' => __( 'Resurva Widget designed to be used with Resurva booking website.', 'bt_plugin' )
				) 
			);
		}

		public function widget( $args, $instance ) {

			if ( ! empty( $instance['resurva_url'] ) ) {

				echo $args['before_widget'];
				if ( ! empty( $instance['title'] ) ) {
					echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
				}

				$resurva = untrailingslashit( $instance['resurva_url'] );

				ob_start();
				?>
					<iframe src="<?php echo esc_url( $resurva ); ?>/book?embedded=true" name="resurva-frame" frameborder="0" width="450" height="450" style="max-width:100%"></iframe>
				<?php
				$content = ob_get_clean();

				echo '<div class="btOpentableWidgetContent">';
					echo $content;
				echo '</div>';

				echo $args['after_widget'];

			}

		}

		public function form( $instance ) {
			$title			= ! empty( $instance['title'] ) ? $instance['title'] : '';
			$resurva_url	= ! empty( $instance['resurva_url'] ) ? $instance['resurva_url'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'resurva_url' ) ); ?>"><?php _e( 'Resurva URL:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'resurva_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'resurva_url' ) ); ?>" type="text" value="<?php echo esc_attr( $resurva_url ); ?>">
			</p>
			<p class="bt-admin-description bt-background-description bt-link-underline-bold"><span class="dashicons dashicons-editor-help"></span> <?php _e( 'For more help with this widget, please <a href="http://documentation.bold-themes.com/bello/widgets/#bt-resurva" target="_blank">click here</a>.','bt_plugin' ); ?></p>
			<?php
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();

			$instance['title']			= ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['resurva_url']	= ( ! empty( $new_instance['resurva_url'] ) ) ? strip_tags( $new_instance['resurva_url'] ) : '';

			return $instance;
		}

	}
}


// HEADER SEARCH WIDGET
if ( ! class_exists( 'BT_Header_Search_Widget' ) ) {
	class BT_Header_Search_Widget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'bt_header_search_widget', // Base ID
				__( 'BT Header Search', 'bt_plugin' ), // Name
				array( 
					'description' => __( 'Widget with a Search Button that has a custom link - mainly used to redirect user to advanced search.', 'bt_plugin' )
				) 
			);
		}

		public function widget( $args, $instance ) {                    
			if ( ! empty( $instance['url'] ) ) {
				echo $args['before_widget'];
				$url = untrailingslashit( $instance['url'] );
				echo '<div class="btAdvancedSearch">';
                                        echo '<div class="bt_bb_icon">';
                                             echo '<a href="' . $url . '" target="_self" data-ico-fa="&#xf002;" class="bt_bb_icon_holder"></a>';
                                        echo '</div>';
                                echo '</div>';
				echo $args['after_widget'];
			}
		}

		public function form( $instance ) {
			$url	= ! empty( $instance['url'] ) ? $instance['url'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"><?php _e( 'URL:', 'bt_plugin' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>" type="text" value="<?php echo esc_attr( $url ); ?>">
			</p>
			<p class="bt-admin-description bt-background-description bt-link-underline-bold"><span class="dashicons dashicons-editor-help"></span> <?php _e( 'For more help with this widget, please <a href="http://documentation.bold-themes.com/bello/widgets/#bt-header-search" target="_blank">click here</a>.','bt_plugin' ); ?></p>
			<?php
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['url']    = ( ! empty( $new_instance['url'] ) ) ? strip_tags( $new_instance['url'] ) : '';
			return $instance;
		}
                
               

	}
}

/* Add dynamic_sidebar_params filter for BT Header Search  */
add_filter('dynamic_sidebar_params','bt_header_search_widget');
function bt_header_search_widget($params) {     
     if( isset($params[0]['id']) && $params[0]['id'] == 'header_menu_widgets' ){
         if ( isset($params[0]['widget_name']) && $params[0]['widget_name'] == 'BT Header Search' ) {
             $class = 'class="btTopBox btIconWidget '; 
             $params[0]['before_widget'] = str_replace('class="btTopBox', $class, $params[0]['before_widget']);
         }
     }
     
     return $params;
}

function bello_register_listing_widgets() {
	register_widget( 'BT_Banner_Widget' );
	register_widget( 'BT_Listing_Widget' );
	register_widget( 'BT_User_Widget' );
	register_widget( 'BT_Claim_Widget' );
	register_widget( 'BT_Listing_Form_Widget' );
	register_widget( 'BT_Opentable_Widget' );
	register_widget( 'BT_Resurva_Widget' );
        register_widget( 'BT_Header_Search_Widget' );
}

add_action( 'widgets_init', 'bello_register_listing_widgets' );


/************ COMMENT FORM ****************/
/* https://www.smashingmagazine.com/2012/05/adding-custom-fields-in-wordpress-comment-form/
/*
/******************************************/

add_action( 'comment_form_logged_in_after', 'bt_additional_fields_logged_in_after' );
	function bt_additional_fields_logged_in_after () {
                if ( function_exists('boldthemes_get_option') ) {
                    $current_post_type = get_post_type();
                    if (bt_is_forbiden_current_post_type($current_post_type))
                           return;

                     $listing_show_rating	= boldthemes_get_option( 'listing_show_rating' );
                     if ( $listing_show_rating ) {
                       echo '<div class="review-by">'.
                                     '<label>'. __('Review Rating *','bt_plugin') . '</label>
                                     <span class="commentratingbox">';
                                             for( $i=1; $i <= 5; $i++ )
                                             echo '<span class="commentrating"><input type="radio" name="rating" id="rating'. $i .'" value="'. $i .'"/><label for="rating'. $i .'">'. $i .'</label></span>';
                                      echo'</span>';
                        echo'</div>';
                     }

                      echo '<div class="pcItem">'.
                     '<label for="title"></label>'.
                     '<p><input id="title" name="title" type="text" size="30"  tabindex="1" aria-required="true" placeholder="' . __( 'Title of the review *','bt_plugin' ) . '"/></p></div>';

                      echo '<div class="pcItem btComment"><label for="comment"></label><p><textarea id="comment" name="comment" tabindex="4" cols="30" rows="8" placeholder="' . __( 'Your review *','bt_plugin' ) . '"
                      aria-required="true">' .'</textarea></p></div>';
                }
	}

add_action( 'comment_form_before_fields', 'bt_additional_fields' );
	function bt_additional_fields () {
		  if ( function_exists('boldthemes_get_option') ) {
                    $current_post_type = get_post_type();
                    if (bt_is_forbiden_current_post_type($current_post_type))
                          return;

                    $listing_show_rating	= boldthemes_get_option( 'listing_show_rating' );
                    if ( $listing_show_rating ) {
                      echo '<div class="review-by">'.
                                    '<label>'. __('Review Rating *','bt_plugin') . '</label>
                                    <span class="commentratingbox">';
                                            for( $i=1; $i <= 5; $i++ )
                                            echo '<span class="commentrating"><input type="radio" name="rating" id="rating'. $i .'" value="'. $i .'"/><label for="rating'. $i .'">'. $i .'</label></span>';
                                     echo'</span>';
                       echo'</div>';
                    }

                     echo '<div class="pcItem">'.
                    '<label for="title"></label>'.
                    '<p><input id="title" name="title" type="text" size="30"  tabindex="1" aria-required="true" placeholder="' . __( 'Title of the review *','bt_plugin' ) . '"/></p></div>';
                  }
	}

add_action( 'comment_post', 'bt_save_comment_meta_data' );
	function bt_save_comment_meta_data( $comment_id ) {
		
		 $current_post_type = get_post_type( get_comment($comment_id)->comment_post_ID );
		 if (bt_is_forbiden_current_post_type($current_post_type))
			return;

		  if ( ( isset( $_POST['title'] ) ) && ( $_POST['title'] != '') )
		  $title = wp_filter_nohtml_kses($_POST['title']);
		  add_comment_meta( $comment_id, 'title', $title );

		  if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != '') )
		  $rating = wp_filter_nohtml_kses($_POST['rating']);
		  add_comment_meta( $comment_id, 'rating', $rating );
	}

add_filter( 'preprocess_comment', 'bt_verify_comment_meta_data' );
	function bt_verify_comment_meta_data( $commentdata ) {
             if ( function_exists('boldthemes_get_option') ) {
		 $current_post_type = get_post_type( $commentdata["comment_post_ID"] );
		 if (bt_is_forbiden_current_post_type($current_post_type))
			 return $commentdata;

                 
                  $listing_show_rating	= boldthemes_get_option( 'listing_show_rating' );                  
		  if ( $listing_show_rating && !isset( $_POST['rating'] ) )
		  wp_die( __( 'Error: You did not add a rating. Hit the Back button on your Web browser and resubmit your comment with a rating.','bt_plugin' ) );
             }   
             return $commentdata;            
	}

add_filter( 'comment_text', 'bt_modify_comment');
	function bt_modify_comment( $text ){

		 $current_post_type = get_post_type();
		 if (bt_is_forbiden_current_post_type($current_post_type))
			return $text;

		  if( $commenttitle = get_comment_meta( get_comment_ID(), 'title', true ) ) {
			$commenttitle = '<strong>' . esc_attr( $commenttitle ) . '</strong><br/>';
			$text = $commenttitle . $text;
		  } 

		  if( $commentrating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {
				$commentrating = '<p class="comment-rating">Rating: <strong>'. $commentrating .' / 5</strong></p>';
				$text = $text . $commentrating;
				return $text;
		  } else {
				return $text;
		  }
	}

add_action( 'add_meta_boxes_comment', 'bt_extend_comment_add_meta_box' );
	function bt_extend_comment_add_meta_box() {
                 $comment_id = get_comment_ID();
                 $comment = get_comment( $comment_id );
		 $current_post_type = get_post_type( $comment->comment_post_ID );
		 if (bt_is_forbiden_current_post_type($current_post_type))
			return;

		 add_meta_box( 'title', __( 'Extended Comment','bt_plugin' ), 'bt_extend_comment_meta_box', 'comment', 'normal', 'high' );
		 remove_meta_box( 'woocommerce-rating',  'comment', 'normal');
	}

	function bt_extend_comment_meta_box ( $comment ) {

		$current_post_type = get_post_type( $comment->comment_post_ID );
		if (bt_is_forbiden_current_post_type($current_post_type))
			return;

		$title	= get_comment_meta( $comment->comment_ID, 'title', true );
		$rating = get_comment_meta( $comment->comment_ID, 'rating', true );
		wp_nonce_field( 'extend_comment_update', 'extend_comment_update', false );
		?>
		<table class="form-table editcomment" style="width: 100%;">
			<tbody>
			<tr>
				<td class="first" style="width: 10%;"><label for="title"><?php _e( 'Title' ); ?>:</label></td>
				<td><input type="text" name="title" value="<?php echo esc_attr( $title ); ?>" id="name" style="width: 98%;"></td>
			</tr>
			<tr>
				<td class="first" style="width: 10%;"><label for="rating"><?php _e( 'Rating: ' ); ?>:</label></td>
				<td>
					 <span class="commentratingbox">
						  <?php for( $i=1; $i <= 5; $i++ ) {
							echo '<span class="commentrating"><input type="radio" name="rating" id="rating" value="'. $i .'"';
							if ( $rating == $i ) echo ' checked="checked"';
								echo ' />'. $i .' </span>';
							}
						  ?>
					  </span>
				</td>
			</tr>
			</tbody>
		</table>
		<?php
	}


add_action( 'edit_comment', 'bt_extend_comment_edit_metafields' );
	function bt_extend_comment_edit_metafields( $comment_id ) {

		 $current_post_type = get_post_type( get_comment($comment_id)->comment_post_ID );
		 if (bt_is_forbiden_current_post_type($current_post_type))
			return;

		  if( ! isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ) return;

		  if ( ( isset( $_POST['title'] ) ) && ( $_POST['title'] != '') ){
			  $title = wp_filter_nohtml_kses($_POST['title']);
			  update_comment_meta( $comment_id, 'title', $title );
		  } else {
			  delete_comment_meta( $comment_id, 'title');
		  }

		  if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != '') ){
			  $rating = wp_filter_nohtml_kses($_POST['rating']);
			  update_comment_meta( $comment_id, 'rating', $rating );
		  } else {
			  delete_comment_meta( $comment_id, 'rating');
		  }
	}

/**
 * Remove the original comment field because it's added to the default fields in /comments.php
 */
add_filter( 'comment_form_defaults', 'bt_remove_comment_form_defaults', 10, 1 );
	function bt_remove_comment_form_defaults( $defaults ) {
		if ( isset( $defaults[ 'comment_field' ] ) ) {
			$defaults[ 'comment_field' ] = '';
		}
		return $defaults;
	}



function bt_is_forbiden_current_post_type($current_post_type) {
	$current_post_type = !empty( $current_post_type ) ? $current_post_type : "";
	$approved_post_types = array( 'listing' );
	if ( in_array( $current_post_type , $approved_post_types) )
		return 0;
	else
		return 1;
}

/************ /COMMENT FORM ****************/

function bt_get_last_week_dates($current_day)
{
    $lastWeek = array();   
    $prevFirstDay = strtotime( "today", strtotime("-$current_day day") );
    $prevDay = date("Y-m-d",$prevFirstDay);
    for($i=0; $i<8; $i++)
    {
        $d = date("Y-m-d", strtotime( $prevDay." + $i day") );
        $lastWeek[]=$d;
    }   
    return $lastWeek;
}

/**
 * Return open hour from working times by days and days in week
 */
function bt_open_hours(  $post_id ) {        
	$working_times	= boldthemes_rwmb_meta('boldthemes_theme_listing-working_time', array(), $post_id);
	$open_hours = '';
	
	if ( !empty( $working_times ) ) {
                $closed_all = 1;                
                foreach ( $working_times as $working_time ){ 
                      if ( 
                            (isset($working_time["start"]) && $working_time["start"] == '') &&
                            (isset($working_time["end"]) && $working_time["end"] == '') &&
                            (isset($working_time["start2"]) && $working_time["start2"] == '') &&
                            (isset($working_time["end2"]) && $working_time["end2"] == '') &&
                            (!isset($working_time["all"]))
                            ){
                            $closed_all = 1;
                      }else{
                          $closed_all = 0;
                          break;
                      }
                }
                if ( $closed_all == 1 ){
                    $open_hours = 'closed';
                    return $open_hours;
                }
                
		$listing_search_time_format = get_option( 'time_format' ) != '' ?  get_option( 'time_format' ) : 'g:i A';      

		$gmtOffset = get_option('gmt_offset');
                $now	   = date('Y-m-d H:i', strtotime($gmtOffset . ' hours'));
                
                $day_name       = bt_day_name();
		$current_now	= strtotime( date('l', strtotime('now')) );
		$current_day	= 0;
               
		foreach ( $day_name as $key => $value ){                    
			if ( $value == $current_now ){
				$current_day = $key;
				break;
			}			
		}
                
                $last_week_dates  = bt_get_last_week_dates($current_day); 
                
                $dd = 0;
                $open_all = 1;
                foreach ( $working_times as &$working_time){ 
                    if ( !isset($working_time["all"]) ) {
                        $open_all = 0;
                        break;
                    }
                }                
                if ( $open_all == 1 ){
                    $open_hours = '';
                    return $open_hours;
                }  
                
                $dd = 0;                
                foreach ( $working_times as &$working_time){ 
                    if ( isset($working_time["all"]) ) {
                        if ( $working_time["all"] == 1 ) {  
                            $working_time['start'] = $last_week_dates[$dd] . ' 00:00';
                            $working_time['end']   = $last_week_dates[$dd] . ' 24:00';
                            $working_time['start2'] = '';
                            $working_time['end2']   = '';
                            $dd++;
                            continue;
                        }
                    }                   
                    
                    $working_time['start'] = $last_week_dates[$dd] . ' ' . $working_time['start'];
                    if ( $working_time["start2"]	!= "" ) {
                        $working_time['start2'] = $last_week_dates[$dd] . ' ' . $working_time['start2'];
                    }
                   
                    $working_time_start	=  date('H:i', strtotime($working_time["start"]));
                    $working_time_end	=  date('H:i', strtotime($working_time["end"]));
                    if ( $working_time_end < $working_time_start ){
                        $working_time['end'] = $last_week_dates[$dd + 1] . ' ' . $working_time['end'];
                    }else{
                        $working_time['end'] = $last_week_dates[$dd] . ' ' . $working_time['end'];
                    }
                    
                    if ( $working_time["end2"]	!= "" ) {
                        $working_time_start2	=  date('H:i', strtotime($working_time["start2"]));
                        $working_time_end2	=  date('H:i', strtotime($working_time["end2"]));
                        if ( $working_time_end2 < $working_time_start2 ){
                            $working_time['end2'] = $last_week_dates[$dd + 1] . ' ' . $working_time['end2'];
                        }else{
                            $working_time['end2'] = $last_week_dates[$dd] . ' ' . $working_time['end2'];
                        }
                    }
                    $dd++;
                    
                }
                unset($working_time);
               
                
                $now_open = 0;
                foreach ( $working_times as $working_time){
                     $start_open    = date('Y-m-d H:i', strtotime($working_time["start"] . ' + 0 day')); 
                     $end_open      = date('Y-m-d H:i', strtotime($working_time["end"] . ' + 0 day')); 
                     
                     if ( $now > $start_open && $now < $end_open){
                         $now_open = 1;
                         break;
                     }
                     if ( $working_time["start2"] != "" && $working_time["end2"] != "" ) {
                            $start2_open    = date('Y-m-d H:i', strtotime($working_time["start2"] . ' + 0 day'));  
                            $end2_open      = date('Y-m-d H:i', strtotime($working_time["end2"] . ' + 0 day'));                          
                            if ( $now > $start2_open && $now < $end2_open){
                                 $now_open = 1;
                                 break;
                            }
                     }
                }
                                
		$current = $working_times[$current_day]; 
                $start =  '';
                if ( $current["start"]	!= "" ) {
                        $start	=  date('Y-m-d H:i', strtotime($current["start"]));
                }
                $end	=  '';
                if ( $current["end"]	!= "" ) {
                        $end	=  date('Y-m-d H:i', strtotime($current["end"]));
                }  
                $start2  =  '';
                if ( $current["start2"]	!= "" ) {
                        $start2	=  date('Y-m-d H:i', strtotime($current["start2"]));
                }
                $end2	=  '';
                if ( $current["end2"]	!= "" ) {
                        $end2	=  date('Y-m-d H:i', strtotime($current["end2"]));
                }
               
                if ( $now_open == 0){ 
                        if ( $now < $start ){
                                $open_hours =  date($listing_search_time_format, strtotime($current["start"]));
                        }else if ( $now > $start && $now < $start2 ){
                                $open_hours = date($listing_search_time_format, strtotime($current["start2"]));
                        }else{
                                $tomorrow = isset($working_times[$current_day + 1]) ? $working_times[$current_day + 1] : $working_times[0];
                                if ( $tomorrow["start"] != '' ) {
                                    if ( strlen($tomorrow["start"]) < 5 ){ $tomorrow["start"] = "0".$tomorrow["start"]; }
                                    $open_hours = date($listing_search_time_format, strtotime($tomorrow["start"]));
                                }else{
                                    $open_hours = 'closed';
                                    if ( isset($tomorrow["all"]) && $tomorrow["all"] == 1 ){
                                        $open_hours = '';
                                    }
                                }
                                
                        }
                }
	}        
        
	return $open_hours;
}

/**
 * Return open hour from working times by days and days in week
 */
function bt_open_hours_current_day(  $post_id ) {
    $listing_search_time_format = get_option( 'time_format' ) != '' ?  get_option( 'time_format' ) : 'g:i A';  
    $working_times	= boldthemes_rwmb_meta('boldthemes_theme_listing-working_time', array(), $post_id);
    $current_open_hours = array();
    if ( !empty( $working_times ) ) {
            $closed_all = 1;                
            foreach ( $working_times as $working_time ){ 
                   if ( 
                         (isset($working_time["start"]) && $working_time["start"] == '') &&
                         (isset($working_time["end"]) && $working_time["end"] == '') &&
                         (isset($working_time["start2"]) && $working_time["start2"] == '') &&
                         (isset($working_time["end2"]) && $working_time["end2"] == '') &&
                         (!isset($working_time["all"]))
                         ){
                         $closed_all = 1;
                   }else{
                       $closed_all = 0;
                       break;
                   }
             }
             if ( $closed_all == 1 ){
                 return array();
             }
                
        
            $day_name	= bt_day_name();

            $gmtOffset	= get_option('gmt_offset');
            $now		= date('H:i', strtotime($gmtOffset . ' hours'));
            if ( $now == '12:00 AM' ){ $now = '23:59';}
            if ( $now == '12:00 PM' ){ $now = '11:59';}

            $current_now	= strtotime( date('l', strtotime('now')) );
            $current_day	= 0;
            foreach ( $day_name as $key => $value ){
                    if ( $value == $current_now ){
                            $current_day = $key;
                            break;
                    }			
            }
            $current = $working_times[$current_day];

            $start =  '';
            if ( $current["start"]	!= "" ) {
                    if ( strlen($current["start"]) < 5 ){ $current["start"] = "0".$current["start"]; }
                    $start	=  date($listing_search_time_format, strtotime($current["start"]));
            }

            $end	=  '';
            if ( $current["end"]	!= "" ) {
                    if ( strlen($current["end"]) < 5 ){ $current["end"] = "0".$current["end"]; }
                    $end	=  date($listing_search_time_format, strtotime($current["end"]));
            }

            $start2 =  '';
            if ( $current["start2"]	!= "" ) {
                    if ( strlen($current["start2"]) < 5 ){ $current["start2"] = "0".$current["start2"]; }
                    $start2	=  date($listing_search_time_format, strtotime($current["start2"]));
            }

            $end2	=  '';
            if ( $current["end2"]	!= "" ) {
                    if ( strlen($current["end2"]) < 5 ){ $current["end2"] = "0".$current["end2"]; }
                    $end2	=  date($listing_search_time_format, strtotime($current["end2"]));
            }

            $all	=  '';
            if ( isset($current["all"]) ) {
                if ( $current["all"] == 1 ) {  
                        $start	= __( 'OPEN 24h', 'bt_plugin' );
                        $end	= '';
                        $start2	= '';
                        $end2	= '';
                        $all	=  '1';
                }
            }
            
            

            $current_open_hours = array( $start, $end, $start2, $end2, $all );
    }
    
    return $current_open_hours;
}

function bt_day_name() {
	$start_of_week = get_option( 'start_of_week' );
	$day_name = array( strtotime( 'Sunday' ), strtotime( 'Monday' ), strtotime( 'Tuesday' ), strtotime( 'Wednesday' ), strtotime( 'Thursday' ), strtotime( 'Friday' ), strtotime( 'Saturday' ) );		
	for ( $i = 0; $i < $start_of_week; $i++ ) {
		$item = array_shift( $day_name );
		array_push( $day_name, $item );
	}

	return $day_name;
}

function bt_get_distance($listing) {
	$custom_fields = bello_get_listing_fields( array( 'listing_id' => $listing->ID ) );
	if ( isset($custom_fields["location_position"]["value"]) ) {
		$location_position = explode(",",$custom_fields["location_position"]["value"][0]);											
	}
	$latitudeTo		= isset($location_position) ? $location_position[0] : '0';
	$longitudeTo	= isset($location_position) ? $location_position[1] : '0';

	$ip = $_SERVER['REMOTE_ADDR'];

	$new_arr[]= unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
	
	$latitudeFrom = $new_arr[0]['geoplugin_latitude'];
	$longitudeFrom = $new_arr[0]['geoplugin_longitude'];

	$earthRadius = 3959000;

        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
              pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);	
	
	return getDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo,  boldthemes_get_option( 'listing_search_distance_unit' ), 2);
}

function getDistance($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2) {
	$degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));

	switch($unit) {
		case 'km':
			$distance = $degrees * 111.13384; // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
			break;
		case 'mi':
			$distance = $degrees * 69.05482; // 1 degree = 69.05482 miles, based on the average diameter of the Earth (7,913.1 miles)
			break;
		case 'nmi':
			$distance =  $degrees * 59.97662; // 1 degree = 59.97662 nautic miles, based on the average diameter of the Earth (6,876.3 nautical miles)
	}
	return round($distance, $decimals);
}

function bt_get_distance_unit($distance_unit) {
	$ret = "miles";
	switch ($distance_unit)
	{
		case 'mi':		$ret = "miles";break;
		case 'km':		$ret = "kilometres";break;
		case 'nmi':		$ret = "nautical miles";break;
		default:		$ret = "miles";break;		
	}
	return $ret;
}

if ( ! function_exists( 'bt_mail' ) ) {
    function bt_mail( $to, $subject, $message, $headers = null ) {

        if ( !$headers ) {
            $from =  get_option('admin_email');
            if(!(isset($from) && is_email($from))) {		
                $sitename = strtolower( get_bloginfo( 'name' ) );
                if ( substr( $sitename, 0, 4 ) == 'www.' ) {
                        $sitename = substr( $sitename, 4 );					
                }
                $from = 'admin@'.$sitename; 
            }
            $sender = 'From: '.get_option('name').' <'.$from.'>' . "\r\n";

            $headers[] = 'MIME-Version: 1.0' . "\r\n";
            $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers[] = "X-Mailer: PHP \r\n";
            $headers[] = $sender;
        }
        $mail = wp_mail( $to, $subject, $message, $headers );
        return $mail;
    }
}

if ( ! function_exists( 'bt_get_current_page_url' ) ) {
    function bt_get_current_page_url() {
         global $wp;
         return add_query_arg( $_SERVER['QUERY_STRING'], '', home_url( $wp->request ) );       
    }
}

if ( ! function_exists( 'bt_current_page_url' ) ) {
    function bt_current_page_url() {
      echo bt_get_current_page_url();
    }
}

/* NEXTEND FACEBOOK CONNECT PLUGIN FIX: line 222  $u->id -> $u->ID */
remove_filter( 'get_avatar', 'new_fb_insert_avatar', 5, 5 );
add_filter('get_avatar', 'bt_new_fb_insert_avatar', 5, 5);
if ( ! function_exists( 'bt_new_fb_insert_avatar' ) ) {
    function bt_new_fb_insert_avatar($avatar = '', $id_or_email, $size = 96, $default = '', $alt = false) {
        
        $id = 0;
        if (is_numeric($id_or_email)) {
            $id = $id_or_email;
        } else if (is_string($id_or_email)) {
            $u  = get_user_by('email', $id_or_email);
            $id = $u->ID;
        } else if (is_object($id_or_email)) {
            $id = $id_or_email->user_id;
        }
        if ($id == 0) return $avatar;
        $pic = get_user_meta($id, 'fb_profile_picture', true);
        if (!$pic || $pic == '') return $avatar;
        $avatar = preg_replace('/src=("|\').*?("|\')/i', 'src=\'' . $pic . '\'', $avatar);

        return $avatar;
    }
}
/* /NEXTEND FACEBOOK CONNECT PLUGIN FIX */

if ( ! function_exists( 'bt_get_my_account_form' ) ) {
    function bt_get_my_account_form() {
        include( 'inc/my-account/includes/form-login.php' );
    }
}

if ( ! function_exists( 'bt_bb_contact_form_generate_response' ) ) {
    function bt_bb_contact_form_generate_response($type, $message, $rnd){
            //global $response;
            if($type == "success") $response = "<div class='success'>{$message}</div>";
            else $response = "<div class='error'>{$message}</div>";
            return $response;

    }
}

if ( ! function_exists( 'bello_allow_media_upload' ) ) {
    function bello_allow_media_upload( ) {
        $role = 'subscriber';        
        if( current_user_can($role) ) {
            $subscriber = get_role( $role );
            if ( !current_user_can('upload_files') ) {
                $subscriber->add_cap( 'upload_files' );
            }
        }
        $role = 'customer';        
        if( current_user_can($role) ) {
            $customer = get_role( $role );
            if ( !current_user_can('upload_files') ) {
                $customer->add_cap( 'upload_files' );
            }
        }
    } 
}
add_action('admin_init', 'bello_allow_media_upload');
add_action('init', 'bello_allow_media_upload');
  

function wp3344_map_meta_cap( $caps, $cap, $user_id, $args ){      
    if ( 'edit_post' == $cap ) { 
        $page_woocommerce_id    = get_option('woocommerce_myaccount_page_id');
        $page_my_account_slug   = get_option( 'listing_my_account_page_slug' );       
        $page_my_account_id     = boldthemes_get_id_by_slug($page_my_account_slug);
        
        $page_id  = 0;
        if ( $page_my_account_id > 0  ) {
            $page_id   = $page_my_account_id;
        }  else {            
            $page_id   = $page_woocommerce_id > 0 ? $page_woocommerce_id : 0;
        } 
        //$page_id = $args[0];
        if ( $page_id > 0 ) { 
            $post       = get_post( $page_id );
            $post_type  = get_post_type_object( $post->post_type );
            $caps       = array();
        }
    }
   
    
    return $caps;
}
add_filter( 'map_meta_cap', 'wp3344_map_meta_cap', 10, 4 );


/**
* Filter funstion 
* Show only uers files in media library, if user is not at roles: administrator, editor
* 
*/
function bt_show_current_user_attachments( $query ) {  
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    if (current_user_can('administrator')) {
        return $query;
    }
    
    //$user_id = get_current_user_id();
    if ( $user_id ) {
        $query['author'] = $user_id;
    }
    
    return $query;
}
add_filter( 'ajax_query_attachments_args', 'bt_show_current_user_attachments', 10, 1 );

/**
 * Returns page id by slug
 *
 * @return string
 */
if ( ! function_exists( 'boldthemes_get_id_by_slug' ) ) {
	function boldthemes_get_id_by_slug( $page_slug ) {            
		$page = get_posts(
			array(
				'name'      => $page_slug,
				'post_type' => 'page'
			)
		);
                
		if ( isset($page[0]->ID) ) {
			return $page[0]->ID;	
		} else {
			return null;
		}
		
	}
}

/**
 * My Listing Endpoint settings
 * Endpoint is active on plugin or theme activation
 * After listing endpoint change, It is necessary to re-save permalinks via Settings > Permalinks so that the new listing endpoint is active
 */
 
if ( ! function_exists( 'boldthemes_account_listing_endpoint' ) ) {
	function boldthemes_account_listing_endpoint() {                
		return 'bello-listing-endpoint';
	}
}

// define the wp_mail_failed callback 
function bt_action_wp_mail_failed($wp_error) 
{
    return error_log(print_r($wp_error, true));
}
          
// add the action 
add_action('wp_mail_failed', 'bt_action_wp_mail_failed', 10, 1);

// add all roles authors to author dropdown in wp-admin edit listing
add_filter('wp_dropdown_users', 'bt_all_users_to_dropdown');
function bt_all_users_to_dropdown($output)
{
    global $post;    
    if ( $post->post_type == 'listing' ) {
        $users = get_users(array(
            'orderby'   => 'display_name',
            'order'     => 'ASC'
        ));
        $output = "<select id=\"post_author_override\" name=\"post_author_override\" class=\"\">";
        foreach($users as $user)
        {
            $user_meta  = get_userdata( $user->ID );
            $user_roles = $user_meta->roles;
            $role = isset($user_roles[0]) ? '- ' . $user_roles[0] : '';
            $sel = ($post->post_author == $user->ID)?"selected='selected'":'';
            $output .= '<option value="'.$user->ID.'"'.$sel.'>'.$user->display_name.' ( ' .$user->user_login.' ) ' . $role .'</option>';
        }
        $output .= "</select>";
    }

    return $output;
}

if ( ! function_exists( 'bt_map_is_google' ) ) {
    function bt_map_is_google() {
        if ( function_exists( 'boldthemes_get_option' ) ) {
            $listing_search_map_type = boldthemes_get_option( 'listing_search_map_type' ) != '' ? boldthemes_get_option( 'listing_search_map_type' ) : 'google';
            if ( $listing_search_map_type != 'google' ){
                return 0;
            }
        }
        return 1;
    }
}

if ( ! function_exists( 'bt_map_is_osm' ) ) {
    function bt_map_is_osm() {
        if ( function_exists( 'boldthemes_get_option' ) ) {            
            $listing_search_map_type = boldthemes_get_option( 'listing_search_map_type' ) != '' ? boldthemes_get_option( 'listing_search_map_type' ) : 'google';
            if ( $listing_search_map_type == 'osm' ){
                return 1;
            }
        }
        return 0;
    }
}
if ( ! function_exists( 'bt_map_is_leaflet' ) ) {
    function bt_map_is_leaflet() {
        if ( function_exists( 'boldthemes_get_option' ) ) {
            $listing_search_map_type = boldthemes_get_option( 'listing_search_map_type' ) != '' ? boldthemes_get_option( 'listing_search_map_type' ) : 'google';
            if ( $listing_search_map_type == 'leaflet' ){
                return 1;
            }
        }
        return 0;
    }
}
if ( ! function_exists( 'bt_is_autocomplete' ) ) {
    function bt_is_autocomplete() {
        if ( function_exists( 'boldthemes_get_option' ) ) {
            $show_location_autocomplete  = boldthemes_get_option( 'listing_search_autocomplete' ) != '' ? boldthemes_get_option( 'listing_search_autocomplete' ) : false;
            if ( $show_location_autocomplete ){
                return 1;
            }  
        }
        return 0;
    }
}

if ( ! function_exists( 'bt_is_https' ) ) {
    function bt_is_https() {
        if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {   
             return 1;
        }
        return 0;
    }
}

if ( ! function_exists( 'boldthemes_custom_controls_customizer_tooltips' ) ) {
    function boldthemes_custom_controls_customizer_tooltips() {
        ?>
        <script type="text/javascript">
             jQuery(document).ready(function() {
                    wp.customize.bind('ready', function() {
                        wp.customize.control.each(function(ctrl, i) {
                            var tooltip_type = ctrl.container.find('.customize-control-tooltip');
                            var example_type = ctrl.container.find('.customize-control-example');
                            if(tooltip_type.length) {
                                var ctrl_id = ctrl.id;
                                var title = ctrl.container.find('.customize-control-title');
                                var tooltip = tooltip_type.text();
                                tooltip_type.remove();
                                title.append(' <i id="dashicons_'+ctrl_id+'" data-id="'+ctrl_id+'" class="dashicons dashicons-editor-help" style="vertical-align: text-bottom;" title="'+tooltip+'"></i>');
                            }
                        });
                        
                         $( ".dashicons-editor-help" ).click(function() {
                            var id = $( this ).data( 'id' );
                            $( '#' + id ).toggle("fast","linear");
                        });
                        
                    });
                });
        </script>
        <?php
    }
}
 
add_action('customize_controls_print_footer_scripts', 'boldthemes_custom_controls_customizer_tooltips');

if(has_action('nsl_register_new_user')) {
    add_action('nsl_register_new_user', function ($user_id) {	
            $user = new WP_User($user_id);
            $user->set_role('customer'); 
            wp_set_current_user( $user_id, $user->user_login );
            wp_set_auth_cookie( $user_id );
    });
}

function boldthemes_max_image_size( $file ) {
    
        $max_upload_size = wp_max_upload_size();
        if ( ! $max_upload_size ) {
                $max_upload_size = 0;
        }
        $limit = $max_upload_size;
        $limit_output =  esc_html( size_format( $max_upload_size ) );

        $size = isset($file['size']) ? $file['size'] : 0;
        $size = boldthemes_image_size($size);    

        $type = isset($file['type']) ? $file['type'] : '';
        $is_image = strpos( $type, 'image' ) !== false;

        if ( $is_image && $size > $limit ) {
          $file['error'] = __( 'Image files must be smaller than ', 'bt_plugin' ) . $limit_output;
        }
        return $file;
   
}
add_filter( 'wp_handle_upload_prefilter', 'boldthemes_max_image_size' );

function boldthemes_image_size( $bytes, $decimals = 0 ) {
    $quant = array(
        'TB' => TB_IN_BYTES,
        'GB' => GB_IN_BYTES,
        'MB' => MB_IN_BYTES,
        'KB' => KB_IN_BYTES,
        'B'  => 1,
    );
     
    if ( 0 === $bytes ) {
        return 0;
    }
    foreach ( $quant as $unit => $mag ) {
        if ( doubleval( $bytes ) >= $mag ) {
            $retVal = number_format( $bytes / $mag, absint($decimals) );
            return intval( $retVal );
        }
    }
    
    return false;
}

/*add_filter( 'rwmb_frontend_validate', function( $validate, $config ) {
    bt_dump($_POST);
    if ( empty( $_POST['_thumbnail_id'] ) ) {
        $validate = 'Please select Thumbnail to upload'; // Return a custom error message
    }
    return $validate;
}, 10, 2 );*/

// Remove update notifications
function remove_update_notifications( $value ) {

    if ( isset( $value ) && is_object( $value ) ) {
        unset( $value->response[ 'mb-frontend-submission/mb-frontend-submission.php' ] );
    }

    return $value;
}
add_filter( 'site_transient_update_plugins', 'remove_update_notifications' );

add_action( 'wp_ajax_rwmb_get_terms', array( 'RWMB_Taxonomy_Field', 'ajax_get_terms' ) );
add_action( 'wp_ajax_nopriv_rwmb_get_terms', array( 'RWMB_Taxonomy_Field', 'ajax_get_terms' ) );


add_filter('login_errors','boldthemes_login_error_message');
function boldthemes_login_error_message($error){
    die($error);
    $pos = strpos($error, 'incorrect');
    if (is_int($pos)) {
        $error = "Wrong information";
    }
    return $error;
}
