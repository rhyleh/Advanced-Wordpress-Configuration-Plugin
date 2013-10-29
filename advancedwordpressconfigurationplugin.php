<?php
/*
 * Plugin Name: Advanced Wordpress Configuration Plugin
 * Plugin URI: https://github.com/rhyleh/Advanced-Wordpress-Configuration-Plugin
 * Description: Enables advanced backend/frontend settings
 * Version: 1.7
 * Author: T. Böhning
 * Author URI https://github.com/rhyleh/Advanced-Wordpress-Configuration-Plugin
 * Author Email: boehning@gmail.com
 * License: GPL2
*/
define( 'AWCP_VERSION', '1.7' );

 if ( !defined('DB_NAME') ) {
	header('HTTP/1.0 403 Forbidden');
	die;
}

global $wp_version;

// prevent parsing errors on PHP 4 or old WP installs
if ( version_compare(PHP_VERSION, '5.2', '<') && version_compare( $wp_version, '3.3.999', '>' ) ) {
	if ( is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX) ) {
		require_once ABSPATH.'/wp-admin/includes/plugin.php';
		deactivate_plugins( __FILE__ );
		add_action('admin_notices', create_function( '', "echo '<div class=\"error\"><p>".__('Advanced Wordpress Configuration Plugin requires PHP 5 and WordPress 3.3+ to work correctly. The plugin has now disabled itself.') ."</p></div>';") );
	} else {
		return;
	}
} else {

	if ( is_admin() ) {
		//activation and deactivation
		register_activation_hook( __FILE__, 'awcp_BaseActivate' );
		register_deactivation_hook( __FILE__, 'awcp_BaseDeactivate' );

		//admin notices
		add_action('admin_init', 'awcp_AdminInit');
		add_action('admin_notices', 'awcp_AdminNotices');

		//link to settings
		add_filter('plugin_action_links', 'awcp_actionLinks', 10, 4 );
	} 

	//load base-file for backend and frontend
	add_action( 'plugins_loaded', 'awcp_BaseInit', 0 );
}

/**
 * Includes the base-file
 */
function awcp_BaseInit() {
	include_once dirname( __FILE__ ).'/lib/advancedwordpressconfigurationpluginBase.php';
}

/**
 * adds a link to settings page
 */
function awcp_actionLinks( $actions, $file, $data, $context ) {

 	if ( !current_user_can('manage_options') ) {
		return $actions;
	}
	if ( 'Advanced Wordpress Configuration Plugin' === $data[ 'Name' ] ) {
		$actions[ 'settings' ] = '<a href="' . esc_url( add_query_arg( array( 'page' => 'advanced-wordpress-configuration-plugin-options' ), admin_url( 'options-general.php' ) ) ) . '">' . __( 'Settings', 'advanced-wordpress-configuration-plugin-locale' ) . '</a>';
	}

	return $actions;
}

/**
 * show pending admin notices
 */
function awcp_AdminInit() {
 
    $notices= get_option('advanced-wordpress-configuration-pluginDeferredAdminNotices', array());
    
    update_option('advanced-wordpress-configuration-pluginDeferredAdminNotices', $notices);
}

/**
 * Print admin notice
 */
function awcp_AdminNotices() {

  	if ($notices= get_option('advanced-wordpress-configuration-pluginDeferredAdminNotices')) {
    	foreach ($notices as $notice) {
      		echo "<div class='updated'><p>$notice</p></div>";
    	}
    	delete_option('advanced-wordpress-configuration-pluginDeferredAdminNotices');
	}
}

/**
 * runs on activation of the plugin.
 */
function awcp_BaseActivate() {

	$notices= get_option('advanced-wordpress-configuration-pluginDeferredAdminNotices', array());
  	$notices[]= 'Advanced Wordpress Configuration Plugin activated. Please check the <a href="' . esc_url( add_query_arg( array( 'page' => 'advanced-wordpress-configuration-plugin-options' ), admin_url( 'options-general.php' ) ) ) . '">' . __( 'Settings', 'advanced-wordpress-configuration-plugin-locale' ) . '</a>.';
  	update_option('advanced-wordpress-configuration-pluginDeferredAdminNotices', $notices);

	if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();
	}
}

/**
 * runs on deactivation of the plugin.
 */
function awcp_BaseDeactivate() {
	
  	delete_option('advanced-wordpress-configuration-pluginDeferredAdminNotices'); 

	if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();
	}
}

?>