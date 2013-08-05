<?php
/**
   * advanced-wordpress-configuration-pluginWordpressSettings
   * Modifying wordpress
   *
   * @author     T. Boehning
   * @version	1.1
   *
   *
   */
class advancedwordpressconfigurationpluginWordpressSettings {

	protected $options_general;
	protected $options_backend;
	protected $options_frontend;
	protected $options_rss;
	protected $options_javascript;
	protected $options_adminbar;
	protected $options_comments;

  	function __construct() {

  		//theme support
  		add_theme_support('h5bp-htaccess');

  		$this->options_general 		= get_option('advanced_wordpress_configuration_plugin_general');
		$this->options_backend 		= get_option('advanced_wordpress_configuration_plugin_backend');
		$this->options_frontend 	= get_option('advanced_wordpress_configuration_plugin_frontend');
		$this->options_rss 			= get_option('advanced_wordpress_configuration_plugin_rss');
		$this->options_javascript 	= get_option('advanced_wordpress_configuration_plugin_javascript');
		$this->options_adminbar 	= get_option('advanced_wordpress_configuration_plugin_adminbar');
		$this->options_comments 	= get_option('advanced_wordpress_configuration_plugin_comments');

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
				add_action( 'do_meta_boxes', array($this, 'removeMetaBoxes2') );
			}
		}
	





		if( !is_null($this->options_frontend) ) {

			if( isset($this->options_frontend["advanced_wordpress_configuration_plugin_deregisterPluginStyles"]) ) {
				add_action( 'wp_print_styles', array( &$this, 'deregisterPluginStyles'), 100 );
			}

			if( isset($this->options_frontend['advanced_wordpress_configuration_plugin_metaImageThumb']) ) {
				add_action('wp_head', array( &$this, 'metaImageThumb') );
			}

			if( isset($this->options_frontend['advanced_wordpress_configuration_plugin_addBreadcrumbAction']) ) {
				add_action('the_breadcrumb', array( &$this, 'addBreadcrumbAction') );
			}

			if( isset($this->options_frontend['advanced_wordpress_configuration_plugin_setSearchResultsPerPage']) ) {
				add_action('pre_get_posts', array( &$this, 'setSearchResultsPerPage') );
			}

			//remove header stuff
			if( get_option("advanced_wordpress_configuration_plugin_removeHeaderLinks") ) {
				add_action('init', array($this, 'removeHeaderLinks') );
			}
		}



		


		if( !is_null($this->options_rss) ) {

			//set in plugin options, default is false: disable RSS feeds
			if( isset($this->options_rss["advanced_wordpress_configuration_plugin_disableRSS"]) ) {
				add_action('do_feed', array($this, 'disableRSS'), 1);
				add_action('do_feed_rdf', array($this, 'disableRSS'), 1);
				add_action('do_feed_rss', array($this, 'disableRSS'), 1);
				add_action('do_feed_rss2', array($this, 'disableRSS'), 1);
				add_action('do_feed_atom', array($this, 'disableRSS'), 1);
			}
		}




		


		

		if( !is_null($this->options_adminbar) ) {

			//customizes the admin bar
			if( isset($this->options_adminbar["advanced_wordpress_configuration_plugin_removeFromAdminBar"]) ) {
				add_action( 'wp_before_admin_bar_render', array($this, 'removeFromAdminBar') );
			}

			if( isset($this->options_adminbar["advanced_wordpress_configuration_plugin_customizeAdminbar"]) ) {
				add_action( 'wp_before_admin_bar_render', array($this, 'customizeAdminBar') );
			}

			//open site links in new browser window
			if( isset($this->options_adminbar["advanced_wordpress_configuration_plugin_openSiteLinkNewWindow"]) ) {
				add_action( 'wp_before_admin_bar_render', array($this, 'openSiteLinkNewWindow') );
			}

			// additonal help information
			if( isset($this->options_adminbar["advanced_wordpress_configuration_plugin_addHelpForEditors"]) ) {
				add_action( 'load-post.php', array($this, 'addHelpForEditors') );
			}
		}


		

		if (current_theme_supports('h5bp-htaccess')) {
			add_action('generate_rewrite_rules', array($this, 'addH5bpHtaccess') );
		}

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



		
		//backend
		if( !is_null($this->options_backend) ) {
		
			//removes columns from posts view
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_removePostColumns"]) ) {
				add_filter('manage_posts_columns', array($this, 'removePostColumns') );
			}

			//removes columns from page view
			if( isset($this->options_backend["advanced_wordpress_configuration_plugin_removePageColumns"]) ) {
				add_filter('manage_pages_columns', array($this, 'removePageColumns') );
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



		//completely remove admin bar
		if( !is_null($this->options_adminbar) ) {
			if( isset($this->options_adminbar["advanced_wordpress_configuration_plugin_removeAdminbar"]) ) {
				add_filter( 'show_admin_bar', array($this, 'removeAdminbar') );
			}
		}










		//Define how many words to return when using the_excerpt();
		if( !is_null($this->options_frontend) ) {
			if( isset($this->options_frontend["advanced_wordpress_configuration_plugin_customExcerptLength"]) ) {
				add_filter( 'excerpt_length', array($this, 'customExcerptLength') );
			}

			//Pingback Header abschalten
			if( isset($this->options_frontend["advanced_wordpress_configuration_plugin_removePingbackHeader"]) ) {
				add_filter(	'wp_headers', create_function(	'$h','unset($h["X-Pingback"]); return $h;'	));
			}

			// remove 'Read more' link
			if( isset($this->options_frontend["advanced_wordpress_configuration_plugin_removeExcerptMore"]) ) {
				add_filter('excerpt_more', array($this, 'removeExcerptMore'));
			}

			//Add parent page slug to body_class
			if( isset($this->options_frontend["advanced_wordpress_configuration_plugin_addBodyClass"]) ) {
				add_filter('body_class', array($this, 'addBodyClass') );
			}

			//completely remove wordpress version
			if( isset($this->options_frontend["advanced_wordpress_configuration_plugin_removeWordpressVersion"]) ) {
				add_filter('the_generator', array($this, 'removeWordpressVersion') );
			}

			if( isset($this->options_frontend["advanced_wordpress_configuration_plugin_keepUserLoggedIn"]) ) {
				add_filter( 'auth_cookie_expiration', array($this, 'keepUserLoggedIn') );
			}

			//change external links in content
			if( isset($this->options_frontend["advanced_wordpress_configuration_plugin_styleExternalLinks"]) ) {
				add_filter('the_content', array($this, 'styleExternalLinks'));
			}

			//add a class to the first post
			if( isset($this->options_frontend["advanced_wordpress_configuration_plugin_addClassToFirstPost"]) ) {
				add_filter('post_class', array($this, 'styleFirstPost'));
			}

			//adds the category slug to list categories
			if( isset($this->options_frontend["advanced_wordpress_configuration_plugin_addSlugClassToCategoryList"]) ) {
				add_filter('wp_list_categories', array($this, 'addSlugClassToCategoryList'));
			}


			if( isset($this->options_frontend['advanced_wordpress_configuration_plugin_removeWidthHeightFromImage']) ) {
				add_filter( 'post_thumbnail_html', array($this, 'removeWidthHeightFromImage'), 10 );
				add_filter( 'image_send_to_editor', array($this, 'removeWidthHeightFromImage'), 10 );
			}

			//links to large version
			if( isset($this->options_frontend["advanced_wordpress_configuration_plugin_linkToLargeAttachementFile"]) ) {
				add_filter('wp_get_attachment_link', array($this, 'getAttachmentLinkFilter'), 10, 4);
			}	

			//wrap more link in div
			if( isset($this->options_frontend["advanced_wordpress_configuration_plugin_wrapMoreInDiv"]) ) {
				add_filter('the_content_more_link', array($this, 'wrapReadmore'), 10, 1);
			}
		}




		if( !is_null($this->options_rss) ) {
			
			//Delay publish to RSS feed, default 10 minutes
			if( isset($this->options_rss["advanced_wordpress_configuration_plugin_delayPublishRSS"]) ) {
				add_filter('posts_where',  array($this, 'delayPublishRSS') );
			}

			//adds the post thumbnail to the rss feed
			if( isset($this->options_rss["advanced_wordpress_configuration_plugin_addThumbnailToRSS"]) ) {
				add_filter('the_excerpt_rss', array($this, 'addThumbnailToRSS') );
				add_filter('the_content_feed', array($this, 'addThumbnailToRSS') );
			}

			if( isset($this->options_rss["advanced_wordpress_configuration_plugin_customizeRSSFooter"]) ) {
				add_filter('the_excerpt_rss', array($this, 'customizeRSSFooter') );
				add_filter('the_content_feed', array($this, 'customizeRSSFooter') );
			}

			if( isset($this->options_rss["advanced_wordpress_configuration_plugin_removeCategoryFromFeed"]) ) {
				add_filter('pre_get_posts', array($this, 'removeCategoryFromFeed') );
			}
		}










		
		if( !is_null($this->options_javascript) ) {

			//set in plugin options, default is false: add jquery from cdn
			if( isset($this->options_javascript["advanced_wordpress_configuration_plugin_jqueryFromCdn"]) ) {
				add_action( 'init', array($this, 'jqueryFromCdn') );
			}

			//adds a defer='defer' to javascripts in header
			if( isset($this->options_javascript["advanced_wordpress_configuration_plugin_deferJavascript"]) ) {
				add_filter( 'clean_url', array($this, 'addScriptDefer'), 99, 1);
			}
		}


		

		//By default, WordPress counts trackbacks, and pings as comments. This inflates the comment count which looks really bad especially when you are not displaying the trackbacks and pings.
		if( !is_null($this->options_comments) ) {
			if(isset($this->options_comments["advanced_wordpress_configuration_plugin_correctCommentCount"]) ) {
				add_filter('get_comments_number', array($this, 'correctCommentCount'), 0);
			}
		}
		
	}



	/**
	 * The links of regular tags doesnt work with Custom Post Types. This fixes it.
	 */
	function correctTagForCustomTaxonomies($request) {
		if ( isset($request['tag']) )
			$request['post_type'] = 'any';

		return $request;
	}

	/**
	 *
	 */
	function customUploadMimes ( $existing_mimes=array() ) {

		// add your ext =&gt; mime to the array
		$existing_mimes['eps'] = 'application/eps';
		$existing_mimes['indt'] = 'application/octet-stream';
		$existing_mimes['ppt'] = 'application/octet-stream';
	 	$existing_mimes['psd'] = 'application/octet-stream';
	 	$existing_mimes['svg'] = 'image/svg+xml';
		// and return the new full result
		return $existing_mimes;

	}

	/**
	 *
	 */
	function logoutToHomepage($logouturl, $redir) {
		$redir = get_option('siteurl');
		return $logouturl . '&amp;redirect_to=' . urlencode($redir);
	}


	/**
	 *
	 */
	function jqueryFromCdn() {

		if ( !is_admin() ) {

		    wp_deregister_script( 'jquery' );
		    wp_register_script( 'jquery', ( 'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js' ), false, null, true );
		    wp_enqueue_script( 'jquery' );
		}
	}

	/**
	 *
	 */
	function deregisterPluginStyles() {
		
		//download monitor
		wp_deregister_style( 'wp_dlmp_styles' );

		//wp-polls
		wp_deregister_style( 'wp-polls' );
	}

	/**
	 *
	 */
	function keepUserLoggedIn( $expirein ) {
		//year: 31556926
		//month: 2676400

		if( intval($this->options_frontend["advanced_wordpress_configuration_plugin_keepUserLoggedIn"]) > 0 ) return intval($this->options_frontend["advanced_wordpress_configuration_plugin_keepUserLoggedIn"]);
	}

	/**
	 *
	 */
	function removeHeaderLinks() {
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'wp_generator');
		remove_action('wp_head', 'start_post_rel_link');
		remove_action('wp_head', 'index_rel_link');
		remove_action('wp_head', 'feed_links_extra', 3);
		remove_action('wp_head', 'start_post_rel_link', 10, 0);
		remove_action('wp_head', 'parent_post_rel_link', 10, 0);
		remove_action('wp_head', 'adjacent_posts_rel_link');
	}

	/**
	 *
	 */
	function removeWordpressVersion() {
		return '';
	}


	/**
	 * Ausgabe der Artikelbilder als og:image Metatag
	 * TODO: adjust default 
	 */
	function metaImageThumb() {
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
	
	/**
	 * Aufruf und Ausgabe im Template via do_action('the_breadcrumb')
	 * copied from Sergej Müller: https://gist.github.com/sergejmueller/4706816 
	 */
	function addBreadcrumbAction() {
		/* Aussteigen? */
		if ( ! is_singular() ) {
			return;
		}
 
		/* Home */
		$items = array(
			0 => array(
				'url'	=> '/',
				'title' => ''
			)
		);
 
		/* Kategorie */
		if ( $objects = get_the_category() ) {
			$items[] = array(
				'url'	=> get_category_link($objects[0]->term_id),
				'title' => $objects[0]->cat_name
			);
		}
 
		/* Loopen */
		foreach ($items as $item) { ?>
			<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
				<a href="<?php echo esc_url($item['url']) ?>" itemprop="url">
					<span itemprop="title"><?php echo $item['title'] ?></span>
				</a>
			</span>
		<?php }
	}


	//change amount of posts on the search page - set here to 100
	function setSearchResultsPerPage( $query ) {
		global $wp_the_query;
		if ( ( ! is_admin() ) && ( $query === $wp_the_query ) && ( $query->is_search() ) ) {
			$query->set( 'wpfme_search_results_per_page', $this->options_frontend["advanced_wordpress_configuration_plugin_setSearchResultsPerPage"] );
		}
		return $query;
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




	/**
	 *
	 */
	function addH5bpHtaccess($content) {
	  global $wp_rewrite;
	  $home_path = function_exists('get_home_path') ? get_home_path() : ABSPATH;
	  $htaccess_file = $home_path . '.htaccess';
	  $mod_rewrite_enabled = function_exists('got_mod_rewrite') ? got_mod_rewrite() : false;

	  if ((!file_exists($htaccess_file) && is_writable($home_path) && $wp_rewrite->using_mod_rewrite_permalinks()) || is_writable($htaccess_file)) {
	    if ($mod_rewrite_enabled) {
	      $h5bp_rules = extract_from_markers($htaccess_file, 'HTML5 Boilerplate');
	      if ($h5bp_rules === array()) {
	        $filename = dirname(__FILE__) . '/h5bp-htaccess';
	        return insert_with_markers($htaccess_file, 'HTML5 Boilerplate', extract_from_markers($filename, 'HTML5 Boilerplate'));
	      }
	    }
	  }

	  return $content;
	}




	/**
 	 * adds defer='defer' to javascript in header to defer loading
	 */
	function deferJavascript($file) {
		if ( strpos($file, '.js') !== false ) {
			return sprintf(
				"%s' defer='defer",
				$file
			);
		}

		return $file;
	}

	

	/**
 	 * adds a new default gravatar
 	 * @TODO: change url and name of gravatar
	 */
	function addNewGravatar ($avatar_defaults) {

		$path = trailingslashit($this->options_backend['advanced_wordpress_configuration_plugin_addNewGravatar']);

		$myavatar =  get_bloginfo('template_directory').'/'.$path.'customavatar.png';
		$avatar_defaults[$myavatar] = "Custom"; 

		return $avatar_defaults;
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
	function showAdminMessages() {
		if(strlen($this->options_backend['advanced_wordpress_configuration_plugin_showAdminMessage']) > 2)
	    	$this->showAdminMessage($this->options_backend['advanced_wordpress_configuration_plugin_showAdminMessage'], true);
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

	function removeMetaBoxes2() {

		if( $this->options_backend["advanced_wordpress_configuration_plugin_removeMetaBoxes"] ) {
			

			$selectedOptions = explode( ',', $this->options_backend["advanced_wordpress_configuration_plugin_removeMetaBoxes"] );

			foreach ($selectedOptions as $key => $value) {
				
				switch (trim($value)) {
	
					case 'postimagediv':
						remove_meta_box( trim($value), 'post', 'side' );
						break;

					default:
						# code...
						break;
				}
			}
		}
	}




	/**
 	 * includes an additional stylesheet on backend login page
 	 * @TODO: change image
	 */
	function customLoginImage(){

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

	/**
 	 * includes an additional stylesheet on backend login page
 	 * @TODO: change pass to css
	 */
	function customLoginCSS() {

		$path = trailingslashit($this->options_backend['advanced_wordpress_configuration_plugin_customLoginCSS']);

		echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/'.$path.'wp-login.css" />';
	}

	/**
 	 * includes an additional stylesheet in backend
 	 * @TODO: change path to css file
	 */
	function customBackend() {

		$path = trailingslashit($this->options_backend['advanced_wordpress_configuration_plugin_customBackend']);

		echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/'.$path.'wp-admin.css" />';
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









	/* Admin Bar related __________________________________________________________*/


	function removeAdminbar() {

		return false; //for all users

		//return ( current_user_can( 'administrator' ) ) ? $content : false; //except for the admins
	}

	/*
	* Remove the WordPress Logo from the WordPress Admin Bar
	*/
	function removeFromAdminBar() {
		global $wp_admin_bar;

		/* Aktiv und Admin? */
		if ( !is_admin_bar_showing() ) {
			return;
		}

		$selectedOptions = explode( ',', $this->options_adminbar["advanced_wordpress_configuration_plugin_removeFromAdminBar"] );

		foreach ($selectedOptions as $key => $value) {
			$wp_admin_bar->remove_menu(trim($value));
		}

	}

	/*
	* Remove the WordPress Logo from the WordPress Admin Bar
	*/
	function customizeAdminBar() {
		global $wp_admin_bar;

		/* Aktiv und Admin? */
		if ( !is_admin_bar_showing() ) {
			return;
		}

	    /* Add new menus */
	    /*$wp_admin_bar->add_menu(array(
			'id' => 'wp-admin-bar-new-item',
			'title' => __('Titel'),
			'href' => 'Link'
		));*/


		/* Suche definieren */
		$form  = '<form action="' .esc_url( admin_url('edit.php') ). '" method="get" id="adminbarsearch">';
		$form .= '<input class="adminbar-input" name="s" tabindex="1" type="text" value="" maxlength="50" />';
		$form .= '<input type="submit" class="adminbar-button" value="' .__('Search'). '"/>';
		$form .= '</form>';
		
		/* Suche einbinden */
		$wp_admin_bar->add_menu(
			array(
				'parent' => 'top-secondary',
				'id'     => 'search',
				'title'  => $form,
				'meta'   => array(
					'class' => 'admin-bar-search'
				)
			)
		);
	}


	/*
	* Make Visit Site links open in a new window: My Sites > Site Name > Visit Site
	*/
	function openSiteLinkNewWindow() {
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








	/* Comments related ____________________________________________________________*/

	function correctCommentCount( $count ) {
		if ( ! is_admin() ) {
			global $id;
			$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
			return count($comments_by_type['comment']);
		} else {
			return $count;
		}
	}






	/* Backend related _________________________________________________________________*/

	/**
	 * Customize User Contact Info
	 */
	function addUserContactFields($contactmethods) {

		//add fields
		$contactmethods['phone'] = 'Telefonnummer';	//add phone number input field

		return $contactmethods;
	}

	function removeUserContactFields($contactmethods) {

		$selectedOptions = explode( ',', $this->options_backend["advanced_wordpress_configuration_plugin_removeUserContactFields"] );

		foreach ($selectedOptions as $key => $value) {
			unset($contactmethods[trim($value)]);
		}
		
		return $contactmethods;
	}

	/**
	 * adds information to the help dropdown in the backend
	 */
	function addHelpForEditors() {
		$screen = get_current_screen();

		switch($screen->id) {
			//help on pages
			case 'page':
				$content = '<p>Der Haupt-Editor sollte nichts enthalten, bitte die sections verwenden.</p>
						<p>Mit dem more-tag k&ouml;nnen Inhalte in zwei Spalten aufgeteilt werden.</p>';
				break;

			//help on news posts
			case 'post':
				$content = '<p>Mit dem more-tag k&ouml;nnen Inhalte in zwei Spalten aufgeteilt werden.</p>';
				break;

			default:
				$content = '<p>Keine weiteren Informationen verf&uuml;bar.</p>';
				break;
		}

		$screen->add_help_tab( array(
			'id'      => 'kr',
			'title'   => __('Helpadvanced-wordpress-configuration-plugin', 'advanced-wordpress-configuration-plugintheme'),
			'content' => $content ,
		));
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



	/* RSS related ____________________________________________________*/

	/**
 	 * disables the RSS feed
	 *
	 */
	function disableRSS() {
		wp_die( __('No feed available, please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
	}

	/**
 	 * adds the thumbnail picture to the RSS feed
	 *
	 */
	function addThumbnailToRSS($content) {
		global $post;
		if(has_post_thumbnail($post->ID)) {
		$content = '<p>' . get_the_post_thumbnail($post->ID) .
		'</p>' . get_the_content();
		}
		return $content;
	}

	/*
	* all bloggers make errors that we catch after we publish the post. Sometimes even within the next minute or two. That is why it is best that we delay our posts to be published on the RSS by 5-10 minutes.
	*/
	function delayPublishRSS($where) {

		if( $this->options_rss["advanced_wordpress_configuration_plugin_delayPublishRSS"] != 'false' ) {

			global $wpdb;

			if ( is_feed() ) {
				// timestamp in WP-format
				$now = gmdate('Y-m-d H:i:s');

				// value for wait; 

				$time = ( intval($this->options_rss["advanced_wordpress_configuration_plugin_delayPublishRSS"]) > 0 ) ? $this->options_rss["advanced_wordpress_configuration_plugin_delayPublishRSS"] : '10';

				$wait = $time; // integer

				// http://dev.mysql.com/doc/refman/5.0/en/date-and-time-functions.html#function_timestampdiff
				$device = 'MINUTE'; //MINUTE, HOUR, DAY, WEEK, MONTH, YEAR

				// add SQL-sytax to default $where
				$where .= " AND TIMESTAMPDIFF($device, $wpdb->posts.post_date_gmt, '$now') > $wait ";
			}
		}

		return $where;
		
	}

	/*
	* 
	*/
	function customizeRSSFooter($content) {
		if(is_feed()){
			if(strlen($this->options_rss['advanced_wordpress_configuration_plugin_customizeRSSFooter']) > 0 ) $content .= $this->options_rss['advanced_wordpress_configuration_plugin_customizeRSSFooter'];
		}
		return $content;
	}


	/*
	* custom feed query
	*/
	function removeCategoryFromFeed($query) {
		if(is_feed()) {
			$query->set('cat',$this->options_rss['advanced_wordpress_configuration_plugin_removeCategoryFromFeed']);
			return $query;
		}
	}





	/* Content modification ____________________________________________________*/

	/**
	 *
	 */
	function addBodyClass($classes) {
		global $wpdb, $post;
		if (is_page()) {
		    if ($post->post_parent) {
			$parent  = end(get_post_ancestors($current_page_id));
		    } else {
			$parent = $post->ID;
		    }
		    $post_data = get_post($parent, ARRAY_A);
		    $classes[] = 'section-' . $post_data['post_name'];
		}
		return $classes;
	}

	/**
	 * sets the length of the excerpt (for get_the_excerpt)
	 */
	function customExcerptLength( $length ) {
		//the amount of words to return

		if( intval($this->options_frontend["advanced_wordpress_configuration_plugin_customExcerptLength"]) > 0 ) return intval($this->options_frontend["advanced_wordpress_configuration_plugin_customExcerptLength"]);
	}

	/**
	 * add class to external links in content
	 */
	function styleExternalLinks($content) {
		$regexp = '/\<a[^\>]*(target="_([\w]*)")[^\>]*\>[^\<]*\<\/a>/smU';
		if (preg_match_all($regexp, $content, $matches) ){
			for ($m=0; $m < count($matches[0]); $m++) {
				if ($matches[2][$m] == 'blank') {
					$temp = str_replace($matches[1][$m], $matches[1][$m] . 'class="external"', $matches[0][$m]);
					$content = str_replace($matches[0][$m], $temp, $content);
				}
			}
		}
		return $content;
	}

	/**
	 * 
	 */
	function styleFirstPost( $classes ) {
	    global $wp_query;
	    if( 0 == $wp_query->current_post )
	        $classes[] = 'first';
	        return $classes;
	}

	/**
	 * adds the category id to wp_list_categories
	 */
	
	function addSlugClassToCategoryList($list) {

		$cats = get_categories('hide_empty=0');
			foreach($cats as $cat) {
				$find = 'cat-item-' . $cat->term_id . '"';
				$replace = 'cat-item-' . $cat->slug . ' cat-item-' . $cat->term_id . '"';
				$list = str_replace( $find, $replace, $list );

			}

		return $list;
	}

	/**
	 * 
	 */
	function removeWidthHeightFromImage( $html ) {
	   $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
	   return $html;
	}

	/**
	 * Removes the link to the full version of an image and replaces it by a link to the large version
	 * copied from: http://oikos.org.uk/2011/09/tech-notes-using-resized-images-in-wordpress-galleries-and-lightboxes/
	 * @param  [type] $content   [description]
	 * @param  [type] $post_id   [description]
	 * @param  [type] $size      [description]
	 * @param  [type] $permalink [description]
	 * @return [type]            [description]
	 */
	function getAttachmentLinkFilter( $content, $post_id, $size, $permalink ) {
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

	/**
	 * Wraps the more-link in a div instead of a paragraph
	 * @param  [type] $more_link [description]
	 * @return [type]            [description]
	 */
	function wrapReadmore($more_link) {
		return '<div class="post-readmore">'.$more_link.'</div>';
	}

	/**
 	 * remove "Read more" link and simply display three dots
	 *
	 */
	function removeExcerptMore($more) {
		return '...';
	}


}

?>
