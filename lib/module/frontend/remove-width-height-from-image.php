<?php
/*
Module Name: Remove image width and height
Description: Remove Width and Height attributes from inserted images (by regex).
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter( 'post_thumbnail_html', 'awcp_removeWidthHeightFromImage', 10 );
add_filter( 'image_send_to_editor', 'awcp_removeWidthHeightFromImage', 10 );
	

/**
 * [awcp_removeWidthHeightFromImage description]
 * @param  [type] $html [description]
 * @return [type]       [description]
 */
function awcp_removeWidthHeightFromImage( $html ) {
	$html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
	return $html;
}