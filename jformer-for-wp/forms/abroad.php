<?php
    $form->addJFormComponentArray(array(
		new JFormComponentSingleLineText('name', 'Name:', array(
            'width' => 'longest',
            'validationOptions' => array('required'),
        )),
        new JFormComponentSingleLineText('email', 'E-mail address:', array(
            'width' => 'longest',
            'validationOptions' => array('required', 'email'), // notice the validation options
        )),
    new JFormComponentSingleLineText('dob', 'Date of Birth (mm/dd/yyyy):', array(
        'validationOptions' => array('required', 'date'),
    )),

    new JFormComponentSingleLineText('contact_number', 'Your 10 digit Mobile Number:', array(
        'validationOptions' => array('required')
    )),
    new JFormComponentMultipleChoice('update_type', 'From which department you want the counsellor to contact you?', array(
        array('value' => 'Counsellor', 'label' => 'College/VISA counsellor'),
        array('value' => 'Loan', 'label' => 'Loan counsellor'),
        array('value' => 'Both', 'label' => 'Both of the above counsellors'),
            ),
            array(
                'multipleChoiceType' => 'radio',
                'validationOptions' => array('required'),    
            )),
            
    new JFormComponentMultipleChoice('update_type', 'How would you like to recieve updates?', array(
        array('value' => 'Counsellor', 'label' => 'I want to know about colleges abroad.'),
        array('value' => 'Loa', 'label' => 'I want to know about Loan options.'),
            ),
            array(
                'multipleChoiceType' => 'radio',
                'validationOptions' => array('required'),    
            )),
        
    new JFormComponentTextArea('query', 'Your query:', array(
        'validationOptions' => array('required'),
        'height' => 'medium',
        'width' => 'longest'
    )),

    new JFormComponentMultipleChoice('terms', '', array(
        array('value' => 'agree', 'label' => 'I agree to the site <a href="/legal/terms-and-conditions/" target="_blank" data-bitly-type="bitly_hover_card">Terms and Conditions</a>?'),
            ),
            array(
                'validationOptions' => array('required'),
    )),

    ));
    
// Set the function for a successful form submission
function onSubmit($formValues) {

    require_once(ABSPATH . WPINC . '/registration.php');
    global $wpdb;

	$name = $wpdb->escape($formValues->name);
    $password = substr(SHA1($name."test"),-16,8);
	$email = $wpdb->escape($formValues->email);
	$dob = $wpdb->escape($formValues->dob);
	$contact_number = $wpdb->escape($formValues->contact_number);
	$query = $wpdb->escape($formValues->query);
    $update_type = $wpdb->escape($formValues->update_type);
    
    $status = wp_create_user( $email, $password, $email );
    
    if ( !is_wp_error($status) )
    {    
        $user_id = $status;
        $name = ucwords($name);
          
        wp_insert_user( array('ID' =>$user_id , 'dob'=> $dob ));
        wp_insert_user( array('ID' =>$user_id , 'user_nicename'=> $name ));
        wp_insert_user( array('ID' =>$user_id , 'nickname'=> $name ));
        wp_update_user( array('ID' =>$user_id , 'display_name'=> $name ));            
        wp_update_user( array('ID' =>$user_id , 'contact_number'=> $contact_number ));
        
        global $wpdb;
        $wpdb->query("UPDATE wp_users SET dob = '$dob' AND contact_number = '$contact_number' WHERE ID = '$user_id'");
        
        update_user_meta( $user_id, 'dob', $dob );
        update_user_meta( $user_id, 'nickname', $name );
        update_user_meta( $user_id, 'display_name', $name );
        update_user_meta( $user_id, 'name', $name );
        update_user_meta( $user_id, 'contact_number', $contact_number );
        
       //auto login
        wp_set_auth_cookie( $user_id, true, is_ssl() );
    } else {
        $user = get_user_by( 'email', $email );
        $user_id = $user->ID; 
    }   

    $from = '"Career Breeder" <contactus@careerbreeder.com>';
    $headers = 'From: '.$from . "\r\n
    List-Unsubscribe: <mailto:contactus@careerbreeder.com>, <http://careerbreeder.com/member/unsubscribe/?listname=espc-tech@domain.com?id=12345N>";
    // Always set content-type when sending HTML email
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
    $subject = "You will soon receive a call from our counsellor";
    $msg = "Dear $name,<br/><br/>Thank you for contacting Career Breeder.<br/><br/>Your login details are-<br/>Username: $email<br/>Password: $password <br/><br/>Your query \"$query\" was successfully sent to our counsellors. They will soon get back to you with the resolution. <br/><br/>Please feel free to call us at +91 9019161133<br/><br/><br/>Regards<br/>Career Breeder Team";
    wp_mail( $email, $subject, $msg, $headers );
        
    
    if($update_type=='Counsellor')
    {
        $to = 'rohit@careerbreeder.com';
    } else if($update_type == 'Loan')
    {
        $to = 'rohit@careerbreeder.com';
    } else if($update_type=='Both')
    {
        $to = 'rohit@careerbreeder.com';            
    }
    
    
    $from = '"Career Breeder" <contactus@careerbreeder.com>';
    $headers = 'From: '.$from . "\r\n
    List-Unsubscribe: <mailto:contactus@careerbreeder.com>, <http://careerbreeder.com/member/unsubscribe/?listname=espc-tech@domain.com?id=12345N>";
    // Always set content-type when sending HTML email
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
    $subject = "One user has study abroad related query";

    $msg = "User ID: $user_id <br/>
    Name: $name <br/>
    Contact number: $contact_number <br/>
    DOB: $dob <br/>
    Query: $query <br/>
    <b>Call them up</b>";          

    wp_mail( $to, $subject, $msg, $headers );
    $response = "<h2>Just relax now! Our counselors will soon call you and solve all your queries.</h2>";

    return array( 'successPageHtml' => $response );
}
?>