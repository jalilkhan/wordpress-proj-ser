<?php
/*

https://github.com/timekit-io/booking-js#configuration

*/

if ( class_exists( 'RWMB_Field' ) )
{
	class RWMB_Timekit_Field extends RWMB_Field
	{

		/**
		 * Get field HTML 		
		 *
		 * @param mixed $meta
		 * @param array $field
		 *
		 * @return string
		 */
		static public function html( $meta, $field )
		{

			$name		=  isset( $meta[0] ) ? $meta[0] : '';
			$email		=  isset( $meta[1] ) ? $meta[1] : '';
			$app		=  isset( $meta[2] ) ? $meta[2] : '';
			$apiToken	=  isset( $meta[3] ) ? $meta[3] : '';
			$calendar	=  isset( $meta[4] ) ? $meta[4] : '';			
			$widget_id	=  isset( $meta[5] ) ? $meta[5] : '';

			$text1 = '<input type="text" name="' . $field['field_name'] . '[0]" value="' . $name . '" placeholder="' . __( 'Name', 'bt_plugin' ) . '">';
			$text2 = '<input type="text" name="' . $field['field_name'] . '[1]" value="' . $email . '" placeholder="' . __( 'Email', 'bt_plugin' ) . '"><br />';
			$text3 = '<input type="text" name="' . $field['field_name'] . '[2]" value="' . $app . '" placeholder="' . __( 'App', 'bt_plugin' ) . '">';
			$text4 = '<input type="text" name="' . $field['field_name'] . '[3]" value="' . $apiToken . '" placeholder="' . __( 'API Token', 'bt_plugin' ) . '">';			
			$text5 = '<input type="text" name="' . $field['field_name'] . '[4]" value="' . $calendar . '" placeholder="' . __( 'Calendar', 'bt_plugin' ) . '">';			
			$text6 = '<input type="text" name="' . $field['field_name'] . '[5]" value="' . $widget_id . '" placeholder="' . __( 'Widget ID', 'bt_plugin' ) . '">';
			

			$output = $text1 . $text2 . $text3 . $text4 . $text5 . '&nbsp;OR&nbsp; ' . $text6;

			return $output;
		}

	}
}