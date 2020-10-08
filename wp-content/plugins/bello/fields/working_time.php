<?php
if ( class_exists( 'RWMB_Field' ) )
{
	class RWMB_Working_Time_Field extends RWMB_Field
	{
		static public function html( $meta, $field )
		{
                    wp_enqueue_script( 'rwmb-time' );
                    
			$start_of_week = get_option( 'start_of_week' );
			$day_name = array( strtotime( 'Sunday' ), strtotime( 'Monday' ), strtotime( 'Tuesday' ), strtotime( 'Wednesday' ), strtotime( 'Thursday' ), strtotime( 'Friday' ), strtotime( 'Saturday' ) );
			for ( $i = 0; $i < $start_of_week; $i++ ) {
				$item = array_shift( $day_name );
				array_push( $day_name, $item );
			}
                        
			$days = $meta;
                        $listing_search_time_format = 'H:i';//get_option( 'time_format' ) != '' ?  get_option( 'time_format' ) : 'H:i'; 
			$output = '';
                        
                        for ( $i = 0; $i < 7; $i++ ) {                                
                                $value_start    = isset( $days[ $i ]['start'] ) && $days[ $i ]['start'] != null && $days[ $i ]['start'] != '' ? $days[ $i ]['start'] : '';
                                $value_end      = isset( $days[ $i ]['end'] ) && $days[ $i ]['end'] != null && $days[ $i ]['end'] != '' ? $days[ $i ]['end'] : '';
                                $value_start2   = isset( $days[ $i ]['start2'] ) && $days[ $i ]['start2'] != null && $days[ $i ]['start2'] != '' ? $days[ $i ]['start2'] : '';
                                $value_end2     = isset( $days[ $i ]['end2'] ) && $days[ $i ]['end2'] != null  && $days[ $i ]['end2'] != ''? $days[ $i ]['end2'] : '';

                                $value_start    = $value_start     != '' ? date($listing_search_time_format, strtotime($value_start)) : '';                                
                                $value_end      = $value_end       != '' ? date($listing_search_time_format, strtotime($value_end)) : '';
                                $value_start2   = $value_start2    != '' ? date($listing_search_time_format, strtotime($value_start2)) : '';
                                $value_end2     = $value_end2      != '' ? date($listing_search_time_format, strtotime($value_end2)) : '';

                                $checked = isset( $days[ $i ]['all'] ) ? 'checked' : '';

                                $title = '<h4>' . date_i18n( 'l', $day_name[ $i ] ) . '</h4>';
                                $start = '<input type="time" class="rwmb-time hasDatepicker" name="' . $field['field_name'] . '[' . $i . '][start]" value="' . $value_start . '" placeholder="' . __( 'Start', 'bt_plugin' ) . '">';
                                $end = '<input type="time" class="rwmb-time hasDatepicker" name="' . $field['field_name'] . '[' . $i . '][end]" value="' . $value_end . '" placeholder="' . __( 'End', 'bt_plugin' ) . '">';


                                $start2 = '<input type="time" class="rwmb-time hasDatepicker" name="' . $field['field_name'] . '[' . $i . '][start2]" value="' . $value_start2 . '" placeholder="' . __( 'Start', 'bt_plugin' ) . '">';
                                $end2 = '<input type="time" class="rwmb-time hasDatepicker" name="' . $field['field_name'] . '[' . $i . '][end2]" value="' . $value_end2 . '" placeholder="' . __( 'End', 'bt_plugin' ) . '">';

                                $start3 = '<div class="rwmb-time-24hrs"><input id="' . $field['field_name'] . '[' . $i . '][all]" name="' . $field['field_name'] . '[' . $i . '][all]" type="checkbox" value="1" ' . $checked . '>';
                                $end3 = '<label for="' . $field['field_name'] . '[' . $i . '][all]" class="bt_bb_check_label">24H</label></div>';

                                $output .= $title . $start . $end . $start2 . $end2 . $start3 . $end3;                              
                        }

			return $output;
		}
	}
}