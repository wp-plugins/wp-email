<?php
/*
+----------------------------------------------------------------+
|																							|
|	WordPress 1.5 Plugin: WP-EMail 2.00b										|
|	Copyright (c) 2005 Lester "GaMerZ" Chan									|
|																							|
|	File Written By:																	|
|	- Lester "GaMerZ" Chan															|
|	- http://www.lesterchan.net													|
|																							|
|	File Information:																	|
|	- Install WP-EMail 2.00															|
|	- wp-admin/email-install.php													|
|																							|
+----------------------------------------------------------------+
*/


### Require Config
require('../wp-config.php');

### Variables, Variables, Variables
$create_table = array();
$insert_options = array();
$error = '';

### Create Tables (1 Table)
$create_table[] = "CREATE TABLE $wpdb->email (".
	"email_id int(10) NOT NULL auto_increment,".
	"email_yourname varchar(200) NOT NULL default '',".
	"email_youremail varchar(200) NOT NULL default '',".
	"email_yourremarks text NOT NULL,".
	"email_friendname varchar(200) NOT NULL default '',".
	"email_friendemail varchar(200) NOT NULL default '',".
	"email_postid int(10) NOT NULL default '0',".
	"email_posttitle text NOT NULL,".
	"email_timestamp varchar(20) NOT NULL default '',".
	"email_ip varchar(100) NOT NULL default '',".
	"email_host varchar(200) NOT NULL default '',".
	"email_status varchar(20) NOT NULL default '',".
	"PRIMARY KEY (email_id));";
        
### Insert Options  (9 Rows)
$insert_options[] ="INSERT INTO $wpdb->options VALUES (180, 0, 'email_smtp', 'Y', 3, '', 20, 8, 'Your SMTP Name, Password, Server', 8, 'yes');";
$insert_options[] ="INSERT INTO $wpdb->options VALUES (182, 0, 'email_contenttype', 'Y', 3, 'text/html', 20, 8, 'Your E-Mail Type', 8, 'yes');";
$insert_options[] ="INSERT INTO $wpdb->options VALUES (181, 0, 'email_mailer', 'Y', 3, 'smtp', 20, 8, 'Your Mailer Type', 8, 'yes');";
$insert_options[] ="INSERT INTO $wpdb->options VALUES (188, 0, 'email_template_subject', 'Y', 3, 'Recommanded Article By %EMAIL_YOUR_NAME%: %EMAIL_POST_TITLE%', 20, 8, 'Template For E-Mail Subject', 8, 'yes');";
$insert_options[] ="INSERT INTO $wpdb->options VALUES (183, 0, 'email_template_body', 'Y', 3, '<p>Hi <b>%EMAIL_FRIEND_NAME%</b>,<br />Your friend, <b>%EMAIL_YOUR_NAME%</b>, has recommanded this article entitled \'<b>%EMAIL_POST_TITLE%</b>\' to you.</p>\r\n<p><b>Here is his/her remarks:</b><br />%EMAIL_YOUR_REMARKS%</p>\r\n<p><b>%EMAIL_POST_TITLE%</b><br />Posted By %EMAIL_POST_AUTHOR% On %EMAIL_POST_DATE% In %EMAIL_POST_CATEGORY%</p>\r\n%EMAIL_POST_CONTENT%\r\n<p>Article taken from %EMAIL_BLOG_NAME% - <a href=\\\"%EMAIL_BLOG_URL%\\\">%EMAIL_BLOG_URL%</a><br />URL to article: <a href=\\\"%EMAIL_PERMALINK%\\\">%EMAIL_PERMALINK%</a></p>', 20, 8, 'Template For E-Mail Body', 8, 'yes');";
$insert_options[] ="INSERT INTO $wpdb->options VALUES (189, 0, 'email_template_bodyalt', 'Y', 3, 'Hi %EMAIL_FRIEND_NAME%,\r\nYour friend, %EMAIL_YOUR_NAME%, has recommanded this article entitled \'%EMAIL_POST_TITLE%\' to you.\r\n\r\nHere is his/her remarks:\r\n%EMAIL_YOUR_REMARKS%\r\n\r\n%EMAIL_POST_TITLE%\r\nPosted By %EMAIL_POST_AUTHOR% On %EMAIL_POST_DATE% In %EMAIL_POST_CATEGORY%\r\n%EMAIL_POST_CONTENT%\r\nArticle taken from %EMAIL_BLOG_NAME% - %EMAIL_BLOG_URL%\r\nURL to article: %EMAIL_PERMALINK%', 20, 8, 'Template For E-Mail Alternate Body', 8, 'yes');";
$insert_options[] ="INSERT INTO $wpdb->options VALUES (185, 0, 'email_template_sentsuccess', 'Y', 3, '<p>Article: <b>%EMAIL_POST_TITLE%</b> Has Been Sent To <b>%EMAIL_FRIEND_NAME% (%EMAIL_FRIEND_EMAIL%)</b></p>', 20, 8, 'Template For E-Mail That Is Sent Successfully', 8, 'yes');";
$insert_options[] ="INSERT INTO $wpdb->options VALUES (186, 0, 'email_template_sentfailed', 'Y', 3, '<p>An Error Has Occured When Trying To Send The E-Mail<br /><b>»</b> %EMAIL_ERROR_MSG%</p>', 20, 8, 'Template For E-Mail That Failed To Sent', 8, 'yes');";
$insert_options[] ="INSERT INTO $wpdb->options VALUES (187, 0, 'email_template_error', 'Y', 3, '<p>An Error Has Occured<br /><b>&raquo;</b> %EMAIL_ERROR_MSG%</p>', 20, 8, 'Template For E-Mail That Has An Error', 8, 'yes');";

