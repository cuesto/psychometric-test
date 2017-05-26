<?php
function login_after_test_helper()
{
    global $current_user;
    get_currentuserinfo();
    global $wpdb;
    
                //update the user's uid in ref_no
                $result_master = Array(
                    'ref_no' => $_GET['ref_no'],
                    'uid' => $current_user->ID,
                 );
                 $table_name = $wpdb->prefix."cb_result_master";
                $wpdb->update( $table_name, $result_master, Array('ref_no' => $result_master['ref_no']));
                
                //check if the test is free or not to decide next step.
                $table_name1 = $wpdb->prefix."cb_test";
                $table_name2 = $wpdb->prefix."cb_result_master";
                $ref_no = $result_master['ref_no'];
                $amount = $wpdb->get_results("SELECT cost FROM `$table_name1`,`$table_name2` WHERE $table_name1.test_id = $table_name2.test_id AND $table_name2.ref_no = $ref_no ");
                $amount = $amount[0]->cost;
                if($amount==0)
                {   
                    //mail results to the user.
                    $from = '"Career Breeder" <contactus@careerbreeder.com>';
	                $headers = 'From: '.$from . "\r\n
                    List-Unsubscribe: <mailto:contactus@careerbreeder.com>, <http://careerbreeder.com/member/unsubscribe/?listname=espc-tech@domain.com?id=12345N>";
                    // Always set content-type when sending HTML email
                    $headers .= "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";


	                $subject = "IQ Test at Career Breeder";
       	             $msg = "Thank you for giving IQ test at Career Breeder.\n".cbquiz_results($ref_no);
	                wp_mail( $email, $subject, $msg, $headers );
                    $response .= "$email, $subject, $msg, $headers";
                    $response .= "Your results are mailed to you. Kindly check your email.";
//                    $response. = <div id="success_cont"><a href="'.site_url("show-me-the-results").'?test_id=3" title="test success continue" class="cont_btn">Continue</a></div>              
                }else {
                    $response .= "<h2>Thank you for registering with us.</h2>";
                    $response .= "<br/><h2><a href=\"".site_url("order")."?ref_no=$ref_no&test_id=2\">Click here to view your result</a></h2>";
                }
                return $response;
}
add_shortcode("login_after_test_helper","login_after_test_helper");
?>