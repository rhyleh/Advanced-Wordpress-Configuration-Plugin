<?php
/*
Module Name: Customize backend
Description: Enter the path to the CSS file (wp-admin.css). Path is based on current theme (enter library/css/ for example). For example use "#header-logo" to set up a custom logo. If wp-admin.css resides directly in the theme folder enter a "/".
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
add_action('admin_head', 'awcp_customBackend');


/**
 * includes an additional stylesheet in backend
 * @TODO: change path to css file
 */
function awcp_customBackend() {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$info =  get_file_data( __FILE__ , array('name' => 'Module Name'));
	$shortName = sanitize_file_name($info['name']);

	if(strlen($options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName]) > 0 ) {

		$path = trailingslashit($options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName]);

		echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/'.$path.'wp-admin.css" />';
	}
}