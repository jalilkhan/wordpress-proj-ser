<?php
/* main listing template */

BoldThemesFrameworkTemplate::$listing_category	= get_query_var('listing-category') != '' ? get_query_var('listing-category') : '';
BoldThemesFrameworkTemplate::$listing_region	= get_query_var('listing-region') != '' ? get_query_var('listing-region') : '';
BoldThemesFrameworkTemplate::$listing_tag	= get_query_var('listing-tag') != '' ? get_query_var('listing-tag') : '';        
BoldThemesFrameworkTemplate::$paged		= get_query_var('paged') > 0 ? get_query_var('paged') : 1;

BoldThemesFrameworkTemplate::$listing_grid_columns              = boldthemes_get_option( "listing_grid_columns" ) != '' ? boldthemes_get_option( 'listing_grid_columns' ) : '2';

BoldThemesFrameworkTemplate::$listing_list_grid_view            = boldthemes_get_option( "listing_list_grid_view" ) != '' ? boldthemes_get_option( 'listing_list_grid_view' ) : 'list';
if ( isset($_COOKIE['bt_bb_listing_view']) ) {
     BoldThemesFrameworkTemplate::$listing_list_grid_view = $_COOKIE['bt_bb_listing_view'];
}
BoldThemesFrameworkTemplate::$listing_list_grid_view = BoldThemesFrameworkTemplate::$listing_list_grid_view != 'list' && BoldThemesFrameworkTemplate::$listing_list_grid_view != 'grid'
    ? 'list' : BoldThemesFrameworkTemplate::$listing_list_grid_view;

BoldThemesFrameworkTemplate::$listing_root_slug                 = boldthemes_get_option( "listing_search_root_category_slug" ) != '' ? boldthemes_get_option( 'listing_search_root_category_slug' ) : '_listing_root';
BoldThemesFrameworkTemplate::$listing_search_type		= boldthemes_get_option( 'listing_search_type' ) != '' ? boldthemes_get_option( 'listing_search_type' ) : 'ajax';
BoldThemesFrameworkTemplate::$listing_search_distance_unit	= boldthemes_get_option( 'listing_search_distance_unit' ) != '' ? boldthemes_get_option( 'listing_search_distance_unit' ) : 'mi';

BoldThemesFrameworkTemplate::$listing_search_autocomplete	= bt_is_autocomplete();
        
BoldThemesFrameworkTemplate::$location_autocomplete_distance	= boldthemes_get_option( 'listing_search_distance_radius' ) != '' ? boldthemes_get_option( 'listing_search_distance_radius' ) : '0';
BoldThemesFrameworkTemplate::$posts_per_page                    = boldthemes_get_option( 'listing_grid_listings_per_page' ) > 0 ? boldthemes_get_option( 'listing_grid_listings_per_page' ) : 24;
BoldThemesFrameworkTemplate::$custom_map_style                  = boldthemes_get_option( "custom_map_style" ) != '' ? boldthemes_get_option( "custom_map_style" ) : '';
BoldThemesFrameworkTemplate::$limit                             = boldthemes_get_option( 'listing_grid_listings_per_page' ) > 0 ? boldthemes_get_option( 'listing_grid_listings_per_page' ) : 24;

BoldThemesFrameworkTemplate::$listing_distance_max              = boldthemes_get_option( 'listing_distance_max' ) ? boldthemes_get_option( 'listing_distance_max' ) : '1000';
BoldThemesFrameworkTemplate::$currency_symbol                   = boldthemes_get_option( 'listing_search_currency_symbol' ) ? boldthemes_get_option( 'listing_search_currency_symbol' ) : '';   

 /* distance iz get-a, pa customizera ili od usera u js */
if (BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lat == '' || BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lat == 0){//get
    BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lat = boldthemes_get_option( 'listing_search_distance_lat' )  != '' ? boldthemes_get_option( 'listing_search_distance_lat' ) : '51.476852';
}
if (BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lng == '' || BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lng == 0){//get
    BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lng = boldthemes_get_option( 'listing_search_distance_lng' )  != '' ? boldthemes_get_option( 'listing_search_distance_lng' ) : '-0.000500';
}
                
BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lat_default = boldthemes_get_option( 'listing_search_distance_lat' )  != '' ? boldthemes_get_option( 'listing_search_distance_lat' ) : '51.476852';
BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lng_default = boldthemes_get_option( 'listing_search_distance_lng' )  != '' ? boldthemes_get_option( 'listing_search_distance_lng' ) : '-0.000500';

