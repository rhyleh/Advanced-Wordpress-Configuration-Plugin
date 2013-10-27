<?php
/*
Module Name: Enable for excerpt
Description: Enables the rich text editor for excerpt fields.
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Backend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_action( 'admin_head-post.php', 'awcp_tinymce_css');  
add_action( 'admin_head-post-new.php', 'awcp_tinymce_css'); 

add_action( 'admin_head-post.php', 'awcp_tinymce_excerpt_js');  
add_action( 'admin_head-post-new.php', 'awcp_tinymce_excerpt_js');  


/**
 * Source: http://wpsnipp.com/index.php/excerpt/enable-tinymce-editor-for-post-the_excerpt/
 * @return [type] [description]
 */
function awcp_tinymce_excerpt_js(){ 
	echo '<script type="text/javascript">  
		jQuery(document).ready( tinymce_excerpt );  
		function tinymce_excerpt() {  
			jQuery("#excerpt").addClass("mceEditor");  
			tinyMCE.execCommand("mceAddControl", false, "excerpt");  
		}  
	</script> ';
}
  
function awcp_tinymce_css(){
	echo "<style type='text/css'>  
		#postexcerpt .inside{margin:0;padding:0;background:#fff;}  
		#postexcerpt .inside p{padding:0px 0px 5px 10px;}  
		#postexcerpt #excerpteditorcontainer { border-style: solid; padding: 0; }  
	</style>";
}