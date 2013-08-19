<?php
/*
Module Name: Set footer text
Description: Leave empty for standard thank you text.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Input
Class: regular-text
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('admin_footer_text', 'awcp_backendChangeFooter');


/**
 * sets the footer information in backend
 * @param  [type] $text [description]
 * @return [type]       [description]
 */
function awcp_backendChangeFooter($text) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	if(strlen($options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName]) > 0 ) {
		echo $options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName];
	} else {
		return $text;
	}
}