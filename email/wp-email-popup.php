<?php
/*
+----------------------------------------------------------------+
|																							|
|	WordPress 2.0 Plugin: WP-EMail 2.07										|
|	Copyright (c) 2005 Lester "GaMerZ" Chan									|
|																							|
|	File Written By:																	|
|	- Lester "GaMerZ" Chan															|
|	- http://www.lesterchan.net													|
|																							|
|	File Information:																	|
|	- E-Mail Post/Page To A Friend (Popup Window)							|
|	- wp-email-popup.php															|
|																							|
+----------------------------------------------------------------+
*/


### Session Start
session_start();

### Image Verification
$email_image_verify = intval(get_settings('email_imageverify'));

### Variables
$did_email = get_query_var('wp-email');

### Actions
add_action('init', 'get_email_content');

### Filters
add_filter('wp_title', 'email_pagetitle');

### Require PHP-Mailer Class
require(ABSPATH.'wp-content/plugins/email/class-phpmailer.php');

### Form Variables
$yourname = strip_tags(stripslashes(trim($_POST['yourname'])));
$youremail = strip_tags(stripslashes(trim($_POST['youremail'])));
$yourremarks = strip_tags(stripslashes(trim($_POST['yourremarks'])));
$friendname = strip_tags(stripslashes(trim($_POST['friendname'])));
$friendemail = strip_tags(stripslashes(trim($_POST['friendemail'])));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	<meta name="robots" content="noindex, nofollow" />
	<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<style type="text/css" media="screen">
		P {
			margin-left: 10px;
			text-align: left;
		}
	</style>
	<?php wp_head(); ?>
