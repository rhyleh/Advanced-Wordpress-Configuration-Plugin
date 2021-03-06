<?php
/*
Module Name: Remove contact fields
Description: Please choose which fields to remove:
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: selectmultiple
Options: aim => AIM, yim => Yahoo IM, jabber => Jabber, url => URL, googleplus => Google+, twitter => Twitter, facebook => Facebook
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('user_contactmethods', 'awcp_removeUserContactFields');


/**
 * [removeUserContactFields description]
 * @param  [type] $contactmethods [description]
 * @return [type]                 [description]
 */
function awcp_removeUserContactFields($contactmethods) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	if(strlen($options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName]) > 0 ) {

		$selectedOptions = explode( ',', $options->options_backend["advanced_wordpress_configuration_plugin_".$shortName] );

		foreach ($selectedOptions as $key => $value) {
			unset($contactmethods[trim($value)]);
		}

	}

	return $contactmethods;
}