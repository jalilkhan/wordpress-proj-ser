/**
* Utility Methods
*/
(function( $ ) {
    
     $(document).on('click', '.bt_favorites_clear', function(event) {
            event.preventDefault();
            
            var button = $( this );
            var userid = button.data( 'userid' );
            var siteid = button.data( 'siteid' );

            bt_favorites_clear( userid, siteid );

        });
        
      function bt_favorites_clear( userid, siteid ) {
           if ( userid == 0 ){
                bt_del_cookie('bt-favorites', '', 0);
           }
           
           var data= {
                    'action':       'bt_favorites_clear_action',
                    'siteid':       siteid,
                    'userid':       userid
           };
            
           $.ajax({
                type: 'POST',
                url: ajax_object_favorites_list.ajax_url,
                data: data,
                async: true,
                success: function( response ) {						
                    if ( response )
                    {  
                        if ( response == 1 ){
                            $('#bt_favorites_list').empty();
                            
                            $('.bt_favorites_clear').hide();                            
                            $('.bt_favorites_empty').text(ajax_object_favorites_list.text_empty);
                        }
                    }
                },
                error: function( xhr, status, error ) {
                        console.log('error: ' +  status + ' ' + error);
                }
          });
        }
        
        function bt_del_cookie(cname, cvalue, exMins) {
            var d = new Date();
            d.setTime(d.getTime() + (exMins*60*1000));
            var expires = "expires="+d.toUTCString();  
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            
            window.location = "";
        }

})( jQuery );


