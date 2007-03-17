<?php
/*
 * Email Plugin For WordPress
 *	- wp-email.php
 *
 * Copyright © 2004-2005 Lester "GaMerZ" Chan
*/


// SMTP Server URL
$smtp_server = '';
$smtp_username = '';
$smtp_password = '';

// Require WordPress Header
require('wp-blog-header.php');

// Require E-Mailer Class
require(ABSPATH.WPINC.'/class-phpmailer.php');

// If User Mail
if(isset($_POST['mail'])) {
	$p = intval($_POST['p']);
	$yourname = htmlspecialchars(trim($_POST['yourname']));
	$youremail = htmlspecialchars(trim($_POST['youremail']));
	$yourremarks = htmlspecialchars(trim($_POST['yourremarks']));
	$friendname = htmlspecialchars(trim($_POST['friendname']));
	$friendemail = htmlspecialchars(trim($_POST['friendemail']));

	// Assign Variables
    $post = $wpdb->get_row("SELECT $wpdb->posts.ID, post_name, post_date, post_title, post_content, user_nickname FROM $wpdb->posts LEFT JOIN $wpdb->users ON $wpdb->users.ID = $wpdb->posts.post_author WHERE $wpdb->posts.ID = $p AND post_date_gmt < '".gmdate("Y-m-d H:i:s")."' AND post_status = 'publish' AND post_password = ''");
	$post_id = intval($post->ID);
	$post_title = htmlspecialchars(stripslashes($post->post_title));
	$post_date = mysql2date('jS F Y @ H:i', $post->post_date);
	$post_content = wpautop(stripslashes($post->post_content));
	$post_alt_content = htmlspecialchars(stripslashes($post->post_content));
	$user_nickname = htmlspecialchars(stripslashes($post->user_nickname));

	// Mail Failed Because Of Empty Fields
	if(empty($yourname) || empty($youremail) || empty($friendname) || empty($friendemail) || empty($post)) {
		get_header();
		echo '<div id="content" class="narrowcolumn">';
		echo '<p>';
		echo '<b>Error</b><br />';
		echo '<b>&raquo;</b> Some Fields Are Not Filled Up<br />';
		echo '</p>';
		echo '<p><a href="javascript:history.go(-1);">Click Here To Go Back</a></p>';
		echo '</div>';
		get_sidebar();
		get_footer();
		exit();
	}

	// Assign Text
	$mail_body = "<p>Hi there <b>$friendname</b>,<br />Your friend, $yourname, has this recommanded this article entitled '$post_title' to you.</p><p>Here is his/her remarks:<br />$yourremarks</p><p><b>$post_title</b><br />Posted By $user_nickname On $post_date</p><p>$post_content</p><p>&nbsp</p><p>Article taken from ".get_bloginfo('name')." - ".get_bloginfo('url')."</p><p>URL to article: <a href=\"".get_permalink()."\">".get_permalink()."</a></p>";
	 $mail_alt_body = "Hi there $friendname,\nYour friend, $yourname, has this recommanded this article entitled '$post_title' to you.\n\nHere is his/her remarks:\n$yourremarks\n\n> $post_title\n- Posted By $user_nickname On $post_date\n\n$post_alt_content\n\n\n\nArticle taken from ".get_bloginfo('name')." - ".get_bloginfo('url')."\nURL to article: ".get_permalink();

	// Send Mail Using phpMailer
	$mail = new PHPMailer();
	$mail->From     = $youremail;
	$mail->FromName = $yourname;
	$mail->AddAddress($friendemail, $friendname);
	$mail->Username = $smtp_username; 
	$mail->Password = $smtp_password;
	$mail->Host     = $smtp_server;
	$mail->Mailer   = "smtp";
	$mail->Subject = "Recommanded Article By $yourname: $post_title";
	$mail->Body    = $mail_body;
    $mail->AltBody = $mail_alt_body;

	if($mail->Send()) {
		get_header();
		echo "<div id=\"content\" class=\"narrowcolumn\"><p>Article: <b>$post_title</b> Has Been Sent To <b>$friendemail</b></p></div>";
		get_sidebar();
		get_footer();
		exit();
	} else {
		get_header();
		echo '<div id="content" class="narrowcolumn">'.$mail->ErrorInfo.'</div>';
		get_sidebar();
		get_footer();
	}
}
?>
<?php get_header(); ?>
	<div id="content" class="narrowcolumn">
		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
				<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
					<input type="hidden" name="p" value="<?=the_ID()?>" />
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