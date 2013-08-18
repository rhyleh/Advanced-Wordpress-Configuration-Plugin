<?php
/*
Module Name: Disable RSS Feeds
Description: Completely disables all RSS feeds.
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
add_action('do_feed', 'awcp_disableRSS', 1);
add_action('do_feed_rdf', 'awcp_disableRSS', 1);
add_action('do_feed_rss', 'awcp_disableRSS', 1);
add_action('do_feed_rss2', 'awcp_disableRSS', 1);
add_action('do_feed_atom', 'awcp_disableRSS', 1);


/**
 * disables the RSS feed
 *
 */
function awcp_disableRSS() {	
	wp_die( __('No feed available, please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
}

?>