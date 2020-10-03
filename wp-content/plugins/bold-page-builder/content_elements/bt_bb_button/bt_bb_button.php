<?php

class bt_bb_button extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'text'			=> '',
			'icon'			=> '',
			'icon_position'	=> '',
			'url'			=> '',
			'target'		=> '',
			'color_scheme'  => '',
			'style'			=> '',
			'size'			=> '',
			'width'			=> '',
			'shape'			=> '',
			'align'			=> ''
		) ), $atts, $this->shortcode ) );
		
		$class = array( $this->shortcode );
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}	
		
		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . esc_attr( $el_id ) . '"';
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
		}
		
		if ( $icon_position != '' ) {
			$class[] = $this->prefix . 'icon_position' . '_' . $icon_position;
		}
		
		if ( $color_scheme != '' ) {
			$class[] = $this->prefix . 'color_scheme_' . bt_bb_get_color_scheme_id( $color_scheme );
		}
		
		if ( $style != '' ) {
			$class[] = $this->prefix . 'style' . '_' . $style;
		}
		
		if ( $size != '' ) {
			$class[] = $this->prefix . 'size' . '_' . $size;
		}
		
		if ( $width != '' ) {
			$class[] = $this->prefix . 'width' . '_' . $width;
		}
		
		if ( $shape != '' ) {
			$class[] = $this->prefix . 'shape' . '_' . $shape;
		}
		
		if ( $align != '' ) {
			$class[] = $this->prefix . 'align' . '_' . $align;
		}

		if ( $target == '' ) {
			$target = '_self';
		}
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );
		
		$output = $this->get_html( $icon, $text, $url, $target );
		
		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>' . $output . '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}
	
	function get_html( $icon, $text, $url, $target ) {
		
		require_once( dirname(__FILE__) . '/../../content_elements_misc/misc.php' );

		if ( $url == '' ) {
			$url = '#';
		}
		
		$text_output = '';

		if ( $text != '' ) {
			$text_output = '<span class="bt_bb_button_text">' . $text . '</span>';
		}

		$link = bt_bb_get_url( $url );

		// IMPORTANT: esc_attr must be used instead of esc_url
		$output = '<a href="' . esc_attr( $link ) . '" target="' . esc_attr( $target ) . '" class="' . esc_attr( $this->prefix ) . 'link" title="' . esc_attr( $text ) . '">';
			if ( $icon == '' || $icon == 'no_icon' ) {
				$output .= $text_output;
			} else {
				$output .= $text_output . bt_bb_icon::get_html( $icon, '', '', '' );
			}
		$output .= '</a>';
		
		return $output;
	}	

	function map_shortcode() {

		if ( function_exists('boldthemes_get_icon_fonts_bb_array') ) {
			$icon_arr = boldthemes_get_icon_fonts_bb_array();
		} else {
			require_once( dirname(__FILE__) . '/../../content_elements_misc/fa_icons.php' );
			require_once( dirname(__FILE__) . '/../../content_elements_misc/s7_icons.php' );
			$icon_arr = array( 'Font Awesome' => bt_bb_fa_icons(), 'S7' => bt_bb_s7_icons() );
		}

		require_once( dirname(__FILE__) . '/../../content_elements_misc/misc.php' );
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();

		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Button', 'bold-builder' ), 'description' => esc_html__( 'Button with custom link', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'text', 'type' => 'textfield', 'heading' => esc_html__( 'Text', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'icon', 'type' => 'iconpicker', 'heading' => esc_html__( 'Icon', 'bold-builder' ), 'value' => $icon_arr, 'preview' => true ),
				array( 'param_name' => 'icon_position', 'type' => 'dropdown', 'heading' => esc_html__( 'Icon Position', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Left', 'bold-builder' ) => 'left',
						esc_html__( 'Right', 'bold-builder' ) => 'right'
					)
				),
				array( 'param_name' => 'url', 'type' => 'textfield', 'heading' => esc_html__( 'URL', 'bold-builder' ), 'description' => esc_html__( 'Enter full or local URL (eg. https://www.bold-themes.com or /pages/about-us) or post slug (eg. about-us)', 'bold-builder' ), 'preview' => true ),
				array( 'param_name' => 'target', 'type' => 'dropdown', 'heading' => esc_html__( 'Target', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Self (open in same tab)', 'bold-builder' ) => '_self',
						esc_html__( 'Blank (open in new tab)', 'bold-builder' ) => '_blank',
					)
				),
				array( 'param_name' => 'align', 'type' => 'dropdown', 'heading' => esc_html__( 'Alignment', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Inherit', 'bold-builder' ) => 'inherit',
						esc_html__( 'Left', 'bold-builder' ) => 'left',
						esc_html__( 'Right', 'bold-builder' ) => 'right'
					)
				),
				array( 'param_name' => 'size', 'type' => 'dropdown', 'heading' => esc_html__( 'Size', 'bold-builder' ), 'preview' => true, 'group' => esc_html__( 'Design', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Small', 'bold-builder' ) => 'small',
						esc_html__( 'Medium', 'bold-builder' ) => 'medium',
						esc_html__( 'Normal', 'bold-builder' ) => 'normal',
						esc_html__( 'Large', 'bold-builder' ) => 'large'
					)
				),				
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => esc_html__( 'Color scheme', 'bold-builder' ), 'value' => $color_scheme_arr, 'preview' => true, 'group' => esc_html__( 'Design', 'bold-builder' ) ),
				array( 'param_name' => 'style', 'type' => 'dropdown', 'heading' => esc_html__( 'Style', 'bold-builder' ), 'preview' => true, 'group' => esc_html__( 'Design', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Outline', 'bold-builder' ) => 'outline',
						esc_html__( 'Filled', 'bold-builder' ) => 'filled',
						esc_html__( 'Clean', 'bold-builder' ) => 'clean'
					)
				),
				array( 'param_name' => 'shape', 'type' => 'dropdown', 'heading' => esc_html__( 'Shape', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Inherit', 'bold-builder' ) => 'inherit',
						esc_html__( 'Square', 'bold-builder' ) => 'square',
						esc_html__( 'Rounded', 'bold-builder' ) => 'rounded',
						esc_html__( 'Round', 'bold-builder' ) => 'round'
					)
				),
				array( 'param_name' => 'width', 'type' => 'dropdown', 'heading' => esc_html__( 'Width', 'bold-builder' ), 'group' => esc_html__( 'Design', 'bold-builder' ),
					'value' => array(
						esc_html__( 'Inline', 'bold-builder' ) => 'inline',
						esc_html__( 'Full', 'bold-builder' ) => 'full'				
					)
				)
			)
		) );
	} 
}