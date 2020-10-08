<?php
	
if ( ! function_exists( 'boldthemes_get_query_listings' ) ) {
	function boldthemes_get_query_listings( $params = array(), $featured_only = 0, $non_featured_only = 0 ) {
		$TxQuery = array();
        $limit		= isset($params["limit"]) && $params["limit"] != '' ? $params["limit"] : 0;
		$field		= isset($params["field"]) && $params["field"] != '' ? $params["field"] : 'slug';
		$operator	= isset($params["operator"]) && $params["operator"] != '' ? $params["operator"] : 'IN';
                
		$search_post_slug               = isset($params["search_post_slug"]) && $params["search_post_slug"] != '' ? $params["search_post_slug"] : ''; 
		$featured_listing_bb_element    = isset($params["featured_listing_bb_element"]) && $params["featured_listing_bb_element"] != '' ? $params["featured_listing_bb_element"] : 0; 

		// boldthemes_theme_listing-price

		$orderby	= isset($params["orderby"]) && $params["orderby"] != ''? $params["orderby"] : 'rand';
		$order		= isset($params["order"]) && $params["order"] != '' ? $params["order"] : 'DESC';
		$posts_per_page = isset($params["posts_per_page"]) && $params["posts_per_page"] != '' ? $params["posts_per_page"] : -1;
           
		/* standard filters: keyword, taxonomy 1 (region or category),taxonomy 2 (region or category) */
                $search_term    = isset($params["search_term"]) && $params["search_term"] != ''? $params["search_term"] : '';
		$listing_type	= isset($params["listing_type"]) && !empty($params["listing_type"]) ? $params["listing_type"] : null;
		$taxonomy	= isset($params["taxonomy"]) && !empty($params["taxonomy"]) ? $params["taxonomy"] : array();                
                $listing_type2	= isset($params["listing_type2"]) && !empty($params["listing_type2"]) ? $params["listing_type2"] : null;
		$taxonomy2	= isset($params["taxonomy2"]) && !empty($params["taxonomy2"]) ? $params["taxonomy2"] : array();		
		/* /standard filters */

		/* form query params: lat, lng, sort, price from, price to, now open, distance */   
               
		$form_query_params = isset($params["form_query_params"]) && $params["form_query_params"] != ''? $params["form_query_params"] : array();
                
		$bt_bb_listing_field_my_lat     = boldthemes_get_option( 'listing_search_distance_lat' )    != '' ? boldthemes_get_option( 'listing_search_distance_lat' )      : '0';
		$bt_bb_listing_field_my_lng     = boldthemes_get_option( 'listing_search_distance_lng' )    != '' ? boldthemes_get_option( 'listing_search_distance_lng' )      : '0';
		$bt_bb_listing_distance_max     = boldthemes_get_option( 'listing_distance_max' ) != '' ? boldthemes_get_option( 'listing_distance_max' ) : '100000'; 

		$bt_bb_listing_distance_max_in_slider	= boldthemes_get_option( 'listing_distance_max_in_slider' ) != '' ? boldthemes_get_option( 'listing_distance_max_in_slider' ) : false; 
		$bt_bb_listing_distance_max = $bt_bb_listing_distance_max_in_slider ? $bt_bb_listing_distance_max : 0;


		$bt_bb_listing_field_my_lat		= isset($form_query_params["bt_bb_listing_field_my_lat"]) && !empty($form_query_params["bt_bb_listing_field_my_lat"]) ? 
									 $form_query_params["bt_bb_listing_field_my_lat"] : $bt_bb_listing_field_my_lat;   
		$bt_bb_listing_field_my_lng		= isset($form_query_params["bt_bb_listing_field_my_lng"]) && !empty($form_query_params["bt_bb_listing_field_my_lng"]) ? 
											 $form_query_params["bt_bb_listing_field_my_lng"] : $bt_bb_listing_field_my_lng;             
		$bt_bb_listing_field_price_range_from	= isset($form_query_params["bt_bb_listing_field_price_range_from"]) && !empty($form_query_params["bt_bb_listing_field_price_range_from"]) ? 
											$form_query_params["bt_bb_listing_field_price_range_from"] : '';                
		$bt_bb_listing_field_price_range_to	= isset($form_query_params["bt_bb_listing_field_price_range_to"]) && !empty($form_query_params["bt_bb_listing_field_price_range_to"]) ? 
											$form_query_params["bt_bb_listing_field_price_range_to"] : '';                
		$bt_bb_listing_field_sort		= isset($form_query_params["bt_bb_listing_field_sort"]) && !empty($form_query_params["bt_bb_listing_field_sort"]) ? 
											$form_query_params["bt_bb_listing_field_sort"] : '';                
		$bt_bb_listing_field_now_open		= isset($form_query_params["bt_bb_listing_field_now_open"]) && !empty($form_query_params["bt_bb_listing_field_now_open"]) ? 
											$form_query_params["bt_bb_listing_field_now_open"] : 0; 
                
		$bt_bb_listing_field_distance_value	= isset($form_query_params["bt_bb_listing_field_distance_value"]) ? 
									$form_query_params["bt_bb_listing_field_distance_value"] : 0;           	

		/* /form query params */ 
                  
		/* meta query params: all filters */
                
		$meta_query_params = isset($params["meta_query"]) && !empty($params["meta_query"]) ? $params["meta_query"] : array();
                
		$meta_keys  = array();		
		foreach( $meta_query_params as $key => $value){			
			if ( $value != '') {   
				array_push( $meta_keys ,  $key );
			}                        				
		}      
		if ( ( $taxonomy == 'listing-category' && $listing_type == '' ) || ( $taxonomy2 == 'listing-category' &&  $listing_type2 == '') ){
			$meta_query_params = array();
			$meta_keys  = array();	
		}
		/* /meta query params: all filters */   

		
		if ( $search_post_slug != '' ){
				$search_post_slugs = explode( ';', $search_post_slug );  
				$args = array(
						 'post_type'                     => 'listing',
						 'post_status'                   => 'publish',
						 'posts_per_page'                => $posts_per_page,
						 'post_name__in'                 => $search_post_slugs,
						 "orderby"                       => $orderby,
						 'order'                         => $order,
				 );

		}else{
				 /* taxonomy query */
				BoldThemesFrameworkTemplate::$listing_root_slug = boldthemes_get_option( "listing_search_root_category_slug" ) != '' ? boldthemes_get_option( 'listing_search_root_category_slug' ) : '_listing_root';                

				if ( $taxonomy == 'listing-category' && is_array($listing_type) ){
					array_push($listing_type, BoldThemesFrameworkTemplate::$listing_root_slug);
				}
				if ( $taxonomy == 'listing-category' && is_array($listing_type2) ){
					array_push($listing_type2, BoldThemesFrameworkTemplate::$listing_root_slug);
				}


				$listing_type_arr  = ($taxonomy == 'listing-category' && is_array($listing_type))  ? $listing_type : array($listing_type);
				$listing_type2_arr = ($taxonomy == 'listing-category' && is_array($listing_type2)) ? $listing_type2: array($listing_type2);

				if ( !empty($listing_type) && !empty($listing_type2) ) {//region and category 
						if ( $listing_type[0] != '' && $listing_type2[0] != '' ) {
								$TxQuery = array(
										'relation' => 'AND',
										array(
												'taxonomy'		=> $taxonomy,
												'terms'			=> $listing_type_arr,
												'field'			=> $field,
												'include_children'	=> true,
												'operator'		=> $operator
										),
										array(
												'taxonomy'		=> $taxonomy2,
												'terms'			=> $listing_type2_arr,
												'field'			=> $field,
												'include_children'	=> true,
												'operator'		=> $operator
										),
								);
						}
				}else if ( !empty($listing_type) ) {//region or category or tag
						if ( $listing_type[0] != '' ) {                             
								$TxQuery = array(
										'relation' => 'AND',
										array(
												'taxonomy'		=> $taxonomy,
												'terms'			=> $listing_type_arr,
												'field'			=> $field,
												'include_children'	=> true,
												'operator'		=> $operator
										),
								);
						}
				} else{                    
						$TxQuery = array();
				}   
				/* /taxonomy query */   

				/* wp query */	  
				$args = array(
						'post_type'                     => 'listing',
						'post_status'                   => 'publish',
						'order'                         => $order,
						'posts_per_page'                => $posts_per_page,
						'search_prod_title'             => $search_term,
						'search_prod_price_range_from'  => $bt_bb_listing_field_price_range_from,
						'search_prod_price_range_to'    => $bt_bb_listing_field_price_range_to,
						'search_meta_query'             => $meta_keys,
						'tax_query'                     => $TxQuery,
				);

				if ( $orderby == 'price_from' ){
					$args = array_merge($args, array( "meta_key"=>"boldthemes_theme_listing-price_from", "orderby"=>"meta_value_num"));
				}else{
					$args = array_merge($args, array( "orderby"=>$orderby));
				}
		}

		/* /wp query */	        

		add_filter( 'posts_join', 'boldthemes_posts_join_filter', 10, 2 ); 
		add_filter( 'posts_where', 'boldthemes_posts_where_filter', 10, 2 );


		$listing_query	= new WP_Query($args);
             
        remove_filter( 'posts_join', 'boldthemes_posts_join_filter', 10, 2 ); 
		remove_filter( 'posts_where', 'boldthemes_posts_where_filter', 10, 2 );

        $found      = $listing_query->found_posts;
		$listings   = $listing_query->posts;
        // var_dump($listing_query->request);
        //print_r($listings);exit;
		wp_reset_postdata();
        reset($listings);
                
		if ( $search_post_slug == '' ){
				/* filters open now, distance */
				$i = 0;
				foreach ( $listings as $listing ) {
						/* open now search filter */
						if ( $bt_bb_listing_field_now_open == 1 ) { 
								$open_hours = bt_open_hours( $listing->ID ); 
								if ( $open_hours != '' ) {	// open hour exist for listing -> now closed			
										array_splice($listings, $i, 1);
										continue;
								}
						}

						BoldThemesFrameworkTemplate::$listing_distance_max  = boldthemes_get_option( 'listing_distance_max' ) ? boldthemes_get_option( 'listing_distance_max' ) : '100000';
						/* distance search filter */

						if ( $bt_bb_listing_field_distance_value > 0 && !$featured_listing_bb_element ) { 
							if ( $bt_bb_listing_field_my_lat != 0 && $bt_bb_listing_field_my_lng != 0 ) { 	
								$distance = boldthemes_get_distance( $bt_bb_listing_field_my_lat, $bt_bb_listing_field_my_lng,  $listing->ID);

								if ( $distance == '' || $distance > $bt_bb_listing_field_distance_value ){  
										array_splice($listings, $i, 1);
										continue;
								}
							}
						}
						$i++;
				}

				/* /filters open now, distance */

				/* featured and non-featured listings, featured first in serach results */
				$listings_featured = array(); $listings_non_featured = array();
				foreach ( $listings as &$listing ) {
					$listing->featured = bello_listing_is_featured( $listing ) ? 1 : 0;	
					if ( $listing->featured == 1 ) {

							array_push( $listings_featured, $listing );
					}else{
							array_push( $listings_non_featured, $listing );
					}
				}
                //Wprint_r($listings_featured);

				if ( $featured_only ){
					$listings =  $listings_featured;
				}else if ( $non_featured_only ){
					$listings =  $listings_non_featured;;
				}else{
					if ( $featured_listing_bb_element == 1 ){
						//$listings = $listings;
                        $listings = array_merge( $listings_featured, $listings_non_featured );
					}else{
						$listings = array_merge( $listings_featured, $listings_non_featured );
					}
				}
				/* /featured and non-featured listings, featured first in serach results */

		}

		if ( $limit > 0 ){
			$listings = array_slice($listings, 0, $limit);
		}

		BoldThemesFrameworkTemplate::$found		= count( $listings );       
		BoldThemesFrameworkTemplate::$posts_per_page	= boldthemes_get_option( 'listing_grid_listings_per_page' ) > 0 ? boldthemes_get_option( 'listing_grid_listings_per_page' ) : 1000;   
		BoldThemesFrameworkTemplate::$max_page	= is_int(  BoldThemesFrameworkTemplate::$found / BoldThemesFrameworkTemplate::$posts_per_page ) ? 
        BoldThemesFrameworkTemplate::$found / BoldThemesFrameworkTemplate::$posts_per_page : ceil( BoldThemesFrameworkTemplate::$found / BoldThemesFrameworkTemplate::$posts_per_page );
        return $listings;
	}
}


