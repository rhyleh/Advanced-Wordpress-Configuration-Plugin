<?php
/*
Module Name: Remove recent comment widget style
Description: Remove the inline style inserted by the recent comment widget (use this only if you use the recent comments widget).
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Both
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_action( 'widgets_init', 'awcp_removeRecentCommentsWidgetStyle' );


/**
 * 
 */
function awcp_removeRecentCommentsWidgetStyle() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'  ) );
}