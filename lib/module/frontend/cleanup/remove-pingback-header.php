<?php
/*
Module Name: Remove the pingback header
Description: Unset the X-Pingback header.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter(	'wp_headers', create_function(	'$h','unset($h["X-Pingback"]); return $h;'	));