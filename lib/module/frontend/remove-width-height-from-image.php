<?php
/*
Module Name: Remove image width and height
Description: Remove Width and Height Attributes From Inserted Images.
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
add_filter( 'post_thumbnail_html', 'awcp_removeWidthHeightFromImage', 10 );
add_filter( 'image_send_to_editor', 'awcp_removeWidthHeightFromImage', 10 );
	

/**
 * 
 */
function awcp_removeWidthHeightFromImage( $html ) {
   $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
   return $html;
}