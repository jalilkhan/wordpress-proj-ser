<?php

$custom_fields = bello_get_listing_fields();

foreach( $custom_fields as $field ) {
    if ( isset($field) ) {
        
        if ( $field['group'] == 'widget_' . $slug ||  $field['group'] == $slug ) {
            if ( $field['type'] == 'image_advanced' ){
                $value[0] = array(
                        'slug'			=> isset($field['slug']) ? $field['slug'] : '',
                        'name'			=> 'Listing Galleries',
                        'type'			=> 'image_advanced',
                        'group'			=> isset($field['group']) ? $field['group'] : '',
                        'term_id'		=> isset($field['term_id']) ? $field['term_id'] : 0,
                        'value'			=> isset($field['value']) ? $field['value'] : array()
                );

                $field = array(
                        'name'			=> 'Listing Galleries',
                        'type'			=> 'image_advanced',
                        'group'			=> isset($field['group']) ? $field['group'] : '',
                        'slug'			=> isset($field['slug']) ? $field['slug'] : '',
                        'term_id'		=> isset($field['term_id']) ? $field['term_id'] : 0,
                        'showinfo'		=> 1,
                        'gallery_type'          => 'carousel',
                        'value'			=> $value
                );
                echo bello_show_field( $field, '1' );
            } else if ( $field['type'] == 'oembed' ) {
                $field["showinfo"] = 0;
                echo bello_show_field( $field, '1' );
            } else if ( $field['type'] == 'audio' ) {
                $field["showinfo"] = 0;
                echo bello_show_field( $field, '1' );
            }else{                
                bello_show_field( $field, $title );
            }

        }
    }
}

/*

Array
(
[slug] => location_position
[name] => Location
[type] => map
[group] => Location
)

Array
(
    [slug] => working_time
    [name] => Working time
    [type] => working_time
    [group] => widget_working_time
    [term_id] => 106
    [value] => Array
        (
            [0] => a:7:{i:0;a:4:{s:5:"start";s:5:"10:00";s:3:"end";s:5:"22:00";s:6:"start2";s:0:"";s:4:"end2";s:0:"";}i:1;a:4:{s:5:"start";s:5:"10:00";s:3:"end";s:5:"21:00";s:6:"start2";s:0:"";s:4:"end2";s:0:"";}i:2;a:4:{s:5:"start";s:0:"";s:3:"end";s:0:"";s:6:"start2";s:0:"";s:4:"end2";s:0:"";}i:3;a:4:{s:5:"start";s:0:"";s:3:"end";s:0:"";s:6:"start2";s:0:"";s:4:"end2";s:0:"";}i:4;a:4:{s:5:"start";s:0:"";s:3:"end";s:0:"";s:6:"start2";s:0:"";s:4:"end2";s:0:"";}i:5;a:4:{s:5:"start";s:0:"";s:3:"end";s:0:"";s:6:"start2";s:0:"";s:4:"end2";s:0:"";}i:6;a:4:{s:5:"start";s:0:"";s:3:"end";s:0:"";s:6:"start2";s:0:"";s:4:"end2";s:0:"";}}
        )

)


*/

?>
