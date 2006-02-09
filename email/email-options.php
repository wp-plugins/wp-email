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
|	- Configure E-Mail Options														|
|	- wp-content/plugins/email/email-options.php							|
|																							|
+----------------------------------------------------------------+
*/


### Check Whether User Can Manage EMail
if(!current_user_can('manage_email')) {
	die('Access Denied');
}


### E-Mail Variables
$base_name = plugin_basename('email/email-options.php');
$base_page = 'admin.php?page='.$base_name;
$id = intval($_GET['id']);


### If Form Is Submitted
if($_POST['Submit']) {
	$email_smtp_name = strip_tags(trim($_POST['email_smtp_name']));
	$email_smtp_password = strip_tags(trim($_POST['email_smtp_password']));
	$email_smtp_server = strip_tags(trim($_POST['email_smtp_server']));
	$email_smtp = "$email_smtp_name|$email_smtp_password|$email_smtp_server";
	$email_contenttype = strip_tags(trim($_POST['email_contenttype']));
	$email_mailer = strip_tags(trim($_POST['email_mailer']));
	$email_snippet = intval(trim($_POST['email_snippet']));
	$email_interval = intval(trim($_POST['email_interval']));
	$email_template_subject = strip_tags(trim($_POST['email_template_subject']));
	$email_template_body = trim($_POST['email_template_body']);
	$email_template_bodyalt = trim($_POST['email_template_bodyalt']);
	$email_template_sentsuccess = trim($_POST['email_template_sentsuccess']);
	$email_template_sentfailed = trim($_POST['email_template_sentfailed']);
	$email_template_error = trim($_POST['email_template_error']);
	$update_email_queries = array();
	$update_email_text = array();
	$update_email_queries[] = update_option('email_smtp', $email_smtp);
	$update_email_queries[] = update_option('email_contenttype', $email_contenttype);
	$update_email_queries[] = update_option('email_mailer', $email_mailer);
	$update_email_queries[] = update_option('email_snippet', $email_snippet);
	$update_email_queries[] = update_option('email_interval', $email_interval);
	$update_email_queries[] = update_option('email_template_subject', $email_template_subject);
	$update_email_queries[] = update_option('email_template_body', $email_template_body);
	$update_email_queries[] = update_option('email_template_bodyalt', $email_template_bodyalt);
	$update_email_queries[] = update_option('email_template_sentsuccess', $email_template_sentsuccess);
	$update_email_queries[] = update_option('email_template_sentfailed', $email_template_sentfailed);
	$update_email_queries[] = update_option('email_template_error', $email_template_error);
	$update_email_text[] = __('SMTP Information');
	$update_email_text[] = __('E-Mail Content Type');
	$update_email_text[] = __('Send E-Mail Method');
	$update_email_text[] = __('Snippet Option');
	$update_email_text[] = __('Interval Option');
	$update_email_text[] = __('Subject Template');
	$update_email_text[] = __('Body Template');
	$update_email_text[] = __('Alternate Body Template');
	$update_email_text[] = __('Sent Success Template');
	$update_email_text[] = __('Sent Failed Template');
	$update_email_text[] = __('Error Template');
	$i=0;
	$text = '';
	foreach($update_email_queries as $update_email_query) {
		if($update_email_query) {
			$text .= '<font color="green">'.$update_email_text[$i].' '.__('Updated').'</font><br />';
		}
		$i++;
	}
	if(empty($text)) {
		$text = '<font color="red">'.__('No E-Mail Option Updated').'</font>';
	}
}

