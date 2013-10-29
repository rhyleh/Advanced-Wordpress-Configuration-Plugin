<?php
/*
Module Name: Highlight search results
Description: Highlights the search words in the title and the excerpt. Keywords will get a span with class "search-excerpt" (do the highlighting in CSS).
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('the_excerpt', 'awcp_highlightResults');
add_filter('the_title', 'awcp_highlightResults');


/**
 * Source: http://wpsnipp.com/index.php/excerpt/highlight-keywords-in-search-results-the_excerpt-and-the_title/
 * @param  [type] $text [description]
 * @return [type]       [description]
 */
function awcp_highlightResults($text){
	if(is_search()){
		$sr = get_query_var('s');
		$keys = explode(" ",$sr);
		$text = preg_replace('/('.implode('|', $keys) .')/iu', '<span class="search-excerpt">'.$sr.'</span>', $text);
	}
	return $text;
}
