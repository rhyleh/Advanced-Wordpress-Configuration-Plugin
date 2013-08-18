<?php
/*
Module Name: Search results per page
Description: Enter any number here. Wordpress default: 10.
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
add_action('pre_get_posts', 'awcp_setSearchResultsPerPage' );



/**
 * change amount of posts on the search page - set here to 100
 * @param [type] $query [description]
 */
function awcp_setSearchResultsPerPage( $query ) {
	global $wp_the_query;
	if ( ( ! is_admin() ) && ( $query === $wp_the_query ) && ( $query->is_search() ) ) {

		//get options
		$options = advancedwordpressconfigurationpluginOptions::getInstance();

		//get current option name
		$info =  get_file_data( __FILE__ , array('name' => 'Module Name'));
		$shortName = sanitize_file_name($info['name']);

		$query->set( 'wpfme_search_results_per_page', $options->options_frontend["advanced_wordpress_configuration_plugin_".$shortName] );
	}
	return $query;
}