<?php

if ( ! class_exists( 'BB_Instagram' ) ) {
	
	// INSTAGRAM	
	
	class BB_Instagram extends WP_Widget {
		
		private $feed_id;
		private $error_cache_time = 15;
		private $min_cache_time = 15;
		private $default_cache_time = 30;
		private $trans_prefix = 'bt_bb_insta_';
	
		function __construct() {
			parent::__construct(
				'bt_bb_instagram', // Base ID
				esc_html__( 'BB Instagram', 'bold-builder' ), // Name
				array( 'description' => esc_html__( 'Instagram photos.', 'bold-builder' ) ) // Args
			);
		}
		
		public function get_cache_id( $hashtag, $access_token, $number ) {
			return  $hashtag . '_' . $number . '_' . trim( $access_token );
		}
		
		public function get_trans_name( $hashtag, $access_token, $number ) {
			// return  $this->trans_prefix . $this->get_cache_id( $hashtag, $access_token, $number );
			return  $this->trans_prefix . $hashtag . '_' . $number . '_' . trim( $access_token );
		}

		public function widget( $args, $instance ) {

			$number = intval( trim( $instance['number'] ) );
			
			if ( $number < 1 ) {
				$number = 4;
			} else if ( $number > 30 ) {
				$number = 30;
			}
			
			$hashtag = trim( $instance['hashtag'] );
			
			$trans_name = $this->get_trans_name( $hashtag, trim( $instance['access_token'] ), $number );

			$cache = $this->min_cache_time;
			
			if ( isset( $instance['cache'] ) ) { // back-compat
				$cache = intval( trim( $instance['cache'] ) );
			}
			
			if ( $cache < $this->min_cache_time ) {
				$cache = $this->min_cache_time;
			} else if ( $cache > 24*60 ) {
				$cache = 24*60;
			}
			
			// uncomment this for testing
			// $cache = 0;
					
			if ( $cache == 0 ) {
				delete_transient( $trans_name );
			}

			$instance['access_token'] = isset( $instance['access_token'] ) ? $instance['access_token'] : '';

			$access_token = trim( $instance['access_token'] );

			if ( $access_token == '' ) {
				return;
			}

			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}
			
			// var_dump( get_transient( $trans_name ) );
			
			if ( false == ( $cache_data = get_transient( $trans_name ) ) ) {
				
				$no_error = false;
			
				$output = '<div class="btInstaWrap">';
					$output .= '<div class="btInstaGrid">';
		 
						// if your app is not approved - always use 'self'
						$ig_user_id = 'self';
						 
						// Instagram API connection
						$remote_wp = wp_remote_get( 'https://api.instagram.com/v1/users/' . $ig_user_id . '/media/recent/?access_token=' . $access_token );
						 
						// Instagram response is JSON encoded, let's convert it to an object
						if ( ! is_wp_error( $remote_wp ) ) {
							$instagram_response = json_decode( $remote_wp['body'] );
							// 200 OK
							if ( $remote_wp['response']['code'] == 200 ) {

								// $instagram_response->data object contains all the user information
								
								$n = 0;
								foreach( $instagram_response->data as $item ) {
									if ( $hashtag != '' && ! strpos( $item->caption->text, $hashtag ) ) {
										continue;
									}
									$output .= '<span><a href="' . esc_url_raw( $item->link ) . '"><img src="' . esc_url_raw( $item->images->standard_resolution->url ) . '" alt="' . esc_url_raw( $item->link ) . '"></a></span>';
									$n++;
									if ( $n == $number ) {
										break;
									}
								}
								
								$no_error = true;

							// Example of error handling, try to set incorrect user ID to catch an error
							// 400 Bad Request
							} else {
								// var_dump( $instagram_response );
								if ( isset( $instagram_response->error_message ) ){
									echo $instagram_response->error_message;
								} else {
									$body = json_decode( $remote_wp['body']);
									if ( isset( $body->meta->error_message ) ){
										echo  $body->meta->error_message;
									} else {
										echo esc_html__( 'Instagram error occured.', 'bold-builder' );
									}
								}

								//echo $instagram_response->error_message;

								// no_error set to true to avoid error calls during error_cache_time
								// cache set to error_cache_time 
								$no_error = true;
								$cache = $this->error_cache_time;
							}
						}
					
					$output .= '</div>';
				$output .= '</div>';
				
				if ( $no_error && $cache > 0 ) {
					set_transient( $trans_name, $output, $cache * 60 );
				}
				
				echo $output;
				
			} else {
				
				echo $cache_data;
				
			}
			
			echo $args['after_widget'];
			
		}
	
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Instagram', 'bold-builder' );
			$number = ! empty( $instance['number'] ) ? $instance['number'] : '4';
			$hashtag = ! empty( $instance['hashtag'] ) ? $instance['hashtag'] : '';
			$cache = ! empty( $instance['cache'] ) ? $instance['cache'] : $this->default_cache_time;
			$access_token = ! empty( $instance['access_token'] ) ? $instance['access_token'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of photos:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>">			
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'hashtag' ) ); ?>"><?php _e( 'Hashtag:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'hashtag' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hashtag' ) ); ?>" type="text" value="<?php echo esc_attr( $hashtag ); ?>">			
			</p>			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'cache' ) ); ?>"><?php _e( 'Cache (minutes):', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cache' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cache' ) ); ?>" type="text" value="<?php echo esc_attr( $cache ); ?>">			
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>"><?php _e( 'Access token:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'access_token' ) ); ?>" type="text" value="<?php echo esc_attr( $access_token ); ?>">			
			</p>
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';
			$instance['hashtag'] = ( ! empty( $new_instance['hashtag'] ) ) ? strip_tags( $new_instance['hashtag'] ) : '';
			$instance['cache'] = ( ! empty( $new_instance['cache'] ) ) ? strip_tags( $new_instance['cache'] ) : $this->default_cache_time;
			$instance['access_token'] = ( ! empty( $new_instance['access_token'] ) ) ? strip_tags( $new_instance['access_token'] ) : '';
			
			$new_trans_name = $this->get_trans_name( $instance['hashtag'], $instance['access_token'], $instance['number'] );
			
			$old_trans_name = $this->get_trans_name( $old_instance['hashtag'], $old_instance['access_token'], $old_instance['number'] );
			
			delete_transient( $old_trans_name );
			delete_transient( $new_trans_name );	
			
			return $instance;
		}
	}

}