<?php
/**
 * Settings
 */
function bt_favorites_admin_init() {
    register_setting( 'bt_favorites_settings', 'bt_favorites_settings' );
}
add_action( 'admin_init', 'bt_favorites_admin_init' );

/**
 * Settings menu
 */
function bt_favorites_menu() {
	add_options_page( __( 'Bold Favorites Settings', 'bt_favorites' ), __( 'Bold Favorites', 'bt_favorites' ), 'manage_options', 'bt_favorites_settings', 'bt_favorites_settings_callback' );
}
add_action( 'admin_menu', 'bt_favorites_menu' );

/**
 * Settings bt_favorites_post_types, bt_favorites_show callback
 */
function bt_favorites_settings_action( $new_value, $old_value ) {
    if( isset( $_POST ) ) {
        $bt_favorites_settings_arr = array();        
        foreach( $_POST as $key => $_value ) {
            if ( $key == 'bt_favorites_post_types' ) {
                foreach ( $new_value as &$value){
                    $new_value["bt_favorites_post_types"] = is_array($_value) ? implode("," , $_value) : $_value;
                }
                unset($value);                
            } 
            
            if ( $key == 'bt_favorites_show' ) {
                foreach ( $new_value as &$value){
                    $new_value["bt_favorites_show"] = is_array($_value) ? implode("," , $_value) : $_value;
                }
                unset($value);
            }
        }
        return $new_value;
    }    
}
function bt_favorites_settings_init() {
    add_filter( 'pre_update_option_bt_favorites_settings', 'bt_favorites_settings_action', 10, 2 );
}
add_action('init', 'bt_favorites_settings_init');

/**
 * Settings page callback
 */
