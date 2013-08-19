<?php
/*
Module Name: Customize TinyMCE Editor
Description: * Options need to be set directly in plugin: Select buttons to hide/display.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter( 'tiny_mce_before_init', 'awcp_customTinymceSettings' );


/**
 * defines available styles
 * @param  [type] $settings [description]
 * @return [type]           [description]
 */
function awcp_customTinymceSettings( $settings ) {

	//see http://alisothegeek.com/2011/05/tinymce-styles-dropdown-wordpress-visual-editor/ for explanations
	$style_formats = array(
	    array(
		    'title' => 'Button',
		    'selector' => 'a',
		    'classes' => 'button'
	    ),
	    array(
		    'title' => 'Callout Box',
		    'block' => 'div',
		    'classes' => 'callout',
		    'wrapper' => true
	    ),
	    array(
		    'title' => 'Bold Red Text',
		    'inline' => 'span',
		    'styles' => array(
			    'color' => '#f00',
			    'fontWeight' => 'bold'
		    )
	    )
	);

	$settings['style_formats'] = json_encode( $style_formats );


	//custom stylesheet for tinymce editor to get more realistic preview
	//TODO this causes a fatal error
	//add_editor_style('library/css/backend_rte.css');

	return $settings;
}