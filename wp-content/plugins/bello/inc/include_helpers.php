<?php

if ( ! function_exists( 'bt_dump' ) ) {
    function bt_dump($value) {
            echo "<pre>";
            print_r( $value );
            echo "</pre>";
    }
}

if ( ! function_exists( 'bt_update_listings_prices' ) ) {
    function bt_update_listings_prices(){    
        $args = array(
                'post_type'     => 'listing',
                'posts_per_page' => -1,
                'post_status'   => 'publish'		
        );
        $listing_query	= new WP_Query($args);
        $listings   = $listing_query->posts;

        $prices = array( array(25,50),array(55,105),array(100,150),array(120,201),array(180,305),array(310,900));

        foreach ($listings as $listing){       
                $meta = get_post_meta( $listing->ID );
                $price = $prices[rand(0,5)];
                $from   = $price[0];
                $to     = $price[1];

                if ( !isset($meta['boldthemes_theme_listing-price_from']) && !isset($meta['boldthemes_theme_listing-price_to']))
                {
                    update_post_meta( $listing->ID, 'boldthemes_theme_listing-price_from', $from );
                    update_post_meta( $listing->ID, 'boldthemes_theme_listing-price_to', $to );
                }
                else if (!isset($meta['boldthemes_theme_listing-price_from']))
                {
                    update_post_meta( $listing->ID, 'boldthemes_theme_listing-price_from', $from );
                }
                else if (!isset($meta['boldthemes_theme_listing-price_to']))
                {
                    update_post_meta( $listing->ID, 'boldthemes_theme_listing-price_to', $to );
                }
                else
                {
                    echo '('. $listing->ID . ' ) - ';
                }
        }
    }
}

if ( ! function_exists( 'bt_update_listings_prices2' ) ) {
    function bt_update_listings_prices2(){  
        $args = array(
                'post_type'     => 'listing',
                'posts_per_page' => -1,
                'post_status'   => 'publish'		
        );
        $listing_query	= new WP_Query($args);
        $listings   = $listing_query->posts;

        foreach ($listings as $listing){   

            if ( $listing->ID == 3560 ) {
                    $meta = get_post_meta( $listing->ID );

                    if ( isset($meta['boldthemes_theme_listing-price_from'])){
                        $from = $meta['boldthemes_theme_listing-price_from'];
                        update_post_meta( $listing->ID, 'boldthemes_theme_listing-price_from', $from[0] );
                    }

                    if ( isset($meta['boldthemes_theme_listing-price_to'])){
                        $to = $meta['boldthemes_theme_listing-price_to'];
                        update_post_meta( $listing->ID, 'boldthemes_theme_listing-price_to', $to[0] );
                    }
                    break;
            }            
        }    
    }
}

if ( ! function_exists( 'bt_array_orderby' ) ) {
    function bt_array_orderby()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
                }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }
}

/* set metaboxes for price from and price to of some are empty */
// After inserting post
add_action( 'save_post', 'bt_set_both_prices', 10, 3);
if ( ! function_exists( 'bt_set_both_prices' ) ) {
    function bt_set_both_prices( $post_ID, $post, $update ) {
       if($post->post_type == 'listing') {
           $meta = get_post_meta( $post->ID);
           
           if ( !empty($_POST['boldthemes_theme_listing-price_from']) ) {
                delete_post_meta( $post->ID, 'boldthemes_theme_listing-price_from');
                add_post_meta( $post->ID, 'boldthemes_theme_listing-price_from', $_POST['boldthemes_theme_listing-price_from']);
           }else{
               update_post_meta( $post->ID, 'boldthemes_theme_listing-price_from', '' );
           }
           
           if ( !empty($_POST['boldthemes_theme_listing-price_to']) ) {
                delete_post_meta( $post->ID, 'boldthemes_theme_listing-price_to');
                add_post_meta( $post->ID, 'boldthemes_theme_listing-price_to', $_POST['boldthemes_theme_listing-price_to']);
           }else{
               update_post_meta( $post->ID, 'boldthemes_theme_listing-price_to', '' );
           }
           
           update_post_meta( $post->ID, 'boldthemes_theme_listing-price_free', '' );
           if ( !empty($_POST['boldthemes_theme_listing-price_free']) ) {
                if ( $_POST['boldthemes_theme_listing-price_free'] == 1 ){
                    update_post_meta( $post->ID, 'boldthemes_theme_listing-price_free', '1' );
                    update_post_meta( $post->ID, 'boldthemes_theme_listing-price_from', '' );
                    update_post_meta( $post->ID, 'boldthemes_theme_listing-price_to', '' );
                }
           }          
           
           if (isset($meta['boldthemes_theme_listing-price_from'])){              
               if ( $meta['boldthemes_theme_listing-price_from'] == '' ){
                    update_post_meta( $post->ID, 'boldthemes_theme_listing-price_from', '' );
                } 
           }else{
               //update_post_meta( $post->ID, 'boldthemes_theme_listing-price_from', $_POST['boldthemes_theme_listing-price_from'] );
           }
           if (isset($meta['boldthemes_theme_listing-price_to'])){               
               if ( $meta['boldthemes_theme_listing-price_to'] == '' ){
                    update_post_meta( $post->ID, 'boldthemes_theme_listing-price_to', '' );
                } 
           }else{
               //update_post_meta( $post->ID, 'boldthemes_theme_listing-price_to', $_POST['boldthemes_theme_listing-price_to'] );
           } 

       }
       return true;
    }
}


