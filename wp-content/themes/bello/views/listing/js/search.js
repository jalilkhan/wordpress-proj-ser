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
   function bt_get_listing_results() {
    
	var root = $( '.bt_bb_listing_view_as_grid' );
	var button = $('.bt_bb_loadmore');
        
        root.addClass( 'bt_bb_grid_hide' );
        root.find( '.bt_bb_listing_box' ).remove();
        root.parent().find( '.bt_bb_post_grid_loader' ).show();
        
        root.data('offset', 1);         
        if (ajax_object.ajax_pagination == 'loadmore'){
            if (root.data('maxpage') > 1) {
                button.show();
            }
        }
        
        $('.bt_bb_listing_options_results').html('');   
        
       
	var args = Array.prototype.slice.call(arguments, 0);
        
        if ( args[0] ) {
            /* get data from search form */
            var listing_category   = args[0]["listing_category"] && args[0]["listing_category"] != 'all' ? args[0]["listing_category"] : '';
            if ( listing_category == 'all' ){
                bt_reset_listing_additional_filter(listing_slug);
            }
       
            var orderby            = args[0]["orderby"] ? args[0]["orderby"] : '';
            
           
            var listing_region     = args[0]["listing_region"] && args[0]["listing_region"] != 'all' ? args[0]["listing_region"] : '';
            var listing_tag        = args[0]["listing_tag"] ? args[0]["listing_tag"] : '';
            var search_term        = args[0]["search_term"] ? args[0]["search_term"] : '';        
            var form_data          = args[0]["form_data"] > 0 ? args[0]["form_data"] : 0;
            
            var location_detected = $('#bt_bb_show_location').hasClass('location_detected') ? 1 : 0;  
            if ( location_detected ){
                $('#bt_bb_show_location').addClass('location_detected')
            }
            
            var data_form = null;
            if ( form_data == 1  )
            {
                data_form = $('#listing_search_form').serializeArray();

                var c = $( '.bt_bb_listing_search_element' ); 
                c.find( '.btQuoteSwitch' ).each(function() {
                        var vrednost = 0;
                        if ( $( this ).hasClass( 'on' ) ) {
                             vrednost = $( this ).data( 'on' ) ;
                        } else {
                             vrednost = $( this ).data( 'off' );
                        } 
                        if ($(this).attr('name') && vrednost > 0){
                            data_form.push({name: $(this).attr('name'), value: vrednost});
                        }            
                });
            }  
            /* /get data from search form */
            
           
            /* COUNT listings results and set loadmore button (optional) */
            var data= {
                    'action':		ajax_object.ajax_action_count,
                    'listing_view':	ajax_object.ajax_listing_view,
                    'orderby':		orderby,
                    'listing_category': listing_category,
                    'listing_region':   listing_region,
                    'listing_tag':	listing_tag,
                    'search_term':	search_term,
                    data_form
            };
            $.ajax({
                    type: 'POST',
                    url: ajax_object.ajax_url,
                    data: data,
                    async: true,
                    success: function( response ) {						
                        if ( response)
                        {                                    
                            $('.bt_bb_listing_options_results').html( ajax_object.ajax_label_found + ' ' + response + ' ' + ajax_object.ajax_label_results);
                            
                            var maxpage = parseInt( response === undefined ? 0 : response ) / parseInt( root.data( 'number' ) );
                            root.data('maxpage', Math.ceil(maxpage));

                            if (ajax_object.ajax_pagination == 'loadmore'){
                                if (root.data('maxpage') > 1) {
                                    button.show();// if more than 1 page, show the button
                                }
                                if (root.data('offset') >= root.data('maxpage') ) {
                                    button.hide(); // if last page, hide the button
                                }
                            }
                        }
                    },
                    error: function( xhr, status, error ) {
                            console.log('error: ' +  status + ' ' + error);
                    }
            });
            /* /COUNT listings results */
                 
            /* GET listings results */
            var data= {
                    'action':		ajax_object.ajax_action,
                    'listing_view':	ajax_object.ajax_listing_view,
                    'orderby':		orderby,
                    'listing_category': listing_category,
                    'listing_region':   listing_region,
                    'listing_tag':	listing_tag,
                    'search_term':	search_term,
                    data_form
            };
            $.ajax({
                    type: 'POST',
                    url: ajax_object.ajax_url,
                    data: data,
                    async: true,
                    success: function( response ) {						
                        if ( response)
                        {
                            $('#bt_bb_listing_view_container').html( response );
                            $( '.bt_bb_listing_view_container' ).css( 'height', 'auto' );                            
                            
                            /*if ($('#bt_bb_listing_field_distance').val() == 0 ){
                                $('#bt_bb_listing_field_location_autocomplete').val('');
                            }*/

                            root.parent().find( '.bt_bb_post_grid_loader' ).hide();
                            root.removeClass( 'bt_bb_grid_hide' ); 
                            bt_bb_set_distance();
                        }
                    },
                    error: function( xhr, status, error ) {
                            console.log('error: ' +  status + ' ' + error);
                    }
            });
            
            /* /GET listings results */

            /* listings results FOR MAP , IF MAP CONATINER EXIST */
           
            if ( $('#bt_bb_listing_search_map_container').length > 0 ){
                var data_map= {
                        'action':           ajax_object.ajax_action_map,
                        'listing_view':     ajax_object.ajax_listing_view,
                        'orderby':          orderby,
                        'listing_category': listing_category,
                        'listing_region':   listing_region,
                        'listing_tag':      listing_tag,
                        'search_term':      search_term,
                        data_form
                };
                $.ajax({
                        type: 'POST',
                        url: ajax_object.ajax_url,
                        data: data_map,
                        async: true,
                        success: function( response ) {
                                $('#bt_bb_listing_search_map_container').html( response );
                                $( '.bt_bb_listing_view_container' ).css( 'height', 'auto' );
                                //if (ajax_object.ajax_listing_search_map_type == 'google'){
                                    bt_bb_listing_gmap_init(); 
                                //}
                        },error: function( xhr, status, error ) {
                                console.log('error: ' +  status + ' ' + error);
                        }
                });
            }
            /* /listings results FOR MAP, IF MAP CONATINER EXIST */
           
            /* change url if it is ajax loading*/
            if ( ajax_object.listing_search_type == 'ajax' ) {
                var currentURL = window.location.href.split('?')[0];
                var queryURL = "?";
                data_form.forEach(function(entry) {
                     if ( entry['value'] ){
                         queryURL += "&" + entry['name'] + "=" + entry['value'];
                     }
                });
                var url = currentURL + queryURL;
                history.pushState(undefined, '', url);
            }
            
        }
}

