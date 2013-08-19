<?php
/*
Module Name: Sets a new excerpt length
Description: Set new excerpt length (integer). Wordpress default: 55 words.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Number
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter( 'excerpt_length', 'awcp_customExcerptLength' );


/**
 * sets the length of the excerpt (for get_the_excerpt)
 * @param  [type] $length [description]
 * @return [type]         [description]
 */
function awcp_customExcerptLength( $length ) {
	//the amount of words to return

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	if( intval($options->options_frontend["advanced_wordpress_configuration_plugin_".$shortName]) > 0 ) {
		return intval($options->options_frontend["advanced_wordpress_configuration_plugin_".$shortName]);
	}
}