/**
* Retrieve a product given its slug.
*/
if ( ! function_exists( 'bt_get_product_by_slug' ) ) {
    function bt_get_product_by_slug($page_slug, $output = OBJECT) {
        global $wpdb;
            $product = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s", $page_slug, 'product'));
            if ( $product )
                return get_post($product, $output);

        return null;
    }
}

if ( ! function_exists( 'bt_get_meta_query_param' ) ) {
    function bt_get_meta_query_param($field, $value) {
       if ($value != '' && substr($field, 0, 17) === 'boldthemes_field_') {
                $field = substr( $field, 17, strlen($field) );
                $meta_query_param[$field] = $value;
                return $meta_query_param;
       }
       
       return null;
    }
}

if ( ! function_exists( 'bt_get_form_query_param' ) ) {
    function bt_get_form_query_param($field, $value) {
       if ( $value != '' && substr($field, 0, 20) === 'bt_bb_listing_field_'){
                $form_query_params[$field] = $value;
                return $form_query_params;
       }
       
       return null;
    }
}

/**
 * Returns post type id by slug
 *
 * @return string
 */
if ( ! function_exists( 'boldthemes_get_listing_category_id_by_slug' ) ) {
	function boldthemes_get_listing_category_id_by_slug( $slug ) {

		$post = get_posts(
			array(
				'name'      => $slug,
				'post_type' => 'listing-category'
			)
		);
		if ( isset($post[0]->ID) ) {
			return $post[0]->ID;	
		} else {
			return null;
		}
		
	}
}




/* combine category from post/listing and root category */
if ( ! function_exists( 'bt_wp_get_post_terms' ) ) {
    function bt_wp_get_post_terms( $params = array() ){
        $id = isset( $params['listing_id'] ) ? $params['listing_id'] : get_the_ID();
	$post_terms = wp_get_post_terms( $id, 'listing-category' );
         
        $listing_root_slug  = boldthemes_get_option( "listing_search_root_category_slug" ) != '' ? boldthemes_get_option( 'listing_search_root_category_slug' ) : '_listing_root';
        $terms_root         = get_term_by('slug', $listing_root_slug, 'listing-category');
       
        if (isset($terms_root)){
                $post_terms_root = array();
                $post_terms_root[0]  = $terms_root;
                $post_terms =  array_merge($post_terms_root, $post_terms);
                return $post_terms;
        }
        
        return $post_terms;
    }
}

if ( ! function_exists( 'bt_format_phone_number' ) ) {
    function bt_format_phone_number( $phone_number ) {
        $phone_number = preg_replace("/[^0-9]/", "", $phone_number );
        return "+" . $phone_number;
        //return $phone_number;
    }
}

if ( ! function_exists( 'bt_sort_array' ) ) {
    function bt_sort_array( &$array, $key ) {
            if ( !empty($array) && $key != '' ) {
                    $sorter=array();
                    $ret=array();
                    reset($array);
                    foreach ($array as $ii => $va) {
                            $sorter[$ii]=$va[$key];
                    }
                    asort($sorter);
                    foreach ($sorter as $ii => $va) {
                            $ret[$ii]=$array[$ii];
                    }
                    $array=$ret;
                    return $array;
            }
    }
}

