<?php
/*
Module Name: Remove category
Description: Enter comma separated category ids (be careful, there is no further check at this point).
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Input
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_filter('pre_get_posts', 'awcp_removeCategoryFromFeed' );


/*
* custom feed query
*/
function awcp_removeCategoryFromFeed($query) {
	if(is_feed()) {

		//get options
		$options = advancedwordpressconfigurationpluginOptions::getInstance();

		//get current option name
		$info =  get_file_data( __FILE__ , array('name' => 'Module Name'));
		$shortName = sanitize_file_name($info['name']);

		$query->set('cat',$options->options_rss['advanced_wordpress_configuration_plugin_'.$shortName]);
		return $query;
	}
}

?>