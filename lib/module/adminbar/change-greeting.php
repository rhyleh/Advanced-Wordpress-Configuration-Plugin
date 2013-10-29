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


add_filter('admin_bar_menu', 'awcp_changeGreeting', 25);


/**
 * Changes the greeting in the admin bar
 * see: http://botcrawl.com/how-to-change-or-remove-the-howdy-greeting-message-on-the-wordpress-user-menu-bar/
 * @return [type] [description]
 */

function awcp_changeGreeting( $wp_admin_bar ) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	if( strlen($options->options_adminbar['advanced_wordpress_configuration_plugin_'.$shortName]) > 0 ) {

		$user_id = get_current_user_id();
		$current_user = wp_get_current_user();
		$profile_url = get_edit_profile_url( $user_id );
		$text = trim($options->options_adminbar['advanced_wordpress_configuration_plugin_'.$shortName]);

		if ( 0 != $user_id ) {
			/* Add the "My Account" menu */
			$avatar = get_avatar( $user_id, 28 );
			$howdy = sprintf( __($text.' %1$s'), $current_user->display_name );
			$class = empty( $avatar ) ? '' : 'with-avatar';

			$wp_admin_bar->add_menu( 
				array(
					'id' => 'my-account',
					'parent' => 'top-secondary',
					'title' => $howdy . $avatar,
					'href' => $profile_url,
					'meta' => array(
						'class' => $class,
						'title' => 'My Account'
					),
				)
			);

		}

	}
}