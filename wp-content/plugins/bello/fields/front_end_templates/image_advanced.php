<?php
if ( isset($field) ) {
	if ( isset($field['name']) && isset($field['value']) ) {

		$showinfo	= isset( $field['showinfo'] ) ? $field['showinfo'] : 0;
		$gallery_type	= isset( $field['gallery_type'] ) ? $field['gallery_type'] : 'grid';
		$_galleries	=  $field['value'];
		
		$galleries = array();
		foreach ($_galleries as $_gallery ) {
			$key = $_gallery['name'];
			if ( count($_gallery['value']) == 1 ){
					$galleries[ $key ] = $_gallery['value'][0];
			} else {
					$galleries[ $key ] = $_gallery['value'];
			}
		}
		$_html = '';

			if ( $gallery_type == 'carousel' ) {
					
						if ( shortcode_exists( 'bt_bb_slider' ) ) {
							$image_ids = array();

							if ( count($galleries) > 1 ){

								$_html .= '<div class="bt_bb_separator bt_bb_bottom_spacing_extra_small bt_bb_top_spacing_small bt_bb_border_style_solid"></div><div class="bt_bb_tabs bt_bb_color_scheme_3 bt_bb_style_simple bt_bb_shape_square">';
									$_html .= '<ul class="bt_bb_tabs_header">';
									foreach( $galleries as $gallery => $image ) {
										$_html .= '<li class="on"><span>' . $gallery . '</span></li>';
									}
									$_html .= '</ul>';
									$_html .= '<div class="bt_bb_tabs_tabs">';
											$i = 0;
											foreach( $galleries as $gallery => $image ) {	
												$class_on = $i == 0 ? " on" : "";
												$_html .= '<div class="bt_bb_tab_item' . $class_on . '">';
													$_html .= '<div class="bt_bb_tab_content">';
														$_html .= '<div class="bt_bb_text">';
															
															$_html .= '<div class="btArticleMedia"><div class="btMediaBox">';
																$auto_play = '';
																if ( $i == 0 ){
																	$auto_play = ' auto_play="3000"';
																}
                                                                                                                                
                                                                                                                                $images = is_array( $image ) ? implode( ',', $image ) : $image;
																$_html .= do_shortcode( '[bt_bb_slider el_id="listing_carousel_' . $i . '" images="' . $images . '" show_dots="bottom" height="auto"' . $auto_play . ']' );
															$_html .= '</div></div>';													
														
														$_html .= '</div>';
													$_html .= '</div>';
												$_html .= '</div>';	
												$i++;
											}
										
									$_html .= '</div>';
								$_html .= '</div>';

							}else{
									$i = 0;
									foreach( $galleries as $gallery => $image ) {	
										$class_on = $i == 0 ? " on" : "";
										$_html .= '<div class="bt_bb_tab_item' . $class_on . '">';
											$_html .= '<div class="bt_bb_tab_content">';
												$_html .= '<div class="bt_bb_text">';
													
													$_html .= '<div class="btArticleMedia"><div class="btMediaBox">';
														$auto_play = '';
														if ( $i == 0 ){
															$auto_play = ' auto_play="3000"';
														}
                                                                                                                $images = is_array( $image ) ? implode( ',', $image ) : $image;
														$_html .= do_shortcode( '[bt_bb_slider el_id="listing_carousel_' . $i . '" images="' . $images . '" show_dots="bottom" height="auto"' . $auto_play . ']' );
													$_html .= '</div></div>';													
												
												$_html .= '</div>';
											$_html .= '</div>';
										$_html .= '</div>';	
										$i++;
									}
							}
						}
					
				} else {
					
						if ( shortcode_exists( 'bt_bb_masonry_image_grid' ) ) {
							$image_ids = array();

							if ( count( $galleries ) > 1 ){
									$tabs_id = "tabs_" . rand(100,1000);

									$_html .= '<div class="bt_bb_separator bt_bb_bottom_spacing_extra_small bt_bb_top_spacing_small bt_bb_border_style_solid"></div><div class="bt_bb_tabs bt_bb_color_scheme_3 bt_bb_style_simple bt_bb_shape_square">';
										$_html .= '<ul class="bt_bb_tabs_header">';
										foreach( $galleries as $gallery => $image ) {
											$_html .= '<li class="on"><span>' . $gallery . '</span></li>';
										}
										$_html .= '</ul>';
										$_html .= '<div class="bt_bb_tabs_tabs" id="' . $tabs_id . '">';
											$i = 0;
											foreach( $galleries as $image ) {
												$prefix = 'listing';
												$class_on = $i == 0 ? " on" : "";
												$_html .= '<div class="bt_bb_tab_item' . $class_on . '">';
													$_html .= '<div class="bt_bb_tab_content">';
														$_html .= '<div class="bt_bb_text">';

															$_html .= '<div class="btArticleMedia"><div class="btMediaBox">';
                                                                                                                        $images = is_array( $image ) ? implode( ',', $image ) : $image;
															$_html .= do_shortcode( '[bt_bb_masonry_image_grid el_id="listing_grid_' . $i . '" images="' . $images . '" columns="' . boldthemes_get_option( $prefix . '_grid_gallery_columns' ) .  '" gap="' . boldthemes_get_option( $prefix . '_grid_gallery_gap' ) .  '"]' );
															$_html .= '</div></div>';

														$_html .= '</div>';
													$_html .= '</div>';
												$_html .= '</div>';	
												$i++;
											}

										$_html .= '</div>';
									$_html .= '</div>';
							}else{
									$i = 0;
									foreach( $galleries as $image ) {
										$prefix		= 'listing';
										$images		= count($image) == 1 ? $image : implode( ',', $image );

										if ( count($image) < boldthemes_get_option( $prefix . '_grid_gallery_columns' ) ){
											$columns	= count($image);
										}else{
											$columns	= boldthemes_get_option( $prefix . '_grid_gallery_columns' );
										}
										
										$class_on	= $i == 0 ? " on" : "";

										$_html .= '<div class="bt_bb_tab_item' . $class_on . '">';
											$_html .= '<div class="bt_bb_tab_content">';
												$_html .= '<div class="bt_bb_text">';
													$_html .= '<div class="btArticleMedia"><div class="btMediaBox">';
                                                                                                        $images = is_array( $image ) ? implode( ',', $image ) : $image;
													$_html .= do_shortcode( '[bt_bb_masonry_image_grid el_id="listing_grid_' . $i . '" images="' . $images . '" columns="' . $columns .  '" gap="' . 
														boldthemes_get_option( $prefix . '_grid_gallery_gap' ) .  '"]' );
													$_html .= '</div></div>';

												$_html .= '</div>';
											$_html .= '</div>';
										$_html .= '</div>';	
										$i++;
									}
							}							
						}
					
				}

		return $_html;

	}
}
?>