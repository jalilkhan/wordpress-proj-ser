<?php
 /* leaflet_map bb framework */

bt_include_scripts_leaflet();

function bt_include_scripts_leaflet() {
    wp_enqueue_script( 'jquery' );
    $leaflet_framework_path	= get_template_directory_uri() . '/bold-page-builder/content_elements/bt_bb_leaflet_map/leafletmap/'; 
    /* js */
    wp_enqueue_script( 'framework-leaflet-js', $leaflet_framework_path . 'lib/leaflet.js' );
    wp_enqueue_script( 'framework-leaflet-markercluster-js', $leaflet_framework_path . 'lib/leaflet.markercluster.js' );
    wp_enqueue_script( 'framework-leaflet-ajax-min-js', $leaflet_framework_path . 'lib/leaflet.ajax.min.js' ); 
    /* css */
    wp_enqueue_style( 'framework-lefflet-css', $leaflet_framework_path . 'lib/leaflet.css', array(), false, 'screen');
    wp_enqueue_style( 'framework-markercluster-css', $leaflet_framework_path . 'lib/MarkerCluster.css', array(),false, 'screen' );                 
    wp_enqueue_style( 'framework-markerclustee-default-css',  $leaflet_framework_path . 'lib/MarkerCluster.Default.css', array(), false, 'screen' ); 

    /* map source */
    wp_enqueue_script( 'leafletmap-source-js', $leaflet_framework_path . 'js/leafletmap-source.js' );
    /* map css*/
    wp_enqueue_style( 'leaflet-css', $leaflet_framework_path . 'css/leaflet.css', array(), false, 'screen' );
}

//add_action( 'wp_enqueue_scripts', 'bt_enqueue_scripts_leaflet' );
