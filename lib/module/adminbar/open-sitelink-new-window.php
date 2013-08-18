<?php
/*
Module Name: Open site links in new window
Description: Opens the site links in _blank instead of _self.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Both
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_action( 'wp_before_admin_bar_render', 'awcp_openSiteLinkNewWindow' );


/*
* Make Visit Site links open in a new window: My Sites > Site Name > Visit Site
*/
function awcp_openSiteLinkNewWindow() {
	global $wp_admin_bar;
	foreach ( (array) $wp_admin_bar->user->blogs as $blog ) {
		$menu_id  = 'blog-' . $blog->userblog_id;
		$wp_admin_bar->add_menu( array(
			'parent' 	=> $menu_id,
			'id' 	=> $menu_id . '-v',
			'title' 	=> __( 'Visit Site' ),
			'href' 	=> get_home_url( $blog->userblog_id, '/' ),
			'meta' 	=> array( target => '_blank' ) )
	   );
	}
}