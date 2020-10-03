<?php

function bt_set_cookie_data($params){
    $retVal = array();
    $new_params = isset($params) && !empty($params) ? $params : array(); 

    $user_id = 0;
    $site_id = isset($new_params['siteid']) && !empty($new_params['siteid']) ? $new_params['siteid'] : 0;
    $post_id = isset($new_params['posts']) && !empty($new_params['posts']) ? $new_params['posts'] : 0;
    $cookie  = isset($new_params['cookie_value']) && !empty($new_params['cookie_value']) ? $new_params['cookie_value'] : null;

    $cookie_datas     = bt_get_cookie_data($cookie);    
    $new_cookie_datas = bt_cookie_favorites_with_site_id($cookie_datas, $new_params, $site_id, $user_id);  ;//bt_check_exist($new_params, $cookie_datas);

    $cookie_value  = json_encode( $new_cookie_datas ); 
   
    if ( !empty($cookie_value) ){        
        /*if (isset($_COOKIE['bt-favorites'])) {
            unset($_COOKIE['bt-favorites']);
        }*/
        ob_start();
        setcookie( 
            'bt-favorites',
            $cookie_value, 
            time()+(3600*24*1),
            '/' 
        ); 
        ob_flush();
    }
    
    $is_favorite = bt_is_favorite( $post_id, $site_id, $user_id, $cookie_value);
    $retVal['favorite'] = $is_favorite;
    $retVal['cookie']   = $cookie_value;
    return $retVal;
}

function bt_get_cookie_data($cookie_value = null){ 
    if ( $cookie_value ){
        return (array)json_decode(stripslashes($cookie_value), true);
    }
    if ( isset($_COOKIE['bt-favorites']) ) {
        return (array)json_decode(stripslashes($_COOKIE['bt-favorites']), true);
    }
    return array();
}

function bt_set_meta_data($params = null){    
    $new_params = isset($params) && !empty($params) ? $params : array(); 
    
    $user_id = isset($new_params['userid']) && !empty($new_params['userid']) ? intval($new_params['userid']) : get_current_user_id();
    $site_id = isset($new_params['siteid']) && !empty($new_params['siteid']) ? intval($new_params['siteid']) : 1;
    $post_id = isset($new_params['posts']) && !empty($new_params['posts']) ? intval($new_params['posts']) : 0;
    $meta_key = 'bt_favorites';
    
    $meta_datas     = bt_get_meta_data( $user_id,  $meta_key );//existing meta value     
    $new_meta_datas = bt_meta_favorites_with_site_id($meta_datas, $new_params, $site_id, $user_id);    
    
    $meta_value     = json_encode( $new_meta_datas ); 
    if ( $user_id > 0 && $meta_value != ''){
       
        $updated = update_user_meta($user_id, $meta_key, $meta_value);
    }
    
    $is_favorite = bt_is_favorite( $post_id, $site_id, $user_id); 
    return $is_favorite;
}

function bt_get_meta_data( $user_id, $meta_key){     
    $favorites = get_user_meta( $user_id,  $meta_key );  
    if ( empty($favorites[0]) ) return array();
    return $favorites;    
}


function bt_is_favorite($post_id, $site_id, $user_id, $cookie_value = null){
   
    if ( $user_id > 0 ){   
        $favorites  = bt_get_meta_data( $user_id,  BT_FAVORITES_META_NAME );//existing meta value 
        if ( empty($favorites) ) return 0;
        
        foreach($favorites as $key_favorites => $values){ 
            if ( !is_array($values) ){
                $existing_favorites_arr = (array)json_decode(stripslashes($values), true);
                if ($existing_favorites_arr[0]['siteid'] == $site_id && $existing_favorites_arr[0]['userid'] == $user_id) {
                    $existing_favorites = $existing_favorites_arr[0]['posts'];  
                    if ($existing_favorites){
                        $existing_favorites = explode(",", $existing_favorites);
                        //bt_dump($existing_favorites);
                        if ( is_array($existing_favorites) ){
                            if ( in_array( $post_id, $existing_favorites) ){
                                return 1;
                            }                            
                        }
                    }
                }
            }
        }
    }else{
        $favorites = bt_get_cookie_data($cookie_value);  
        if ( empty($favorites) ) return 0;
        
        $existing_favorites_arr = $favorites;
        if ($existing_favorites_arr[0]['siteid'] == $site_id && $existing_favorites_arr[0]['userid'] == $user_id) {
            $existing_favorites = $existing_favorites_arr[0]['posts'];  
            if ($existing_favorites){
                $existing_favorites = explode(",", $existing_favorites);
                if ( is_array($existing_favorites) ){
                    if ( in_array( $post_id, $existing_favorites) ){
                        return 1;
                    }                            
                }
            }
        }        
    }
    return 0;
}

