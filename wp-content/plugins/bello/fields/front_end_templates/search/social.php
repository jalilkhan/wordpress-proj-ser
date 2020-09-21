<?php
if ( isset($field) ) {
	if ( isset( $field['type'] ) && isset( $field['text'] ) ) {
		$name = 'bt_bb_listing_field_'.$field['type'];	
		$value = isset($get_values[$name]) ? $get_values[$name] : '';
		?>
		<div class="bt_bb_column col-lg-3 col-md-2 col-sm-12 bt_bb_vertical_align_top bt_bb_padding_normal bt_bb_spaced bt_bb_listing_search_col" data-width="4">
			<div class="bt_bb_listing_search_element">
				<label for="<?php echo $name;?>" class="bt_bb_<?php echo $field['type'];?>_label"><?php echo $field['text'];?></label>
				<div class="btQuoteSwitch" data-off="0" data-on="1" id="<?php echo $name;?>" name="<?php echo $name;?>"><div class="btQuoteSwitchInner"></div></div>
			</div>
		</div>
		<?php
	}
}