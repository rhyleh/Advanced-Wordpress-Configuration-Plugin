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


add_filter('body_class', 'awcp_addBodyClass' );


/**
 * [awcp_addBodyClass description]
 * @param  [type] $classes [description]
 * @return [type]          [description]
 */
function awcp_addBodyClass($classes) {

	  global $post;

    // add a class for the name of the page - later might want to remove the auto generated pageid class which isn't very useful
	if( is_page()) {
		$pn = $post->post_name;
		$classes[] = "page_".$pn;
	}

	// add a class for the parent page name
	$post_parent = get_post($post->post_parent);
	$parentSlug = $post_parent->post_name;

	if ( is_page() && $post->post_parent ) {
	    $classes[] = "parent_".$parentSlug;
	}

	// add class for the name of the custom template used (if any)
	$temp = get_page_template();
	if ( $temp != null ) {
		$path = pathinfo($temp);
		$tmp = $path['filename'] . "." . $path['extension'];
		$tn= str_replace(".php", "", $tmp);
		$classes[] = "template_".$tn;
	}

    return $classes;
}