/**
* Check for Site ID in user's favorites
*/
function bt_cookie_favorites_with_site_id($favorites, $new_favorites, $site_id, $user_id)
{
        $existing_favorites = array();
        $new_post = $new_favorites['posts'];
        
        if ( is_array($favorites) && empty($favorites) ){
            $existing_favorites = array($new_post);
        }
       
        $existing_favorites_arr = $favorites;//(array)json_decode(stripslashes($values), true);  

        if ( bt_key_exists('siteid', $existing_favorites_arr) && bt_key_exists('userid', $existing_favorites_arr) ) {
            if ($existing_favorites_arr[0]['siteid'] == $site_id && $existing_favorites_arr[0]['userid'] == $user_id) {
                $existing_favorites = $existing_favorites_arr[0]['posts'];                    
                if ($existing_favorites){
                    $existing_favorites = explode(",", $existing_favorites);
                    //bt_dump($existing_favorites);
                    if (!empty($existing_favorites)){ 
                        $add = 1;
                        for ($i = 0;  $i < count($existing_favorites); $i++ ){
                            if ($existing_favorites[$i] == $new_post) {
                                unset($existing_favorites[$i]);
                                $add = 0;
                            }
                        }

                        if ($add == 1){
                            array_push($existing_favorites, $new_post);
                        }
                    }else{
                       $existing_favorites = array($new_post);
                    }
                }else{
                    $existing_favorites = array($new_post);
                }
            }
        }           
        
        $new_favorites = array(
                array(
                        'siteid' => $site_id,
                        'userid' => $user_id,
                        'posts'  => implode(",",$existing_favorites)
                )
        );
        
        return $new_favorites;
}

/**
* Check for Site ID in user's favorites
*/
function bt_meta_favorites_with_site_id($favorites, $new_favorites, $site_id, $user_id)
{
        $existing_favorites = array();
        $new_post = $new_favorites['posts'];
        
        if ( is_array($favorites) && empty($favorites) ){
            $existing_favorites = array($new_post);
        }
        foreach($favorites as $key_favorites => $values){  
            $existing_favorites_arr = is_array($values) ? array($new_post) : (array)json_decode(stripslashes($values), true);  
            
            if ( bt_key_exists('siteid', $existing_favorites_arr) && bt_key_exists('userid', $existing_favorites_arr) ) {
                if ($existing_favorites_arr[0]['siteid'] == $site_id && $existing_favorites_arr[0]['userid'] == $user_id) {
                    $existing_favorites = $existing_favorites_arr[0]['posts'];                    
                    if ($existing_favorites){
                        $existing_favorites = explode(",", $existing_favorites);
                        //bt_dump($existing_favorites);
                        if (!empty($existing_favorites)){ 
                            $add = 1;
                            for ($i = 0;  $i < count($existing_favorites); $i++ ){
                                if ($existing_favorites[$i] == $new_post) {
                                    unset($existing_favorites[$i]);
                                    $add = 0;
                                }
                            }
                            
                            if ($add == 1){
                                array_push($existing_favorites, $new_post);
                            }
                        }else{
                           $existing_favorites = array($new_post);
                        }
                    }else{
                        $existing_favorites = array($new_post);
                    }
                }
            }           
        }
        $new_favorites = array(
                array(
                        'siteid' => $site_id,
                        'userid' => $user_id,
                        'posts'  => implode(",",$existing_favorites)
                )
        );
        
        return $new_favorites;
}

function bt_favorites_clear_data( $params = null ){
    $new_params = isset($params) && !empty($params) ? $params : array(); 
    
    $user_id = isset($new_params['userid']) && !empty($new_params['userid']) ? intval($new_params['userid']) : get_current_user_id();
    $site_id = isset($new_params['siteid']) && !empty($new_params['siteid']) ? $new_params['siteid'] : 1;
    
    if ( $user_id > 0 ){
        if (delete_user_meta($user_id, 'bt_favorites')){
            return 1;
        }
    }else{
        if (isset($_COOKIE['bt-favorites'])) {
            unset($_COOKIE['bt-favorites']);
            return 1;
        }
        return 1;
    }
    return 0;
}

function bt_get_site_id($site_id = 0){
    global $blog_id;
    return $site_id > 0 ? $site_id : $blog_id;
}

function bt_get_user_id($user_id = 0){
    return $user_id == 0 ? get_current_user_id() : $user_id; 
}

function bt_get_post_id($post_id = 0){
    return $post_id == 0 ? get_the_id()  : $post_id; 
}

/**
* Multidemensional array key search
* @return boolean
*/
function bt_key_exists($needle, $haystack)
{
	if ( array_key_exists($needle, $haystack) || in_array($needle, $haystack) ){
			return true;
	} else {
			$return = false;
			foreach ( array_values($haystack) as $value ){
					if ( is_array($value) && !$return ) $return = bt_key_exists($needle, $value);
			}
			return $return;
	}
}


