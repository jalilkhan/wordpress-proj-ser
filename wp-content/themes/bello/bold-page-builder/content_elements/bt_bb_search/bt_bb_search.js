(function( $ ) {

'use strict';

$( document ).ready(function() {

	$(document).on("click",".bt_bb_link_search",function(evt){
		evt.preventDefault();  
                var formid = $(this).data("formid");
                $('#simple-search-form-' + formid).trigger( 'submit' );
	});

	$('.bt_bb_listing_search_element select').fancySelect(); 
       

});

$(document).keyup(function(e) {
        e.preventDefault();
        if (e.keyCode == 13) { 
            if ($('#bt_bb_link_simple_search').length > 0) { 
                $('#bt_bb_link_simple_search').trigger( 'click' );
                return false;
            }
       }
});

})( jQuery );

var autocomplete_location;
var autocomplete_form_code;

/* -- fill autocomplete location */
function bt_bb_autocomplete_change_sh_location(form_code) {
    autocomplete_form_code = form_code;   
    var input_location = /** @type {HTMLInputElement} */(document.getElementById('bt_bb_listing_field_location_autocomplete-' + autocomplete_form_code));    
    if ( input_location )
    {
        autocomplete_location = new google.maps.places.Autocomplete(input_location);
        if ( autocomplete_location )
        {
           autocomplete_location.addListener('place_changed', bt_bb_autocomplete_fill_sh_my_location);
        }else{
            console.log("error: search_autocomplete_location not defined");
            return;
        }
    }	
}

function bt_bb_autocomplete_fill_sh_my_location() {
    var place = autocomplete_location.getPlace();
    if ( typeof place === 'undefined' ){
        console.log("error: Autocomplete's not returned place");
        return;
    }
    if (!place.geometry) {
        console.log("error: Autocomplete's returned place contains no geometry");
        return;
    }

    if ( document.getElementById('bt_bb_listing_field_location_autocomplete-' + autocomplete_form_code).value != ''  ){
            var my_lat = document.getElementById('bt_bb_listing_field_my_lat-' + autocomplete_form_code);
            var my_lng = document.getElementById('bt_bb_listing_field_my_lng-' + autocomplete_form_code);
            if (place.geometry.location) {
                my_lat.value = place.geometry.location.lat();
                my_lng.value = place.geometry.location.lng();
            }
    }
}

/* -- fill autocomplete with detecting user location */
function bt_sh_get_my_position(user_position, form_code) {
    autocomplete_form_code = form_code;   
    jQuery('#bt_bb_show_location-' + autocomplete_form_code).addClass('location_detecting');
    
    var my_lat	= document.getElementById('bt_bb_listing_field_my_lat-' + autocomplete_form_code);
    var my_lng	= document.getElementById('bt_bb_listing_field_my_lng-' + autocomplete_form_code); 
    
    var options = {
        enableHighAccuracy: true,
        timeout: 100000,
        maximumAge: 0
    };  
    
    if ( user_position == 0 ){
        //customizer position
        my_lat.value = ajax_sh_object.ajax_sh_lat;
	my_lng.value = ajax_sh_object.ajax_sh_lng;
        bt_bb_sh_geocode_latlng(my_lat.value,my_lng.value, 1);
    }else{
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(bt_sh_show_my_position, bt_sh_my_error, options);            
        }
    } 
}
function bt_sh_show_my_position(position) {    
    var my_lat	= document.getElementById('bt_bb_listing_field_my_lat-' + autocomplete_form_code);
    var my_lng	= document.getElementById('bt_bb_listing_field_my_lng-' + autocomplete_form_code); 
    
    var crd = position.coords;
    // user position
    my_lat.value = crd.latitude;
    my_lng.value = crd.longitude;	        
    bt_bb_sh_geocode_latlng(my_lat.value,my_lng.value, 0);
}