function bt_get_listing_results_loadmore() {
	var root    = $( '.bt_bb_listing_view_as_grid' );
	var button  = $('.bt_bb_loadmore');
                
        //  current page
        var offset = root.data('offset');
        root.data('offset', offset + 1);

        var args = Array.prototype.slice.call(arguments, 0); 
        if (args[0] )
        {
                /* get data from search form */
                var orderby            = args[0]["orderby"] ? args[0]["orderby"] : '';
                var listing_category   = args[0]["listing_category"] && args[0]["listing_category"] != 'all' ? args[0]["listing_category"] : '';
                var listing_region     = args[0]["listing_region"] && args[0]["listing_region"] != 'all' ? args[0]["listing_region"] : '';
                var listing_tag        = args[0]["listing_tag"] ? args[0]["listing_tag"] : '';
                var search_term        = args[0]["search_term"] ? args[0]["search_term"] : '';        
                var form_data          = args[0]["form_data"] > 0 ? args[0]["form_data"] : 0;
                var offset             = root.data('offset') ? root.data('offset') : '1';

                var data_form = null;
                if ( form_data == 1  )
                {
                    data_form = $('#listing_search_form').serializeArray();

                    var c = $( '.bt_bb_listing_search_element' ); 
                    c.find( '.btQuoteSwitch' ).each(function() {
                            var vrednost = 0;
                            if ( $( this ).hasClass( 'on' ) ) {
                                 vrednost = $( this ).data( 'on' ) ;
                            } else {
                                 vrednost = $( this ).data( 'off' );
                            } 
                            if ($(this).attr('name') && vrednost > 0){
                                data_form.push({name: $(this).attr('name'), value: vrednost});
                            }            
                    });
                }          
                /* /get data from search form */

                /* listings results */                       
                var data= {
                        'action':           ajax_object.ajax_action,
                        'listing_view':     ajax_object.ajax_listing_view,
                        'orderby':          orderby,
                        'listing_category': listing_category,
                        'listing_region':   listing_region,
                        'listing_tag':      listing_tag,
                        'search_term':      search_term,
                        'page':             offset,
                        'paged':            offset,
                        'offset':           offset,
                        data_form
                };
					
                $.ajax({
                    type: 'POST',
                    url: ajax_object.ajax_url,
                    data: data,
                    async: true,
                    beforeSend : function ( xhr ) {
                            button.html(ajax_object.ajax_label_loading_listings)
                    },
                    success: function( response ) {						
                        if ( response)
                        {
                           
                            $( '#bt_bb_listing_view_container' ).append( response );
                            
                            $( '.bt_bb_listing_view_container' ).css( 'height', 'auto' );

                            button.text( ajax_object.ajax_label_load_more_listings );  

                            if (root.data('offset') >= root.data('maxpage') ) {
                                button.hide(); // if last page, remove the button
                            }
                        } else {
                            button.hide(); 
                        }
                    },error: function( xhr, status, error ) {
                            console.log('error: ' +  status + ' ' + error);
                    }
                });                       
                /* /listings results */

                /* listings results FOR map */                        
                if( $('#bt_bb_listing_search_map_container').length>0 ){
                    var data_map= {
                            'action':           ajax_object.ajax_action_map,
                            'listing_view':     ajax_object.ajax_listing_view,
                            'orderby':          orderby,
                            'listing_category': listing_category,
                            'listing_region':   listing_region,
                            'listing_tag':      listing_tag,
                            'search_term':      search_term,
                            'page':             offset,
                            'paged':            offset,
                            'offset':           offset,
                            data_form
                        };

                    $.ajax({
                            type: 'POST',
                            url: ajax_object.ajax_url,
                            data: data_map,
                            async: true,
                            success: function( response ) {                                            
                                $('#bt_bb_listing_search_map_container').html( response );
                                $( '.bt_bb_listing_view_container' ).css( 'height', 'auto' );
                                //if (ajax_object.ajax_listing_search_map_type == 'google'){
                                    bt_bb_listing_gmap_init(); 
                                //}                                           
                            },error: function( xhr, status, error ) {
                                    console.log('error: ' +  status + ' ' + error);
                            }
                    });
                 }                       
            /* /listings results FOR MAP */            
    }
}

