<?php

class bt_bb_leaflet_map extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
                        'tiles_url'             => '',
			'zoom'                  => '',
			'height'                => '',
			'center_map'            => '',
                        'custom_style'          => '',
                        'scroll_wheel'          => '',
                        'zoom_control'          => ''
		) ), $atts, $this->shortcode ) );
                
                // enqueue leaflet framework js and css 
                $leaflet_framework_path = get_template_directory() . '/bold-page-builder/content_elements/bt_bb_leaflet_map/leafletmap/';
                require_once( $leaflet_framework_path . 'include_map.php' );
                // enqueue leaflet bb element js  
                $leaflet_bb_path = get_template_directory_uri() . '/bold-page-builder/content_elements/bt_bb_leaflet_map/';
                wp_enqueue_script( 'bt_bb_leaflet_map_js', $leaflet_bb_path . 'js/bt_bb_leaflet_map.js' ); 
                
                if ( $custom_style == '' ) {
			$custom_style = 1;
		}
                if ( $zoom == '' ) {
			$zoom = 14;
		}
                
                $scroll_wheel = $scroll_wheel != '' ? 1 : 0;
                $zoom_control = $zoom_control != '' ? 1 : 0;
                
                $custom_osm_map_style   = ''; 
               
                $class_master = 'bt_bb_map';
                
                $class      = array( $this->shortcode );
                
                $class_map  = array( $class_master );
                
                if ( $el_class != '' ) {
			$class[]        = $el_class;
		}

		if ( $center_map == 'yes_no_overlay' ) {
			$class[]        = $this->shortcode . '_no_overlay';
                        $class_map[]    = $class_master . '_no_overlay';
		}

		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . esc_attr($el_id) . '"';
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr($el_style) . '"';
		}
                
		$style_height = '';
		if ( $height != '' ) {
			$style_height = ' ' . 'style="height:' . $height . 'px"';
		}
	
		$map_id = uniqid( 'map_canvas' );               
                $content_html = wptexturize( do_shortcode( $content ) );               
		$locations = substr_count( $content_html, '"bt_bb_leaflet_map_location' );
		$locations_without_content = substr_count( $content_html, 'bt_bb_leaflet_map_location_without_content' );
              
		if ( $content != '' && $locations != $locations_without_content ) {
                        $content = '<span class="' . esc_attr($this->shortcode) . '_content_toggler ' . esc_attr($class_master) . '_content_toggler"></span>'
                                . '<div class="' . esc_attr($this->shortcode) . '_content ' . esc_attr($class_master) . '_content">'
                                    . '<div class="' . esc_attr($this->shortcode) . '_content_wrapper ' . esc_attr($class_master) . '_content_wrapper">' . $content_html . '</div>'
                                . '</div>';
                        $class[] = $this->shortcode . '_with_content';
                        $class_map[] = $class_master . '_with_content';
		} else {
                       $content = $content_html;
		}
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );
                $class_map = apply_filters( $this->shortcode . '_class_map', $class_map, $atts );

                $output = '<div class="' . esc_attr($this->shortcode) . '_map ' . esc_attr($class_master) . '_map" id="' . esc_attr($map_id) . '"' . $style_height . '></div>';

		$output .= $content;

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . ' ' . implode( ' ', $class_map ) . '"' . $style_attr . ' data-center="' . esc_attr($center_map) . '">' . $output . '</div>';

		$output .= '<script type="text/javascript">';
			$output .= 'var bt_bb_leaflet_' . $map_id . '_init_finished = false; ';
			$output .= 'document.addEventListener("readystatechange", function() { ';
				$output .= 'if ( !bt_bb_leaflet_' . $map_id . '_init_finished && ( document.readyState === "interactive" || document.readyState === "complete" ) ) { ';
					$output .= 'if ( typeof( bt_bb_leaflet_init ) !== typeof(Function) ) { return false; }';
                                        $output .= 'bt_bb_leaflet_init( "' . $map_id . '", ' . $zoom . ', ' . $custom_style . ',' . $scroll_wheel . ',"' . $tiles_url . '",' . $zoom_control . ' );';
					$output .= 'bt_bb_leaflet_' . $map_id . '_init_finished = true; ';
				$output .= '};';
			$output .= '}, false);';
		$output .= '</script>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;
                
        }
        
       function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Leaflet Maps', 'bello' ), 'description' => esc_html__( 'Leaflet Maps with custom content', 'bello' ), 'container' => 'vertical', 'accept' => array( 'bt_bb_openmap_location' => true ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'tiles_url', 'type' => 'textfield', 'heading' => esc_html__( 'Tiles URL', 'bello' ), 'description' => esc_html__( 'Map tiles url. Ex. https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 'bello' ), 'preview' => true ),
				array( 'param_name' => 'zoom', 'type' => 'textfield', 'heading' => esc_html__( 'Zoom (e.g. 14)', 'bello' ), 'preview' => true ),
				array( 'param_name' => 'height', 'type' => 'textfield', 'heading' => esc_html__( 'Height (e.g. 250px)', 'bello' ), 'description' => esc_html__( 'Used when there is no content', 'bello' ) ),
				array( 'param_name' => 'custom_style', 'type' => 'dropdown', 'heading' => esc_html__( 'Leaflet Map Style', 'bello' ), 
					'value' => array(
						esc_html__( 'Style 1 - Mapnik OSM', 'bello' ) => '1',
						esc_html__( 'Style 2 - Wikimedia', 'bello' ) => '2',
						esc_html__( 'Style 3 - OSM Hot', 'bello' ) => '3',
						esc_html__( 'Style 4 - Stamen Watercolor', 'bello' ) => '4',
						esc_html__( 'Style 5 - Stamen Terrain', 'bello' ) => '5',
						esc_html__( 'Style 6 - Stamen Toner', 'bello' ) => '6',
						esc_html__( 'Style 7 - Carto Dark', 'bello' ) => '7',
						esc_html__( 'Style 8 - Carto Light', 'bello' ) => '8'
					)
				),
				array( 'param_name' => 'center_map', 'type' => 'dropdown', 'heading' => esc_html__( 'Center map', 'bello' ),
					'value' => array(
						esc_html__( 'No', 'bello' ) => 'no',
						esc_html__( 'Yes', 'bello' ) => 'yes',
						esc_html__( 'Yes (without overlay initially)', 'bello' ) => 'yes_no_overlay'
					)
				),
				array( 'param_name' => 'scroll_wheel',  'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'bello' ) => 'scroll_wheel' ), 'heading' => esc_html__( 'Enable Scroll Wheel Zoom on Map', 'bello' ), 'preview' => true
				),
				array( 'param_name' => 'zoom_control',  'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'bello' ) => 'zoom_control' ), 'heading' => esc_html__( 'Enable Zoom Control on Map', 'bello' ), 'preview' => true
				),
				
			)
		) );
	}
}

