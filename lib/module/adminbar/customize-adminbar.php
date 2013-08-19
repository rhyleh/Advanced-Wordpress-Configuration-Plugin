<?php
/*
Module Name: Customize admin bar
Description: * Options need to be set directly in plugin. Adds a search bar by default.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Both
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_action( 'wp_before_admin_bar_render', 'awcp_customizeAdminBar' );


/**
 * Remove the WordPress Logo from the WordPress Admin Bar
 * @return [type] [description]
 */
function awcp_customizeAdminBar() {
	global $wp_admin_bar;

	/* Aktiv und Admin? */
	if ( !is_admin_bar_showing() ) {
		return;
	}

	/* Add new menus */
	/*$wp_admin_bar->add_menu(array(
		'id' => 'wp-admin-bar-new-item',
		'title' => __('Titel'),
		'href' => 'Link'
	));*/


	/* Suche definieren */
	$form  = '<form action="' .esc_url( admin_url('edit.php') ). '" method="get" id="adminbarsearch">';
	$form .= '<input class="adminbar-input" name="s" tabindex="1" type="text" value="" maxlength="50" />';
	$form .= '<input type="submit" class="adminbar-button" value="' .__('Search'). '"/>';
	$form .= '</form>';
	
	/* Suche einbinden */
	$wp_admin_bar->add_menu(
		array(
			'parent' => 'top-secondary',
			'id'     => 'search',
			'title'  => $form,
			'meta'   => array(
				'class' => 'admin-bar-search'
			)
		)
	);
}