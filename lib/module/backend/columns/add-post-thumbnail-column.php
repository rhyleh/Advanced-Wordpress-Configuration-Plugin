<?php
/*
Module Name: Add post thumbnail column
Description: Adds a post-thumbnail column to posts.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('manage_posts_columns', 'awcp_addPostThumbnailColumn', 5);
add_action('manage_posts_custom_column', 'awcp_displayPostThumbnailColumn', 5, 2);


/**
 * register the filters - all set via options page
 * @param  [type] $cols [description]
 * @return [type]       [description]
 */
function awcp_addPostThumbnailColumn($cols){
	$cols['awcp_post_thumb'] = __('Thumbnail');
	return $cols;
}


/**
 * Grab featured-thumbnail size post thumbnail and display it.
 * @param  [type] $col [description]
 * @param  [type] $id  [description]
 * @return [type]      [description]
 */
function awcp_displayPostThumbnailColumn($col, $id){
  switch($col){
    case 'awcp_post_thumb':
      if( function_exists('the_post_thumbnail') )
        echo the_post_thumbnail( 'admin-list-thumb' );
      else
        echo 'Not supported in theme';
      break;
  }
}