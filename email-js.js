var email_p=0,email_pageid=0,email_yourname="",email_youremail="",email_yourremarks="",email_friendname="",email_friendemail="",email_friendnames="",email_friendemails="",email_imageverify="";emailL10n.max_allowed=parseInt(emailL10n.max_allowed);
function validate_email_form(){var b=!1,a=emailL10n.text_error+"\n",a=a+"__________________________________\n\n";!jQuery("#yourname").length||!isEmpty(email_yourname)&&is_valid_name(email_yourname)||(a=a+emailL10n.text_name_invalid+"\n",b=!0);!jQuery("#youremail").length||!isEmpty(email_youremail)&&is_valid_email(email_youremail)||(a=a+emailL10n.text_email_invalid+"\n",b=!0);!jQuery("#yourremarks").length||isEmpty(email_yourremarks)||is_valid_remarks(email_yourremarks)||(a=a+emailL10n.text_remarks_invalid+
"\n",b=!0);if(jQuery("#friendname").length){if(isEmpty(email_friendname))a=a+emailL10n.text_friend_names_empty+"\n",b=!0;else for(i=0;i<email_friendnames.length;i++)if(isEmpty(email_friendnames[i])||!is_valid_name(email_friendnames[i]))a=a+emailL10n.text_friend_name_invalid+email_friendnames[i]+"\n",b=!0;email_friendnames.length>emailL10n.max_allowed&&(a=a+emailL10n.text_max_friend_names_allowed+"\n",b=!0)}if(isEmpty(email_friendemail))a=a+emailL10n.text_friend_emails_empty+"\n",b=!0;else for(i=0;i<
email_friendemails.length;i++)if(isEmpty(email_friendemails[i])||!is_valid_email(email_friendemails[i]))a=a+emailL10n.text_friend_email_invalid+email_friendemails[i]+"\n",b=!0;email_friendemails.length>emailL10n.max_allowed&&(a=a+emailL10n.text_max_friend_emails_allowed+"\n",b=!0);jQuery("#friendname").length&&email_friendnames.length!=email_friendemails.length&&(a=a+emailL10n.text_friends_tally+"\n",b=!0);jQuery("#imageverify").length&&isEmpty(email_imageverify)&&(a=a+emailL10n.text_image_verify_empty+
"\n",b=!0);return 1==b?(alert(a),!1):!0}function isEmpty(b){return""==jQuery.trim(b)?!0:!1}function is_valid_name(b){return!/[(\*\(\)\[\]\+\,\/\?\:\;\'\"\`\~\\#\$\%\^\&\<\>)+]/.test(jQuery.trim(b))}function is_valid_email(b){return/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(jQuery.trim(b))}
function is_valid_remarks(b){b=jQuery.trim(b);var a="apparently-to content-disposition content-type content-transfer-encoding errors-to in-reply-to message-id mime-version multipart/mixed multipart/alternative multipart/related reply-to x-mailer x-sender x-uidl".split(" ");for(i=0;i<a.length;i++)if(-1!=b.indexOf(a[i]))return!1;return!0}function email_popup(b){window.open(b,"_blank","width=500,height=500,toolbar=0,menubar=0,location=0,resizable=0,scrollbars=1,status=0")}
function email_form(){jQuery("#yourname").length&&(email_yourname=jQuery("#yourname").val());jQuery("#youremail").length&&(email_youremail=jQuery("#youremail").val());jQuery("#yourremarks").length&&(email_yourremarks=jQuery("#yourremarks").val());jQuery("#friendname").length&&(email_friendname=jQuery("#friendname").val(),email_friendnames=email_friendname.split(","));email_friendemail=jQuery("#friendemail").val();email_friendemails=email_friendemail.split(",");jQuery("#imageverify").length&&(email_imageverify=
	jQuery("#imageverify").val());jQuery("#p").length&&(email_p=jQuery("#p").val());jQuery("#page_id").length&&(email_pageid=jQuery("#page_id").val());validate_email_form()&&(email_ajax_data="action=email",jQuery("#wp-email-submit").attr("disabled",!0),jQuery("#wp-email-loading").show(),jQuery("#yourname").length&&(email_ajax_data+="&yourname="+email_yourname,jQuery("#yourname").attr("disabled",!0)),jQuery("#youremail").length&&(email_ajax_data+="&youremail="+email_youremail,jQuery("#youremail").attr("disabled",
	!0)),jQuery("#yourremarks").length&&(email_ajax_data+="&yourremarks="+email_yourremarks,jQuery("#yourremarks").attr("disabled",!0)),jQuery("#friendname").length&&(email_ajax_data+="&friendname="+email_friendname,jQuery("#friendname").attr("disabled",!0)),jQuery("#friendemail").length&&(email_ajax_data+="&friendemail="+email_friendemail,jQuery("#friendemail").attr("disabled",!0)),jQuery("#imageverify").length&&(email_ajax_data+="&imageverify="+email_imageverify,jQuery("#imageverify").attr("disabled",
	!0)),jQuery("#p").length&&(email_ajax_data+="&p="+email_p),jQuery("#page_id").length&&(email_ajax_data+="&page_id="+email_pageid),email_ajax_data+="&wp-email_nonce="+jQuery("#wp-email_nonce").val(),jQuery.ajax({type:"POST",xhrFields:{withCredentials:!0},url:emailL10n.ajax_url,data:email_ajax_data,cache:!1,success:function(b){jQuery("#wp-email-content").html(b)}}))};