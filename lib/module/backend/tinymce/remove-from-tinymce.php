<?php
/*
Module Name: Remove buttons
Description: Select buttons to hide.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: selectmultiple
Options: formatselect => Format select, styleselect => Style select, underline => Underline, justifyfull => Justify full, forecolor => Forecolor, pastetext => Paste text, pasteword => Paste word, removeformat => Remove format, charmap => Char map, outdent => Outdent, indent => Indent, undo => Undo, redo => Redo, wp_help => WP help
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter( 'mce_buttons ', 'awcp_customTinymce' );
add_filter( 'mce_buttons_2', 'awcp_customTinymce' );


/**
 * based on: http://alisothegeek.com/2011/05/tinymce-styles-dropdown-wordpress-visual-editor/
 * @param  [type] $buttons [description]
 * @return [type]          [description]
 */
function awcp_customTinymce( $buttons ) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$shortName = $options->getShortName(__FILE__);

	$selectedOptions = explode( ',', $options->options_backend["advanced_wordpress_configuration_plugin_".$shortName] );

	if(is_array($selectedOptions)) {
		return array_diff($buttons, $selectedOptions);
	}

	return $buttons;
}