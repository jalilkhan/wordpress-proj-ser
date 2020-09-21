<?php
if ( isset($field) ) {
    if ( isset($field['name']) && isset($field['value']) ) {   
        $time_format    = get_option('time_format') != '' ?  get_option('time_format') : 'H:i';                       
        $output         = isset($field['value'][0]) && $field['value'][0] != '' ? date($time_format, strtotime($field['value'][0])) : '';
        if ($output) {
            ?>
                <div class="bt_bb_listing_time <?php echo $field['group'];?> <?php echo $field['slug'];?>">
                        <span><?php echo $output;?></span>
                </div>
            <?php
        }
    }
}
