<?php
/*
Module Name: Link pdf to Google viewer
Description: Open links to pdf-files in Google viewer. You need to use the shortcode syntax like this: [pdf href="http://yoursite.com/file.pdf"]PDF[/pdf]
Author: Tobias Böhning
Author URI: http://boehning.net
Scope: Frontend
Type: Checkbox
*/


/* Sicherheitsabfrage */
if ( !class_exists('advancedwordpressconfigurationpluginBase') ) {
	die();
}


add_shortcode('pdf', 'awcp_pdflink');


/**
 * Source: http://wpsnipp.com/index.php/functions-php/enable-google-docs-shortcode-for-pdf-documents/
 * @param  [type] $attr    [description]
 * @param  [type] $content [description]
 * @return [type]          [description]
 */
function awcp_pdflink($attr, $content) {
    if ($attr['href']) {
        return '<a class="pdf" href="http://docs.google.com/viewer?url=' . $attr['href'] . '">'.$content.'</a>';
    } else {
        $src = str_replace("=", "", $attr[0]);
        return '<a class="pdf" href="http://docs.google.com/viewer?url=' . $src . '">'.$content.'</a>';
    }
}
