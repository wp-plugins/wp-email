<?php
/*
Plugin Name: WP-EMail
Plugin URI: http://www.lesterchan.net/portfolio/programming.php
Description: Enable You To Send Your Webblog Entry To A Friend.
Version: 2.03
Author: GaMerZ
Author URI: http://www.lesterchan.net
*/


/*  Copyright 2005  Lester Chan  (email : gamerz84@hotmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


### E-Mail Table Name
$wpdb->email = $table_prefix . 'email';


### Function: E-Mail Administration Menu
add_action('admin_menu', 'email_menu');
function email_menu() {
	if (function_exists('add_menu_page')) {
		add_menu_page(__('E-Mail'), __('E-Mail'), 'manage_email', 'email/email-manager.php');
	}
	if (function_exists('add_submenu_page')) {
		add_submenu_page('email/email-manager.php', __('Manage E-Mail'), __('Manage E-Mail'), 'manage_email', 'email/email-manager.php');
		add_submenu_page('email/email-manager.php', __('E-Mail Options'), __('E-Mail Options'),  'manage_email', 'email/email-options.php');
	}
}


### Function: E-Mail htaccess ReWrite Rules
add_filter('generate_rewrite_rules', 'email_rewrite');
function email_rewrite($wp_rewrite) {
	$rewrite_rules2 = $wp_rewrite->generate_rewrite_rule($wp_rewrite->permalink_structure.'email');
	array_splice($rewrite_rules2, 1);
	$r_rule = array_shift(array_keys($rewrite_rules2));
	$r_rule = str_replace('/trackback', '', $r_rule);
	$r_link = array_shift(array_values($rewrite_rules2));
	$r_link = str_replace('tb=1', 'email=1', $r_link);
    $email_rules = array($r_rule => $r_link, '(.+)/emailpage/?$' => 'index.php?pagename='.$wp_rewrite->preg_index(1).'&email=1');
    $wp_rewrite->rules = $email_rules + $wp_rewrite->rules;
}


### Function: E-Mail Public Variables
add_filter('query_vars', 'email_variables');
function email_variables($public_query_vars) {
	$public_query_vars[] = 'email';
	$public_query_vars[] = 'wp-email';
	return $public_query_vars;
}


### Function: Display E-Mail Link
function email_link($text_post = 'Email This Post', $text_page = 'EMail This Page') {
	global $id;
	$using_permalink = get_settings('permalink_structure');
	$permalink = get_permalink();
	if(!empty($using_permalink)) {
		if(is_page()) {
			echo '<a href="'.$permalink.'emailpage/">'.$text_page.'</a>';
		} else {
			echo '<a href="'.$permalink.'email/">'.$text_post.'</a>';
		}
	} else {
		if(is_page()) {
			echo '<a href="'.get_settings('home').'/wp-email.php?page_id='.$id.'">'.$text_page.'</a>';
		} else {
			echo '<a href="'.get_settings('home').'/wp-email.php?p='.$id.'">'.$text_post.'</a>';
		}
	}
}


### Function: Display E-Mail Image Link
function email_link_image() {
	global $id;
	$using_permalink = get_settings('permalink_structure');
	$permalink = get_permalink();
	if(file_exists(ABSPATH.'/wp-content/plugins/email/images/email.gif')) {
		$email_image = '<img src="'.get_settings('siteurl').'/wp-content/plugins/email/images/email.gif" alt="E-Mail This Post/Page" />';
	} else {
		$email_image = 'E-Mail';
	}
	if(!empty($using_permalink)) {
		if(is_page()) {
			echo '<a href="'.$permalink.'emailpage/">'.$email_image.'</a>';
		} else {
			echo '<a href="'.$permalink.'email/">'.$email_image.'</a>';
		}
	} else {
		if(is_page()) {
			echo '<a href="'.get_settings('home').'/wp-email.php?page_id='.$id.'">'.$email_image.'</a>';
		} else {
			echo '<a href="'.get_settings('home').'/wp-email.php?p='.$id.'">'.$email_image.'</a>';
		}
	}
}


### Function: Get EMail Total Sent
function get_emails() {
	global $wpdb;
	if(function_exists('wp_email')) {
		$totalemails = $wpdb->get_var("SELECT COUNT(email_id) FROM $wpdb->email");
		echo $totalemails;
	}
}


### Function: Get EMail Total Sent Success
if(!function_exists('get_emails_success')) {
	function get_emails_success() {
		global $wpdb; 
		$totalemails_success = $wpdb->get_var("SELECT COUNT(email_id) FROM $wpdb->email WHERE email_status = '".__('Success')."'");
		echo $totalemails_success;
	}
}


### Function: Get EMail Total Sent Failed
if(!function_exists('get_emails_failed')) {
	function get_emails_failed() {
		global $wpdb; 
		$totalemails_failed = $wpdb->get_var("SELECT COUNT(email_id) FROM $wpdb->email WHERE email_status = '". __('Failed')."'");
		echo $totalemails_failed;
	}
}


### Function: Get Most E-Mailed
if(!function_exists('get_mostemailed')) {
	function get_mostemailed($mode = '', $limit = 10) {
		global $wpdb, $post;
		$where = '';
		if($mode == 'post') {
				$where = 'post_status = \'publish\'';
		} elseif($mode == 'page') {
				$where = 'post_status = \'static\'';
		} else {
				$where = '(post_status = \'publish\' OR post_status = \'static\')';
		}
		$mostemailed= $wpdb->get_results("SELECT $wpdb->posts.ID, post_title, post_name, post_date, COUNT($wpdb->email.email_postid) AS 'email_total' FROM $wpdb->email LEFT JOIN $wpdb->posts ON $wpdb->email.email_postid = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND $where AND post_password = '' GROUP BY $wpdb->email.email_postid ORDER  BY email_total DESC LIMIT $limit");
		if($mostemailed) {
			foreach ($mostemailed as $post) {
					$post_title = htmlspecialchars(stripslashes($post->post_title));
					$email_total = intval($post->email_total);
					echo "<li><a href=\"".get_permalink()."\">$post_title</a> - $email_total ".__('Emails')."</li>";
			}
		} else {
			echo '<li>'.__('N/A').'</li>';
		}
	}
}


### Function: Load WP-EMail
add_action('template_redirect', 'wp_email');
function wp_email() {
	if(intval(get_query_var('email')) == 1) {
		include(ABSPATH . '/wp-email.php');
		exit;
	}
}


### Function: Create E-Mail Table
add_action('activate_email/email.php', 'create_email_table');
function create_email_table() {
	global $wpdb;
	include(ABSPATH.'/wp-admin/upgrade-functions.php');
	// Create E-Mail Table
	$create_table = "CREATE TABLE $wpdb->email (".
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
	maybe_create_table($wpdb->email, $create_table);
	// Add In Options (11 Records)
	add_option('email_smtp', '', 'Your SMTP Name, Password, Server');
	add_option('email_contenttype', 'text/html', 'Your E-Mail Type');
	add_option('email_mailer', 'php', 'Your Mailer Type');
	add_option('email_template_subject', 'Recommended Article By %EMAIL_YOUR_NAME%: %EMAIL_POST_TITLE%', 'Template For E-Mail Subject');
	add_option('email_template_body', '<p>Hi <b>%EMAIL_FRIEND_NAME%</b>,<br />Your friend, <b>%EMAIL_YOUR_NAME%</b>, has recommended this article entitled \'<b>%EMAIL_POST_TITLE%</b>\' to you.</p><p><b>Here is his/her remarks:</b><br />%EMAIL_YOUR_REMARKS%</p><p><b>%EMAIL_POST_TITLE%</b><br />Posted By %EMAIL_POST_AUTHOR% On %EMAIL_POST_DATE% In %EMAIL_POST_CATEGORY%</p>%EMAIL_POST_CONTENT%<p>Article taken from %EMAIL_BLOG_NAME% - <a href="%EMAIL_BLOG_URL%">%EMAIL_BLOG_URL%</a><br />URL to article: <a href="%EMAIL_PERMALINK%">%EMAIL_PERMALINK%</a></p>', 'Template For E-Mail Body');
	add_option('email_template_bodyalt', 'Hi %EMAIL_FRIEND_NAME%,'."\n".
	'Your friend, %EMAIL_YOUR_NAME%, has recommended this article entitled \'%EMAIL_POST_TITLE%\' to you.'."\n\n".
	'Here is his/her remarks:'."\n".
	'%EMAIL_YOUR_REMARKS%'."\n\n".
	'%EMAIL_POST_TITLE%'."\n".
	'Posted By %EMAIL_POST_AUTHOR% On %EMAIL_POST_DATE% In %EMAIL_POST_CATEGORY%'."\n".
	'%EMAIL_POST_CONTENT%'."\n".
	'Article taken from %EMAIL_BLOG_NAME% - %EMAIL_BLOG_URL%'."\n".
	'URL to article: %EMAIL_PERMALINK%', 'Template For E-Mail Alternate Body');
	add_option('email_template_sentsuccess', '<p>Article: <b>%EMAIL_POST_TITLE%</b> Has Been Sent To <b>%EMAIL_FRIEND_NAME% (%EMAIL_FRIEND_EMAIL%)</b></p>', 'Template For E-Mail That Is Sent Successfully');
	add_option('email_template_sentfailed', '<p>An Error Has Occured When Trying To Send The E-Mail<br /><b>&raquo;</b> %EMAIL_ERROR_MSG%</p>', 'Template For E-Mail That Failed To Sent');
	add_option('email_template_error', '<p>An Error Has Occured<br /><b>&raquo;</b> %EMAIL_ERROR_MSG%</p>', 'Template For E-Mail That Has An Error');
	add_option('email_interval', 10, 'The Number Of Minutes Before The User Can E-Mail The Next Article');
	add_option('email_snippet', 100, 'Enable Snippet Feature For Your E-Mail?');
	// Set 'manage_email' Capabilities To Administrator	
	$role = get_role('administrator');
	if(!$role->has_cap('manage_email')) {
		$role->add_cap('manage_email');
	}
}
?>