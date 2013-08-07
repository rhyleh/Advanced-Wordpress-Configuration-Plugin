<?php
/**
   * advanced-wordpress-configuration-pluginWordpressSettings
   * Module frontend
   *
   * @author     T. Boehning
   * @version	1.1
   *
   *
   */
class moduleFrontend {
	
	protected $options_frontend;


  	function __construct() {

		$this->options_frontend 	= get_option('advanced_wordpress_configuration_plugin_frontend');
		
		$this->registerActions();
		$this->registerFilters();
	}

	/**
	 * register the actions - all set via options page
	 */
	function registerActions() {

		if( !is_null($this->options_frontend) ) {

			if( isset($this->options_frontend["advanced_wordpress_configuration_plugin_deregisterPluginStyles"]) ) {
				add_action( 'wp_print_styles', array( &$this, 'deregisterPluginStyles'), 100 );
			}

			if( isset($this->options_frontend["advanced_wordpress_configuration_plugin_deregisterPluginScripts"]) ) {
				add_action( 'wp_print_styles', array( &$this, 'deregisterPluginScripts'), 100 );
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
	}



	/**
	 * register the filters - all set via options page
	 */
	function registerFilters() {

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
	}


	/**
	 * copied from http://www.stephanmuller.nl/removing-wordpress-plugin-style-scripts-head/
	 */
	function deregisterPluginStyles() {

		$handles = array_map('trim', explode(",", $this->options_frontend['advanced_wordpress_configuration_plugin_deregisterPluginStyles']));

		if( is_array($handles) ) {
			foreach($handles as $handle){
				wp_deregister_style( $handle);
			}
		}
	}

	/**
	 * [copied from http://www.stephanmuller.nl/removing-wordpress-plugin-style-scripts-head/
	 * @return [type] [description]
	 */
	function deregisterPluginScripts() {

		$handles = array_map('trim', explode(",", $this->options_frontend['advanced_wordpress_configuration_plugin_deregisterPluginScripts']));

		if( is_array($handles) ) {
			foreach($handles as $handle){
				wp_deregister_script( $handle);
			}
		}
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
	 * sets the length of the excerpt (for get_the_excerpt)
	 */
	function customExcerptLength( $length ) {
		//the amount of words to return

		if( intval($this->options_frontend["advanced_wordpress_configuration_plugin_customExcerptLength"]) > 0 ) return intval($this->options_frontend["advanced_wordpress_configuration_plugin_customExcerptLength"]);
	}

	/**
 	 * remove "Read more" link and simply display three dots
	 *
	 */
	function removeExcerptMore($more) {
		return '...';
	}

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
	 *
	 */
	function removeWordpressVersion() {
		return '';
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

}

?>
