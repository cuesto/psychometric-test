<?php
$test_id = $_GET['test_id'];
if(!isset($test_id))
    $test_id = $_SESSION['test_id'];
$test_id-=1000;
DEFINE('NUMBER_OF_QUESTIONS',2);
global $wpdb;
$table_name = $wpdb->prefix."cb_test";
$test = $wpdb->get_results("SELECT * FROM $table_name WHERE test_id = $test_id");
$table_name = $wpdb->prefix."cb_questions";
$questions = $wpdb->get_results("SELECT * FROM $table_name WHERE test_id = $test_id ORDER BY question_no ASC");
$pageNavigation = true;
if($test[0]->duration>0)
    $pageNavigation = false;
$pageNavigation = true;

// Create the form page
$i=1;
$section_no = 0;
$question_counter=0;
//so the initial questions won't miss out
$jFormPage = 'jFormPage'.$i;
$$jFormPage = new JFormPage('page'.$section_no, array(
        'title' => ($section_no+1)
));

$jFormSection = 'jFormSection'.$i;
$$jFormSection = new JFormSection('election' . $section_no, array(
    ));


foreach($questions as $question)
{
    $question_counter++;
    if($question_counter== NUMBER_OF_QUESTIONS)
    {
        $$jFormPage->addJFormSection($$jFormSection);
        // Add the page to the form
        $form->addJFormPage($$jFormPage,$test[0]->duration."&".$section_no);

        $jFormPage = 'jFormPage'.$i;
        $$jFormPage = new JFormPage('page'.$section_no, array(
                'title' => ($section_no+1)
        ));
        
        $jFormSection = 'jFormSection'.$i;
        $$jFormSection = new JFormSection('election' . $section_no, array(
            ));
        $section_no++;
        $question_counter=0;
    }
    
//    $table_name= $wpdb->prefix.'cb_questions';
//    $questions = $wpdb->get_results("SELECT * FROM $table_name WHERE section_id =".$section->section_id." ORDER BY question_no ASC");
    
    $option_array =Array();
    
//    foreach($questions as $question)
//    {
        $table_name= $wpdb->prefix.'cb_option';
        $options = $wpdb->get_results("SELECT * FROM $table_name WHERE question_id =".$question->question_id." ORDER BY question_no ASC");
        
        $option_array = Array();
        
        foreach($options as $option)
        {
            $option_value = str_replace('\\','',$option->name);
            $path = get_bloginfo('wpurl')."/wp-content/uploads/cb-test/".$test_id.$question->question_id.$option->option_id.".jpg";
            if(file_exists($_SERVER['DOCUMENT_ROOT']."/wp-content/uploads/cb-test/".$test_id.$question->question_id.$option->option_id.".jpg"))
            {
                $option_value .= "<img src=\"$path\" />";
            }
            array_push(
                $option_array,
                array('value' => $option->option_id , 'label' => $option_value)
            );
        }    

            $required = array(
                'multipleChoiceType' => 'radio',
                'validationOptions' => array('required'), );
            
            if($pageNavigation==false)
                $required = array('multipleChoiceType' => 'radio');
                
            $question_name = str_replace('\\','',$question->question);
            $path = get_bloginfo('wpurl')."/wp-content/uploads/cb-test/".$test_id.$question->question_id."q.jpg";
            if(file_exists($_SERVER['DOCUMENT_ROOT']."/wp-content/uploads/cb-test/".$test_id.$question->question_id."q.jpg"))
            {
                $question_name .= "<img src=\"$path\" />";
            }

    $$jFormSection->addJFormComponentArray(Array(
        new JFormComponentMultipleChoice('question'.$question->question_id, $question_name, $option_array,
                    $required
            )
            ));
            

//    }
    $i++;
// Add the section to the page
//$form->addJFormPage($$jFormPage);
    
}

