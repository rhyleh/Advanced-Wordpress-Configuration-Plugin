<?php
/*
Module Name: Load jQuery from CDN
Description: Loads jQuery from Google CDN, version: 1.10.2
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_action( 'init', 'awcp_jqueryFromCdn' );


/**
 *
 */
function awcp_jqueryFromCdn() {

	if ( !is_admin() ) {

		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', ( 'https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js' ), false, null, true );
		wp_enqueue_script( 'jquery' );
	}
}