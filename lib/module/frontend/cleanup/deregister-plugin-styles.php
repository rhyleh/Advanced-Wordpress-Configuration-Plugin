<?php
/*
Module Name: De-register plugin styles
Description: Plugins like to add their own styles. To save on HTTP requests the styles should be copied to the template stylesheet. To remove styles enqueed via wp_enqueue_style enter them here in a comma separated list.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Input
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_action( 'wp_print_styles', 'awcp_deregisterPluginStyles', 100 );


/**
 * copied from http://www.stephanmuller.nl/removing-wordpress-plugin-style-scripts-head/
 * @return [type] [description]
 */
function awcp_deregisterPluginStyles() {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	$handles = array_map('trim', explode(",", $options->options_frontend['advanced_wordpress_configuration_plugin_'.$shortName]));

	if( is_array($handles) ) {
		foreach($handles as $handle){
			wp_deregister_style( $handle);
		}
	}
}