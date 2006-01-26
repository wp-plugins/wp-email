<?php
/*
Plugin Name: WP-EMail
Plugin URI: http://www.lesterchan.net/portfolio/programming.php
Description: Enable You To Send Your Webblog Entry To A Friend.
Version: 2.02
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
		add_menu_page(__('E-Mail'), __('E-Mail'), 'manage_email', 'email-manager.php');
	}
	if (function_exists('add_submenu_page')) {
		add_submenu_page('email-manager.php', __('Manage E-Mail'), __('Manage E-Mail'), 'manage_email', 'email-manager.php');
		add_submenu_page('email-manager.php', __('E-Mail Option'), __('E-Mail Option'),  'manage_email', 'email-options.php');
	}
}


### Function: E-Mail Administration Role
add_action('admin_head', 'email_role');
function email_role() {
	if(function_exists('get_role')) {
		$role = get_role('administrator');
		$role->add_cap('manage_email');
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


### Function: Load WP-EMail
add_action('template_redirect', 'wp_email');
function wp_email() {
	if(intval(get_query_var('email')) == 1) {
		include(ABSPATH . '/wp-email.php');
		exit;
	}
}
?>