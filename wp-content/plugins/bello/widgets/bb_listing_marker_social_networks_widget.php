<?php
$custom_fields = bello_get_listing_fields();

$social_facebook	= bello_get_listing_field_value ( 'social_facebook' );
$social_twitter		= bello_get_listing_field_value ( 'social_twitter' );
$social_instagram	= bello_get_listing_field_value ( 'social_instagram' );
$social_google_plus	= bello_get_listing_field_value ( 'social_google_plus' );
$social_pinterest	= bello_get_listing_field_value ( 'social_pinterest' );
$social_yelp		= bello_get_listing_field_value ( 'social_yelp' );
$social_youtube		= bello_get_listing_field_value ( 'social_youtube' );

?>
<div class="bt_bb_listing_marker_social_icons">
	<?php if ( $social_facebook != '') { ?>
		<div class="bt_bb_icon btIcoFacebook bt_bb_style_filled bt_bb_size_xsmall bt_bb_shape_circle"><a href="<?php echo $social_facebook;?>" target="_blank" data-ico-fa="" class="bt_bb_icon_holder"></a></div>
	<?php } ?>
	<?php if ( $social_twitter != '') { ?>
		<div class="bt_bb_icon btIcoTwitter bt_bb_style_filled bt_bb_size_xsmall bt_bb_shape_circle"><a href="<?php echo $social_twitter;?>" target="_blank" data-ico-fa="" class="bt_bb_icon_holder"></a></div>
	<?php } ?>
	<?php if ( $social_instagram != '') { ?>
		<div class="bt_bb_icon btIcoInstagram bt_bb_style_filled bt_bb_size_xsmall bt_bb_shape_circle"><a href="<?php echo $social_instagram;?>" target="_blank" data-ico-fa="" class="bt_bb_icon_holder"></a></div>
	<?php } ?>
	<?php if ( $social_pinterest != '') { ?>
		<div class="bt_bb_icon btIcoPinterest bt_bb_style_filled bt_bb_size_xsmall bt_bb_shape_circle"><a href="<?php echo $social_pinterest;?>" target="_blank" data-ico-fa="" class="bt_bb_icon_holder"></a></div>
	<?php } ?>
	<?php if ( $social_yelp != '') { ?>
		<div class="bt_bb_icon btIcoYelp bt_bb_style_filled bt_bb_size_xsmall bt_bb_shape_circle"><a href="<?php echo $social_yelp;?>" target="_blank" data-ico-fa="" class="bt_bb_icon_holder"></a></div>
	<?php } ?>
	<?php if ( $social_youtube != '') { ?>
		<div class="bt_bb_icon btIcoYoutube bt_bb_style_filled bt_bb_size_xsmall bt_bb_shape_circle"><a href="<?php echo $social_youtube;?>" target="_blank" data-ico-fa="" class="bt_bb_icon_holder"></a></div>
	<?php } ?>
</div>