if ( ! function_exists( 'bt_sort_multiarray' ) ) {
    function bt_sort_multiarray( &$array, $key, $sort = SORT_ASC  ) {
            if ( !empty($array) && $key != '' ) {
                    $sortArray = array(); 
                    foreach( $array as $position){ 
                            foreach($position as $key=>$value){ 
                                    if(!isset($sortArray[$key])){ 
                                            $sortArray[$key] = array(); 
                                    } 
                                    $sortArray[$key][] = $value; 
                            } 
                    }
                    array_multisort( $sortArray[$key], $sort,  $array);
                    return  $array;
            }
    }
}

/**
 * Calculates the great-circle distance between two points, with
 * the Vincenty formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $listing_id Listing with Latitude and Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m] : 6371000 meters , in [miles] : 3959 
 * @return float Distance between points in [m] or [mi] (same as earthRadius)
 */
if ( ! function_exists( 'boldthemes_get_distance' ) ) {
	function boldthemes_get_distance( $latitudeFrom, $longitudeFrom, $listing_id = 0, $earthRadius = 3959) {

	  BoldThemesFrameworkTemplate::$listing_search_distance_unit = boldthemes_get_option( 'listing_search_distance_unit' ) != '' ? boldthemes_get_option( 'listing_search_distance_unit' ) : 'mi';
	  switch(BoldThemesFrameworkTemplate::$listing_search_distance_unit) {
		case 'km':
			$earthRadius = 6371;
			break;
		case 'mi':
			$earthRadius = 3959;
			break;
		case 'nmi':
			$earthRadius = 3959;
			break;
		default:
			$earthRadius = 3959;
			break;
	}

	  $arg = $listing_id > 0  ?  array( 'listing_id' => $listing_id ) :  array( 'listing_id' => get_the_ID() );
	  $listing_fields   = bello_get_listing_fields( $arg );		

	  if ( isset($listing_fields["location_position"]["value"]) ) {
		  $location_position = explode(",",$listing_fields["location_position"]["value"][0]);
		  $latitudeTo		 = $location_position[0];
		  $longitudeTo		 = $location_position[1];	 

		  // convert from degrees to radians
		  $latFrom = deg2rad(floatval($latitudeFrom));
		  $lonFrom = deg2rad(floatval($longitudeFrom));
		  $latTo = deg2rad(floatval($latitudeTo));
		  $lonTo = deg2rad(floatval($longitudeTo));

		  $lonDelta = $lonTo - $lonFrom;
		  $a = pow(cos($latTo) * sin($lonDelta), 2) + pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
		  $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

		  $angle = atan2(sqrt($a), $b);
		  $distance =  number_format((float)$angle * $earthRadius, 2, '.', ''); //$angle * $earthRadius;

		  return $distance;
	  }
	 
	  return '';

	}
}

if ( ! function_exists( 'boldthemes_get_average_rating' ) ) {
    function boldthemes_get_average_rating($listing_id) {
            $comment_array = get_approved_comments($listing_id);
            $count = 1;
            $i = 0;
            $total = 0;
            $rating = 0;

            if ($comment_array) {        
                foreach($comment_array as $comment){
                    $rate = get_comment_meta($comment->comment_ID, 'rating');
                    if(isset($rate[0]) && $rate[0] !== '') {
                        $i++;
                        $total += $rate[0];
                    }
                }
                if( $i > 0 && $total > 0 ) {
                        $rating = round( $total/$i, 2 );
                }
            } 
            
            return  array( "rating" => $rating, "total" => $total, "no" => $i);
    }
}

/**
 * Get post rating
 */
if ( ! function_exists( 'boldthemes_get_post_rating' ) ) {
	function boldthemes_get_post_rating( $post_id = null ) {
		$review = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_review', array(), $post_id );
		$review_arr = explode( PHP_EOL, $review );
		$sum = 0;
		foreach( $review_arr as $r ) {
			$r_arr = explode( ';', $r );
			if ( isset( $r_arr[1] ) ) {
				$item_rating = round( floatval( $r_arr[1] ) );
			} else {
				$item_rating = 0;
			}
			$sum += $item_rating;
		}
		$rating = round( $sum / count( $review_arr ) , 1 );
		return $rating;
	}
}

/**
 * Get post star rating
 */
