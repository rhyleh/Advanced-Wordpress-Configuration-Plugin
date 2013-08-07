<?php
/**
   * advanced-wordpress-configuration-pluginWordpressSettings
   * Module Javascript
   *
   * @author     T. Boehning
   * @version	1.1
   *
   *
   */
class moduleJavascript {

	protected $options_javascript;


  	function __construct() {

		$this->options_javascript 	= get_option('advanced_wordpress_configuration_plugin_javascript');

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
				
		if( !is_null($this->options_javascript) ) {

			//set in plugin options, default is false: add jquery from cdn
			if( isset($this->options_javascript["advanced_wordpress_configuration_plugin_jqueryFromCdn"]) ) {
				add_action( 'init', array($this, 'jqueryFromCdn') );
			}

			//adds a defer='defer' to javascripts in header
			if( isset($this->options_javascript["advanced_wordpress_configuration_plugin_deferJavascript"]) ) {
				add_filter( 'clean_url', array($this, 'addScriptDefer'), 99, 1);
			}
		}
	}

	/**
	 *
	 */
	function jqueryFromCdn() {

		if ( !is_admin() ) {

		    wp_deregister_script( 'jquery' );
		    wp_register_script( 'jquery', ( 'https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js' ), false, null, true );
		    wp_enqueue_script( 'jquery' );
		}
	}

	/**
 	 * adds defer='defer' to javascript in header to defer loading
	 */
	function deferJavascript($file) {
		if ( strpos($file, '.js') !== false ) {
			return sprintf(
				"%s' defer='defer",
				$file
			);
		}

		return $file;
	}

}

?>
