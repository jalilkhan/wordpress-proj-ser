<?php
if ( isset($field) ) {
	if ( isset( $field['type'] ) && isset( $field['text'] ) ) {	
		$name = 'boldthemes_field_'.$field['type'];	
		$value = isset($get_values[$name]) ? $get_values[$name] : 0;
		$checked = $value == 1 ? ' checked' : '';		
		?>
			<input id="<?php echo $name;?>" name="<?php echo $name;?>" type="checkbox" value="1" <?php echo $checked;?>>
			<label for="<?php echo $name;?>" class="bt_bb_<?php echo $field['type'];?>_label"><?php echo $field['text'];?></label>
		<?php
	}
}