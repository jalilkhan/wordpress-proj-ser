<?php

if ( class_exists( 'RWMB_Field' ) )
{
	class RWMB_Resurva_Field extends RWMB_Field
	{

		/**
		 * Get field HTML - expl: https://crowsnestbarbershop.resurva.com
		 *
		 * @param mixed $meta
		 * @param array $field
		 *
		 * @return string
		 */
		static public function html( $meta, $field )
		{
			return sprintf(
				'<input type="text" name="%s" id="%s" value="%s" placeholder="' . __( 'Resurva URL', 'bt_plugin' ) . '">',
				$field['field_name'],
				$field['id'],
				$meta
			);
		}
	}
}

