<?php
/*
Module Name: Remove menu entries
Description: Select main menu entries to remove (for all users):
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: selectmultiple
Options: Posts => Posts, Pages => Pages, Comments => Comments, Appearance => Appearance, Plugins => Plugins, Users => Users, Tools => Tools, Settings => Settings
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_action( 'admin_menu', 'awcp_removeBackendMenus' );


/**
 * removes backend menus
 * see: http://www.paulund.co.uk/add-menu-item-to-wordpress-admin
 */
function awcp_removeBackendMenus() {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	$selectedOptions = explode( ',', $options->options_backend["advanced_wordpress_configuration_plugin_".$shortName] );

	global $menu;

	foreach ($selectedOptions as $key => $value) {
		$restricted[] = __(trim($value));
	}

	/* additional: restrict for certain user-name
	 *global $current_user;
	  get_currentuserinfo();

	  if($current_user->user_login == 'clients-username'){ .. }
	 */

	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}