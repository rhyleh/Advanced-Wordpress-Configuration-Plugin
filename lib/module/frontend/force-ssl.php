<?php
/*
Module Name: Force SSL
Description: TODO: funktion wird nicht aufgerufen! Enter a comma-separated list of post- and page-ids to be delivered via ssl.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Input
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('force_ssl' , 'awcp_ForceSsl', 1, 3);


/**
 * Source: http://wpsnipp.com/index.php/functions-php/force-specific-pages-to-be-secure-ssl-https/
 * @param  [type]  $force_ssl [description]
 * @param  integer $post_id   [description]
 * @param  string  $url       [description]
 * @return [type]             [description]
 */
function awcp_ForceSsl( $force_ssl, $post_id = 0, $url = '' ) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	if( strlen($options->options_frontend['advanced_wordpress_configuration_plugin_'.$shortName]) > 0 ) {

		$ids = array_map('trim', explode(",", $options->options_frontend['advanced_wordpress_configuration_plugin_'.$shortName]));

		if( is_array($ids) ) {			
			if(in_array($post_id, $ids )) {
				return true;
			}
		}
	}

    return $force_ssl;
}