<?php
function bt_enqueue_localization_scripts() {	
	if ( ! is_admin() ) {
		wp_register_script( 'jquery-messages-localization', plugin_dir_url( __FILE__ ) . '/messages.js', array( 'jquery' ) );
		wp_enqueue_script( 'jquery-messages-localization' );	
		wp_localize_script( 'jquery-messages-localization', 'plugin_localization_messages_object', 
			  array( 
							'required'		=> esc_html__( 'This field is required', 'bt_plugin' ),
							'remote'		=> esc_html__( 'Please fix this field.', 'bt_plugin' ),						
							'email'			=> esc_html__( 'Please enter a valid email address.', 'bt_plugin' ),
							'url'			=> esc_html__( 'Please enter a valid URL.', 'bt_plugin' ),
							'date'			=> esc_html__( 'Please enter a valid date.', 'bt_plugin' ),
							'dateISO'		=> esc_html__( 'Please enter a valid date (ISO).', 'bt_plugin' ),
							'number'		=> esc_html__( 'Please enter a valid number.', 'bt_plugin' ),
							'digits'		=> esc_html__( 'Please enter only digits.', 'bt_plugin' ),
							'equalTo'		=> esc_html__( 'Please enter the same value again.', 'bt_plugin' ),
							'maxlength'		=> esc_html__( 'Please enter no more than {0} characters.', 'bt_plugin' ),
							'minlength'		=> esc_html__( 'Please enter at least {0} characters.', 'bt_plugin' ),
							'rangelength'	=> esc_html__( 'Please enter a value between {0} and {1} characters long.', 'bt_plugin' ),
							'range'			=> esc_html__( 'Please enter a value between {0} and {1}.', 'bt_plugin' ),
							'max'			=> esc_html__( '"Please enter a value less than or equal to {0}.', 'bt_plugin' ),
							'min'			=> esc_html__( 'Please enter a value greater than or equal to {0}.', 'bt_plugin' ),
							'step'			=> esc_html__( 'Please enter a multiple of {0}.', 'bt_plugin' )
					) 
		);	
	}
}
add_action( 'wp_enqueue_scripts', 'bt_enqueue_localization_scripts' );

