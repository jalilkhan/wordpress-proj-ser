<?php
if ( isset($field) ) {
    if ( isset($field['name']) && isset($field['value']) ) {   
        $date_format    = get_option('date_format') != '' ?  get_option('date_format') : 'Y-m-d';                       
        $output         = isset($field['value'][0]) && $field['value'][0] != '' ? date($date_format, strtotime($field['value'][0])) : ''; 
        if ($output) {
        ?>
            <div class="bt_bb_listing_date <?php echo $field['group'];?> <?php echo $field['slug'];?>">
                    <span><?php echo $output;?></span>
            </div>
        <?php
        }
    }
}