$params = array();
if (isset($_GET)){
	BoldThemesFrameworkTemplate::$listing_gets = $_GET;
	foreach($_GET as $field => $value) {
            if ($value != ''){
		$params[$field] = $value;
            }
	}
}

if ( isset($params["bt_bb_listing_field_category"]) && $params["bt_bb_listing_field_category"] == 'all' ){
    $params["bt_bb_listing_field_category"] = '';
}
if ( isset($params["bt_bb_listing_field_region"]) && $params["bt_bb_listing_field_region"] == 'all' ){
    $params["bt_bb_listing_field_region"] = '';
}
 
$listing_search_sort= '-1';
if ( isset($params["bt_bb_listing_field_sort"]) && $params["bt_bb_listing_field_sort"] != '' ){
    $listing_search_sort  = $params["bt_bb_listing_field_sort"]; 
}else{
    $listing_search_sort  = boldthemes_get_option( 'listing_search_sort' );
}
BoldThemesFrameworkTemplate::$listing_search_sort = $listing_search_sort;

$listing_orderby = 'random'; $listing_order      = 'DESC';
switch ( $listing_search_sort  ){
        case '-1':	$listing_orderby = 'post_date';	$listing_order = 'DESC';break;//Date, A-Z
        case '0':	$listing_orderby = 'post_date';	$listing_order = 'ASC';	break;//Date, Z-A
        case '1':	$listing_orderby = 'post_title';$listing_order = 'ASC';	break;//Name, A-Z
        case '2':	$listing_orderby = 'post_title';$listing_order = 'DESC';break;//Name, Z-A
        case '3':	$listing_orderby = 'price_from';$listing_order = 'DESC';break;//Price, A-Z
        case '4':	$listing_orderby = 'price_from';$listing_order = 'ASC';break;//Price, Z-A
        default:	$listing_orderby = 'rand';	$listing_order = 'DESC';break;//Random
}