function bt_favorites_settings_callback() {
	$options = get_option( 'bt_favorites_settings' );
	?>
		<div class="wrap">
			<h2><?php _e( 'Bold Favorites Settings', 'bt_favorites' ); ?></h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'bt_favorites_settings' ); ?>
				<table class="form-table">
					<tbody>	
                        <tr>
                            <th scope="row"><?php _e( 'Enable Bold Favorites for', 'bt_favorites' ); ?></th>
							<td>
								<fieldset>
									<legend class="screen-reader-text"><span><?php _e( 'Enable Favorites for', 'bt_favorites' ); ?></span>
								</legend>
									<?php  
										$args = array('public' => 1);
										$post_types = get_post_types( $args, 'objects' );                                                                    
										
										$favorites_post_types = array();
										if (  $options && array_key_exists( 'bt_favorites_post_types', $options ) && $options[ 'bt_favorites_post_types' ] != '') {
											 $favorites_post_types = explode("," , $options[ 'bt_favorites_post_types'] );
										}
										
										$bt_favorites_exclude_pages = '';
										if (  $options && array_key_exists( 'bt_favorites_exclude_pages', $options ) && $options[ 'bt_favorites_exclude_pages' ] != '') {
											$bt_favorites_exclude_pages = $options[ 'bt_favorites_exclude_pages' ];
										}
										foreach ( $post_types  as $post_type ) {
										   $checked = '';
										   if ( in_array( $post_type->name, $favorites_post_types) ){
											   $checked = ' ' . 'checked="checked"';
										   }
										   echo '<p><label><input id="bt_favorites_post_types_'.$post_type->name.'" name="bt_favorites_post_types[]" type="checkbox" value="'.trim($post_type->name).'"' . $checked . '><span>' . __( $post_type->label, 'bt_favorites' ) . '</span></label></p>';
										   if ( $post_type->name == 'page' ){
											   echo '<p><label>' . esc_html( 'Excluded pages ', 'bt_favorites' ) . '<input type="text" name="bt_favorites_settings[bt_favorites_exclude_pages]" style="width:200px !important;" value="'. $bt_favorites_exclude_pages.'"> * </label></p>';
											   ?>
											   
											   <?php
										   }
										}
									?> 
									<p class="description">
									 * <?php esc_html_e( 'You may exclude some pages by ids, ex. 2026,2793,8', 'bt_favorites' ); ?>
									</p>									
								</fieldset>
							</td>					
						</tr>                                                  
						<tr>
						<th scope="row"><?php _e( 'Insert Bold Favorites button', 'bt_favorites' ); ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span><?php _e( 'Favorites Content Insert', 'bt_favorites' ); ?></span>
								</legend>
								<fieldset>
								<?php 
									$show_arrs = array(
										array(
											'label'	=> 	'Before content',
											'name' 	=> 	'before'
										),
										array(
											'label'	=>	'After content',
											'name'	=>	'after'
										)
									);

									$bt_favorites_show = array();
									if (  $options && array_key_exists( 'bt_favorites_show', $options ) && $options[ 'bt_favorites_show' ] != '') {
										 $bt_favorites_show = explode("," , $options[ 'bt_favorites_show'] );
									}
									foreach ( $show_arrs  as $show_arr ) {
										$checked = '';
										if ( in_array( $show_arr['name'], $bt_favorites_show) ){
											$checked = ' ' . 'checked="checked"';
										}
										echo '<p><label>';
											echo '<input id="bt_favorites_show_'.$show_arr['name'].'" name="bt_favorites_show[]" type="checkbox" value="'.trim($show_arr['name']).'"' . $checked . '>' . __( $show_arr['label'], 'bt_favorites' );
										echo '</label></p>';
										
										
									}
									
								?>
								<p class="description">
									<?php esc_html_e( 'Favorite buttons are inserted before or/and after the content using the_content filter.', 'bt_favorites' );?>
								</p>                                                       
								</fieldset>
						</td>					
						</tr>
						<tr>
							 <th scope="row"><?php _e( 'Before/After Content Button', 'bt_favorites' ); ?></th>
							<td> 
								<table>
									<?php
									$bt_favorites_button_text = '';
									if (  $options && array_key_exists( 'bt_favorites_button_text', $options ) && $options[ 'bt_favorites_button_text' ] != '') {
										$bt_favorites_button_text = $options[ 'bt_favorites_button_text' ];
									}

									$bt_favorites_button_text_added = '';
									if (  $options && array_key_exists( 'bt_favorites_button_text_added', $options ) && $options[ 'bt_favorites_button_text_added' ] != '') {
										$bt_favorites_button_text_added = $options[ 'bt_favorites_button_text_added' ];
									}

									$bt_favorites_button_class = '';
									if (  $options && array_key_exists( 'bt_favorites_button_class', $options ) && $options[ 'bt_favorites_button_class' ] != '') {
										$bt_favorites_button_class = $options[ 'bt_favorites_button_class' ];
									}
									$bt_favorites_button_style = '';
									if (  $options && array_key_exists( 'bt_favorites_button_style', $options ) && $options[ 'bt_favorites_button_style' ] != '') {
										$bt_favorites_button_style = $options[ 'bt_favorites_button_style' ];
									}

									echo '<tr><td>' . esc_html( 'Unfavorited Text', 'bt_favorites' ) . '</td><td><input type="text" name="bt_favorites_settings[bt_favorites_button_text]" value="'.$bt_favorites_button_text.'"></td></tr>';
									echo '<tr><td>' . esc_html( 'Favorited Text', 'bt_favorites' ) . '</td><td><input type="text" name="bt_favorites_settings[bt_favorites_button_text_added]" value="'.$bt_favorites_button_text_added.'"></td></tr>';
									echo '<tr><td>' . esc_html( 'Custom Class', 'bt_favorites' ) . '</td><td><input type="text" name="bt_favorites_settings[bt_favorites_button_class]" value="'.$bt_favorites_button_class.'"></td></tr>';
									echo '<tr><td>' . esc_html( 'Custom Style', 'bt_favorites' ) . '</td><td><textarea name="bt_favorites_settings[bt_favorites_button_style]" rows="5" cols="50">'.$bt_favorites_button_style.'</textarea></td></tr>';

									?>
								  </table>
							</td>					
						</tr>
                        <tr>
                        <th scope="row"><?php _e( 'Shortcode Button', 'bt_favorites' ); ?></th>
							<td>
								<fieldset><legend class="screen-reader-text"><span><?php _e( 'Shortcode Button', 'bt_favorites' ); ?></span></legend>
								<p>	
									<table>
										<?php
										$bt_favorites_simple_button_text = '';
										if (  $options && array_key_exists( 'bt_favorites_simple_button_text', $options ) && $options[ 'bt_favorites_simple_button_text' ] != '') {
											$bt_favorites_simple_button_text = $options[ 'bt_favorites_simple_button_text' ];
										}
										
										$bt_favorites_simple_button_text_added = '';
										if (  $options && array_key_exists( 'bt_favorites_simple_button_text_added', $options ) && $options[ 'bt_favorites_simple_button_text_added' ] != '') {
											$bt_favorites_simple_button_text_added = $options[ 'bt_favorites_simple_button_text_added' ];
										}
										
										$bt_favorites_simple_button_class = '';
										if (  $options && array_key_exists( 'bt_favorites_simple_button_class', $options ) && $options[ 'bt_favorites_simple_button_class' ] != '') {
											$bt_favorites_simple_button_class = $options[ 'bt_favorites_simple_button_class' ];
										}
										$bt_favorites_simple_button_style = '';
										if (  $options && array_key_exists( 'bt_favorites_simple_button_style', $options ) && $options[ 'bt_favorites_simple_button_style' ] != '') {
											$bt_favorites_simple_button_style = $options[ 'bt_favorites_simple_button_style' ];
										}
									   
										echo '<tr><td><label>' . esc_html( 'Unfavorited Text', 'bt_favorites' ) . '</label></td><td><input type="text" name="bt_favorites_settings[bt_favorites_simple_button_text]" value="'.$bt_favorites_simple_button_text.'"></td></tr>';
										echo '<tr><td><label>' . esc_html( 'Favorited Text', 'bt_favorites' ) . '</label></td><td><input type="text" name="bt_favorites_settings[bt_favorites_simple_button_text_added]" value="'.$bt_favorites_simple_button_text_added.'"></td></tr>';
										echo '<tr><td><label>' . esc_html( 'Custom Class', 'bt_favorites' ) . '</label></td><td><input type="text" name="bt_favorites_settings[bt_favorites_simple_button_class]" value="'.$bt_favorites_simple_button_class.'"></td></tr>';
										echo '<tr><td><label>' . esc_html( 'Custom Style', 'bt_favorites' ) . '</label></td><td><textarea name="bt_favorites_settings[bt_favorites_simple_button_style]" rows="5" cols="50">'.$bt_favorites_simple_button_style.'</textarea></td></tr>';
									   
										?>
									  </table>
								</p> 
								<p class="description">
								<?php esc_html_e( 'You may overwrite Favorites Simple Button class and style settings in the shortcode, ex. [bt_favorites_button site_id="1" class="bt_bb_favs" style="background-color: red;"].', 'bt_favorites' ); ?>
								</p>
								</fieldset>
							</td>					
						</tr>
						<tr>
                            <th scope="row"><?php _e( 'List Appearance', 'bt_favorites' ); ?></th>
							<td>
								<fieldset><legend class="screen-reader-text"><span><?php _e( 'Favorites List', 'bt_favorites' ); ?></span></legend>
								<p>
									<table>
										<?php
										$bt_favorites_clear_button_text = '';
										if (  $options && array_key_exists( 'bt_favorites_clear_button_text', $options ) && $options[ 'bt_favorites_clear_button_text' ] != '') {
											$bt_favorites_clear_button_text = $options[ 'bt_favorites_clear_button_text' ];
										}
										
										$bt_favorites_div_class = '';
										if (  $options && array_key_exists( 'bt_favorites_div_class', $options ) && $options[ 'bt_favorites_div_class' ] != '') {
											$bt_favorites_div_class = $options[ 'bt_favorites_div_class' ];
										}
										$bt_favorites_div_style = '';
										if (  $options && array_key_exists( 'bt_favorites_div_style', $options ) && $options[ 'bt_favorites_div_style' ] != '') {
											$bt_favorites_div_style = $options[ 'bt_favorites_div_style' ];
										}
										$bt_favorites_no_favorites_text = '';
										if (  $options && array_key_exists( 'bt_favorites_no_favorites_text', $options ) && $options[ 'bt_favorites_no_favorites_text' ] != '') {
											$bt_favorites_no_favorites_text = $options[ 'bt_favorites_no_favorites_text' ];
										}
										echo '<tr><td><label>' . esc_html( 'Clear Favorites Button Text', 'bt_favorites' ) . '</label></td><td><input type="text" name="bt_favorites_settings[bt_favorites_clear_button_text]" value="'.$bt_favorites_clear_button_text.'"></td></tr>';
										echo '<tr><td><label>' . esc_html( 'No Favorites Text', 'bt_favorites' ) . '</label></td><td><input type="text" name="bt_favorites_settings[bt_favorites_no_favorites_text]" style="width:400px !important;" value="'.$bt_favorites_no_favorites_text.'"></td></tr>';
									   
										echo '<tr><td><label>' . esc_html( 'Button Class', 'bt_favorites' ) . '</label></td><td><input type="text" name="bt_favorites_settings[bt_favorites_div_class]" value="'.$bt_favorites_div_class.'"></td></tr>';
										echo '<tr><td><label>' . esc_html( 'Button Style', 'bt_favorites' ) . '</label></td><td><textarea name="bt_favorites_settings[bt_favorites_div_style]" rows="5" cols="50">'.$bt_favorites_div_style.'</textarea></td></tr>';
									   
										?>
									  </table>
								</p>
								<p class="description">
								<?php esc_html_e( 'You may overwrite Favorites List class and style settings in the shortcode, ex. [bt_favorites_list site_id="1" class="bt_bb_favs" style="background-color: red;"].', 'bt_favorites' ); ?>
								</p>
								</fieldset>
							</td>					
						</tr>
					</tbody>
				</table>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Settings', 'bt_favorites' ); ?>"></p>
			</form>
		</div>
	<?php

}


