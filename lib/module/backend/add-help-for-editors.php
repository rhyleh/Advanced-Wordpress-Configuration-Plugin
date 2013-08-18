<?php
/*
Module Name: Add help for editors
Description: * Options need to be set directly in plugin. Example setup for help tab on post-screen included.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_action( 'load-post.php', 'awcp_addHelpForEditors' );



/**
 * adds information to the help dropdown in the backend
 * see: http://www.paulund.co.uk/adding-a-help-tab-to-wordpress
 */
function awcp_addHelpForEditors() {
	$screen = get_current_screen();

	switch($screen->id) {
		//help on pages
		case 'page':
			$content = '<p>Lorem ipsum dolor sit amet.</p>';
			break;

		//help on news posts
		case 'post':
			$content = '<p>Lorem ipsum dolor sit amet.</p>';
			break;

		default:
			$content = '<p>Lorem ipsum dolor sit amet.</p>';
			break;
	}

	$screen->add_help_tab( array(
		'id'      => 'awcp',
		'title'   => __('Helpadvanced-wordpress-configuration-plugin', 'advanced-wordpress-configuration-plugintheme'),
		'content' => $content ,
	));
}