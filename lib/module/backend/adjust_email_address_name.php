<?php
/*
Module Name: Adjust email address name
Description: Sets the name of the email sender.
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


add_filter('wp_mail_from_name','awcp_mailFromName');


/**
 * [mail_from_name description]
 * @return [type] [description]
 */
function awcp_mailFromName($sendername) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	if(strlen($options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName]) > 0) {
		return $options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName];
	} else {
		return $sendername;
	}
}