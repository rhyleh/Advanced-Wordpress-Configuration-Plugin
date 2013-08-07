<?php
/**
   * advanced-wordpress-configuration-pluginWordpressSettings
   * Module RSS
   *
   * @author     T. Boehning
   * @version	1.1
   *
   *
   */
class moduleRss {

	protected $options_rss;


  	function __construct() {

		$this->options_rss 			= get_option('advanced_wordpress_configuration_plugin_rss');
		
		$this->registerActions();
		$this->registerFilters();
	}

	/**
	 * register the actions - all set via options page
	 */
	function registerActions() {

		if( !is_null($this->options_rss) ) {

			//set in plugin options, default is false: disable RSS feeds
			if( isset($this->options_rss["advanced_wordpress_configuration_plugin_disableRSS"]) ) {
				add_action('do_feed', array($this, 'disableRSS'), 1);
				add_action('do_feed_rdf', array($this, 'disableRSS'), 1);
				add_action('do_feed_rss', array($this, 'disableRSS'), 1);
				add_action('do_feed_rss2', array($this, 'disableRSS'), 1);
				add_action('do_feed_atom', array($this, 'disableRSS'), 1);
			}
		}
	}



	/**
	 * register the filters - all set via options page
	 */
	function registerFilters() {

		if( !is_null($this->options_rss) ) {
			
			//Delay publish to RSS feed, default 10 minutes
			if( isset($this->options_rss["advanced_wordpress_configuration_plugin_delayPublishRSS"]) ) {
				add_filter('posts_where',  array($this, 'delayPublishRSS') );
			}

			//adds the post thumbnail to the rss feed
			if( isset($this->options_rss["advanced_wordpress_configuration_plugin_addThumbnailToRSS"]) ) {
				add_filter('the_excerpt_rss', array($this, 'addThumbnailToRSS') );
				add_filter('the_content_feed', array($this, 'addThumbnailToRSS') );
			}

			if( isset($this->options_rss["advanced_wordpress_configuration_plugin_customizeRSSFooter"]) ) {
				add_filter('the_excerpt_rss', array($this, 'customizeRSSFooter') );
				add_filter('the_content_feed', array($this, 'customizeRSSFooter') );
			}

			if( isset($this->options_rss["advanced_wordpress_configuration_plugin_removeCategoryFromFeed"]) ) {
				add_filter('pre_get_posts', array($this, 'removeCategoryFromFeed') );
			}
		}
	}

	/**
 	 * disables the RSS feed
	 *
	 */
	function disableRSS() {
		wp_die( __('No feed available, please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
	}

	/*
	* all bloggers make errors that we catch after we publish the post. Sometimes even within the next minute or two. That is why it is best that we delay our posts to be published on the RSS by 5-10 minutes.
	*/
	function delayPublishRSS($where) {

		if( $this->options_rss["advanced_wordpress_configuration_plugin_delayPublishRSS"] != 'false' ) {

			global $wpdb;

			if ( is_feed() ) {
				// timestamp in WP-format
				$now = gmdate('Y-m-d H:i:s');

				// value for wait; 

				$time = ( intval($this->options_rss["advanced_wordpress_configuration_plugin_delayPublishRSS"]) > 0 ) ? $this->options_rss["advanced_wordpress_configuration_plugin_delayPublishRSS"] : '10';

				$wait = $time; // integer

				// http://dev.mysql.com/doc/refman/5.0/en/date-and-time-functions.html#function_timestampdiff
				$device = 'MINUTE'; //MINUTE, HOUR, DAY, WEEK, MONTH, YEAR

				// add SQL-sytax to default $where
				$where .= " AND TIMESTAMPDIFF($device, $wpdb->posts.post_date_gmt, '$now') > $wait ";
			}
		}

		return $where;
	}

	/**
 	 * adds the thumbnail picture to the RSS feed
	 *
	 */
	function addThumbnailToRSS($content) {
		global $post;
		if(has_post_thumbnail($post->ID)) {
		$content = '<p>' . get_the_post_thumbnail($post->ID) .
		'</p>' . get_the_content();
		}
		return $content;
	}

	/*
	* 
	*/
	function customizeRSSFooter($content) {
		if(is_feed()){
			if(strlen($this->options_rss['advanced_wordpress_configuration_plugin_customizeRSSFooter']) > 0 ) $content .= $this->options_rss['advanced_wordpress_configuration_plugin_customizeRSSFooter'];
		}
		return $content;
	}

	/*
	* custom feed query
	*/
	function removeCategoryFromFeed($query) {
		if(is_feed()) {
			$query->set('cat',$this->options_rss['advanced_wordpress_configuration_plugin_removeCategoryFromFeed']);
			return $query;
		}
	}

}

?>
