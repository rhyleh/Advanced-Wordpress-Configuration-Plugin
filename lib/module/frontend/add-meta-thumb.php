<?php
/*
Module Name: Add image tag
Description: Adds an meta image tag for thumbnails
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_action('wp_head', 'awcp_metaImageThumb' );


/**
 * Ausgabe der Artikelbilder als og:image Metatag
 * @return [type] [description]
 */
function awcp_metaImageThumb() {
	/* Keine Feeds und X-Backs */
	if ( is_feed() or is_trackback() ) {
		return;
	}
 
	/* Beiträge mit Thumbs */
	if ( is_singular() && has_post_thumbnail() ) {
		$image = wp_get_attachment_image_src(
			get_post_thumbnail_id()
		);
 
		$thumb = $image[0];
 
	/* Default Thumb */
	} else {
		//$thumb = 'http://cdn.wpseo.de/website/v3/img/logo/120x120.png';
	}
 
	/* Ausgabe */
	echo sprintf(
		'%s<meta property="og:image" content="%s" />',
		"\n",
		esc_attr($thumb)
	);
}