<?php

// working_time;Working time;working_time;widget_working_time
// hours format '9:00' , '9 AM', '9:00 AM'
// if all wotkung hours are empty then show widget = 0 , bello.php l.1869
if ( isset($field) ) {
	if ( isset($field['name']) && isset($field['value']) ) {
		$working_times	= boldthemes_rwmb_meta('boldthemes_theme_listing-working_time');
		$listing_search_time_format = get_option( 'time_format' ) != '' ?  get_option( 'time_format' ) : 'H:i';  
                
		$day_name		= bt_day_name();
		$open_hours		= bt_open_hours( get_the_ID() );
                
		?>
		<div class="widget_bt_bb_listing_marker_working_hours <?php echo $field['group'];?>">
			<ul class="bt_bb_listing_marker_meta_data_items">
                            <?php if ($open_hours == 'closed'){ ?>
					<li class="bt_bb_listing_marker_meta_working_hours"><span><?php echo(__( 'Now closed', 'bt_plugin' ))?> </span></li>
                            <?php }else{ ?> 
					<?php if ( $open_hours != '' && $open_hours != 'closed' ) { ?>
						<li class="bt_bb_listing_marker_meta_working_hours">
                                                    <span> <?php echo(__( 'Now closed', 'bt_plugin' ))?> <span class="bt_bb_listing_marker_meta_show_working_hours"><?php __( 'Show working hours', 'bt_plugin' )?></span> 
                                                        <?php if ( $open_hours != '00:00' && $open_hours != '12:00 am') { ?>
                                                        <small class="bt_bb_listing_marker_meta_opens_at"><?php echo(__( 'Opens at', 'bt_plugin' ))?> <strong><?php echo $open_hours;?></strong></small>
                                                        <?php } ?>
                                                    </span>
					<?php }else if ($open_hours == 'closed'){ ?>
						<li class="bt_bb_listing_marker_meta_working_hours"><span><?php echo(__( 'Now closed', 'bt_plugin' ))?> </span>
					<?php }else{ ?>
						<li class="bt_bb_listing_marker_meta_working_hours bt_bb_listing_marker_meta_now_working"><span><?php echo(__( 'Now open', 'bt_plugin' ))?> <span class="bt_bb_listing_marker_meta_show_working_hours"><?php echo(__( 'Show working hours', 'bt_plugin' ))?></span></span>
					<?php } ?>
                                                    
					<dl>
						<?php
							$i = 0;
							foreach(  $working_times as  $working_time){
                                                                $klasa =  '';
								$title = '<dt>' . date_i18n( 'l', $day_name[ $i ] ) . '</dt>';

								 if ( isset($working_time["all"]) ) {                                                                        
                                                                        if ( $working_time["all"] != 1 ) {  
                                                                             $klasa = ' class="bt_bb_listing_marker_meta_working_hours_closed"';     
                                                                        }
                                                                }else{
                                                                    $klasa = ( $working_time["start"] == '' && $working_time["end"] =='' && $working_time["start2"] == '' && $working_time["end2"] == '' ) ? ' class="bt_bb_listing_marker_meta_working_hours_closed"' : '';                                                             
                                                                }
                   
                                                                
								$hours1_start = '';
								if ( isset($working_time["start"]) && $working_time["start"] != ''  ){
                                                                        $hours1_start = date($listing_search_time_format, strtotime($working_time["start"]));
								}

								$hours1_end = '';
								if ( isset($working_time["end"]) && $working_time["end"] != ''  ){
									$hours1_end	= ' - ' . date($listing_search_time_format, strtotime($working_time["end"]));
								}
								
								$hours2_start = '';
								if ( isset($working_time["start2"]) && $working_time["start2"] != ''  ){
									$hours2_start = ' ' . date($listing_search_time_format, strtotime($working_time["start2"]));
								}

								$hours2_end = '';
								if ( isset($working_time["end2"]) && $working_time["end2"] != ''  ){
									$hours2_end = ' - ' . date($listing_search_time_format, strtotime($working_time["end2"]));
								}
                                                                
                                                                $hours_all = '';
								if ( isset($working_time["all"]) && $working_time["all"] == 1  ){
									$hours1_start = '24h';
                                                                        $hours1_end   = '';  
								}
                                                                
                                                                
								$hours = $hours1_start . $hours1_end . $hours2_start . $hours2_end;	

								if ( $hours == '' ) {
									$hours =  __( 'CLOSED', 'bt_plugin' );										
								}

								echo $title . '<dd' . $klasa . '>' . $hours . '</dd>';
								$i++;

							}
						?>
					</dl>
				</li>
                           <?php } ?>
			</ul>
		</div>
		<?php
	}
}
