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



/**
 * register the filters - all set via options page
 */
add_filter('manage_pages_columns', 'awcp_removePageColumns' );


/**
 * removes backend columns from page view
 */
function awcp_removePageColumns($defaults) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$info =  get_file_data( __FILE__ , array('name' => 'Module Name'));
	$shortName = sanitize_file_name($info['name']);

	$selectedOptions = explode( ',', $options->options_backend["advanced_wordpress_configuration_plugin_".$shortName] );

	foreach ($selectedOptions as $key => $value) {
		unset($defaults[trim($value)]);
	}

	return $defaults;
}