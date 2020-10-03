<?php

if ( class_exists( 'RWMB_Field' ) )
{
	class RWMB_Price_Field extends RWMB_Field
	{
		static public function html( $meta, $field )
		{
                        $listing_id = isset($_GET["listing_id"]) ? $_GET["listing_id"] : get_the_ID();
                        
                        $output = '';
                        $price_from = '';
                        $price_to = '';
                        $checked_free = 'checked';
                        
                        $meta_values    = get_post_meta( $listing_id );
                        
                        if ( !empty($meta_values)) {                            
                            if ( isset( $meta_values['boldthemes_theme_listing-price_from'] )){
                                $boldthemes_theme_listing_price_from_arr =  $meta_values['boldthemes_theme_listing-price_from'];
                                if (is_array($boldthemes_theme_listing_price_from_arr)){
                                    foreach ( $boldthemes_theme_listing_price_from_arr as $boldthemes_theme_listing_price_from){
                                        if ( $boldthemes_theme_listing_price_from != '' ){
                                            $price_from = $boldthemes_theme_listing_price_from;
                                            break;
                                        }
                                    }
                                }
                            }
                            if ( isset( $meta_values['boldthemes_theme_listing-price_to'] )){
                                $boldthemes_theme_listing_price_to_arr =  $meta_values['boldthemes_theme_listing-price_to'];
                                if (is_array($boldthemes_theme_listing_price_to_arr)){
                                    foreach ( $boldthemes_theme_listing_price_to_arr as $boldthemes_theme_listing_price_to){
                                        if ( $boldthemes_theme_listing_price_to != '' ){
                                            $price_to = $boldthemes_theme_listing_price_to;
                                            break;
                                        }
                                    }
                                }
                            }
                            
                            if ( isset( $meta_values['boldthemes_theme_listing-price_free'] )){
                                $checked_free =$meta_values['boldthemes_theme_listing-price_free'][0] == 1 ?  ' checked' : '';
                            }
                            
                            
                        }
                        $text1 = '<input type="number" step="0.01" id="boldthemes_theme_listing-price_from" name="boldthemes_theme_listing-price_from" value="' . $price_from . '" placeholder="' . __( 'Price From', 'bt_plugin' ) . '">';
                        $text2 = '<input type="number" step="0.01" id="boldthemes_theme_listing-price_to" name="boldthemes_theme_listing-price_to" value="' . $price_to . '" placeholder="' . __( 'Price To', 'bt_plugin' ) . '"><br />';

                        $text3 = '<div class="rwmb-price-free"><input type="checkbox" id="boldthemes_theme_listing-price_free" name="boldthemes_theme_listing-price_free" value="1" ' . $checked_free . '>';
                        $text3 .= '<label for="boldthemes_theme_listing-price_free" class="bt_bb_check_label">' . __( 'Free', 'bt_plugin' ) . '</label></div>';

                        $output = $text1 . $text2 . $text3;

                       
                    return $output;
		}
	}
}

