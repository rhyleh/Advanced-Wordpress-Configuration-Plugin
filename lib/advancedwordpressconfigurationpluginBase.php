<?php
/*
 * advanced-wordpress-configuration-pluginBase
 * Basic settings for the plugin
 *
 * @author     T. Boehning
 * @version	1.2
 */

//define custom post types, here is how to do it:
//require_once('includes/custom_post_types.php');

class advancedwordpressconfigurationpluginBase {


	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'pluginTextdomain' ) );

		//include the api (generic useful functions)
		include 'advancedwordpressconfigurationpluginAPI.php';

		//Load backend options and admin styles only in admin section
		if( is_admin() ) {		
			$this->register_admin_styles();
			$this->register_admin_scripts();
		} else {
			$this->register_plugin_styles();
			$this->register_plugin_scripts();
		}

		include 'advancedwordpressconfigurationpluginOptions.php';
		$pluginOptions = advancedwordpressconfigurationpluginOptions::getInstance();
		
		//set error handler
		set_error_handler(array($this, "customError"), E_USER_WARNING);
	}

	
	

	/**
	* Loads the plugin text domain for translation
	*/
	public function pluginTextdomain() {

		load_plugin_textdomain( 'advanced-wordpress-configuration-plugin-locale', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
	}




	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {

		wp_enqueue_style( 'advanced-wordpress-configuration-plugin-admin-styles', plugins_url( '../css/admin.css', __FILE__ ) );
	}

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() {

		//wp_enqueue_script( 'advanced-wordpress-configuration-plugin-admin-script', plugins_url( '../js/admin.js', __FILE__ ) );
	}


	/**
	 * Registers and enqueues plugin-specific styles.
	 */
	public function register_plugin_styles() {

		//wp_enqueue_style( 'advanced-wordpress-configuration-plugin-plugin-styles', plugins_url( '../css/display.css', __FILE__ ) );
	}

	/**
	 * Registers and enqueues plugin-specific scripts.
	 */
	public function register_plugin_scripts() {

		//wp_enqueue_script( 'plugin-script', plugins_url( '../js/display.js', __FILE__ ) );
	}




	/**
	 * Sends an email
	 */
	function customError($errno, $errstr) {

		if ( ($number !== E_NOTICE) && ($number < 2048) ) {
			die("There was an error. Please try again later.");
		}

		error_log("Error: [$errno] $errstr",1,"website@advanced-wordpress-configuration-plugin.com","From: website@advanced-wordpress-configuration-plugin.com");
	}
}

new advancedwordpressconfigurationpluginBase();
?>
