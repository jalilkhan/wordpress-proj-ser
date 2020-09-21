<?php
$custom_fields = bello_get_listing_fields();

$contact_address	= bello_get_listing_field_value ( 'contact_address' );
$contact_phone		= bello_get_listing_field_value ( 'contact_phone' );
$contact_mobile		= bello_get_listing_field_value ( 'contact_mobile' );
$contact_website	= bello_get_listing_field_value ( 'contact_website' );
$contact_website	= str_replace("http://", "", $contact_website);
$price				= bello_get_listing_field_value ( 'price' );

?>
<ul class="bt_bb_listing_marker_meta_data_items">
	<?php if ( $contact_address != '') { ?>
		<li class="bt_bb_listing_marker_meta_address"><span><?php echo $contact_address;?></span></li>
	<?php } ?>
	<?php if ( $contact_phone != '') { ?>
		<li class="bt_bb_listing_marker_meta_phone"><a href="tel:<?php echo $contact_phone;?>"><?php echo $contact_phone;?></a></li>
	<?php } ?>
	<?php if ( $contact_mobile != '') { ?>
		<li class="bt_bb_listing_marker_meta_phone"><a href="tel:<?php echo $contact_phone;?>"><?php echo $contact_mobile;?></a></li>
	<?php } ?>
	<?php if ( $contact_website != '') { ?>
		<li class="bt_bb_listing_marker_meta_web_site"><a href="http://<?php echo $contact_website;?>" target="_blank"><?php echo $contact_website;?></a></li>
	<?php } ?>
	<?php if ( $price != '') { ?>
		<li class="bt_bb_listing_marker_meta_price"><span>Prices start from $<?php echo $price;?> ????</span></li>
	<?php } ?>
	<li class="bt_bb_listing_marker_meta_distance"><span>.4 miles from your location ????</span></li>
</ul>
