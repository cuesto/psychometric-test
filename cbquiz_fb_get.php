<?php 
require_once(ABSPATH . WPINC . '/registration.php');
global $wpdb;
//echo  "hi".$_GET['firstnm'];

//Check whether the user is already logged in
if ( !is_user_logged_in() ) {


		//We shall SQL escape all inputs
		$name = $wpdb->escape($_GET['firstnm']." ".$_GET['lastnm']);
        $password = substr(SHA1($name."test"),-16,8);
		$email = $wpdb->escape($_GET['email']);
		$dob = $wpdb->escape($_GET['dob']);
//		$contact_number = $wpdb->escape($formValues->contact_number);
//        $ref_no= $formValues->ref_no;

        $status = wp_create_user( $email, $password, $email );
        
		if ( is_wp_error($status) )
			$response = "<br/>Email ID already registered. Please try another one.<a href=\"http://careerbreeder.com/register-2/?test_id=$test_id&ref_no=$ref_no\">Click here to try again</a>";
		else {
		      $user_id = $status;
           	update_usermeta( $user_id, 'dob', $dob );
           	update_usermeta( $user_id, 'name', $name );
//            update_usermeta( $user_id, 'contact_number', $contact_number );
            
            //auto login
            wp_set_auth_cookie( $user_id, true, is_ssl() );
            
            $from = '"Career Breeder" <contactus@careerbreeder.com>';
	                $headers = 'From: '.$from . "\r\n
                    List-Unsubscribe: <mailto:contactus@careerbreeder.com>, <http://careerbreeder.com/member/unsubscribe/?listname=espc-tech@domain.com?id=12345N>";
	                $subject = "Registration successful at Career Breeder";
       	             $msg = "Thank you for registering at Career Breeder.\nYour login details are-\nUsername: $email\nPassword: $password";
	                wp_mail( $email, $subject, $msg, $headers );
            
    return array( 'successPageHtml' => $response );
    }
}

?>