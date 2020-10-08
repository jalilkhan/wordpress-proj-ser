<?php
/* AJAX POPUP LOGIN, REGISTER & LOST PASSWORD */

if ( ! function_exists( 'bello_ajax_login_init' ) ) {
    function bello_ajax_login_init(){ 
       
        $myaccount_page_url = get_option( 'woocommerce_myaccount_page_id' ) ? get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) : home_url();
        $myaccount_lost_password_page_url = $myaccount_page_url . 'lost-password/?reset-link-sent=true';

        wp_enqueue_style( 'my-account-login-styles', plugin_dir_url( __FILE__ ) . 'my-account/css/login.css' , array(), null );
        
        if ( is_user_logged_in() ) {
            wp_register_script('my-account-ajax-login-script', plugin_dir_url( __FILE__ ) . 'my-account/js/claim.js', array('jquery') ); 
        }else{
            wp_register_script('my-account-ajax-login-script', plugin_dir_url( __FILE__ ) . 'my-account/js/login.js', array('jquery') ); 
        }
        wp_enqueue_script('my-account-ajax-login-script');
        wp_localize_script( 'my-account-ajax-login-script', 'ajax_login_object', array( 
            'ajaxurl'                       => admin_url( 'admin-ajax.php' ),
            'redirecturl'                   => esc_url(boldthemes_current_page_server_uri()),
            'redirecturl_register'          => esc_url($myaccount_page_url),
            'redirecturl_lost_password'     => esc_url($myaccount_lost_password_page_url),
            'loadingmessage'                => esc_html__('Sending user info, please wait...', 'bt_plugin')
        ));        
        
        // Enable the user with no privileges to run in AJAX
        add_action( 'wp_ajax_nopriv_ajaxloginpopup', 'bello_ajax_login' );    
        add_action( 'wp_ajax_nopriv_ajaxregisterpopup', 'bello_ajax_register' );
        add_action( 'wp_ajax_nopriv_ajaxforgotpasswordpopup', 'bello_ajax_forgot_password' );
        
        add_action( 'wp_ajax_ajaxsendclaim', 'bello_ajax_send_claim' );
        add_action( 'wp_ajax_nopriv_ajaxsendclaim', 'bello_ajax_send_claim' );
    }
}


/*
 *  login - ajax woo popup
 */
if ( ! function_exists( 'bello_ajax_login' ) ) {
    function bello_ajax_login(){
        global $wpdb;
        check_ajax_referer( 'ajax-login-nonce-popup', 'loginsecurity' );
        
        $post_user  = apply_filters( 'pre_user_user_login', $_POST['username'] );
        $post_pass  = apply_filters( 'pre_user_user_pass', $_POST['password'] );

        $info = array();
        $info['user_login']     = $post_user;
        $info['user_password']  = $post_pass;
        $info['remember']       = true;
        $user_signon = wp_signon( $info, false );
        
        if ( is_wp_error($user_signon) ){            
            $error = strip_tags($user_signon->get_error_message());
			if( ! empty( $error ) ) {
                //echo json_encode(array('loggedin'=>false, 'message'=> $error ));
				echo json_encode(array('loggedin'=>false, 'message'=> esc_html__('Wrong username or password.', 'bt_plugin') ));
            }else{
                echo json_encode(array('loggedin'=>false, 'message'=>esc_html__('Wrong username or password.', 'bt_plugin')));
            }
        } else {
            wp_set_current_user( $user_signon->ID, $user_signon->user_login );
            wp_set_auth_cookie( $user_signon->ID );    
            echo json_encode(array('loggedin'=>true, 'message'=>esc_html__('Login successful, redirecting...', 'bt_plugin')));
        }
        die();
    }
}

/*
 *  register - ajax woo popup
 */
if ( ! function_exists( 'bello_ajax_register' ) ) {
    function bello_ajax_register(){
        global $wpdb;
        check_ajax_referer( 'ajax-register-nonce-popup', 'registersecurity' );

        $post_user  = apply_filters( 'pre_user_user_login', $_POST['username'] );
        $post_pass  = apply_filters( 'pre_user_user_pass', $_POST['password'] );
        $post_email = apply_filters( 'pre_user_user_email', $_POST['username'] );
        $account_type = $_POST['account_type'];
        $plan = $_POST['plan'];

        $info = array();
        $info['user_login']     = $post_user;
        $info['user_pass']      = $post_pass;
        $info['user_email']     = $post_email;
        $info['role']           = 'customer';

        $new_user_id = wp_insert_user( $info );

        if ( is_wp_error($new_user_id) ){
            $error = strip_tags($new_user_id->get_error_message());
            if( ! empty( $error ) ) {
                echo json_encode(array('loggedin'=>false, 'message'=> esc_html__('Wrong username or password.', 'bt_plugin') ));
            }else{
                echo json_encode(array('loggedin'=>false, 'message'=>esc_html__('Wrong username or password.', 'bt_plugin')));
            }
        } else {
            do_action( 'user_register', $new_user_id );

            $info_user = array();
            $info_user['user_login']    = $post_user;
            $info_user['user_password'] = $post_pass;
            $info_user['remember'] = true;
            $user_signon = wp_signon( $info_user, false );  

            if ( is_wp_error($user_signon) ){
                $error = strip_tags($user_signon->get_error_message());            
                if( ! empty( $error ) ) {
                    echo json_encode(array('loggedin'=>false, 'message'=> esc_html__('Wrong username or password.', 'bt_plugin') ));
                } else {
                    echo json_encode(array('loggedin'=>false, 'message'=>esc_html__('Wrong username or password.', 'bt_plugin')));
                }
            }else{
                wp_set_current_user( $user_signon->ID, $user_signon->user_login );
                wp_set_auth_cookie( $user_signon->ID );    
            
                $account_listing_endpoint = bt_account_listing_endpoint();
                $blog_url = function_exists('wc_get_endpoint_url') ? wc_get_endpoint_url( $account_listing_endpoint, '', wc_get_page_permalink( 'myaccount' ) ) : '';
                $blog_title = get_bloginfo( 'name' );                
                $to = $post_email;
                $subject = esc_html__('Registering successful ', 'bt_plugin')  . $blog_title;
                $message =  "<br />" . esc_html__(' Your user name is: ', 'bt_plugin') . $post_user;
                $message .= "<br />" . esc_html__(' Your password is: ', 'bt_plugin') . $post_pass;
                if ( $blog_url != ''){
                    $message .= "<br />" . esc_html__(' URL: ', 'bt_plugin') . $blog_url;
                }
                $mail = bt_mail( $to, $subject, $message );
                
                echo json_encode(array('loggedin'=>true, 'message'=>esc_html__('Registering successful, redirecting...', 'bt_plugin')));
            }
        }

        die();
    }
}

