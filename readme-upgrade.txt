-> Upgrade Instructions For Version 2.01 To Version 2.02
------------------------------------------------------------------
// Open wp-admin folder

Overwrite:
------------------------------------------------------------------
email-options.php
email-manager.php
------------------------------------------------------------------


// Open root Wordpress folder

Overwrite:
------------------------------------------------------------------
wp-email.php
------------------------------------------------------------------


// Open wp-includes folder

Overwrite:
------------------------------------------------------------------
functions-wp-email.php
------------------------------------------------------------------


// Open wp-content/plugins folder

Overwrite:
------------------------------------------------------------------
email.php
------------------------------------------------------------------










-> Upgrade Instructions For Version 2.00 To Version 2.01
------------------------------------------------------------------
// Open wp-admin folder

Overwrite:
------------------------------------------------------------------
email-options.php
email-manager.php
------------------------------------------------------------------
Put:
------------------------------------------------------------------
email-upgrade.php
------------------------------------------------------------------


// Open root Wordpress folder

Overwrite:
------------------------------------------------------------------
wp-email.php
------------------------------------------------------------------


// Open wp-includes folder

Put:
------------------------------------------------------------------
functions-wp-email.php
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


// Run wp-admin/email-upgrade.php.php

Note:
------------------------------------------------------------------
Please remember to remove email-upgrade.php.php after installation.
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










-> Upgrade Instructions For Version 1.0x To Version 2.00
------------------------------------------------------------------
Please follow the instructions in readme-install.txt. This file is just a place holder for future version upgrade.