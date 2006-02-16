<?php
/*
+----------------------------------------------------------------+
|																							|
|	WordPress 2.0 Plugin: WP-EMail 2.03										|
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


### If wp-email.php Loaded Directly, Load wp-blog-header.php
if(intval($_GET['p']) > 0 || !function_exists('single_post_title')) {
	require(dirname(__FILE__).'/wp-blog-header.php');
}

### Variables Variables Variables
$did_email = get_query_var('wp-email');

### Load E-Mail Get Content Function
add_action('init', 'get_email_content');

### E-Mail Page Title
add_filter('wp_title', 'email_pagetitle');

### Require PHP-Mailer Class
require(ABSPATH.'wp-content/plugins/email/class-phpmailer.php');

### Require WP-EMail Functions
require(ABSPATH.'wp-content/plugins/email/functions-wp-email.php');

### If User Click On Mail
if(!empty($did_email)) {
	// Variables Variables Variables
	$yourname = strip_tags(stripslashes(trim($_POST['yourname'])));
	$youremail = strip_tags(stripslashes(trim($_POST['youremail'])));
	$yourremarks = strip_tags(stripslashes(trim($_POST['yourremarks'])));
	$friendname = strip_tags(stripslashes(trim($_POST['friendname'])));
	$friendemail = strip_tags(stripslashes(trim($_POST['friendemail'])));
	$smtp_info = get_settings('email_smtp');
	$smtp_info = explode('|', $smtp_info);
	$error = '';
	// If Remarks Is Empty, Assign N/A
	if(empty($yourremarks)) { $yourremarks = 'N/A'; }

	// If There Is Post
	if(have_posts()){
		while (have_posts()) {
			the_post();

			// Check Fields For Error
			if(!is_valid_name($yourname) || empty($yourname)) {
				$error .= '<br /><b>&raquo;</b> '.__('Your name is invalid or is empty.');
			}
			if(!is_valid_email($youremail) || empty($youremail)) {
				$error .= '<br /><b>&raquo;</b> '.__('Your email is invalid or is empty.');
			}
			if(!is_valid_remarks($yourremarks)) {
				$error .= '<br /><b>&raquo;</b> '.__('Your remarks is invalid.');
			}
			if(!is_valid_name($friendname) || empty($friendname)) {
				$error .= '<br /><b>&raquo;</b> '.__('Your friend\'s name is invalid or is empty.');
			}
			if(!is_valid_email($friendemail) || empty($friendemail)) {
				$error .= '<br /><b>&raquo;</b> '.__('Your friend\'s email is invalid or is empty.');
			}
			
			// If There Are Errors
			if(!empty($error)) {
				$error = substr($error, 20);
				get_header();
				echo '<div id="content" class="narrowcolumn">'."\n";
				$template_email_error = stripslashes(get_settings('email_template_error'));
				$template_email_error = str_replace("%EMAIL_ERROR_MSG%", $error, $template_email_error);
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
			$post_category_alt = strip_tags($post_category);
			$post_excerpt = get_the_excerpt();
			$post_content .= email_content();
			$post_content_alt = email_content_alt();
						
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
			$template_email_body = str_replace("%EMAIL_POST_EXCERPT%", $post_excerpt, $template_email_body);
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
			$template_email_bodyalt = str_replace("%EMAIL_POST_EXCERPT%", $post_excerpt, $template_email_bodyalt);
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
				$template_email_sentsuccess = str_replace("%EMAIL_FRIEND_EMAIL%", $friendemail, $template_email_sentsuccess);
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
				$template_email_sentfailed = str_replace("%EMAIL_FRIEND_EMAIL%", $friendemail, $template_email_sentfailed);
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
			$email_yourname = addslashes($yourname);
			$email_youremail = addslashes($youremail);
			$email_yourremarks = addslashes($yourremarks);
			$email_friendname = addslashes($friendname);
			$email_friendemail = addslashes($friendemail);
			$email_postid = email_id();
			$email_posttitle = addslashes(email_title());
			$email_timestamp = current_time('timestamp');
			$email_ip = get_email_ipaddress();
			$email_host = gethostbyaddr($email_ip);
			$log_email_sending = email_log("0, '$email_yourname', '$email_youremail', '$email_yourremarks', '$email_friendname', '$email_friendemail', $email_postid, '$email_posttitle', '$email_timestamp', '$email_ip', '$email_host', '$email_status'");
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
		<?php if (not_spamming()): ?>
			<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post(); ?>
					<?php if(not_password_protected()) { ?>
						<?php email_form_header(); ?>		
						<p align="center">
							E-Mail A Copy Of <b><?php the_title(); ?></b> To A Friend.
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
							<input type="submit" value="     Mail It!     " name="wp-email" class="Buttons" />
						</p>
						</form>
					<?php } else { echo get_the_password_form(); } ?>
				<?php endwhile; ?>
			<?php else : ?>
				<p><b>Invalid Post</b></p>
			<?php endif; ?>
		<?php else : ?>
				<p>Please Wait For <b><?php email_flood_interval(); ?> Minutes</b> Before Sending The Next Article.</p>
		<?php endif; ?>
	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>