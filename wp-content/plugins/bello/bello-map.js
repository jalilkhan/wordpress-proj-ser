(function( $ ) {
    
    'use strict';

    $( document ).ready(function() {
        if ( $('.rwmb-map-canvas').length != 0 && object_map.map_lat != '' && object_map.map_lng != '' ) {
            $( '.rwmb-map-canvas').data('default-loc', object_map.map_lat + ',' + object_map.map_lng);   
        }                
    });    
        
})( jQuery );