function bt_get_listing_results_pagelink() {
	var root = $( '.bt_bb_listing_view_as_grid' );
        root.parent().find( '.bt_bb_post_grid_loader' ).show();
        
        $('#bt_bb_listing_view_container').html( '' ); 
        $('html, body').animate({ scrollTop: 100 }, 'fast');
        
        /* get data from form */
        var data_form = $('#listing_search_form').serializeArray();
        var c = $( '.bt_bb_listing_search_element' ); 
        c.find( '.btQuoteSwitch' ).each(function() {
                var vrednost = 0;
                if ( $( this ).hasClass( 'on' ) ) {
                                vrednost = $( this ).data( 'on' ) ;
                } else {
                                vrednost = $( this ).data( 'off' );
                } 
                if ($(this).attr('name') && vrednost > 0){
                        data_form.push({name: $(this).attr('name'), value: vrednost});
                }            
        });
        /* /get data from form */
	
        var args = Array.prototype.slice.call(arguments, 0);
        if (args[0] )
        {
                /* listings results */
                var data= {
                        data_form,
                        'action':		ajax_object.ajax_action,
                        'listing_view':		ajax_object.ajax_listing_view,
                        'orderby':		args[0]["orderby"] ? args[0]["orderby"] : '',
                        'listing_category':     args[0]["listing_category"] && args[0]["listing_category"] != 'all' ? args[0]["listing_category"] : '',
                        'listing_region':       args[0]["listing_region"] && args[0]["listing_region"] != 'all' ? args[0]["listing_region"] : '',
                        'listing_tag':		args[0]["listing_tag"] ? args[0]["listing_tag"] : '',
                        'search_term':		args[0]["search_term"] ? args[0]["search_term"] : '',
                        'page':			args[0]["page"] ? args[0]["page"] : '1',
                        'paged':		args[0]["paged"] ? args[0]["paged"] : '1',
                };

                $.ajax({
                        type: 'POST',
                        url: ajax_object.ajax_url,
                        data: data,
                        async: true,
                        success: function( response ) {						
                                if ( response)
                                {
                                    $('#bt_bb_listing_view_container').html( response );                                    
                                    $( '.bt_bb_listing_view_container' ).css( 'height', 'auto' );

                                root.parent().find( '.bt_bb_post_grid_loader' ).hide();
                                }
                        },error: function( xhr, status, error ) {
                                console.log('error: ' +  status + ' ' + error);
                        }
                });
                /* /listings results */

                /* listings map results */
                if( $('#bt_bb_listing_search_map_container').length ){
                        var data_map= {
                                data_form,
                                'action':		ajax_object.ajax_action_map,
                                'listing_view':		ajax_object.ajax_listing_view,
                                'orderby':		args[0]["orderby"] ? args[0]["orderby"] : '',
                                'listing_category':     args[0]["listing_category"] && args[0]["listing_category"] != 'all' ? args[0]["listing_category"] : '',
                                'listing_region':       args[0]["listing_region"] && args[0]["listing_region"] != 'all' ? args[0]["listing_region"] : '',
                                'listing_tag':		args[0]["listing_tag"] ? args[0]["listing_tag"] : '',
                                'search_term':		args[0]["search_term"] ? args[0]["search_term"] : '',
                                'page':			args[0]["page"] ? args[0]["page"] : '2',
                                'paged':		args[0]["paged"] ? args[0]["paged"] : '1',
                        };

                        $.ajax({
                                type: 'POST',
                                url: ajax_object.ajax_url,
                                data: data_map,
                                async: true,
                                success: function( response ) {	
                                    if ( response)
                                    {
                                        $('#bt_bb_listing_search_map_container').html( response );
                                         bt_bb_listing_gmap_init();                                        
                                    }
                                },error: function( xhr, status, error ) {
                                        console.log('error: ' +  status + ' ' + error);
                                }
                        });
                }
                /* listings map results */
	}
}

