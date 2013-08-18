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



/**
 * register the filters - all set via options page
 */
add_filter('admin_footer_text', 'awcp_backendChangeFooter');


/**
 * sets the footer information in backend
 */
function awcp_backendChangeFooter () {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$info =  get_file_data( __FILE__ , array('name' => 'Module Name'));
	$shortName = sanitize_file_name($info['name']);

	echo $options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName];
}