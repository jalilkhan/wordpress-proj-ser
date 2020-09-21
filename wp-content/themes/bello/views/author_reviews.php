<?php
$review		= boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_review' );
$review_arr	= explode( PHP_EOL, $review );
$review_summary = boldthemes_rwmb_meta( BoldThemesFramework::$pfx . '_review_summary' );

if ( $review != '' && $review_summary != '' ) { ?>
	<div class="btReviewHolder">
		<div class="bt_bb_row bt_bb_column_gap_30">
			<div class="bt_bb_column col-md-6 col-sm-12">
				<h5 class="btReviewHeadingOverview"><?php _e( 'Overview', 'bello' ); ?></h5>
				<?php
					foreach( $review_arr as $r ) {
						$r_arr = explode( ';', $r );
						if ( isset( $r_arr[1] ) ) {
							$rating = round( floatval( $r_arr[1] ) );
						} else {
							$rating = 0;
						}
						?>
						<div class="btReviewOverviewSegment">
							<span class="btReviewSegmentTitle"><?php echo wp_kses_post( $r_arr[0] ); ?></span>
							<div class="bt_bb_progress_bar bt_bb_align_inherit bt_bb_size_small bt_bb_style_line bt_bb_shape_rounded">
								<div class="bt_bb_progress_bar_bg"></div>
								<div class="bt_bb_progress_bar_inner animate" style="width:<?php echo wp_kses_post( $rating ); ?>%">
									<span class="bt_bb_progress_bar_text"><?php echo wp_kses_post( $rating ); ?>%</span>
								</div>
							</div>
						</div>

				<?php }
					$overall_score = boldthemes_get_post_rating();
				?>
			</div>
			<div class="bt_bb_column col-md-6 col-sm-12">
				<h5 class="btReviewHeadingSummary"><?php _e( 'Summary', 'bello' ); ?></h5>
				<div class="btSummary">
					<blockquote><?php echo wp_kses_post( $review_summary ); ?></blockquote>
				</div>
				<div class="btReviewScore">
					<div class="btReviewPercentage">
						<span class="btScoreTitle"><?php _e( 'Overall score', 'bello' ); ?></span>
						<strong><?php echo wp_kses_post( $overall_score ); ?>%</strong>
					</div>
					<div class="btReviewStars">
						<div class="star-rating">
							<span style="width:<?php echo wp_kses_post( $overall_score ); ?>%">
								<strong class="rating"><?php echo wp_kses_post( $overall_score ); ?></strong>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>