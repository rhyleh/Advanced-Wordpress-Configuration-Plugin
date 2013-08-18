<?php
/*
Module Name: Removes the read more link
Description: No description yet.
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
add_filter('excerpt_more', 'awcp_removeExcerptMore' );


/**
	 * remove "Read more" link and simply display three dots
 *
 */
function awcp_removeExcerptMore($more) {
	return '...';
}