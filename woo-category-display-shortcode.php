<?php
/*
Plugin Name: Woo Category Display Shortcode
Plugin URI: -
Description: Display WooCommerce categories via shortcode. Several options are included.
Author: Mo
Version: 1.0.2
Author URI: -
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {


	/**
	 * Enqueue the frontend stylesheet
	 **/
	add_action('wp_enqueue_scripts', 'woo_cds_frontend_enqueue', 99);
	function woo_cds_frontend_enqueue() {
		// Dont load these scripts in the admin backend
	    if ( ! is_admin() ) { 

	    	// Check if the file "woo-cat-display.css" exists in theme folder
	    	if (file_exists( get_stylesheet_directory() . '/woo-cat-display.css' )) {
	    		wp_enqueue_style( 'woo-cds-plugin', get_stylesheet_directory_uri() . '/woo-cat-display.css' );
	    	} else {
	    	// If there is no css file in the theme folder load the default one
	    		wp_enqueue_style( 'woo-cds-plugin', plugins_url('/woo-cat-display.css', __FILE__) );
	    	}

	    }// END: ! is_admin
	}// END: "function woo_cds_frontend_enqueue"


	/**
	 * Frontpage template function
	 **/
	function woo_cds_template($atts) {

		/* Get all shortcode variables */
		$atts = shortcode_atts(array(

			// Display attributes
			'show_link'		=> 'true',
			'show_title'	=> 'true',
			'show_desc'		=> 'true',
			'show_img'		=> 'true',
			'show_count'	=> 'true',

			// Query attributes
	    	'orderby' 		=> 'name', // id / count / name* / slug / term_group / none
	    	'order' 		=> 'ASC', // ASC* / DESC
	    	'hide_empty' 	=> 1, // 1* / 0
	    	'include'		=> '',
	    	'exclude' 		=> '', // An array of term ids to exclude. Also accepts a string of comma-separated ids
	    	'exclude_tree'	=> '', // An array of parent term ids to exclude 
	    	'number' 		=> '', // The maximum number of terms to return. Default is to return them all.
	    	'slug' 			=> '', // Returns terms whose "slug" matches this value. Default is empty string.
	    	'parent' 		=> '', // Get direct children of this term (only terms whose explicit parent is this value). If 0 is passed, only top-level terms are returned. Default is an empty string.
	    	'child_of' 		=> 0, // Get all descendents of this term. (as many levels as are available) 
	    	'childless' 	=> false, // Returns terms that have no children if taxonomy is hierarchical, all terms if taxonomy is not hierarchical  
	    
	    ), $atts );
	    extract( $atts );

	    $terms_args = array(
		    'orderby'		=> $orderby, 	
		    'order'			=> $order,		 
		    'hide_empty'	=> $hide_empty,  
		    'include'		=> $include,
		    'exclude'		=> $exclude,
		    'exclude_tree'	=> $exclude_tree, 
		    'number'		=> $number, 	
		    'slug'			=> $slug,		 
		    'parent'		=> $parent, 	 
		    'child_of'		=> $child_of,
		    'childless'		=> $childless,
		);
	    $terms = get_terms('product_cat', $terms_args);

	    // Check if there are any terms
	    if ($terms) {

	    	echo '<ul class="woo-category-list clearfix">';

		    foreach ($terms as $term) {

		    	$term_id 		= $term->term_id; // Term ID
		    	$term_slug 		= $term->slug; // Term slug
		    	$term_name 		= $term->name; // Term name
		    	$term_desc 		= $term->description; // Term description
		    	$term_count 	= $term->count; // Term count
		    	$term_link 		= get_term_link($term->slug, $term->taxonomy); // Term link
		    	$img_id 		= get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ); // Term image ID
		    	$term_img 		= wp_get_attachment_url( $img_id );	// Term image

		    	// Start the template for each term

		    	echo '<li id="term-'.$term_slug.'" class="woo-category term-'.$term_id.'">';

		    		// If show_link = true, display link-wrapper
		    		if ($show_link == 'true') {
		    			echo '<a href="'.esc_url($term_link).'" title="'.$term_name.'">';
					}

					// If show_title OR show_desc = true, display the term-content div
			    	if ($show_title == 'true' || $show_desc == 'true') {

				    	echo '<div class="term-content">';

				    		// If show_title = true, display the title
					    	if ($show_title == 'true') {
					    		echo '<h3 class="term-title">'.$term_name.'</h3>';
					    	}

					    	if ( $term_count > 0 && $show_count == 'true' ) {
								echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . $term_count . ')</mark>', $term );
					    	}

					    	// If show_desc = true, display description
					    	if ($show_desc == 'true') {
					    		// Checks if a description exists
					    		if ($term_desc) { echo '<p>'.$term_desc.'</p>'; }
					    	}

				    	echo '</div>';

				    }// END: show_title OR show_desc

				    	// If show_img = true, display the image
			    		if ($show_img == 'true') {
			    			// Checks if an image exists
			    			if ($term_img) { 
			    				echo '<div class="term-img"><img src="'.$term_img.'" title="'.$term_name.'" alt="'.$term_name.'" /></div>'; 
			    			}
			    		}

			    	// If show_link = true, display link-wrapper
			    	if ($show_link == 'true') {	echo '</a>'; }

		    	echo '</li>';

			} // END: foreach ($terms as $term)

		    echo '</ul>';

	    }// END: if $terms

	};// END: "function woo_cds_template"


	/**
	 * Create shortcode
	 *
	 * Use: [woo-categories]
	 *
	 **/
	function woo_cds_shortcode($atts, $content = null){
		ob_start();
			$content = woo_cds_template($atts);
			$content = ob_get_contents();
		ob_end_clean();
		 
		return $content;
	}// END: "function woo_cds_shortcode"
	add_shortcode('woo-categories', 'woo_cds_shortcode');

}// END: WC check