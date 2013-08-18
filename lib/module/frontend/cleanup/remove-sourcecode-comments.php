<?php
/*
Module Name: Remove source comments
Description: Removes all sourcecode comments left by plugins etc (be careful regarding performance, this uses ob_start and ob_end_flush).
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_action('get_header', 'awcp_bufferStart');
add_action('wp_footer', 'awcp_bufferEnd');

/**
 *
 */
function awcp_callback($buffer) {
	$buffer = preg_replace('/<!--(.|\s)*?-->/', '', $buffer);
	return $buffer;
}
/**
 * [buffer_start description]
 */
function awcp_bufferStart() {
	ob_start("awcp_callback");
}

/**
 * [buffer_end description]
 */
function awcp_bufferEnd() {
	ob_end_flush();
}
