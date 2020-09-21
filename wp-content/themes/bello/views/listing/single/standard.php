<?php
$share_html = boldthemes_get_share_html( get_permalink(), 'listing', 'xsmall' );

BoldThemesFrameworkTemplate::$currency_symbol = boldthemes_get_option( 'listing_search_currency_symbol' ) ? boldthemes_get_option( 'listing_search_currency_symbol' ) : '';   


/* nearby locations to single listing or from customizer default */
$ajax_lat = 0;
$ajax_lng = 0;
$location_position = boldthemes_rwmb_meta('boldthemes_theme_listing-location_position') != '' ? explode(",", boldthemes_rwmb_meta('boldthemes_theme_listing-location_position')) : array();
if ( !empty($location_position) ) {
    $ajax_lat = $location_position[0];	
    $ajax_lng = $location_position[1];
}else{
    $ajax_lat = boldthemes_get_option( 'listing_search_distance_lat' )  != '' ? boldthemes_get_option( 'listing_search_distance_lat' ) : '0';
    $ajax_lng = boldthemes_get_option( 'listing_search_distance_lng' )  != '' ? boldthemes_get_option( 'listing_search_distance_lng' ) : '0';
}

$arr_categories = array();
$categories = get_the_terms( get_the_ID(), 'listing-category' );
if ( !empty($categories) ) {
foreach ($categories as $category){
        array_push( $arr_categories, $category->slug);
}
}

wp_register_script( 'bello_listing_single_listing_js', get_template_directory_uri() . '/views/listing/js/single_listing.js' );
wp_localize_script( 'bello_listing_single_listing_js', 'ajax_object', array( 
        'ajax_url'	=> admin_url( 'admin-ajax.php' ), 
        'ajax_action'	=> 'bt_get_listing_nearby_action',
        'listing_id'	=> get_the_ID(),
        'ajax_lat'	=> $ajax_lat,	
        'ajax_lng'	=> $ajax_lng,
        'ajax_unit'	=> boldthemes_get_option( 'listing_search_distance_unit' ) != '' ? boldthemes_get_option( 'listing_search_distance_unit' ) : 'mi',
        'categories'	=> $arr_categories,
		'ajax_label_m'         => esc_html__( 'm', 'bello' ),
		'ajax_label_km'         => esc_html__( 'km', 'bello' ),
		'ajax_label_mi'         => esc_html__( 'mi', 'bello' ),
        ) 
);
wp_enqueue_script( 'bello_listing_single_listing_js' );
?>

<article id="bt-post-listing-item" class="btPostListingItem btListingItemStandard gutter">
	<div class="port">
		<div class="btPostContentHolder">

			<?php if( boldthemes_get_option( 'hide_headline' ) ) { 
				$heading_ratings = boldthemes_rating_header_listing_single();
				echo '<div class="btArticleHeadline">';
					echo boldthemes_get_heading_html( 
						array(
							'superheadline' => BoldThemesFrameworkTemplate::$categories_html,
							'headline' => get_the_title(),
							'subheadline' => $heading_ratings,
							'size' => 'large',
							'html_tag' => 'h1',
							'dash' => BoldThemesFrameworkTemplate::$dash
						)
					);
					echo boldthemes_price_header_listing_single();
				echo '</div><!-- /btArticleHeadline -->';
			 } ?>

			<div class="btArticleContent">
				<div class="bt_bb_wrapper"> 
					<?php if ( has_excerpt()) { ?>
						<div class="btSingleListingItem">
								<?php the_excerpt();?>
						</div> 
					<?php } ?>
                                    
					<?php
                                        
					if ( BoldThemesFrameworkTemplate::$media_video_html != '' ) {
						echo BoldThemesFrameworkTemplate::$media_video_html;
					}else{
						if ( BoldThemesFrameworkTemplate::$media_image_html != '' ) {
							echo BoldThemesFrameworkTemplate::$media_image_html;
						}
					}
					
					$listing_single_author_review = boldthemes_get_option( 'listing_single_author_review' );
					if ( $listing_single_author_review ) {
						get_template_part( 'views/author_reviews' );
					}
					?>

					<div class="btSingleListingDescription">
						<?php the_content(); ?>
					</div>
                                    
                                        <?php                                       
                                        if ( BoldThemesFrameworkTemplate::$media_audio_html != '' ) {
						echo BoldThemesFrameworkTemplate::$media_audio_html;
					}
                                        ?>
                                    
					<?php
					if ( BoldThemesFrameworkTemplate::$media_image_html != '' ) {
						if ( BoldThemesFrameworkTemplate::$media_video_html != '' ) {
							echo BoldThemesFrameworkTemplate::$media_image_html;
						}
					}
                                        
                                       
					
					if ( BoldThemesFrameworkTemplate::$listing_faq != '' ) { ?>
						<div class="btSingleListingFaq">
							<h6><?php esc_html_e( 'FAQ', 'bello' ); ?></h6>
							<?php echo BoldThemesFrameworkTemplate::$listing_faq;?>
						</div>
					<?php } ?>
                                    
						<?php  
						  if ( function_exists( 'bello_get_listing_groups_more_widgets_html' ) ) { 
									  bello_get_listing_groups_more_widgets_html();
						  } 
						  ?>
                                    
				</div>
                            
                              
			</div>
			
			<?php if ( is_active_sidebar( 'listing_banner' ) ) { ?>				
				<section class="boldSection btSinglePostBanner gutter bottomMediumSpaced">
					<div class="port">
						<div class="boldRow boldRow btTextLeft" id="boldSiteFooterWidgetsRow">
						<?php dynamic_sidebar( 'listing_banner' );?>							
						</div>
					</div>
				</section>
			<?php } ?>

			<?php
                       
			if ( function_exists( 'boldthemes_amenities_html' ) ) {
				boldthemes_amenities_html();
			}
			?>
			
			<div class="btSingleListingShare">
				<?php if ( BoldThemesFrameworkTemplate::$tags_html != '' ) { ?>
					<h6><?php esc_html_e( 'Tags', 'bello' ); ?></h6>
				<?php } ?>
				<div class="btArticleShareEtc">
						<div class="btTagsColumn">
							<?php echo wp_kses_post( BoldThemesFrameworkTemplate::$tags_html ); ?>
						</div>
					<?php echo '<div class="btShareColumn">' . wp_kses_post( $share_html ) . '</div><!-- /btShareColumn -->';?>
				</div>
			</div>

		</div>
	</div>
</article>

<section class="btComments btReviews gutter">
	<div class="port">
		<div class="btCommentsContent">
			<?php get_template_part( 'views/comments_listing' ); ?>
		</div>
	</div>
</section>

<div id="nearby_locations_container"></div>
					


