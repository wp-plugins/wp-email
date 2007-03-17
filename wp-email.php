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
|	- E-Mail Post/Page To A Friend												|
|	- wp-email.php																	|
|																							|
+----------------------------------------------------------------+
*/


### Magic Quotes GPC
if (get_magic_quotes_gpc()) {
   function traverse(&$arr) {
       if(!is_array($arr))
           return;
       foreach($arr as $key => $val)
           is_array($arr[$key]) ? traverse($arr[$key]) : ($arr[$key] = stripslashes($arr[$key]));
   }
   $gpc = array(&$_GET, &$_POST, &$_COOKIE);
   traverse($gpc);
}

### Require WordPress Header
require('wp-blog-header.php');

### Require PHP-Mailer Class
require(ABSPATH.WPINC.'/class-phpmailer.php');

### Function: E-Mail Title
function email_title() {
	$title = get_the_title();
	$title = apply_filters('the_title', $title);
	$title = apply_filters('the_title_rss', $title);
	return $title;
}

### Function: E-Mail Category
function email_category($separator = ', ', $parents='') {
	return get_the_category_list($separator, $parents);
}

### Function: E-Mail Content
function email_content() {
    $content = get_the_content($more_link_text, $stripteaser, $more_file);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}

### Function: E-Mail Alternate Content
function email_content_alt() {
	$content = get_the_content($more_link_text, $stripteaser, $more_file);
	$content = apply_filters('the_content', $content);
	$content = clean_pre($content);
	$content = strip_tags($content);
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

### If User Click On Mail
if(!empty($_POST['mail'])) {
	// Variables Variables Variables
	$yourname = strip_tags(stripslashes(trim($_POST['yourname'])));
	$youremail = strip_tags(stripslashes(trim($_POST['youremail'])));
	$yourremarks = strip_tags(stripslashes(trim($_POST['yourremarks'])));
	$friendname = strip_tags(stripslashes(trim($_POST['friendname'])));
	$friendemail = strip_tags(stripslashes(trim($_POST['friendemail'])));
	$smtp_info = get_settings('email_smtp');
	$smtp_info = explode('|', $smtp_info);
	
	// If Remarks Is Empty, Assign N/A
	if(empty($yourremarks)) { $yourremarks = 'N/A'; }

	// If There Is Post
	if(have_posts()){
		while (have_posts()) {
			the_post();

			// Mail Failed Because Of Empty Fields
			if(empty($yourname) || empty($youremail) || empty($friendname) || empty($friendemail)) {
				get_header();
				echo '<div id="content" class="narrowcolumn">'."\n";
				$template_email_error = stripslashes(get_settings('email_template_error'));
				$template_email_error = str_replace("%EMAIL_ERROR_MSG%", 'Some Fields Are Not Filled Up', $template_email_error);
				$template_email_error = str_replace("%EMAIL_BLOG_NAME%", get_bloginfo('name'), $template_email_error);
				$template_email_error = str_replace("%EMAIL_BLOG_URL%", get_bloginfo('url'), $template_email_error);
				$template_email_error = str_replace("%EMAIL_PERMALINK%", get_permalink(), $template_email_error);
				echo $template_email_error;
				echo '</div>'."\n";
				get_sidebar();
				get_footer();
				exit();
			}

			// Variables Variables Variables
			$post_title = email_title();
			$post_author = the_author('', false);
			$post_date = the_date('jS F Y @ H:i', '', '', false);
			$post_category = email_category();
			$post_content = '';
			for($page = 1; $page <= $numpages; $page++) {
				$post_content .= email_content();
				$post_content_alt = email_content_alt();
			} 
			$post_category_alt = strip_tags($post_category);
			
			// Template For E-Mail Subject
			$template_email_subject = stripslashes(get_settings('email_template_subject'));
			$template_email_subject = str_replace("%EMAIL_YOUR_NAME%", $yourname, $template_email_subject);
			$template_email_subject = str_replace("%EMAIL_YOUR_EMAIL%", $youremail, $template_email_subject);
			$template_email_subject = str_replace("%EMAIL_POST_TITLE%", $post_title, $template_email_subject);
			$template_email_subject = str_replace("%EMAIL_POST_AUTHOR%", $post_author, $template_email_subject);
			$template_email_subject = str_replace("%EMAIL_POST_DATE%", $post_date, $template_email_subject);
			$template_email_subject = str_replace("%EMAIL_POST_CATEGORY%", $post_category_alt, $template_email_subject);
			$template_email_subject = str_replace("%EMAIL_BLOG_NAME%", get_bloginfo('name'), $template_email_subject);
			$template_email_subject = str_replace("%EMAIL_BLOG_URL%", get_bloginfo('url'), $template_email_subject);
			$template_email_subject = str_replace("%EMAIL_PERMALINK%", get_permalink(), $template_email_subject);

			// Template For E-Mail Body
			$template_email_body = stripslashes(get_settings('email_template_body'));
			$template_email_body = str_replace("%EMAIL_YOUR_NAME%", $yourname, $template_email_body);
			$template_email_body = str_replace("%EMAIL_YOUR_EMAIL%", $youremail, $template_email_body);
			$template_email_body = str_replace("%EMAIL_YOUR_REMARKS%", $yourremarks, $template_email_body);
			$template_email_body = str_replace("%EMAIL_FRIEND_NAME%", $friendname, $template_email_body);
			$template_email_body = str_replace("%EMAIL_FRIEND_EMAIL%", $friendemail, $template_email_body);
			$template_email_body = str_replace("%EMAIL_POST_TITLE%", $post_title, $template_email_body);
			$template_email_body = str_replace("%EMAIL_POST_AUTHOR%", $post_author, $template_email_body);
			$template_email_body = str_replace("%EMAIL_POST_DATE%", $post_date, $template_email_body);
			$template_email_body = str_replace("%EMAIL_POST_CATEGORY%", $post_category, $template_email_body);
			$template_email_body = str_replace("%EMAIL_POST_CONTENT%", $post_content, $template_email_body);
			$template_email_body = str_replace("%EMAIL_BLOG_NAME%", get_bloginfo('name'), $template_email_body);
			$template_email_body = str_replace("%EMAIL_BLOG_URL%", get_bloginfo('url'), $template_email_body);
			$template_email_body = str_replace("%EMAIL_PERMALINK%", get_permalink(), $template_email_body);

			// Template For E-Mail Alternate Body
			$template_email_bodyalt = stripslashes(get_settings('email_template_bodyalt'));
			$template_email_bodyalt = str_replace("%EMAIL_YOUR_NAME%", $yourname, $template_email_bodyalt);
			$template_email_bodyalt = str_replace("%EMAIL_YOUR_EMAIL%", $youremail, $template_email_bodyalt);
			$template_email_bodyalt = str_replace("%EMAIL_YOUR_REMARKS%", $yourremarks, $template_email_bodyalt);
			$template_email_bodyalt = str_replace("%EMAIL_FRIEND_NAME%", $friendname, $template_email_bodyalt);
			$template_email_bodyalt = str_replace("%EMAIL_FRIEND_EMAIL%", $friendemail, $template_email_bodyalt);
			$template_email_bodyalt = str_replace("%EMAIL_POST_TITLE%", $post_title, $template_email_bodyalt);
			$template_email_bodyalt = str_replace("%EMAIL_POST_AUTHOR%", $post_author, $template_email_bodyalt);
			$template_email_bodyalt = str_replace("%EMAIL_POST_DATE%", $post_date, $template_email_bodyalt);
			$template_email_bodyalt = str_replace("%EMAIL_POST_CATEGORY%", $post_category_alt, $template_email_bodyalt);
			$template_email_bodyalt = str_replace("%EMAIL_POST_CONTENT%", $post_content_alt, $template_email_bodyalt);
			$template_email_bodyalt = str_replace("%EMAIL_BLOG_NAME%", get_bloginfo('name'), $template_email_bodyalt);
			$template_email_bodyalt = str_replace("%EMAIL_BLOG_URL%", get_bloginfo('url'), $template_email_bodyalt);
			$template_email_bodyalt = str_replace("%EMAIL_PERMALINK%", get_permalink(), $template_email_bodyalt);

			// PHP Mailer Variables
			$mail = new PHPMailer();
			$mail->From     = $youremail;
			$mail->FromName = $yourname;
			$mail->AddAddress($friendemail, $friendname);
			$mail->Username = $smtp_info[0]; 
			$mail->Password = $smtp_info[1];
			$mail->Host     = $smtp_info[2];
			$mail->Mailer   = get_settings('email_mailer');
			$mail->ContentType =  get_settings('email_contenttype');
			$mail->Subject = $template_email_subject;
			if(get_settings('email_contenttype') == 'text/plain') {
				$mail->Body    = $template_email_bodyalt;
			} else {
				$mail->Body    = $template_email_body;
				$mail->AltBody = $template_email_bodyalt;
			}
	
			// Send The Mail
			if($mail->Send()) {
				$email_status = __('Success');
				get_header();
				// Template For Sent Successfully
				echo '<div id="content" class="narrowcolumn">'."\n";
				$template_email_sentsuccess = stripslashes(get_settings('email_template_sentsuccess'));
				$template_email_sentsuccess = str_replace("%EMAIL_FRIEND_NAME%", $friendname, $template_email_sentsuccess);
				$template_email_sentsuccess = str_replace("%EMAIL_FRIEND_EMAIL%", $yourname, $template_email_sentsuccess);
				$template_email_sentsuccess = str_replace("%EMAIL_POST_TITLE%", $post_title, $template_email_sentsuccess);
				$template_email_sentsuccess = str_replace("%EMAIL_BLOG_NAME%", get_bloginfo('name'), $template_email_sentsuccess);
				$template_email_sentsuccess = str_replace("%EMAIL_BLOG_URL%", get_bloginfo('url'), $template_email_sentsuccess);
				$template_email_sentsuccess = str_replace("%EMAIL_PERMALINK%", get_permalink(), $template_email_sentsuccess);
				echo $template_email_sentsuccess;
				echo '</div>'."\n";
				get_sidebar();
				get_footer();

			// If There Is Error Sending
			} else {
				$email_status = __('Failed');
				get_header();
				// Template For Sent Failed
				echo '<div id="content" class="narrowcolumn">'."\n";
				$template_email_sentfailed = stripslashes(get_settings('email_template_sentfailed'));
				$template_email_sentfailed = str_replace("%EMAIL_FRIEND_NAME%", $friendname, $template_email_sentfailed);
				$template_email_sentfailed = str_replace("%EMAIL_FRIEND_EMAIL%", $yourname, $template_email_sentfailed);
				$template_email_sentfailed = str_replace("%EMAIL_ERROR_MSG%", $mail->ErrorInfo, $template_email_sentfailed);
				$template_email_sentfailed = str_replace("%EMAIL_POST_TITLE%", $post_title, $template_email_sentfailed);
				$template_email_sentfailed = str_replace("%EMAIL_BLOG_NAME%", get_bloginfo('name'), $template_email_sentfailed);
				$template_email_sentfailed = str_replace("%EMAIL_BLOG_URL%", get_bloginfo('url'), $template_email_sentfailed);
				$template_email_sentfailed = str_replace("%EMAIL_PERMALINK%", get_permalink(), $template_email_sentfailed);
				echo $template_email_sentfailed;
				echo '</div>'."\n";
				get_sidebar();
				get_footer();
			}

			// Logging Email Variables
			$email_yourname = addslashes($yourname);
			$email_youremail = addslashes($youremail);
			$email_yourremarks = addslashes($yourremarks);
			$email_friendname = addslashes($friendname);
			$email_friendemail = addslashes($friendemail);
			$email_postid = intval($id);
			$email_posttitle = addslashes($post_title);
			$email_timestamp = current_time('timestamp');
			$email_ip = get_email_ipaddress();
			$email_host = gethostbyaddr(get_email_ipaddress());
			$log_email_sending = $wpdb->query("INSERT INTO $wpdb->email VALUES(0, '$email_yourname', '$email_youremail', '$email_yourremarks', '$email_friendname', '$email_friendemail', $email_postid, '$email_posttitle', '$email_timestamp', '$email_ip', '$email_host', '$email_status')");
			if(!$log_email_sending) {
				die('Error Logging E-Mail Sending');
			}
			exit();
		}
	}
}
?>
<?php get_header(); ?>
	<div id="content" class="narrowcolumn">
		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					 <?php if(is_page()) : ?>
						  <input type="hidden" name="page_id" value="<?php echo the_ID(); ?>" />
					 <?php else : ?>
						<input type="hidden" name="p" value="<?php echo the_ID(); ?>" />
					 <?php endif; ?>
					<p align="center">
						Article: <b><?php the_title(); ?></b> Will Be Send To Your Friend.
					</p>
					<p><b>* Required</b></p>
					<p>
						<b>Your Name: *</b><br />
						<input type="text" size="50" maxlength="50" name="yourname" class="Forms" />
					</p>
					<p>
						<b>Your E-Mail: *</b><br />
						<input type="text" size="50" maxlength="100" name="youremail" class="Forms" />
					</p>
					<p>
						<b>Your Remarks:</b><br />
						<textarea cols="49" rows="8" name="yourremarks" class="Forms"></textarea>
					</p>
					<p>
						<b>Friend's Name: *</b><br />
						<input type="text" size="50" maxlength="100" name="friendname" class="Forms" />
					</p>
					<p>
						<b>Friend's E-Mail: *</b><br />
						<input type="text" size="50" maxlength="100" name="friendemail" class="Forms" />
					</p>
					<p align="center">
						<input type="submit" value="     Mail It!     " name="mail" class="Buttons" />
					</p>
				</form>
			<?php endwhile; ?>
		<?php else : ?>
			<p><b>Invalid Post</b></p>
		<?php endif; ?>
	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>