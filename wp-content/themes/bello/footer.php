		</div><!-- /boldthemes_content -->
<?php
if ( BoldThemesFramework::$has_sidebar ) {
	include( get_template_directory() . '/sidebar.php' );					
}

?> 
	</div><!-- /contentHolder -->
</div><!-- /contentWrap -->

<?php

if ( boldthemes_show_footer() ) {

		if ( boldthemes_get_option( 'footer_dark_skin' ) ) {
			echo '<div class="btDarkSkin btSiteFooter">';
		} else {
			echo '<div class="btSiteFooter">';
		}

		$page_slug = boldthemes_get_option( 'footer_page_slug' );
		if ( $page_slug != '' ) {
			$page_id = boldthemes_get_id_by_slug( $page_slug );
			if ( ! is_null( $page_id ) ) {
				$page = get_post( $page_id );
				$content = $page->post_content;
				$content = apply_filters( 'the_content', $content );
				$content = preg_replace( '/data-edit_url="(.*?)"/s', 'data-edit_url="' . get_edit_post_link( $page_id, '' ) . '"', $content );
				echo str_replace( ']]>', ']]&gt;', $content );
			}
		}

		$custom_text_html = '';
		$custom_text = boldthemes_get_option( 'custom_text' );
		if ( $custom_text != '' ) {
			$custom_text_html = '<p class="copyLine">' . $custom_text . '</p>';
		} 
		?>
		<footer>
		<?php if ( is_active_sidebar( 'footer_widgets' ) ) {
			echo '
			
				<section class="btSiteFooterWidgets gutter">
					<div class="port">
						<div class="bt_bb_row" id="boldSiteFooterWidgetsRow">';
						dynamic_sidebar( 'footer_widgets' );
				echo '	
						</div>
					</div>
				</section>
			';
		}

		?>
		<?php if ( $custom_text_html != '' || has_nav_menu( 'footer' )) { ?>
			<section class="gutter btSiteFooterCopyMenu">
				<div class="port">
					<div class="">
						<div class="btFooterCopy">
							<div class="bt_bb_column_content">
								<?php echo wp_kses_post( $custom_text_html ); ?>
							</div>
						</div><!-- /copy -->
						<div class="btFooterMenu">
							<div class="bt_bb_column_content">
								<?php boldthemes_nav_menu( false, 'footer'); ?>
							</div>
						</div>
					</div><!-- /boldRow -->
				</div><!-- /port -->
			</section>
		<?php } ?>
		</footer>
		
</div><!-- /btSiteFooter -->

<?php } ?>

</div><!-- /pageWrap -->

<?php
if ( class_exists( 'WooCommerce' ) ) { 
    boldthemes_get_my_account_form();
}
wp_footer();
?>
</body>
</html>