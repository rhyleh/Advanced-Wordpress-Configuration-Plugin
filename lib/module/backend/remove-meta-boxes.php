<?php
/*
Module Name: Remove metaboxes
Description: Select metaboxes to remove from editing screen:
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: selectmultiple
Options: submitdiv => Submit div, commentsdiv => Comments, revisionsdiv => Revisions, authordiv => Author, slugdiv => Slug, tagsdiv-post_tag => Tags, categorydiv => Category, postexcerpt => Excerpt, formatdiv => Format, trackbacksdiv => Trackbacks,	postcustom => Post,	commentstatusdiv => Comment status, postimagediv => Post image,	pageparentdiv => Page parent
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_action( 'admin_menu', 'awcp_removeMetaBoxes' );


/**
 * [awcp_removeMetaBoxes description]
 * @return [type] [description]
 */
function awcp_removeMetaBoxes() {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	if( $options->options_backend["advanced_wordpress_configuration_plugin_".$shortName] ) {
		
		$selectedOptions = explode( ',', $options->options_backend["advanced_wordpress_configuration_plugin_".$shortName] );

		foreach ($selectedOptions as $key => $value) {
			
			switch (trim($value)) {
				case 'submitdiv':
				case 'commentsdiv':
				case 'revisionsdiv':
				case 'slugdiv':
				case 'authordiv':
				case 'postexcerpt':
				case 'formatdiv':
				case 'trackbacksdiv':
				case 'postcustom':
				case 'commentstatusdiv':
					remove_meta_box( trim($value), 'post', 'normal' );
					break;

				case 'tagsdiv-post_tag':
				case 'categorydiv':
				case 'postimagediv':
					remove_meta_box( trim($value), 'post', 'side' );
					break;

				case 'pageparentdiv':
					remove_meta_box( trim($value), 'page', 'side' );
					break;
				
				default:
					# code...
					break;
			}
		}
	}
}