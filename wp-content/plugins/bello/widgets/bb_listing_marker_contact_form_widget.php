<?php

$response = "";

$contact_name		= isset($_POST['contact-name']) ? $_POST['contact-name'] : "";
$contact_email		= isset($_POST['contact-email']) ? $_POST['contact-email'] : "";
$contact_phone		= isset($_POST['contact-phone']) ? $_POST['contact-phone'] : "";
$contact_message	= isset($_POST['contact-message']) ? $_POST['contact-message'] : "";

$missing_content = __( 'Please supply all necessary information!', 'bt_plugin' );
$email_invalid   = __( 'Email Address Invalid!', 'bt_plugin' );
$message_unsent  = __( 'Message was not sent. Try Again!', 'bt_plugin' );
$message_sent    = __( 'Thanks! Your message has been sent!', 'bt_plugin' );


if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			
	if(empty($contact_email) || empty($contact_name)){
		$response = bt_bb_contact_form_generate_response("error", $missing_content);
	}
	else
	{
		  if( !filter_var($contact_email, FILTER_VALIDATE_EMAIL) || !filter_var($contact_form_email, FILTER_VALIDATE_EMAIL) ){
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

				$sent = wp_mail($contact_form_email, $subject, $message_to_admin, $headers);
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
	
}

//wp_enqueue_script( 'bt_listing_contact_form_js', plugins_url() . '/bello/widgets/js/bb_listing_marker_contact_form_widget.js', array( 'jquery-ui-slider' ) );

if ( $contact_form_email != '') {
?>
	<a id="bb_listing_marker_contact_form"></a>	
	<div class="submitMessage"><?php echo $response;?></div>
	<form id="bb_listing_marker_contact_form" class="widget_form_wrapper" method="post" action="#bb_listing_marker_contact_form">
		<input type="hidden" name="contact-admin-email" id="contact-admin-email" value="<?php echo $contact_form_email;?>">
		<p><input type="text" id="contact-name" name="contact-name" placeholder="<?php echo __( 'Your name', 'bt_plugin' );?> *" value="<?php echo $contact_name;?>"></p>
		<p><input type="text" id="contact-email" name="contact-email" placeholder="<?php echo __( 'Your e-mail address', 'bt_plugin' );?> *" value="<?php echo $contact_email;?>"></p>
		<p><input type="text" id="contact-phone" name="contact-phone" placeholder="<?php echo __( 'Your phone number', 'bt_plugin' );?>" value="<?php echo $contact_phone;?>"></p>
		<p><textarea id="contact-message" name="contact-message" cols="30" rows="6" placeholder="<?php echo __( 'Your Message', 'bt_plugin' );?>"><?php echo $contact_message;?></textarea></p>
		<p><button type="submit" value="<?php echo __( 'Send message', 'bt_plugin' );?>" id="btMessageSubmit" class="btMessageSubmit" name="submit" data-ico-fa=""><span class="btnInnerText"><?php echo __( 'Send message', 'bt_plugin' );?></span></button></p>
	</form>
<?php 
} 



function bt_bb_contact_form_generate_response($type, $message){
	global $response;
	if($type == "success") $response = "<div class='success'>{$message}</div>";
	else $response = "<div class='error'>{$message}</div>";
	return $response;

}