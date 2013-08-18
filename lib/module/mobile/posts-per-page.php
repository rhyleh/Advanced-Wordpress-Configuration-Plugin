<?php
/*
Module Name: Mobile posts per page
Description: Sets post per page for mobile devices (usually less posts than for desktops). Based on wp_is_mobile() which is true for mobile devices and tablets.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Number
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_action('pre_get_posts', 'awcp_customPostsPerPage');



/**
 * Custom posts per page for mobile devices
 *
 */
function awcp_customPostsPerPage($query) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$info =  get_file_data( __FILE__ , array('name' => 'Module Name'));
	$shortName = sanitize_file_name($info['name']);

	$number = (int) $options->options_mobile["advanced_wordpress_configuration_plugin_".$shortName];

	if(is_int($number)) {

		if(wp_is_mobile()) {

			if(is_home()){
			    $query->set('posts_per_page', $number);
			}
			
			if(is_search()){
			    $query->set('posts_per_page', -1);
			}
			
			if(is_archive()){
				$query->set('posts_per_page', $number);
			}

		}

	}
}

?>