<?php
/*
Module Name: Change the Wordpress Version
Description: Changes the version info in bottom right footer.
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
add_filter('update_footer', 'awcp_backendChangeFooterVersion', 9999 );


/**
	 * sets the footer information in backend
 */
function awcp_backendChangeFooterVersion($text) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$info =  get_file_data( __FILE__ , array('name' => 'Module Name'));
	$shortName = sanitize_file_name($info['name']);

	if(strlen($options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName]) > 0 ) {
		return $options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName];
	} else {
		return $text;
	}
}