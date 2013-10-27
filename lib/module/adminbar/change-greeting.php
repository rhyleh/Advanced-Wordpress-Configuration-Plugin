<?php
/*
Module Name: Change greeting
Description: Changes the "howdy"-Greeting
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Both
Type: input
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('admin_bar_menu', 'awcp_changeGreeting');


/**
 * Changes the greeting in the admin bar
 * see: http://botcrawl.com/how-to-change-or-remove-the-howdy-greeting-message-on-the-wordpress-user-menu-bar/
 * @return [type] [description]
 */
function awcp_changeGreeting( $wp_admin_bar ) {

	//get options0
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	if( strlen($options->options_adminbar['advanced_wordpress_configuration_plugin_'.$shortName]) > 0 ) {

		$text = trim($options->options_adminbar['advanced_wordpress_configuration_plugin_'.$shortName]);

		$my_account=$wp_admin_bar->get_node('my-account');

		$newtitle = str_replace( 'Howdy,', $text, $my_account->title );
		$wp_admin_bar->add_node( array(
			'id' => 'my-account',
			'title' => $newtitle) );
		
	}

	//$my_account = $wp_admin_bar->get_node('my-account');

	/*$wp_admin_bar->add_node(array(
	  'id' => 'my-account'
	  ,'title' => str_replace('Howdy, ', self::$options['change_howdy'] . ' ', $my_account->title)
	));*/
}