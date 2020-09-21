(function( $ ) {
    
    'use strict';

    $( document ).ready(function() {   
            // listing
            $('.no-touch .bt_bb_listing_search_element select').fancySelect();
            $('.no-touch .bt_bb_listing_options_sorting select').fancySelect();            
           
            $(".bt_bb_listing_search_switch").on( 'click', function() {
                    $(this).toggleClass('on');
                    $(this).parent(".bt_bb_listing_search_parameters").toggleClass('hidden');
            });
            
            $(".bt_bb_listing_options_view_list").on( 'click', function() {
                    $(this).addClass('on');
                    $('.bt_bb_listing_options_view_grid').removeClass('on');
                    $('.bt_bb_listing_view_as_grid').addClass('bt_bb_listing_view_as_list');
                    
                    bt_set_listing_view_cookie('bt_bb_listing_view','list',1);
            });
            
            $(".bt_bb_listing_options_view_grid").on( 'click', function() {    
                    $(this).addClass('on');
                    $('.bt_bb_listing_options_view_list').removeClass('on');
                    $('.bt_bb_listing_view_as_grid').removeClass('bt_bb_listing_view_as_list');
                    
                    bt_set_listing_view_cookie('bt_bb_listing_view','grid',1);
            });	
            
            
             /* helpers */
            
            function bt_set_listing_view_cookie(name,value,days) {
                var expires = "";
                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime() + (days*24*60*60*1000));
                    expires = "; expires=" + date.toUTCString();
                }
                document.cookie = name + "=" + (value || "")  + expires + "; path=/";
            }
       });
    
})( jQuery );
