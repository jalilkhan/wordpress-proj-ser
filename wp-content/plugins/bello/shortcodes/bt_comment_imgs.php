<?php

// [bt_comment_imgs]
function bt_comment_imgs_func( $atts ) {
	extract( shortcode_atts( array(
            'images'      => '',
            'columns'     => '',
            'format'      => '',
            'gap'         => '',
            'no_lightbox' => '',
            'images_size'    => 'boldthemes_small_rectangle',
            'images_number'  => '4'  
	), $atts, 'bt_comment_imgs_func' ) );
        
        $shortcode  = 'bt_bb_masonry_image_grid';
        $prefix     = 'bt_bb_';
        $el_style   = '';
        $el_id      = '';
        $el_class   = '';
        
        wp_enqueue_script( 'jquery-masonry' );
        wp_enqueue_script( 
                'bt_bb_comment_image_grid',
                plugin_dir_url( __FILE__ ) . 'js/bt_bb_masonry_image_grid.js',
                array( 'jquery' )
        );

        $class = array( $shortcode, 'bt_bb_grid_container' );
        
		if ( $el_class != '' ) {
                $class[] = $el_class;
        }	

        $id_attr = '';
        if ( $el_id != '' ) {
                $id_attr = ' ' . 'id="' . $el_id . '"';
        }

        $style_attr = '';
        if ( $el_style != '' ) {
                $style_attr = ' ' . 'style="' . $el_style . '"';
        }

        if ( $columns != '' ) {
                $class[] = $prefix . 'columns' . '_' . $columns;
        }

        if ( $gap != '' ) {
                $class[] = $prefix . 'gap' . '_' . $gap;
        }

        if ( $no_lightbox == 'no_lightbox' ) {
                $class[] = $prefix . 'no_lightbox';
        }

        $class = apply_filters( $shortcode . '_class', $class, $atts );

        $output = '';

        $output .= '<div class="bt_bb_grid_sizer"></div>';

        $images_arr = explode( ',', $images );
        $format_arr = explode( ',', $format );

        $n = 0;
        
        foreach( $images_arr as $id ) {
                $img = wp_get_attachment_image_src( $id, $images_size );
                $img_src = isset($img[0]) ? $img[0] : '';
                $img_full = wp_get_attachment_image_src( $id, 'full' );
                $img_src_full = isset($img_full[0]) ? $img_full[0] : '';			
                $image_post = get_post( $id );
                if ( isset( $format_arr[ $n ] ) ) {
                        $tile_format = 'bt_bb_tile_format';
                        if ( $format_arr[ $n ] == '21' ) {
                                $tile_format .= "_" . $format_arr[ $n ];
                        } else {
                                $tile_format .= '11';
                        }
                }
                $data_hw = '';
                if ( $img[1] > 0 ) {
                        $data_hw = $img[2] / $img[1];
                }
                $data_title = '';
                if ( is_object( $image_post ) ) {
                        $data_title = $image_post->post_title;
                }
                
                $hide_item_style = '';
                if ( $n > $images_number - 1  ){
                    $hide_item_style = ' style="display:none;"';
                }
                
                $output .= '<div' . $hide_item_style . ' class="bt_bb_grid_item ' . $tile_format . '" data-hw="' . $data_hw . '" data-src="' . $img_src . '" data-src-full="' . $img_src_full . '" data-title="' . $data_title . '">'
                   . '<div class="bt_bb_grid_item_inner" data-hw="' . $data_hw . '" ><div class="bt_bb_grid_item_inner_image"></div><div class="bt_bb_grid_item_inner_content"></div></div></div>';
                $n++;
        }

        $output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . ' data-columns="' . $columns . '"><div class="bt_bb_masonry_post_image_content" data-columns="' . $columns . '">' . $output . '</div></div>';

        $output = apply_filters( 'bt_bb_general_output', $output, $atts );
        $output = apply_filters( $shortcode . '_output', $output, $atts );

        return $output;
        
}

add_shortcode( 'bt_comment_imgs', 'bt_comment_imgs_func' );