/* join for metadata price , price from & price to */
if ( ! function_exists( 'boldthemes_posts_join_filter' ) ) {
	function boldthemes_posts_join_filter( $join, $wp_query ) {
		global $wpdb;
		$meta_keys = array('boldthemes_theme_listing-price', 'boldthemes_theme_listing-price_from', 'boldthemes_theme_listing-price_to');
	   
		$search_prod_price_range_from   = $wp_query->get( 'search_prod_price_range_from' );
		$search_prod_price_range_to     = $wp_query->get( 'search_prod_price_range_to' );
		$search_meta_query		= $wp_query->get( 'search_meta_query' );
                                
		if ( $search_prod_price_range_from || $search_prod_price_range_to ) {
                    $join .= " INNER JOIN $wpdb->postmeta AS bt_postmeta_price_from ON ($wpdb->posts.ID = bt_postmeta_price_from.post_id AND  "
                            . "( "
                                    . "bt_postmeta_price_from.meta_key = '".$meta_keys[1] . "')"
                            . ") "; 
                    
                    
                    $join .= " INNER JOIN $wpdb->postmeta AS bt_postmeta_price_to ON ($wpdb->posts.ID = bt_postmeta_price_to.post_id AND  "
                            . "( "
                                    . "bt_postmeta_price_to.meta_key = '".$meta_keys[2] . "')"
                            . ") ";    
		}

		if ( !empty($search_meta_query) ) {
			foreach ($search_meta_query as $meta_key){
				if ( $meta_key != "distance_value" ){
                                        //boldthemes_theme_listing-amenities_parking -> amenities_parking   
                                        $key_as =  boldthemes_posts_replace_for_query($meta_key);
                                       
					$meta_key = 'boldthemes_theme_listing-' . $meta_key;
					$join .= " INNER JOIN $wpdb->postmeta AS " . $key_as . " ON ($wpdb->posts.ID =" . $key_as . ".post_id AND  " . $key_as . ".meta_key = '".$meta_key . "') "; 
				}
			}        
		}
		return $join;
	}
}


