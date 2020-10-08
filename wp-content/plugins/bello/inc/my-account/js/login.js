/*
 * POPUP Login, Register & Lost Password Forms
 */

(function( $, window, document, undefined ) {
    'use strict';
    $( document ).ready(function(){

        $( '.woocommerce-message' ).hide();

        // show claim
        $(document).on('click', '.bt_bb_link_claim', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var logged = $( this ).data('logged');
            bt_show_my_account_popup_form('claim', logged);
        });

        // show login 
        $( document ).on( 'click', '.btMyAccountLogin, .btMyAccountLogin a', function( e ){
            e.preventDefault();
            e.stopPropagation();
            bt_show_my_account_popup_form('login');
        });

        // show register
        $( document ).on( 'click', '.btMyAccountRegister, .btMyAccountRegister a', function( e ){
            e.preventDefault();
            e.stopPropagation();
            bt_show_my_account_popup_form('register');
        });

        // show lost password
        $( document ).on( 'click', '.btMyAccountLostPassword, .btMyAccountLostPassword a', function( e ){
            e.preventDefault();
            e.stopPropagation();
            bt_show_my_account_popup_form('password');
        });

        // close popup
        $( document ).on( 'click', '.woo-login-popup-sc-modal-overlay, .woo-login-popup-sc-close a', function( e ){
            e.preventDefault();
            e.stopPropagation();
            bt_login_styler_esc();
        });

        // close popup with esc button
        $(document).keyup(function(e) {
            if (e.keyCode == 27) {
                bt_login_styler_esc();
            }
        });

        // toggle between forms 
        $('.woo-login-popup-sc-toggle').on("click",function(){
            var href = $(this).attr('href');
            if (typeof href  !== "undefined"){
                if ( href == '#woo-customer-login' ){
                    $( '#woo-customer-register' ).hide();
                    $( '#woo-customer-password' ).hide();
                }
                if ( href == '#woo-customer-register' ){
                    $( '#woo-customer-login' ).hide();
                    $( '#woo-customer-password' ).hide();
                }
                if ( href == '#woo-customer-password' ){
                    $( '#woo-customer-login' ).hide();
                    $( '#woo-customer-register' ).hide();
                }
                $(href).fadeIn('fast');
            }
        });

        // submit login
        $( document ).on( 'click', '#submit_login', function( e ){
            e.preventDefault();
            $( 'input[type="submit"]', this ).attr( 'disabled', 'disabled' );
            $('form#login p.login_status').show().text(ajax_login_object.loadingmessage);
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: ajax_login_object.ajaxurl,
                data: {
                    'action': 'ajaxloginpopup', //calls wp_ajax_nopriv_ajaxlogin_popup
                    'username': $('form#login #bt_username').val(),
                    'password': $('form#login #bt_password').val(),
                    'loginsecurity': $('form#login #loginsecurity').val()
                },
                success: function(data){
                    $('form#login p.login_status').text(data.message);
                    $('form#login .woocommerce-message' ).show();
                    if (data.loggedin == true){
                        document.location.href = ajax_login_object.redirecturl;
                    }
                }
            });

        });

        // Attach change event on account type selection in register popup
        $( document ).on( 'change', 'form#register #bt_reg_account_type', function( e ){
            if($(this).val() == 'service_provider') {
                $('.woocommerce-form-row-packages').removeClass('div-hide');
            } else {
                $('.woocommerce-form-row-packages').addClass('div-hide');
            }
        });
        // submit register
        $( document ).on( 'click', '#submit_register', function( e ){
            e.preventDefault();
            $( 'input[type="submit"]', this ).attr( 'disabled', 'disabled' );
            $('form#register p.register_status').show().text(ajax_login_object.loadingmessage);
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: ajax_login_object.ajaxurl,
                data: {
                    'action': 'ajaxregisterpopup', //calls wp_ajax_nopriv_ajaxregister_popup
                    'username': $('form#register #bt_reg_email').val(),
                    'password': $('form#register #bt_reg_password').val(),
                    'account_type': $('form#register #bt_reg_account_type').val(),
                    'plan': $('form#register #bt_reg_plan').val(),
                    'registersecurity': $('form#register #registersecurity').val()
                },
                success: function(data){
                    $('form#register p.register_status').text(data.message);
                    $('form#register .woocommerce-message' ).show();
                    if (data.loggedin == true){
                        if($('form#register #bt_reg_account_type').val() == 'service_provider'
                            && $('form#register #bt_reg_plan').val() == 'standard') {
                            document.location.href = document.location.origin + '/?add-to-cart=5098';
                        } else if($('form#register #bt_reg_account_type').val() == 'service_provider'
                            && $('form#register #bt_reg_plan').val() == 'premium') {
                            document.location.href = document.location.origin + '/?add-to-cart=5154';
                        } else if($('form#register #bt_reg_account_type').val() == 'service_provider'
                            && $('form#register #bt_reg_plan').val() == 'premium_plus') {
                            document.location.href = document.location.origin + '/?add-to-cart=5235';
                        }
                        else {
                            document.location.href = ajax_login_object.redirecturl_register;
                        }
                    }
                }
            });
        });

        // submit lost password
        $( document ).on( 'click', '#submit_lost_password', function( e ){
            e.preventDefault();
            $( 'input[type="submit"]', this ).attr( 'disabled', 'disabled' );
            $('form#forgot_password p.forgot_password_status').show().text(ajax_login_object.loadingmessage);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: ajax_login_object.ajaxurl,
                data: {
                    'action': 'ajaxforgotpasswordpopup', //calls wp_ajax_nopriv_ajaxforgotpassword_popup
                    'user_login': $('#bt_user_login').val(),
                    'forgotsecurity': $('#forgotsecurity').val(),
                },
                success: function(data){
                    $('form#forgot_password p.forgot_password_status').text(data.message);
                    $('form#forgot_password .woocommerce-message' ).show();
                    if (data.loggedin == true){
                        document.location.href = ajax_login_object.redirecturl_lost_password;
                    }
                }
            });
        });

    });

    function bt_show_my_account_popup_form( type, logged ) {
        $( '.woo-login-popup-sc' ).hide();
        $( '#woo-login-popup-sc-login' ).show();

        $( '#woo-customer-login' ).hide();
        $( '#woo-customer-register' ).hide();
        $( '#woo-customer-password' ).hide();
        $( '#woo-customer-claim' ).hide();

        if ( type == 'claim'){
            if ( logged == 1 ){
                $( '#woo-customer-claim').fadeIn('fast');
            }else{
                $( '#woo-customer-login').fadeIn('fast');
            }
        }else{
            $( '#woo-customer-' + type).fadeIn('fast');
        }

        if( $( '.woo-login-popup-sc-modal-off' ).length > 0 ){
            $('html, body').animate({
                scrollTop: $( '.woo-login-popup-sc-modal-off' ).offset().top - 30
            }, 400);
            $( '.woo-login-popup-sc-modal-overlay' ).show();
        }else{
            var ftop = '100px';
            if ( $(window).width() < 700 ){
                ftop = '20px';
            }
            $( '.woo-login-popup-sc-modal-overlay, .woo-login-popup-sc-modal-on' ).show();
            $( '.woo-login-popup-sc-modal-on' ).animate({ top : ftop }, 400 );
        }
    }


    function bt_login_styler_esc(){
        $( '.woo-login-popup-sc-modal-overlay' ).fadeOut(100);
        $( '.woo-login-popup-sc-modal-on' ).animate({ top :'-200px', 'opacity' : '0' }, 250, function(){
            $( '.woo-login-popup-sc-modal-on' ).css({ 'display' : 'none', 'opacity' : '1' })
        } );
    }

})( jQuery, window, document );


