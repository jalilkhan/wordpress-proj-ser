function bt_bb_gmap_init() { 
    return true;
}
function setMarker(lon, lat, count){

    var lonLatMarker = new OpenLayers.LonLat(lon, lat).transform(map.displayProjection,  map.projection);;
    var feature = new OpenLayers.Feature(markers, lonLatMarker);
    feature.closeBox = true;
    feature.popupClass = OpenLayers.Class(OpenLayers.Popup.AnchoredBubble, {minSize: new OpenLayers.Size(300, 180) } );
    feature.data.popupContentHTML = 'Hello World';
    feature.data.overflow = "hidden";

    var icon = new OpenLayers.Icon("/src/includes/lib/map/export_badge.php?number="+count);
    var marker = new OpenLayers.Marker(lonLatMarker, icon);
    marker.feature = feature;

    var markerClick = function(evt) {
        if (this.popup == null) {
            this.popup = this.createPopup(this.closeBox);
            map.addPopup(this.popup);
            this.popup.show();
        } else {
            this.popup.toggle();
        }
        OpenLayers.Event.stop(evt);
    };
    marker.events.register("mousedown", feature, markerClick);

    markers.addMarker(marker);
}

