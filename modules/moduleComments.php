<?php
/**
   * advanced-wordpress-configuration-pluginWordpressSettings
   * Module comments
   *
   * @author     T. Boehning
   * @version	1.1
   *
   *
   */
class moduleComments {

	protected $options_comments;

  	function __construct() {

		$this->options_comments 	= get_option('advanced_wordpress_configuration_plugin_comments');

		$this->registerActions();
		$this->registerFilters();
	}

	/**
	 * register the actions - all set via options page
	 */
	function registerActions() {

	}


	/**
	 * register the filters - all set via options page
	 */
	function registerFilters() {

		//By default, WordPress counts trackbacks, and pings as comments. This inflates the comment count which looks really bad especially when you are not displaying the trackbacks and pings.
		if( !is_null($this->options_comments) ) {
			if(isset($this->options_comments["advanced_wordpress_configuration_plugin_correctCommentCount"]) ) {
				add_filter('get_comments_number', array($this, 'correctCommentCount'), 0);
			}
		}
	}

	/**
	 * [correctCommentCount description]
	 * @param  [type] $count [description]
	 * @return [type]        [description]
	 */
	function correctCommentCount( $count ) {
		if ( ! is_admin() ) {
			global $id;
			$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
			return count($comments_by_type['comment']);
		} else {
			return $count;
		}
	}

}

?>
