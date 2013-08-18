<?php
/*
Module Name: Correct tags for custom taxonomies
Description: The links of regular tags doesnt work with Custom Post Types. This fixes it.
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
add_filter('request', 'awcp_correctTagForCustomTaxonomies' );



/**
 * The links of regular tags doesnt work with Custom Post Types. This fixes it.
 * @param  [type] $request [description]
 */
function awcp_correctTagForCustomTaxonomies($request) {
	if ( isset($request['tag']) )
		$request['post_type'] = 'any';

	return $request;
}