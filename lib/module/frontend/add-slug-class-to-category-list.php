<?php
/*
Module Name: List categories with slug
Description: Adds the category slug as a class to wp_list_categories.
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
add_filter('wp_list_categories', 'awcp_addSlugClassToCategoryList' );



/**
 * adds the category id to wp_list_categories
 */

function awcp_addSlugClassToCategoryList($list) {

	$cats = get_categories('hide_empty=0');
		foreach($cats as $cat) {
			$find = 'cat-item-' . $cat->term_id . '"';
			$replace = 'cat-item-' . $cat->slug . ' cat-item-' . $cat->term_id . '"';
			$list = str_replace( $find, $replace, $list );

		}

	return $list;
}