<?php
if ( isset($field) ) {
    if ( isset($field['name']) && isset($field['value']) ) {   
        if ($field['value'][0] != '') {
        ?>
            <div class="bt_bb_listing_textarea <?php echo $field['group'];?> <?php echo $field['slug'];?>">
                    <span><?php echo $field['value'][0];?></span>
            </div>
        <?php
        }
    }
}

