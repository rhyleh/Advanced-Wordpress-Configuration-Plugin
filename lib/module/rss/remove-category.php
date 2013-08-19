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


add_filter('pre_get_posts', 'awcp_removeCategoryFromFeed' );


/**
 * custom feed query
 * @param  [type] $query [description]
 * @return [type]        [description]
 */
function awcp_removeCategoryFromFeed($query) {
	if(is_feed()) {

		//get options
		$options = advancedwordpressconfigurationpluginOptions::getInstance();

		//get current option name
		$shortName = $options->getShortName(__FILE__);

		$query->set('cat',$options->options_rss['advanced_wordpress_configuration_plugin_'.$shortName]);
		return $query;
	}
}