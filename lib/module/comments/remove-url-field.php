<?php
/*
Module Name: Remove url field
Description: Remove the url field from comment form.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_filter('comment_form_default_fields', 'unset_url_field');


/**
 * Source: http://wpsnipp.com/index.php/comment/remove-unset-url-field-from-comment-form/
 * @param  [type] $fields [description]
 * @return [type]         [description]
 */
function unset_url_field($fields){
    if(isset($fields['url']))
       unset($fields['url']);
       return $fields;
}