<?php
/*
Module Name: Add upload mimetypes
Description: Enter a comma separated list of file-extensions (e.g. "eps, indt, ppt, svg, psd"). Info: if the filetype is unknown it will be returned as "application/octet-stream" (which is good for most cases).
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Input
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_filter('upload_mimes', 'awcp_customUploadMimes' );



/**
 * Adds more mime types for upload in media manager
 * @param  array  $existing_mimes [description]
 */
function awcp_customUploadMimes( $existing_mimes=array() ) {

	//get options
	$options = advancedwordpressconfigurationpluginOptions::getInstance();

	//get current option name
	$info =  get_file_data( __FILE__ , array('name' => 'Module Name'));
	$shortName = sanitize_file_name($info['name']);

	if( strlen($options->options_general['advanced_wordpress_configuration_plugin_'.$shortName]) > 0 ) {

		$mimeTypes = array_map('trim', explode(",", $options->options_general['advanced_wordpress_configuration_plugin_'.$shortName]));

		if( is_array($mimeTypes) ) {
			foreach ($mimeTypes as &$value) {
				$mimetypeDefinition = awcp_lookupMimeType($value);

				if( $mimetypeDefinition) {
					$existing_mimes[$value] = $mimetypeDefinition;
				}
			}
		}
	}

	return $existing_mimes;
}

/**
 * Helper function for customUploadMimes
 * @param  [type] $ext [description]
 * @return [type]      [description]
 */
function awcp_lookupMimeType($ext) {

	$mime_types = array(

		'txt' => 'text/plain',
		'htm' => 'text/html',
		'html' => 'text/html',
		'php' => 'text/html',
		'css' => 'text/css',
		'js' => 'application/javascript',
		'json' => 'application/json',
		'xml' => 'application/xml',
		'swf' => 'application/x-shockwave-flash',
		'flv' => 'video/x-flv',

		// images
		'png' => 'image/png',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'gif' => 'image/gif',
		'bmp' => 'image/bmp',
		'ico' => 'image/vnd.microsoft.icon',
		'tiff' => 'image/tiff',
		'tif' => 'image/tiff',
		'svg' => 'image/svg+xml',
		'svgz' => 'image/svg+xml',

		// archives
		'zip' => 'application/zip',
		'rar' => 'application/x-rar-compressed',
		'exe' => 'application/x-msdownload',
		'msi' => 'application/x-msdownload',
		'cab' => 'application/vnd.ms-cab-compressed',

		// audio/video
		'mp3' => 'audio/mpeg',
		'qt' => 'video/quicktime',
		'mov' => 'video/quicktime',

		// adobe
		'pdf' => 'application/pdf',
		'psd' => 'image/vnd.adobe.photoshop',
		'ai' => 'application/postscript',
		'eps' => 'application/postscript',
		'ps' => 'application/postscript',
		'indt' => 'application/octet-stream',

		// ms office
		'doc' => 'application/msword',
		'rtf' => 'application/rtf',
		'xls' => 'application/vnd.ms-excel',
		'ppt' => 'application/vnd.ms-powerpoint',

		// open office
		'odt' => 'application/vnd.oasis.opendocument.text',
		'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
	);

	$ext = strtolower(array_pop(explode('.',$filename)));
	if (array_key_exists($ext, $mime_types)) {
		return $mime_types[$ext];
	}
	elseif (function_exists('finfo_open')) {
		$finfo = finfo_open(FILEINFO_MIME);
		$mimetype = finfo_file($finfo, $filename);
		finfo_close($finfo);
		return $mimetype;
	}
	else {
		return 'application/octet-stream';
	}
}