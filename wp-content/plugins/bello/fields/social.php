<?php

if ( class_exists( 'RWMB_Field' ) )
{
	class RWMB_Social_Field extends RWMB_Field
	{
		static public function html( $meta, $field )
		{
			return sprintf(
				'<input type="text" name="%s" id="%s" value="%s">',
				$field['field_name'],
				$field['id'],
				$meta
			);
		}
	}
}