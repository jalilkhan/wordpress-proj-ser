<?php
BoldThemesFrameworkTemplate::$currency_symbol  = boldthemes_get_option( 'listing_search_currency_symbol' ) ? boldthemes_get_option( 'listing_search_currency_symbol' ) : '';   

if ( isset($field) ) {
    if ( isset($field['type']) && isset($field['text']) ) {
        $name1 = 'bt_bb_listing_field_price_range_from';	
        $value1 = isset($get_values[$name1]) ? $get_values[$name1] : '';
        $name2 = 'bt_bb_listing_field_price_range_to';	
        $value2 = isset($get_values[$name2]) ? $get_values[$name2] : '';
        ?>
        <label for="bt_bb_listing_field_price_range_from" class="bt_bb_price_range_from_label">
        <?php esc_html_e( 'Price to', 'bt_plugin' ); ?> <span class="bt_bb_listing_note">(<?php echo BoldThemesFrameworkTemplate::$currency_symbol;?>)</span></label>
        <div class="bt_bb_control_container">
			<input name="bt_bb_listing_field_price_range_to" id="bt_bb_listing_field_price_range_to" type="number" value="<?php echo $value2;?>"  min="0" max="1000" step="5" placeholder="<?php echo __( 'Price To', 'bt_plugin' );?>">
        </div>
        <?php
    }
}