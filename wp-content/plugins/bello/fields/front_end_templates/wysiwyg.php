<?php
if ( isset($field) ) {
	if ( isset($field['name']) && isset($field['value']) ) {
		?>
		<div class="bt_bb_listing_text <?php echo $field['group'];?>">
			<span><?php echo $field['value'][0];?></span>
		</div>
		<?php
	}
}
?>