function bt_reset_listing_additional_filter(listing_slug) {
     $('input:checkbox').prop('checked', false);
     $('#bt_bb_listing_options_additional_filters span').hide();
}

function bt_get_listing_additional_filter(listing_slug) {        
        bt_reset_listing_additional_filter(listing_slug);
        var root = $( '#bt_bb_listing_options_additional_filters_view_container' );
   
        root.hide();
        
        root.parent().find( '.bt_bb_additional_filter_loader' ).show();
        var data_search = {
                'action':		'bt_get_listing_search_action',
                'listing_slug':		listing_slug,
                'orderby':      	$('#bt_bb_listing_field_sort').val(),
                'listing_category':	$('#bt_bb_listing_field_category').find(":selected").val(),
                'listing_region':       $('#bt_bb_listing_field_region').find(":selected").val(),
                'listing_tag':		ajax_object.ajax_listing_tag,
                'search_term':		$('#bt_bb_listing_field_keyword').val(),
                'listing_gets':		ajax_object.ajax_listing_gets
        }        
        $.ajax({
                type: 'POST',
                url: ajax_object.ajax_url,
                data: data_search,
                async: true,
                success: function( response ) {
                        root.parent().find( '.bt_bb_additional_filter_loader' ).hide();
                        jQuery('#bt_bb_listing_options_search_view_container').html( response );
                },error: function( xhr, status, error ) {
                        console.log('error: ' +  status + ' ' + error);
                }
        });
       
        
        var data_additional_filters = {
		'action':		'bt_get_listing_additional_filter_action',
		'listing_slug':		listing_slug,
		'listing_gets':		ajax_object.ajax_listing_gets
	}
	$.ajax({
		type: 'POST',
		url: ajax_object.ajax_url,
		data: data_additional_filters,
		async: true,
		success: function( response ) {	
                        if ( response ) {
                            root.show();
                            jQuery('#bt_bb_listing_options_additional_filters_view_container').html( response );
                            $('.bt_bb_listing_options_additional_filters span').show();
                        }
		},error: function( xhr, status, error ) {
			console.log('error: ' +  status + ' ' + error);
		}
	});
				
}


