<?php
/*
 * advanced-wordpress-configuration-pluginBackendOptions
 * setup of the backend options page, base partly on this tutorial: http://wp.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-1/
 *
 * @author     T. Boehning
 * @version	1.2
 */
class advancedwordpressconfigurationpluginOptions {

	private static $instance;
	protected $settings_sections;
	protected $page_name;
	protected $activeTab;

	public $options_general;
	public $options_backend;
	public $options_frontend;
	public $options_rss;
	public $options_javascript;
	public $options_adminbar;
	public $options_comments;
	public $options_mobile;

	/**
	 * Initializes the plugin
	 */
	function __construct() {

		//get all options from database
		$this->options_general = get_option('advanced_wordpress_configuration_plugin_general');
		$this->options_backend = get_option('advanced_wordpress_configuration_plugin_backend');
		$this->options_frontend = get_option('advanced_wordpress_configuration_plugin_frontend');
		$this->options_rss = get_option('advanced_wordpress_configuration_plugin_rss');
		$this->options_javascript = get_option('advanced_wordpress_configuration_plugin_javascript');
		$this->options_adminbar = get_option('advanced_wordpress_configuration_plugin_adminbar');
		$this->options_comments = get_option('advanced_wordpress_configuration_plugin_comments');
		$this->options_mobile = get_option('advanced_wordpress_configuration_plugin_mobile');

		//just load the necessary modules in the frontend
		if( !is_admin()) {
			self::addSettings();
		} 
		//setup options and load modules in backend
		else {

			$this->page_name = 'advanced-wordpress-configuration-plugin-options';

			$this->setting_sections = array( 	
				'advanced_wordpress_configuration_plugin_general' 	=> __('General', 'advanced-wordpress-configuration-plugin-locale'), 
				'advanced_wordpress_configuration_plugin_backend' 	=>  __('Backend', 'advanced-wordpress-configuration-plugin-locale'), 
				'advanced_wordpress_configuration_plugin_frontend' 	=>  __('Frontend', 'advanced-wordpress-configuration-plugin-locale'), 
				'advanced_wordpress_configuration_plugin_rss' 		=>  __('RSS', 'advanced-wordpress-configuration-plugin-locale'), 
				'advanced_wordpress_configuration_plugin_javascript' =>  __('Javascript', 'advanced-wordpress-configuration-plugin-locale'), 
				'advanced_wordpress_configuration_plugin_adminbar' 	=>  __('Adminbar', 'advanced-wordpress-configuration-plugin-locale'), 
				'advanced_wordpress_configuration_plugin_comments' 	=>  __('Comments', 'advanced-wordpress-configuration-plugin-locale'),
				'advanced_wordpress_configuration_plugin_mobile' 	=>  __('Mobile', 'advanced-wordpress-configuration-plugin-locale') );

			add_action('admin_menu', array(&$this, 'createOptionsPage'));
		}
	}

