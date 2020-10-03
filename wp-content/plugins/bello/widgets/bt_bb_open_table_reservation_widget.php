
<div class="btOpenTableReservation  <?php echo $el_class ?>" <?php echo $style_attr ?>>
	<?php if ( !empty($rid) && intval($rid) ) : ?>
	<form method="get" class="btOpenTableReservationForm" action="http://www.opentable.<?php echo $domain_ext; ?>/restaurant-search.aspx" target="_blank">
		<div class="btOpenTableReservationRow">
			<div class="btOpenTableReservationColumn btOpenTableReservationColumnDate">
				<?php if ( $show_labels ) : ?>
				<label for="date-otreservations"><?php _e( 'Date', 'bt_plugin' ) ?></label>
				<?php endif; ?>
				<input id="date-otreservations" name="startDate" class="otw-reservation-date" type="date" value="<?php echo date("Y-m-d"); ?>" autocomplete="off" min="<?php echo date("Y-m-d"); ?>">
			</div>
			<div class="btOpenTableReservationColumn btOpenTableReservationColumnTime">
				<?php if ( $show_labels ) : ?>
				<label for="time-otreservations"><?php _e( 'Time', 'bt_plugin' ) ?></label>
				<?php endif; ?>
				<select id="time-otreservations" name="ResTime" class="otw-reservation-time selectpicker">
					<?php
					//Time Loop
					$inc = 30 * 60;
					$start = ( strtotime( '6AM' ) ); // 6  AM
					$end = ( strtotime( '11:59PM' ) ); // 10 PM
					for ( $i = $start; $i <= $end; $i += $inc ) {
						// to the standart format
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
				<label for="party-otreservations"><?php _e( 'People', 'bt_plugin' ) ?></label>
				<?php endif; ?>
				<select id="party-otreservations" name="partySize" class="otw-party-size-select selectpicker">
					<option value="1"><?php _e('1 Person', 'bt_plugin'); ?></option>
					<option value="2" selected="selected"><?php _e('2 People', 'bt_plugin_txtd'); ?></option>
					<option value="3"><?php _e('3 People', 'bt_plugin'); ?></option>
					<option value="4"><?php _e('4 People', 'bt_plugin'); ?></option>
					<option value="5"><?php _e('5 People', 'bt_plugin'); ?></option>
					<option value="6"><?php _e('6 People', 'bt_plugin'); ?></option>
					<option value="7"><?php _e('7 People', 'bt_plugin'); ?></option>
					<option value="8"><?php _e('8 People', 'bt_plugin'); ?></option>
					<option value="9"><?php _e('9 People', 'bt_plugin'); ?></option>
					<option value="10"><?php _e('10 People', 'bt_plugin'); ?></option>
				</select>

			</div>

			<div class="btOpenTableReservationColumn btOpenTableReservationColumnSubmit">
				<input type="submit" class="otreservations-submit" value="<?php echo (__( 'Find a table', 'bt_plugin' )) ?>" />
			</div>
		</div>
		<input type="hidden" name="RestaurantID" class="RestaurantID" value="<?php echo $rid; ?>">
		<input type="hidden" name="rid" class="rid" value="<?php echo $rid; ?>">
		<input type="hidden" name="GeoID" class="GeoID" value="15">
		<input type="hidden" name="txtDateFormat" class="txtDateFormat" value="<?php echo ! empty( $date_format ) ? $date_format : "MM/DD/YYYY"; ?>">
		<input type="hidden" name="RestaurantReferralID" class="RestaurantReferralID" value="<?php echo $rid; ?>">
	</form>
	<?php else : ?>
		<span class="btOpenTableReservationColumn btOpenTableReservationColumnError"><?php _e('OpenTable restaurant ID is not valid.', 'bt_plugin') ?></span>
	<?php endif; ?>
</div>
<?php
echo sprintf( "<script type='text/javascript' src='//www.opentable.com/widget/reservation/loader?rid=%s&type=standard&theme=standard&overlay=false&iframe=true'></script>", $rid );