/*where filter - keyword, meta, price */
if ( ! function_exists( 'boldthemes_posts_where_filter' ) ) {
	function boldthemes_posts_where_filter($where, $wp_query){
		$search_term                    = $wp_query->get( 'search_prod_title' );
		$search_prod_price_range_from   = $wp_query->get( 'search_prod_price_range_from' );
		$search_prod_price_range_to     = $wp_query->get( 'search_prod_price_range_to' );
		$search_meta_query		= $wp_query->get( 'search_meta_query' );                
	   
		if ( $search_term ){
			$where .= boldthemes_posts_where_filter_keyword($search_term);
		}
		if ( !empty($search_meta_query) ){
			$where .= boldthemes_posts_where_filter_meta($search_meta_query);
		}
		if ( $search_prod_price_range_from || $search_prod_price_range_to ) {
			$meta_keys = array('boldthemes_theme_listing-price', 'boldthemes_theme_listing-price_from', 'boldthemes_theme_listing-price_to');
			$where .= boldthemes_posts_where_filter_price($meta_keys, $search_prod_price_range_from, $search_prod_price_range_to);
		}
                
		return $where;
	}
}

/*helper where filter - keyword */
if ( ! function_exists( 'boldthemes_posts_where_filter_keyword' ) ) {
	function boldthemes_posts_where_filter_keyword($search_term){
		$clause = '';
		global $wpdb;
		if ( $search_term ) {
			$clause .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $search_term ) ) . '%\'';
			$clause .= ' OR ' . $wpdb->posts . '.post_excerpt LIKE \'%' . esc_sql( $wpdb->esc_like( $search_term ) ) . '%\'';
			$clause .= ' OR ' . $wpdb->posts . '.post_content LIKE \'%' . esc_sql( $wpdb->esc_like( $search_term ) ) . '%\')';                   
		}		
		return $clause;    
	}
}

