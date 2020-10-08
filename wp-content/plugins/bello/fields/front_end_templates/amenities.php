<?php
// in single listing view in content area
if ( isset($field) ) {
	if ( isset($field["value"][0]) && $field["value"][0] == 1 ) {
		?>
		<li><?php echo $field["name"];?></li>
		<?php
	}
}

/*
 * exp: in categoru custom fileds:
 
amenities_free_wifi;Free Wi-Fi;checkbox;Amenities
 * 
 * slug;name;field;group
 * 
Array
(
    [slug] => amenities_free_wifi
    [name] => Free Wi-Fi
    [type] => amenities
    [group] => Amenities
    [term_id] => 106
    [value] => Array
        (
            [0] => 1
        )

)

*/