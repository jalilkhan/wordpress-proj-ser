<?php
if ( isset($field) ) {
	if ( isset($field['name']) && isset($field['value']) ) {
		?>
		<div class="bt_bb_listing_url <?php echo $field['group'];?> <?php echo $field['slug'];?>">
			<a href="mailto:<?php echo $field['value'][0];?>"><?php echo $field['value'][0];?></a>
		</div>
		<?php
	}
}
?>