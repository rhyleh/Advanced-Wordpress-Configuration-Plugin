<?php
/*
Module Name: Keep user logged in
Description: Enter in seconds: year: 31556926, month: 2676400
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Number
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter( 'auth_cookie_expiration', 'awcp_keepUserLoggedIn' );


/**
 * [awcp_keepUserLoggedIn description]
 * @param  [type] $expirein [description]
 * @return [type]           [description]
 */
function awcp_keepUserLoggedIn( $expirein ) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	if( intval($options->options_frontend["advanced_wordpress_configuration_plugin_".$shortName]) > 0 ) {
		return intval($options->options_frontend["advanced_wordpress_configuration_plugin_".$shortName]);
	}
}