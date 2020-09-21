(function( $ ) {
    
    'use strict';
    
        var bt_bb_set_distance = function(  ) {
            $( '.bt_bb_masonry_post_grid_content .bt_bb_grid_item .bt_bb_grid_item_inner .bt_bb_listing_box' ).each(function() {
                var latitude    =  $( this ).data( 'latitude' );
                var longitude   =  $( this ).data( 'longitude' );
                var postid      =  $( this ).data( 'postid' );
                var unit        =  $( this ).data( 'unit' );
                
                if ( latitude != '' && longitude != '' && postid > 0) {
                    var distance_featured = bt_get_distance_featured(  latitude, longitude,postid, unit );  
                    $( '#bt_bb_listing_distance_' +  $( this ).data( 'postid' )).html();
                }                
            });
        }

	var bt_bb_load_images = function( root ) {
		root.each(function() {
			var page_bottom = $( window ).scrollTop() + $( window ).height();
			$( this ).find( '.bt_bb_grid_item' ).each(function() {
				var this_top = $( this ).offset().top;
				if ( this_top < page_bottom + $( window ).height() ) {
					var img_src = $( this ).data( 'src' );
					if ( img_src !== '' && $( this ).find( '.bt_bb_grid_item_post_thumbnail a' ).html() == '' ) {
						$( this ).find( '.bt_bb_grid_item_post_thumbnail a' ).html( '<img src="' + img_src + '">' );
					}
				}
			});
		});
	}
        
        var bt_bb_resize = function() {
                $( '.bt_bb_masonry_post_grid_content .bt_bb_grid_item .bt_bb_grid_item_inner' ).each( function() {
                        $( this ).css( 'height', '' );
                        var h = Math.ceil( $( this ).width() * $( this ).data( 'hw' ) );
                        $( this ).css( 'height', h );
                });
                $( '.bt_bb_masonry_post_grid_content .bt_bb_grid_item .bt_bb_grid_item_inner  .bt_bb_grid_item_inner_image' ).each( function() {                   
                        $( this ).css( 'height', '' );
                        var h = Math.ceil( $( this ).width() * $( this ).data( 'hw' ) );
                        $( this ).css( 'height', h );
                });
                $( '.bt_bb_masonry_post_grid_content' ).each( function() {                  
                        $( this ).width( 'initial' );
                        var child_left_margin = parseInt( $( this ).find( '.bt_bb_masonry_post_image_content' ).css( 'margin-left' ) );
                        var base_item_width =  ( $( this ).width() - child_left_margin ) / ( $( this ).data( 'columns' ) ) ;
                        if ( Math.ceil( base_item_width ) != base_item_width ) {
                                $( this ).width( $( this ).data( 'columns' ) * Math.ceil( base_item_width ) + child_left_margin );
                        } 				
                });

        }		
	
	var bt_bb_load_posts = function( root ) {
		root.addClass( 'bt_bb_grid_hide' );
		root.find( '.bt_bb_grid_item' ).remove();
		if ( root.hasClass( 'masonry' ) ) {
			root.masonry( 'destroy' );
		}
		
		root.parent().find( '.bt_bb_post_grid_loader' ).show();
		if ( root.data( 'post-type' ) == 'listing' && root.data( 'show-type' ) == 'bt_bb_featured_listing_grid' ) {
			var action = 'bt_bb_get_featured_listing';
			var data = {
					'action': action,
					'number': root.data( 'number-listing' ),
					'format': root.data( 'format-listing' ),
					'category': root.data( 'category-listing' ),
					'show': root.data( 'show-listing' ),
					'type': root.data( 'show-type' ),
					'region': root.data( 'region-listing' ),
					'listing': ajax_object.listing_slugs,
					'sorting': root.data( 'sorting-listing' ),
					'bt-nonce': root.data( 'bt-nonce' )
			};

			$.ajax({
					type: 'POST',
					url: ajax_object.ajax_url,
					data: data,
					async: true,
					success: function( response ) {
							root.append( response );
							bt_bb_resize( root );
							bt_bb_load_images( root );
							root.masonry({
									columnWidth: '.bt_bb_grid_sizer',
									itemSelector: '.bt_bb_grid_item',
									gutter: 0,
									percentPosition: true
							});
							root.parent().find( '.bt_bb_post_grid_loader' ).hide();
							root.removeClass( 'bt_bb_grid_hide' );
							$( '.bt_bb_grid_container' ).css( 'height', 'auto' );
							bt_bb_set_distance();
					},
					error: function( response ) {
							root.parent().find( '.bt_bb_post_grid_loader' ).hide();	
							root.removeClass( 'bt_bb_grid_hide' );			
					}
			});
		} 
	}

	$( document ).ready(function() {
		$( window ).on( 'scroll', function() {
			bt_bb_load_images( $( '.bt_bb_masonry_post_grid_content' ) );
		});

		$( '.bt_bb_masonry_post_grid_content' ).each(function() {
			var grid_content = $( this );
			bt_bb_load_posts( grid_content );
		});

		$( '.bt_bb_post_grid_filter_item' ).on( 'click', function() {
			var root = $( this ).closest( '.bt_bb_grid_container' );
			root.height( root.height() );
			$( this ).parent().find( '.bt_bb_post_grid_filter_item' ).removeClass( 'active' ); 
			$( this ).addClass( 'active' );			
			var grid_content = $( this ).closest( '.bt_bb_masonry_portfolio_tiles' ).find( '.bt_bb_masonry_post_grid_content' );
			grid_content.data( 'category-portfolio', $( this ).data( 'category-portfolio' ) );
			grid_content.data( 'format-portfolio', $( this ).data( 'format-portfolio' ) );
			grid_content.data( 'post-type', $( this ).data( 'post-type' ) );
			bt_bb_load_posts( grid_content );
		});
		
	});

	$( window ).load(function() {

		bt_bb_resize();
		
		$( '.bt_bb_masonry_post_image_content' ).masonry({
			columnWidth: '.bt_bb_grid_sizer',
			itemSelector: '.bt_bb_grid_item',
			gutter: 0,
			percentPosition: true
		});

		$( window ).on( 'resize', function() {
			bt_bb_resize();
		});
		
		setTimeout(function() {
			$( '.bt_bb_masonry_post_image_content' ).masonry( 'layout' );
		}, 10 );
		
	});

})( jQuery );

