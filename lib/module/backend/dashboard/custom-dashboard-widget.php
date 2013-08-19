<?php
/*
Module Name: Customize dashboard widgets
Description: Select which dashboard to remove (for all users):
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: selectmultiple
Options: dashboard_quick_press => Dashboard: QuickPress, dashboard_plugins => Dashboard: Plugins, dashboard_recent_comments => Dashboard: Recent Comments, dashboard_primary => Dashboard: Wordpress Blog, dashboard_secondary => Dashboard: Weitere Wordpress, dashboard_incoming_links => Dashboard: Incoming Links, dashboard_right_now => Dashboard:  Right now, dashboard_recent_drafts => Dashboard: Recent Drafts, yoast_db_widget => Plugin: Yoast SEO
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_action('wp_dashboard_setup', 'awcp_customizeDashboardWidgets' );


/**
 * removes default widgets from dashboard and adds new widgets
 * @return [type] [description]
 */
function awcp_customizeDashboardWidgets() {

	global $wp_meta_boxes;

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	$selectedOptions = explode( ',', $options->options_backend["advanced_wordpress_configuration_plugin_".$shortName] );

	if( is_array($selectedOptions) ) {
		foreach ($selectedOptions as $key => $value) {
			remove_meta_box( trim($value), 'dashboard', 'core');
		}
	}
}