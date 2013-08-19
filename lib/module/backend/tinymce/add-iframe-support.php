<?php
/*
Module Name: Add iFrame support
Description: Enables iFrame-support in TinyMCE (iframe-Tags will not get removed anymore).
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('tiny_mce_before_init', create_function( '$a', '$a["extended_valid_elements"] = "iframe[*]"; return $a;') );