<?php
/*
Module Name: Adjust email address name
Description: Sets the name of the email sender.
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
add_filter('wp_mail_from_name','mail_from_name');


/**
 * [mail_from_name description]
 * @return [type] [description]
 */
function mail_from_name($sendername) {
	return $sendername;
}