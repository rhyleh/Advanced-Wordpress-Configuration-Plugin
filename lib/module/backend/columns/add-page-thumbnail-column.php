<?php
/*
Module Name: Add page thumbnail column
Description: Adds a post-thumbnail column to pages.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_filter('manage_pages_columns', 'awcp_addPageThumbnailColumn', 5);
add_action('manage_pages_custom_column', 'awcp_displayPageThumbnailColumn', 5, 2);


// Add the column
function awcp_addPageThumbnailColumn($cols){
	$cols['awcp_post_thumb'] = __('Thumbnail');
	return $cols;
}

// Grab featured-thumbnail size post thumbnail and display it.
function awcp_displayPageThumbnailColumn($col, $id){
  switch($col){
    case 'awcp_post_thumb':
      if( function_exists('the_post_thumbnail') )
        echo the_post_thumbnail( 'admin-list-thumb' );
      else
        echo 'Not supported in theme';
      break;
  }
}