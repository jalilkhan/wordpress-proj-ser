<?php
if ( class_exists( 'RWMB_Field' ) )
{
	class RWMB_Region_Field extends RWMB_Field
	{
		static public function html( $meta, $field )
		{
                  
			$args = array(
				'taxonomy'   => 'listing-region',
				'hide_empty' => false
			);
			$regions = get_terms( $args );
			$select = '<select name="' . $field['field_name'] . '" id="' . $field['id'] . '">';
				$select .= '<option></option>';
				foreach( $regions as $r ) {
					if ( $meta == $r->slug ) {
						$select .= '<option value="' . $r->slug . '" selected>' . $r->name . '</option>';
					} else {
						$select .= '<option value="' . $r->slug . '">' . $r->name . '</option>';
					}
				}
			$select .= '</select>';

			return $select;
		}
	}
}