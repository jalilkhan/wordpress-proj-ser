<div class="btOpenTableReservation  <?php echo esc_attr($el_class); ?>" <?php echo esc_attr($style_attr); ?>>
	<?php if ( !empty($rid) && intval($rid) ) : ?>
	<form method="get" class="btOpenTableReservationForm" action="http://www.opentable.<?php echo esc_url($domain_ext); ?>/restaurant-search.aspx" target="_blank">
		<div class="btOpenTableReservationRow">
			<div class="btOpenTableReservationColumn btOpenTableReservationColumnDate">
				<?php if ( $show_labels ) : ?>
				<label for="date-otreservations"><?php _e( 'Date', 'bello' ) ?></label>
				<?php endif; ?>
				<input id="date-otreservations" name="startDate" class="otw-reservation-date" type="date" value="<?php echo esc_attr( date("Y-m-d") ); ?>" autocomplete="off" min="<?php echo esc_attr( date("Y-m-d") ); ?>">
			</div>
			<div class="btOpenTableReservationColumn btOpenTableReservationColumnTime">
				<?php if ( $show_labels ) : ?>
				<label for="time-otreservations"><?php _e( 'Time', 'bello' ) ?></label>
				<?php endif; ?>
				<select id="time-otreservations" name="ResTime" class="otw-reservation-time selectpicker">
					<?php
					$inc = 30 * 60;
					$start = ( strtotime( '6AM' ) ); 
					$end = ( strtotime( '11:59PM' ) ); 
					for ( $i = $start; $i <= $end; $i += $inc ) {
						$time      = date( 'g:i a', $i );
						$timeValue = date( 'g:ia', $i );
						$default   = "7:00pm";
						echo "<option value=\"$timeValue\" " . ( ( $timeValue == $default ) ? ' selected="selected" ' : "" ) . ">$time</option>" . PHP_EOL;
					}
					?>
				</select>

			</div>
			<div class="btOpenTableReservationColumn btOpenTableReservationColumnPeople">
				<?php if ( $show_labels ) : ?>
				<label for="party-otreservations"><?php _e( 'People', 'bello' ) ?></label>
				<?php endif; ?>
				<select id="party-otreservations" name="partySize" class="otw-party-size-select selectpicker">
					<option value="1"><?php _e('1 Person', 'bello'); ?></option>
					<option value="2" selected="selected"><?php _e('2 People', 'bello'); ?></option>
					<option value="3"><?php _e('3 People', 'bello'); ?></option>
					<option value="4"><?php _e('4 People', 'bello'); ?></option>
					<option value="5"><?php _e('5 People', 'bello'); ?></option>
					<option value="6"><?php _e('6 People', 'bello'); ?></option>
					<option value="7"><?php _e('7 People', 'bello'); ?></option>
					<option value="8"><?php _e('8 People', 'bello'); ?></option>
					<option value="9"><?php _e('9 People', 'bello'); ?></option>
					<option value="10"><?php _e('10 People', 'bello'); ?></option>
				</select>

			</div>

			<div class="btOpenTableReservationColumn btOpenTableReservationColumnSubmit">
				<input type="submit" class="otreservations-submit" value="<?php echo (esc_attr__( 'Find a table', 'bello' )) ?>" />
			</div>
		</div>
		<input type="hidden" name="RestaurantID" class="RestaurantID" value="<?php echo esc_attr($rid); ?>">
		<input type="hidden" name="rid" class="rid" value="<?php echo esc_attr($rid); ?>">
		<input type="hidden" name="GeoID" class="GeoID" value="15">
		<input type="hidden" name="txtDateFormat" class="txtDateFormat" value="<?php echo ! empty( $date_format ) ? $date_format : "MM/DD/YYYY"; ?>">
		<input type="hidden" name="RestaurantReferralID" class="RestaurantReferralID" value="<?php echo esc_attr($rid); ?>">
	</form>
	<?php else : ?>
		<span class="btOpenTableReservationColumn btOpenTableReservationColumnError"><?php _e('OpenTable restaurant ID is not valid.', 'bello') ?></span>
	<?php endif; ?>
</div>