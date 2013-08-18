<?php
/*
Module Name: Add new gravatar
Description: Path to "customavatar.png" based on template directory (e.g. images/ or lib/img/). Enter a "/" if the image resides directly in the theme folder.
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
add_filter('avatar_defaults', 'awcp_addNewGravatar' );



/**
 * adds a new default gravatar
 * @TODO: change url and name of gravatar
 */
function awcp_addNewGravatar ($avatar_defaults) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$info =  get_file_data( __FILE__ , array('name' => 'Module Name'));
	$shortName = sanitize_file_name($info['name']);

	if( strlen($options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName]) > 0 ) {

		$path = trailingslashit($options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName]);

		$myavatar =  get_bloginfo('template_directory').'/'.$path.'customavatar.png';
		$avatar_defaults[$myavatar] = "Custom"; 
	}

	return $avatar_defaults;
}