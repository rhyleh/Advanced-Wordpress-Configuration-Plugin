<?php
/**
   * advanced-wordpress-configuration-pluginWordpressSettings
   * Modifying wordpress
   *
   * @author     T. Boehning
   * @version	1.2
   *
   *
   */
class advancedwordpressconfigurationpluginWordpressSettings {

  	function __construct() {

  		require_once('modules/moduleGeneral.php');
  		$moduleGeneral = new moduleGeneral();

  		require_once('modules/moduleBackend.php');
  		$moduleBackend = new moduleBackend();

  		require_once('modules/moduleFrontend.php');
  		$moduleFrontend = new moduleFrontend();

  		require_once('modules/moduleComments.php');
  		$moduleComments = new moduleComments();

  		require_once('modules/moduleRss.php');
  		$moduleRss = new moduleRss();

  		require_once('modules/moduleJavascript.php');
  		$moduleJavascript = new moduleJavascript();

  		require_once('modules/moduleAdminBar.php');
  		$moduleAdminBar = new moduleAdminBar();

  		require_once('modules/moduleMobile.php');
  		$moduleMobile = new moduleMobile();


  		//theme support
  		add_theme_support('h5bp-htaccess');

		$this->registerActions();
		$this->registerFilters();
	}

	/**
	 * register the actions - all set via options page
	 */
	function registerActions() {

		if (current_theme_supports('h5bp-htaccess')) {
			add_action('generate_rewrite_rules', array($this, 'addH5bpHtaccess') );
		}
	}

	/**
	 * register the filters - all set via options page
	 */
	function registerFilters() {
		
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

}

?>
