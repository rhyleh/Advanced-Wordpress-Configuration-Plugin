<?php
/*
Module Name: Remove from admin bar
Description: Select options to remove:
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Both
Type: selectmultiple
Options: wp-logo => Wordpress Logo, about =>   About Wordpress, wporg =>   Wordpress.org, documentation =>   Documentation, support-forums => Support Forum, feedback =>   Feedback, site-name => Site Name, view-site => View site, updates => Updates, comments => Comments, new-content => New content, w3tc => W3 Total Cache, wpseo-menu => Yoast SEO, my-account => My account
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */

add_action( 'wp_before_admin_bar_render', 'awcp_removeFromAdminBar' );




/*
* Remove the WordPress Logo from the WordPress Admin Bar
*/
function awcp_removeFromAdminBar() {
	global $wp_admin_bar;

	/* Aktiv und Admin? */
	if ( !is_admin_bar_showing() ) {
		return;
	}

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$info =  get_file_data( __FILE__ , array('name' => 'Module Name'));
	$shortName = sanitize_file_name($info['name']);

	$selectedOptions = explode( ',', $options->options_adminbar["advanced_wordpress_configuration_plugin_".$shortName] );

	foreach ($selectedOptions as $key => $value) {
		$wp_admin_bar->remove_menu(trim($value));
	}

}