-> Installation Instructions
------------------------------------------------------------------
// Open wp-admin folder

Put:
------------------------------------------------------------------
email-install.php
email-manager.php
email-options.php
------------------------------------------------------------------


// Open wp-includes folder

Put:
------------------------------------------------------------------
class-phpmailer.php
class-smtp.php
functions-wp-email.php
------------------------------------------------------------------


// Open root Wordpress folder

Put:
------------------------------------------------------------------
wp-email.php
------------------------------------------------------------------


// Open wp-content/plugins folder

Put:
------------------------------------------------------------------
email.php
------------------------------------------------------------------


// Activate the email plugin

Note: 
------------------------------------------------------------------
You MAY Need To Re-Generate The Permalink.
Options -> Permalinks Options -> Update Permalink Structure
------------------------------------------------------------------


// Run wp-admin/email-install.php

Note:
------------------------------------------------------------------
Please remember to remove email-install.php after installation.
------------------------------------------------------------------


// Open wp-content/themes/<YOUR THEME NAME>/index.php 

Find:
------------------------------------------------------------------
<?php while (have_posts()) : the_post(); ?>
------------------------------------------------------------------
Add Anywhere Below It:
------------------------------------------------------------------
<?php if(function_exists('wp_email')) { email_link(); } ?>
------------------------------------------------------------------
Note:
------------------------------------------------------------------
The first value you pass in is the text for emailing post.
The second value is the text for emailing page.
Default: email_link('EMail This Post', 'EMail This Page')
------------------------------------------------------------------
