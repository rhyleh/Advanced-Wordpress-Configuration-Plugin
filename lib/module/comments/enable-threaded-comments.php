<?php
/*
Module Name: Enable threaded comments
Description: Loads the javascript for threaded comments (still needs to be enabled in backend)
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Both
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_action('get_header', 'enableThreadedComments');


/**
 * Enqueues javascript for threaded comments
 * @param  [type] $count [description]
 * @return [type]        [description]
 */
function enableThreadedComments(){
	if (!is_admin()) {
		if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1))
			wp_enqueue_script('comment-reply');
	}
}