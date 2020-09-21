
<?php
$title			= BoldThemesFrameworkTemplate::$title;
$supertitle		= BoldThemesFrameworkTemplate::$supertitle;
$subtitle		= BoldThemesFrameworkTemplate::$subtitle;
$extra_class	= BoldThemesFrameworkTemplate::$extra_class;
$feat_image		= BoldThemesFrameworkTemplate::$feat_image;
$parallax		= BoldThemesFrameworkTemplate::$parallax;
$parallax_class = BoldThemesFrameworkTemplate::$parallax_class;
$dash			= BoldThemesFrameworkTemplate::$dash;
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
	<section class="bt_bb_section gutter bt_bb_vertical_align_bottom bt_bb_background_overlay_dark_solid bt_bb_color_scheme_1 btPageHeadline btListingHeadline">
		<div class="bt_bb_regular_image <?php echo esc_attr( $extra_class );?>" style="background-image:url(<?php echo esc_url_raw( $feat_image );?>);" data-parallax="<?php echo esc_attr( $parallax );?>" data-parallax-offset="-300"></div>
		<div class="bt_bb_port port">
			<div class="bt_bb_cell">
				<div class="bt_bb_cell_inner">
					<div class="bt_bb_row">
						<div class="bt_bb_column">
							<div class="bt_bb_column_content">
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
								<?php echo boldthemes_price_header_listing_single();?>
							</div><!-- /rowItemContent -->
						</div><!-- /rowItem -->
					</div><!-- /boldRow -->
				</div><!-- boldCellInner -->
			</div><!-- boldCell -->
		</div><!-- port -->
	</section>
	<?php

}