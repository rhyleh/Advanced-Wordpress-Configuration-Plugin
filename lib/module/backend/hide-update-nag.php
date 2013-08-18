<?php
/*
Module Name: Hide Update Notification
Description: For all users.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
remove_action( 'admin_menu', 'awcp_hideUpdateNag' );



/**
 *
 */
function awcp_hideUpdateNag() {
	remove_action( 'admin_notices', 'update_nag', 3 );
}