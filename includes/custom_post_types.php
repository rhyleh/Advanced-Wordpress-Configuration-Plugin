<?php

/**
 * registers custom post types like produkt
 * original code based on plugin "Custom Post Type UI"
 * register capabilities for each role via role scoper plugin!
 */
function registerCustomPostTypes() {

	//good explanation: http://justintadlock.com/archives/2010/04/29/custom-post-types-in-wordpress

	
	createCustomPostTypeDemo();
	registerRolesAndCapabilitesDemo();

	add_action( 'init', 'createTaxonomyDemo', 0 );
	
	//register indivuelle Messages im Backend
	add_filter( 'post_updated_messages', 'registerMessagesDemo' );
	
	add_action( 'contextual_help', 'provideHelp', 10, 3 );

}

function createCustomPostTypeDemo() {
	$labelsDemo = array(
		'name' => __('Demo', 'post type general name'),
		'singular_name' => __('Demo', 'post type singular name'),
		'menu_name' => __('Demo'),
		'add_new' => __('Neues Demo', 'Intro-Eintrag'),
		'add_new_item' => __('Demo hinzufügen'),
		'edit' => __('Bearbeiten'),
		'edit_item' => __('Demo bearbeiten'),
		'new_item' => __('Neuer Demo-Eintrag'),
		'view_item' => __('Demo-Eintrag ansehen'),
		'view' => __('Demo ansehen'),
		'search_items' => __('Demos durchsuchen'),
		'not_found' =>  __('Nichts gefunden'),
		'not_found_in_trash' => __('Nichts gefunden im Papierkorb'),
		'parent' => __('Parent Demo'),
		'parent_item_colon' => ''
	);

	$argsDemo = array(
		'label' => 'Demo',
		'labels' => $labelsDemo,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		//'menu_icon' => get_stylesheet_directory_uri() . '/images/article16.png',
		//'rewrite' => true,
		'capability_type' => 'post',
		'capabilities' => array(
							'publish_posts' => 'publish_demo',
							'edit_post' => 'edit_demo',
							'edit_posts' => 'edit_demo',
							'edit_others_posts' => 'edit_others_demo',
							'edit_published_posts' => 'edit_published_demo',
							'delete_post' => 'delete_demo',
							'delete_posts' => 'delete_demo',
							'delete_others_posts' => 'delete_others_demo',
							'delete_published_posts' => 'delete_published_demo',
							'read_private_posts' => 'read_private_demo',
							'read_post' => 'read_demo'
			),
		//'map_meta_cap' => true,
		'rewrite' => array("slug" => "demo"), 
		'hierarchical' => false,
		//'menu_position' => null,
		'supports' => array('title','editor', 'revisions') 
	 ); 
	
	register_post_type( 'demo' , $argsDemo );

}

function registerRolesAndCapabilitesDemo() {
	//Capabilites fuer Administrator Rolle hinzufuegen:
	$role = get_role( 'administrator' ); 
	$role->add_cap( 'publish_demo' );
	$role->add_cap( 'edit_demo' );
	$role->add_cap( 'edit_others_demo' );
	$role->add_cap( 'edit_published_demo' );
	$role->add_cap( 'delete_demo' );
	$role->add_cap( 'delete_others_demo' );
	$role->add_cap( 'delete_published_demo' );
	$role->add_cap( 'read_private_demo' );
	$role->add_cap( 'read_demo' );

	//Capabilities und Roles am besten ueber das Plugin Capability Manager Enhanced verwalten


	//diese Funktionalitaet ist hier nicht abgebildet
	$neueRolle = add_role( 'marketing', 'Marketing', array( 'read' => true, 'manage_links',  'manage_categories',  'moderate_comments' ));  
}

function registerMessagesDemo( $messages ) {
	global $post, $post_ID;
	$messages['demo'] = array(
		0 => '', 
		1 => sprintf( __('Demo updated. <a href="%s">View demo</a>'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Demo updated.'),
		5 => isset($_GET['revision']) ? sprintf( __('Demo restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Demo published. <a href="%s">View product</a>'), esc_url( get_permalink($post_ID) ) ),
		7 => __('Demo saved.'),
		8 => sprintf( __('Demo submitted. <a target="_blank" href="%s">Preview product</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Demo scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview product</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Demo draft updated. <a target="_blank" href="%s">Preview product</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $messages;
}

function provideHelp( $contextual_help, $screen_id, $screen ) { 
	if ( 'demo' == $screen->id ) {

		$contextual_help = '<h2>Demo</h2>
		<p>Products show the details of the items that we sell on the website. You can see a list of them on this page in reverse chronological order - the latest one we added is first.</p> 
		<p>You can view/edit the details of each product by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items.</p>';

	} 
	return $contextual_help;
}

function createTaxonomyDemo() {
	
	$labels = array(
		'name'              => _x( 'Demo Kategorien', 'taxonomy general name' ),
		'singular_name'     => _x( 'Demo Kategorie', 'taxonomy singular name' ),
		'search_items'      => __( 'Demo Kategorien durchsuchen' ),
		'all_items'         => __( 'Alle Demo Kategorien' ),
		'parent_item'       => __( 'Parent Demo Kategorie' ),
		'parent_item_colon' => __( 'Parent Demo Kategorie:' ),
		'edit_item'         => __( 'Demo Kategorie editieren' ), 
		'update_item'       => __( 'Demo Kategorie aktualisieren' ),
		'add_new_item'      => __( 'Neue Demo Kategorie' ),
		'new_item_name'     => __( 'Neue Demo Kategorie' ),
		'menu_name'         => __( 'Demo Kategorien' ),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
	);
	register_taxonomy( 'demo_kategorie', 'demo', $args );

}

?>
