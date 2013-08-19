<?php
/*
Module Name: Remove posts backend columns 
Description: Select columns to remove from posts:
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


add_filter('manage_posts_columns', 'awcp_removePostColumns' );


/**
 * removes backend columns from post view
 * @param  [type] $defaults [description]
 * @return [type]           [description]
 */
function awcp_removePostColumns($defaults) {

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