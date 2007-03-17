-> Installation Instructions
------------------------------------------------------------------
// Open wp-content/plugins folder

Put:
------------------------------------------------------------------
Folder: email
------------------------------------------------------------------


// Activate WP-EMail plugin

Note: 
------------------------------------------------------------------
You MAY Need To Re-Generate The Permalink.
Options -> Permalinks Options -> Update Permalink Structure
------------------------------------------------------------------


// Tutorial On How To Integrate wp-email.php With Your Theme

Go To:
------------------------------------------------------------------
http://www.lesterchan.net/wordpress/tutorials/integrating/
------------------------------------------------------------------



-> Usage Instructions
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

If you want to use an image/icon instead, replace email_link(); 
with email_link_image();

If you want to use a popup window, replace email_link();
with email_popup(); for normal link or email_popup_image(); for image/icon.

If you are having templates problem, I recommend you to use the popup
window option instead.
------------------------------------------------------------------


// Log into http://www.yourdomain.com/wordpressdirectory/wp-admin/ 

Click "E-Mail"
------------------------------------------------------------------
Modify your settings accordingly.
------------------------------------------------------------------
Note:
------------------------------------------------------------------
If the mailserver/type settings are not correct, WP-Email will
NOT function.
------------------------------------------------------------------


// E-Mail Stats (You can place it anywhere outside the WP Loop)

// To Display Most E-Mailed Posts
Use:
------------------------------------------------------------------
<?php if (function_exists('get_mostemailed')): ?>
	<?php get_mostemailed(); ?>
<?php endif; ?>
------------------------------------------------------------------
Note:
------------------------------------------------------------------
The default limit is 10, you can change the limit by doing this
<?php get_mostemailed(<LIMIT>); ?>
------------------------------------------------------------------


// To Display Total E-Mails Sent

Use:
------------------------------------------------------------------
<?php if (function_exists('get_emails')): ?>
	<?php get_emails(); ?>
<?php endif; ?>
------------------------------------------------------------------


// To Display Total E-Mails Sent Successfully

Use:
------------------------------------------------------------------
<?php if (function_exists('get_emails_success')): ?>
	<?php get_emails_success(); ?>
<?php endif; ?>
------------------------------------------------------------------


// To Display Total E-Mails Sent Unsuccessfully

Use:
------------------------------------------------------------------
<?php if (function_exists('get_emails_failed')): ?>
	<?php get_emails_failed(); ?>
<?php endif; ?>
------------------------------------------------------------------