	/**
	 * Singleton construct to enable the modules to access the options
	 * @return [type] [description]
	 */
	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new self();
		}

	    return self::$instance;
	}

	/**
	 * [getShortName description]
	 * @param  [type] $file [description]
	 * @return [type]       [description]
	 */
	public static function getShortName($file) {
		$info =  get_file_data( $file , array('name' => 'Module Name'));
		$shortName = sanitize_file_name($info['name']);

		return $shortName;
	}


	/**
	 * creates a setting page
	 */
	function createOptionsPage() {

		add_options_page(	'Advanced Wordpress Configuration Plugin', //the page title
							'Advanced Wordpress Configuration',  //Menu title
							'manage_options',  //capabilitites to show menu entry
							$this->page_name, //slug, unique id
							array($this, 'displaySettingsPage') //callback
						);

		add_action('admin_init', array($this, 'registerPluginSettings') );
	}

	/**
	 * displays the settings page
	 */
	function displaySettingsPage() {

		$this->activeTab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'advanced_wordpress_configuration_plugin_general';

		echo '<div class="wrap advanced_wordpress_configuration_plugin">';

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
			__('', 'advanced-wordpress-configuration-plugin-locale'), //General Options - Output is not good enough for styling 
			array($this, 'intro_advanced_wordpress_configuration_plugin_general'), 	// Callback used to render the description of the section  
			'advanced_wordpress_configuration_plugin_general'			// Page on which to add this section of options  
		);



		add_settings_section(
			'advanced_wordpress_configuration_plugin_backend_section', 
			__('', 'advanced-wordpress-configuration-plugin-locale'), //ackend Options - Output is not good enough for styling	
			array($this, 'intro_advanced_wordpress_configuration_plugin_backend'), 
			'advanced_wordpress_configuration_plugin_backend'
		);

		add_settings_section(
			'advanced_wordpress_configuration_plugin_backend_section_customize', 
			__('', 'advanced-wordpress-configuration-plugin-locale'), //ackend Options - Output is not good enough for styling	
			array($this, 'intro_advanced_wordpress_configuration_plugin_backend_customize'), 
			'advanced_wordpress_configuration_plugin_backend'
		);

		add_settings_section(
			'advanced_wordpress_configuration_plugin_backend_section_tinymce', 
			__('', 'advanced-wordpress-configuration-plugin-locale'), //ackend Options - Output is not good enough for styling	
			array($this, 'intro_advanced_wordpress_configuration_plugin_backend_tinymce'), 
			'advanced_wordpress_configuration_plugin_backend'
		);

		add_settings_section(
			'advanced_wordpress_configuration_plugin_backend_section_profile', 
			__('', 'advanced-wordpress-configuration-plugin-locale'), 	//Profile page - Output is not good enough for styling
			array($this, 'intro_advanced_wordpress_configuration_plugin_backend_profile'), 
			'advanced_wordpress_configuration_plugin_backend'
		);

		add_settings_section(
			'advanced_wordpress_configuration_plugin_backend_section_columns', 
			__('', 'advanced-wordpress-configuration-plugin-locale'), 	//Columns - Output is not good enough for styling
			array($this, 'intro_advanced_wordpress_configuration_plugin_backend_columns'), 
			'advanced_wordpress_configuration_plugin_backend'
		);

		add_settings_section(
			'advanced_wordpress_configuration_plugin_backend_section_dashboard', 
			__('', 'advanced-wordpress-configuration-plugin-locale'), 	//Dashboard - Output is not good enough for styling
			array($this, 'intro_advanced_wordpress_configuration_plugin_backend_dashboard'), 
			'advanced_wordpress_configuration_plugin_backend'
		);



		add_settings_section('advanced_wordpress_configuration_plugin_rss_section', 
			__('', 'advanced-wordpress-configuration-plugin-locale'), //RSS Options - Output is not good enough for styling
			array($this, 'intro_advanced_wordpress_configuration_plugin_rss'), 
			'advanced_wordpress_configuration_plugin_rss'
		);


		add_settings_section('advanced_wordpress_configuration_plugin_frontend_section', 
			__('', 'advanced-wordpress-configuration-plugin-locale'), //Frontend Options - Output is not good enough for styling
			array($this, 'intro_advanced_wordpress_configuration_plugin_frontend'), 
			'advanced_wordpress_configuration_plugin_frontend'
		);

		add_settings_section('advanced_wordpress_configuration_plugin_frontend_section_cleanup', 
			__('', 'advanced-wordpress-configuration-plugin-locale'), //Clean-Up - Output is not good enough for styling
			array($this, 'intro_advanced_wordpress_configuration_plugin_frontend_cleanup'), 
			'advanced_wordpress_configuration_plugin_frontend'
		);

		add_settings_section('advanced_wordpress_configuration_plugin_frontend_section_users', 
			__('', 'advanced-wordpress-configuration-plugin-locale'), //Users - Output is not good enough for styling
			array($this, 'intro_advanced_wordpress_configuration_plugin_frontend_users'), 
			'advanced_wordpress_configuration_plugin_frontend'
		);


		add_settings_section('advanced_wordpress_configuration_plugin_javascript_section', 
			__('', 'advanced-wordpress-configuration-plugin-locale'), //Javascript Options - Output is not good enough for styling
			array($this, 'intro_advanced_wordpress_configuration_plugin_javascript'), 
			'advanced_wordpress_configuration_plugin_javascript'
		);

		add_settings_section('advanced_wordpress_configuration_plugin_adminbar_section', 
			__('', 'advanced-wordpress-configuration-plugin-locale'), //Adminbar Options - Output is not good enough for styling
			array($this, 'intro_advanced_wordpress_configuration_plugin_adminbar'), 
			'advanced_wordpress_configuration_plugin_adminbar'
		);

		add_settings_section('advanced_wordpress_configuration_plugin_comments_section', 
			__('', 'advanced-wordpress-configuration-plugin-locale'), //Comments Options - Output is not good enough for styling
			array($this, 'intro_advanced_wordpress_configuration_plugin_comments'), 
			'advanced_wordpress_configuration_plugin_comments'
		);

		add_settings_section('advanced_wordpress_configuration_plugin_mobile_section', 
			__('', 'advanced-wordpress-configuration-plugin-locale'), //Mobile Options - Output is not good enough for styling
			array($this, 'intro_advanced_wordpress_configuration_plugin_mobile'), 
			'advanced_wordpress_configuration_plugin_mobile'
		);	



		//3. setting field that will set a value for the settings in the setting group
		self::addSettings();



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
	 * Calls setting-functions to load modules for backend and frontend
	 */
	function addSettings() {
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

		//mobile
		$this->addSettingsMobile();
	}

	

	/**
	 * Defines settings for general section
	 */
	function addSettingsGeneral() {

		$this->loadModules('general');
	}

	/**
	 * Defines settings for backend section
	 */
	function addSettingsBackend() {

		$this->loadModules('backend');

		$this->loadModules('backend', 'columns');
		$this->loadModules('backend', 'customize');
		$this->loadModules('backend', 'dashboard');
		$this->loadModules('backend', 'profile');
		$this->loadModules('backend', 'tinymce');
	}

	/**
	 * Defines settings for frontend section
	 */
	function addSettingsFrontend() {

		$this->loadModules('frontend');

		$this->loadModules('frontend', 'cleanup');
		$this->loadModules('frontend', 'users');
	}

	/**
	 * Defines settings for rss section
	 */
	function addSettingsRss() {

		$this->loadModules('rss');
	}

	/**
	 * Defines settings for javascript section
	 */
	function addSettingsJavascript() {

		$this->loadModules('javascript');
	}

	/**
	 * Defines settings for admin bar section
	 */
	function addSettingsAdminbar() {

		$this->loadModules('adminbar');
	}

	/**
	 * Defines settings for comments section
	 */	
	function addSettingsComments() {

		$this->loadModules('comments');
	}

	/**
	 * Defines settings for rss section
	 */
	function addSettingsMobile() {

		$this->loadModules('mobile');
	}

	/**
	 * [loadModules description]
	 * @param  [type] $modulePath [description]
	 * @return [type]             [description]
	 */
	function loadModules($modulePath, $subModule = null) {

		//get module filepath
		if( is_null($subModule)) { 
			$filePath = plugin_dir_path(__FILE__). 'module/'.$modulePath.'/';
		} else {
			$filePath = plugin_dir_path(__FILE__). 'module/'.$modulePath.'/'.$subModule.'/';
		}
		
		$path = trailingslashit(realpath($filePath));

		//loop through each file in the modules-directory
		foreach (new DirectoryIterator($path) as $filename) {
		    if($filename->isDot()) continue;

			if($filename->isFile()) {
				$file_extension = pathinfo($filename->getFilename(), PATHINFO_EXTENSION);
			} else {
				continue;
			}
		
			if ( $filename->isReadable() && $filename->valid() && $file_extension === 'php') {
	
				//set internal option name
				$currentOption = 'options_'.$modulePath;
	
				//only do stuff if option is set
				if( !is_null($this->$currentOption) ) {

					//look at the meta data first and determine whether to include the file or not
					
					//get meta data like Module Name, Description, Author etc.
					$meta = self::getMetaData($path.$filename->current());

					//get a compatible optionname based on the name of the option (which can include spaces etc.)
					$shortName = sanitize_file_name($meta['name']);

					$optionName = "advanced_wordpress_configuration_plugin_".$shortName;

					//loads the function, adds filters and actions if necessary
					if(is_array($this->$currentOption)) {
						if (array_key_exists($optionName, $this->$currentOption)) {

							if( strlen($meta['scope']) > 0) {
								
								if( strtolower($meta['scope']) === 'backend' && is_admin() ) {
									require_once($path.$filename->current());
								} elseif ( strtolower($meta['scope']) === 'frontend' && !is_admin() ) {
									require_once($path.$filename->current());
								} elseif ( strtolower($meta['scope']) === 'both' ) {
									require_once($path.$filename->current());
								}
							} else {
								require_once($path.$filename->current());
							}
							
						}
					}
				
					//only set up options in backend
					if( is_admin() ) {
						self::setupBackendOption($modulePath, $subModule, $shortName, $meta);
					}
				}
			} 
		}
	}

	/**
	 * Displays the options in the backend
	 * @param  [type] $modulePath [description]
	 * @param  [type] $subModule  [description]
	 * @param  [type] $shortName  [description]
	 * @param  [type] $meta       [description]
	 */
	function setupBackendOption($modulePath, $subModule, $shortName, $meta) {
		//get options from string
		if(!empty($meta['options'])) {

			$options = array();
			$exploded = explode(',', $meta['options']);

			if(is_array($exploded)) {
				foreach ($exploded as $key => $value) {
					$exploded2 = explode('=>', $value);
					$options[trim($exploded2[0])] = trim($exploded2[1]);
				}
			}
		} else {
			$options = '';
		}

		//display option in main module or in a submodule
		if( is_null($subModule)) {
			$module = 'advanced_wordpress_configuration_plugin_'.$modulePath.'_section';
		} else {
			$module = 'advanced_wordpress_configuration_plugin_'.$modulePath.'_section_'.$subModule;
		}


		//add the setting
		add_settings_field(
			'advanced_wordpress_configuration_plugin_'.$shortName, 
			__($meta['name'], 'advanced-wordpress-configuration-plugin-locale'), 
			array($this, 'renderOptions'), 
			'advanced_wordpress_configuration_plugin_'.$modulePath, 
			$module, 
			array( 
				'option_name' => 'advanced_wordpress_configuration_plugin_'.$shortName, 
				'type' => strtolower($meta['type']), 
				'label' => ( empty($meta['desc']) ? '' : $meta['desc'] ),
				'class' => ( empty($meta['class']) ? '' : $meta['class'] ),
				'options' => $options 
			)
		);
	}

	/**
	 * Reads metadata of a file (uses Wordpress function)
	 * @param  [type] $file [description]
	 * @return [type]       [description]
	 */
	function getMetaData($file) {
		return get_file_data( $file, array(
			'name' => 'Module Name', 
			'desc' => 'Description',
			'scope' => 'Scope',
			'type' => 'Type',
			'class' => 'Class',
			'options' => 'Options')
		);
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

				$checked = '';
				if( isset($options[$args['option_name']]) ) {
					$checked = checked( $options[$args['option_name']], 1, false );
				}
				echo '<input id="'.$this->activeTab.'['.$args['option_name'].']" name="'.$this->activeTab.'['.$args['option_name'].']" type="checkbox" value="1" ' . $checked . '/>';

				if($args['label']) echo '<label for="'.$this->activeTab.'['.$args['option_name'].']"> '  . $args['label'] . '</label>';

				echo '<br />';

				break;

			case 'input':

				$value = '';
				if( isset($options[$args['option_name']]) ) {
					$value = esc_attr($options[$args['option_name']]);
				}
				echo '<input type="text" ' . $class . 'id="'.$this->activeTab.'['.$args['option_name'].']" name="'.$this->activeTab.'['.$args['option_name'].']" value="' . $value . '" />';  

				if($args['label']) echo '<br><label for="'.$this->activeTab.'['.$args['option_name'].']" ><span class="description"> '  . $args['label'] . '</span></label>';

				echo '<br />';

				break;	

			case 'number':
				$value = '';
				if( isset($options[$args['option_name']]) ) {
					$value = $options[$args['option_name']];
				}
				echo '<input type="number" id="'.$this->activeTab.'['.$args['option_name'].']" name="'.$this->activeTab.'['.$args['option_name'].']" value="' . $value . '" />';  
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
		echo '<section class="awcp_section">
				<h3>'.__('General', 'advanced-wordpress-configuration-plugin-locale').'</h3>
				<p class="note">' . __("Configure general settings.", 'advanced-wordpress-configuration-plugin-locale') . '</p>
				</section>';
	}

	function intro_advanced_wordpress_configuration_plugin_backend() {
		echo '<section class="awcp_section">
				<h3>Backend</h3>
				<p class="note">' . __("Configure backend settings.", 'advanced-wordpress-configuration-plugin-locale') . '</p>
				</section>';
	}

	function intro_advanced_wordpress_configuration_plugin_backend_customize() {
		echo '<section class="awcp_section">
				<h3>'.__('Customize backend', 'advanced-wordpress-configuration-plugin-locale').'</h3>
				<p class="note">' . __("Customize the backend (CSS and text changes).", 'advanced-wordpress-configuration-plugin-locale') . '</p>
				</section>';
	}

	function intro_advanced_wordpress_configuration_plugin_backend_tinymce() {
		echo '<section class="awcp_section">
				<h3>'.__('TinyMCE Rich Text Editor', 'advanced-wordpress-configuration-plugin-locale').'</h3>
				<p class="note">' . __("Configure the TinyMCE rich text editor.", 'advanced-wordpress-configuration-plugin-locale') . '</p>
				</section>';
	}

	function intro_advanced_wordpress_configuration_plugin_backend_profile() {
		echo '<section class="awcp_section">
				<h3>'.__('Profile page', 'advanced-wordpress-configuration-plugin-locale').'</h3>
				<p class="note">' . __("Configure profile page settings.", 'advanced-wordpress-configuration-plugin-locale') . '</p>
				</section>';
	}

	function intro_advanced_wordpress_configuration_plugin_backend_columns() {
		echo '<section class="awcp_section">
				<h3>'.__('Columns', 'advanced-wordpress-configuration-plugin-locale').'</h3>
				<p class="note">' . __("Configure the backend columns.", 'advanced-wordpress-configuration-plugin-locale') . '</p>
				</section>';
	}

	function intro_advanced_wordpress_configuration_plugin_backend_dashboard() {
		echo '<section class="awcp_section">
				<h3>'.__('Dashboard', 'advanced-wordpress-configuration-plugin-locale').'</h3>
				<p class="note">' . __("Configure the dashboard.", 'advanced-wordpress-configuration-plugin-locale') . '</p>
				</section>';
	}

	function intro_advanced_wordpress_configuration_plugin_rss() {
		echo '<section class="awcp_section">
				<h3>'.__('RSS', 'advanced-wordpress-configuration-plugin-locale').'</h3>
				<p class="note">' . __("Configure RSS settings.", 'advanced-wordpress-configuration-plugin-locale') . '</p>
				</section>';
	}

	function intro_advanced_wordpress_configuration_plugin_frontend() {
		echo '<section class="awcp_section">
				<h3>'.__('Frontend', 'advanced-wordpress-configuration-plugin-locale').'</h3>
				<p class="note">' . __("Configure frontend settings.", 'advanced-wordpress-configuration-plugin-locale') . '</p>
				</section>';
	}

	function intro_advanced_wordpress_configuration_plugin_frontend_cleanup() {
		echo '<section class="awcp_section">
				<h3>'.__('Clean-Up', 'advanced-wordpress-configuration-plugin-locale').'</h3>
				<p class="note">' . __("Clean up frontend output.", 'advanced-wordpress-configuration-plugin-locale') . '</p>
				</section>';
	}

	function intro_advanced_wordpress_configuration_plugin_frontend_users() {
		echo '<section class="awcp_section">
				<h3>'.__('Users', 'advanced-wordpress-configuration-plugin-locale').'</h3>
				<p class="note">' . __("Frontend users.", 'advanced-wordpress-configuration-plugin-locale') . '</p>
				</section>';
	}

	function intro_advanced_wordpress_configuration_plugin_javascript() {
		echo '<section class="awcp_section">
				<h3>'.__('Javascript', 'advanced-wordpress-configuration-plugin-locale').'</h3>
				<p class="note">' . __("Configure the javascript settings.", 'advanced-wordpress-configuration-plugin-locale') . '</p>
				</section>';
	}

	function intro_advanced_wordpress_configuration_plugin_adminbar() {
		echo '<section class="awcp_section">
				<h3>'.__('Admin bar', 'advanced-wordpress-configuration-plugin-locale').'</h3>
				<p class="note">' . __("Configure admin bar settings.", 'advanced-wordpress-configuration-plugin-locale') . '</p>
				</section>';
	}

	function intro_advanced_wordpress_configuration_plugin_comments() {
		echo '<section class="awcp_section">
				<h3>'.__('Comments', 'advanced-wordpress-configuration-plugin-locale').'</h3>
				<p class="note">' . __("Configure comment settings.", 'advanced-wordpress-configuration-plugin-locale') . '</p>
				</section>';
	}

	function intro_advanced_wordpress_configuration_plugin_mobile() {
		echo '<section class="awcp_section">
				<h3>'.__('Mobile', 'advanced-wordpress-configuration-plugin-locale').'</h3>
				<p class="note">' . __("Configure mobile settings.", 'advanced-wordpress-configuration-plugin-locale') . '</p>
				</section>';
	}

}

?>