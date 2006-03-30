-> Upgrade Instructions For Version 2.03 To Version 2.04
------------------------------------------------------------------
// Deactivate WP-EMail plugin


// Open wp-content/plugins folder

Overwrite:
------------------------------------------------------------------
Folder: email
------------------------------------------------------------------


// Open root Wordpress folder

Delete:
------------------------------------------------------------------
wp-email.php
------------------------------------------------------------------


// Activate WP-EMail plugin


// Go to 'WP-Admin -> EMail -> EMail Options' and restore all the template variables to Default



-> Upgrade Instructions For Version 1.0x To Version 2.04
------------------------------------------------------------------
// Deactivate WP-EMail plugin


// Delete these file if exists

Delete:
------------------------------------------------------------------
wp-content/plugins/email.php
wp-admin/email-options.php
wp-admin/email-manager.php
wp-includes/class-phpmailer.php
wp-includes/class-smtp.php
wp-includes/functions-wp-email.php
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


// Refer to readme-install.txt for further usage instructions