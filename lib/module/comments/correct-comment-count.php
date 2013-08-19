<?php
/*
Module Name: Correct comment count
Description: Corrects the comment count
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Both
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('get_comments_number', 'awcp_correctCommentCount', 0);


/**
 * [correctCommentCount description]
 * @param  [type] $count [description]
 * @return [type]        [description]
 */
function awcp_correctCommentCount( $count ) {
	if ( ! is_admin() ) {
		global $id;
		$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
		return count($comments_by_type['comment']);
	} else {
		return $count;
	}
}