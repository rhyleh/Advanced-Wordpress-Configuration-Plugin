<?php
/*
Module Name: Remove post features
Description: Remove support for these post features:
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: selectmultiple
Options: title => Post title, editor => Content Editor, author => Author, thumbnail => Featured image, excerpt => Excerpt, trackbacks => Trackbacks, custom-fields => Custom fields, comments => Comments, revisions => Revisions, page-attributes => Template and menu order
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_action( 'admin_init', 'awcp_postSupport', 10 );


/**
 * Source: http://wpsnipp.com/index.php/functions-php/remove-support-for-specific-post-type-features/
 * @return [type] [description]
 */
function awcp_postSupport() {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	if( $options->options_backend["advanced_wordpress_configuration_plugin_".$shortName] ) {
		
		$selectedOptions = explode( ',', $options->options_backend["advanced_wordpress_configuration_plugin_".$shortName] );

		foreach ($selectedOptions as $key => $value) {

			remove_post_type_support( 'post', $value );
		}
	}
}