$( document ).ready(function() {
        $(document).keyup(function(e) {
           
             if (e.keyCode == 13) { 
                    if ( ajax_object.listing_search_type == 'ajax' )
                    {	
                           // region from select list or hidden value (if autocomplete)   
                           var listing_region = $('#bt_bb_listing_field_region').find(":selected").val() != '' && $('#bt_bb_listing_field_region').find(":selected").val() != undefined ? 
                                $('#bt_bb_listing_field_region').find(":selected").val() : $('#bt_bb_listing_field_region').val();
                            bt_get_listing_results( { 
                                            orderby:            $('#bt_bb_listing_field_sort').val(),
                                            listing_category:   $('#bt_bb_listing_field_category').find(":selected").val(),
                                            listing_region:     listing_region,
                                            listing_tag:        ajax_object.ajax_listing_tag,
                                            search_term:        $('#bt_bb_listing_field_keyword').val(),
                                            page:		2,
                                            paged:              1,
                                            form_data:          1
                                    } 
                            );
                    }else{  
                            $('#listing_search_form').submit();
                            return false;
                    }
            }
        });
        
        /* search listings by open now switch */
        $(document).on('click', '#bt_bb_listing_field_now_open', function() {   
                if ( ajax_object.listing_search_type == 'ajax' ){
                        // region from select list or hidden value (if autocomplete)   
                    var listing_region = $('#bt_bb_listing_field_region').find(":selected").val() != '' && $('#bt_bb_listing_field_region').find(":selected").val() != undefined ? 
                    $('#bt_bb_listing_field_region').find(":selected").val() : $('#bt_bb_listing_field_region').val();
					bt_get_listing_results( { 
							orderby:		$('#bt_bb_listing_field_sort').val(),
							listing_category:	$('#bt_bb_listing_field_category').find(":selected").val(),
							listing_region:         listing_region,
							listing_tag:		ajax_object.ajax_listing_tag,
							search_term:		$('#bt_bb_listing_field_keyword').val(),
												page:                   2,
												paged:                  1,
							form_data:		1
						} 
					);
				}
        });
	
	/* search listings on form submit button */
        $("#bt_bb_link_search_submit").on( 'click', function() {
                
		if ( ajax_object.listing_search_type == 'ajax' )
		{
                     // region from select list or hidden value (if autocomplete)   
                        var listing_region = $('#bt_bb_listing_field_region').find(":selected").val() != '' && $('#bt_bb_listing_field_region').find(":selected").val() != undefined ? 
                                $('#bt_bb_listing_field_region').find(":selected").val() : $('#bt_bb_listing_field_region').val();
                                
			bt_get_listing_results( { 
					orderby:            $('#bt_bb_listing_field_sort').val(),
					listing_category:   $('#bt_bb_listing_field_category').find(":selected").val(),
					listing_region:     listing_region,
					listing_tag:        ajax_object.ajax_listing_tag,
					search_term:        $('#bt_bb_listing_field_keyword').val(),
                                        page:		    2,
                                        paged:              1,
					form_data:          1
				} 
			);
		}else{
                        $('#listing_search_form').submit();
                        return false;
			
		}
	});

	/* search listings by order */
	$('#bt_bb_listing_field_sort').fancySelect().on('change.fs', function () {
           
                // region from select list or hidden value (if autocomplete)   
                var listing_region = $('#bt_bb_listing_field_region').find(":selected").val() != '' && $('#bt_bb_listing_field_region').find(":selected").val() != undefined ? 
                                $('#bt_bb_listing_field_region').find(":selected").val() : $('#bt_bb_listing_field_region').val();
                                
		bt_get_listing_results( { 
				orderby:	    $( this ).val(),
				listing_category:   $('#bt_bb_listing_field_category').find(":selected").val(),
                                listing_region:     listing_region,
				listing_tag:        ajax_object.ajax_listing_tag,
				search_term:        $('#bt_bb_listing_field_keyword').val(),
                                page:		    2,
                                paged:              1,
				form_data:	    1
			} 
		);
	});
	
	/* search listings by region */
	$('#bt_bb_listing_field_region').fancySelect().on('change.fs', function () {
           
		if ( ajax_object.listing_search_type == 'ajax' ){                     
                                
			bt_get_listing_results( { 
					orderby:		$('#bt_bb_listing_field_sort').val(),
					listing_category:	$('#bt_bb_listing_field_category').find(":selected").val(),
					listing_region:         $( this ).val(),
					listing_tag:		ajax_object.ajax_listing_tag,
					search_term:		$('#bt_bb_listing_field_keyword').val(),
                                        page:                   2,
                                        paged:                  1,
					form_data:		1
				} 
			);
		}
                $('#bt_bb_listing_field_distance_label').html("Distance from " + $( this ).find("option[value='" + $( this ).val() + "']").text());
	});

	/* search listing by category : filters and listings */
	$('#bt_bb_listing_field_category').fancySelect().on('change.fs', function () {  
                bt_reset_listing_additional_filter( $( this ).val() );
                bt_get_listing_additional_filter( $( this ).val() );
                if ( ajax_object.listing_search_type == 'ajax' ){
                        var listing_category = $( this ).val();
                        if ( listing_category == 'all'){
                            listing_category = '';                            
                        }
                        
                        // region from select list or hidden value (if autocomplete)   
                        var listing_region = $('#bt_bb_listing_field_region').find(":selected").val() != '' && $('#bt_bb_listing_field_region').find(":selected").val() != undefined ? 
                                $('#bt_bb_listing_field_region').find(":selected").val() : $('#bt_bb_listing_field_region').val();
                
                        
                        bt_get_listing_results( {
                                        orderby:            $('#bt_bb_listing_field_sort').val(),
                                        listing_category:   listing_category,
                                        listing_region:     listing_region,
                                        listing_tag:        ajax_object.ajax_listing_tag,
                                        search_term:        $('#bt_bb_listing_field_keyword').val(),
                                        page:               2,
                                        paged:              1,
                                        form_data:          1
                                } 
                        );
                }
           

	});

	$(document).on('click', '.bt_bb_loadmore', function(event) {
                event.preventDefault();
                // region from select list or hidden value (if autocomplete)   
                var listing_region = $('#bt_bb_listing_field_region').find(":selected").val() != '' && $('#bt_bb_listing_field_region').find(":selected").val() != undefined ? 
                                $('#bt_bb_listing_field_region').find(":selected").val() : $('#bt_bb_listing_field_region').val();
                                
		bt_get_listing_results_loadmore( { 
				orderby:	    $('#bt_bb_listing_field_sort').val(),
				listing_category:   $('#bt_bb_listing_field_category').find(":selected").val(),
                                listing_region:     listing_region,
				listing_tag:	    ajax_object.ajax_listing_tag,
				search_term:	    $('#bt_bb_listing_field_keyword').val(),
				page:		    2,
                                paged:              1,
				form_data:	    1
			} 
		);
               
	});
        
        $(document).on('click', '.page-numbers', function(event) {
		event.preventDefault();
                // region from select list or hidden value (if autocomplete)   
                var listing_region = $('#bt_bb_listing_field_region').find(":selected").val() != '' && $('#bt_bb_listing_field_region').find(":selected").val() != undefined ? 
                                $('#bt_bb_listing_field_region').find(":selected").val() : $('#bt_bb_listing_field_region').val();
                                
                bt_get_listing_results_pagelink( { 
				orderby:            $('#bt_bb_listing_field_sort').val(),
				listing_category:   $('#bt_bb_listing_field_category').find(":selected").val(),
                                listing_region:     listing_region,
				listing_tag:        ajax_object.ajax_listing_tag,
				search_term:        $('#bt_bb_listing_field_keyword').val(),
				page:               $(this).text(),
				paged:              $(this).text(),
				form_data:          1
			}
		);
	});
	
	$(document).on('click', '#bt_bb_listing_options_view_on_map', function(event) {	
                event.preventDefault();
                $('#listing_list_view').val('standard');
                $('#listing_search_form').submit();
                return false;
	});
        
        $(document).on('click', '#bt_bb_show_location', function(event) {	
                event.preventDefault();
                var user_position = $(this).hasClass('location_detected') ? 0 : 1;
                bt_get_my_position(user_position); 
	});

	bt_get_listing_additional_filter( $('#bt_bb_listing_field_category').find(":selected").val() );

	function bt_display_page_result(){
		if ( ajax_object.paged > 0 )
		{
			var paged = ajax_object.paged;
                        
                        // region from select list or hidden value (if autocomplete)   
                        var listing_region = $('#bt_bb_listing_field_region').find(":selected").val() != '' && $('#bt_bb_listing_field_region').find(":selected").val() != undefined ? 
                                $('#bt_bb_listing_field_region').find(":selected").val() : $('#bt_bb_listing_field_region').val();
                                
			bt_get_listing_results_pagelink( { 
					orderby:            $('#bt_bb_listing_field_sort').val(),
					listing_category:   $('#bt_bb_listing_field_category').find(":selected").val(),
					listing_region:     listing_region,
					listing_tag:        ajax_object.ajax_listing_tag,
					search_term:        $('#bt_bb_listing_field_keyword').val(),
					page:               ajax_object.paged,
					paged:              ajax_object.paged,
					form_data:          1
				}
			);
		}
	}

	if ( ajax_object.listing_search_type != 'ajax' ){
		bt_get_listing_additional_filter( $('#bt_bb_listing_field_category').find(":selected").val() );
	}

	$(document).on('input change', '#bt_bb_listing_field_distance', function() {
		$('#bt_bb_listing_field_distance_value').val( $(this).val() );
	});

	$(document).on('input change', '#bt_bb_listing_field_distance_value', function() {
		$('#bt_bb_listing_field_distance').val( $(this).val() );
	});
        
        $(".bt_bb_listing_options_additional_filters span").on( 'click', function() {    
                $(this).parent().toggleClass('on');
                $('.bt_bb_listing_options_additional_filters_view').toggleClass('on');
        });
        
        $( window ).load(function() {
            bt_load_images();
            bt_get_user_location();
            
            setTimeout(function(){
               bt_bb_set_distance();
            }, 1000);
            
            $( window ).on( 'scroll', function() {
               bt_load_images();
               bt_bb_set_distance();
            });  
        });
        
        $(".bt_bb_listing_search_inner").on( 'scroll', function() {
            bt_load_images();
            bt_bb_set_distance();
        }); 
                
        $(document).ajaxStart(function () {
            $('#bt_listing_loading').show();
        });
        
        $(document).ajaxStop(function () {
             bt_load_images();
             $('#bt_listing_loading').hide();
             bt_bb_set_distance();
             
        });
        

    });

})( jQuery );


