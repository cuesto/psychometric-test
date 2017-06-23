<?php

//Set the test_id
$test_id = $_GET['test_id'];
if (!isset($test_id))
{
    if(isset($_SESSION['test_id']))
        $test_id = $_SESSION['test_id'];
    else {
        //redirect the user to homepage
    }
}

/**
 * Do preliminary test of Career Indicator to start the test
 */
$prelimTestFailed = false;
if(!is_user_logged_in() && $test_id == 17)
{
    $prelimTestFailed = true;
} else if($test_id == 17){
    $uid = get_current_user_id();
    $user_info = get_userdata($uid);
    if($user_info->degree_id == 0)
        $prelimTestFailed = true;
    else if($user_info->contact_number == '')
        $prelimTestFailed = true;
    else if(!$user_info->product>0)    
        $prelimTestFailed = true;
}

if($prelimTestFailed == true)
{
    echo '<h2>OOOPPPPPPSSSSS something went wrong. Please <a href="'.site_url('career-counseling').'">click here</a> to go back.</h2>';
    exit();
}


    
/**
 * If user has already submitted the test
 */
if(isset($_GET['ref_no']))
{
    $ref_no = $_GET['ref_no'];
}

if (isset($ref_no) && is_user_logged_in())
{
    global $current_user;
    get_currentuserinfo();
    global $wpdb;

    //update the user's uid in ref_no
    $result_master = array('ref_no' => $ref_no, 'uid' => $current_user->ID, );
    $table_name = $wpdb->prefix . "cb_result_master";
    $wpdb->update($table_name, $result_master, array('ref_no' => $result_master['ref_no']));
}


//Load the test
global $wpdb;
$table_name = $wpdb->prefix . "cb_test";
$test = $wpdb->get_results("SELECT cache FROM $table_name WHERE test_id = $test_id");

//All values are Index 0 of $test
$test = $test[0]->cache;

//Decode the json values
$test = json_decode($test);


//If requested test doesn't exist, then redirect the user to homepage
if(!isset($test->test_id) || empty($test->test_id))
{
    //redirect the user to homepage
}


//Set the page navigation based on test's duration
$pageNavigation = true;
if ($test->duration > 0)
    $pageNavigation = false;

$sections = $test->sections;


// Create the form page
$i = 1;
foreach ($sections as $section)
{
    $jFormPage = 'jFormPage' . $i;
    $$jFormPage = new JFormPage('page' . $section->section_id, array('title' => $section->
        name));

    $jFormSection = 'jFormSection' . $i;
    $$jFormSection = new JFormSection('election' . $section->section_id, array());
    
    $questions = $section->questions;
    
    foreach ($questions as $question)
    {
        $options = $question->option_array;
        $j=0;
        foreach($options as $option)
        {
            $option_array[$j]['value'] = $option->value;
            $option_array[$j]['label'] = $option->label;
            $j++;
        }

        $required = array(
            'multipleChoiceType' => 'radio',
            'validationOptions' => array('required'),
            );

        if ($pageNavigation == false)
            $required = array('multipleChoiceType' => 'radio');

        $question_name = str_replace('\\', '', $question->question);
        $path = get_bloginfo('wpurl') . "/wp-content/uploads/cb-test/" . $test_id . $question->
            question_id . "q.jpg";
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/wp-content/uploads/cb-test/" . $test_id .
            $question->question_id . "q.jpg"))
        {
            $question_name .= "<img src=\"$path\" />";
        }

        $$jFormSection->addJFormComponentArray(array(new JFormComponentMultipleChoice('question' .
            $question->question_id, $question_name, $option_array, $required)));


    }
    
    /**
     * Only in Career Indicator
     * -> Load interest based 3 questions
     */
    if ($test_id == 17 && $i == 5)
    {
        $careers = $wpdb->get_results("SELECT career_id, name, category FROM wp_cb_careers WHERE code != '' ORDER BY category");
        $option_array = array();
        
        array_push($option_array, array(
                    'value' =>'', 
                    'label' => ' - Select - ',
                    'disabled' => true,
                    'selected' => true));  



        foreach ($careers as $career)
        {
            $career_name = $career->category." - ".substr($career->name, 0, -1);
            array_push($option_array, array('value' => $career->career_id, 'label' => $career_name ));
        }


        $dropDown = new JFormComponentDropDown('question1001', 'Choose your first dream occupation:', 
                $option_array,             array('tip' =>
            '<p></p>',
            'validationOptions' => array('required'),
            ));
        $$jFormSection->addJFormComponentArray(array($dropDown));

        $dropDown = new JFormComponentDropDown('question1002', 'Choose your second dream occupation:', 
                $option_array,             array('tip' =>
            '<p></p>',
            'validationOptions' => array('required'),
            ));
        $$jFormSection->addJFormComponentArray(array($dropDown));

        $dropDown = new JFormComponentDropDown('question1003', 'Choose your third dream occupation:', 
                $option_array,             array('tip' =>
            '<p></p>',
            'validationOptions' => array('required'),
            ));

        $$jFormSection->addJFormComponentArray(array($dropDown));

        // Add the section to the page
//        $$jFormPage->addJFormSection($$jFormSection);
    }
    $i++;

    // Add the section to the page
    $$jFormPage->addJFormSection($$jFormSection);
    // Add the page to the form
    $form->addJFormPage($$jFormPage, $section->duration . "&" . $section->section_id);
}

