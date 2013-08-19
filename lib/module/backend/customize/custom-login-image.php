<?php
/*
Module Name: Custom login image
Description: Enter the path to following images: bg_login.jpg, logo.png, button.jpg. Path is based on current theme (enter images/ for example or /lib/img/).
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Input
Class: regular-text
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_action( 'login_enqueue_scripts', 'awcp_customLoginImage' );


/**
 * includes an additional stylesheet on backend login page
 * @return [type] [description]
 */
function awcp_customLoginImage(){

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	if(strlen($options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName]) > 0 ) {

		$path = trailingslashit($options->options_backend['advanced_wordpress_configuration_plugin_'.$shortName]);

		echo '
		<div class="background-cover"></div>
		<style type="text/css" media="screen">
			.background-cover{
				background:url('.get_bloginfo('template_directory').'/'.$path.'bg_login.jpg) no-repeat center center fixed;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
				position:fixed;
				top:0;
				left:0;
				z-index:10;
				overflow: hidden;
				width: 100%;
				height:100%;
			}
			#login{ z-index:9999; position:relative; }
			.login form { box-shadow: 0px 0px 0px 0px !important; }
			.login h1 a { background:url('.get_bloginfo('template_directory').'/'.$path.'logo.png) no-repeat center top !important; }
			input.button-primary, button.button-primary, a.button-primary{
				border-radius: 3px !important; 	background:url('.get_bloginfo('template_directory').'/'.$path.'button.jpg);
					border:none !important;
					font-weight:normal !important;
					text-shadow:none !important;
				}
				.button:active, .submit input:active, .button-secondary:active {
					background:#96C800 !important;
					text-shadow: none !important;
				}
				.login #nav a, .login #backtoblog a {
					color:#fff !important;
					text-shadow: none !important;
				}
				.login #nav a:hover, .login #backtoblog a:hover{
					color:#96C800 !important;
					text-shadow: none !important;
				}
				.login #nav, .login #backtoblog{
					text-shadow: none !important;
				}
			</style>
		';
	}
}