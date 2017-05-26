<?php
    $test_id = $_GET['test_id'];
    $ref_no = $_GET['ref_no'];

    if(isset($ref_no) && is_user_logged_in())
        echo "<script language=\"javascript\">self.location=\"".site_url("thank-you-for-giving-the-assessment")."?ref_no=$ref_no\"; </script>";
        
    
    cb_login_form(0);

    global $wpdb;
    $degrees = $wpdb->get_results("SELECT * FROM wp_cb_ci_degree");
    $degree_array = array();
    
    
    
    foreach ($degrees as $degree)
    {
        array_push($degree_array, array('value' => $degree->degree_id, 'label' => $degree->degree ));
    }


	
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
    new JFormComponentHidden('ref_no', $ref_no),
    new JFormComponentHidden('test_id', $test_id),
    
    new JFormComponentSingleLineText('contact_number', 'Your 10 digit Mobile Number:', array(
        'validationOptions' => array('required'),
        'mask' => '9999999999',        
    )),
    
    
    new JFormComponentDropDown('degree', 'I am in:', 
        $degree_array,             array('tip' =>
        '<p></p>',
        'validationOptions' => array('required'),
    )),

    new JFormComponentMultipleChoice('terms', '', array(
        array('value' => 'agree', 'label' => 'I agree to the site <a href="/legal/terms-and-conditions/" target="_blank" data-bitly-type="bitly_hover_card">Terms and Conditions</a>?'),
            ),
            array(
                'validationOptions' => array('required'),
    )),


/*  new JFormComponentMultipleChoice('updates', '', array(
        array('value' => 'signup', 'label' => 'I would like to recieve updates.'),
            ),
            array()),
    new JFormComponentSingleLineText('password', 'Password:', array(
        'type' => 'password',
        'validationOptions' => array('required', 'password'),
    )),
    new JFormComponentSingleLineText('passwordConfirm', 'Confirm Password:', array(
        'type' => 'password',
        'validationOptions' => array('required', 'password', 'matches' => 'password'),
    )),
    new JFormComponentMultipleChoice('update_type', 'How would you like to recieve updates?', array(
        array('value' => 'Email', 'label' => 'Send updates to my Email'),
        array('value' => 'Text Message', 'label' => 'Send updates to my Phone via Text Message'),
            ),
            array(
                'multipleChoiceType' => 'radio',
                'validationOptions' => array('required'),
                'dependencyOptions' => array(
                    'dependentOn' => 'updates',
                    'display' => 'hide',
                    'jsFunction' => 'jQuery("#updates-choice1").is(":checked");'
                ),
    )),*/

    ));
    
