<?php
if ( isset($field) ) {
	if ( isset($field['name']) && isset($field['value']) ) {
		$checked = isset($field['value'][0]) && $field['value'][0] > 0 ? 1 : 0;
                if ( $checked ){
		?>
		<div class="bt_bb_listing_checkbox <?php echo $field['group'];?> <?php echo $field['slug'];?>">
			<span><?php echo $field['name'];?></span>
		</div>
		<?php
                }
	}
}