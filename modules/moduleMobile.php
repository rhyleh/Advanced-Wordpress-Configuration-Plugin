<?php
/**
   * advanced-wordpress-configuration-pluginWordpressSettings
   * Module Mobile
   *
   * @author     T. Boehning
   * @version	1.0
   *
   *
   */
class moduleMobile {

	protected $options_mobile;


  	function __construct() {

		$this->options_mobile = get_option('advanced_wordpress_configuration_plugin_mobile');
		
		$this->registerActions();
		$this->registerFilters();
	}

   
    
	/**
	 * register the actions - all set via options page
	 */
	function registerActions() {

		if( !is_null($this->options_mobile) ) {

			if( isset($this->options_mobile["advanced_wordpress_configuration_plugin_mobilePostsPerPage"]) ) {
				//this adds the function above to the 'pre_get_posts' action  
				add_action('pre_get_posts', array($this, 'customPostsPerPage'));
			}
		}
	}



	/**
	 * register the filters - all set via options page
	 */
	function registerFilters() {

	}

	/**
 	 * Custom posts per page for mobile devices
	 *
	 */
	function customPostsPerPage($query) {

		$number = (int) $this->options_mobile["advanced_wordpress_configuration_plugin_mobilePostsPerPage"];

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

}

?>
