<?php
/*
Module Name: Link to large version
Description: Links to the "large" version of an attached file/image, instead of the full version (e.g. for opening 6 megapixel images in lightboxes).
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
add_filter('wp_get_attachment_link', 'awcp_getAttachmentLinkFilter', 10, 4);


/**
 * Removes the link to the full version of an image and replaces it by a link to the large version
 * copied from: http://oikos.org.uk/2011/09/tech-notes-using-resized-images-in-wordpress-galleries-and-lightboxes/
 * @param  [type] $content   [description]
 * @param  [type] $post_id   [description]
 * @param  [type] $size      [description]
 * @param  [type] $permalink [description]
 * @return [type]            [description]
 */
function awcp_getAttachmentLinkFilter( $content, $post_id, $size, $permalink ) {
	// Only do this if we're getting the file URL
	if ( !$permalink) {
		// This returns an array of (url, width, height)
		$image = wp_get_attachment_image_src( $post_id, 'large' );
		$new_content = preg_replace('/href=\'(.*?)\'/', 'href=\'' . $image[0] . '\'', $content );
		return $new_content;
	} else {
		return $content;
	}
}