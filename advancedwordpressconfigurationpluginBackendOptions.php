<?php
/**
   * advanced-wordpress-configuration-pluginBackendOptions
   * setup of the backen options page, base partly on this tutorial: http://wp.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-1/
   *
   * @author     T. Boehning
   * @version	1.2
   */
class advancedwordpressconfigurationpluginBackendOptions {

	protected $settings_sections;
	protected $page_name;
	protected $activeTab;


	/**
	 * Initializes the plugin
	 */
 	function __construct() {

 		$this->page_name = 'advanced-wordpress-configuration-plugin-options';

 		$this->setting_sections = array( 	
 								'advanced_wordpress_configuration_plugin_general' 	=> __('General', 'advanced-wordpress-configuration-plugin-locale'), 
								'advanced_wordpress_configuration_plugin_backend' 	=>  __('Backend', 'advanced-wordpress-configuration-plugin-locale'), 
								'advanced_wordpress_configuration_plugin_frontend' 	=>  __('Frontend', 'advanced-wordpress-configuration-plugin-locale'), 
								'advanced_wordpress_configuration_plugin_rss' 		=>  __('RSS', 'advanced-wordpress-configuration-plugin-locale'), 
								'advanced_wordpress_configuration_plugin_javascript' =>  __('Javascript', 'advanced-wordpress-configuration-plugin-locale'), 
								'advanced_wordpress_configuration_plugin_adminbar' 	=>  __('Adminbar', 'advanced-wordpress-configuration-plugin-locale'), 
								'advanced_wordpress_configuration_plugin_comments' 	=>  __('Comments', 'advanced-wordpress-configuration-plugin-locale') );

		add_action('admin_menu', array(&$this, 'createOptionsPage'));

	}

	


	/**
	 * creates a setting page
	 */
	function createOptionsPage() {

		add_options_page(	'Advanced Wordpress Configuration Plugin', //the page title
							'Advanced Wordpress Configuration',  //Menu title
							'manage_options',  //capabilitites to show menu entry
							$this->page_name, //slug, unique id
							array($this, 'displayadvancedwordpressconfigurationpluginSettingsPage') //callback
						);

		add_action('admin_init', array($this, 'registerPluginSettings') );
	}

	/**
	 * displays the settings page
	 */
	function displayadvancedwordpressconfigurationpluginSettingsPage() {

		$this->activeTab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'advanced_wordpress_configuration_plugin_general';

		echo '<div class="wrap">';

		screen_icon();

		echo '<h2>'.__('Advanced Wordpress Configuration Options', 'advanced-wordpress-configuration-plugin-locale').'</h2>';

		echo '<form method="post" action="options.php">';

		//tabs wrapper
		echo '<h2 class="nav-tab-wrapper">';
		foreach( $this->setting_sections as $tab => $name ){
	    	$class = ( $tab == $this->activeTab ) ? ' nav-tab-active' : '';
	        echo "<a class='nav-tab$class' href='?page=".$this->page_name."&tab=$tab'>$name</a>";
	    }
		echo '</h2>';

		//render settings for active tab
		settings_fields($this->activeTab);
	    do_settings_sections($this->activeTab);

		submit_button();

		echo '</form>
				</div><div class="warning" style="font-size: 0.8em;">* changes in the plugin breaks updatability of this plugin. Be warned.</div>';

		
	}


