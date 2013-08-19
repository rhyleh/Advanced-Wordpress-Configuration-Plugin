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


add_action( 'admin_head', 'awcp_hideHelpTab' );


/**
 * just adds a style, could be added to admin stylesheet
 * @return [type] [description]
 */
function awcp_hideHelpTab() {
    echo '<style type="text/css">
            #contextual-help-link-wrap { display: none !important; }
          </style>';
}