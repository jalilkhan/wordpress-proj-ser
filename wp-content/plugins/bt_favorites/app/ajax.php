<?php

add_action('wp_ajax_bt_set_cookie_action', 'bt_set_cookie_action_callback'); 
add_action('wp_ajax_nopriv_bt_set_cookie_action', 'bt_set_cookie_action_callback'); 

function bt_set_cookie_action_callback()
{   
    $params = array(); 
    
    if (isset($_POST)){
           foreach($_POST as $field => $value) { 
               if ( $value != '' && $value != null && $field != 'action'){
                   $params[$field] = $value;
               }
           }           
           if ( $params ) {
               $bt_set_cookie_data = bt_set_cookie_data($params);
               $bt_set_cookie_data = json_encode($bt_set_cookie_data);
               if(is_array($bt_set_cookie_data)){
                    print_r($bt_set_cookie_data);   
               }else{
                    echo $bt_set_cookie_data;
               }
               die;
           }            
    }
    echo 0;
    die;   
}

add_action('wp_ajax_bt_set_meta_value_action', 'bt_set_meta_value_action_callback'); 
add_action('wp_ajax_nopriv_bt_set_meta_value_action', 'bt_set_meta_value_action_callback'); 

function bt_set_meta_value_action_callback()
{   
    $params = array(); 
    if (isset($_POST)){
           foreach($_POST as $field => $value) { 
               if ( $value != '' && $value != null && $field != 'action'){
                   $params[$field] = $value;
               }
           }           
           if ( $params ) {
               $bt_set_meta_data = bt_set_meta_data($params);
               echo $bt_set_meta_data;
               die;
           }            
    }
    echo 0;
    die;
}

add_action('wp_ajax_bt_favorites_clear_action', 'bt_favorites_clear_action_callback'); 
add_action('wp_ajax_nopriv_bt_favorites_clear_action', 'bt_favorites_clear_action_callback'); 

function bt_favorites_clear_action_callback()
{   
    $params = array(); 
    if (isset($_POST)){
           foreach($_POST as $field => $value) { 
               if ( $value != '' && $value != null && $field != 'action'){
                   $params[$field] = $value;
               }
           }           
           if ( $params ) {
               $bt_favorites_clear_data = bt_favorites_clear_data($params);
               echo $bt_favorites_clear_data;
               die;
           }            
    }
    echo 0;
    die;
}