// Process any request to the form
// Set the function for a successful form submission
	function onSubmit($formValues)
	{
        $test_id = $_SESSION['test_id'];
        $test_id-=1000;
        global $wpdb;
        $table_name = $wpdb->prefix."cb_section";
        $sections = $wpdb->get_results("SELECT * FROM $table_name WHERE test_id = $test_id ORDER BY section_no ASC");
        
        //code to generate reference number
        /**
         * Testid + month year + unique_id
         */
         $table_name = $wpdb->prefix."cb_result_master";
         $result_id = $wpdb->get_results("SELECT result_id FROM $table_name ORDER BY result_id DESC LIMIT 1");
         $refNo = $test_id.date('my').'000'.($result_id[0]->result_id+1);
         
         
        
        $results = Array();
//        $questionName=0;
        foreach($sections as $section)
        {
            $pageName = 'page'.$section->section_id;
            $sectionName = 'election'.$section->section_id;

            $table_name= $wpdb->prefix.'cb_questions';
            $questions = $wpdb->get_results("SELECT * FROM $table_name WHERE section_id =".$section->section_id." ORDER BY question_no ASC");
            foreach($questions as $question)
            {
                $questionName= 'question'.$question->question_id;
                $a = $formValues->$pageName->$sectionName->$questionName;
                $results[] = Array(
                    'option_id' => $a,
                    'question_id' => $question->question_id
                );
           }
        }

         $result_master = Array(
            'ref_no' => $refNo,
            'test_id' => $test_id,
            'result' => json_encode($results),
            'uid' => get_current_user_id(),
         );
         
         $table_name = $wpdb->prefix."cb_result_master";         
         $result_id = $wpdb->insert($table_name,$result_master);
         
         //test cost
        $table_name1 = $wpdb->prefix."cb_test";
        $table_name2 = $wpdb->prefix."cb_result_master";
        $cb_test = $wpdb->get_results("SELECT cost,name FROM `$table_name1` WHERE $table_name1.test_id = $test_id");
        $amount = $cb_test[0]->cost;
        $test_name = $cb_test[0]->name;

         
         $args = array(
        'echo' => false,
        'redirect' => site_url()."/view-results", 
        'form_id' => 'loginform',
        'label_username' => __( 'Username' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in' => __( 'Log In' ),
        'id_username' => 'user_login',
        'id_password' => 'user_pass',
        'id_remember' => 'rememberme',
        'id_submit' => 'wp-submit',
        'remember' => true,
        'value_username' => NULL,
        'value_remember' => false );
        
        
        $_SESSION['ref_no'] = $refNo;
         
		$response= "<div id=\"main_content_wrapper\">
  
  <div id=\"left_sidebarwrap\">
  
   	<div class=\"tst_hdrimg\">
    	<img src=\"images/tst-dscrption/apt-dscr-img.jpg\" alt=\"aptitd tst dscription imag\" title=\"aptitd tst img\" width=\"192\" height=\"295\">
    </div>
    
    <!--sidbar -->
    
    	<div class=\"tstcnt_sidbar\">
        	<div id=\"sid_head\" class=\"classi_txt2\">You can also try more
            
            	<ul>
                	<li><a href=\"#\">Personality test</a></li>
                    <li><a href=\"#\">Aptitude test</a></li>
                    <li><a href=\"#\">IQ test</a></li>
                    <li><a href=\"#\">Intellectual test</a></li>
                </ul>
            
            </div>
            
        </div>
    <!--sidbar -->
    
    </div><!--left sidebar wrap ends -->
    
    
    <div id=\"rightmaincont_sect\">
    
    	<div class=\"tst_hdrmain\">
        	<div class=\"tst_hdrmainlft\">
            	<div class=\"tst_title\">
                	<span class=\"test_title1\">$test_name</span>
                </div>
                
                <!--test completion icon -->
                
                	<div id=\"test_complicon\" class=\"testcomplicon\"></div>
                
                <!--test completion icon ends -->
                
                <div class=\"clear\"></div>
                
                <div class=\"tst_classification\">
                	<span class=\"classi_txt1\">Recommended&nbsp;</span>
                    <span class=\"classi_txt2\">(Premium Test)</span>
                </div>
                
                <div class=\"clear\"></div>
                
                <div id=\"finish_confirmmsg\">
                	<span class=\"fcm_txt1\"><a>Good Work!</a></span>
                    <span class=\"fcm_txt2\"><a>You have succesfully finished the test.</a></span>
                </div>
                
                <div class=\"clear\"></div>
               
                <!--social ntwork -->
                
<div id=\"social_ntwork\">
                    	<div class=\"classi_txt1\">Tell your friends that you just got your career certified.</div>
              <div class=\"clear\"></div>
                       <!--social ntwork -->
                

                    	

<a href=\"#\" title=\"Facebook share\"><li id=\"sn_facebook\" class=\"sn_icon\"></li></a>
<a href=\"#\" title=\"Google Plus Share\"><li id=\"sn_googleplus\" class=\"sn_icon\"></li></a>
<a href=\"#\" title=\"Linkedin Share\"><li id=\"sn_linkedin\" class=\"sn_icon\"></li></a>
<a href=\"#\" title=\"twitter share\"><li id=\"sn_twitter\" class=\"sn_icon\"></li></a>



                
               
                        
              </div>
                
                <!--social ntwork -->
    
            </div>
            
            <div class=\"tst_hdrmainrgt\">
            	
                <div id=\"uin\">
                	<span class=\"fcm_txt1\" id=\"uin_header1\"><a>Please note down this and<br /> Keep it safe and saved.</a></span>
                    <span class=\"classi_txt1\" id=\"uin_header2\"><a>We generated a Unique Reference Number for the test you completed.<br />
Your Unique Reference Number (URN) is:</a></span>
<span class=\"test_title1\" id=\"uin_no\"><a>$refNo</a></span>
                </div>
                
                <div class=\"clear\"></div>";
                if(is_user_logged_in() && $amount <1)
                {
                    //mail the user
                    $response.= "Your report has been mailed to you.";                    
                } 
                else if(is_user_logged_in())
                {
                    $response.= "<a href=\"".site_url("order")."?test_id=2\">Click here to view your report.</a>";                    
                } else {
                    $response.= "<div id=\"success_cont\"><a href=\"".site_url("show-me-the-results")."?test_id=3"."\" title=\"test success continue\" class=\"cont_btn\"></a></div>";
                }
                    $response .= "
            </div>
        
        </div><!--tst_hdrmain ends -->
        
         <!--tst main contnt -->
    
    	<!--div class=\"tstcntnt_main\">
        
        	<login section starts -->	
            
            	<!--div id=\"loginreq\">
                    
                    <div class=\"clear\"></div>
                    
                    <div id=\"login_formwrap\">
                    <div id=\"login_msg\">";
                    if(is_user_logged_in())
                    {
                        $response.= "<a href=\"".site_url()."/view-results\">Click here to view the results online</a>
                        <a href=\"#\">Click here to download the results</a></div>";
                    } else {
                        $response.="<span class=\"fcm_txt1\"><a>Please provide us your identification</a></span>
                        <span class=\"classi_txt2\" id=\"login_notify\"><a>You need to login or Register to continue further.</a></span>
                    </div>
                        <div id=\"login_form\">".wp_login_form($args)."<div class=\"clear\"></div>
                            <span class=\"classi_txt1\" id=\"frgt_passwrd\"><a href=\"#\" title=\"Forgot Password\">Forgot Password?</a></span>
                            <span class=\"classi_txt1\" id=\"reg_now\"><a href=\"".site_url()."/register\" title=\"Register with CB\">Register Now</a></span>
                        </div>";
                    }
                    $response.= "</div>
                </div>
            
            <!--login section ends -->
        
        </div-->
    <!--tst main cont nds -->
        
    </div>
  </div><!--tst hdr div ends -->";
       //  $response .= wp_login_form($args);
//         $response .= do_shortcode('[register]');
		return array(
		'successPageHtml' => $response );//, 'successJs' => 'setTimeout("self.close();",2000);');
	}

?>