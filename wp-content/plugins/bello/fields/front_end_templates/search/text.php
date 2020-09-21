<?php
if ( isset($field) ) {
	if ( isset( $field['type'] ) && isset( $field['text'] ) ) {
		$name = 'bt_bb_listing_field_'.$field['type'];	
		$value = isset($get_values[$name]) ? $get_values[$name] : '';
		?>
		<label for="<?php echo $name;?>" class="bt_bb_<?php echo $field['type'];?>_label"> <?php echo $field['text'];?></label>
		<input type="text" name="<?php echo $name;?>" id="bt_bb_listing_field_keyword" placeholder="<?php echo $field['text'];?> to search..." value="<?php echo $value;?>">
		<?php
	}
}