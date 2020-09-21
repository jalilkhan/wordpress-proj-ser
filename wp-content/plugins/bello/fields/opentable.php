<?php
// restauran id : 107194
if ( class_exists( 'RWMB_Field' ) )
{
	class RWMB_Opentable_Field extends RWMB_Field
	{
		static public function html( $meta, $field )
		{

			$opentable_id		=  isset( $meta[0] ) ? $meta[0] : '';
			$opentable_dom_ext	=  isset( $meta[1] ) ? $meta[1] : '';

			$start = '<input type="text" name="' . $field['field_name'] . '[0]" value="' . $opentable_id . '" placeholder="' . __( 'Opentable Restaurant ID', 'bt_plugin' ) . '">&nbsp;&nbsp;';
			$end = '<select name="' . $field['field_name'] . '[1]">';
					$end .= '<option value="">' . __( 'Select Country', 'bt_plugin' ) . '</option>';					
					$domain_ext_arr = array("Global / U.S." => "com", "United Kingdom" => "co.uk", "Japan" => "jp", "Germany" => "de", "Mexico" => "com.mx");
					foreach( $domain_ext_arr as $key => $value ) {
						if ( $value == $opentable_dom_ext ) {
							$end .= '<option value="' . $value . '" selected>' . $key . '</option>';
						} else {
							$end .= '<option value="' . $value . '">' . $key . '</option>';
						}
					}
					
			$end .= '</select>';

			$output = $start . $end;

			return $output;
		}

	}
}