if ( ! function_exists( 'boldthemes_get_post_star_rating' ) ) {
	function boldthemes_get_post_star_rating( $post_id = null ) {
		$rating = boldthemes_get_post_rating( $post_id );
		if ( $rating == 0 ) {
			return '';
		}
		return '<div class="star-rating"><span style="width:' . $rating . '%"><strong class="rating">' . $rating . '</strong>' . esc_html__( 'min', 'bt_plugin' ) . '100</span></div>';
	}
}

/**
 * Get post star rating
 */
if ( ! function_exists( 'boldthemes_get_all_comments_of_post_type' ) ) {
	function boldthemes_get_all_comments_of_post_type($post_type = null, $post_id = null){
			if ( $post_type == null ){
				return 0;
			}

			  global $wpdb;
			  $cc = $wpdb->get_var("SELECT COUNT(comment_ID)
				FROM $wpdb->comments
				WHERE comment_post_ID in (
				  SELECT ID 
				  FROM $wpdb->posts 
				  WHERE ID = $post_id
				  AND post_type = '$post_type' 
				  AND post_status = 'publish')
				AND comment_approved = '1'
			  ");
			  return $cc;
	}
}



if ( ! function_exists( 'boldthemes_get_listings_google_map_center' ) ) {
	function boldthemes_get_listings_google_map_center( $listings ) {
           
                $get_listing_search_distance_lat            = isset($_GET['bt_bb_listing_field_my_lat']) ? $_GET['bt_bb_listing_field_my_lat'] : '51.476852';
                $get_listing_search_distance_lng            = isset($_GET['bt_bb_listing_field_my_lng']) ? $_GET['bt_bb_listing_field_my_lng'] : '-0.000500';
                $get_listing_field_location_autocomplete    = isset($_GET['bt_bb_listing_field_location_autocomplete']) && $_GET['bt_bb_listing_field_location_autocomplete'] != ''
                        ? $_GET['bt_bb_listing_field_location_autocomplete'] : '';
                
                $listing_search_max_zoom        = boldthemes_get_option( 'listing_search_max_zoom' )        != '' ? boldthemes_get_option( 'listing_search_max_zoom' )       : 12;
                $listing_search_distance_lat    = boldthemes_get_option( 'listing_search_distance_lat' )    != '' ? boldthemes_get_option( 'listing_search_distance_lat' )   : $get_listing_search_distance_lat;
                $listing_search_distance_lng    = boldthemes_get_option( 'listing_search_distance_lng' )    != '' ? boldthemes_get_option( 'listing_search_distance_lng' )   : $get_listing_search_distance_lng;

                
                if ( $get_listing_field_location_autocomplete != '' && count($listings) == 0 ) {
                    $listing_search_distance_lat = $get_listing_search_distance_lat;
                    $listing_search_distance_lng = $get_listing_search_distance_lng;
                }
                
               
                
                $i = 0;
		$lat_sum = 0;
		$lng_sum = 0;
		$lat_center = 0;
		$lng_center = 0;
		foreach ( $listings as $listing){
                    if ( isset($listing->ID) ){
			$boldthemes_theme_listing_location_position	 = boldthemes_rwmb_meta('boldthemes_theme_listing_location_position', array(),$listing->ID);
			$boldthemes_theme_listing_location_position	 = explode(",", $boldthemes_theme_listing_location_position);
			if ( isset($boldthemes_theme_listing_location_position[0]) && isset($boldthemes_theme_listing_location_position[1]) ) {
				$lat_sum += $boldthemes_theme_listing_location_position[0];
				$lng_sum += $boldthemes_theme_listing_location_position[1];
				$i++;
			}
                    }
		}

		$lat_center = $lat_sum > 0 ? $lat_sum/$i : $listing_search_distance_lat;
		$lng_center = $lng_sum > 0 ? $lng_sum/$i : $listing_search_distance_lng;
                
                $lat_center = $listing_search_distance_lat;
		$lng_center = $listing_search_distance_lng;
                
		return  array( "lat_center" => $lat_center, "lng_center" => $lng_center, "zoom" => $listing_search_max_zoom, "no" => $i);
	}
}

if ( ! function_exists( 'bello_get_term' ) ) {
    function bello_get_term( $arr, &$term_arr ) {
            $terms = get_terms( array(
                    'taxonomy'   => 'listing-category',
                    'hide_empty' => false,
                    'hierarchical' => 'true',
                    'include'    => $arr
            ) );
            
            if ( isset($terms) ) {
                     if ( !is_wp_error( $terms ) && !empty($terms) ){
                        if ( $terms[0]->parent != 0 ) {
                                $term_arr[] = $terms[0]->parent;
                                bello_get_term( $terms[0]->parent, $term_arr );
                        }
                    }
            }
    }
}

if ( ! function_exists( 'bello_get_listing_terms' ) ) {
    function bello_get_listing_terms( $term_arr ) {        
            bello_get_term( $term_arr, $term_arr );           
            $terms = get_terms( array(
                    'taxonomy'   => 'listing-category',
                    'hide_empty' => false, 
                    'include'    => $term_arr,
                    'hierarchical' => 'true',
                    'orderby' => 'parent',
                    'order' => 'ASC'
            ) );
           
            return $terms;
    }
}

if ( ! function_exists( 'bello_get_listing_terms_all' ) ) {
    function bello_get_listing_terms_all( $term_id_exclude) {
            $terms = get_terms( array(
                    'taxonomy'   => 'listing-category',
                    'hide_empty' => false,
                    'hierarchical' => 'true',
                    'orderby' => 'parent',
                    'order' => 'ASC',
                    'exclude'   => $term_id_exclude,
            ) );
           
            return $terms;
    }
}

/**
 * Current page uri
 */
if ( !function_exists( 'boldthemes_current_page_server_uri' ) ) {
    function boldthemes_current_page_server_uri() {
        $current_rel_uri = add_query_arg( NULL, NULL );
        return esc_url($current_rel_uri);
    }
}

/**
 * Current listing category image
 */
if ( !function_exists( 'boldthemes_listing_category_image' ) ) {
    function boldthemes_listing_category_image( $listing_id, $only_path = true, $selected = false ) {
            $listing_pin_normal     = boldthemes_get_option( 'listing_pin_normal' )     != '' ? boldthemes_get_option( 'listing_pin_normal' )   : '';
            $listing_pin_selected   = boldthemes_get_option( 'listing_pin_selected' )   != '' ? boldthemes_get_option( 'listing_pin_selected' ) : '';
            
            $property = $selected == 0 ? 'showcase-taxonomy-image-id' : 'showcase-taxonomy-selected-image-id';
        
            $categories  = get_the_terms( $listing_id, 'listing-category' );
            $image_id = 0;
            if ( $categories ){
                foreach ( $categories as $category){
                    $image_meta_id = get_term_meta ( $category->term_id, $property, true );
                    if ( $image_meta_id > 0 ) {
                        $image_id = $image_meta_id;
                    }
                }
            }
            
            if ( $only_path ){
                 $image = wp_get_attachment_url( $image_id );
            }else{
                $image = wp_get_attachment_image ( $image_id, 'thumbnail' );
            }
            
            if ( !$selected ){
                return $image != '' ? $image : $listing_pin_normal;
            }else{
                return $image != '' ? $image : $listing_pin_selected;
            }
    }
}

/**
 * Include price from element in sort by list if price exist in category
 */
if ( !function_exists( 'boldthemes_listing_sort_prices' ) ) {
    function boldthemes_listing_sort_prices( $listing_category ) {
        $have_prices = 0;
        $cf_settings =  bello_get_listing_category_cf_settings_sort_prices( $listing_category, 'search' ); 
        if ( !empty($cf_settings) ){	
            foreach( $cf_settings as $cf_setting){
                if ( isset( $cf_setting['control'] ) ) {
                    if ( $cf_setting['control'] == 'price' ){
                        $have_prices = 1;
                        break;
                    }
                }
            }
        }
        return $have_prices;
    }
}

// get category cf settings
// type: 'search 
function bello_get_listing_category_cf_settings_sort_prices( $cat, $type = '' ) {	
	$cf_settings	= array();
	$custom_fields	= array();        
                 
        $listing_category      = get_term_by( 'slug', $cat, 'listing-category' );
        $listing_category_id   = isset($listing_category) && isset($listing_category->term_id) ? $listing_category->term_id : 0; 
          
        $arr = bello_get_listing_category_fields( $listing_category_id, 1 );      
            
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
                                        $field = bello_get_listing_category_search_fields_sort_prices( $listing_category_id, $key, $type );                                       
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

// get category search fields
function bello_get_listing_category_search_fields_sort_prices( $cat, $field_name = '', $type = '' ) {
	$fields = array();	
	$arr = bello_get_listing_category_fields( $cat, 1 );
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
