<?php
/*
Module Name: Remove login errors
Description: Removes any login error messages.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('login_errors', create_function('$a', "return null;"));