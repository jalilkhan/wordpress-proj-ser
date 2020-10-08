/**
* Utility Methods
*/
(function( $ ) {
    //simple button click
    $(document).on('click', '.bt-simplefavorite-button', function(event) {
        event.preventDefault();
        var button = $( this );
        var userid = button.data( 'userid' );        
        if ( userid > 0 ){ //meta value, logged user
            bt_set_meta_value(button, '.bt-simplefavorite-button');
        }else{ //cookie, anonymus user
            bt_set_cookie_value(button, '.bt-simplefavorite-button')
        }

    });
    
    //shortcode button click
    $(document).on('click', '.bt-simplefavorite-button-sh', function(event) {
        event.preventDefault();
        var button = $( this );
        var userid = button.data( 'userid' );        
        if ( userid > 0 ){ //meta value, logged user            
            bt_set_meta_value(button, '.bt-simplefavorite-button-sh');
        }else{ //cookie value, anonymus user            
            bt_set_cookie_value(button, '.bt-simplefavorite-button-sh')
        }

    });
    
    function bt_set_cookie_value(button, button_class) {
        var cookie_value = bt_get_cookie('bt-favorites');        
        bt_del_cookie('bt-favorites', '', 0);
        var data= {
                'action':       'bt_set_cookie_action',
                'posts':        button.data( 'postid' ),
                'siteid':       button.data( 'siteid' ),
                'userid':       button.data( 'userid' ),
                'cookie_value': cookie_value
        };
        
        $.ajax({
                type: 'POST',
                url: ajax_object_favorites.ajax_url,
                data: data,
                async: true,
                dataType: 'JSON',
                success: function( response ) {						
                    if ( response )
                    { 
                        bt_set_classes( response.favorite, button_class);
                    }
                },
                error: function( xhr, status, error ) {
                        console.log('error: ' +  status + ' ' + error);
                }
          });
    }
    
    function bt_set_meta_value(button, button_class) {
         var data= {
                'action':   'bt_set_meta_value_action',
                'posts':    button.data( 'postid' ),
                'siteid':   button.data( 'siteid' ),
                'userid':   button.data( 'userid' )
        };
        
        $.ajax({
                type: 'POST',
                url: ajax_object_favorites.ajax_url,
                data: data,
                async: true,
                success: function( response ) {	
                    if ( response )
                    { 
                        bt_set_classes( response, button_class);
                    }
                },
                error: function( xhr, status, error ) {
                        console.log('error: ' +  status + ' ' + error);
                }
          });
    }
    
    function bt_set_classes( response, button_class) {        
        if ( response == 1 ) {
            $('.bt-simplefavorite-button' ).addClass('active');
            $('.bt-simplefavorite-button a').removeClass('bt_bb_listing_favourite').addClass('bt_bb_listing_favourite_on');
            
            $('.bt-simplefavorite-button-sh' ).addClass('active');
            $('.bt-simplefavorite-button-sh a').removeClass('bt_bb_listing_favourite').addClass('bt_bb_listing_favourite_on');
            
            $('.bt-simplefavorite-button a span').text(ajax_object_favorites.text_added);
            $('.bt-simplefavorite-button-sh a span').text(ajax_object_favorites.text_added_sh);

        }else{
            $('.bt-simplefavorite-button' ).removeClass('active');
            $('.bt-simplefavorite-button a').removeClass('bt_bb_listing_favourite_on').addClass('bt_bb_listing_favourite');
            
            $('.bt-simplefavorite-button-sh' ).removeClass('active');
            $('.bt-simplefavorite-button-sh a').removeClass('bt_bb_listing_favourite_on').addClass('bt_bb_listing_favourite');
            
            $('.bt-simplefavorite-button a span').text(ajax_object_favorites.text_add);
            $('.bt-simplefavorite-button-sh a span').text(ajax_object_favorites.text_add_sh);
        }
    }
    
    function bt_set_cookie(sKey, sValue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = encodeURIComponent(sKey) + "=" + sValue + ";" + expires + ";domain='';path=/";
    }

    function bt_get_cookie(sKey) {
        if (!sKey) { return null; }
        return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null;
    }
    
    function bt_delete_cookie(sKey, sPath, sDomain) {
        document.cookie = encodeURIComponent(sKey) + 
                  "=; expires=Thu, 01 Jan 1970 00:00:00 GMT; domain=''; path='/'";
    }
    
    function bt_del_cookie(cname, cvalue, exMins) {
        var d = new Date();
        d.setTime(d.getTime() + (exMins*60*1000));
        var expires = "expires="+d.toUTCString();  
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
})( jQuery );


    




