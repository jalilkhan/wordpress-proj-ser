<?php
if ( isset($field) ) {
	if ( isset($field['name']) && isset($field['value']) ) {
                if ($field['value'][0] != '') {
                    ?>
                    <div class="bt_bb_listing_select <?php echo $field['group'];?>">
                            <span style="color:<?php echo $field['value'][0];?>;">Select <?php echo $field['name'];?>: <?php echo $field['value'][0];?></span>
                    </div>
                    <?php
                }
	}
}