<?php
/*
Module Name: Remove Wordpress version
Description: Completely remove wordpress versions in frontend output.
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
add_filter('the_generator', 'awcp_removeWordpressVersion' );


/**
 *
 */
function awcp_removeWordpressVersion() {
	return '';
}