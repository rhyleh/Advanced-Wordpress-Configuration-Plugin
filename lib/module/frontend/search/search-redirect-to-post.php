<?php
/*
Module Name: Redirect search result
Description: Redirects to post if only one result is found.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_action('template_redirect', 'awcp_redirectSingleResult');  


/**
 * [single_result description]
 * @return [type] [description]
 */
function awcp_redirectSingleResult() {  
    if (is_search()) {  
        global $wp_query;  
        if ($wp_query->post_count == 1) {  
            wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );  
        }  
    }  
}  