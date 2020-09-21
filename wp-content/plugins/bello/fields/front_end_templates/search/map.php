<?php
BoldThemesFrameworkTemplate::$listing_search_distance_unit  = boldthemes_get_option( 'listing_search_distance_unit' ) != '' ? boldthemes_get_option( 'listing_search_distance_unit' ) : 'mi';
BoldThemesFrameworkTemplate::$listing_search_autocomplete	= bt_is_autocomplete();

if ( isset($field) ) {
    if ( isset( $field['type'] ) && isset( $field['text'] ) ) {	
       
        $bt_bb_listing_distance_max             = boldthemes_get_option( 'listing_distance_max' ) != '' ? boldthemes_get_option( 'listing_distance_max' ) : '100000'; 
        $bt_bb_listing_distance_max_in_slider	= boldthemes_get_option( 'listing_distance_max_in_slider' ) != '' ? boldthemes_get_option( 'listing_distance_max_in_slider' ) : false; 
        
        $distance_max_in_slider = $bt_bb_listing_distance_max_in_slider ? $bt_bb_listing_distance_max : 0;
        $name1= 'bt_bb_listing_field_distance';
        $value1 = isset($get_values[$name1]) ? $get_values[$name1] : $distance_max_in_slider;
        $name2 = 'bt_bb_listing_field_distance_value';	
        $value2 = isset($get_values[$name2]) ? $get_values[$name2] : $distance_max_in_slider;
        
        if ( $value1 == 0 ){
            $value1 = $value2;
        }
                
        $step = intval(BoldThemesFrameworkTemplate::$listing_distance_max) > 0 ? BoldThemesFrameworkTemplate::$listing_distance_max / 100 : 1;
        ?>
        <?php if (BoldThemesFrameworkTemplate::$listing_search_autocomplete){ ?>

            <div class="bt_bb_control_container">
                    <div class="bt_bb_control_half">
                        <label for="bt_bb_listing_field_distance" class="bt_bb_distance_label"><?php esc_html_e( 'Where to look for?', 'bt_plugin' ); ?> 
                            <span class="bt_bb_listing_note bt_bb_category_help" title="<?php esc_html_e( 'Search for a location or let us detect your location', 'bt_plugin' ); ?>"></span>
                        </label>
			<a href="#" class="bt_bb_show_location_help" id="bt_bb_show_location"  title="<?php esc_html_e( 'Click here to detect your location or reset to default location', 'bt_plugin' ); ?>"></a>
                        <input name="bt_bb_listing_field_location_autocomplete" id="bt_bb_listing_field_location_autocomplete" type="text" placeholder="<?php esc_html_e( 'Search for location', 'bt_plugin' ); ?>" value="<?php echo BoldThemesFrameworkTemplate::$bt_bb_listing_field_location_autocomplete;?>"/>
                    </div>
                    <div class="bt_bb_control_half">
                         <label for="bt_bb_listing_field_distance" class="bt_bb_distance_label">
                            <span id="bt_bb_listing_field_distance_label"><?php esc_html_e( 'Distance', 'bt_plugin' ); ?></span>                 
                            <span class="bt_bb_listing_note bt_bb_category_help" title="<?php esc_html_e( 'Distance is disabled from search when equals zero', 'bt_plugin' ); ?>"></span>
                            <span class="bt_bb_listing_note">(<?php echo  BoldThemesFrameworkTemplate::$listing_search_distance_unit;?>)</span>
                        </label>
                        <input name="bt_bb_listing_field_distance" id="bt_bb_listing_field_distance" type="range"  min="0" max="<?php echo BoldThemesFrameworkTemplate::$listing_distance_max;?>" value="<?php echo $value1;?>" step="<?php echo $step;?>">
                        <input name="bt_bb_listing_field_distance_value" id="bt_bb_listing_field_distance_value" type="number" min="0" max="<?php echo BoldThemesFrameworkTemplate::$listing_distance_max;?>" step="<?php echo $step;?>" value="<?php echo $value2;?>">
                    </div>
            </div>
        <?php }
    }
}