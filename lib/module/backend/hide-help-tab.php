<?php
/*
Module Name: Hide help tab from admin panel
Description: Removes the help tab (via CSS).
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_action( 'admin_head', 'awcp_hideHelpTab' );


/**
 * just adds a style, could be added to admin stylesheet
 */
function awcp_hideHelpTab() {
    echo '<style type="text/css">
            #contextual-help-link-wrap { display: none !important; }
          </style>';
}