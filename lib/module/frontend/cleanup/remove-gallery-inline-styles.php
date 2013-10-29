<?php
/*
Module Name: Remove gallery inline styles
Description: Remove the default inline styles for galleries.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter( 'use_default_gallery_style', '__return_false' );