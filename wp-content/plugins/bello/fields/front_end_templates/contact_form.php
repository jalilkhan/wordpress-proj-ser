<?php
if ( isset($field) ) {
	if ( isset($field['name']) && isset($field['value']) ) {
		$contact_form_email	= isset($field["value"][0]) ? $field["value"][0] : '';
		
		$response = "";

		$contact_name		= isset($_POST['contact-name']) ? $_POST['contact-name'] : "";
		$contact_email		= isset($_POST['contact-email']) ? $_POST['contact-email'] : "";
		$contact_phone		= isset($_POST['contact-phone']) ? $_POST['contact-phone'] : "";
		$contact_message	= isset($_POST['contact-message']) ? $_POST['contact-message'] : "";
                $rnd                    = isset($_POST['contact-rnd']) ? $_POST['contact-rnd'] : rand(100, 999);
                $contact_listing_id     = isset($_POST['contact-listing-id']) ? " ID: " . $_POST['contact-listing-id'] : "";
                $contact_listing        = $contact_listing_id . " - " . esc_html( get_the_title() );

		$missing_content = __( 'Please supply all necessary information!', 'bt_plugin' );
		$email_invalid   = __( 'Email Address Invalid!', 'bt_plugin' );
		$message_unsent  = __( 'Message was not sent. Try Again!', 'bt_plugin' );
		$message_sent    = __( 'Thanks! Your message has been sent!', 'bt_plugin' );

		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {					
                    if(empty($contact_email) || empty($contact_name)){
                        $response = bt_bb_contact_form_generate_response("error", $missing_content, $rnd);
                    }
                    else
                    {
                        if( !filter_var($contact_email, FILTER_VALIDATE_EMAIL) || !filter_var($contact_form_email, FILTER_VALIDATE_EMAIL) ){
                              $response = bt_bb_contact_form_generate_response("error", $email_invalid, $rnd);				
                        }
                        else
                        {
                              $subject = __( 'Custom Listing Contact message sent from', 'bt_plugin' ) . " ".get_bloginfo('name');

                              $headers = get_option('admin_email') != '' ? "From: " . get_option('admin_email') . "\r\n" : '';
                              $headers .= "MIME-Version: 1.0\r\n";
                              $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                              $headers .= "Content-Transfer-Encoding: 8bit\n";

                              $message_to_admin = '<html><body>' . "\r\n";
                              if ( $contact_name != '' )	$message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Name', 'bt_plugin' ) . '</b>: ' . stripslashes( $contact_name ) . '</div>' . "\r\n";
                              if ( $contact_email != '' )	$message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Email', 'bt_plugin' ) . '</b>: <a href="mailto:' . $contact_email . '">' . $contact_email . '</a></div>' . "\r\n";
                              if ( $contact_phone != '' )	$message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Phone', 'bt_plugin' ) . '</b>: ' . $contact_phone . '</div>' . "\r\n";
                              if ( $contact_message != '' )	$message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Message', 'bt_plugin' ) . '</b>: ' . stripslashes( $contact_message ) . '</div>' . "\r\n";
                              if ( $contact_listing != '' )	$message_to_admin .= '<div style="padding:.5em;"><b>' . __( 'Listing', 'bt_plugin' ) . '</b>: ' . stripslashes( $contact_listing ) . '</div>' . "\r\n";
                              
                              $message_to_admin .= '</body></html>';

                              $sent = wp_mail($contact_form_email, $subject, $message_to_admin, $headers);
                              if($sent){
                                        $response = bt_bb_contact_form_generate_response("success", $message_sent, $rnd); 
                                        $contact_name		= "";
                                        $contact_email		= "";
                                        $contact_phone		= "";
                                        $contact_message	= "";
                              } else {
                                        $sitename = strtolower( $_SERVER['SERVER_NAME'] );
                                        if ( substr( $sitename, 0, 4 ) == 'www.' ) {
                                            $sitename = substr( $sitename, 4 );
                                        }
                
                                        $headers2 = "From: wordpress@" . $sitename . "\r\n" ;
                                        $headers2 .= "MIME-Version: 1.0\r\n";
                                        $headers2 .= "Content-Type: text/html; charset=UTF-8\r\n";
                                        $headers2 .= "Content-Transfer-Encoding: 8bit\n";
                                    
                                        $sent = wp_mail($contact_form_email, $subject, $message_to_admin, $headers2);
                                        if($sent){
                                                $response = bt_bb_contact_form_generate_response("success", $message_sent, $rnd); 
                                                $contact_name		= "";
                                                $contact_email		= "";
                                                $contact_phone		= "";
                                                $contact_message	= "";
                                        } else {
                                                $response = bt_bb_contact_form_generate_response("error", $message_unsent . ' ' . $GLOBALS['phpmailer']->ErrorInfo, $rnd);
                                        }
                              }
                        }
                    }			
		}

		if ( $contact_form_email != '') {
		?>
                    <div class="submitMessage"><?php echo $response;?></div>
                    <form id="bb_listing_marker_contact_form_<?php echo $rnd;?>" class="widget_form_wrapper" method="post" action="#bb_listing_marker_contact_form_<?php echo $rnd;?>">
                            <input type="hidden" name="contact-admin-email" id="contact-admin-email" value="<?php echo $contact_form_email;?>">
                            <p><input type="text" id="contact-name" name="contact-name" placeholder="<?php echo __( 'Your name', 'bt_plugin' );?> *" value="<?php echo $contact_name;?>"></p>
                            <p><input type="text" id="contact-email" name="contact-email" placeholder="<?php echo __( 'Your e-mail address', 'bt_plugin' );?> *" value="<?php echo $contact_email;?>"></p>
                            <p><input type="text" id="contact-phone" name="contact-phone" placeholder="<?php echo __( 'Your phone number', 'bt_plugin' );?>" value="<?php echo $contact_phone;?>"></p>
                            <p><textarea id="contact-message" name="contact-message" cols="30" rows="6" placeholder="<?php echo __( 'Your Message', 'bt_plugin' );?>"><?php echo $contact_message;?></textarea></p>
                            <p>
                                <input type="hidden" name="contact-rnd" id="contact-rnd" value="<?php echo $rnd;?>" />
                                <input type="hidden" name="contact-listing-id" id="contact-listing-id" value="<?php echo get_the_ID();?>" />
                                <button type="submit" value="<?php echo __( 'Send message', 'bt_plugin' );?>" id="btMessageSubmit" class="btMessageSubmit" name="submit" data-ico-fa=""><span class="btnInnerText"><?php echo __( 'Send message', 'bt_plugin' );?></span></button>
                            </p>
                    </form>
		<?php 
		} 
	}
}