/*helper where filter - price from, price to*/
if ( ! function_exists( 'boldthemes_posts_where_filter_price' ) ) {
	function boldthemes_posts_where_filter_price($meta_keys, $price_range_from, $price_range_to){
		$clause = '';	   
		if ( $price_range_from || $price_range_to ) {
		   $clause .= ' AND ';
		}
                
                if ( $price_range_from && $price_range_to ) {                
                    if ( $price_range_from  ) {
                        $clause .= ' ((bt_postmeta_price_to.meta_key = "boldthemes_theme_listing-price_to" AND bt_postmeta_price_to.meta_value >= ' . $price_range_from . ')'; 
                    }
                    if ( $price_range_from && $price_range_to ) {
                        $clause .= ' AND ';
                    }
                    if ( $price_range_to ) {   
                        $clause .= ' (bt_postmeta_price_from.meta_key = "boldthemes_theme_listing-price_from" AND bt_postmeta_price_from.meta_value <= ' . $price_range_to . ')'; 
                    }

                    if ( $price_range_from || $price_range_to ) {
                       $clause .= ' )';
                    }                
                }else{                
                    if ( $price_range_from ) {
                        $clause .= ' ((bt_postmeta_price_from.meta_key = "boldthemes_theme_listing-price_from" AND bt_postmeta_price_from.meta_value >= ' . $price_range_from . ')';
                        $clause .= ' OR ';
                        $clause .= ' (bt_postmeta_price_to.meta_key = "boldthemes_theme_listing-price_to" AND bt_postmeta_price_to.meta_value >= ' . $price_range_from . '))';
                    }
                    if ( $price_range_to ) {
                        $clause .= ' ((bt_postmeta_price_from.meta_key = "boldthemes_theme_listing-price_from"  AND bt_postmeta_price_from.meta_value <> "" AND bt_postmeta_price_from.meta_value <= ' . $price_range_to . ')';
                        $clause .= ' OR ';
                        $clause .= ' (bt_postmeta_price_to.meta_key = "boldthemes_theme_listing-price_to"  AND bt_postmeta_price_to.meta_value <> "" AND bt_postmeta_price_to.meta_value <= ' . $price_range_to . '))';
                    } 
                }
		return $clause;
	}
}

