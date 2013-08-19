<?php
/*
Module Name: Removes pages backend columns
Description: Select columns to remove from pages:
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: selectmultiple
Options: comments => Comments, categories => Categories, author => Author, date => Date
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('manage_pages_columns', 'awcp_removePageColumns' );


/**
 * removes backend columns from page view
 * @param  [type] $defaults [description]
 * @return [type]           [description]
 */
function awcp_removePageColumns($defaults) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	$selectedOptions = explode( ',', $options->options_backend["advanced_wordpress_configuration_plugin_".$shortName] );

	if( is_array($selectedOptions) ) {
		foreach ($selectedOptions as $key => $value) {
			unset($defaults[trim($value)]);
		}
	}

	return $defaults;
}