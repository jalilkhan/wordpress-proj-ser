<?php
//emple: https://crowsnestbarbershop.resurva.com
if ( isset($field) ) {
	if ( isset($field['name']) && isset($field['value']) ) {
		$resurva = boldthemes_rwmb_meta('boldthemes_theme_listing-resurva');
		if ( $resurva ) {
			ob_start();
			?>
				<iframe src="<?php echo esc_url( $resurva ); ?>/book?embedded=true" name="resurva-frame" frameborder="0" width="450" height="450" style="max-width:100%"></iframe>
			<?php
			$content = ob_get_clean();
			?>
			<div class="bt_bb_button bt_bb_icon_position_left bt_bb_color_scheme_10 bt_bb_style_filled bt_bb_size_normal bt_bb_width_full bt_bb_shape_inherit bt_bb_align_inherit">
				<a href="#resurva_popup" class="bt_bb_link_resurva bt_bb_link"><span class="bt_bb_button_text"><?php _e( 'Click here to book', 'bt_plugin' ) ?></span><span data-ico-fontawesome="" class="bt_bb_icon_holder"></span></a>
			</div>
			
			<div class="<?php echo $field['slug'] ?> <?php echo $field['group'];?> mfp-hide" id="resurva_popup">
				<?php if ( $title == '' ) : ?>
				<h4><span><?php _e( 'Book with Resurva', 'bt_plugin' ) ?></span></h4>
				<?php endif; ?>
				<div class="btResurvaReservation">
					<?php echo $content;?>
				</div>
			</div>
			<?php
		}
	}
}