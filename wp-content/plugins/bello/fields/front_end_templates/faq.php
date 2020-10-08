<?php

if ( isset($field) ) {
	if ( isset($field['name']) && isset($field['value']) ) {
                if ($field['value'][0] != '') {
                ?>
                    <div class="bt_bb_listing_faq <?php echo $field['group'];?> <?php echo $field['slug'];?>">
                        <?php echo $field['value'][0]; ?>
                    </div>
                <?php
                }
	}
}
