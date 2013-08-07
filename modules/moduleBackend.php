<?php
/**
   * advanced-wordpress-configuration-pluginWordpressSettings
   * Module Backend
   *
   * @author     T. Boehning
   * @version	1.0
   *
   *
   */
class moduleBackend {

	protected $options_general;
	protected $options_backend;
	protected $options_frontend;
	protected $options_rss;
	protected $options_javascript;
	protected $options_adminbar;
	protected $options_comments;

  	function __construct() {

		$this->options_backend 		= get_option('advanced_wordpress_configuration_plugin_backend');

		$this->registerActions();
		$this->registerFilters();
	}

	/**
	 * register the actions - all set via options page
	 */
	function registerActions() {

		if( !is_null($this->options_backend) ) {
		
			//remove menus we never use
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_removeBackendMenus"]) ) {
				add_action('admin_menu', array($this, 'removeBackendMenus') );
			}
		
			//remove default widgets we never use
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_customizeDashboardWidgets"]) ) {
				add_action('wp_dashboard_setup', array($this, 'customizeDashboardWidgets') );
			}

			// Custom CSS for the whole admin area
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_customBackend"]) ) {
				add_action('admin_head', array($this, 'customBackend'));
			}

			//loads a background image into login screen
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_customLoginImage"]) ) {
				add_action( 'login_enqueue_scripts', array($this, 'customLoginImage') );
			}

			// Custom CSS for the login page
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_customLoginCSS"]) ) {
				add_action('login_head', array($this, 'customLoginCSS'));
			}

			//removes color scheme selection from user profile
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_admin_color_scheme_picker"]) ) {
				remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
			}

			// remove the notifications of the core
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_hideUpdateNag"]) ) {
				remove_action( 'admin_menu', 'hideUpdateNag' );
			}

			//disables the dragging of widgets in dashboard
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_disableDragDashboardWidgets"]) ) {
				add_action( 'admin_init', array($this, 'disableDragDashboardWidgets') );
			}

			//shows an admin message to backend users
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_showAdminMessage"]) ) {
				add_action( 'admin_notices', array($this, 'showAdminMessages') );
			}

			//shows an admin message to backend users
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_hideHelpTab"]) ) {
				add_action( 'admin_head', array($this, 'hideHelpTab') );
			}

			//removes meta boxes from editing screen
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_removeMetaBoxes"]) ) {
				add_action( 'admin_menu', array($this, 'removeMetaBoxes') );
			}
		}
	}



	/**
	 * register the filters - all set via options page
	 */
	function registerFilters() {
		
		//backend
		if( !is_null($this->options_backend) ) {
		
			//removes columns from page view
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_removePageColumns"]) ) {
				add_filter('manage_pages_columns', array($this, 'removePageColumns') );
			}

			//removes columns from posts view
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_removePostColumns"]) ) {
				add_filter('manage_posts_columns', array($this, 'removePostColumns') );
			}

			//Enable shortcodes in widgets
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_addShortcodetoWidgets"]) ) {
				add_filter('widget_text', 'do_shortcode', 11);
			}

			//custom tiny mce
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_customTinymce"]) ) {
				add_filter( 'mce_buttons_2', array($this, 'customTinymce') );
				add_filter( 'tiny_mce_before_init', array($this, 'customTinymceSettings') );
			}

			//change text in backend footer
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_backendChangeFooter"]) ) {
				add_filter('admin_footer_text', array($this, 'backendChangeFooter'));
			}

			//changes the Wordpress version text in footer
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_backendChangeFooterVersion"]) ) {
				add_filter('update_footer', array($this, 'backendChangeFooterVersion'), 9999 );
			}

			//add phone field to user and hide other info
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_addUserContactFields"]) ) {
				add_filter('user_contactmethods', array($this, 'addUserContactFields'));
			}

			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_removeUserContactFields"]) ) {
				add_filter('user_contactmethods', array($this, 'removeUserContactFields'));
			}

			//add a new avatar to avatar select options
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_addNewGravatar"]) ) {
				add_filter('avatar_defaults', array($this, 'addNewGravatar'));
			}

			//add iframe support in tinymce
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_addIframeSupportToTinyMCE"]) ) {
				add_filter('tiny_mce_before_init', create_function( '$a', '$a["extended_valid_elements"] = "iframe[*]"; return $a;') );
			}

			//add thumbnail column to posts
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_addPostThumbnailColumn"]) ) {
				add_filter('manage_posts_columns', array($this, 'addPostThumbnailColumn'), 5);
				add_action('manage_posts_custom_column', array($this, 'displayPostThumbnailColumn'), 5, 2);
			}

			//add thumbnail column to posts
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_addPageThumbnailColumn"]) ) {
				add_filter('manage_pages_columns', array($this, 'addPostThumbnailColumn'), 5);
				add_action('manage_pages_custom_column', array($this, 'displayPostThumbnailColumn'), 5, 2);
			}

			//remove post format UI (WordPress 3.6 and up)
			//copied from http://bueltge.de/post-format-ui-deaktivieren/2587/
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_hidePostFormatUI"]) ) {
				add_filter( 'enable_post_format_ui', '__return_false' );
			}
		}
	}

	/**
 	 * removes backend menus
	 */
	function removeBackendMenus () {

		$selectedOptions = explode( ',', $this->options_backend["advanced_wordpress_configuration_plugin_removeBackendMenus"] );

		global $menu;

		foreach ($selectedOptions as $key => $value) {
			$restricted[] = __(trim($value));
		}

		/* additional: restrict for certain user-name
		 *global $current_user;
		  get_currentuserinfo();

		  if($current_user->user_login == 'clients-username'){ .. }
		 */

		end ($menu);
		while (prev($menu)){
			$value = explode(' ',$menu[key($menu)][0]);
			if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
		}
	}

	/**
 	 * removes default widgets from dashboard and adds new widgets
	 */
	function customizeDashboardWidgets() {

		global $wp_meta_boxes;

		$selectedOptions = explode( ',', $this->options_backend["advanced_wordpress_configuration_plugin_customizeDashboardWidgets"] );

		foreach ($selectedOptions as $key => $value) {
			remove_meta_box( trim($value), 'dashboard', 'core');
		}
	}


	/**
 	 * adds a custom dashboard widget (with no content yet...)
 	 * TODO: add content
	 */
	function customDashboardWidget() {
		echo '<p>Willkommen</p>';
	}

	/**
 	 * includes an additional stylesheet in backend
 	 * @TODO: change path to css file
	 */
	function customBackend() {

		if(strlen($this->options_backend['advanced_wordpress_configuration_plugin_customBackend']) > 0 ) {

			$path = trailingslashit($this->options_backend['advanced_wordpress_configuration_plugin_customBackend']);

			echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/'.$path.'wp-admin.css" />';
		}
	}

	/**
 	 * includes an additional stylesheet on backend login page
 	 * @TODO: change image
	 */
	function customLoginImage(){

		if(strlen($this->options_backend['advanced_wordpress_configuration_plugin_customLoginImage']) > 0 ) {

			$path = trailingslashit($this->options_backend['advanced_wordpress_configuration_plugin_customLoginImage']);

			echo '
			<div class="background-cover"></div>
			<style type="text/css" media="screen">
				.background-cover{
					background:url('.get_bloginfo('template_directory').'/'.$path.'bg_login.jpg) no-repeat center center fixed;
					-webkit-background-size: cover;
					-moz-background-size: cover;
					-o-background-size: cover;
					background-size: cover;
					position:fixed;
					top:0;
					left:0;
					z-index:10;
					overflow: hidden;
					width: 100%;
					height:100%;
				}
				#login{ z-index:9999; position:relative; }
				.login form { box-shadow: 0px 0px 0px 0px !important; }
				.login h1 a { background:url('.get_bloginfo('template_directory').'/'.$path.'logo.png) no-repeat center top !important; }
				input.button-primary, button.button-primary, a.button-primary{
					border-radius: 3px !important; 	background:url('.get_bloginfo('template_directory').'/'.$path.'button.jpg);
						border:none !important;
						font-weight:normal !important;
						text-shadow:none !important;
					}
					.button:active, .submit input:active, .button-secondary:active {
						background:#96C800 !important;
						text-shadow: none !important;
					}
					.login #nav a, .login #backtoblog a {
						color:#fff !important;
						text-shadow: none !important;
					}
					.login #nav a:hover, .login #backtoblog a:hover{
						color:#96C800 !important;
						text-shadow: none !important;
					}
					.login #nav, .login #backtoblog{
						text-shadow: none !important;
					}
				</style>
			';
		}
	}

	/**
 	 * includes an additional stylesheet on backend login page
 	 * @TODO: change pass to css
	 */
	function customLoginCSS() {

		if(strlen($this->options_backend['advanced_wordpress_configuration_plugin_customLoginCSS']) > 0 ) {

			$path = trailingslashit($this->options_backend['advanced_wordpress_configuration_plugin_customLoginCSS']);

			echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/'.$path.'wp-login.css" />';
		}
	}

	/**
	 *
	 */
	function hideUpdateNag() {
	    remove_action( 'admin_notices', 'update_nag', 3 );
	}

	/**
	 *
	 */
	function disableDragDashboardWidgets() {
	    wp_deregister_script('postbox');
	}

	function showAdminMessages() {
		if(strlen($this->options_backend['advanced_wordpress_configuration_plugin_showAdminMessage']) > 0)
	    	$this->showAdminMessage($this->options_backend['advanced_wordpress_configuration_plugin_showAdminMessage'], true);
	}

	/**
	 * @param: Message
	 */
	function showAdminMessage($message, $errormsg = false) {
		if ($errormsg) {
			echo '<div id="message" class="error">';
		}
		else {
			echo '<div id="message" class="updated fade">';
		}
		echo "<p><strong>$message</strong></p></div>";
	} 

	/**
	 * just adds a style, could be added to admin stylesheet
	 */
	function hideHelpTab() {
	    echo '<style type="text/css">
	            #contextual-help-link-wrap { display: none !important; }
	          </style>';
	}

	/**
	 * @TODO: choose which meta boxes to remove
	 */
	function removeMetaBoxes() {

		if( $this->options_backend["advanced_wordpress_configuration_plugin_removeMetaBoxes"] ) {
			

			$selectedOptions = explode( ',', $this->options_backend["advanced_wordpress_configuration_plugin_removeMetaBoxes"] );

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

	/**
 	 * removes backend columns from page view
	 */
	function removePageColumns($defaults) {

		$selectedOptions = explode( ',', $this->options_backend["advanced_wordpress_configuration_plugin_removePageColumns"] );

		foreach ($selectedOptions as $key => $value) {
			unset($defaults[trim($value)]);
		}

		return $defaults;
	}

	/**
 	 * removes backend columns from post view
	 */
	function removePostColumns($defaults) {

		$selectedOptions = explode( ',', $this->options_backend["advanced_wordpress_configuration_plugin_removePostColumns"] );

		foreach ($selectedOptions as $key => $value) {
			unset($defaults[trim($value)]);
		}

		return $defaults;
	 }

	 /**
 	 * sets the footer information in backend
	 */
	function backendChangeFooter () {
		echo $this->options_backend['advanced_wordpress_configuration_plugin_backendChangeFooter'];
	}

	/**
 	 * sets the footer information in backend
	 */
	function backendChangeFooterVersion() {
		return $this->options_backend['advanced_wordpress_configuration_plugin_backendChangeFooterVersion'];
	}

	/**
	 * Customize User Contact Info
	 */
	function addUserContactFields($contactmethods) {

		if(strlen($this->options_backend['advanced_wordpress_configuration_plugin_addUserContactFields']) > 0 ) {

			$contactFields = array_map('trim', explode(",", $this->options_backend['advanced_wordpress_configuration_plugin_addUserContactFields']));

			if( is_array($contactFields) ) {
				foreach ($contactFields as &$value) {
					$contactmethods[urlencode(strtolower($value))] = $value;
				}
			}
		}

		return $contactmethods;
	}

	/**
	 * [removeUserContactFields description]
	 * @param  [type] $contactmethods [description]
	 * @return [type]                 [description]
	 */
	function removeUserContactFields($contactmethods) {

		if(strlen($this->options_backend['advanced_wordpress_configuration_plugin_removeUserContactFields']) > 0 ) {

			$selectedOptions = explode( ',', $this->options_backend["advanced_wordpress_configuration_plugin_removeUserContactFields"] );

			foreach ($selectedOptions as $key => $value) {
				unset($contactmethods[trim($value)]);
			}

		}

		return $contactmethods;
	}

	

	/**
 	 * adds a new default gravatar
 	 * @TODO: change url and name of gravatar
	 */
	function addNewGravatar ($avatar_defaults) {

		if( strlen($this->options_backend['advanced_wordpress_configuration_plugin_addNewGravatar']) > 0 ) {

			$path = trailingslashit($this->options_backend['advanced_wordpress_configuration_plugin_addNewGravatar']);

			$myavatar =  get_bloginfo('template_directory').'/'.$path.'customavatar.png';
			$avatar_defaults[$myavatar] = "Custom"; 
		}

		return $avatar_defaults;
	}

	// Add the column
	function addPostThumbnailColumn($cols){
		$cols['kr_post_thumb'] = __('Thumbnail');
		return $cols;
	}

	// Grab featured-thumbnail size post thumbnail and display it.
	function displayPostThumbnailColumn($col, $id){
	  switch($col){
	    case 'kr_post_thumb':
	      if( function_exists('the_post_thumbnail') )
	        echo the_post_thumbnail( 'admin-list-thumb' );
	      else
	        echo 'Not supported in theme';
	      break;
	  }
	}





	/* TinyMCE related ____________________________________________________*/

	/**
 	 * includes an additional stylesheet in backend
 	 * settings will be overwritten by TinyMceAdvanced plugin...
	 */
	function customTinymce( $buttons ) {

		// based on: http://alisothegeek.com/2011/05/tinymce-styles-dropdown-wordpress-visual-editor/

		array_unshift( $buttons, 'formatselect' );
		return $buttons;
	}

	/**
 	 * defines available styles
 	 * TODO: remove stuff from formats as well...
	 */
	function customTinymceSettings( $settings ) {

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






	/* Debugging related ____________________________________________________*/

	/**
 	 * Display Errors as Admin Alerts
 	 * TODO: set path to error logfile
 	 * TODO: set as option
	 */
	function showErrorsInBackend() {
		$logfile = '/logs/error_log.log'; // Enter the server path to your logs file here
		$displayErrorsLimit = 50; // The maximum number of errors to display in the widget
		$errorLengthLimit = 300; // The maximum number of characters to display for each error
		$fileCleared = false;
		$userCanClearLog = current_user_can( 'manage_options' );
		// Clear file?
		if ( $userCanClearLog && isset( $_GET["slt-php-errors"] ) && $_GET["slt-php-errors"]=="clear" ) {
			$handle = fopen( $logfile, "w" );
			fclose( $handle );
			$fileCleared = true;
		}
		// Read file
		if ( file_exists( $logfile ) ) {
			$errors = file( $logfile );
			$errors = array_reverse( $errors );
			if ( $fileCleared ) echo '<p><em>File cleared.</em></p>';
			if ( $errors ) {
				echo '<p>'.count( $errors ).' error';
				if ( $errors != 1 ) echo 's';
				echo '.';
				if ( $userCanClearLog ) echo ' [ <b><a href="'.get_bloginfo("url").'/wp-admin/?slt-php-errors=clear" onclick="return confirm(\'Are you sure?\');">Log-Datei leeren</a></b> ]';
				echo '</p>';
				echo '<div id="slt-php-errors" style="height:250px;overflow:scroll;padding:2px;background-color:#faf9f7;border:1px solid #ccc;">';
				echo '<ol style="padding:0;margin:0;">';
				$i = 0;
				foreach ( $errors as $error ) {
					echo '<li style="padding:2px 4px 6px;border-bottom:1px solid #ececec;">';
					$errorOutput = preg_replace( '/\[([^\]]+)\]/', '<b>[$1]</b>', $error, 1 );
					if ( strlen( $errorOutput ) > $errorLengthLimit ) {
						echo substr( $errorOutput, 0, $errorLengthLimit ).' [...]';
					} else {
						echo $errorOutput;
					}
					echo '</li>';
					$i++;
					if ( $i > $displayErrorsLimit ) {
						echo '<li style="padding:2px;border-bottom:2px solid #ccc;"><em>Mehr als '.$displayErrorsLimit.' Fehler im Log...</em></li>';
						break;
					}
				}
				echo '</ol></div>';
			} else {
				echo '<p>Zur Zeit ist das Error-Log leer.</p>';
			}
		} else {
			echo '<p><em>Das Error-Log konnte nicht gelesen werden. Bitte pr&uuml;fe den Pfad.</em></p>';
		}
	}

}

?>
