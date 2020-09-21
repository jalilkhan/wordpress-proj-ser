(function( $ ) { 
	"use strict";
        
        window.bt_bb_openmap_init = function ( map_id, zoom, custom_style, scroll_wheel, custom_osm_map_style ) {
                        
            var mapOptions = {controls: [ 
                 new OpenLayers.Control.Navigation(),             
                 new OpenLayers.Control.Zoom()
             ]
            };
            var map = new OpenLayers.Map( document.getElementById( map_id ), mapOptions); 
            
            var style = map_openmap_source_arr[custom_style];
            
            if ( custom_style == 0 ){
                if ( custom_osm_map_style != '' ){
                    style = custom_osm_map_style;
                }
            }
            var g = new OpenLayers.Layer.OSM("Simple OSM Map", style, {}); 
            map.addLayers([g]);            
            
            var lat     = 0;
            var lng     = 0;
            var zoom    = zoom;                            

            var fromProjection = new OpenLayers.Projection("EPSG:4326");  
            var toProjection   = new OpenLayers.Projection("EPSG:900913");
            
            var lonLat  = new OpenLayers.LonLat(lng, lat).transform( fromProjection, toProjection);
            
            var defaultStyle = new OpenLayers.Style({
                'cursor': 'pointer'
            });

            var selectStyle = new OpenLayers.Style({
                 'cursor': ''
            });

            var styleMap  = new OpenLayers.StyleMap({
                'default': defaultStyle,
                'select': selectStyle
             });

            var vectorMapLayer = new OpenLayers.Layer.Vector("VectorLayer", {
                styleMap: styleMap 
            });   

            var container   = jQuery( '#' + map_id ).parent();
            var locations   = container.find( '.bt_bb_openmap_location' );
            var features    = new Array(locations.length);

            var center_map = container.data( 'center' );
            if ( center_map == 'no' ) {
                    center_map = false;
            } else {
                    center_map = true;
            }

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
                     lonLat  = new OpenLayers.LonLat(lng, lat).transform( fromProjection, toProjection);
                }                
                locations.eq( 0 ).addClass( 'bt_bb_openmap_location_show' ); 
                locations.eq( 0 ).addClass( 'bt_bb_map_location_show' );  
                features[n] =  new OpenLayers.Feature.Vector(
                    new OpenLayers.Geometry.Point( lng, lat  ).transform(fromProjection, toProjection),
                    {
                        id: n
                    } ,                                                    
                    { 
                        cursor:'pointer', 
                        externalGraphic: icon, 
                        graphicHeight: 58, 
                        graphicWidth: 45, 
                        graphicXOffset:-12, 
                        graphicYOffset:-25 
                    }
                );                 
                n++;
            });
            
            if ( center_map ) {
                    lonLat   = new OpenLayers.LonLat(lng_sum / n, lat_sum / n).transform( fromProjection, toProjection);
            }
            
            map.setCenter (lonLat, zoom);            
            vectorMapLayer.addFeatures(features);     
            map.addLayer(vectorMapLayer);
            
            var controls = {
                    selector: new OpenLayers.Control.SelectFeature(vectorMapLayer, {
                        clickout: false, 
                        toggle: true,
                        multiple: false, 
                        hover: false
                    })
            };
            map.addControl(controls['selector']);
            controls['selector'].activate();
            
            vectorMapLayer.events.on({
                'featureselected': function(event) {
                    clickOnPin(event.feature);
                },
                'featureunselected': function(event) {
                    clickOnPin(event.feature);
                }
            });
                        
            var cacheWrite = new OpenLayers.Control.CacheWrite({
                autoActivate: true,
                imageFormat: "image/jpeg",
                eventListeners: {
                    cachefull: function() {
                        //console.log("Cache full.");
                    }
                }
            });
            map.addControl(cacheWrite);
            
            var cacheRead = new OpenLayers.Control.CacheRead();
            map.addControl(cacheRead);
            
            if ( scroll_wheel == 0 ) {
                var NavigationControls = map.getControlsByClass('OpenLayers.Control.Navigation') , i;
                for ( i = 0; i < NavigationControls.length; i++ ) {
                      NavigationControls[i].disableZoomWheel();
                }
            }

            function clickOnPin(features){
                var id = features.attributes.id;
                var reload = true;
                if ( locations.eq( id ).hasClass( 'bt_bb_openmap_location_show' ) && !container.hasClass( 'bt_bb_openmap_no_overlay' ) ) reload = false; 
                container.removeClass( 'bt_bb_openmap_no_overlay' );
                locations.removeClass( 'bt_bb_openmap_location_show' );
                if ( reload ) locations.eq( id ).addClass( 'bt_bb_openmap_location_show' );
                  
                if ( locations.eq( id ).hasClass( 'bt_bb_map_location_show' ) && !container.hasClass( 'bt_bb_map_no_overlay' ) ) reload = false; 
                container.removeClass( 'bt_bb_map_no_overlay' );
                locations.removeClass( 'bt_bb_map_location_show' );
                if ( reload ) locations.eq( id ).addClass( 'bt_bb_map_location_show' );
            }
       } 
}( jQuery ));



