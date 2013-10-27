<?php
/*
Module Name: Color post by status
Description: Change background color of posts according to their status (publish, draft, pending).
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_action('admin_footer','awcp_postsStatusColor');


/**
 * Source: http://wpsnipp.com/index.php/functions-php/change-admin-postpage-color-by-status-draft-pending-published-future-private/
 * @return [type] [description]
 */
function awcp_postsStatusColor() {
	echo '<style>
		.status-draft{background: #FCE3F2 !important;}
		.status-pending{background: #87C5D6 !important;}
		.status-publish{/* no background keep wp alternating colors */}
		.status-future{background: #C6EBF5 !important;}
		.status-private{background:#F2D46F;}
		</style>';
}