/*
 *  lost password - ajax woo popup
 */
if ( ! function_exists( 'bello_ajax_forgot_password' ) ) {
    function  bello_ajax_forgot_password(){
        global $wpdb;

        check_ajax_referer( 'ajax-forgot-nonce-popup', 'forgotsecurity' );

        $account = apply_filters( 'pre_user_user_login', $_POST['user_login'] );
        
        if( empty( $account ) ) {
                $error = esc_html__('Enter an username or e-mail address.', 'bt_plugin' );
        } else {
                if(is_email( $account )) {
                        if( email_exists($account) ) 
                                $get_by = 'email';
                        else	
                                $error =  esc_html__('There is no user registered with that email address.', 'bt_plugin' );			
                }
                else if (validate_username( $account )) {
                        if( username_exists($account) ) 
                                $get_by = 'login';
                        else	
                                $error =  esc_html__('There is no user registered with that username.', 'bt_plugin' );			
                }
                else
                        $error =  esc_html__('Invalid username or e-mail address.', 'bt_plugin' );	
        }

        if(empty ($error)) {
               
                $random_password = wp_generate_password();
                $user = get_user_by( $get_by, $account );

                $update_user = wp_update_user( array ( 'ID' => $user->ID, 'user_pass' => $random_password ) );
                if( $update_user ) {
                         $account_listing_endpoint = bt_account_listing_endpoint();
                        $blog_url = function_exists('wc_get_endpoint_url') ? wc_get_endpoint_url( $account_listing_endpoint, '', wc_get_page_permalink( 'myaccount' ) ) : '';
                        $blog_title = get_bloginfo( 'name' );   
                
                        $to = $user->user_email;
                        $subject = esc_html__('Your new password ', 'bt_plugin' )  . $blog_title;
                        $message = esc_html__('Your new password is: '.$random_password, 'bt_plugin' );
                        if ( $blog_url != ''){
                            $message .= "<br />" . esc_html__(' URL: ', 'bt_plugin') . $blog_url;
                        }

                        $mail = bt_mail( $to, $subject, $message );
                        if( $mail ) 
                                $success = esc_html__('Check your email address for you new password.', 'bt_plugin' );
                        else
                                $error = esc_html__('System is unable to send you mail containg your new password.', 'bt_plugin' );					
                } else {
                        $error = esc_html__('Oops! Something went wrong while updateing your account.', 'bt_plugin' );
                }
            }

            if( ! empty( $error ) )
                echo json_encode(array('loggedin'=>false, 'message'=> $error ));

            if( ! empty( $success ) )
                echo json_encode(array('loggedin'=>true,  'message'=> $success ));

            die();

    }
}


/*
 *  claim - ajax woo popup
 */
if ( ! function_exists( 'bello_ajax_send_claim' ) ) {
    function bello_ajax_send_claim(){
        if ( $_POST['claim_title'] == '' || $_POST['claim_details'] == '' ){
            echo json_encode(array('loggedin'=>false, 'message'=>esc_html__('Enter claim title and details.', 'bt_plugin')));
            die;
        }
        global $wpdb;       
        check_ajax_referer( 'ajax-claim-nonce', 'security' );
        $info = array();
        $info['claim_title']     = $_POST['claim_title'];
        $info['claim_details']   = $_POST['claim_details'];
        $info['claimed_listing'] = $_POST['claimed_listing'];
        
        $info['claimer']         = get_current_user_id();
        $info['owner']           = $_POST['owner'];
        $info['claim_status']    = 'pending';
                
        $new_claim = bt_save_claim($_POST['claimed_listing'] , $info);        
        if ( !$new_claim ){
            echo json_encode(array('loggedin'=>false, 'message'=>esc_html__('Oops! Something went wrong while your claim has been processed. Try again.', 'bt_plugin')));
        } else {    
             echo json_encode(array('loggedin'=>true, 'message'=>esc_html__('Claiming successful. Your claim for this listing is pending.', 'bt_plugin')));           
        }
        
        die();
    }
}

/* /AJAX POPUP LOGIN, REGISTER & LOST PASSWORD */

