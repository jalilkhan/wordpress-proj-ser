<?php
if ( isset($field) ) {
	if ( isset($field['name']) && isset($field['value']) ) {
                if ($field['value'][0] != '') {
                    $link = $field['value'][0] ? bt_format_phone_number( $field['value'][0] ) : '';
                    ?>
                    <div class="bt_bb_listing_phone <?php echo $field['group'];?> <?php echo $field['slug'];?>">
                            <a href="tel:<?php echo $link;?>"><?php echo $field['value'][0];?></a>
                    </div>
                    <?php
                }
	}
}