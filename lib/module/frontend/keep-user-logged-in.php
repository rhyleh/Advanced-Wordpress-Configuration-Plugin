<?php
/*
Module Name: Keep user logged in
Description: Enter in seconds: year: 31556926, month: 2676400
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: input
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_filter( 'auth_cookie_expiration', 'awcp_keepUserLoggedIn' );


/**
 *
 */
function awcp_keepUserLoggedIn( $expirein ) {
	//year: 31556926
	//month: 2676400

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$info =  get_file_data( __FILE__ , array('name' => 'Module Name'));
	$shortName = sanitize_file_name($info['name']);

	if( intval($options->options_frontend["advanced_wordpress_configuration_plugin_".$shortName]) > 0 ) return intval($options->options_frontend["advanced_wordpress_configuration_plugin_".$shortName]);
}