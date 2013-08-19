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


remove_action( 'admin_menu', 'awcp_hideUpdateNag' );


/**
 * [awcp_hideUpdateNag description]
 * @return [type] [description]
 */
function awcp_hideUpdateNag() {
	remove_action( 'admin_notices', 'update_nag', 3 );
}