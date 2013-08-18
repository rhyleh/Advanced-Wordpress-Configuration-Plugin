<?php
/*
Module Name: Add breadcrumb navigation
Description: Adds an action for breadcrumbs. Needs to be called in theme by "do_action("the_breadcrumb")".
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}



/**
 * register the filters - all set via options page
 */
add_action('the_breadcrumb', 'awcp_addBreadcrumbAction' );



/**
 * Aufruf und Ausgabe im Template via do_action('the_breadcrumb')
 * copied from Sergej Müller: https://gist.github.com/sergejmueller/4706816 
 */
function awcp_addBreadcrumbAction() {
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