<?php
/*
Module Name: Replace read more
Description: Replaces the"Read more" link by a clickable link. Enter the link-text here. 
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Input
Class: regular-text
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('excerpt_more', 'awcp_removeExcerptMore' );


/**
 * [awcp_removeExcerptMore description]
 * @param  [type] $more [description]
 * @return [type]       [description]
 */
function awcp_removeExcerptMore($more) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	if(strlen($options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName]) > 0) {
		return '<span class="excerpt_more">
					<a href="'.get_permalink().'">'.$options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName].'</a>
				</span>';
	} else {
		return $more;
	}
}