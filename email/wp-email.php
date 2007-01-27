<?php
/*
+----------------------------------------------------------------+
|																							|
|	WordPress 2.1 Plugin: WP-EMail 2.10										|
|	Copyright (c) 2007 Lester "GaMerZ" Chan									|
|																							|
|	File Written By:																	|
|	- Lester "GaMerZ" Chan															|
|	- http://www.lesterchan.net													|
|																							|
|	File Information:																	|
|	- E-Mail Post/Page To A Friend												|
|	- wp-content/plugins/email/wp-email.php									|
|																							|
+----------------------------------------------------------------+
*/


### Session Start
session_start();

### Require PHP-Mailer Class
require(ABSPATH.'wp-content/plugins/email/class-phpmailer.php');

### Filters
add_filter('wp_title', 'email_pagetitle');
add_filter('the_title', 'email_title');
add_filter('the_content', 'email_form', '', false, false);

### We Use Page Template
include(get_page_template());
?>