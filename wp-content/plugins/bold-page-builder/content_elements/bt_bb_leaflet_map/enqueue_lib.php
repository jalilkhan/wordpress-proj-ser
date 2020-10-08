<?php
 /* leaflet_map bb framework */

bt_include_scripts_leaflet();

function bt_include_scripts_leaflet() {
    
	/* js */
    wp_enqueue_script( 'framework-leaflet-js', plugin_dir_url( __FILE__ ) . 'leafletmap/lib/leaflet.js' );
    wp_enqueue_script( 'framework-leaflet-markercluster-js', plugin_dir_url( __FILE__ ) . 'leafletmap/lib/leaflet.markercluster.js' );
    wp_enqueue_script( 'framework-leaflet-ajax-min-js', plugin_dir_url( __FILE__ ) . 'leafletmap/lib/leaflet.ajax.min.js' ); 
    
	/* css */
    wp_enqueue_style( 'framework-lefflet-css', plugin_dir_url( __FILE__ ) . 'leafletmap/lib/leaflet.css', array(), false, 'screen');
    wp_enqueue_style( 'framework-markercluster-css', plugin_dir_url( __FILE__ ) . 'leafletmap/lib/MarkerCluster.css', array(), false, 'screen' );                 
    wp_enqueue_style( 'framework-markerclustee-default-css',  plugin_dir_url( __FILE__ ) . 'leafletmap/lib/MarkerCluster.Default.css', array(), false, 'screen' ); 

    /* map source */
    wp_enqueue_script( 'leafletmap-source-js', plugin_dir_url( __FILE__ ) . 'leafletmap/js/leafletmap-source.js' );
    
}

//add_action( 'wp_enqueue_scripts', 'bt_enqueue_scripts_leaflet' );
