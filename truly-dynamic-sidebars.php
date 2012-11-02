<?php
/* Truly Dynamic Sidebars
 * Developed by Ed Nailor, RED Creative Marketing ( http://gored.co )
 * Presented for WordCamp Raleigh, Nov 2012.
 */
 
/* Register the Client Added Sidebars 
 *
 * Uses OptionTree plugin by Derek Herman ( http://wp.envato.com/ )
 *
 * This script takes the client's added sidebars from the theme options
 * and registers each one. The structure of the register_sidebar array
 * is taken from the parent theme (TwentyTen) to ensure it fits within
 * the HTML structure of the theme.
 *
 * In addition, since there is a possibility of having several sidebars,
 * we have included the Widget Admin Dropdown Switch, a simple script that 
 * converts all the sidebars on the Widgets admin page into a single 
 * dropdown so you are only managing one Widget area at a time.
 *
 * Currently, requires some modifications to the theme to make this all
 * happen. Stay tuned! Plugin in development!
 *
 */
 
/* Register Client Sidebars
 * This script will pull the client submitted sidebars, combine them
 * with our "Default" sidebar (which is used when one is not selected)
 * and them registers each one.
 *
 */

if( ! function_exists( 'redcm_create_client_sidebars' ) ) :
	function redcm_create_client_sidebars() {	

		global $the_sidebars, $client_sidebars, $the_sidebars_array;
		
		// Start string with our Deafult Sidebar
		$the_sidebars = 'Default Sidebar';
		$client_sidebars = null;
		if( function_exists( 'ot_get_option' ) ) $client_sidebars = ot_get_option( 'sidebars' ); // pull the theme options data
		
		// Pull the client submitted sidebars through the theme options panel and merge to the string
		if( $client_sidebars ) $the_sidebars .=','; // add comma to connect the string
		$the_sidebars .= $client_sidebars;
		
		// Convert the string to an array
		$the_sidebars_array = explode( ',', $the_sidebars );

		// Register all the sidebars
		foreach( $the_sidebars_array as $sidebar ) {
			register_sidebar( 
				array (
					'name' => __( $sidebar, 'redcm' ),
					'id' =>	'sidebar-' . str_replace( ' ', '-', strtolower( $sidebar ) ), // replace all spaces with a dash
					'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
					'after_widget' => '</li>',
					'before_title' => '<h3 class="widget-title">',
					'after_title' => '</h3>'
					)
				);
			}
		}
	redcm_create_client_sidebars();
endif;
	


/* Sidebar Selector Script for Truly Dynamic Sidebars
 *
 * Uses Meta Box plugin by Riliwis ( http://www.deluxeblogtips.com/meta-box )
 *
 * This script will compile all the defined sidebars by the client and
 * create a custom meta box for the admin user to manage the sidebars on
 * a per page/post/cpt basis. Also adds an option for the admin user to 
 * add specific content above or below the sidebar on a per page basis.
 *
 */
 
if( !function_exists( 'redcm_sidebar_selector' ) ) {
	function redcm_sidebar_selector() {
		
		// Define the Post Types are we applying this script to. 
		// Custom Post Types are allowed!
		$allowed_post_types = array( 'page', 'post' );	
		
		// Call the globals needed
		global $the_sidebars_array; 
		
		// If you want to allow the user to select no sidebar, uncomment the next line
		// $the_sidebars_array[] = 'None';
		
		// Create Custom Meta Boxes for Our Post Types
		// This portion uses Meta Box plugin by Riliwis ( http://www.deluxeblogtips.com/meta-box )
		$prefix = '_redcm_meta_';
		global $meta_boxes;
		$meta_boxes[] = array(
			'id' => 'sidebar_options',
			'title' => 'Sidebar Options',
			'pages' => $allowed_post_types,
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				// Sidebar Selector
				array(
					'name'		=> 'Sidebar Selection',
					'id'		=> "{$prefix}sidebar",
					'type'		=> 'select',
					'options'	=> $the_sidebars_array,
					'std'		=> array( '0' ),
					'desc'		=> 'Select the sidebar to be shown for this page.'
				),
				// Extra Section to go above the page's sidebar
				array(
					'name'		=> '<b>Above Sidebar</b><br /><em>This WYSIWYG editor will be placed above the selected sidebar.</em>',
					'id'		=> $prefix . 'above_sidebar',
					'desc'		=> '',
					'type'		=> 'wysiwyg',
					'std'		=> ''
				),
				// Extra Section to go below the page's sidebar
				array(
					'name'		=> '<b>Below Sidebar</b><br /><em>This WYSIWYG editor will be placed below the selected sidebar.</em>',
					'id'		=> $prefix . 'below_sidebar',
					'desc'		=> '',
					'type'		=> 'wysiwyg',
					'std'		=> ''
				)	
			)
		);
	}
	// Run the function now.
	redcm_sidebar_selector();
}



/* Removing TwentyTen Sidebars
 * 
 * For the purpose of this demo, we are removing the Parent theme sidebars
 * The primary and secondary are the ones being replced with our scripts
 * The footer sidebars are being removed to keep the presentation clean.
 *
 */
function unregister_twentyten_sidebars () {
	unregister_sidebar( 'primary-widget-area' );
	unregister_sidebar( 'secondary-widget-area' );
	unregister_sidebar( 'first-footer-widget-area' );
	unregister_sidebar( 'second-footer-widget-area' );
	unregister_sidebar( 'third-footer-widget-area' );
	unregister_sidebar( 'fourth-footer-widget-area' );
}
add_action( 'widgets_init', 'unregister_twentyten_sidebars', 11 );


/* Widget Admin Dropdown Switch
 *
 * This script will compile all the widget sections on the Widgets Admin page into a dropdown menu
 * which allows you to focus on one widget area at a time. Very useful when you are managing a lot
 * of sidebars on your website.
 *
 * Modified from original script by SoulSizzle ( http://soulsizzle.com/jquery/manage-a-boatload-of-wordpress-sidebars-with-help-from-jquery/ )
 *
 * NOTE: Not Compatible with Widget Logic Plugin!
 *
 */
if( ! function_exists( 'redcm_admin_sidebar_switcher' ) ) :
	function redcm_admin_sidebar_switcher() {
		global $pagenow;
		if ($pagenow == 'widgets.php')
			wp_enqueue_script('redcm_admin_sidebar_switcher_script', get_bloginfo('stylesheet_directory').'/js/admin-sidebar-switcher.js');
	}
	add_action('admin_print_scripts', 'redcm_admin_sidebar_switcher');
endif;
?>