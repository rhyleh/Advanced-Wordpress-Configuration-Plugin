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


add_action( 'admin_init', 'awcp_disableDragDashboardWidgets' );


/**
 * [awcp_disableDragDashboardWidgets description]
 * @return [type] [description]
 */
function awcp_disableDragDashboardWidgets() {
	wp_deregister_script('postbox');
}