<?php
/*
Module Name: Delay Publishing
Description: Delays publishing by x minutes.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Both
Type: Select
Options: false => no delay, 10 => 10 Minutes, 20 => 20 Minutes, 30 => 30 Minutes
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('posts_where', 'awcp_delayPublishRSS' );


/**
 * all bloggers make errors that we catch after we publish the post. Sometimes even within the next minute or two. That is why it is best that we delay our posts to be published on the RSS by 5-10 minutes.
 * @param  [type] $where [description]
 * @return [type]        [description]
 */
function awcp_delayPublishRSS($where) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	if( $options->options_rss["advanced_wordpress_configuration_plugin_".$shortName] !== 'false' ) {

		global $wpdb;

		if ( is_feed() ) {
			// timestamp in WP-format
			$now = gmdate('Y-m-d H:i:s');

			// value for wait; 
			$time = ( intval($options->options_rss["advanced_wordpress_configuration_plugin_".$shortName]) > 0 ) ? $options->options_rss["advanced_wordpress_configuration_plugin_".$shortName] : '10';

			$wait = $time; // integer

			// http://dev.mysql.com/doc/refman/5.0/en/date-and-time-functions.html#function_timestampdiff
			$device = 'MINUTE'; //MINUTE, HOUR, DAY, WEEK, MONTH, YEAR

			// add SQL-sytax to default $where
			$where .= " AND TIMESTAMPDIFF($device, $wpdb->posts.post_date_gmt, '$now') > $wait ";
		}
	}

	return $where;
}