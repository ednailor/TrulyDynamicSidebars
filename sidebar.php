<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

<div id="primary" class="widget-area" role="complementary">
	<ul class="xoxo">

		<?php 
		/* Modified Section of Sidebar file
		 * We have replaced the theme's sidebar with the following code,
		 * which will determine which sidebar we should show, and if any
		 * additional above or below content is being shown.
		 *
		 * The script below first calls the globals already defined in the 
		 * lib/sidebars.php file, then grabs the post's meta. Once we have 
		 * this information, we use it to extract the chosen sidebar and 
		 * any content added above or below the sidebar itself.
		 *
		 */
		 
		// Call the globals to be used
		global $the_sidebars_array;
		
		// Get the Post Meta ( doing a general call so we don't have to make several calls for the data )
		$post_meta = get_post_meta( $post->ID );
			// $post_meta['_redcm_meta_sidebar'][0] is the chosen sidebar
			// $post_meta['_redcm_meta_above_sidebar'][0] is the content for above the sidebar
			// $post_meta['_redcm_meta_below_sidebar'][0] is the content for below the sidebar

			
		// Extract the chosen sidebar for this page, converting spaces to dashes and all lowercase
		$chosen_sidebar = str_replace( ' ', '-', strtolower( $the_sidebars_array[$post_meta['_redcm_meta_sidebar'][0]] ) );
				
		// Just in case we don't have a chosen sidebar, let's use the Default one. ( Useful for template pages, such as Archives, Index / Blog, ect )
		if( !$chosen_sidebar ) $chosen_sidebar = 'default-sidebar';
		
		// Display any Content for Above the Sidebar
		if( $post_meta['_redcm_meta_above_sidebar'][0] ) 
			echo '<li id="above-side-' . $post->ID . '" class="widget-container above-side widget">'.$post_meta['_redcm_meta_above_sidebar'][0].'</li>';
		
		// Display the chosen Sidebar
		if( $chosen_sidebar != 'none' ) 
			dynamic_sidebar( $chosen_sidebar );
		
		// Display any Content for Below the Sidebar
		if( $post_meta['_redcm_meta_below_sidebar'][0] ) 
			echo '<li id="below-side-' . $post->ID . '" class="widget-container below-side widget">'.$post_meta['_redcm_meta_below_sidebar'][0].'</li>';
		
		?>
	</ul>
</div><!-- #primary .widget-area -->