	/**
	 * 
	 */
	function registerPluginSettings() {

		//1. setup options
		foreach( $this->setting_sections as $tab => $name ){ //$tab = advanced_wordpress_configuration_plugin_general, $name = General
	    	add_option( $tab );
	    }



		//2. Add a setting section that will contain one or more setting fields: id of section, title, name of output function, name of section
		add_settings_section(
			'advanced_wordpress_configuration_plugin_general_section', 			// ID used to identify this section and with which to register options
			'General Options', 						// Title to be displayed on the administration page  
			array($this, 'intro_advanced_wordpress_configuration_plugin_general'), 	// Callback used to render the description of the section  
			'advanced_wordpress_configuration_plugin_general'			// Page on which to add this section of options  
		);

		add_settings_section('advanced_wordpress_configuration_plugin_backend_section', __('Backend Options', 'advanced-wordpress-configuration-plugin-locale'), 	array($this, 'intro_advanced_wordpress_configuration_plugin_backend'), 'advanced_wordpress_configuration_plugin_backend');
		add_settings_section('advanced_wordpress_configuration_plugin_rss_section', 	__('RSS Options', 'advanced-wordpress-configuration-plugin-locale'), 		array($this, 'intro_advanced_wordpress_configuration_plugin_rss'), 'advanced_wordpress_configuration_plugin_rss');
		add_settings_section('advanced_wordpress_configuration_plugin_frontend_section', __('Frontend Options', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'intro_advanced_wordpress_configuration_plugin_frontend'), 'advanced_wordpress_configuration_plugin_frontend');
		add_settings_section('advanced_wordpress_configuration_plugin_javascript_section', __('Javascript Options', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'intro_advanced_wordpress_configuration_plugin_javascript'), 'advanced_wordpress_configuration_plugin_javascript');
		add_settings_section('advanced_wordpress_configuration_plugin_adminbar_section', __('Adminbar Options', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'intro_advanced_wordpress_configuration_plugin_adminbar'), 'advanced_wordpress_configuration_plugin_adminbar');
		add_settings_section('advanced_wordpress_configuration_plugin_comments_section', __('Comments Options', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'intro_advanced_wordpress_configuration_plugin_comments'), 'advanced_wordpress_configuration_plugin_comments');		



		//3. setting field that will set a value for the settings in the setting group

		//general
		$this->addSettingsGeneral();

		//backend
		$this->addSettingsBackend();

		//frontend
		$this->addSettingsFrontend();

		//rss
		$this->addSettingsRss();

		//javascript
		$this->addSettingsJavascript();

		//admin bar
		$this->addSettingsAdminbar();

		//comments
		$this->addSettingsComments();

		
		

		//4. now register the settings

		foreach( $this->setting_sections as $tab => $name ){ //$tab = advanced_wordpress_configuration_plugin_general, $name = General
	    	register_setting(  
			    $tab,  //A settings group name. Must exist prior to the register_setting call. This must match the group name in settings_fields
			    $tab,  //The name of an option to sanitize and save
			    array($this, 'sanitizeInput')
			); 
	    }

	}



