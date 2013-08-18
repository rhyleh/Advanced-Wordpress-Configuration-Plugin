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



/**
 * register the filters - all set via options page
 */
add_filter('manage_posts_columns', 'awcp_removePostColumns' );


/**
 * removes backend columns from post view
 */
function awcp_removePostColumns($defaults) {

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