<?php
if ( class_exists( 'RWMB_Field' ) )
{
	class RWMB_Tag_Field extends RWMB_Field
	{
		static public function html( $meta, $field )
		{
			$args = array(
				'taxonomy'   => 'listing-tag',
				'hide_empty' => false
			);
			$tags = get_terms( $args ); 
                        
                        $id = 0;
                        if ( isset($_GET["post"]) ){
                            $id = $_GET["post"];
                        }
                        if ( isset($_GET["listing_id"]) ){
                            $id = $_GET["listing_id"];
                        }
                        
                        $checks = '';
                        if ( intval($id) > 0 ) {
                            global $wpdb;
                            $where             = 'tr.object_id = %d and tt.taxonomy = "listing-tag"';                        
                            $where_fields      = array( $id );
                            $result =  $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->term_relationships as tr "
                                    . " inner join $wpdb->term_taxonomy as tt on tr.term_taxonomy_id=tt.term_taxonomy_id WHERE $where", $where_fields ) );
                            
                            $result_arr = array();
                            if ( !empty($result) ){
                                foreach( $result as $r ) {
                                    $result_arr[] = $r->term_taxonomy_id;
                                } 
                            }
                            if ( !empty($tags) ){    
                                foreach( $tags as $r ) {
                                        $checks .= '<div class="rwmb-field rwmb-checkbox-wrapper">';
                                            $checks .= '<div class="rwmb-label">';
                                                    $checks .= '<label for="boldthemes_theme_listing-' . $r->name . '">' . $r->name . '</label>';
                                            $checks .= '</div>';
                                            $checks .= '<div class="rwmb-input">';
                                                    if ( in_array($r->term_taxonomy_id, $result_arr)  ){
                                                            $checks .= '<input class="rwmb-checkbox" type="checkbox" id="' . $field['field_name'] . '" name="' . $field['field_name'] . '[]" value="' . $r->term_taxonomy_id . '"  checked="checked">';
                                                    } else {
                                                            $checks .= '<input class="rwmb-checkbox" type="checkbox" id="' . $field['field_name'] . '" name="' . $field['field_name'] . '[]" value="' . $r->term_taxonomy_id . '">';
                                                    }
                                            $checks .= '</div>';
                                        $checks .= '</div>';
                                } 
                            }
                        }
			return $checks;
		}
	}
}
