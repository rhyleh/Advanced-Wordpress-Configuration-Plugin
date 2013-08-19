<?php
/*
Module Name: De-register plugin scripts
Description: Plugins like to add their own scripts. To save on HTTP requests the scripts should be concatened and minified. If you serve an optimized version of your javascripts, remove the scripts added via wp_enqueue_script here. Enter a comma separated list.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: input
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_action( 'wp_print_styles', 'awcp_deregisterPluginScripts', 100 );


/**
 * [copied from http://www.stephanmuller.nl/removing-wordpress-plugin-style-scripts-head/
 * @return [type] [description]
 */
function awcp_deregisterPluginScripts() {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	$handles = array_map('trim', explode(",", $options->options_frontend['advanced_wordpress_configuration_plugin_'.$shortName]));

	if( is_array($handles) ) {
		foreach($handles as $handle){
			wp_deregister_script( $handle);
		}
	}
}