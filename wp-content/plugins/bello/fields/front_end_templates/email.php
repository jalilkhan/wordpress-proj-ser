<?php
if ( isset($field) ) {
	if ( isset($field['name']) && isset($field['value']) ) {
                if ($field['value'][0] != '') {
                    ?>
                    <div class="bt_bb_listing_email <?php echo $field['group'];?> <?php echo $field['slug'];?>">
                            <a href="mailto:<?php echo $field['value'][0];?>"><?php echo $field['value'][0];?></a>
                    </div>
                    <?php
                }
	}
}
?>