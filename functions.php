<?php 
/* Functions for Demo Site */

// Include Truly Dynamic Sidebars Script
require_once ( 'lib/truly-dynamic-sidebars.php' );

// Regsiter Any Meta Boxes with Meta Box Class
function redcm_register_meta_boxes() {
	global $meta_boxes;
	if ( class_exists( 'RW_Meta_Box' ) ) { foreach ( $meta_boxes as $meta_box ) { new RW_Meta_Box( $meta_box );	} }
}
add_action( 'admin_init', 'redcm_register_meta_boxes' );



?>