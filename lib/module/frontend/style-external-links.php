<?php
/*
Module Name: Styles external links
Description: No description yet
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
add_filter('the_content', 'awcp_styleExternalLinks' );


/**
 * add class to external links in content
 */
function awcp_styleExternalLinks($content) {
	$regexp = '/\<a[^\>]*(target="_([\w]*)")[^\>]*\>[^\<]*\<\/a>/smU';
	if (preg_match_all($regexp, $content, $matches) ){
		for ($m=0; $m < count($matches[0]); $m++) {
			if ($matches[2][$m] == 'blank') {
				$temp = str_replace($matches[1][$m], $matches[1][$m] . 'class="external"', $matches[0][$m]);
				$content = str_replace($matches[0][$m], $temp, $content);
			}
		}
	}
	return $content;
}