var ajax_lat            = ajax_object.ajax_lat;
var ajax_lng            = ajax_object.ajax_lng;
var ajax_unit           = ajax_object.ajax_unit;
var ajax_radius         = ajax_object.ajax_radius;
var my_lat              = document.getElementById('bt_bb_listing_field_my_lat');
var my_lng              = document.getElementById('bt_bb_listing_field_my_lng');
var my_lat_default	= document.getElementById('bt_bb_listing_field_my_lat_default');
var my_lng_default	= document.getElementById('bt_bb_listing_field_my_lng_default');

var search_autocomplete_location;

function bt_bb_search_autocomplete_change_location() {
	var search_input_location = /** @type {HTMLInputElement} */(document.getElementById('bt_bb_listing_field_location_autocomplete'));        
	if ( search_input_location )
	{
            search_autocomplete_location = new google.maps.places.Autocomplete(search_input_location);
            if ( search_autocomplete_location )
            {
                search_autocomplete_location.addListener('place_changed', bt_bb_search_autocomplete_fill_map_my_location);
            }else{
                console.log("error: search_autocomplete_location not defined");
                return;
            }
            
            // need to stop prop of the touchend event
            if (navigator.userAgent.match(/(iPad|iPhone|iPod)/g)) {
                setTimeout(function() {
                    var container = document.getElementsByClassName('pac-container')[0];
                    container.addEventListener('touchend', function(e) {
                        e.stopImmediatePropagation();
                        setTimeout(function(){document.activeElement.blur();},300);
                    });
                }, 1000);
            }
	}
}

