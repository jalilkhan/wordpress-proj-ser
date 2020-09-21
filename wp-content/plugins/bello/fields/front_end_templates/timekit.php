 <?php

 if ( isset($field) ) {
	if ( isset($field['name']) && isset($field['value']) ) {
		
		$rnd = rand(1000, 9999);
		$timekit	=  boldthemes_rwmb_meta('boldthemes_theme_listing-timekit');
		

		$name		=  isset( $timekit[0] ) ? $timekit[0] : '';		
		$email		=  isset( $timekit[1] ) ? $timekit[1] : '';
		$app		=  isset( $timekit[2] ) ? $timekit[2] : '';
		$apiToken	=  isset( $timekit[3] ) ? $timekit[3] : '';
		$calendar	=  isset( $timekit[4] ) ? $timekit[4] : '';	
		$widget_id	=  isset( $timekit[5] ) ? $timekit[5] : '';		

		if ( $widget_id != '' || ( $name != '' && $email != '' && $app != '' && $apiToken != '' && $calendar != '' ) ){
		
		?>		
				<div class="<?php echo $field['slug'] ?> <?php echo $field['group'];?>">

					<?php if ( $widget_id == ''){?>
							<div id="bookingjs<?php echo $rnd;?>"> </div>
							<?php wp_enqueue_script( 'boldthemes_timekit_booking_js', plugins_url() . '/bello/fields/front_end_templates/booking.min.js' ); ?>
							<script type="text/javascript">
								jQuery( document ).ready(function() {
									  var widget<?php echo $rnd;?> = new TimekitBooking();
									  widget<?php echo $rnd;?>.init({
										targetEl: '#bookingjs<?php echo $rnd;?>',
										name:     '<?php echo $name;?>',
										email:    '<?php echo $email;?>',
										apiToken: '<?php echo $apiToken;?>',
										calendar: '<?php echo $calendar;?>',
										app: '<?php echo $app;?>',
										bookingFields: {
										  'name': {
												placeholder: '<?php esc_attr__( 'Your name', 'bt_plugin' ) ?>'
											  },
										  'email': {
												placeholder: '<?php esc_attr__( 'Your email', 'bt_plugin' ) ?>'
											  },
										  'phone': {
											enabled: true,
											placeholder: '<?php esc_attr__( 'Your phone number', 'bt_plugin' ) ?>',
											prefilled: false,
											required: false,
											locked: false
										  }
										},
										localization: {
										  timeDateFormat: '24h-dmy-mon'
										}
									  });
								});
							</script>
					 

					<?php } else { ?>

							<div id="bookingjs<?php echo $rnd;?>"></div> 
							<?php wp_enqueue_script( 'boldthemes_timekit_booking_min_js', plugins_url() . '/bello/fields/front_end_templates/booking.min.js#asyncload' ); ?>				
							<script>
								window.timekitBookingConfig = { 
										widgetId: '<?php echo $widget_id;?>',
										targetEl: '#bookingjs<?php echo $rnd;?>',
										name:	  '<?php echo $name;?>'
									}
							</script>

					<?php } ?>
				</div>
		<?php
		}
	}
}
/*
name:		'Make an appointment',
email:		'imran@cridio.com',
apiToken:	'0B7NQUIjLMqh5fQ183H9wvX4lY03X5L3',
calendar:	'22f86f0c-ee80-470c-95e8-dadd9d05edd2',
app:		'listingpro'
widgetId:	'df5fac7e-f1b0-47b3-87d7-a7102d1887f4'
*/

/*
app:      'back-to-the-future',
email:    'marty.mcfly@timekit.io',
apiToken: 'bNpbFHRmrfZbtS5nEtCVl8sY5vUkOFCL',
calendar: '8687f058-5b52-4fa4-885c-9294e52ab7d4',
name:     'Marty McFly',
avatar:   '../misc/avatar-mcfly.png'

app:      'back-to-the-future',
email:    'marty.mcfly@timekit.io',
apiToken: 'bNpbFHRmrfZbtS5nEtCVl8sY5vUkOFCL',
calendar: '8687f058-5b52-4fa4-885c-9294e52ab7d4',
name:     'Marty McFly',
widgetId:	'df5fac7e-f1b0-47b3-87d7-a7102d1887f4'
*/