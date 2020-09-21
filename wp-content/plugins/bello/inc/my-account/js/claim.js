/*
 * POPUP Claim Form
 */

(function( $, window, document, undefined ) {
     'use strict';
     $( document ).ready(function(){     
         $( '.woocommerce-message' ).hide();

         //claim
        $(document).on('click', '.bt_bb_link_claim', function(e) {
            e.preventDefault();
            e.stopPropagation();

            $( '.woo-login-popup-sc' ).hide();
            $( '#woo-login-popup-sc-login' ).show();

            $( '#woo-customer-register' ).hide();
            $( '#woo-customer-password' ).hide();

            var logged = $( this ).data('logged');
            if ( logged == 1 ){
                $( '#woo-customer-login' ).hide();
                $( '#woo-customer-claim').fadeIn('fast');
            }else{
                $( '#woo-customer-claim' ).hide();
                $( '#woo-customer-login').fadeIn('fast');
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
        });   
     
        $( document ).on( 'click', '.woo-login-popup-sc-modal-overlay, .woo-login-popup-sc-close a', function( e ){
            e.preventDefault();
            e.stopPropagation();
            woo_login_styler_esc();
        });
         
        $(document).keyup(function(e) {
             if (e.keyCode == 27) { // escape key maps to keycode `27`
                woo_login_styler_esc();
            }
        });
         
        $('.woo-login-popup-sc-toggle').on("click",function(){            
            var href = $(this).attr('href');
            if (typeof href  !== "undefined"){
                if ( href == '#woo-customer-login' ){
                   $( '#woo-customer-register' ).hide();
                   $( '#woo-customer-password' ).hide();
                   $(href).fadeIn('fast');
                }
                if ( href == '#woo-customer-register' ){
                   $( '#woo-customer-login' ).hide();
                   $( '#woo-customer-password' ).hide();
                   $(href).fadeIn('fast');
                }
                if ( href == '#woo-customer-password' ){
                   $( '#woo-customer-login' ).hide();
                   $( '#woo-customer-register' ).hide();
                   $(href).fadeIn('fast');
                }                
            }
        }); 
         
        $( document ).on( 'click', '#submit_claim', function( e ){
             $( 'input[type="submit"]', this ).attr( 'disabled', 'disabled' );
             $('form#claim p.claim_status').show().text(ajax_login_object.loadingmessage);  

             var claim_title        = $('#bt_claim_title').val();
             var claim_details      = $('#bt_claim_details').val();
             var claimed_listing    = $('#claimed_listing').val();
             var claimsecurity      = $('#claimsecurity').val();
             var owner              = $('#owner').val();
            
             var data =  { 
                    'action': 'ajaxsendclaim', 
                    'claim_title': claim_title, 
                    'claim_details': claim_details, 
                    'claimed_listing': claimed_listing,
                    'owner': owner, 
                    'security': claimsecurity
            };            
            
            $.ajax({
                   type: 'POST',
                   dataType: 'json',
                   url: ajax_login_object.ajaxurl,
                   data: data,
                   success: function(data){	
                       if (data){
                           $('form#claim p.claim_status').text(data.message);
                           $('form#claim .woocommerce-message' ).show();
                           if (data.loggedin == true){
                                //document.location.href = ajax_login_object.redirecturl;
                                $( '#woo-customer-claim .form-row' ).hide();
                           }
                       }
                   },
                   error: function( xhr, status, error ) {
                           console.log('error: ' +  status + ' ' + error);
                   }
               });
             
              e.preventDefault();               
         });
         
     });
     
     function woo_login_styler_esc(){
        $( '.woo-login-popup-sc-modal-overlay' ).fadeOut(100);
        $( '.woo-login-popup-sc-modal-on' ).animate({ top :'-200px', 'opacity' : '0' }, 250, function(){
            $( '.woo-login-popup-sc-modal-on' ).css({ 'display' : 'none', 'opacity' : '1' })
        } );
    }
     
})( jQuery, window, document );


