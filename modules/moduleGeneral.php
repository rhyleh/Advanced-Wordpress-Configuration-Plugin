<?php
/**
   * advanced-wordpress-configuration-pluginWordpressSettings
   * Module General
   *
   * @author     T. Boehning
   * @version	1.0
   *
   *
   */
class moduleGeneral {

	protected $options_general;
	

	function __construct() {

		$this->options_general = get_option('advanced_wordpress_configuration_plugin_general');

		$this->registerActions();
		$this->registerFilters();
	}

	/**
	 * register the actions - all set via options page
	 */
	function registerActions() {

	}



	/**
	 * register the filters - all set via options page
	 */
	function registerFilters() {

		if( !is_null($this->options_general) ) {

			//General
			if( isset($this->options_general["advanced_wordpress_configuration_plugin_correctTagForCustomTaxonomies"]) ) {
				add_filter('request', array($this, 'correctTagForCustomTaxonomies') );
			}

			//mime type erweitern
			if( isset($this->options_general["advanced_wordpress_configuration_plugin_customUploadMimes"]) ) {
				add_filter('upload_mimes', array($this, 'customUploadMimes') );
			}

			//logout to front page
			if( isset($this->options_general["advanced_wordpress_configuration_plugin_logoutToHomepage"]) ) {
				add_filter('logout_url', array($this, 'logoutToHomepage'), 10, 2 );
			}

			if( isset($this->options_general["advanced_wordpress_configuration_plugin_disableAutoP"]) ) {
				remove_filter ('the_content',  'wpautop');
			}
		}
	}



	/**
	 * The links of regular tags doesnt work with Custom Post Types. This fixes it.
	 * @param  [type] $request [description]
	 */
	function correctTagForCustomTaxonomies($request) {
		if ( isset($request['tag']) )
			$request['post_type'] = 'any';

		return $request;
	}

	/**
	 * Adds more mime types for upload in media manager
	 * @param  array  $existing_mimes [description]
	 */
	function customUploadMimes( $existing_mimes=array() ) {

		if( strlen($this->options_general['advanced_wordpress_configuration_plugin_customUploadMimes']) > 0 ) {

			$mimeTypes = array_map('trim', explode(",", $this->options_general['advanced_wordpress_configuration_plugin_customUploadMimes']));

			if( is_array($mimeTypes) ) {
				foreach ($mimeTypes as &$value) {
					$mimetypeDefinition = $this->lookupMimeType($value);

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
	function lookupMimeType($ext) {

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

	/**
	 * Returns the user back to the homepage on logout from backend
	 * @param  [type] $logouturl [description]
	 * @param  [type] $redir     [description]
	 */
	function logoutToHomepage($logouturl, $redir) {
		$redir = get_option('siteurl');
		return $logouturl . '&amp;redirect_to=' . urlencode($redir);
	}


}

?>
