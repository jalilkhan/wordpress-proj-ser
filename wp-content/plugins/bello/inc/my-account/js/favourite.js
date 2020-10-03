 (function( $ ) {
      'use strict';
  $( document ).ready(function(){
      
    $(document).on('click', '.simplefavorite-button2', function(e) {
           e.preventDefault();
           return false;

           /*var data= {
                'action':	'bt_favourites_action',
                'postid':       $(this).parent().data('postid'),
                'siteid':       $(this).parent().data('siteid'),
                'groupid':      $(this).parent().data('groupid'),
                'favourited':   $(this).parent().data('favourited')
            };
            $.ajax({
                    type: 'POST',
                    url: ajax_object.ajax_url,
                    data: data,
                    async: true,
                    success: function( response ) {						
                        if ( response)
                        {
                            $('.favourite_box_' + $(this).data('postid')).html( response );
                        }
                    },
                    error: function( xhr, status, error ) {
                            console.log('error: ' +  status + ' ' + error);
                    }
            });*/

    });
});   
})( jQuery );
