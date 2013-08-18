<?php
/*
Module Name: Hide post format UI
Description: Hides the post format UI for all users(new in WordPress 3.6).
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_filter( 'enable_post_format_ui', '__return_false' );