</head>
<body>
<?php
### If User Click On Mail
if(!empty($did_email)) {
	// Variables Variables Variables
	$imageverify = $_POST['imageverify'];
	$smtp_info = get_settings('email_smtp');
	$smtp_info = explode('|', $smtp_info);
	$error = '';

	// If There Is Post
	if(have_posts()){
		while (have_posts()) {
			the_post();

			// Multiple Names/Emails
			$friends = array();
			$multiple_names = explode(',', $friendname);
			$multiple_emails = explode(',', $friendemail);
			$multiple_max = intval(get_settings('email_multiple'));
			if($multiple_max == 0) { $multiple_max = 1; }

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
			if($multiple_names) {
				$i = 0;
				foreach($multiple_names as $multiple_name) {
					$multiple_name = trim($multiple_name);
					if(!is_valid_name($multiple_name) || empty($multiple_name)) {
						$error .= '<br /><b>&raquo;</b> '.__('Friend\'s name ('.$multiple_name.') is invalid or is empty.');
					} else {
						$friends[$i]['name'] = $multiple_name;
						$i++;
					}
					if($i > $multiple_max) {
						break;
					}
				}
			}
			if($multiple_emails) {
				$i = 0;
				foreach($multiple_emails as $multiple_email) {
					$multiple_email = trim($multiple_email);
					if(!is_valid_email($multiple_email) || empty($multiple_email)) {
						$error .= '<br /><b>&raquo;</b> '.__('Friend\'s email ('.$multiple_email.') is invalid or is empty.');
					} else {
						$friends[$i]['email'] = $multiple_email;
						$i++;
					}
					if($i > $multiple_max) {
						break;
					}
				}
			}
			if(sizeof($friends) > $multiple_max) {
				$error .= '<br /><b>&raquo;</b> '.__('Maximum '.$multiple_max.' entries allowed');
			}

			// Check Whether We Enable Image Verification
			if($email_image_verify) {
				$imageverify = strtoupper($imageverify);
				if(empty($imageverify)) {
					$error .= '<br /><b>&raquo;</b> '.__('Image verification is empty.');
				} else {
					if($_SESSION['email_verify'] != md5($imageverify)) {
						$error .= '<br /><b>&raquo;</b> '.__('Image verification failed.');
					}
				}
			}
			
			// If There Is No Error, We Process The E-Mail
			if(empty($error)) {
				// If Remarks Is Empty, Assign N/A
				if(empty($yourremarks)) { $yourremarks = 'N/A'; }

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
				foreach($friends as $friend) {
					$mail->AddAddress($friend['email'], $friend['name']);
				}
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
					// Template For Sent Successfully
					$template_email_sentsuccess = stripslashes(get_settings('email_template_sentsuccess'));
					$template_email_sentsuccess = str_replace("%EMAIL_FRIEND_NAME%", $friendname, $template_email_sentsuccess);
					$template_email_sentsuccess = str_replace("%EMAIL_FRIEND_EMAIL%", $friendemail, $template_email_sentsuccess);
					$template_email_sentsuccess = str_replace("%EMAIL_POST_TITLE%", $post_title, $template_email_sentsuccess);
					$template_email_sentsuccess = str_replace("%EMAIL_BLOG_NAME%", get_bloginfo('name'), $template_email_sentsuccess);
					$template_email_sentsuccess = str_replace("%EMAIL_BLOG_URL%", get_bloginfo('url'), $template_email_sentsuccess);
					$template_email_sentsuccess = str_replace("%EMAIL_PERMALINK%", get_permalink(), $template_email_sentsuccess);
					echo $template_email_sentsuccess;

				// If There Is Error Sending
				} else {
					if($yourremarks == 'N/A') { $yourremarks = ''; }
					$email_status = __('Failed');
					// Template For Sent Failed
					$template_email_sentfailed = stripslashes(get_settings('email_template_sentfailed'));
					$template_email_sentfailed = str_replace("%EMAIL_FRIEND_NAME%", $friendname, $template_email_sentfailed);
					$template_email_sentfailed = str_replace("%EMAIL_FRIEND_EMAIL%", $friendemail, $template_email_sentfailed);
					$template_email_sentfailed = str_replace("%EMAIL_ERROR_MSG%", $mail->ErrorInfo, $template_email_sentfailed);
					$template_email_sentfailed = str_replace("%EMAIL_POST_TITLE%", $post_title, $template_email_sentfailed);
					$template_email_sentfailed = str_replace("%EMAIL_BLOG_NAME%", get_bloginfo('name'), $template_email_sentfailed);
					$template_email_sentfailed = str_replace("%EMAIL_BLOG_URL%", get_bloginfo('url'), $template_email_sentfailed);
					$template_email_sentfailed = str_replace("%EMAIL_PERMALINK%", get_permalink(), $template_email_sentfailed);
				}

				// Logging
				$email_yourname = addslashes($yourname);
				$email_youremail = addslashes($youremail);
				$email_yourremarks = addslashes($yourremarks);
				$email_postid = email_id();
				$email_posttitle = addslashes($post_title);
				$email_timestamp = current_time('timestamp');
				$email_ip = get_email_ipaddress();
				$email_host = gethostbyaddr($email_ip);
				foreach($friends as $friend) {
					$email_friendname = addslashes($friend['name']);
					$email_friendemail = addslashes($friend['email']);
					$log_email_sending = email_log("0, '$email_yourname', '$email_youremail', '$email_yourremarks', '$email_friendname', '$email_friendemail', $email_postid, '$email_posttitle', '$email_timestamp', '$email_ip', '$email_host', '$email_status'");
					if(!$log_email_sending) {
						die('Error Logging E-Mail Sending');
					}
				}

			// If There Are Errors
			} else {			
				$error = substr($error, 20);
				$template_email_error = stripslashes(get_settings('email_template_error'));
				$template_email_error = str_replace("%EMAIL_ERROR_MSG%", $error, $template_email_error);
				$template_email_error = str_replace("%EMAIL_BLOG_NAME%", get_bloginfo('name'), $template_email_error);
				$template_email_error = str_replace("%EMAIL_BLOG_URL%", get_bloginfo('url'), $template_email_error);
				$template_email_error = str_replace("%EMAIL_PERMALINK%", get_permalink(), $template_email_error);				
			} // End if(empty($error))
		} // End while (have_posts())
	} // End if(have_posts())
} // End if(!empty($did_email))
?>
<?php if(empty($email_status) || $email_status == __('Failed')): ?>
	<?php if (not_spamming()): ?>
		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
				<?php if(not_password_protected()) { ?>
					<?php email_popup_form_header(); ?>
					<p style="text-align: center">
						E-Mail A Copy Of <b><?php the_title(); ?></b> To A Friend.
					</p>
					<!-- Display Error, If There Is Any -->
					<?php echo $template_email_sentfailed; ?>
					<?php echo $template_email_error; ?>
					<!-- End Display Error, If There Is Any -->
					<p><b>* Required Field</b></p>
					<p>
						<b><label for="yourname">Your Name: *</label></b><br />
						<input type="text" size="50" id="yourname" name="yourname" class="Forms" value="<?php echo $yourname; ?>" />
					</p>
					<p>
						<b><label for="youremail">Your E-Mail: *</label></b><br />
						<input type="text" size="50" id="youremail" name="youremail" class="Forms" value="<?php echo $youremail; ?>" />
					</p>
					<p>
						<b><label for="yourremarks">Your Remarks:</label></b><br />
						<textarea cols="49" rows="8" id="yourremarks" name="yourremarks" class="Forms"><?php echo $yourremarks; ?></textarea>
					</p>
					<p>
						<b><label for="friendname">Friend's Name: *</label></b><br />
						<input type="text" size="50" id="friendname" name="friendname" class="Forms" value="<?php echo $friendname; ?>" /><?php email_multiple(); ?>
					</p>
					<p>
						<b><label for="friendemail">Friend's E-Mail: *</label></b><br />
						<input type="text" size="50" id="friendemail" name="friendemail" class="Forms" value="<?php echo $friendemail; ?>" /><?php email_multiple(); ?>
					</p>
					<?php if($email_image_verify): ?>
						<p>
							<b><label for="imageverify">Image Verification: *</label></b><br />
							<img src="<?php echo get_settings('siteurl'); ?>/wp-content/plugins/email/email-image-verify.php" width="60" height="22" alt="Image Verification" /><input type="text" size="5" maxlength="5" id="imageverify" name="imageverify" class="Forms" />
						</p>
					<?php endif; ?>
					<p style="text-align: center">
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
<?php endif; ?>
	<p style="text-align: center; padding-top: 20px;"><a href="#" onclick="window.close();">Close This Window</a></p>
	<?php wp_footer(); ?>
</body>
</html>