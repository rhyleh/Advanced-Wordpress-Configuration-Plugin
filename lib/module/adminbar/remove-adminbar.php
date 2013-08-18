<?php
/*
Module Name: Remove admin bar
Description: * Options need to be set directly in plugin: remove for all users or all users except admins. Default: all users except admins.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Both
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_filter( 'show_admin_bar', 'awcp_removeAdminbar' );



/**
 * Completely disables the admin bar
 * @return [type] [description]
 */
function awcp_removeAdminbar() {

	return false; //for all users

	//return ( current_user_can( 'administrator' ) ) ? $content : false; //except for the admins
}