<?php
/*
Module Name: Disable comments on pages
Description: Turn off comments on pages.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}

add_filter('comments_open', 'awcp_disableCommentsOnPages');


/**
 * Copied from WP Helpers
 * @param  [type] $fields [description]
 * @return [type]         [description]
 */
function awcp_disableCommentsOnPages( $open ){
	if ('page' == get_post_type()) {
		return false;
	}

	return $open;
}