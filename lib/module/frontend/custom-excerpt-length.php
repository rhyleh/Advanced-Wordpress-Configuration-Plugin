<?php
/*
Module Name: Sets a new excerpt length
Description: Set new excerpt length (integer). Wordpress default: 55 words.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: number
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_filter( 'excerpt_length', 'awcp_customExcerptLength' );


/**
 * sets the length of the excerpt (for get_the_excerpt)
 */
function awcp_customExcerptLength( $length ) {
	//the amount of words to return

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$info =  get_file_data( __FILE__ , array('name' => 'Module Name'));
	$shortName = sanitize_file_name($info['name']);

	if( intval($options->options_frontend["advanced_wordpress_configuration_plugin_".$shortName]) > 0 ) return intval($options->options_frontend["advanced_wordpress_configuration_plugin_".$shortName]);
}