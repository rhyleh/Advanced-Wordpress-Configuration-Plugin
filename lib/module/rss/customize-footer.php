<?php
/*
Module Name: Add text to RSS Footer
Description: Adds this string at the end of the feed.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Input
Class: Regular-Text
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('the_excerpt_rss', 'awcp_customizeRSSFooter' );
add_filter('the_content_feed', 'awcp_customizeRSSFooter' );


/**
 * [customizeRSSFooter description]
 * @param  [type] $content [description]
 * @return [type]          [description]
 */
function awcp_customizeRSSFooter($content) {
	if(is_feed()){

		//get options
		$options = advancedwordpressconfigurationpluginOptions::getInstance();

		//get current option name
		$shortName = $options->getShortName(__FILE__);

		if(strlen($options->options_rss['advanced_wordpress_configuration_plugin_'.$shortName]) > 0 ) $content .= $options->options_rss['advanced_wordpress_configuration_plugin_'.$shortName];
	}
	return $content;
}