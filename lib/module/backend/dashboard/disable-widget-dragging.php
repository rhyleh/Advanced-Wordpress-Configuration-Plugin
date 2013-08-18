<?php
/*
Module Name: Disable Widget dragging
Description: Disables the dragging of metaboxes in dashboard. 
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
add_action( 'admin_init', 'awcp_disableDragDashboardWidgets' );


/**
 *
 */
function awcp_disableDragDashboardWidgets() {
	wp_deregister_script('postbox');
}