function bt_bb_sh_geocode_latlng(lat, lng, reset) {
   
    if ( reset == 1 ){
        document.getElementById('bt_bb_listing_field_my_lat-' + autocomplete_form_code).value = document.getElementById('bt_bb_listing_field_my_lat_default-' + autocomplete_form_code).value;
        document.getElementById('bt_bb_listing_field_my_lng-' + autocomplete_form_code).value = document.getElementById('bt_bb_listing_field_my_lng_default-' + autocomplete_form_code).value;
        
        document.getElementById('bt_bb_listing_field_location_autocomplete-' + autocomplete_form_code).value = '';
        jQuery('#bt_bb_show_location-' + autocomplete_form_code).toggleClass('location_detected'); 
        jQuery('#bt_bb_show_location-' + autocomplete_form_code).removeClass('location_detecting');
        return false;
    }
    var lat = lat > 0 ? lat : document.getElementById('bt_bb_listing_field_my_lat-' + autocomplete_form_code).value;  
    var lng = lng > 0 ? lng : document.getElementById('bt_bb_listing_field_my_lng-' + autocomplete_form_code).value; 
    var input = lat.concat(',',lng);
        
    var latlngStr = input.split(',', 2);
    var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
    var geocoder = new google.maps.Geocoder;
    geocoder.geocode({'location': latlng}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) { 
            place = results[0];
                        
            var address  = '';
            if (place.formatted_address != null) {
                address = place.formatted_address;
            }

            var componentForm = {
                locality: 'long_name',
            };  

            var val = '';
            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (componentForm[addressType]) {
                    val = place.address_components[i][componentForm[addressType]];
                    break;
                }
            }
            if ( val != '' ){
                address = val;
            }

            document.getElementById('bt_bb_listing_field_location_autocomplete-' + autocomplete_form_code).value = address;
            jQuery('#bt_bb_show_location-' + autocomplete_form_code).toggleClass('location_detected'); 
            jQuery('#bt_bb_show_location-' + autocomplete_form_code).removeClass('location_detecting');
        } else {
          console.log('No results found');
        }
      } else {
        console.log('Geocoder failed due to: ' + status);
      }
    });
}


/* -- get default or  user location on loading shortcode */
function bt_sh_get_user_location(form_code) {
        autocomplete_form_code = form_code;   
	var options = {
	  enableHighAccuracy: true,
	  timeout: 100000,
	  maximumAge: 0
	};

	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(bt_sh_show_position, bt_sh_my_error, options);
	}else{
		bt_sh_show_position_default();
	}
}

function bt_sh_show_position(position) {  
        var my_lat          = document.getElementById('bt_bb_listing_field_my_lat-' + autocomplete_form_code);
        var my_lng          = document.getElementById('bt_bb_listing_field_my_lng-' + autocomplete_form_code); 
        var my_lat_default  = document.getElementById('bt_bb_listing_field_my_lat_default-' + autocomplete_form_code);
        var my_lng_default  = document.getElementById('bt_bb_listing_field_my_lng_default-' + autocomplete_form_code); 

        var crd = position.coords;
        var distance = bt_sh_calculate_distance( ajax_sh_object.ajax_sh_lat, ajax_sh_object.ajax_sh_lng, crd.latitude, crd.longitude, ajax_sh_object.ajax_sh_unit );
        if ( parseFloat(distance) > parseFloat(ajax_sh_object.ajax_radius) )
        {
                //customizer position
                my_lat.value = ajax_sh_object.ajax_sh_lat;
                my_lng.value = ajax_sh_object.ajax_sh_lng;
                my_lat_default.value = ajax_sh_object.ajax_sh_lat;
                my_lng_default.value = ajax_sh_object.ajax_sh_lng;
        }else{
                // user position
                my_lat.value = crd.latitude;
                my_lng.value = crd.longitude;
                my_lat_default.value = crd.latitude;
                my_lng_default.value = crd.longitude;
        }
}

/* get base location from customizer position  */
function bt_sh_show_position_default() {
        var my_lat	= document.getElementById('bt_bb_listing_field_my_lat-' + autocomplete_form_code);
        var my_lng	= document.getElementById('bt_bb_listing_field_my_lng-' + autocomplete_form_code); 

        my_lat.value = ajax_sh_object.ajax_sh_lat;
        my_lng.value = ajax_sh_object.ajax_sh_lng;
        console.log("Geolocation is not supported by your browser");
}

function bt_sh_calculate_distance(lat1, lon1, lat2, lon2, unit) {																	
	switch (unit)
	{
		case 'mi': R = 3959;break;
		case 'km': R = 6371;break;
		case 'nmi': R = 3959;break;
		default: R = 3959;break;
	}

	var dLat = bt_to_rad(lat2-lat1);
	var dLon = bt_to_rad(lon2-lon1);
	var lat1 = bt_to_rad(lat1);
	var lat2 = bt_to_rad(lat2);

	var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
			Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2); 
	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
	var d = R * c;
	return parseFloat(Math.round(d * 100) / 100).toFixed(2);
}

function bt_sh_to_rad(degrees){
	return degrees * Math.PI / 180;
}

function bt_sh_my_error(error) {
  console.warn('MY ERROR:' +  error.message);
  switch(error.code) {
        case error.PERMISSION_DENIED:
            console.log("Permission denied by user.");
            break;
        case error.POSITION_UNAVAILABLE:
            console.log("Location position unavailable.");
            break;
        case error.TIMEOUT:
            console.log("Request timeout.");
            break;
        case error.UNKNOWN_ERROR:
            console.log("Unknown error.");
            break;
   }
   jQuery('#bt_bb_show_location').removeClass('location_detecting');
}

