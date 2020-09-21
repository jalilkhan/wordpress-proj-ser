<?php
 /* osm map bb framework */

bt_include_scripts_osm();
function bt_include_scripts_osm() {
    wp_enqueue_script( 'jquery' );
    $osm_framework_path	= get_template_directory_uri() . '/bold-page-builder/content_elements/bt_bb_openmap/openmap/'; 
    /* js */
    wp_enqueue_script( 'openmap-framework-js', $osm_framework_path . 'lib/OpenLayers.js' );
    wp_enqueue_script( 'openmap-source-js', $osm_framework_path . 'theme-openmap-source.js' );  
}

//add_action( 'wp_enqueue_scripts', 'bt_enqueue_scripts_osm' );


