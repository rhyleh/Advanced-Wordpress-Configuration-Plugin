<?php
/*
Module Name: Restrict backend access
Description: Restrict the backend access to certain user groups.
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
add_action('admin_init', 'awcp_restrictBackendAccess', 1);


/**
 * [awcp_restrictBackendAccess description]
 * @return [type] [description]
 */
function awcp_restrictBackendAccess(){
	global $current_user;
	get_currentuserinfo();

	if ($current_user->user_level <  4) { //if not admin, die with message
		wp_redirect( get_bloginfo('url') );
		exit;
	}

}