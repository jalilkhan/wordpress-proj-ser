<?php
$custom_fields = bello_get_listing_fields();

$contact_phone		= bello_get_listing_field_value ( 'contact_phone' );
$contact_mobile		= bello_get_listing_field_value ( 'contact_mobile' );

$location_position  = bello_get_listing_field_value ( 'location_position' );
$map = array();
if ( !empty($location_position) ){
	$map	= explode(",", $location_position);
	$lat	= isset($map[0]) ? $map[0] : '40.604620';
	$lng	= isset($map[1]) ? $map[1] : '-74.161917';
	$zoom	= isset($map[2]) ? $map[2] : '14';
}

//https://www.google.com/maps/dir//22,40/@22.0110607,39.9378705,12z/data=!3m1!4b1
?>
<ul>
	<li><a href="#" class="bt_bb_listing_marker_add_favourite added_favourite"><span><?php esc_html_e( 'Add as favourite', 'bt_plugin' ); ?></span></a></li>
	<?php if ( !empty($map) ){ ?>
		<li><a href="https://www.google.com/maps/dir//<?php echo $lat;?>,<?php echo $lng;?>/@<?php echo $lat;?>,<?php echo $lng;?>,<?php echo $zoom;?>z" class="bt_bb_listing_marker_get_directions" target="_blank"><span><?php esc_html_e( 'Get directions', 'bt_plugin' ); ?></span><em class="bt_bb_listing_marker_small_circle">4</em></a></li>
	<?php } ?>
	<li><a href="#" class="bt_bb_listing_marker_write_review"><span><?php esc_html_e( 'Write a review', 'bt_plugin' ); ?></span><em class="bt_bb_listing_marker_small_circle">5</em></a></li>
	<?php if ( $contact_phone != '') { ?>
		<li><a href="tel:<?php echo $contact_phone;?>" class="bt_bb_listing_marker_make_call"><span><?php esc_html_e( 'Make a call', 'bt_plugin' ); ?></span></a></li>
	<?php } ?>
</ul>
