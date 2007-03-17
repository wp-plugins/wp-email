-> Email Plugin For WordPress
--------------------------------------------------
Author	-> Lester 'GaMerZ' Chan
Email	-> lesterch@singnet.com.sg
Website	-> http://www.lesterchan.net/
Demo	-> http://www.lesterchan.net/blogs/wp-email.php?p=647
Updated	-> 17th May 2005
--------------------------------------------------

-> Installation Instructions
--------------------------------------------------
// Open wp-includes folder

Put:
------------------------------------------------------------------
class-phpmailer.php
class-smtp.php
------------------------------------------------------------------


// Open root Wordpress folder

Put:
------------------------------------------------------------------
wp-email.php
------------------------------------------------------------------


// Open wp-email.php

Modify:
------------------------------------------------------------------
$smtp_server = 'YOUR SMTP SERVER';
$smtp_username = 'YOUR SMTP SERVER USERNAME';
$smtp_password = 'YOUR SMTP SERVER PASSWORD';
------------------------------------------------------------------



// Go to Manage > Files > Common -> .htaccess (for rewrite rules)
// Note: If you ARE using nice permalink url
Find:
------------------------------------------------------------------
RewriteRule ^archives/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/?([0-9]+)?/?$ <BLOG URL>/index.php?year=$1&monthnum=$2&day=$3&name=$4&page=$5 [QSA,L]
------------------------------------------------------------------
Add Below It:
------------------------------------------------------------------
RewriteRule ^archives/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/email/?$ <BLOG URL>/wp-email.php?year=$1&monthnum=$2&day=$3&name=$4 [QSA,L]
------------------------------------------------------------------

Find:
------------------------------------------------------------------
<?php while (have_posts()) : the_post(); ?>
------------------------------------------------------------------
Add Below It:
------------------------------------------------------------------
<a href="<?php the_permalink(); ?>email/">Email This Article</a>
------------------------------------------------------------------



// Open wp-content/themes/<YOUR THEME NAME>/index.php 
// Note: If you ARE NOT using nice permalink url
Find:
------------------------------------------------------------------
<?php while (have_posts()) : the_post(); ?>
------------------------------------------------------------------
Add Below It:
------------------------------------------------------------------
<a href="wp-email.php?p=<?php the_ID(); ?>">Email This Article</a>
------------------------------------------------------------------