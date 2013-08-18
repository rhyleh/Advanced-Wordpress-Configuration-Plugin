<?php
/*
Module Name: Add thumbnail image
Description: Adds the thumbnail picture to RSS Feeds.
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
add_filter('the_excerpt_rss', 'awcp_addThumbnailToRSS' );
add_filter('the_content_feed', 'awcp_addThumbnailToRSS' );


/**
 * adds the thumbnail picture to the RSS feed
 *
 */
function awcp_addThumbnailToRSS($content) {
	global $post;
	if(has_post_thumbnail($post->ID)) {
	$content = '<p>' . get_the_post_thumbnail($post->ID) .
	'</p>' . get_the_content();
	}
	return $content;
}

?>