function bt_bb_search_autocomplete_fill_map_my_location() {       
        if ( search_autocomplete_location )
        {
            var place = search_autocomplete_location.getPlace(); 

            if ( typeof place === 'undefined' ){
                console.log("error: Autocomplete's not returned place");
                return;
            }
            if (!place.geometry) {
                console.log("error: Autocomplete's returned place contains no geometry");
                return;
            }        

            if (place.geometry.location) {
                var my_lat = document.getElementById('bt_bb_listing_field_my_lat');
                var my_lng = document.getElementById('bt_bb_listing_field_my_lng');

                my_lat.value = place.geometry['location'].lat();
                my_lng.value = place.geometry['location'].lng();
                
            }
        }
        return;
}

if ( typeof google !== 'undefined' ){
    google.maps.event.addDomListener(window, 'load', bt_bb_search_autocomplete_change_location);
}

//Value Retrieval Function
var bt_get_value = function (values, valueName) {
    return values[valueName];
};

function bt_load_images() {
    jQuery( 'img[data-loaded="0"]' ).each(function() {
        var $image = jQuery( this );
        if ( inViewport( $image ) ) {
            var img_src     = $image.data( 'src' );
            var img_loaded  = $image.data( 'loaded' );
            var img_srcset  = $image.data( 'srcset' );            
            
            var downloadingImage = new Image();
            downloadingImage.onload = function () {
                    $image.attr('src',img_src);
                    $image.data('loaded','1');
                    if ( typeof img_srcset !== "undefined" ){
                       $image.attr('srcset',img_src);
                    }
                    $image.addClass( 'bt_src_loaded' );      
            };
            downloadingImage.src = $image.data( 'src' );
            
            $image.addClass( 'bt_src_loading' );
        }
    });
}

function inViewport(ele) {
    var lBound = jQuery(window).scrollTop(),
        uBound = lBound + jQuery(window).height(),
        top = ele.offset().top,
        bottom = top + ele.outerHeight(true);

    return (top > lBound && top < uBound)
        || (bottom > lBound && bottom < uBound)
        || (lBound >= top && lBound <= bottom)
        || (uBound >= top && uBound <= bottom);
}

function bt_bb_geocode_latlng(lat, lng, reset) {
    if ( reset == 1 ){
        document.getElementById('bt_bb_listing_field_my_lat').value = document.getElementById('bt_bb_listing_field_my_lat_default').value;
        document.getElementById('bt_bb_listing_field_my_lng').value = document.getElementById('bt_bb_listing_field_my_lng_default').value;
        
        document.getElementById('bt_bb_listing_field_location_autocomplete').value = '';
        jQuery('#bt_bb_show_location').toggleClass('location_detected'); 
        jQuery('#bt_bb_show_location').removeClass('location_detecting');
        return false;
    }
    var lat = lat > 0 ? lat : document.getElementById('bt_bb_listing_field_my_lat').value;  
    var lng = lng > 0 ? lng : document.getElementById('bt_bb_listing_field_my_lng').value; 
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

                        document.getElementById('bt_bb_listing_field_location_autocomplete').value = address;
                        jQuery('#bt_bb_show_location').toggleClass('location_detected'); 
                        jQuery('#bt_bb_show_location').removeClass('location_detecting');
                } else {
                        console.log('No results found');
                }
          } else {
                console.log('Geocoder failed due to: ' + status);
          }
    });
}

