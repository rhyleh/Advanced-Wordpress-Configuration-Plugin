<?php
/*
Module Name: Defer Javascript
Description: Be careful with this setting: not every plugin is well-programmed and may stumble here...
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter( 'clean_url', 'awcp_deferJavascript', 99, 1);


/**
 * adds defer='defer' to javascript in header to defer loading
 * @param  [type] $file [description]
 * @return [type]       [description]
 */
function awcp_deferJavascript($file) {
	if ( strpos($file, '.js') !== false ) {
		return sprintf(
			"%s' defer='defer",
			$file
		);
	}

	return $file;
}
