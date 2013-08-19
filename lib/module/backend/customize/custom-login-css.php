<?php
/*
Module Name: Custom login CSS
Description: Enter the path to the CSS file (wp-login.css). Path is based on current theme (enter library/css/ for example).
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Input
Class: regular-text
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_action('login_head', 'awcp_customLoginCSS' );


/**
 * includes an additional stylesheet on backend login page
 * @return [type] [description]
 */
function awcp_customLoginCSS() {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	if(strlen($options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName]) > 0 ) {

		$path = trailingslashit($options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName]);

		echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/'.$path.'wp-login.css" />';
	}
}