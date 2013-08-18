<?php
/*
Module Name: Disable WP Auto P
Description: Disable automatic Paragraph-Tag addition by Wordpress.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
remove_filter ('the_content',  'wpautop');