	/**
	 * Defines settings for general section
	 */
	function addSettingsGeneral() {
		add_settings_field('advanced_wordpress_configuration_plugin_correctTagForCustomTaxonomies', __('Correct tags for custom taxonomies', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_general', 'advanced_wordpress_configuration_plugin_general_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_correctTagForCustomTaxonomies', 'type' => 'checkbox', 'label' => 'The links of regular tags doesnt work with Custom Post Types. This fixes it.' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_customUploadMimes', __('Add more upload mimetypes', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_general', 'advanced_wordpress_configuration_plugin_general_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_customUploadMimes', 'type' => 'checkbox', 'label' => '* Options need to be set directly in plugin (sets eps, indt, ppt, svg and psd by default).' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_logoutToHomepage', __('Logout to homepage', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_general', 'advanced_wordpress_configuration_plugin_general_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_logoutToHomepage', 'type' => 'checkbox', 'label' => 'Logout redirects to homepage' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_disableAutoP', __('Disable WP Auto P', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_general', 'advanced_wordpress_configuration_plugin_general_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_disableAutoP', 'type' => 'checkbox', 'label' => 'Disable automatic Paragraph-Tag addition by Wordpress.' ) );
	}

	/**
	 * Defines settings for backend section
	 */
	function addSettingsBackend() {
		
		add_settings_field(
			'advanced_wordpress_configuration_plugin_removeBackendMenus', 		// ID used to identify the field throughout the theme
			 __('Remove backend menus', 'advanced-wordpress-configuration-plugin-locale'), 	// The label to the left of the option interface element  
			array($this, 'renderOptions'), 			// The name of the function responsible for rendering the option interface  
			'advanced_wordpress_configuration_plugin_backend', 					// The page on which this option will be displayed  
			'advanced_wordpress_configuration_plugin_backend_section', 			// The name of the section to which this field belongs  
			array( 									//// The array of arguments to pass to the callback.
				'option_name' => 'advanced_wordpress_configuration_plugin_removeBackendMenus', 
				'type' => 'selectmultiple',  'options' => array(
					'Posts' => 'Posts', 
					'Links' => 'Links', 
					'Pages' => 'Pages',
					'Comments' => 'Comments',
					'Appearance' => 'Appearance',
					'Plugins' => 'Plugins',
					'Users' => 'Users',
					'Tools' => 'Tools',
					'Settings' => 'Settings'),
				'label' => 'Select main menu entries to remove (for all users):' 
			) 
		);

		add_settings_field('advanced_wordpress_configuration_plugin_customizeDashboardWidgets', __('Customize dashboard widgets', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 
					'option_name' => 'advanced_wordpress_configuration_plugin_customizeDashboardWidgets', 
					'type' => 'selectmultiple',  
					'options' => array(
						'dashboard_quick_press' => 'Dashboard: QuickPress', 
						'dashboard_plugins' => 'Dashboard: Plugins', 
						'dashboard_recent_comments' => 'Dashboard: Recent Comments',
						'dashboard_primary' => 'Dashboard: Wordpress Blog',
						'dashboard_secondary' => 'Dashboard: Weitere Wordpress',
						'dashboard_incoming_links' => 'Dashboard: Incoming Links',
						'dashboard_right_now' => 'Dashboard:  Right now',
						'dashboard_recent_drafts' => 'Dashboard: Recent Drafts',
						'yoast_db_widget' => 'Plugin: Yoast SEO'), 
					'label' => 'Select which dashboard to remove (for all users):') );

			add_settings_field('advanced_wordpress_configuration_plugin_disableDragDashboardWidgets', __('Disable Widget dragging', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_disableDragDashboardWidgets', 'type' => 'checkbox', 'label' => 'Disables the dragging of metaboxes in dashboard. ' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_customBackend', __('Customize backend', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 
					'option_name' => 'advanced_wordpress_configuration_plugin_customBackend', 
					'type' => 'input', 
					'class' => 'regular-text',
					'label' => 'Enter the path to the CSS file (wp-admin.css). Path is based on current theme (enter library/css/ for example). For example use "#header-logo" to set up a custom logo.' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_customLoginImage', __('Custom login image', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_customLoginImage', 'type' => 'input', 'class' => 'regular-text', 'label' => 'Enter the path to following images: bg_login.jpg, logo.png, button.jpg. Path is based on current theme (enter images/ for example or /lib/img/).' ));

			add_settings_field('advanced_wordpress_configuration_plugin_customLoginCSS', __('Custom login CSS', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_customLoginCSS', 'type' => 'input', 'class' => 'regular-text', 'label' => 'Enter the path to the CSS file (wp-login.css). Path is based on current theme (enter library/css/ for example).' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_admin_color_scheme_picker', __('Removes color scheme picker', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_admin_color_scheme_picker', 'type' => 'checkbox', 'label' => 'Removes the color picker on user profil page.' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_removePostColumns', __('Remove posts backend columns', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_removePostColumns', 'type' => 'selectmultiple',  'options' => array(
					'comments' => 'Comments',
					'categories' => 'Categories',
					'author' => 'Author',
					'date' => 'Date'), 'label' => 'Select columns to remove from posts:' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_removePageColumns', __('Removes pages backend columns', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_removePageColumns', 'type' => 'selectmultiple',  'options' => array(
					'comments' => 'Comments',
					'categories' => 'Categories',
					'author' => 'Author',
					'date' => 'Date'), 'label' => 'Select columns to remove from pages:' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_addPostThumbnailColumn', __('Add post thumbnail column', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_addPostThumbnailColumn', 'type' => 'checkbox', 'label' => 'Adds a post-thumbnail column to posts.' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_addPageThumbnailColumn', __('Add page thumbnail column', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_addPageThumbnailColumn', 'type' => 'checkbox', 'label' => 'Adds a post-thumbnail column to pages.' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_addShortcodetoWidgets', __('Wiget shortcode support', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_addShortcodetoWidgets', 'type' => 'checkbox', 'label' => 'Adds support for shortcodes in widgets.' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_customTinymce', __('Customize TinyMCE Editor', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_customTinymce', 'type' => 'checkbox', 'label' => '* Options need to be set directly in plugin: Select buttons to hide/display.' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_addIframeSupportToTinyMCE', __('Add iFrame Support to TinyMCE', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_addIframeSupportToTinyMCE', 'type' => 'checkbox', 'label' => ' ' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_backendChangeFooter', __('Set footer text', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_backendChangeFooter', 'option_section' => 'advanced_wordpress_configuration_plugin_backend', 'type' => 'input', 'class' => 'regular-text', 'label' => 'Leave empty for standard thank you text.' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_backendChangeFooterVersion', __('Change the Wordpress Version', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_backendChangeFooterVersion', 'type' => 'input', 'class' => 'regular-text', 'label' => 'Changes the version info in bottom right footer.' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_addUserContactFields', __('Add user contact fields', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_addUserContactFields', 'type' => 'checkbox', 'label' => '* Options need to be set directly in plugin: Additional fields.' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_removeUserContactFields', __('Remove user contact fields', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_removeUserContactFields', 'type' => 'selectmultiple',  'options' => array(
					'aim' => 'AIM',
					'url' => 'URL',
					'jabber' => 'Jabber',
					'google_profile' => 'Google+',
					'user_tw' => 'Twitter',
					'user_fb' => 'Facebook'), 'label' => 'Please choose which fields to remove:' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_addNewGravatar', __('Add a new gravatar', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_addNewGravatar', 'type' => 'input', 'class' => 'regular-text', 'label' => 'Path to "customavatar.png" based on template directory (e.g. images/ or lib/img/' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_hideUpdateNag', __('Hide Update Notification', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_hideUpdateNag', 'type' => 'checkbox', 'label' => 'For all users.' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_showAdminMessage', __('Show error message', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_showAdminMessage', 'type' => 'input', 'class' => 'regular-text', 'label' => 'Show this message to backend users.' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_hideHelpTab', __('Hide ‘help’ Tab from admin panel', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_hideHelpTab', 'type' => 'checkbox', 'label' => 'Removes the help tab (via CSS).' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_removeMetaBoxes', __('Remove metaboxes from Edit', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_removeMetaBoxes', 'type' => 'selectmultiple', 'options' => array(
					'submitdiv' => 'Submit div', 
					'commentsdiv' => 'Comments', 
					'revisionsdiv' => 'Revisions',
					'authordiv' => 'Author',
					'slugdiv' => 'Slug',
					'tagsdiv-post_tag' => 'Tags',
					'categorydiv' => 'Category',
					'postexcerpt' => 'Excerpt',
					'formatdiv' => 'Format',
					'trackbacksdiv' => 'Trackbacks',
					'postcustom' => 'Post',
					'commentstatusdiv' => 'Comment status',
					'postimagediv' => 'Post image',
					'pageparentdiv' => 'Page parent'),  'label' => 'Select metaboxes to remove from editing screen:' ) );

			add_settings_field('advanced_wordpress_configuration_plugin_hidePostFormatUI', __('Hide post format UI for all users', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_backend', 'advanced_wordpress_configuration_plugin_backend_section', 
				array( 'option_name' => 'advanced_wordpress_configuration_plugin_hidePostFormatUI', 'type' => 'checkbox', 'label' => 'Hides the post format UI (new in WordPress 3.6.' ) );
	}

	/**
	 * Defines settings for frontend section
	 */
	function addSettingsFrontend() {
		add_settings_field('advanced_wordpress_configuration_plugin_deregisterPluginStyles', __('De-registers plugin styles', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_frontend', 'advanced_wordpress_configuration_plugin_frontend_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_deregisterPluginStyles', 'type' => 'checkbox', 'label' => '* Options need to be set directly in plugin: Plugin styles. Plugins like to add their own styles. To save on HTTP requests the styles shoud be copied to the template stylesheet. ' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_customExcerptLength', __('Sets a new excerpt length', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_frontend', 'advanced_wordpress_configuration_plugin_frontend_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_customExcerptLength', 'type' => 'number', 'label' => 'Set new excerpt length (integer). Wordpress default: 55 words.' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_removePingbackHeader', __('Removes the pingback header', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_frontend', 'advanced_wordpress_configuration_plugin_frontend_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_removePingbackHeader', 'type' => 'checkbox' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_styleExternalLinks', __('Styles external links', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_frontend', 'advanced_wordpress_configuration_plugin_frontend_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_styleExternalLinks', 'type' => 'checkbox' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_removeExcerptMore', __('Removes the read more link', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_frontend', 'advanced_wordpress_configuration_plugin_frontend_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_removeExcerptMore', 'type' => 'checkbox' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_addBodyClass', __('Adds a meaningful body class', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_frontend', 'advanced_wordpress_configuration_plugin_frontend_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_addBodyClass', 'type' => 'checkbox' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_metaImageThumb', __('Adds an meta image tag for thumbnails', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_metaImageThumb', 'advanced_wordpress_configuration_plugin_frontend_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_metaImageThumb', 'type' => 'checkbox' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_removeWordpressVersion', __('Remove Wordpress version', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_frontend', 'advanced_wordpress_configuration_plugin_frontend_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_removeWordpressVersion', 'type' => 'checkbox', 'label' => 'Completely remove wordpress versions in frontend output' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_keepUserLoggedIn', __('Keep user logged in', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_frontend', 'advanced_wordpress_configuration_plugin_frontend_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_keepUserLoggedIn', 'type' => 'number', 'label' => 'Enter in seconds: year: 31556926, month: 2676400' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_addClassToFirstPost', __('First post class', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_frontend', 'advanced_wordpress_configuration_plugin_frontend_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_removeWordpressVersion', 'type' => 'checkbox', 'label' => 'Adds a "first" class to the first post' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_addSlugClassToCategoryList', __('List categories with slug', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_frontend', 'advanced_wordpress_configuration_plugin_frontend_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_addSlugClassToCategoryList', 'type' => 'checkbox', 'label' => 'Adds the category slug as a class to wp_list_categories.' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_addBreadcrumbAction', __('Adds an advanced breadcrumb action', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_frontend', 'advanced_wordpress_configuration_plugin_frontend_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_addBreadcrumbAction', 'type' => 'checkbox', 'label' => 'Adds an action for breadcrumbs. Needs to be called in theme by "do_action("the_breadcrumb")".' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_setSearchResultsPerPage', __('Search results per page', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_frontend', 'advanced_wordpress_configuration_plugin_frontend_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_setSearchResultsPerPage', 'type' => 'number', 'label' => 'Enter any number here. Wordpress default: 10' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_removeHeaderLinks', __('Remove header data', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_frontend', 'advanced_wordpress_configuration_plugin_frontend_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_removeHeaderLinks', 'type' => 'checkbox', 'label' => 'Removes typical Wordpress header entries like rsd_link or wlwmanifest_link.' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_removeWidthHeightFromImage', __('Remove image width/height', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_frontend', 'advanced_wordpress_configuration_plugin_frontend_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_removeWidthHeightFromImage', 'type' => 'checkbox', 'label' => 'Remove Width and Height Attributes From Inserted Images' ) );
	}

	/**
	 * Defines settings for rss section
	 */
	function addSettingsRss() {
		add_settings_field('advanced_wordpress_configuration_plugin_disableRSS', __('Disable RSS Feeds', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_rss', 'advanced_wordpress_configuration_plugin_rss_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_disableRSS', 'type' => 'checkbox', 'label' => 'Completely disables all RSS feeds.' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_delayPublishRSS', __('Delay publishing of RSS feed', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_rss', 'advanced_wordpress_configuration_plugin_rss_section', 
			array( 
				'option_name' => 'advanced_wordpress_configuration_plugin_delayPublishRSS', 
				'type' => 'select', 
				'options' => array('false' => 'no delay', '10' => '10 Minutes', '20' => '20 Minutes', '30' => '30 Minutes'), 
				'label' => 'Delays publishing by x minutes.' 
			) );

		add_settings_field('advanced_wordpress_configuration_plugin_addThumbnailToRSS', __('Add thumbnail image to RSS', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_rss', 'advanced_wordpress_configuration_plugin_rss_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_addThumbnailToRSS', 'type' => 'checkbox', 'label' => 'Adds the thumbnail picture to RSS Feeds.' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_customizeRSSFooter', __('Add text to RSS Footer', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_rss', 'advanced_wordpress_configuration_plugin_rss_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_customizeRSSFooter', 'type' => 'input', 'class' => 'regular-text', 'label' => 'Adds this string at the end of the feed.' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_removeCategoryFromFeed', __('Remove category from feed', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_rss', 'advanced_wordpress_configuration_plugin_rss_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_removeCategoryFromFeed', 'type' => 'input', 'label' => 'Enter comma separated category ids (be careful, there is no further check at this point).' ) );
	}

	/**
	 * Defines settings for javascript section
	 */
	function addSettingsJavascript() {
		add_settings_field('advanced_wordpress_configuration_plugin_jqueryFromCdn', __('Load jQuery from CDN', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_javascript', 'advanced_wordpress_configuration_plugin_javascript_section', 
		array( 'option_name' => 'advanced_wordpress_configuration_plugin_jqueryFromCdn', 'type' => 'checkbox', 'label' => 'Loads jQuery from Google CDN, version: 1.9.1' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_deferJavascript', __('Defer Javascript', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_javascript', 'advanced_wordpress_configuration_plugin_javascript_section', 
		array( 'option_name' => 'advanced_wordpress_configuration_plugin_deferJavascript', 'type' => 'checkbox', 'label' => 'Be careful with this setting: not every plugin is well-programmed and may stumble here...' ) );
	}

	/**
	 * Defines settings for admin bar section
	 */
	function addSettingsAdminbar() {
		add_settings_field('advanced_wordpress_configuration_plugin_removeAdminbar', __('Remove admin bar', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_adminbar', 'advanced_wordpress_configuration_plugin_adminbar_section', 
		array( 'option_name' => 'advanced_wordpress_configuration_plugin_removeAdminbar', 'type' => 'checkbox', 'label' => '* Options need to be set directly in plugin: remove for all users or all users except admins. Default: all users except admins.' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_removeFromAdminBar', __('Remove from admin bar', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_adminbar', 'advanced_wordpress_configuration_plugin_adminbar_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_removeFromAdminBar', 'type' => 'selectmultiple',  
				'options' => array(
					'wp-logo' => 'Wordpress Logo', 
					'about' => '  About Wordpress', 
					'wporg' => '  Wordpress.org',
					'documentation' => '  Documentation',
					'support-forums' => '  Support Forum',
					'feedback' => '  Feedback',
					'site-name' => 'Site Name',
					'view-site' => 'View site',
					'updates' => 'Updates',
					'comments' => 'Comments',
					'new-content' => 'New content',
					'w3tc' => 'W3 Total Cache',
					'wpseo-menu' => 'Yoast SEO',
					'my-account' => 'My account'),  'label' => 'Select options to remove:' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_customizeAdminbar', __('Customize admin bar', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_adminbar', 'advanced_wordpress_configuration_plugin_adminbar_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_customizeAdminbar', 'type' => 'checkbox', 'label' => '* Options need to be set directly in plugin. Adds a search bar by default.' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_openSiteLinkNewWindow', __('Open site links in new window', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_adminbar', 'advanced_wordpress_configuration_plugin_adminbar_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_openSiteLinkNewWindow', 'type' => 'checkbox', 'label' => 'Opens the site links in _blank instead of _self.' ) );

		add_settings_field('advanced_wordpress_configuration_plugin_addHelpForEditors', __('Add help for editors', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_adminbar', 'advanced_wordpress_configuration_plugin_adminbar_section', 
			array( 'option_name' => 'advanced_wordpress_configuration_plugin_addHelpForEditors', 'type' => 'checkbox', 'label' => '* Options need to be set directly in plugin.' ) );
	}

	/**
	 * Defines settings for comments section
	 */
	function addSettingsComments() {
		add_settings_field('advanced_wordpress_configuration_plugin_correctCommentCount', __('Corrects the comment count', 'advanced-wordpress-configuration-plugin-locale'), array($this, 'renderOptions'), 'advanced_wordpress_configuration_plugin_comments', 'advanced_wordpress_configuration_plugin_comments_section', 
		array( 'option_name' => 'advanced_wordpress_configuration_plugin_correctCommentCount', 'type' => 'checkbox' ) );
	}

	/**
	 * Renders the options (input, checkbox, selects)
	 */
	function renderOptions($args) {

		$options = get_option( $this->activeTab );

		if( $args['headline']) echo '<hr><h3>'.$args['headline'].'</h3>';

		$class = '';
		if( $args['class']) $class = 'class="' . $args['class'] . ' "';

		switch($args['type']) {

			case 'checkbox':
				echo '<input id="'.$this->activeTab.'['.$args['option_name'].']" name="'.$this->activeTab.'['.$args['option_name'].']" type="checkbox" value="1" ' . checked( $options[$args['option_name']], 1, false ) . ' />';

				if($args['label']) echo '<label for="'.$this->activeTab.'['.$args['option_name'].']"> '  . $args['label'] . '</label>';

				echo '<br />';

				break;

			case 'input':

				echo '<input type="text" ' . $class . 'id="'.$this->activeTab.'['.$args['option_name'].']" name="'.$this->activeTab.'['.$args['option_name'].']" value="' . esc_attr($options[$args['option_name']]) . '" />';  

				if($args['label']) echo '<br><label for="'.$this->activeTab.'['.$args['option_name'].']" ><span class="description"> '  . $args['label'] . '</span></label>';

				echo '<br />';

				break;	

			case 'number':

				echo '<input type="number" id="'.$this->activeTab.'['.$args['option_name'].']" name="'.$this->activeTab.'['.$args['option_name'].']" value="' . $options[$args['option_name']] . '" />';  

				if($args['label']) echo '<label for="'.$this->activeTab.'['.$args['option_name'].']" ><span class="description"> '  . $args['label'] . '</span></label>';

				echo '<br />';

				break;

			case 'select':

				if( $args['label'] ) echo '<label for="'.$this->activeTab.'['.$args['option_name'].']" ><span class="description"> '  . $args['label'] . '</span></label>';

				echo '<select name="'.$this->activeTab.'['.$args['option_name'].']" id="'.$this->activeTab.'['.$args['option_name'].']">';
				foreach( $args['options'] as $option => $option_label) {
					$selected = ($options[$args['option_name']] == $option ) ? 'selected="selected"' : '';
					echo '<option '.$selected.' value="'.$option.'">'.$option_label.'</option>';	
				}

				echo '</select>';

				break;

			case 'selectmultiple':

				if( $args['label'] ) echo '<label for="'.$this->activeTab.'['.$args['option_name'].']" ><span class="description"> '  . $args['label'] . '</span></label><br>';

				if( count($args['options']) > 6 ) {
					$size = 6;
				} else {
					$size = count($args['options']);
				}

				echo '<select name="'.$this->activeTab.'['.$args['option_name'].'][]" id="'.$this->activeTab.'['.$args['option_name'].']" multiple="multiple" size="'.$size.'">';

				$selectedOptions = explode(',', $options[$args['option_name']]);

				foreach( $args['options'] as $option => $option_label) {
					$selected = ( in_array($option, $selectedOptions) ) ? 'selected="selected"' : '';
					echo '<option '.$selected.' value="'.$option.'">'.$option_label.'</option>';	
				}

				echo '</select>';

				break;


			default:
				echo 'Please set a type (checkbox, select, selectmultiple, input).';
				break;
		}

	}

	/**
	 * Sanitizes settings before saving them to the database
	 */
	function sanitizeInput( $input ) {  
      
	    // Define the array for the updated options  
	    $output = array();  
	  
	    // Loop through each of the options sanitizing the data  
	    if( is_array($input)) {
		    foreach( $input as $key => $val ) {  
		      
		        if( isset ( $input[$key] ) ) {  

		            if( is_array($input[$key]) ) {
		            	$output[$key] = implode(',', $input[$key]);
		            } else {
		            	$output[$key] = sanitize_text_field( $input[$key] ) ;  
		            }

		            
		        } 
		      
		    }
	    }
	      
	    // Return the new collection  
	    return apply_filters( 'sanitizeInput', $output, $input );  
	  
	} 


	function intro_advanced_wordpress_configuration_plugin_general() {
		echo '<p class="note">' . __("Configure general settings.", 'advanced-wordpress-configuration-plugin-locale') . '</p>';
	}

	function intro_advanced_wordpress_configuration_plugin_backend() {
		echo '<p class="note">' . __("Configure backend settings.", 'advanced-wordpress-configuration-plugin-locale') . '</p>';
	}

	function intro_advanced_wordpress_configuration_plugin_rss() {
		echo '<p class="note">' . __("Configure RSS settings.", 'advanced-wordpress-configuration-plugin-locale') . '</p>';
	}

	function intro_advanced_wordpress_configuration_plugin_frontend() {
		echo '<p class="note">' . __("Configure frontend settings.", 'advanced-wordpress-configuration-plugin-locale') . '</p>';
	}

	function intro_advanced_wordpress_configuration_plugin_javascript() {
		echo '<p class="note">' . __("Configure javascript settings.", 'advanced-wordpress-configuration-plugin-locale') . '</p>';
	}

	function intro_advanced_wordpress_configuration_plugin_adminbar() {
		echo '<p class="note">' . __("Configure admin bar settings.", 'advanced-wordpress-configuration-plugin-locale') . '</p>';
	}

	function intro_advanced_wordpress_configuration_plugin_comments() {
		echo '<p class="note">' . __("Configure comment settings.", 'advanced-wordpress-configuration-plugin-locale') . '</p>';
	}

}

?>