/*helper where filter - meta values */
if ( ! function_exists( 'boldthemes_posts_where_filter_meta' ) ) {
	function boldthemes_posts_where_filter_meta($meta_keys){
		$clause = '';
                global $wpdb;
                
		foreach ($meta_keys as $meta_key){
                        $key_as =  boldthemes_posts_replace_for_query($meta_key);                        
			if ( $meta_key != "distance_value" ){	
                             if ( $meta_key == "timekit" ){
                                $search_term = 'a:6:{i:0;s:0:"";i:1;s:0:"";i:2;s:0:"";i:3;s:0:"";i:4;s:0:"";i:5;s:0:"";}';
                                $clause .= ' AND (' . $key_as . '.meta_id IS NOT NULL) AND  (' . $key_as . '.meta_value NOT LIKE \'%' . esc_sql( $wpdb->esc_like( $search_term ) ) . '%\')';
                             }else if( $meta_key == "opentable" ) {
                                $search_term = 'a:2:{i:0;s:0:"";i:1;s:0:"";}';
                                $clause .= ' AND (' . $key_as . '.meta_id IS NOT NULL) AND  (' . $key_as . '.meta_value NOT LIKE \'%' . esc_sql( $wpdb->esc_like( $search_term ) ) . '%\')';
                             }else if( $meta_key == "resurva" ) {
                                 $clause .= ' AND (' . $key_as . '.meta_id IS NOT NULL) AND (CAST(' . $key_as . '.meta_value as CHAR)  != "")';
                             }else{
				 $clause .= ' AND (' . $key_as . '.meta_id IS NOT NULL) AND (CAST(' . $key_as . '.meta_value as CHAR)  != "0")';
                             }
			}
                       
		} 
                
		return $clause;    
	}
}

if ( ! function_exists( 'boldthemes_posts_replace_postmeta_query' ) ) {
	function boldthemes_posts_replace_for_query($meta_key){
            
            $key_as = str_replace('-','_',$meta_key) ;
            $key_as = str_replace(' ','_',$key_as) ;
            $key_as = str_replace('%','_',$key_as) ;
            $key_as = str_replace('^','_',$key_as) ;
            $key_as = str_replace('(','_',$key_as) ;
            $key_as = str_replace(')','_',$key_as) ;
            $key_as = str_replace('-)','_',$key_as) ;
            
            $key_as = "bt_postmeta_".$key_as ;
                        
            return $key_as;
        }
}
        