### Check Whether There Is Any Pre Errors
$wpdb->show_errors = false;
$check_install = $wpdb->query("SHOW COLUMNS FROM $wpdb->email");
if($check_install) {
	$error = __('You Had Already Installed WP-EMail.');
}
if(empty($wpdb->email)) {
	$error = __('Please Define The email mysql table in wp-settings.php.');
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>WordPress &rsaquo; <?php _e('Installing'); ?> &rsaquo; <?php _e('WP-Email 2.00b'); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css" media="screen">
		@import url( wp-admin.css );
	</style>
</head>
<body>
	<div class="wrap"> 
		<h2><?php _e('Install WP-Email'); ?></h2>
		<p><?php _e('This install script will install WP-EMail 2.00b for your Wordpress'); ?>.</p>
		<p>
			<?php _e('This install script will be doing the following:'); ?><br />
			<b>&raquo;</b> <b>1</b> <?php _e('table will be created namely <b>email</b>.'); ?><br />
			<b>&raquo;</b> <b>9</b> <?php _e('options will be inserted into the <b>options</b> table.'); ?><br />
			<b>&raquo;</b> <b>2</b> <?php _e('tables will be optimized namely <b>options</b>, <b>email</b>.'); ?><br />
		</p>
		<?php
			if(empty($error)) {
				if(!empty($_POST['install'])) {
					// Create Tables
					$create_table_count = 0;
					echo "<p><b>".__('Creating Tables:')."</b>";
					foreach($create_table as $createtable) {
						$wpdb->query($createtable);
					}
					$check_email= $wpdb->query("SHOW COLUMNS FROM $wpdb->email");
					if($check_email) { 
						echo "<br /><b>&raquo;</b> Table (<b>$wpdb->email</b>) created.";
						$create_table_count++; 
					} else {
						echo "<br /><b>&raquo;</b> <font color=\"red\">Table (<b>$wpdb->email</b>) table NOT created.</font>";
					}
					echo "<br /><b>&raquo;</b> <b>$create_table_count / 1</b> Tables Created.</p>";
					// Insert Options
					$insert_options_count = 0;
					echo "<p><b>".__('Inserting Options:')."</b>";
					foreach($insert_options as $insertoptions) {
						$temp_options = $wpdb->query($insertoptions);
						$temp_option = explode(" ", $insertoptions);
						$temp_option = $temp_option[6];
						$temp_option = substr($temp_option, 1, -2);
						if($temp_options) {
								echo "<br /><b>&raquo;</b> Option (<b>$temp_option</b>) inserted.";
								$insert_options_count ++;
						} else {
							echo "<br /><b>&raquo;</b> <font color=\"red\">Option (<b>$temp_option</b>) NOT inserted.</font>";
						}
					}
					echo "<br /><b>&raquo;</b> <b>$insert_options_count / 9</b> Options Inserted.</p>";
					// Optimize Tables
					$optimize_table_count = 0;
					echo "<p><b>".__('Optimizing Tables:')."</b>";
					$optimize_tables = $wpdb->query("OPTIMIZE TABLE $wpdb->options, $wpdb->email");
					if($optimize_tables) {
						echo "<br /><b>&raquo;</b> Tables (<b>$wpdb->options</b>, <b>$wpdb->email</b>) optimized.";
						$optimize_table_count = 2;
					} else {
						echo "<br /><b>&raquo;</b> <font color=\"red\">Tables (<b>$wpdb->options</b>, <b>$wpdb->email</b>) NOT optimized.</font>";
					}
					echo "<br /><b>&raquo;</b> <b>$optimize_table_count / 2</b> Tables Optimized.</p>";
					// Check Whether Install Is Successful
					if($create_table_count == 1 && $insert_options_count == 9) {
						echo '<p align="center"><b>'.__('WP-EMail Installed Successfully.').'</b><br />'.__('Please remember to delete this file before proceeding on.').'</p>';
					}
				} else {
		?>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<div align="center"><input type="submit" name="install" value="<?php _e('Click Here To Install WP-EMail'); ?>" class="button"></div>
				</form>
		<?php
				}
			} else {
				echo "<p align=\"center\"><font color=\"red\"><b>$error</b></font></p>\n";
			}
		?>
	</div>
</body>
</html>