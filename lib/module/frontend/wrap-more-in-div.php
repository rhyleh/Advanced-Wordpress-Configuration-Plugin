<?php
/*
Module Name: Wrap More-link in div
Description: Wraps the "more"-link in a div instead of a paragraph (e.g. for better positioning).
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
add_filter('the_content_more_link', 'wrapReadmore', 10, 1);

/**
 * Wraps the more-link in a div instead of a paragraph
 * @param  [type] $more_link [description]
 * @return [type]            [description]
 */
function wrapReadmore($more_link) {
	return '<div class="post-readmore">'.$more_link.'</div>';
}