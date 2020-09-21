<?php
if ( post_password_required() ) {
	return;
}

if ( !is_singular( 'listing' ) ) {
	return;
}
?>
<div id="comments" class="btCommentsBox">

	<?php if ( have_comments() ) : ?>

		<h4>
                    <?php
                        printf( _n( 'One review', '%1$s reviews', get_comments_number(), 'bello' ), number_format_i18n( get_comments_number() ), get_the_title() );
                    ?>
		</h4>
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
                    <nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
                        <?php 
                        $prev_html = get_previous_comments_link( esc_html__( 'Older Comments', 'bello' ) );
                        $next_html = get_next_comments_link( esc_html__( 'Newer Comments', 'bello' ) );
                        if ( $prev_html != '' && $next_html != '' ) {
                                echo get_previous_comments_link( esc_html__( 'Older Comments', 'bello' ) );
                                echo '<span>|</span>';
                                echo get_next_comments_link( esc_html__( 'Newer Comments', 'bello' ) );
                        } else {
                                echo get_previous_comments_link( esc_html__( 'Older Comments', 'bello' ) );
                                echo get_next_comments_link( esc_html__( 'Newer Comments', 'bello' ) );
                        }
                        ?>
                    </nav><!-- #comment-nav-above -->
		<?php endif;?>

		<ul class="comments">
                    <?php
                        wp_list_comments( array(
                                'style'      => 'ul',
                                'short_ping' => true,
                                'reverse_top_level' => true,
                                'callback'   => 'boldthemes_theme_comment_listing'
                        ) );
                    ?>
		</ul><!-- .comments -->
                
                <?php if ( ! comments_open() ) : ?>
                    <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'bello' ); ?></p>
                <?php endif; ?>

		<?php if ( get_comments_number() > 5 ) : ?>
			<div class="show-more-comments">
				<div class="bt_bb_button bt_bb_icon_position_left bt_bb_color_scheme_6 bt_bb_style_filled bt_bb_size_normal bt_bb_width_inline bt_bb_shape_inherit bt_bb_align_inherit">
					<a href="#" target="_self" class="bt_bb_link" id="listing_single_comment_show_more_reviews">
						<span class="bt_bb_button_text"><?php echo  esc_html__( 'Show more reviews', 'bello' );?></span><span data-ico-fontawesome="&#xf021;" class="bt_bb_icon_holder"></span>
					</a>
				</div>
			</div>
		<?php endif; ?>

	<?php else : ?>
                <?php if ( comments_open() ) : ?>        
                    <p class="woocommerce-noreviews"><?php esc_html_e( 'There are no comments yet.', 'bello' ); ?></p>
                <?php endif; ?>
	<?php endif;  ?>
                
	<a id="btCommentsForm"></a>
	<?php 
	
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
	
		$fields =  array(
			'author' =>
				'<div class="pcItem halfWidth"><label for="author"></label>
				<p><input id="author" name="author" type="text" tabindex="2" placeholder="' . esc_attr__( 'Your name *', 'bello' ) . '" value="' . esc_attr( $commenter['comment_author'] ) .
				'" ' . $aria_req . ' /></p></div>',

			'email' =>
				'<div class="pcItem halfWidth"><label for="email"></label>
				<p><input id="email" name="email" type="text" tabindex="3" placeholder="' . esc_attr__( 'Your e-mail address *', 'bello' ) . '" value="' . esc_attr(  $commenter['comment_author_email'] ) .
				'" ' . $aria_req . ' /></p></div>',

			'comment_field' =>  
				'<div class="pcItem btComment"><label for="comment"></label><p><textarea id="comment" name="comment" tabindex="4" cols="30" rows="8" placeholder="' . esc_attr__( 'Your review *', 'bello' ) . '" aria-required="true">' .'</textarea></p></div>'

		);
		$args = array(
		  'id_form'           => 'commentform',
		  'class_form'		  => 'comment-form',
		  'id_submit'         => 'submit',
		  'title_reply'       => esc_html__( 'Submit your review', 'bello' ),
		  'title_reply_to'    => esc_html__( 'Leave a review to %s', 'bello' ),
		  'cancel_reply_link' => esc_html__( 'Cancel review', 'bello' ),
		  'label_submit'      => esc_html__( 'Submit review', 'bello' ),
		  
		  'submit_button' => '<span class="pcItem"><button type="submit" value="' . esc_attr__( 'Post Comment', 'bello' ) . '" id="btSubmit" class="btCommentSubmit" name="submit" data-ico-fa="&#xf1d8;"><span class="btnInnerText">' . esc_html__( 'Submit review', 'bello' ) . '</span></button></span>',

		  'must_log_in' => '<p class="must-log-in">' .
			sprintf(
				wp_kses( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'bello' ), array( 'a' => array( 'href' => array() ) ) ),
				wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
			) . '</p>',

		  'logged_in_as' => '<p class="logged-in-as">' .
			sprintf(
				wp_kses( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="%4$s">%5$s</a>', 'bello' ), array( 'a' => array( 'href' => array() ) ) ),
				admin_url( 'profile.php' ),
				$user_identity,
				wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ),
                                esc_attr__( 'Log out of this account', 'bello' ),
                                esc_html__( 'Log out?', 'bello' )
			) . '</p>',

		  'comment_notes_before' => '<p class="comment-notes">' .
			esc_html__( 'Your email address will not be published.', 'bello' ) . ' ' . ( $req ? esc_html__( 'Required fields are marked *', 'bello' ) : '' ) .
			'</p>',

		  'fields' => apply_filters( 'comment_form_default_fields', $fields ),
		  
		);

		
		comment_form( $args );

	?>

</div><!-- #comments -->