?>
<script language="JavaScript" type="text/javascript">
function email_default_templates(template) {
	var default_template;
	switch(template) {
		case "subject":
			default_template = "Recommended Article By %EMAIL_YOUR_NAME%: %EMAIL_POST_TITLE%";
			break;
		case "body":
			default_template = "<p>Hi <b>%EMAIL_FRIEND_NAME%</b>,<br />Your friend, <b>%EMAIL_YOUR_NAME%</b>, has recommended this article entitled '<b>%EMAIL_POST_TITLE%</b>' to you.</p><p><b>Here is his/her remarks:</b><br />%EMAIL_YOUR_REMARKS%</p><p><b>%EMAIL_POST_TITLE%</b><br />Posted By %EMAIL_POST_AUTHOR% On %EMAIL_POST_DATE% In %EMAIL_POST_CATEGORY%</p>%EMAIL_POST_CONTENT%<p>Article taken from %EMAIL_BLOG_NAME% - <a href=\"%EMAIL_BLOG_URL%\">%EMAIL_BLOG_URL%</a><br />URL to article: <a href=\"%EMAIL_PERMALINK%\">%EMAIL_PERMALINK%</a></p>";
			break;
		case "bodyalt":
			default_template = "Hi %EMAIL_FRIEND_NAME%,\nYour friend, %EMAIL_YOUR_NAME%, has recommended this article entitled '%EMAIL_POST_TITLE%' to you.\n\nHere is his/her remarks:\n%EMAIL_YOUR_REMARKS%\n\n%EMAIL_POST_TITLE%\nPosted By %EMAIL_POST_AUTHOR% On %EMAIL_POST_DATE% In %EMAIL_POST_CATEGORY%\n%EMAIL_POST_CONTENT%\nArticle taken from %EMAIL_BLOG_NAME% - %EMAIL_BLOG_URL%\nURL to article: %EMAIL_PERMALINK%";
			break;
		case "sentsuccess":
			default_template = "<p>Article: <b>%EMAIL_POST_TITLE%</b> Has Been Sent To <b>%EMAIL_FRIEND_NAME% (%EMAIL_FRIEND_EMAIL%)</b></p>";
			break;
		case "sentfailed":
			default_template = "<p>An Error Has Occured When Trying To Send The E-Mail<br /><b>&raquo;</b> %EMAIL_ERROR_MSG%</p>";
			break;
		case "error":
			default_template = "<p>An Error Has Occured<br /><b>&raquo;</b> %EMAIL_ERROR_MSG%</p>";
			break;
	}
	document.getElementById("email_template_" + template).value = default_template;
}