if ( !empty($params) ){
        /* query listings on first time list loading if get values exist */
        if ( isset($params["bt_bb_listing_field_keyword"]) && $params["bt_bb_listing_field_keyword"] != '' ){
            BoldThemesFrameworkTemplate::$keyword = $params["bt_bb_listing_field_keyword"];
        }

        if ( isset($params["bt_bb_listing_field_region"]) && $params["bt_bb_listing_field_region"] != '' ){
                BoldThemesFrameworkTemplate::$listing_region = $params["bt_bb_listing_field_region"];
        }

        if ( isset($params["bt_bb_listing_field_category"]) && $params["bt_bb_listing_field_category"] != '' ){
                BoldThemesFrameworkTemplate::$listing_category = $params["bt_bb_listing_field_category"];
        }

        if ( isset($params["bt_bb_listing_field_my_lat"]) && $params["bt_bb_listing_field_my_lat"] != '' ){
                BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lat = $params["bt_bb_listing_field_my_lat"];
        }
        
        if ( isset($params["bt_bb_listing_field_my_lng"]) && $params["bt_bb_listing_field_my_lng"] != '' ){
                BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lng = $params["bt_bb_listing_field_my_lng"];
        }
        
        if ( isset($params["bt_bb_listing_field_distance_value"]) && $params["bt_bb_listing_field_distance_value"] != '' ){
                BoldThemesFrameworkTemplate::$bt_bb_listing_field_distance = $params["bt_bb_listing_field_distance_value"];
        }
        if ( isset($params["bt_bb_listing_field_location_autocomplete"]) && $params["bt_bb_listing_field_location_autocomplete"] != '' ){
                BoldThemesFrameworkTemplate::$bt_bb_listing_field_location_autocomplete = $params["bt_bb_listing_field_location_autocomplete"];
        } 
        
        

	$meta_query_params = array();
	$form_query_params = array();
	if (isset($params)){
            foreach($params as $key_name => $key_value) {
                if ( $key_value != '' ) {
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
                
        if ( !empty(BoldThemesFrameworkTemplate::$listing_category) && !empty(BoldThemesFrameworkTemplate::$listing_region) ) {//region and category 
                BoldThemesFrameworkTemplate::$listings = boldthemes_get_query_listings(
                        array( 
                                'taxonomy'	=> 'listing-category', 
                                'listing_type'	=> BoldThemesFrameworkTemplate::$listing_category, 
                                'taxonomy2'	=> 'listing-region', 
                                'listing_type2'	=> BoldThemesFrameworkTemplate::$listing_region, 
                                'search_term'	=> BoldThemesFrameworkTemplate::$keyword,
                                'orderby'	=> $listing_orderby, 
                                'order'		=> $listing_order,
                                'form_query_params' => $form_query_params,
                                'meta_query'	=> $meta_query_params,
                        ) 
                );
        }else if ( !empty(BoldThemesFrameworkTemplate::$listing_category) ) {//category           
                BoldThemesFrameworkTemplate::$listings = boldthemes_get_query_listings(
                        array( 
                                'taxonomy'	=> 'listing-category', 
                                'listing_type'	=> BoldThemesFrameworkTemplate::$listing_category, 
                                'search_term'	=> BoldThemesFrameworkTemplate::$keyword,
                                'orderby'	=> $listing_orderby, 
                                'order'		=> $listing_order,
                                'form_query_params' => $form_query_params,
                                'meta_query'	=> $meta_query_params,
                        ) 
                );
        }else if ( !empty(BoldThemesFrameworkTemplate::$listing_region) ) {//region
                BoldThemesFrameworkTemplate::$listings = boldthemes_get_query_listings(
                        array( 
                                'taxonomy'	=> 'listing-region', 
                                'listing_type'	=> BoldThemesFrameworkTemplate::$listing_region, 
                                'search_term'	=> BoldThemesFrameworkTemplate::$keyword,
                                'orderby'	=> $listing_orderby, 
                                'order'		=> $listing_order,
                                'form_query_params' => $form_query_params,
                                'meta_query'	=> $meta_query_params,
                        ) 
                );
        } else{  
                BoldThemesFrameworkTemplate::$listings	= boldthemes_get_query_listings(
                        array(
                                'search_term'	=> BoldThemesFrameworkTemplate::$keyword,
                                'orderby'	=> $listing_orderby, 
                                'order'		=> $listing_order,
                                'form_query_params' => $form_query_params,
                                'meta_query'	=> $meta_query_params,
                        ) 
                );
        }
        
}else{
    
	/* query listings on first time list loading: /listing-category/, /listing-region/, /listing-tag/ from template */    
	if ( BoldThemesFrameworkTemplate::$listing_category != '' ){ // /listing-category
		BoldThemesFrameworkTemplate::$listings	= boldthemes_get_query_listings(  
			array( 
                            'taxonomy'      => 'listing-category', 
                            'listing_type'  => BoldThemesFrameworkTemplate::$listing_category,
                            'orderby'       => $listing_orderby, 
                            'order'         => $listing_order                            
			) 
		);	
	}else if ( BoldThemesFrameworkTemplate::$listing_tag != '' ){ // /listing-tag
		BoldThemesFrameworkTemplate::$listings	= boldthemes_get_query_listings(  
			array( 
                            'taxonomy'      => 'listing-tag', 
                            'listing_type'  => BoldThemesFrameworkTemplate::$listing_tag,
                            'orderby'       => $listing_orderby, 
                            'order'         => $listing_order  
			) 
		);
	}else if ( BoldThemesFrameworkTemplate::$listing_region != '' ){ // /listing-region
		BoldThemesFrameworkTemplate::$listings	= boldthemes_get_query_listings(  
			array( 
                            'taxonomy'      => 'listing-region', 
                            'listing_type'  => BoldThemesFrameworkTemplate::$listing_region,
                            'orderby'       => $listing_orderby, 
                            'order'         => $listing_order  
			) 
		);
	}else{
		BoldThemesFrameworkTemplate::$listings	= boldthemes_get_query_listings(
			array( 
                            'orderby'       => $listing_orderby, 
                            'order'         => $listing_order  
			) 
		);
	}
}
/* /query listings */

/* first time loading listings */
BoldThemesFrameworkTemplate::$found	= count( BoldThemesFrameworkTemplate::$listings );
BoldThemesFrameworkTemplate::$max_page	= ceil( BoldThemesFrameworkTemplate::$found / BoldThemesFrameworkTemplate::$posts_per_page );
BoldThemesFrameworkTemplate::$listings	= array_slice( BoldThemesFrameworkTemplate::$listings, 0, BoldThemesFrameworkTemplate::$limit, true);
/* /first time loading listings */

BoldThemesFrameworkTemplate::$ajax_random_distance = 0;




