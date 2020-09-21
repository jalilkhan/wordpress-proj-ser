<?php

$response = "";

//response messages
$missing_content = __( 'Please supply all necessary information!', 'bt_plugin' );
$email_invalid   = __( 'Email Address Invalid!', 'bt_plugin' );
$message_unsent  = __( 'Message was not sent. Try Again!', 'bt_plugin' );
$message_sent    = __( 'Thanks! Your message has been sent!', 'bt_plugin' );

$contact_name		= isset($_POST['contact-name']) ? $_POST['contact-name'] : "";
$contact_email		= isset($_POST['contact-email']) ? $_POST['contact-email'] : "";
$contact_phone		= isset($_POST['contact-phone']) ? $_POST['contact-phone'] : "";
$contact_message	= isset($_POST['contact-message']) ? $_POST['contact-message'] : "";
$contact_admin_email	= isset($_POST['contact-admin-email']) ? $_POST['contact-admin-email'] : "";

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			
	if(empty($contact_email) || empty($contact_name)){
		$response = bt_bb_contact_form_generate_response("error", $missing_content);
	}
	else
	{
		  if( !filter_var($contact_email, FILTER_VALIDATE_EMAIL) || !filter_var($contact_admin_email, FILTER_VALIDATE_EMAIL) ){
				$response = bt_bb_contact_form_generate_response("error", $email_invalid);				
		  }
		  else
		  {
				$subject = "Custom Listing Contact From message sent from ".get_bloginfo('name');

				$headers = get_option('admin_email') != '' ? "From: " . get_option('admin_email') . "\r\n" : '';
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
				$headers .= "Content-Transfer-Encoding: 8bit\n";

				$message_to_admin = '<html><body>' . "\r\n";

				if ( $contact_name != '' )		$message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Name', 'bt_plugin' ) . '</b>: ' . stripslashes( $contact_name ) . '</div>' . "\r\n";
				if ( $contact_email != '' )		$message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Email', 'bt_plugin' ) . '</b>: <a href="mailto:' . $contact_email . '">' . $contact_email . '</a></div>' . "\r\n";
				if ( $contact_phone != '' )		$message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Phone', 'bt_plugin' ) . '</b>: ' . $contact_phone . '</div>' . "\r\n";
				if ( $contact_message != '' )	$message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Message', 'bt_plugin' ) . '</b>: ' . stripslashes( $contact_message ) . '</div>' . "\r\n";

				$message_to_admin .= '</body></html>';

				$sent = wp_mail($contact_admin_email, $subject, $message_to_admin, $headers);
				if($sent){
					$response = bt_bb_contact_form_generate_response("success", $message_sent); 
					$contact_name		= "";
					$contact_email		= "";
					$contact_phone		= "";
					$contact_message	= "";
				} else {
					$response = bt_bb_contact_form_generate_response("error", $message_unsent);
				}
		  }
	}

	echo $response;
	
}

//function to generate response
function bt_bb_contact_form_generate_response($type, $message){
	global $response;
	if($type == "success") $response = "<div class='success'>{$message}</div>";
	else $response = "<div class='error'>{$message}</div>";
	return $response;

}