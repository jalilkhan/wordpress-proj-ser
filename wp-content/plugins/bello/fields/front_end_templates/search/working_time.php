<?php
if ( isset($field) ) {
	if ( isset( $field['type'] ) && isset( $field['text'] ) ) {
		$name = 'bt_bb_listing_field_now_open';	
		$value = isset($get_values[$name]) ? $get_values[$name] : 0;
		$checked = $value == 1 ? ' checked' : '';	
		?>
		<label for="bt_bb_listing_field_now_open" class="bt_bb_now_open_label"><?php esc_html_e( 'Open?', 'bt_plugin' ); ?></label>
		<input id="bt_bb_listing_field_now_open" name="bt_bb_listing_field_now_open" type="checkbox" value="1" <?php echo $checked;?>><label for="bt_bb_listing_field_now_open"></label>
		<?php
	}
}