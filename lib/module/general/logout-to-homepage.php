<?php
/*
Module Name: Logout to homepage
Description: Logout redirects to homepage.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Both
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('logout_url', 'awcp_logoutToHomepage', 10, 2 );


/**
 * Returns the user back to the homepage on logout from backend
 * @param  [type] $logouturl [description]
 * @param  [type] $redir     [description]
 */
function awcp_logoutToHomepage($logouturl, $redir) {
	$redir = get_option('siteurl');
	return $logouturl . '&amp;redirect_to=' . urlencode($redir);
}