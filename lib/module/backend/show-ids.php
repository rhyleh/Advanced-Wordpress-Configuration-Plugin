<?php
/*
Module Name: Show IDs
Description: Show IDs on edit screens for posts, pages etc.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}

//see: http://wpmu.org/daily-tip-how-to-display-post-and-page-ids-in-the-wordpress-admin/
add_filter('manage_posts_columns', 'awcp_postsColumnsId', 5);
add_action('manage_posts_custom_column', 'awcp_postsCustomIdColumns', 5, 2);
add_filter('manage_pages_columns', 'awcp_postsColumnsId', 5);
add_action('manage_pages_custom_column', 'awcp_postsCustomIdColumns', 5, 2);



/**
 * 
 * @return [type] [description]
 */
function awcp_postsColumnsId($defaults){
	$defaults['wps_post_id'] = __('ID');
	return $defaults;
}

/**
 * [awcp_postsCustomIdColumns description]
 * @param  [type] $column_name [description]
 * @param  [type] $id          [description]
 * @return [type]              [description]
 */
function awcp_postsCustomIdColumns($column_name, $id){
	if($column_name === 'wps_post_id'){
		echo $id;
	}
}
