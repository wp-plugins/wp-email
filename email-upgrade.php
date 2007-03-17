<?php
/*
+----------------------------------------------------------------+
|																							|
|	WordPress 2.0 Plugin: WP-Email 2.02										|
|	Copyright (c) 2005 Lester "GaMerZ" Chan									|
|																							|
|	File Written By:																	|
|	- Lester "GaMerZ" Chan															|
|	- http://www.lesterchan.net													|
|																							|
|	File Information:																	|
|	- Upgrade WP-EMail From 2.0x To 2.02	 									|
|	- wp-admin/email-upgrade.php												|
|																							|
+----------------------------------------------------------------+
*/


### Require Config
require('../wp-config.php');

### Variables, Variables, Variables
$insert_options = array();
$error = '';

### Insert Options  (2 Rows)
$insert_options[] ="INSERT INTO $wpdb->options VALUES (0, 0, 'email_interval', 'Y', 1, '10', 20, 8, 'The Number Of Minutes Before The User Can E-Mail The Next Article.', 1, 'yes');";
$insert_options[] ="INSERT INTO $wpdb->options VALUES (0, 0, 'email_snippet', 'Y', 1, '100', 20, 8, 'Enable Snippet Feature For Your E-Mail?', 1, 'yes');";

### Check Whether There Is Any Pre Errors
$wpdb->show_errors = false;
$check_upgrade = $wpdb->get_var("SELECT option_value FROM $wpdb->options WHERE option_name = 'email_snippet'");
if($check_upgrade) {
	$error = __('You Had Already Installed WP-EMail 2.02.');
}
if(empty($wpdb->email)) {
	$error = __('Please Define The email in wp-settings.php.');
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>WordPress &rsaquo; <?php _e('Upgrading'); ?> &rsaquo; <?php _e('WP-EMail 2.02'); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css" media="screen">
		@import url( wp-admin.css );
	</style>
</head>
<body>
	<div class="wrap"> 
		<h2><?php _e('Upgrading WP-EMail 2.02'); ?></h2>
		<p><?php _e('This upgrade script will upgrade WP-EMail to version 2.02 for your Wordpress.'); ?></p>
		<p>
			<?php _e('This upgrade script will be doing the following:'); ?><br />
			<b>&raquo;</b> <b>2</b> <?php _e('options will be inserted into the <b>options</b> table.'); ?><br />
			<b>&raquo;</b> <b>2</b> <?php _e('tables will be optimized namely <b>email</b> and <b>options</b>.'); ?><br />
		</p>
		<?php
			if(empty($error)) {
				if(!empty($_POST['upgrade'])) {
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
					echo "<br /><b>&raquo;</b> <b>$insert_options_count / 2</b> Options Inserted.</p>";
					// Optimize Tables
					$optimize_table_count = 0;
					echo "<p><b>".__('Optimizing Tables:')."</b>";
					$optimize_tables = $wpdb->query("OPTIMIZE TABLE $wpdb->email, $wpdb->options");
					if($optimize_tables) {
						echo "<br /><b>&raquo;</b> Tables (<b>$wpdb->email</b>, <b>$wpdb->options</b>) optimized.";
						$optimize_table_count = 2;
					} else {
						echo "<br /><b>&raquo;</b> <font color=\"red\">Tables (<b>$wpdb->email</b>, <b>$wpdb->options</b>) NOT optimized.</font>";
					}
					echo "<br /><b>&raquo;</b> <b>$optimize_table_count / 4</b> Tables Optimized.</p>";
					// Check Whether Install Is Successful
					if($insert_options_count == 2) {
						echo '<p align="center"><b>'.__('WP-EMail Upgraded Successfully To Version 2.02.').'</b><br />'.__('Please remember to delete this file before proceeding on.').'</p>';
					}
				} else {
		?>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<div align="center"><input type="submit" name="upgrade" value="<?php _e('Click Here To Upgrade WP-EMail 2.02'); ?>" class="button"></div>
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