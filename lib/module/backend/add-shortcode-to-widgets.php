<?php
/*
Module Name: Add shortcode to widgets
Description: Adds support for shortcodes in widgets.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Both
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('widget_text', 'do_shortcode', 11);