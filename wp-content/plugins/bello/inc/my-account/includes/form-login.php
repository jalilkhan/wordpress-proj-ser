<?php
/**
 * POPUP Login, Register, Lost Password & Claim Forms
 */
$auth       = get_post(get_the_ID());
$auth_name  = !empty( $auth ) ? get_the_author_meta('login', $auth->post_author) : '';
$show_social_connect = ( boldthemes_get_option( 'listing_show_social_logins' ) == 1 && class_exists( 'NextendSocialLogin' ) ) ? true : false;;


?>                          
<div id="woo-login-popup-sc-login" class="woo-login-popup-sc-modal-overlay"></div>

<div class="woo-login-popup-sc-modal woo-login-popup-sc-modal-on"> 
    <span class="woo-login-popup-sc-close"><a href="#"></a></span>
    <div class="woo-login-popup-sc-modal-inner">
        <div class="bt_bb_text">

            <div class="woocommerce"> 
                
                <?php if ( !is_user_logged_in() ) { ?>                
               
                <div class="u-columns col2-set" id="woo-customer-login">
                    <?php wc_print_notices(); ?>                                       
                    <h2><?php _e( 'Login', 'bt_plugin' ); ?></h2>
                    <form class="woocommerce-form woocommerce-form-login login" id="login" action="login" method="post">                               
                        <?php do_action( 'woocommerce_login_form_start' ); ?>
                        <div class="woocommerce-message"><p class="login_status"></p></div> 
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label><?php _e( 'Username or Email Address', 'bt_plugin' ); ?> <span class="required">*</span></label>
                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="bt_username" id="bt_username" value="<?php if ( ! empty( $_POST['bt_username'] ) ) echo esc_attr( $_POST['bt_username'] ); ?>">
                        </p>
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label><?php _e( 'Password', 'bt_plugin' ); ?> <span class="required">*</span></label>
                            <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="bt_password" id="bt_password">
                        </p>        
                        <?php do_action( 'woocommerce_login_form' ); ?>        
                        <p class="form-row">
                            <?php wp_nonce_field( 'woocommerce-login', 'bt-woocommerce-login-nonce' ); ?>			
                            <input type="submit" class="woocommerce-Button button" name="submit_login" id="submit_login" value="<?php esc_attr_e( 'Login', 'bt_plugin' ); ?>">
                            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox inline">
                                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="bt_rememberme" type="checkbox" id="bt_rememberme" value="forever"> 
                                    <span><?php _e( 'Remember me', 'bt_plugin' ); ?></span>
                            </label>
                            <a href="#woo-customer-password" class="woo-login-popup-sc-toggle"> <?php _e( 'Lost your password?', 'bt_plugin' ); ?></a>
                        </p>
                        <?php wp_nonce_field( 'ajax-login-nonce-popup', 'loginsecurity' ); ?>   
                        <?php if ( $show_social_connect ) { 
                                    $login_url = wp_login_url();
                                    $home_url = home_url( '/' );
                            ?>
                            <p class="social">
                                <a class="facebook-signin" href="<?php echo esc_attr( add_query_arg( array( 'loginFacebook' => '1', 'redirect' => $home_url ), $login_url ) ); ?>" onclick="window.location = '<?php echo esc_url_raw( add_query_arg( array( 'loginFacebook' => '1' ), $login_url ) ); ?>&redirect='+window.location.href; return false;"></a>
                                <a class="twitter-signin" href="<?php echo esc_attr( add_query_arg( array( 'loginTwitter' => '1', 'redirect' => $home_url ), $login_url ) ); ?>" onclick="window.location = '<?php echo esc_url_raw( add_query_arg( array( 'loginTwitter' => '1' ), $login_url ) ); ?>&redirect='+window.location.href; return false;"></a>
                                <a class="google-signin" href="<?php echo esc_attr( add_query_arg( array( 'loginGoogle' => '1', 'redirect' => $home_url ), $login_url ) ); ?>" onclick="window.location = '<?php echo esc_url_raw( add_query_arg( array( 'loginGoogle' => '1' ), $login_url ) ); ?>&redirect='+window.location.href; return false;"></a>
                            </p>
                        <?php } ?>
                        <p class="form-row-wide">
                            <?php _e( 'Don\'t have an account yet?', 'bt_plugin' ); ?> 
                            <a href="#woo-customer-register" class="woo-login-popup-sc-toggle">
                                <?php _e( 'Please Register.', 'bt_plugin' ); ?>
                            </a>
                        </p>                         
                        <?php do_action( 'woocommerce_login_form_end' ); ?>        
                    </form>  
                 </div>
                
                 <div class="u-columns col2-set" id="woo-customer-register">
                    <?php wc_print_notices(); ?>   
                    <h2><?php _e( 'Register', 'bt_plugin' ); ?></h2>
                    <form class="register" id="register" action="register" method="post">
                        <?php do_action( 'woocommerce_register_form_start' ); ?>
                        <div class="woocommerce-message"><p class="register_status"></p></div>
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label><?php _e( 'Email Address', 'bt_plugin' ); ?> <span class="required">*</span></label>
                            <input type="email" class="woocommerce-Input woocommerce-Input--text input-text"  id="bt_reg_email" value="<?php if ( ! empty( $_POST['bt_reg_email'] ) ) echo esc_attr( $_POST['bt_reg_email'] ); ?>">
                        </p>
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label><?php _e( 'Password', 'bt_plugin' ); ?> <span class="required">*</span></label>
                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="bt_reg_password" id="bt_reg_password">
                        </p>
                        <?php do_action( 'woocommerce_register_form' ); ?>
                        <?php //do_action( 'register_form' ); ?>
                        <p class="woocommerce-FormRow form-row">
                            <?php wp_nonce_field( 'woocommerce-register', 'bt-woocommerce-register-nonce' ); ?>			
                            <input type="submit" class="woocommerce-Button button" name="submit_register" id="submit_register" value="<?php esc_attr_e( 'Register', 'bt_plugin' ); ?>">
                        </p>                       
                        <?php wp_nonce_field( 'ajax-register-nonce-popup', 'registersecurity' ); ?>
                        <p class="form-row-wide">
                            <?php _e( 'Already have account?', 'bt_plugin' ); ?> 
                            <a href="#woo-customer-login" class="woo-login-popup-sc-toggle">
                                <?php _e( 'Please Login.', 'bt_plugin' ); ?>
                            </a>
                        </p> 
                         <?php do_action( 'woocommerce_register_form_end' ); ?>                                        
                     </form>                         
                 </div>
                
                 <div class="u-columns col2-set" id="woo-customer-password"> 
                    <?php wc_print_notices(); ?> 
                    <h2><?php _e( 'Lost Password', 'bt_plugin' ); ?></h2>
                    <form method="post" class="woocommerce-ResetPassword lost_reset_password" id="forgot_password" action="forgot_password">
                        <div class="woocommerce-message"><p class="forgot_password_status"></p></div>
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Please enter your username or email address. You will receive a link to create a new password via email.', 'bt_plugin' ) ); ?>
                        </p>
                        <p class="woocommerce-FormRow woocommerce-form-row--wide form-row form-row-wide">
                            <label><?php _e( 'Username or Email Address', 'bt_plugin' ); ?>  <span class="required">*</span></label>
                            <input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="bt_user_login" id="bt_user_login" value="<?php if ( ! empty( $_POST['bt_user_login'] ) ) echo esc_attr( $_POST['bt_user_login'] ); ?>">
                        </p>
                        <?php do_action( 'woocommerce_lostpassword_form' ); ?>
                        <p class="woocommerce-form-row form-row">
                            <input type="hidden" name="wc_reset_password" value="true" />
                            <input type="submit" class="woocommerce-Button button" name="submit_lost_password" id="submit_lost_password" value="<?php esc_attr_e( 'Reset password', 'bt_plugin' ); ?>">
                        </p>
                        <?php wp_nonce_field( 'lost_password' ); ?>                                
                        <?php wp_nonce_field('ajax-forgot-nonce-popup', 'forgotsecurity'); ?> 
                        <p class="woocommerce-plogin">
                            <a href="#woo-customer-login" class="woo-login-popup-sc-toggle"><?php _e( 'Login', 'bt_plugin' ); ?></a>
                        </p>
                    </form>                        
                 </div>
                
                 <?php } ?>
                
                 <?php if ( is_user_logged_in() ) { ?>
                
                 <div class="u-columns col2-set" id="woo-customer-claim"> 
                    <?php wc_print_notices(); ?> 
                    <h2><?php _e( 'Claim', 'bt_plugin' ); ?></h2>
                    
                    <form method="post" class="woocommerce-Claim lost_claim" id="claim" action="claim" method="post">  
                        <div class="woocommerce-message"><p class="claim_status"></p></div>
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Please enter claim title and details. Describe why you think that this is your listing and we will review and get back to you as soon as possible.', 'bt_plugin' ) ); ?>
                        </p>
                        <p class="woocommerce-FormRow woocommerce-form-row--wide form-row form-row-wide">
                            <label><?php _e( 'Claim Title', 'bt_plugin' ); ?>  <span class="required">*</span></label>
                            <input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="bt_claim_title" id="bt_claim_title" value="<?php if ( ! empty( $_POST['bt_claim_title'] ) ) echo esc_attr( $_POST['bt_claim_title'] ); ?>">
                        </p>
                        <p class="woocommerce-FormRow woocommerce-form-row--wide form-row form-row-wide">
                            <label><?php _e( 'Claim Details', 'bt_plugin' ); ?>  <span class="required">*</span></label>                           
                            <textarea rows="5" cols="100"  class="woocommerce-Input woocommerce-Input--textarea input-textarea" name="bt_claim_details" id="bt_claim_details"><?php if ( ! empty( $_POST['bt_claim_details'] ) ) echo esc_attr( $_POST['bt_claim_details'] ); ?></textarea>
                        </p>
                        <?php do_action( 'woocommerce_claim_form' ); ?>
                        <p class="woocommerce-form-row form-row">
                            <input type="hidden" name="owner" id="owner" value="<?php echo esc_html($auth_name);?>" />
                            <input type="hidden" name="claimed_listing" id="claimed_listing" value="<?php echo get_the_ID();?>" />
                            <input type="submit" class="woocommerce-Button button" name="submit_claim" id="submit_claim" value="<?php esc_attr_e( 'Claim', 'bt_plugin' ); ?>">
                        </p>                         
                        <?php wp_nonce_field('ajax-claim-nonce', 'claimsecurity'); ?>                         
                    </form>                        
                 </div>
                
                <?php } ?>
                
            </div>

       </div> 
    </div>
</div>
<?php

wc_clear_notices();

