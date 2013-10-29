<?php
/*
Module Name: Login with email
Description: Allow logging in with username or email address.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_action('wp_authenticate','awcp_loginWithEmailAddress');

/**
 * Source: http://wpsnipp.com/index.php/functions-php/login-with-username-or-email-address/
 * @param  [type] $username [description]
 * @return [type]           [description]
 */
function awcp_loginWithEmailAddress($username) {
	$user = get_user_by('email',$username);
	if(!empty($user->user_login))
		$username = $user->user_login;
	return $username;
}
