(function( $ ) { 
	"use strict";
        
         var map = null;
        
         window.bt_bb_leaflet_init = function ( map_id, zoom, custom_style, scroll_wheel, tiles_url, zoom_control ) {                      
                        
            var lat_center     = 0;
            var lng_center     = 0;
            var zoom           = zoom;  

            var container   = jQuery( '#' + map_id ).parent();
            var locations   = container.find( '.bt_bb_leaflet_map_location' );
            var features    = new Array(locations.length);

            var center_map = container.data( 'center' );
            if ( center_map == 'no' ) {
                    center_map = false;
            } else {
                    center_map = true;
            }
            var markerClusters = L.markerClusterGroup();
            
            var lat_sum = 0;
            var lng_sum = 0; 
            var n = 0;
            locations.each(function() {
                var lat     = jQuery( this ).data( 'lat' );
                var lng     = jQuery( this ).data( 'lng' );
                var icon    = jQuery( this ).data( 'icon' );
                
                lat_sum += lat;
                lng_sum += lng;
                
                if ( ! center_map && n == 0 ) {
                     lat_center   = lng;
                     lng_center  =  lat;
                } 
                locations.eq( 0 ).addClass( 'bt_bb_leaflet_map_location_show' ); 
                locations.eq( 0 ).addClass( 'bt_bb_map_location_show' );
                
                var myIcon = L.icon({
                    iconUrl: icon,
                    iconRetinaUrl: icon,
                    iconSize: [45, 58],
                    iconAnchor: [9, 21],
                    popupAnchor: [0, -14]
                  });
                
                var m = L.marker( [ lat, lng ], { icon: myIcon, id: n,  lat: lat, lng:lng } )
                    .on("click", markerOnClick);
                    
                markerClusters.addLayer( m );                
                n++;
            });
            
            if ( center_map ) {
                    lat_center  = lat_sum / n;
                    lng_center  = lng_sum / n;
            }

            //if( map == null ){
                map = L.map(document.getElementById( map_id )).setView([lat_center, lng_center], zoom);
            //}
            
           
            var tile_url = tiles_url != '' ? tiles_url : map_leaflet_source_arr[custom_style];
            var attribution = '';
                        
            L.tileLayer( tile_url, {
             attribution: attribution,
             subdomains: ['a','b','c']
            }).addTo( map );
            
            map.addLayer( markerClusters );

            if ( scroll_wheel == '' ) {
                map.scrollWheelZoom.disable();
            }
            if ( zoom_control == '' ) {
                map.removeControl(map.zoomControl);
            } 
            
            $('.leaflet-control-attribution').hide();
           
            function markerOnClick(e) {
                var attributes = e.target.options;
                var id = attributes.id;
                var reload = true;
                if ( locations.eq( id ).hasClass( 'bt_bb_leaflet_map_location_show' ) && !container.hasClass( 'bt_bb_leaflet_map_no_overlay' ) ) reload = false; 
                container.removeClass( 'bt_bb_leaflet_map_no_overlay' );
                locations.removeClass( 'bt_bb_leaflet_map_location_show' );
                if ( reload ) locations.eq( id ).addClass( 'bt_bb_leaflet_map_location_show' );
                
                if ( locations.eq( id ).hasClass( 'bt_bb_map_location_show' ) && !container.hasClass( 'bt_bb_map_no_overlay' ) ) reload = false; 
                container.removeClass( 'bt_bb_map_no_overlay' );
                locations.removeClass( 'bt_bb_map_location_show' );
                if ( reload ) locations.eq( id ).addClass( 'bt_bb_map_location_show' );
            }
} 
}( jQuery ));
