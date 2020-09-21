(function( $ ) {
     'use strict';
     
     var bt_bb_set_distance = function(  ) {
       $( '.bt_bb_listing_view_as_grid .bt_bb_listing_box' ).each(function() {
           var latitude    =  typeof  $( this ).data('latitude') !== 'undefined' ?  $( this ).data( 'latitude' ) : '';
           var longitude   =  typeof  $( this ).data('longitude') !== 'undefined' ?  $( this ).data( 'longitude' ) : '';
           var postid      =  typeof  $( this ).data('postid') !== 'undefined' ?  $( this ).data( 'postid' ) : 0;
           var unit        =  typeof  $( this ).data('unit') !== 'undefined' ?  $( this ).data( 'unit' ) : '';
           if ( latitude != '' && longitude != '' && postid > 0 && unit != '') {
               bt_get_distance(  latitude, longitude,postid, $( this ).data('unit'));  
           }                
       });
   }
	
    function bt_get_nearby_locations_results() {
            var data= {
                    'action':	ajax_object.ajax_action,
                    'listing_id':	ajax_object.listing_id,
                    'lat':		ajax_object.ajax_lat,
                    'lng':		ajax_object.ajax_lng,
                    'categories':	ajax_object.categories,
            };
            $.ajax({
                    type: 'POST',
                    url: ajax_object.ajax_url,
                    data: data,
                    async: true,
                    success: function( response ) {
                            if ( response)
                            {
                                    $( '#nearby_locations_container' ).html( response );						
                                    $( '#nearby_locations_container' ).css( 'height', 'auto' );
                                    bt_single_load_images();
                                    bt_bb_set_distance();
                            }
                    },
                    error: function( xhr, status, error ) {
                            console.log('error: ' +  status + ' ' + error);
                    }
            });
    }

    $( document ).ready(function() {

            bt_single_load_images();
            
            $( window ).load(function() {
                bt_single_load_images();
             });
        
            $( window ).on( 'scroll', function() {
               bt_single_load_images();
            });    

            // Show more reviews button in listing single comments
            $( 'body' ).on( 'click', '#listing_single_comment_show_more_reviews', function(e) {
                    $( 'body' ).addClass( 'btCommentsExpanded' );
                    return false;
            });

            // single
            $(".comment-read-further span").on( 'click', function() {    
                    $(this).parent().parent().find('.comment-content').toggleClass('on');
                    var textVariable = $(this).data("text-on");          
                    $(this).parent().toggleClass('on');
                    $(this).data('text-on', $(this).text()).text(textVariable);
            });

            $(".bt_bb_listing_marker_meta_show_working_hours").on( 'click', function() { 
                    $(this).toggleClass('on');
                    $('.bt_bb_listing_marker_meta_working_hours dl').toggleClass('on');
            });

           

            $('.bt_bb_link_resurva').magnificPopup({
                      type: 'inline',
                      closeOnContentClick: true
            });

            bt_get_user_location();
            
            $(document).ajaxStop(function () {
                bt_single_load_images();
           });
    });

    bt_get_nearby_locations_results();
    
	
})( jQuery );	


var ajax_lat	= ajax_object.ajax_lat;
var ajax_lng	= ajax_object.ajax_lng;
var ajax_unit	= ajax_object.ajax_unit;
var my_lat	= ajax_lat;
var my_lng	= ajax_lng;

function bt_get_user_location() {
	var options = {
	  enableHighAccuracy: true,
	  timeout: 5000,
	  maximumAge: 0
	};
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(bt_show_position, bt_error, options);
	}
}

function bt_show_position(position) {
	var crd = position.coords;
        var distance = bt_calculate_distance( ajax_lat, ajax_lng, crd.latitude, crd.longitude, ajax_unit );
       
	if ( parseFloat(distance))
	{
		//customizer position
		my_lat = ajax_lat;
		my_lng = ajax_lng;
	}else{
		// user position
		my_lat = crd.latitude;
		my_lng = crd.longitude;
	}
}

function bt_error(error) {
 
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
}



function bt_calculate_distance(lat1, lon1, lat2, lon2, unit) {
	switch (unit)
	{
            case 'mi': R = 3959;break;
            case 'km': R = 6371;break;
            case 'nmi': R = 3959;break;
            case 'm': R = 6371000;break;
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
	var fixed = unit == 'm' ? 0 : 2;
	return parseFloat(Math.round(d * 100) / 100).toFixed(fixed);
}

function bt_to_rad(degrees){
	return degrees * Math.PI / 180;
}

function bt_get_distance( latitudeTo, longitudeTo, listingID, unit) {
        var distance = bt_calculate_distance( my_lat, my_lng, latitudeTo, longitudeTo, unit );
	if (!isNaN(distance) )
	{	
                if ( unit == 'km' && distance < 1 ) {
                    distance = distance * 1000;
                    unit = 'm';
                }
		var unit_display = '';
		switch(unit) {
			case 'm':
				unit_display = ajax_object.ajax_label_m;
				break;
			case 'km':
				unit_display = ajax_object.ajax_label_km;
				break;
			case 'mi':
				unit_display = ajax_object.ajax_label_mi;
				break;
			default:
				unit_display = ajax_object.ajax_label_mi;
		}
		jQuery('#bt_bb_listing_distance_' + listingID).text(distance + ' ' + unit_display + '.');
	}else{
		jQuery('#bt_bb_listing_distance_' + listingID).hide();
	}
	
}

function bt_single_load_images() {
    
    jQuery( 'img[data-loaded="0"]' ).each(function() {
        var $image = jQuery( this );
        if ( inViewportSingle( $image ) ) {
            var img_src     = $image.data( 'src' );
            var img_loaded  = $image.data( 'loaded' );
            
            var downloadingImage = new Image();
            downloadingImage.onload = function () {
                    $image.attr('src',img_src);
                    $image.data('loaded','1');
                    $image.addClass( 'bt_src_loaded' );      
            };
            downloadingImage.src = $image.data( 'src' );
            $image.addClass( 'bt_src_loading' );
        }
    });
}

function inViewportSingle(ele) {
    var lBound = jQuery(window).scrollTop(),
        uBound = lBound + jQuery(window).height(),
        top = ele.offset().top,
        bottom = top + ele.outerHeight(true);

    return (top > lBound && top < uBound)
        || (bottom > lBound && bottom < uBound)
        || (lBound >= top && lBound <= bottom)
        || (uBound >= top && uBound <= bottom);
}

