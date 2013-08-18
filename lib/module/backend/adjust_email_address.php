<?php
/*
Module Name: Adjust email address
Description: Set from email address to a different address than admin email address.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Input
Class: regular-text
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_filter('wp_mail_from','awcp_emailAddress');


/**
 * [awcp_emailAddress description]
 * @param  [type] $emailaddress [description]
 * @return [type]               [description]
 */
function awcp_emailAddress($emailaddress) {
	return $emailaddress;
}