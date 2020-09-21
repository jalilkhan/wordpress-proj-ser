<?php
if ( isset($field) ) {
    if ( isset($field['name']) ) {   
        $time_format = get_option('time_format') != '' ?  get_option('time_format') : 'H:i'; 
        $date_format = get_option('date_format') != '' ?  get_option('date_format') : 'Y-m-d';
        $format = $date_format . '  ' . $time_format;        
        $output    = isset($field['value'][0]) && $field['value'][0] != '' ? date($format, strtotime($field['value'][0])) : date($format); 
        if ($output) {
            ?>
                <div class="bt_bb_listing_datetime <?php echo $field['group'];?> <?php echo $field['slug'];?>">
                        <span><?php echo $output;?></span>
                </div>
            <?php
        }
    }
}