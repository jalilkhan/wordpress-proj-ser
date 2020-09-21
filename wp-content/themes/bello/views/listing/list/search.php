<?php
$listing_categories = get_terms( array(
    'taxonomy' => 'listing-category',
    'hide_empty' => false,
    'parent' => 0
) );
$listing_regions = get_terms( array(
    'taxonomy' => 'listing-region',
    'hide_empty' => false,
    'parent' => 0
) );
 
$listing_form_action_page   = get_post_type_archive_link( 'listing' ) ? get_post_type_archive_link( 'listing' ) : '#' ;
$have_prices                = boldthemes_listing_sort_prices( BoldThemesFrameworkTemplate::$listing_category );
?>

<form id="listing_search_form" name="listing_search_form"  action="<?php echo esc_attr($listing_form_action_page);?>" method='get'>        
        <input type="hidden" name="listing_list_view" id="listing_list_view" value="<?php echo BoldThemesFrameworkTemplate::$listing_list_view;?>" />       
        <input type="hidden" name="bt_bb_listing_field_my_lat" id="bt_bb_listing_field_my_lat" value="<?php echo BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lat;?>" />
        <input type="hidden" name="bt_bb_listing_field_my_lng" id="bt_bb_listing_field_my_lng" value="<?php echo BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lng;?>" />
        <input type="hidden" name="bt_bb_listing_field_my_lat_default" id="bt_bb_listing_field_my_lat_default" value="<?php echo BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lat_default;?>" />
        <input type="hidden" name="bt_bb_listing_field_my_lng_default" id="bt_bb_listing_field_my_lng_default" value="<?php echo BoldThemesFrameworkTemplate::$bt_bb_listing_field_my_lng_default;?>" />
        <?php if (BoldThemesFrameworkTemplate::$listing_search_autocomplete){ ?>
            <?php if (BoldThemesFrameworkTemplate::$listing_region) { ?>
                 <input type="hidden" name="bt_bb_listing_field_region" id="bt_bb_listing_field_region" value="<?php echo BoldThemesFrameworkTemplate::$listing_region?>" />
            <?php  } ?>
        <?php  } ?>
	
        <div class="bt_bb_row bt_bb_column_gap_10">

		<div class="bt_bb_column col-lg-3 col-md-2 col-sm-12 bt_bb_vertical_align_top bt_bb_padding_normal bt_bb_spaced bt_bb_listing_search_col" data-width="5">
			<div class="bt_bb_listing_search_element">
				<label><?php esc_html_e( 'What you\'d like to find?', 'bello' ); ?></label>
				<input type="text" name="bt_bb_listing_field_keyword" id="bt_bb_listing_field_keyword" placeholder="<?php echo esc_attr__( 'Keyword to search...', 'bello' ); ?>" value='<?php echo BoldThemesFrameworkTemplate::$keyword;?>'>
			</div>
		</div>

		<div class="bt_bb_column col-lg-3 col-md-2 col-sm-12 bt_bb_vertical_align_top bt_bb_padding_normal bt_bb_spaced bt_bb_listing_search_col" data-width="5">
			<div class="bt_bb_listing_search_element">
				<label><?php esc_html_e( 'Select category', 'bello' ); ?> <span class="bt_bb_listing_note bt_bb_category_help" title="<?php esc_attr_e( 'Select category to show additional search options', 'bello' ); ?>"></span></label>
				<select name="bt_bb_listing_field_category" id="bt_bb_listing_field_category">
					<option value="all"><?php esc_html_e( 'All categories, please', 'bello' ); ?></option>
					<?php 
						foreach ( $listing_categories as $listing_cat ){							
							$sel = BoldThemesFrameworkTemplate::$listing_category == $listing_cat->slug ? ' selected' : '';	
                                                        if ( $listing_cat->slug == BoldThemesFrameworkTemplate::$listing_root_slug ){
                                                            continue;
                                                        }
                                                        echo '<option value="' . esc_attr( $listing_cat->slug ) . '" ' .  $sel . '>' . $listing_cat->name . '</option>';  
							foreach( get_terms( 'listing-category', array( 'hide_empty' => false, 'parent' => $listing_cat->term_id ) ) as $child_term ) {
                                                            $sel_child = BoldThemesFrameworkTemplate::$listing_category == $child_term->slug ? ' selected' : '';
                                                             echo '<option value="' . esc_attr( $child_term->slug ) . '" ' .  $sel_child . '>&nbsp;&nbsp;&nbsp;&nbsp;' . $child_term->name . '</option>';                                                                                                                           
                                                        }
						} 
					 ?>
				</select>
			</div>
		</div>	
            
                <div class="bt_bb_column col-lg-3 col-md-2 col-sm-12 bt_bb_vertical_align_top bt_bb_padding_normal bt_bb_spaced bt_bb_listing_search_col" data-width="2">
			<div class="bt_bb_listing_search_element">
                            <?php                                 
                                if ( BoldThemesFrameworkTemplate::$listing_root_slug != '' ) {                                    
                                        $listing = get_term_by('slug', BoldThemesFrameworkTemplate::$listing_root_slug, 'listing-category');
                                        $listing_category_id = !empty($listing) ? $listing->term_id : 0;
                                        BoldThemesFrameworkTemplate::$listing_gets['show_control'] = 'working_time';
                                        bello_get_listing_search( $listing_category_id, 'search', BoldThemesFrameworkTemplate::$listing_gets );
                                        unset(BoldThemesFrameworkTemplate::$listing_gets['show_control']);                                     
                                }                                
                            ?>
                        </div>
		</div>	
	</div>
        
        <!-- SEARCH CONTAINER FOR ROOT LISTING CATEGORY -->
        
        <div id="bt_bb_listing_options_search_root_view_container" class="bt_bb_row bt_bb_column_gap_10">
                <?php if (!BoldThemesFrameworkTemplate::$listing_search_autocomplete){ ?>
                    <div class="bt_bb_column bt_bb_vertical_align_top bt_bb_padding_normal bt_bb_listing_search_col bt_bb_listing_search_fields bt_bb_spaced bt_bb_search_map_control_select" data-control-type="">
                            <div class="bt_bb_column_content">
                                     <div class="bt_bb_listing_search_element">                                 
                                            <label><?php esc_html_e( 'Where to look for?', 'bello' ); ?></label> 
                                            <?php if (!BoldThemesFrameworkTemplate::$listing_search_autocomplete){ ?>
                                            <select name="bt_bb_listing_field_region" id="bt_bb_listing_field_region">
                                                    <option value="all"><?php esc_html_e( 'Everywhere', 'bello' ); ?></option>
                                                    <?php foreach ( $listing_regions as $listing_region ){
                                                            $sel = BoldThemesFrameworkTemplate::$listing_region == $listing_region->slug ? ' selected' : '';
                                                            if ( $listing_region->slug == BoldThemesFrameworkTemplate::$listing_region ){
                                                                //continue;
                                                            }
                                                            ?>
                                                            <option value="<?php echo esc_attr($listing_region->slug);?>" <?php echo esc_html($sel);?>><?php echo esc_html($listing_region->name);?></option>
                                                                <?php
                                                                foreach( get_terms( 'listing-region', array( 'hide_empty' => false, 'parent' => $listing_region->term_id ) ) as $child_term ) {
                                                                     $sel_child = BoldThemesFrameworkTemplate::$listing_region == $child_term->slug ? ' selected' : '';
                                                                     echo '<option value="' . esc_attr( $child_term->slug ) . '" ' .  $sel_child . '>&nbsp;&nbsp;&nbsp;&nbsp;' . $child_term->name . '</option>';  
                                                                     
                                                                     foreach( get_terms( 'listing-region', array( 'hide_empty' => false, 'parent' => $child_term->term_id ) ) as $child_term2 ) {
                                                                        $sel_child2 = BoldThemesFrameworkTemplate::$listing_region == $child_term2->slug ? ' selected' : '';
                                                                        echo '<option value="' . esc_attr( $child_term2->slug ) . '" ' .  $sel_child2 . '>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $child_term2->name . '</option>';                                                                                                                           
                                                                        foreach( get_terms( 'listing-region', array( 'hide_empty' => false, 'parent' => $child_term2->term_id ) ) as $child_term3 ) {
                                                                            $sel_child3 = BoldThemesFrameworkTemplate::$listing_region == $child_term3->slug ? ' selected' : '';
                                                                            echo '<option value="' . esc_attr( $child_term3->slug ) . '" ' .  $sel_child3 . '>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $child_term3->name . '</option>';                                                                                                                           
                                                                         }                                                                        
                                                                     }
                                                                }
                                                                ?>
                                                    <?php } ?>
                                            </select>
                                             <?php } ?>
                                      </div>
                            </div>
                    </div>
                <?php } ?>
                <?php    
                        if ( BoldThemesFrameworkTemplate::$listing_root_slug != '' ) {                             
                                $listing = get_term_by('slug', BoldThemesFrameworkTemplate::$listing_root_slug, 'listing-category');
                                $listing_category_id = !empty($listing) ? $listing->term_id : 0;
                                BoldThemesFrameworkTemplate::$listing_gets['hide_control'] = 'working_time';
                                bello_get_listing_search( $listing_category_id, 'search', BoldThemesFrameworkTemplate::$listing_gets );
                                unset(BoldThemesFrameworkTemplate::$listing_gets['hide_control']);
                        }
                ?>
	</div>
	
	<!-- SEARCH CONTAINER FOR AJAX -->
	<div id="bt_bb_listing_options_search_view_container" class="bt_bb_row bt_bb_column_gap_10" >	
		<?php
			if ( BoldThemesFrameworkTemplate::$listing_category != '' ) {
				 $listing = get_term_by('slug', BoldThemesFrameworkTemplate::$listing_category, 'listing-category');
				 $listing_category_id = !empty($listing) ? $listing->term_id : 0;
				 bello_get_listing_search( $listing_category_id, 'search', BoldThemesFrameworkTemplate::$listing_gets );
			}
		?>
	</div>	

	<div class="bt_bb_row bt_bb_column_gap_10">
		<div class="bt_bb_column col-sm-12 bt_bb_align_center bt_bb_vertical_align_top bt_bb_padding_normal bt_bb_spaced bt_bb_listing_search_button" data-width="12">
			<div class="bt_bb_column_content">
				<div class="bt_bb_button bt_bb_icon_position_left bt_bb_color_scheme_6 bt_bb_style_filled bt_bb_size_normal bt_bb_width_inline bt_bb_shape_inherit bt_bb_align_inherit">
					<a href="#" target="_self" class="bt_bb_link" id="bt_bb_link_search_submit"><span class="bt_bb_button_text"><?php esc_html_e( 'Search', 'bello' ); ?></span><span data-ico-fontawesome="&#xf002;" class="bt_bb_icon_holder"></span></a>
				</div>
			</div>
		</div>
	</div>

	<div class="bt_bb_row bt_bb_column_gap_10">
		<div class="bt_bb_column col-sm-12 bt_bb_align_center bt_bb_vertical_align_top bt_bb_padding_normal" data-width="12">
			<div class="bt_bb_column_content">
				<div class="bt_bb_separator bt_bb_bottom_spacing_small bt_bb_border_style_solid" style="border-width: 2px"></div>
			</div>
		</div>
	</div>

	<div class="bt_bb_row bt_bb_column_gap_10">
		<div class="bt_bb_column col-sm-12 bt_bb_vertical_align_top bt_bb_padding_normal" data-width="12">
			<div class="bt_bb_column_content bt_bb_listing_options">
				<div class="bt_bb_listing_options_results">
                    <?php esc_html_e( 'Found ', 'bello' ); ?>
					<?php echo BoldThemesFrameworkTemplate::$found;?> <?php esc_html_e( 'results', 'bello' ); ?>                                        
				</div>
				<div class="bt_bb_listing_options_view_on_map">
					<a href="#" id="bt_bb_listing_options_view_on_map"><?php esc_html_e( 'View results on a map', 'bello' ); ?></a>
				</div>
				<div class="bt_bb_listing_options_sorting">
					<label><?php esc_html_e( 'Sort by', 'bello' ); ?></label>
					<select name="bt_bb_listing_field_sort" id="bt_bb_listing_field_sort">
                        <option value="-1" <?php if ( BoldThemesFrameworkTemplate::$listing_search_sort == '-1'){echo ' selected';}?>><?php esc_html_e( 'Date, descending', 'bello' ); ?></option>
						<option value="0" <?php if ( BoldThemesFrameworkTemplate::$listing_search_sort == '0'){echo ' selected';}?>><?php esc_html_e( 'Date, ascending', 'bello' ); ?></option>
						<option value="1" <?php if ( BoldThemesFrameworkTemplate::$listing_search_sort == '1'){echo ' selected';}?>><?php esc_html_e( 'Name, A-Z', 'bello' ); ?></option>
						<option value="2" <?php if ( BoldThemesFrameworkTemplate::$listing_search_sort == '2'){echo ' selected';}?>><?php esc_html_e( 'Name, Z-A', 'bello' ); ?></option>
						<?php if ( $have_prices == 1 ) { ?>
							<option value="3"><?php esc_html_e( 'Price, descending', 'bello' ); ?></option>
							<option value="4"><?php esc_html_e( 'Price, ascending', 'bello' ); ?></option>
						<?php } ?>
						<option value="rand" <?php if ( BoldThemesFrameworkTemplate::$listing_search_sort == 'rand'){echo ' selected';}?>><?php esc_html_e( 'Please select order', 'bello' ); ?></option>
					</select>
				</div>
			</div>
		</div>
	</div>

	<div class="bt_bb_row bt_bb_column_gap_10">
		<div class="bt_bb_column col-sm-12 bt_bb_vertical_align_top bt_bb_padding_normal" data-width="12">
			<div class="bt_bb_column_content">
				<div class="bt_bb_separator bt_bb_top_spacing_extra_small bt_bb_bottom_spacing_extra_small bt_bb_border_style_solid"></div>
			</div>
		</div>
	</div>
	<div class="bt_bb_row bt_bb_column_gap_10">
		<div class="bt_bb_column col-sm-12 bt_bb_vertical_align_top bt_bb_padding_normal" data-width="12">
			<div class="bt_bb_column_content bt_bb_listing_viewing_options">
                                <div class="bt_bb_listing_options_additional_filters" id="bt_bb_listing_options_additional_filters">
                                            <span><?php esc_html_e( 'Additional filters', 'bello' ); ?></span>
                                </div>
				<div class="bt_bb_listing_options_view_as">
                                        <?php
                                            $list_view_class = '';
                                            $grid_view_class = ' on';
                                            if (BoldThemesFrameworkTemplate::$listing_list_grid_view == 'list' ){
                                                $list_view_class = '  on';
                                                $grid_view_class = '';
                                            }
                                        ?>
					<span><?php esc_html_e( 'View as', 'bello' ); ?></span>
					<ul>
						<li><a href="#" class="bt_bb_listing_options_view_list<?php echo esc_attr($list_view_class);?>"><?php esc_html_e( 'List', 'bello' ); ?></a></li>
						<li><a href="#" class="bt_bb_listing_options_view_grid<?php echo esc_attr($grid_view_class);?>" data-columns="<?php echo BoldThemesFrameworkTemplate::$listing_grid_columns;?>"><?php esc_html_e( 'Grid', 'bello' ); ?></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<!-- ADDITIONAL FILTER SEARCH CONTAININER FOR AJAX -->
        <div class="bt_bb_additional_filter_loader" style="display: none;"></div>
	<div class="bt_bb_row bt_bb_column_gap_10 bt_bb_listing_options_additional_filters_view" id="bt_bb_listing_options_additional_filters_view_container"></div>


</form>



