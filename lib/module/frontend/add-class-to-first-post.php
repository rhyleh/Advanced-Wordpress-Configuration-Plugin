<?php
/*
Module Name: First post class
Description: Adds a "first" class to the first post.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('post_class', 'awcp_styleFirstPost' );


/**
 * [styleFirstPost description]
 * @param  [type] $classes [description]
 * @return [type]          [description]
 */
function awcp_styleFirstPost( $classes ) {
    global $wp_query;
    if( 0 == $wp_query->current_post )
        $classes[] = 'first';
        return $classes;
}