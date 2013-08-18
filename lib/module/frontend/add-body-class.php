<?php
/*
Module Name: Add body class
Description: Adds a meaningful body class
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_filter('body_class', 'awcp_addBodyClass' );


/**
 *
 */
function awcp_addBodyClass($classes) {
	global $wpdb, $post;
	if (is_page()) {
	    if ($post->post_parent) {
			$parent  = end(get_post_ancestors($current_page_id));
	    } else {
			$parent = $post->ID;
	    }
	    $post_data = get_post($parent, ARRAY_A);
	    $classes[] = 'section-' . $post_data['post_name'];
	}
	return $classes;
}