// Set the function for a successful form submission
function onSubmit($formValues) {

        require_once(ABSPATH . WPINC . '/registration.php');
        global $wpdb;

		$name = ucwords($wpdb->escape($formValues->name));
//		$password = $wpdb->escape($formValues->password);
        $password = substr(SHA1($name."test"),-16,8);
		$email = $wpdb->escape($formValues->email);
		$dob = $wpdb->escape($formValues->dob);
		$contact_number = $wpdb->escape($formValues->contact_number);
        $ref_no= $wpdb->escape($formValues->ref_no);
        $degree = $wpdb->escape($formValues->degree);
        $test_id = $wpdb->escape($formValues->test_id);



        $status = wp_create_user( $email, $password, $email );

		if ( ! $status )
        {
			$response = "<br/><h2>Email ID already registered. Please try another one.<a href=\"".site_url('show-me-the-results')."?test_id=$test_id&ref_no=$ref_no\">Click here to try again</a></h2>";
			$errors->add( 'registerfail', __( 'Couldn\'t register you... please contact the site administrator', 'geissinger-wpml' ) );
		}
        else {
            $user_id = $status;
              
            $user['degree_id'] = $degree;
            $user['first_name'] = $name;
            $user['display_name'] = $name;
            $user['dob'] = $dob;
            $user['contact_numer'] = $contact_number;
            
            $wpdb->update('wp_users', $user, Array('ID' => $user_id));              
           	            
            //Because of some JFormer issues, this function (of auto login) is not working.
            //wp_set_auth_cookie( $user_id, true, is_ssl() );
            
            //Email User about login details
            $CBConnect = new CB_Connect();
            $email_footer = $CBConnect->get_email_footer();
            $from = '"Career Breeder" <contactus@careerbreeder.com>';
	                $headers = 'From: '.$from . "\r\n
                    List-Unsubscribe: <mailto:contactus@careerbreeder.com>, <http://careerbreeder.com/member/unsubscribe/?listname=espc-tech@domain.com?id=12345N>";
                    // Always set content-type when sending HTML email
                    $headers .= "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
	                $subject = "Registration successful at Career Breeder";
       	             $msg = "Dear $name,<br/><br/>Thank you for registering at Career Breeder.<br/><br/>Your login details are-<br/>Username: $email <br/>Password: $password".$email_footer;
	                wp_mail( $email, $subject, $msg, $headers );
            
            /**
             * Scenarios:
             *  Career Indicator (Testid: 15) : User must signup in Beginning
             *  IQ Test (Test ID: 17): Signup user and email results
             *  Resume Development( Test ID: 1,2 and 3): Signup User, fetch prices from CB_Connect and take to payment page
             */
            
            
            if($ref_no>14) //User's IQ test_id was generated
            {
                //update the user's uid in ref_no
                $result_master = Array(
                    'ref_no' => $ref_no,
                    'uid' => $user_id,
                 );
                 $table_name = $wpdb->prefix."cb_result_master";

                $wpdb->update( $table_name, $result_master, Array('ref_no' => $result_master['ref_no']));
                
                
                
                //tell site admin about it
                $from = '"Career Breeder" <contactus@careerbreeder.com>';
                $headers = 'From: '.$from . "\r\n
                List-Unsubscribe: <mailto:contactus@careerbreeder.com>, <http://careerbreeder.com/member/unsubscribe/?listname=espc-tech@domain.com?id=12345N>";
                // Always set content-type when sending HTML email
                $headers .= "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

                $subject = "One user has given test which costs ".$amount;
                $msg = "Hi Admin, <br/><br/>Reference Number: $ref_no <br/>
                User ID: $user_id <br />
                Name: $name <br />
                Contact number: $contact_number <br />
                DOB: $dob <br />
                Degree: $degree <br />
                <b>Call them up</b>".$email_footer;
                                 
                wp_mail( "contactus@careerbreeder.com", $subject, $msg, $headers );
                
                
                //mail results to the user.
                $from = '"Career Breeder" <contactus@careerbreeder.com>';
                $headers = 'From: '.$from . "\r\n
                List-Unsubscribe: <mailto:contactus@careerbreeder.com>, <http://careerbreeder.com/member/unsubscribe/?listname=espc-tech@domain.com?id=12345N>\r\n
                Cc: contactus@careerbreeder.com";
                // Always set content-type when sending HTML email
                $headers .= "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

                $subject = "IQ Test at Career Breeder";
                $msg = "Dear $name,<br/><br/>Thank you for giving IQ test at Career Breeder.<br/><br/>".cbquiz_results($ref_no).$email_footer;
                wp_mail( $email, $subject, $msg, $headers );
                $response .= "Your results are mailed to you. Kindly check your email.";                                
            }
            
            
            else if($ref_no==1 || $ref_no==2 || $ref_no==3 )
            {
                $details['product_id'] = $ref_no;
                $details['uid'] = $user_id;
                
                $connect_id = $CBConnect->insert($details);                
                $response.= "<h2>Thank you for registering with us. Redirecting you to payment page.</h2><br />";
                $location = $CBConnect->get_URL($connect_id, $user_id);
                $response.= "<script>window.location = \"$location\";</script>";
		      }
          
          else
          {
                $response .= "Some error occured";
          }
        }
    return array( 'successPageHtml' => $response );
}
?>