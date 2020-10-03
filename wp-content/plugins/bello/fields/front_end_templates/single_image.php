<?php
$_html ='';
if ( isset($field) ) {
    if ( isset($field['name']) && isset($field['value']) ) {
        if ( shortcode_exists( 'bt_bb_image' ) ) {
            $image_id = $field['value'][0] ? $field['value'][0] : 0;
            $_html .='<div class="bt_bb_listing_image ' . $field['group'] . ' ' .$field['slug'] . '">' ;
                $_html .= do_shortcode( '[bt_bb_masonry_image_grid images="'.$image_id .'" columns="1" format="11" gap="no_gap" no_lightbox="hide_share"]' );
            $_html .='</div>'; 
        }
    }
}
echo $_html;

