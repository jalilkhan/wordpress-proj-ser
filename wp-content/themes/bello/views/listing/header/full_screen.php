<?php
$title			= BoldThemesFrameworkTemplate::$title;
$supertitle		= BoldThemesFrameworkTemplate::$supertitle;
$subtitle		= BoldThemesFrameworkTemplate::$subtitle;
$extra_class	= BoldThemesFrameworkTemplate::$extra_class;
$feat_image		= BoldThemesFrameworkTemplate::$feat_image;
$parallax		= BoldThemesFrameworkTemplate::$parallax;
$parallax_class = BoldThemesFrameworkTemplate::$parallax_class;
$dash			= BoldThemesFrameworkTemplate::$dash;
$excerpt		= BoldThemesFrameworkTemplate::$excerpt;
$listing_price	= BoldThemesFrameworkTemplate::$listing_price; 

if ( $title != '' ) {
	$extra_class .= $feat_image ? ' bt_bb_background_image ' . apply_filters( 'boldthemes_header_headline_gradient', '' ) . $parallax_class  : ' ';

	$heading_breadcrumbs = boldthemes_breadcrumbs( false, $title, $subtitle );
	if ( !empty($heading_breadcrumbs) ){
		$supertitle = $heading_breadcrumbs['supertitle'];
	}
		
	$heading_ratings = boldthemes_rating_header_listing_single();
	if ( !empty($heading_ratings) ){
		$subtitle = $heading_ratings;
	}
	?>
	<section class="bt_bb_section gutter bt_bb_vertical_align_bottom bt_bb_full_screen bt_bb_background_overlay_dark_solid bt_bb_color_scheme_1 btPageHeadline btListingHeadline">
		<div class="bt_bb_regular_image <?php echo esc_attr( $extra_class );?>" style="background-image:url(<?php echo esc_url_raw( $feat_image );?>);" data-parallax="<?php echo esc_attr( $parallax );?>" data-parallax-offset="-100"></div>
		<div class="bt_bb_port port">
			<div class="bt_bb_cell">
				<div class="bt_bb_cell_inner">
					<div class="bt_bb_row">
						<div class="bt_bb_column">
							<div class="bt_bb_column_content">

								<div class="bt_bb_text">
									<blockquote><p><?php echo wp_kses_post($excerpt);?></p></blockquote>
								</div>
								<div class="bt_bb_separator bt_bb_top_spacing_small bt_bb_bottom_spacing_extra_small bt_bb_border_style_none"></div>
								<div class="bt_bb_button bt_bb_icon_position_left bt_bb_color_scheme_6 bt_bb_style_outline bt_bb_size_normal bt_bb_width_inline bt_bb_shape_inherit bt_bb_align_inherit">
									<a href="#bt-post-listing-item" target="_self" class="bt_bb_link">
										<span class="bt_bb_button_text"><?php echo esc_html__( 'Read more', 'bello' );?></span><span data-ico-fontawesome="" class="bt_bb_icon_holder"></span>
									</a>
								</div>
								<div class="bt_bb_separator bt_bb_top_spacing_extra_medium bt_bb_bottom_spacing_extra_large bt_bb_border_style_none"></div>

								<?php
									echo boldthemes_get_heading_html( 
										array(
											'superheadline' => $supertitle,
											'headline' => $title,
											'subheadline' => $subtitle,
											'html_tag' => "h1",
											'size' => apply_filters( 'boldthemes_header_headline_size', 'medium' ),
											'dash' => $dash,
											'el_style' => '',
											'el_class' => ''
										)
									);
								?>

								<?php 
                                                                    echo boldthemes_price_header_listing_single();
                                                                ?>
							</div><!-- /rowItemContent -->
						</div><!-- /rowItem -->
					</div><!-- /boldRow -->
				</div><!-- boldCellInner -->
			</div><!-- boldCell -->
		</div><!-- port -->
	</section>
	<?php
}