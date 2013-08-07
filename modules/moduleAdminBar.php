<?php
/**
   * advanced-wordpress-configuration-pluginWordpressSettings
   * Module Admin Bar
   *
   * @author     T. Boehning
   * @version	1.0
   *
   *
   */
class moduleAdminBar {

	protected $options_adminbar;

  	function __construct() {

		$this->options_adminbar = get_option('advanced_wordpress_configuration_plugin_adminbar');

		$this->registerActions();
		$this->registerFilters();
	}

	/**
	 * register the actions - all set via options page
	 */
	function registerActions() {

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
	}

	/**
	 * register the filters - all set via options page
	 */
	function registerFilters() {
		
		
		//completely remove admin bar
		if( !is_null($this->options_adminbar) ) {
			if( isset($this->options_adminbar["advanced_wordpress_configuration_plugin_removeAdminbar"]) ) {
				add_filter( 'show_admin_bar', array($this, 'removeAdminbar') );
			}
		}
	}

	/**
	 * Completely disables the admin bar
	 * @return [type] [description]
	 */
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

}

?>
