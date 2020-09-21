<?php

class bt_bb_open_table_reservation extends BT_BB_Element {

	function __construct() {
		parent::__construct();
	}

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'rid'      				=> '',
			'show_labels'      		=> '',
			'show_icons'      		=> '',
			'orientation'      		=> '',
			'domain_ext'       		=> ''
		) ), $atts, $this->shortcode ) );

		$rid = sanitize_text_field( $rid );
		$show_labels = sanitize_text_field( $show_labels );
		$show_icons = sanitize_text_field( $show_icons );
		$orientation = sanitize_text_field( $orientation );
		$domain_ext = sanitize_text_field( $domain_ext );
		$el_style = sanitize_text_field( $el_style );
		$el_class = sanitize_text_field( $el_class );
		
		$date_format = 'MM/DD/YYYY';
		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' style="' . esc_attr( $el_style ) . '"';
		}
		
		$el_class .= $orientation;
		
		if ( $show_icons != '' ) {
			$el_class .= ' btShowIcons';
		}

		ob_start();
		require "templates/bt_bb_open_table_reservation_template.php";
		return ob_get_clean();

	}

	function map_shortcode() {

		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Open table reservation', 'bello' ), 'description' => esc_html__( 'Use your Opentable account', 'bello' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'rid', 'type' => 'textfield', 'heading' => esc_html__( 'OpenTable Restaurant ID', 'bello' ), 'preview' => true ),
				array( 'param_name' => 'show_labels', 'type' => 'checkbox', 'value' => array( 'Yes' => 'true' ), 'heading' => esc_html__( 'Show labels', 'bello' ) ),
				array( 'param_name' => 'show_icons', 'type' => 'checkbox', 'value' => array( 'Yes' => 'true' ), 'heading' => esc_html__( 'Show icons', 'bello' ) ),
				array( 'param_name' => 'orientation', 'type' => 'dropdown', 'heading' => esc_html__( 'Orientation', 'bello' ), 'preview' => true,
					'value' => array(
						esc_html__( 'Horizontal', 'bello' ) 		=> 'btHorizontalOrientation',
						esc_html__( 'Vertical', 'bello' ) 			=> 'btVerticalOrientation'
					) ),
				array( 'param_name' => 'domain_ext', 'type' => 'dropdown', 'heading' => esc_html__( 'Country', 'bello' ), 'preview' => true,
					'value' => array(
						esc_html__( 'Global / U.S.', 'bello' ) 		=> 'com',
						esc_html__( 'United Kingdom', 'bello' ) 	=> 'co.uk',
						esc_html__( 'Japan', 'bello' ) 				=> 'jp',
						esc_html__( 'Germany', 'bello' )	 		=> 'de',
						esc_html__( 'Mexico', 'bello' )	 			=> 'com.mx'
					) )
			)
		) );

	}
}