function bt_get_my_position(user_position) {
    jQuery('#bt_bb_show_location').addClass('location_detecting');
    var options = {
        enableHighAccuracy: true,
        timeout: 100000,
        maximumAge: 0
    }; 
    
    if ( user_position == 0 ){
        //customizer position
        my_lat.value = ajax_object.ajax_lat;
	my_lng.value = ajax_object.ajax_lng;
        bt_bb_geocode_latlng(my_lat.value,my_lng.value, 1);
    }else{
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(bt_show_my_position, bt_my_error, options);            
        }
    } 
}
function bt_show_my_position(position) {   
    var crd = position.coords;
    // user position
    my_lat.value = crd.latitude;
    my_lng.value = crd.longitude;	        
    bt_bb_geocode_latlng(my_lat.value,my_lng.value, 0);
}

function bt_my_error(error) {
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

function bt_bb_set_allow_distance() { 
       jQuery( '.bt_bb_listing_view_as_grid .bt_bb_listing_box' ).each(function() {
           var latitude    =  typeof  jQuery( this ).data('latitude') !== 'undefined' ?  jQuery( this ).data( 'latitude' ) : '';
           var longitude   =  typeof  jQuery( this ).data('longitude') !== 'undefined' ?  jQuery( this ).data( 'longitude' ) : '';
           var postid      =  typeof  jQuery( this ).data('postid') !== 'undefined' ?  jQuery( this ).data( 'postid' ) : 0;
           var unit        =  typeof  jQuery( this ).data('unit') !== 'undefined' ?  jQuery( this ).data( 'unit' ) : '';
           if ( latitude != '' && longitude != '' && postid > 0 && unit != '') {
               bt_get_distance(  latitude, longitude,postid, jQuery( this ).data('unit'));  
           }                
       });
   }

function bt_get_user_location() {
	var options = {
	  enableHighAccuracy: true,
	  timeout: 100000,
	  maximumAge: 0
	};
       
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(bt_show_position, bt_error, options); 
                
	}else{
		bt_show_position_default();
	}
        
}

function bt_show_position(position) {
	var crd = position.coords;
	var distance = bt_calculate_distance( ajax_lat, ajax_lng, crd.latitude, crd.longitude, ajax_unit );
	if ( parseFloat(distance) > parseFloat(ajax_radius) )
	{
		//customizer position
		my_lat.value = ajax_lat;
		my_lng.value = ajax_lng;
                my_lat_default.value = ajax_lat;
                my_lng_default.value = ajax_lng;
	}else{
		// user position
		my_lat.value = crd.latitude;
		my_lng.value = crd.longitude;
                my_lat_default.value = crd.latitude;
                my_lng_default.value = crd.longitude;
	}
        bt_bb_set_allow_distance();
}

function bt_show_position_default() {
        var ajax_lat        = ajax_object.ajax_lat;
        var ajax_lng        = ajax_object.ajax_lng;
        var my_lat          = document.getElementById('bt_bb_listing_field_my_lat');
        var my_lng          = document.getElementById('bt_bb_listing_field_my_lng');
        
        if ( typeof  my_lat !== 'undefined' ){
            my_lat.value = ajax_lat;
        }
        if ( typeof  my_lng !== 'undefined' ){
            my_lng.value = ajax_lng;
        }
}

function bt_get_random_distance(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min)) + min; 
}

function bt_get_distance( latitudeTo, longitudeTo, listingID, unit) {
        var ajax_lat        = ajax_object.ajax_lat;
        var ajax_lng        = ajax_object.ajax_lng;
        var my_lat          = document.getElementById('bt_bb_listing_field_my_lat');
        var my_lng          = document.getElementById('bt_bb_listing_field_my_lng');
        var my_lat_value    =  typeof  my_lat !== 'undefined' ?  my_lat.value : ajax_lat;
        var my_lng_value    =  typeof  my_lng !== 'undefined' ?  my_lng.value : ajax_lng;
        
        var distance = 0;
        if ( ajax_object.ajax_random_distance == 1 ){
            distance = bt_get_random_distance(1, 10);
        }else{
            distance = bt_calculate_distance( my_lat_value, my_lng_value, latitudeTo, longitudeTo, unit );
        }
	
	if (!isNaN(distance)) 
	{
                if ( unit == 'km' && distance < 1) {
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
		jQuery('#bt_bb_listing_distance_' + listingID).html(distance + ' ' + unit_display + '.');
	}else{
		jQuery('#bt_bb_listing_distance_' + listingID).hide();
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






