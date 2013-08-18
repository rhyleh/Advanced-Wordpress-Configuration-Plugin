<?php
/*
Module Name: Login access only
Description: Allow only logged in users to view the site.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: input
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_filter('get_header', 'awcp_loginAccessOnly');


/**
 *
 */
function awcp_loginAccessOnly() {
if( !is_user_logged_in() ) {
wp_redirect( site_url('/wp-login.php') );
}
}