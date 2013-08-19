<?php
/*
Module Name: Remove admin bar
Description: Remove admin bar for all users or all users except admins.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Both
Type: selectmultiple
Options: all => Everyone, users => Users

*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter( 'show_admin_bar', 'awcp_removeAdminbar' );


/**
 * Completely disables the admin bar
 * @return [type] [description]
 */
function awcp_removeAdminbar($content) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	$selectedOptions = explode( ',', $options->options_adminbar["advanced_wordpress_configuration_plugin_".$shortName] );

	if(is_array($selectedOptions)) {

		if( in_array ( 'all' , $selectedOptions ) ) {
			return false;
		} else {
			return ( current_user_can( 'manage_options' ) ) ? $content : false;
		}
	}

	return false;
}