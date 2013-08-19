<?php
/*
Module Name: Add contact fields
Description: Enter field names here (fields will be available as url-encoded and lowercased, e.g. "Telephone Number" will be available as "telephone+number" or "Öffentliches Profil" will be available as "%C3%96ffentliches+profil").
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Input
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('user_contactmethods', 'awcp_addUserContactFields');


/**
 * Customize User Contact Info
 * @param  [type] $contactmethods [description]
 * @return [type]                 [description]
 */
function awcp_addUserContactFields($contactmethods) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	if(strlen($options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName]) > 0 ) {

		$contactFields = array_map('trim', explode(",", $options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName]));

		if( is_array($contactFields) ) {
			foreach ($contactFields as &$value) {
				$contactmethods[urlencode(strtolower($value))] = $value;
			}
		}
	}

	return $contactmethods;
}