<?php
/*
+----------------------------------------------------------------+
|																							|
|	WordPress 2.0 Plugin: WP-EMail 2.02										|
|	Copyright (c) 2005 Lester "GaMerZ" Chan									|
|																							|
|	File Written By:																	|
|	- Lester "GaMerZ" Chan															|
|	- http://www.lesterchan.net													|
|																							|
|	File Information:																	|
|	- WP-EMail Functions															|
|	- wp-includes/functions-wp-email.php										|
|																							|
+----------------------------------------------------------------+
*/


### Function: Snippet Text
function snippet_text($text, $length = 0) {
	$words = preg_split('/\s+/', ltrim($text), $length + 1);
	if(count($words) > $length) {
		return rtrim(substr($text, 0, strlen($text) - strlen(end($words)))).' ...';
	} else {
		return $text;
	}
}

### Function: E-Mail ID
function email_id() {
	global $id;
	return $id;
}

### Function: E-Mail Title
function email_title() {
	$title = get_the_title();
	return $title;
}

### Function: E-Mail Page Title
function email_pagetitle($page_title) {
	$page_title = '&raquo; E-Mail'.$page_title;
	return $page_title;
}

### Function: E-Mail Category
function email_category($separator = ', ', $parents='') {
	return get_the_category_list($separator, $parents);
}

### Function: E-Mail Content
function email_content() {
	$content = get_email_content();
	$content = str_replace(']]>', ']]&gt;', $content);
	$email_snippet = intval(get_settings('email_snippet'));
	if($email_snippet > 0) {
		return snippet_text($content , $email_snippet);
	} else {
		return $content;
	}
}

### Function: E-Mail Alternate Content
function email_content_alt() {
	$content = get_email_content();
	$content = clean_pre($content);
	$content = strip_tags($content);
	$email_snippet = intval(get_settings('email_snippet'));
	if($email_snippet > 0) {
		return snippet_text($content , $email_snippet);
	} else {
		return $content;
	}
}

### Function: E-Mail Get The Content
function get_email_content() {
	global $pages, $multipage, $numpages, $post;
	if (!empty($post->post_password)) {
		if (stripslashes($_COOKIE['wp-postpass_'.COOKIEHASH]) != $post->post_password) {
			return __('Password Protected Post');
		}
	}
	if($multipage) {
		for($page = 0; $page < $numpages; $page++) {
			$content .= $pages[$page];
		}
	} else {
		$content = $pages[0];
	}
	$content = wptexturize($content);
	$content = convert_smilies($content);
	$content = wpautop($content);
	return $content;
}

### Function: Get IP Address
function get_email_ipaddress() {
	if (empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		$ip_address = $_SERVER["REMOTE_ADDR"];
	} else {
		$ip_address = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	if(strpos($ip_address, ',') !== false) {
		$ip_address = explode(',', $ip_address);
		$ip_address = $ip_address[0];
	}
	return $ip_address;
}

### Function: Check For Password Protected Post
function not_password_protected() {
	global $post;
	if (!empty($post->post_password)) {
		if ($_COOKIE['wp-postpass_'.COOKIEHASH] != $post->post_password) {
			return false;
		}
	}
	return true;
}

### Function: Check Vaild Name (AlphaNumeric With Spaces Allowed Only)
function is_valid_name($name) {
	if(ereg('[^[:space:]a-zA-Z0-9]', $name)) {
		return false;
	} else {
		return true;
	}
}

### Function: Check Valid E-Mail Address
function is_valid_email($email) {
   $regex = '/^[A-z0-9][\w.-]*@[A-z0-9][\w\-\.]+\.[A-z0-9]{2,6}$/';
   return (preg_match($regex, $email));
}

### Function: Check Valid Remarks (Ensure No E-Mail Injections)
function is_valid_remarks($content) { 
	$injection_strings = array('apparently-to', 'bcc', 'boundary', 'charset', 'content-disposition', 'content-type', 'content-transfer-encoding', 'errors-to', 'in-reply-to', 'message-id', 'mime-version', 'multipart/mixed', 'multipart/alternative', 'multipart/related', 'reply-to', 'x-mailer', 'x-sender', 'x-uidl'); 
	foreach ($injection_strings as $spam) { 
		$check = strpos(strtolower($content), $spam); 
		if ($check !== false) {
			return false;
		}
	}
	return true;
}

### Function: Check For E-Mail Spamming
function not_spamming() {
	global $wpdb;
	$current_time = current_time('timestamp');
	$email_ip = get_email_ipaddress();
	$email_host = gethostbyaddr($email_ip);
	$last_emailed = $wpdb->get_var("SELECT email_timestamp FROM $wpdb->email WHERE email_ip = '$email_ip' AND email_host = '$email_host' ORDER BY email_timestamp DESC LIMIT 1");
	$email_allow_interval = intval(get_settings('email_interval'))*60;
	if(($current_time-$last_emailed) < $email_allow_interval) {
		return false;
	} else {
		return true;
	}
}

### Function: E-Mail Flood Interval
function email_flood_interval($echo = true) {
	$email_allow_interval_min = intval(get_settings('email_interval'));
	if($echo) {
		echo $email_allow_interval_min;
	} else {
		return $email_allow_interval_min;
	}
}

### Function: E-Mail Form Header
function email_form_header() {
	global $id;
	$using_permalink = get_settings('permalink_structure');
	$permalink = get_permalink();
	if(!empty($using_permalink)) {
		if(is_page()) {
			echo '<form action="'.$permalink.'emailpage/" method="post">'."\n";
			echo '<input type="hidden" name="page_id" value="'.$id.'" />'."\n";
		} else {
			echo '<form action="'.$permalink.'email/" method="post">'."\n";
			echo '<input type="hidden" name="p" value="'.$id.'" />'."\n";
		}
	} else {
		if(is_page()) {
			echo '<form action="'.get_settings('home').'/wp-email.php?page_id='.$id.'" method="post">'."\n";
			echo '<input type="hidden" name="page_id" value="'.$id.'" />'."\n";
		} else {
			echo '<form action="'.get_settings('home').'/wp-email.php?p='.$id.'" method="post">'."\n";
			echo '<input type="hidden" name="p" value="'.$id.'" />'."\n";
		}
	}
}

### Function: Log E-Mail
function email_log($email_query = '') {
	global $wpdb;
	$log_email_sending = $wpdb->query("INSERT INTO $wpdb->email VALUES(".$email_query.')');
	return $log_email_sending;
}
?>