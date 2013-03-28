<?php
	

	global $mail_success, $form_message;
	
	$fields = array( 'name', 'email', 'category', 'message' );
	
	foreach ($fields as $field) {
		$$field = $_POST["$field"];
	}
	
	foreach ($fields as $var)	{
		if (strlen($$var) < 1) { 
			$mail_success = false; 
			$form_message = "Please fill out the '$var' field"; 
			break;
		}
	}
		
	if (!$form_message) {
		$site = get_bloginfo();
		$site_url = parse_url(get_site_url(), PHP_URL_HOST);
	
		$subject = "$category $site";
		$body = "<b>$name</b> has a <b>$category</b> from the <b>$site</b> website: <br /><br />$message";
	

		// ini_set("sendmail_from", 'tybruffy@gmail.com');
		$headers =	'MIME-Version: 1.0' . "\r\n"
				 .	'Content-type: text/html; charset=iso-8859-1' . "\r\n"
				 .	"From: \"$site Admin\" <tybruffy@gmail.com>" . "\r\n"
				 .	"Reply-To: $email" . "\r\n";

		if (mail(DEVELOPER_EMAIL, $subject, $body, $headers)) {
			$mail_success = true;
			$form_message = "Thank you, your message has been sent successfully.";
		}
		else {
			$mail_success = false;
			$form_message = "An unknown error occurred and your request was not processed.  Please try again.  If the problem persists, please get in touch directly at ".DEVELOPER_EMAIL." or ".DEVELOPER_PHONE;
		}
	}

?>