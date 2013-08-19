<?php
/*
Module Name: Removes color scheme picker
Description: Removes the color picker on user profil page.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );