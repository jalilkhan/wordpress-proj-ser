<?php
if ( isset($field) ) {
	if ( isset($field['name']) && isset($field['value']) ) {
                if ($field['value'][0] != '') {
                    $link = is_email( $field['value'][0] ) ? 'mailto:' . $field['value'][0] : $field['value'][0];
                    ?>
                    <div class="bt_bb_listing_url <?php echo $field['group'];?> <?php echo $field['slug'];?>">
                            <a href="<?php echo $link;?>" target="_blank"><?php echo $field['value'][0];?></a>
                    </div>
                    <?php
                }
	}
}
?>