var ajax_lat	= ajax_object.ajax_lat;
var ajax_lng	= ajax_object.ajax_lng;
var ajax_unit	= ajax_object.ajax_unit;
var ajax_radius = ajax_object.ajax_radius;
var my_lat	= ajax_lat;
var my_lng	= ajax_lng;

bt_get_user_location();	

function bt_get_user_location() {
	var options = {
	  enableHighAccuracy: true,
	  timeout: 10000,
	  maximumAge: 0
	};
	
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(bt_show_position, bt_error, options);
	} else {
		bt_show_position_default();
	}

	
}

function bt_show_position(position) {
	var crd = position.coords;
	var distance = bt_calculate_distance( ajax_lat, ajax_lng, crd.latitude, crd.longitude, ajax_unit );
	if ( parseFloat(distance) > parseFloat(ajax_radius) ) {
		//customizer position
		my_lat = ajax_lat;
		my_lng = ajax_lng;
	} else {
		// user position
		my_lat = crd.latitude;
		my_lng = crd.longitude;
	}
}

function bt_show_position_default() {	
	my_lat = ajax_lat;
	my_lng = ajax_lng;

}

function bt_error(error) {
  console.warn('ERROR:' +  error.message);
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

function bt_get_distance_featured( latitudeTo, longitudeTo, listingID, unit) {	
        var distance = bt_calculate_distance( my_lat, my_lng, latitudeTo, longitudeTo, unit );
	if (!isNaN(distance)) {	 
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
	} else {
		jQuery('#bt_bb_listing_distance_' + listingID).hide();
	}
}

function bt_calculate_distance(lat1, lon1, lat2, lon2, unit) {
	switch (unit) {
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

