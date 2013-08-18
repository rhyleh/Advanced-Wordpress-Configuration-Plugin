Advanced Wordpress Configuration Plugin
=======================================
* Contributors: Tobias Boehning
* Tags: backend, frontend, admin bar, configuration
* Requires at least: 3.3.1
* Tested up to: 3.5.2
* Stable tag: 4.3


Description
-----------

This is a collection of useful settings (based on different websites) to quickly configure Wordpress without having to google advanced settings. The code was mostly copied from different sites and has accumulated over some time (I'm sorry I didn't note where I took it from...). Although this plugin is far from finished I use it on any of my various Wordpress websites.

This plugin was vastly inspired by the Toolbox Plugin (http://playground.ebiene.de/toolbox-wordpress-plugin/).


Installation
------------


1. Upload `advanced-wordpress-configuration-plugin` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Set (some) options


Frequently Asked Questions
--------------------------

Can I ask a question?
No.


Screenshots
-----------

No screenshots available.


Changelog
---------

1.0
* Initial release.



wp-config.php Tipps
-------------------

Also, check these tipps for wp-config.php (based on http://digwp.com/2009/06/wordpress-configuration-tricks/):

Security
- Move wp-config to a non-accessible (via webserver) location. Wordpress will automatically look in the next higher directory (unfortunately this doesn't help very much).
- Use a backup plugin like BackWPup (this is essential).
- Use SSL for backend (and define in wp-config.php: define('FORCE_SSL_LOGIN', true); or define('FORCE_SSL_ADMIN', true); (latter is safer)) (this is good to have but comes with additional costs).
- Limit login attemps (e.g. install Limit Login Attempts plugin or Login LockDown) (this only helps for smaller attacks, not against larger attacks from thousands of IP-addresses). It's better so secure the admin area by .htaccess.
- Security through obfuscation is not security.
- Better WP Security - it helps a little. 
- Plugin AntiVirus - notifies of template changes.
- Plugin Snitch - monitors outgoing connections.

See here for more in-depth information (in German):
http://www.kuketz-blog.de/basisschutz-wordpress-absichern-teil1/
http://www.kuketz-blog.de/schutzmassnahmen-wordpress-absichern-teil2/
http://www.kuketz-blog.de/security-plugins-wordpress-absichern-teil3/


Blog Address and Site Address
By default, these two configurational definitions are not included in the wp-config.php file, but they should be added to improve performance. 

define('WP_HOME', 'http://'.$_SERVER['HTTP_HOST'].'/path/to/wordpress');
define('WP_SITEURL', 'http://'.$_SERVER['HTTP_HOST'].'/path/to/wordpress');


Set new upload folder
define( 'UPLOADS', 'wp-content/uploads' );


Template Path and Stylesheet Path
As with the predefined constants for blog address and site address (see previous section), you can also boost performance by eliminating database queries for the template path and stylesheet path for your site.

define('TEMPLATEPATH', get_template_directory());
define('STYLESHEETPATH', get_stylesheet_directory());

or better:

define('TEMPLATEPATH', get_template_directory());
define('STYLESHEETPATH', get_stylesheet_directory());



Specify Cookie Domain
There are several reasons why you want to specify a cookie domain for your site. A common example involves preventing cookies from being sent with requests for static content on subdomains. In this case, you would use this definition to tell WordPress to send cookies only to your non-static domain. This could be a significant performance boost. 

define('COOKIE_DOMAIN', '.digwp.com'); // don't omit the leading '.'
define('COOKIEPATH', preg_replace('|https?://[^/]+|i', '', get_option('home').'/'));
define('SITECOOKIEPATH', preg_replace('|https?://[^/]+|i', '', get_option('siteurl').'/'));
define('PLUGINS_COOKIE_PATH', preg_replace('|https?://[^/]+|i', '', WP_PLUGIN_URL));
define('ADMIN_COOKIE_PATH', SITECOOKIEPATH.'wp-admin');



Moving Your wp-content directory
There are several good reasons for doing this, including enhancement of site security and facilitation of FTP updates.

// full local path of current directory (no trailing slash)
define('WP_CONTENT_DIR', $_SERVER['DOCUMENT_ROOT'].'/path/wp-content'); 

// full URI of current directory (no trailing slash)
define('WP_CONTENT_URL', 'http://domain.tld/path/wp-content');

You may also further specify a custom path for your plugins directory. This may help with compatibility issues with certain plugins:

// full local path of current directory (no trailing slash)
define('WP_PLUGIN_DIR', $_SERVER['DOCUMENT_ROOT'].'/path/wp-content/plugins'); 

// full URI of current directory (no trailing slash)
define('WP_PLUGIN_URL', 'http://domain.tld/path/wp-content/plugins');



Dealing with Post Revisions
Limit the number of saved revisions

define('WP_POST_REVISIONS', 3); // any integer



Specify the Autosave Interval
By default, WordPress saves your work every 60 seconds, but you can totally modify this setting to whatever you want. 

define('AUTOSAVE_INTERVAL', 160); // in seconds



Error Log Configuration
Here is an easy way to enable basic error logging for your WordPress-powered site. Create a file called “php_error.log”, make it server-writable, and place it in the directory of your choice. Then edit the path in the third line of the following code and place into your wp-config.php file:

@ini_set('log_errors','On');
@ini_set('display_errors','Off');
@ini_set('error_log','/home/path/domain/logs/php_error.log');






Custom Database Error Page

Put a file called "db-error.php" directly inside your /wp-content/ folder and WordPress will automatically use that when there is a database connection problem:

<?php // custom WordPress database error page

  header('HTTP/1.1 503 Service Temporarily Unavailable');
  header('Status: 503 Service Temporarily Unavailable');
  header('Retry-After: 600'); // 1 hour = 3600 seconds

  // If you wish to email yourself upon an error
  // mail("your@email.com", "Database Error", "There is a problem with the database!", "From: Db Error Watching");

?>

<!DOCTYPE HTML>
<html>
<head>
<title>Database Error</title>
<style>
body { padding: 20px; background: red; color: white; font-size: 60px; }
</style>
</head>
<body>
  You got problems.
</body>
</html>