</script>
<?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>
<div class="wrap"> 
	<h2><?php echo $title; ?></h2> 
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
		<fieldset class="options">
			<legend><?php _e('SMTP Settings'); ?></legend>
			<?php
				$smtp_info = get_settings('email_smtp');
				$smtp_info = explode('|', $smtp_info);
			?>
			<table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
				 <tr valign="top">
					<th scope="row" width="40%"><?php _e('SMTP Username:'); ?></th>
					 <td><input type="text" name="email_smtp_name" value="<?php echo  $smtp_info[0]; ?>" size="30" /></td>
				</tr>
				<tr valign="top">
					<th scope="row" width="40%"><?php _e('SMTP Password:'); ?></th>
					 <td><input type="text" name="email_smtp_password" value="<?php echo  $smtp_info[1]; ?>" size="30" /></td>
				</tr>
				<tr valign="top">
					<th scope="row" width="40%"><?php _e('SMTP Server:'); ?></th>
					 <td><input type="text" name="email_smtp_server" value="<?php echo  $smtp_info[2]; ?>" size="30" /><br />You may leave the above fields blank if you do not use a SMTP server.</td>
				</tr>
			</table>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('E-Mail Settings'); ?></legend>
			<table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
				 <tr valign="top">
					<th scope="row" width="40%"><?php _e('E-Mail Content Type:'); ?></th>
					 <td>
						<select name="email_contenttype" size="1">
							<option value="text/plain"<?php selected('text/plain', get_settings('email_contenttype')); ?>><?php _e('Plain Text'); ?></option>
							<option value="text/html"<?php selected('text/html', get_settings('email_contenttype')); ?>><?php _e('HTML'); ?></option>
						</select>
					</td>
				</tr>
				<tr valign="top"> 
					<th scope="row" width="40%"><?php _e('Method Used To Send E-Mail'); ?></th>
					<td>
						<select name="email_mailer" size="1">
							<option value="php"<?php selected('php', get_settings('email_mailer')); ?>><?php _e('PHP'); ?></option>
							<option value="sendmail"<?php selected('sendmail', get_settings('email_mailer')); ?>><?php _e('SendMail'); ?></option>
							<option value="smtp"<?php selected('smtp', get_settings('email_mailer')); ?>><?php _e('SMTP'); ?></option>
						</select>
						<br />If you ARE NOT using a smtp server of if there is a problem sending out email using your smtp server. Please Choose PHP or Send Mail.
					</td> 
				</tr>
				<tr valign="top"> 
					<th scope="row" width="40%"><?php _e('No. Of Words Before Cutting Off'); ?></th>
					<td><input type="text" id="email_snippet" name="email_snippet" value="<?php echo  get_settings('email_snippet'); ?>" size="5" maxlength="5"><br />Setting this value more than 0 will enable the snippet feature. This feature will allow you to send a portion (defined by the text field above) of the article to your friend instead of the whole article.</td> 
				</tr>
				<tr valign="top"> 
					<th scope="row" width="40%"><?php _e('Interval Between E-Mails'); ?></th>
					<td><input type="text" id="email_interval" name="email_interval" value="<?php echo  get_settings('email_interval'); ?>" size="5" maxlength="5"> Mins<br />It allows you to specify the interval in minutes between each email sent per user based on IP to prevent spam and flood.</td> 
				</tr>
			</table>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('Template Variables'); ?></legend>
			<table width="100%" cellspacing="2" cellpadding="5" align="center"> 
				<tr>
					<td><b>%EMAIL_YOUR_NAME%</b> - <?php _e('Display the sender\'s name'); ?></td>
					<td><b>%EMAIL_POST_TITLE%</b> - <?php _e('Display the post\'s title'); ?></td>
				</tr>
				<tr>
					<td><b>%EMAIL_YOUR_EMAIL%</b> - <?php _e('Display the sender\'s email'); ?></td>
					<td><b>%EMAIL_POST_AUTHOR%</b> - <?php _e('Display the post\'s author'); ?></td>
				</tr>
				<tr>
					<td><b>%EMAIL_YOUR_REMARKS%</b> - <?php _e('Display the sender\'s remarks'); ?></td>
					<td><b>%EMAIL_POST_DATE%</b> - <?php _e('Display the post\'s date'); ?></td>
				</tr>
				<tr>
					<td><b>%EMAIL_FRIEND_NAME%</b> - <?php _e('Display the friend\'s name'); ?></td>
					<td><b>%EMAIL_POST_CATEGORY%</b> - <?php _e('Display the post\'s category'); ?></td>
				</tr>
				<tr>
					<td><b>%EMAIL_FRIEND_EMAIL%</b> - <?php _e('Display the friend\'s email'); ?></td>
					<td><b>%EMAIL_POST_CONTENT%</b> - <?php _e('Display the post\'s content'); ?></td>
				</tr>
				<tr>
					<td><b>%EMAIL_ERROR_MSG%</b> - <?php _e('Display the error message'); ?></td>
					<td><b>%EMAIL_BLOG_NAME%</b> - <?php _e('Display the blog\'s name'); ?></td>
				</tr>
				<tr>
					<td>&nbsp</td>
					<td><b>%EMAIL_BLOG_URL%</b> - <?php _e('Display the blog\'s url'); ?></td>
				</tr>
				<tr>
					<td>&nbsp</td>
					<td><b>%EMAIL_PERMALINK%</b> - <?php _e('Display the permalink of the post'); ?></td>
				</tr>
			</table>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('E-Mail Templates'); ?></legend>
			<table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
				 <tr valign="top">
					<td width="30%" align="left">
						<b><?php _e('E-Mail Subject:'); ?></b><br /><br />
						<?php _e('Allowed Variables:'); ?><br />
						- %EMAIL_YOUR_NAME%<br />
						- %EMAIL_YOUR_EMAIL%<br />
						- %EMAIL_POST_TITLE%<br />
						- %EMAIL_POST_AUTHOR%<br />
						- %EMAIL_POST_DATE%<br />
						- %EMAIL_POST_CATEGORY%<br />
						- %EMAIL_BLOG_NAME%<br />
						- %EMAIL_BLOG_URL%<br />
						- %EMAIL_PERMALINK%<br /><br />
						<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template'); ?>" onclick="javascript: email_default_templates('subject');" class="button" />
					</td>
					<td width="70%" align="right"><input type="text" id="email_template_subject" name="email_template_subject" value="<?php echo stripslashes(get_settings('email_template_subject')); ?>" size="62" /></td>
				</tr>
				<tr valign="top"> 
					<td width="30%" align="left">
						<b><?php _e('E-Mail Body:'); ?></b><br /><br />
						<?php _e('Allowed Variables:'); ?><br />
						- %EMAIL_YOUR_NAME%<br />
						- %EMAIL_YOUR_EMAIL%<br />
						- %EMAIL_YOUR_REMARKS%<br />
						- %EMAIL_FRIEND_NAME%<br />
						- %EMAIL_FRIEND_EMAIL%<br />
						- %EMAIL_POST_TITLE%<br />
						- %EMAIL_POST_AUTHOR%<br />
						- %EMAIL_POST_DATE%<br />
						- %EMAIL_POST_CATEGORY%<br />
						- %EMAIL_POST_CONTENT%<br />
						- %EMAIL_BLOG_NAME%<br />
						- %EMAIL_BLOG_URL%<br />
						- %EMAIL_PERMALINK%<br /><br />
						<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template'); ?>" onclick="javascript: email_default_templates('body');" class="button" />
					</td>
					<td width="70%" align="right"><textarea cols="60" rows="15" id="email_template_body" name="email_template_body"><?php echo stripslashes(get_settings('email_template_body')); ?></textarea></td> 
				</tr>
				<tr valign="top"> 
					<td width="30%" align="left">
						<b><?php _e('E-Mail Alternate Body:'); ?></b><br /><br />
						<?php _e('Allowed Variables:'); ?><br />
						- %EMAIL_YOUR_NAME%<br />
						- %EMAIL_YOUR_EMAIL%<br />
						- %EMAIL_YOUR_REMARKS%<br />
						- %EMAIL_FRIEND_NAME%<br />
						- %EMAIL_FRIEND_EMAIL%<br />
						- %EMAIL_POST_TITLE%<br />
						- %EMAIL_POST_AUTHOR%<br />
						- %EMAIL_POST_DATE%<br />
						- %EMAIL_POST_CATEGORY%<br />
						- %EMAIL_POST_CONTENT%<br />
						- %EMAIL_BLOG_NAME%<br />
						- %EMAIL_BLOG_URL%<br />
						- %EMAIL_PERMALINK%<br /><br />
						<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template'); ?>" onclick="javascript: email_default_templates('bodyalt');" class="button" />
					</td>
					<td width="70%" align="right"><textarea cols="60" rows="15" id="email_template_bodyalt" name="email_template_bodyalt"><?php echo stripslashes(get_settings('email_template_bodyalt')); ?></textarea></td> 
				</tr>
			</table>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('After Sending E-Mail Templates'); ?></legend>
			<table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
				 <tr valign="top">
					<td width="30%" align="left">
						<b><?php _e('Sent Successfully:'); ?></b><br /><br />
						<?php _e('Allowed Variables:'); ?><br />
						- %EMAIL_FRIEND_NAME%<br />
						- %EMAIL_FRIEND_EMAIL%<br />
						- %EMAIL_POST_TITLE%<br />
						- %EMAIL_BLOG_NAME%<br />
						- %EMAIL_BLOG_URL%<br />
						- %EMAIL_PERMALINK%<br /><br />
						<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template'); ?>" onclick="javascript: email_default_templates('sentsuccess');" class="button" />
					</td>
					<td width="70%" align="right"><textarea cols="60" rows="10" id="email_template_sentsuccess" name="email_template_sentsuccess"><?php echo stripslashes(get_settings('email_template_sentsuccess')); ?></textarea></td>
				</tr>
				<tr valign="top"> 
					<td width="30%" align="left">
						<b><?php _e('Sent Failed:'); ?></b><br /><br />
						<?php _e('Allowed Variables:'); ?><br />
						- %EMAIL_FRIEND_NAME%<br />
						- %EMAIL_FRIEND_EMAIL%<br />
						- %EMAIL_ERROR_MSG%<br />
						- %EMAIL_POST_TITLE%<br />
						- %EMAIL_BLOG_NAME%<br />
						- %EMAIL_BLOG_URL%<br />
						- %EMAIL_PERMALINK%<br /><br />
						<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template'); ?>" onclick="javascript: email_default_templates('sentfailed');" class="button" />
					</td>
					<td width="70%" align="right"><textarea cols="60" rows="10" id="email_template_sentfailed" name="email_template_sentfailed"><?php echo stripslashes(get_settings('email_template_sentfailed')); ?></textarea></td> 
				</tr>
			</table>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('E-Mail Misc Templates'); ?></legend>
			<table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
				 <tr valign="top">
					<td width="30%" align="left">
						<b><?php _e('E-Mail Error'); ?></b><br /><br />
						<?php _e('Allowed Variables:'); ?><br />
						- %EMAIL_ERROR_MSG%<br />
						- %EMAIL_BLOG_NAME%<br />
						- %EMAIL_BLOG_URL%<br />
						- %EMAIL_PERMALINK%<br /><br />
						<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template'); ?>" onclick="javascript: email_default_templates('error');" class="button" />
					</td>
					<td width="70%" align="right"><textarea cols="60" rows="10" id="email_template_error" name="email_template_error"><?php echo stripslashes(get_settings('email_template_error')); ?></textarea></td>
				</tr>
			</table>
		</fieldset>
		<p class="submit"> 
			<input type="submit" name="Submit" value="<?php _e('Update Options'); ?> &raquo;" /> 
		</p>
	</form> 
</div>