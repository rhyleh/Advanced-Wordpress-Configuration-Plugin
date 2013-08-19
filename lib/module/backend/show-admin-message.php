<?php
/*
Module Name: Show error message
Description: Show this message to backend users (be careful with that).
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Input
Class: regular-text
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_action( 'admin_notices', 'awcp_showAdminMessages' );


/**
 * [awcp_showAdminMessages description]
 * @return [type] [description]
 */
function awcp_showAdminMessages() {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	if(strlen($options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName]) > 0)
		awcp_showAdminMessage($options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName], true);
}


/**
 * [awcp_showAdminMessage description]
 * @param  [type]  $message  [description]
 * @param  boolean $errormsg [description]
 * @return [type]            [description]
 */
function awcp_showAdminMessage($message, $errormsg = false) {
	if ($errormsg) {
		echo '<div id="message" class="error">';
	}
	else {
		echo '<div id="message" class="updated fade">';
	}
	echo "<p><strong>$message</strong></p></div>";
} 