$form->addJFormComponentArray(Array(new JFormComponentHidden('test_id', '17')));


/**
 *  Process any request to the form
 *  Set the function for a successful form submission
 */
function onSubmit($formValues)
{
    if($formValues->page16->page16_section2->test_id==17)
        $test_id = 17;
    else
        $test_id = 15;

    global $wpdb;
    $CBConnect = new CB_Connect;

    $table_name = $wpdb->prefix . "cb_section";
    $sections = $wpdb->get_results("SELECT * FROM $table_name WHERE test_id = $test_id ORDER BY section_no ASC");

    //code to generate reference number
    /**
     * Testid + month year + unique_id
     */
    $table_name = $wpdb->prefix . "cb_result_master";
    $result_id = $wpdb->get_results("SELECT result_id FROM $table_name ORDER BY result_id DESC LIMIT 1");
    $refNo = $test_id . date('my') . '000' . ($result_id[0]->result_id + 1);


    $results = array();    
    $i=0;
    
    foreach ($sections as $section)
    {
        $pageName = 'page' . $section->section_id;
        $sectionName = 'election' . $section->section_id;

        $table_name = $wpdb->prefix . 'cb_questions';
        $questions = $wpdb->get_results("SELECT * FROM $table_name WHERE section_id =" .
            $section->section_id . " ORDER BY question_no ASC");
        foreach ($questions as $question)
        {
            $questionName = 'question' . $question->question_id;
            $a = $formValues->$pageName->$sectionName->$questionName;
            $results[] = array('option_id' => $a, 'question_id' => $question->question_id);
        }
        
        if($test_id==17 && $i==4 )
        {
            for($k=1;$k<4;$k++) { 
                $questionName = 'question100'.$k;
                $a = $formValues->$pageName->$sectionName->$questionName;
                $question_id = '100'.$k;
                
                $results[] = array('option_id' => $a, 'question_id' => $question_id);
            }
              
       }
        $i++;
    }

    $result_master = array('ref_no' => $refNo, 'test_id' => $test_id, 'result' =>
        json_encode($results), 'uid' => get_current_user_id(), );

    $table_name = $wpdb->prefix . "cb_result_master";
    $result_id = $wpdb->insert($table_name, $result_master);

    //test cost
    $table_name1 = $wpdb->prefix . "cb_test";
    $table_name2 = $wpdb->prefix . "cb_result_master";
    $cb_test = $wpdb->get_results("SELECT cost,name FROM `$table_name1` WHERE $table_name1.test_id = $test_id");
    $test_name = $cb_test[0]->name;
    
    if($test_id==17)
        $amount = 499;
    else
        $amount = 0;


    $args = array('echo' => false, 'redirect' => site_url() . "/view-results",
        'form_id' => 'loginform', 'label_username' => __('Username'), 'label_password' =>
        __('Password'), 'label_remember' => __('Remember Me'), 'label_log_in' => __('Log In'),
        'id_username' => 'user_login', 'id_password' => 'user_pass', 'id_remember' =>
        'rememberme', 'id_submit' => 'wp-submit', 'remember' => true, 'value_username' => null,
        'value_remember' => false);


    $_SESSION['ref_no'] = $refNo;
    $response .= "<h2 class=\"black\">Assessment successfully completed.</h2>
                    <h1 class=\"test_title1\" id=\"uin_no\"><a>Your Unique Reference Number (URN) is: $refNo</a></h1>
                	<h2 class=\"fcm_txt1\" id=\"uin_header1\"><a>Please note down this and keep it safe.</a></h2>
                
                <div class=\"clear\"></div>";
    if (is_user_logged_in() && $amount < 1)
    {
        include('../../cb-test/cbquiz_result.php');           
        include('/wp-admin/plugins/cb-test/cbquiz_result.php');           

        //update the user's uid in ref_no
        $result_master = array('ref_no' => $refNo, 'uid' => get_current_user_id(), );
        $table_name = $wpdb->prefix . "cb_result_master";
        $wpdb->update($table_name, $result_master, array('ref_no' => $result_master['ref_no']));

        //mail results to the user.
        $current_user = wp_get_current_user();
        $email = $current_user->user_email;
        $firstname = $CBConnect->get_first_name();
        
        $from = '"Career Breeder" <contactus@careerbreeder.com>';
        $headers = 'From: ' . $from . "\r\n
                    List-Unsubscribe: <mailto:contactus@careerbreeder.com>, <http://careerbreeder.com/member/unsubscribe/?listname=espc-tech@domain.com?id=12345N>";
        // Always set content-type when sending HTML email
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

        $email_footer=$CBConnect->get_email_footer();
        
        
        $subject = "Test Results at Career Breeder";
        $msg = "Dear $firstname, <br/><br/> Thank you for giving test at <a href=\"http://careerbreeder.com\">Career Breeder</a>.\n" . cbquiz_results($refNo).$email_footer;

        wp_mail($email, $subject, $msg, $headers);

        $response .= "<div id=\"success_cont\">Your report is emailed to you.</div>";
    }
    else
        if (is_user_logged_in())
        {
            $uid = get_current_user_id();
            $user_info = get_userdata($uid);
            $name = $CBConnect->get_first_name();
            

            //update the user's uid in ref_no
            $result_master = array('ref_no' => $refNo, 'uid' => get_current_user_id(), );
            $table_name = $wpdb->prefix . "cb_result_master";
            $wpdb->update($table_name, $result_master, array('ref_no' => $result_master['ref_no']));

                
                
            //mail results to the user.
            $from = '"Career Breeder" <contactus@careerbreeder.com>';
            $headers = 'From: '.$from . "\r\n
            List-Unsubscribe: <mailto:contactus@careerbreeder.com>, <http://careerbreeder.com/member/unsubscribe/?listname=espc-tech@domain.com?id=12345N>";
            // Always set content-type when sending HTML email
            $headers .= "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
            
            $subject = "Career Indicator at Career Breeder";

            $message = "Greetings " . $name . ",<br/>
                <br/>
                   Thank you for taking <a href=\"http://careerbreeder.com/career-indicator/\">Career Indicator Assessment</a>. Through this assessment you can assess your career choices against your personality, aptitude and interests.<br/>
                 <br/>
                    " . $name .
                ", You can <strong><a href=\"".site_url('order')."?ref_no=" .
                $refNo . "&test_id=2&affiliates=$campaignID \">get your full report over here</a></strong>.<br/>
                 <br/>
                 
                 <a href=\"".site_url('view-results')."?ref_no=".$refNo."&test_id=2\">View your sample report.</a><br/> 
                 <br/>".$CBConnect->get_email_footer();

            wp_mail( $user_info->user_email, $subject, $message, $headers );
            
            
            
            
                //tell site admin about it
                $from = '"Career Breeder" <contactus@careerbreeder.com>';
                $headers = 'From: '.$from . "\r\n
                List-Unsubscribe: <mailto:contactus@careerbreeder.com>, <http://careerbreeder.com/member/unsubscribe/?listname=espc-tech@domain.com?id=12345N>";
                // Always set content-type when sending HTML email
                $headers .= "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

                $subject = "One user appeared for Career Indicator Assessment";
                $msg = "Hi Admin, <br/><br/>Reference Number: $refNo <br/>
                User ID: $uid <br />
                Name: $name <br />
                Email: ".$user_info->user_email."<br />
                Contact number: ".$user_info->contact_number." <br />
                Degree: ".$user_info->degree." <br />
                Product: ".$user_info->product." <br />
                Report: <a href=\"".site_url('view-results')."?ref_no=" .$refNo. "&test_id=2&affiliates=$campaignID \">View your sample report</a>
                <b>Call them up</b>".$CBConnect->get_email_footer();
                                 
                wp_mail( "contactus@careerbreeder.com", $subject, $msg, $headers );


                
            $response .= "<a href=\"" . site_url("order") ."?test_id=2&ref_no=$refNo\" title=\"test success continue\">Pay and view full report</a><br />";
            $response.= "<br /><a href=\"".site_url('view-results')."?ref_no=" .$refNo. ".&test_id=2\">View your sample report</a>";
        }
        else
        {
            $response .= "<div id=\"success_cont\"><a href=\"" . site_url("show-me-the-results") .
                "?test_id=3&ref_no=$refNo" . "\" title=\"test success continue\" class=\"cont_btn\">Continue</a></div>";
        }
        $response .= "
            </div>
        
        </div><!--tst_hdrmain ends -->";

    return array('successPageHtml' => $response);
}

?>