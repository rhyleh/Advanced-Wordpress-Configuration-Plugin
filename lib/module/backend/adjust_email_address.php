<?php
/*
Module Name: Adjust email address
Description: Set from email address to a different address than admin email address.
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


add_filter('wp_mail_from','awcp_emailAddress');


/**
 * [awcp_emailAddress description]
 * @param  [type] $emailaddress [description]
 * @return [type]               [description]
 */
function awcp_emailAddress($emailaddress) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	if(strlen($options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName]) > 0) {
		return $options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName];
	} else {
		return $emailaddress;
	}
}