-> Installation Instructions
------------------------------------------------------------------
// Open wp-settings.php

Find:
------------------------------------------------------------------
$wpdb->postmeta					= $table_prefix . 'postmeta';
------------------------------------------------------------------
Add Below It:
------------------------------------------------------------------
$wpdb->email					= $table_prefix . 'email';
------------------------------------------------------------------


// Open wp-admin/menu.php

Find:
------------------------------------------------------------------
$menu[20] = array(__('Links'), 5, 'link-manager.php');
------------------------------------------------------------------
Add Below It:
------------------------------------------------------------------
$menu[21] = array(__('E-Mail'), 5, 'email-manager.php');
------------------------------------------------------------------
Note:
------------------------------------------------------------------
If $menu[21] already exist, use $menu[22] or $menu[23] and so on
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
------------------------------------------------------------------


// Open root Wordpress folder

Put:
------------------------------------------------------------------
wp-email.php
------------------------------------------------------------------


// Run wp-admin/email-install.php

Note:
------------------------------------------------------------------
Please remember to remove email-install.php after installation.
------------------------------------------------------------------


// Go to Manage > Files > Common -> .htaccess (for rewrite rules)
// Note: If you ARE using nice permalink url
Find:
------------------------------------------------------------------
# END WordPress
------------------------------------------------------------------
Add Below It:
------------------------------------------------------------------
RewriteRule ^archives/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/email/?$ <BLOG URL>/wp-email.php?year=$1&monthnum=$2&day=$3&name=$4 [QSA,L]
------------------------------------------------------------------

Find: # BEGIN WordPRess
------------------------------------------------------------------
Add Above It:
------------------------------------------------------------------
RewriteRule ^(.+)/emailpage/?$ <BLOG URL>/wp-email.php?pagename=$1 [QSA,L]
------------------------------------------------------------------
Note:
------------------------------------------------------------------
<BLOG URL> Is the directory to your Wordpress. If your Wordpress is in the root, just remove <BLOG URL>.
If not just type in your directory to wordpress with a back-slash in front.
------------------------------------------------------------------


// Open wp-content/themes/<YOUR THEME NAME>/index.php 

Find:
------------------------------------------------------------------
<?php while (have_posts()) : the_post(); ?>
------------------------------------------------------------------
Add Below It:
------------------------------------------------------------------
<?php if(is_page()) : ?>
<a href="<?php the_permalink(); ?>emailpage/">E-Mail This Article</a>
<?php else : ?>
<a href="<?php the_permalink(); ?>email/">E-Mail This Article</a>
<?php endif; ?>
------------------------------------------------------------------


// Note: If you ARE NOT using nice permalink url
// Open wp-content/themes/<YOUR THEME NAME>/index.php 

Find:
------------------------------------------------------------------
<?php while (have_posts()) : the_post(); ?>
------------------------------------------------------------------
Add Below It:
------------------------------------------------------------------
<?php if(is_page()) : ?>
<a href="wp-email.php?page_id=<?=the_ID()?>">E-Mail This Article</a>
<?php else : ?>
<a href="wp-email.php?p=<?=the_ID()?>">E-Mail This Article</a>
<?php endif; ?>
------------------------------------------------------------------