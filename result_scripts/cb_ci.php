<?php

function cb_ci($ref_no)
{
    include ('wp-content/plugins/dompdf/dompdf_config.inc.php');
    include_once ('cb_ci_answers.php');
    include_once ('Barcode39.php');
    include_once ('drawPie.php');
    include_once ('drawBar1.php');
    include_once ('drawBar2.php');
    include_once ('drawHor1.php');
    include_once ('drawHor2.php');
    

    //fetch user's answers and store in an array
    global $wpdb;
    $table_name = $wpdb->prefix . "cb_result_master";
    $answersTicked = $wpdb->get_results("SELECT * FROM $table_name WHERE ref_no =$ref_no");
    $result = json_decode($answersTicked[0]->result);

    //PAYMENT FLAG HERE
     $payFlag=$answersTicked[0]->payment; 


    $user_info = get_userdata($answersTicked[0]->uid);
    $repDate = $answersTicked[0]->time; //date("Y-m-d");

    //user details
    if (isset($user_info->display_name))
        $first_name .= $user_info->display_name;
    else
        if (isset($user_info->user_nicename))
            $first_name .= $user_info->user_nicename;
        else
            if (isset($user_info->nickname))
                $first_name .= $user_info->nickname;
            else
                if (isset($user_info->name))
                    $first_name .= $user_info->name;
                else
                    if (isset($user_info->user_email))
                        $first_name .= $user_info->user_email;

    if (isset($user_info->contact_number))
        $contact_number .= $user_info->contact_number;

    $q = 0;
    //map answers and give marks;
    for ($i = 0; $i < sizeof($result); $i++)
    {
        if ($result[$i]->question_id < 1001)
        { //find the question number
            $question_id = $result[$i]->question_id;
            $table_name = $wpdb->prefix . "cb_questions";
            $question_no = $wpdb->get_results("SELECT * FROM $table_name WHERE question_id = $question_id");
            $section_id = $question_no[0]->section_id;

            $question_no = $question_no[0]->question_no;

            //fetch option number
            $option_id = $result[$i]->option_id;
            $table_name = $wpdb->prefix . "cb_option";
            $option_no = $wpdb->get_results("SELECT * FROM $table_name WHERE option_id = $option_id");
            $option_no = $option_no[0]->question_no;

            //get section number
            $table_name = $wpdb->prefix . "cb_section";
            $section_no = $wpdb->get_results("SELECT * FROM $table_name WHERE section_id = $section_id");
            $section_no = $section_no[0]->section_no;
            //echo $section_no."<br/>";


            /**
             * Separate Question Index and Section Index from the given question number
             * Section Index is zero index but question index is 1 index
             */

            $secQuesNum = getSecQuesNum($question_no);
            $user_answers[$i]['question_no'] = $secQuesNum['qNo'];
            $user_answers[$i]['factor_no'] = $secQuesNum['sNo'];
            $user_answers[$i]['choice'] = $option_no;
        }
        else
        {
            $career_id = $result[$i]->option_id;
            $table_name = $wpdb->prefix . "cb_careers";
            $career_details[$q] = $wpdb->get_results("SELECT * FROM $table_name WHERE career_id = $career_id");
            
            $career_details[$q] = $career_details[$q][0];

            $q++;
        }
    }




    for ($i = 0; $i < 19; $i++)
    {
        $question_no = globalQuesNo($solution_array['CP'][$i]['factor_no'], $solution_array['CP'][$i]['question_no']);
        if ($user_answers[$question_no]['choice'] == 0)
        {
            if ($solution_array['CP'][$i]['first_choice'] == 'P')
            {
                $res['CP']['P']++;
            }
            else
                if ($solution_array['CP'][$i]['first_choice'] == 'C')
                {
                    $res['CP']['C']++;
                }
                else
                {
                    echo "Something is wrong $section_no , $question_no,";
                }
        }
        else
            if ($user_answers[$question_no]['choice'] == 1)
            {
                if ($solution_array['CP'][$i]['first_choice'] == 'P')
                {
                    $res['CP']['C']++;
                }
                else
                    if ($solution_array['CP'][$i]['first_choice'] == 'C')
                    {
                        $res['CP']['P']++;
                    }
                    else
                    {
                        echo "Something is wrong $section_no , $question_no,";
                    }
            }
    }

    for ($i = 0; $i < 19; $i++)
    {
        $question_no = globalQuesNo($solution_array['DM'][$i]['factor_no'], $solution_array['DM'][$i]['question_no']);
        if ($user_answers[$question_no]['choice'] == 0)
        {
            if ($solution_array['DM'][$i]['first_choice'] == 'M')
            {
                $res['DM']['M']++;
            }
            else
                if ($solution_array['DM'][$i]['first_choice'] == 'D')
                {
                    $res['DM']['D']++;
                }
                else
                {
                    echo "Something is wrong DM$section_no , $question_no,";
                }
        }
        else
            if ($user_answers[$question_no]['choice'] == 1)
            {
                if ($solution_array['DM'][$i]['first_choice'] == 'M')
                {
                    $res['DM']['D']++;
                }
                else
                    if ($solution_array['DM'][$i]['first_choice'] == 'D')
                    {
                        $res['DM']['M']++;
                    }
                    else
                    {
                        echo "Something is wrong DM$section_no , $question_no,";
                    }
            }
    }

    for ($i = 0; $i < 19; $i++)
    {
        $question_no = globalQuesNo($solution_array['EI'][$i]['factor_no'], $solution_array['EI'][$i]['question_no']);
        if ($user_answers[$question_no]['choice'] == 0)
        {
            if ($solution_array['EI'][$i]['first_choice'] == 'I')
            {
                $res['EI']['I']++;
            }
            else
                if ($solution_array['EI'][$i]['first_choice'] == 'E')
                {
                    $res['EI']['E']++;
                }
                else
                {
                    echo "Something is wrong EI$section_no , $question_no,";
                }
        }
        else
            if ($user_answers[$question_no]['choice'] == 1)
            {
                if ($solution_array['EI'][$i]['first_choice'] == 'I')
                {
                    $res['EI']['E']++;
                }
                else
                    if ($solution_array['EI'][$i]['first_choice'] == 'E')
                    {
                        $res['EI']['I']++;
                    }
                    else
                    {
                        echo "Something is wrong EI$section_no , $question_no,";
                    }
            }
    }

    for ($i = 0; $i < 13; $i++)
    {
        $question_no = globalQuesNo($solution_array['QS'][$i]['factor_no'], $solution_array['QS'][$i]['question_no']);
        if ($user_answers[$question_no]['choice'] == 0)
        {
            if ($solution_array['QS'][$i]['first_choice'] == 'S')
            {
                $res['QS']['S']++;
            }
            else
                if ($solution_array['QS'][$i]['first_choice'] == 'Q')
                {
                    $res['QS']['Q']++;
                }
                else
                {
                    echo "Something is wrongQS $section_no , $question_no,";
                }
        }
        else
            if ($user_answers[$question_no]['choice'] == 1)
            {
                if ($solution_array['QS'][$i]['first_choice'] == 'S')
                {
                    $res['QS']['Q']++;
                }
                else
                    if ($solution_array['QS'][$i]['first_choice'] == 'Q')
                    {
                        $res['QS']['S']++;
                    }
                    else
                    {
                        echo "Something is wrong QS$section_no , $question_no,";
                    }
            }
    }

    for ($i = 0; $i < 19; $i++)
    {
        $question_no = globalQuesNo($solution_array['RF'][$i]['factor_no'], $solution_array['RF'][$i]['question_no']);
        if ($user_answers[$question_no]['choice'] == 0)
        {
            if ($solution_array['RF'][$i]['first_choice'] == 'F')
            {
                $res['RF']['F']++;
            }
            else
                if ($solution_array['RF'][$i]['first_choice'] == 'R')
                {
                    $res['RF']['R']++;
                }
                else
                {
                    echo "Something is wrong RF$section_no , $question_no,";
                }
        }
        else
            if ($user_answers[$question_no]['choice'] == 1)
            {
                if ($solution_array['RF'][$i]['first_choice'] == 'F')
                {
                    $res['RF']['R']++;
                }
                else
                    if ($solution_array['RF'][$i]['first_choice'] == 'R')
                    {
                        $res['RF']['F']++;
                    }
                    else
                    {
                        echo "Something is wrong RF$section_no , $question_no,";
                    }
            }
    }

    $aptitude_code_names = array(0 => 'BC', 1 => 'PS', 2 => 'CS', 3 => 'D', 4 => 'C',
        5 => 'EM', 6 => 'FL', 7 => 'SM', 8 => 'TM', );


    for ($j = 0; $j < 9; $j++)
    {
        $res[$aptitude_code_names[$j]] = 0;
        for ($i = 0; $i < sizeof($solution_array[$aptitude_code_names[$j]]); $i++)
        {
            $question_no = globalQuesNo($solution_array[$aptitude_code_names[$j]][$i]['factor_no'],
                $solution_array[$aptitude_code_names[$j]][$i]['question_no']);
            if ($user_answers[$question_no]['choice'] == 0)
            {
                if ($solution_array[$aptitude_code_names[$j]][$i]['first_choice'] == 'Y')
                {
                    $res[$aptitude_code_names[$j]]++;
                }
                else
                    if ($solution_array[$aptitude_code_names[$j]][$i]['first_choice'] == 'N')
                        ;
                    else
                    {
                        echo "Something is wrong " . $aptitude_code_names[$j];
                    }
            }
            else
                if ($user_answers[$question_no]['choice'] == 1)
                {
                    if ($solution_array[$aptitude_code_names[$j]][$i]['first_choice'] == 'N')
                    {
                        $res[$aptitude_code_names[$j]]++;
                    }
                    else
                        if ($solution_array[$aptitude_code_names[$j]][$i]['first_choice'] == 'Y')
                            ;
                        else
                        {
                            echo "Something is wrong " . $aptitude_code_names[$j];
                        }
                }
        }
    }

    if ($res['CP']['P'] > $res['CP']['C'])
        $code[] = "P";
    else
        $code[] = "C";

    if ($res['DM']['D'] > $res['DM']['M'])
        $code[] = "D";
    else
        $code[] = "M";

    if ($res['EI']['E'] > $res['EI']['I'])
        $code[] = "E";
    else
        $code[] = "I";

    if ($res['QS']['Q'] > $res['QS']['S'])
        $code[] = "Q";
    else
        $code[] = "S";

    if ($res['RF']['R'] > $res['RF']['F'])
        $code[] = "R";
    else
        $code[] = "F";
    $compressedCode = implode("", $code);


    //Characterostics and suggestions
    $result1[0]['a'] = $res['CP']['P'];
    $result1[0]['b'] = $res['CP']['C'];
    $result1[1]['a'] = $res['DM']['D'];
    $result1[1]['b'] = $res['DM']['M'];
    $result1[2]['a'] = $res['EI']['E'];
    $result1[2]['b'] = $res['EI']['I'];
    $result1[3]['a'] = $res['QS']['Q'];
    $result1[3]['b'] = $res['QS']['S'];
    $result1[4]['a'] = $res['RF']['R'];
    $result1[4]['b'] = $res['RF']['F'];


    for ($i = 0; $i < 5; $i++)
    {
        $ratio[$i] = ($result1[$i]['a'] / ($result1[$i]['a'] + $result1[$i]['b']));
        if ($ratio[$i] > 0.5)
            $ratio[$i] = 1 - $ratio[$i];
    }
    asort($ratio);
    $ratio_temp = $ratio;
    $i = 0;

    foreach ($ratio as $rat)
    {
        $index = array_keys($ratio_temp, $rat);
        if ($i > 0 && $ratio_temp[$index[0]] > 0.35)
            break;
        $ratio_temp[$index[0]] = 0.5;
        $a = cb_ci_characteristic($code[$index[0]]);


        $CS_image[$i] = $a['image'];
        $CS_title[$i] = $a['title'];
        $CS_characteristic[$i] = $a['characteristic'];
        $CS_suggestion[$i] = $a['suggestion'];

        $i++;
    }


    /**
     * Suggested career options
     */
    $table_name = $wpdb->prefix . "cb_careers";
    $careers = $wpdb->get_results("SELECT * FROM $table_name WHERE code  like '%" .
        $compressedCode . "%' ORDER BY cat_level ASC ");

    $k = 1;
    for ($j = 0; $j < min(4, sizeof($careers)); $j++)
    {
        $car[$j]['title'] = $careers[$j]->name;
        $car[$j]['category'] = $careers[$j]->category;
        $car[$j]['description'] = $careers[$j]->description;
        $car[$j]['work'] = $careers[$j]->work;
        $car[$j]['requirements'] = $careers[$j]->requirements;
        $car[$j]['title_examples'] = $careers[$j]->title_examples;
        $car[$j]['degrees_associated_with_this_career'] = $careers[$j]->
            degrees_associated_with_this_career;
        $car[$j]['useful_secondary_school_subjects'] = $careers[$j]->
            useful_secondary_school_subjects;
        $car[$j]['employers'] = $careers[$j]->employers;
        $car[$j]['image'] = $careers[$j]->image;
    }

    $val11 = floor($res['BC'] / sizeof($solution_array['BC']) * 100);
    $val12 = floor($res['PS'] / sizeof($solution_array['PS']) * 100);
    $val13 = floor($res['CS'] / sizeof($solution_array['CS']) * 100);
    $val14 = floor($res['D'] / sizeof($solution_array['D']) * 100);

    $val15 = floor($res['C'] / sizeof($solution_array['C']) * 100);
    $val16 = floor($res['EM'] / sizeof($solution_array['EM']) * 100);
    $val17 = floor($res['FL'] / sizeof($solution_array['FL']) * 100);
    $val18 = floor($res['SM'] / sizeof($solution_array['SM']) * 100);
    $val19 = floor($res['TM'] / sizeof($solution_array['TM']) * 100);


    //print_r($res);
    //call to create pie charts

    drawPie1($result1[0]['a'], "Practical thinker", $result1[0]['b'],
        "Creative thinker", $ref_no . "resultPie1.png");
    drawPie1($result1[1]['a'], "Determined", $result1[1]['b'], "Moody", $ref_no .
        "resultPie2.png");
    drawPie1($result1[2]['a'], "Extrovert", $result1[2]['b'], "Introvert", $ref_no .
        "resultPie3.png");
    drawPie1($result1[3]['a'], "Quick decision maker", $result1[3]['b'],
        "Security minded", $ref_no . "resultPie4.png");
    drawPie1($result1[4]['a'], "Rule bound", $result1[4]['b'], "Flexible", $ref_no .
        "resultPie5.png");


    drawBar1($val11, $val12, $val13, $val14, $ref_no . 'resultBar1.png');
    drawBar2($val15, $val16, $val17, $val18, $val19, $ref_no . 'resultBar2.png');

    drawBar3($val11, $val12, $val13, $val14, $ref_no . 'resultBar3.png');
    drawBar4($val15, $val16, $val17, $val18, $val19, $ref_no . 'resultBar4.png');


    // set object and Pass RefNum
    $bc = new Barcode39($ref_no);

    // set text size
    $bc->barcode_text_size = 5;

    // set barcode bar thickness (thick bars)
    $bc->barcode_bar_thick = 4;

    // set barcode bar thickness (thin bars)
    $bc->barcode_bar_thin = 2;

    // save barcode JPG file
    $bc->draw('wp-content/plugins/cb-test/result_scripts/tmp/' . $ref_no .
        'Bar3.jpg');

    $pageNo = 1;
    $first_name = (ucwords($first_name));
    
    
    
    
    
    /**
     * career choice
     */

//$career_details = $career_details[0];
//Call function
for($i=0;$i<3;$i++)
{
    $career_choice[$i]['code'] = stringToArray($career_details[$i]->code,$compressedCode);
    
    //here we have the personalities which user has
    $choice_changes[0] = cb_ci_characteristic($career_choice[$i]['code'][0]);
    $career_choice[$i]['change_opposite_1'] = $choice_changes[0]['title_verb'];
    //here we are finding the personalities which user should possess
    $career_choice[$i]['code'][0] = $choice_changes[0]['opposite'];
    //here we have the personalitites which user needs to adapt
    $choice_changes[0] = cb_ci_characteristic($career_choice[$i]['code'][0]);
    $career_choice[$i]['title_1'] = $choice_changes[0]['title'];
    $career_choice[$i]['title_verb_1'] = $choice_changes[0]['title_verb'];
    $career_choice[$i]['suggestion_1'] = $choice_changes[0]['suggestion'];
    $career_choice[$i]['image_1'] = $choice_changes[0]['image'];
    
    
    if(isset($career_choice[$i]['code'][1]))
    {
        $choice_changes[1] = cb_ci_characteristic($career_choice[$i]['code'][1]);
        $career_choice[$i]['change_opposite_2'] = $choice_changes[1]['title_verb'];
        $career_choice[$i]['code'][1] = $choice_changes[1]['opposite'];    
        $choice_changes[1] = cb_ci_characteristic($career_choice[$i]['code'][1]);
        $career_choice[$i]['title_2'] = $choice_changes[1]['title'];   
        $career_choice[$i]['title_verb_2'] = $choice_changes[1]['title_verb'];
        $career_choice[$i]['suggestion_2'] = $choice_changes[1]['suggestion'];
        $career_choice[$i]['image_2'] = $choice_changes[1]['image'];
    }
}
//print_r($career_details);
//exit();






    $allPage = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<link href='wp-content/plugins/cb-test/result_scripts/styleRam.css' rel='stylesheet' type='text/css' />
</head>
<body>
<div class='p1Table_01'>
	<div class='p1-CareerBreederLogo_'>
		<img src='wp-content/plugins/cb-test/result_scripts/images/p1_CareerBreederLogo.gif' width='342' height='102' alt='' />
	</div>
	<div class='p1-RefNo_'>
		Ref. No.: $ref_no
	</div>
	<div class='p1-BarcodeImage_'>
		<img  src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "Bar3.jpg' width='174' height='55' alt='' />
	</div>
	<div class='Ruler_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/Ruler.jpg' width='709' height='14' alt='' />
	</div>
	<div class='Page1-15_'>
		 For,
	</div> 
	<div class='CandidateName_'>
		$first_name
	</div>
	<div class='CareerIndicatorBanner_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/CareerIndicatorBanner.gif' width='709' height='274' alt='' />
	</div>
	<div class='ReportPreparedOn_'>
		Report prepared on
	</div>
	<div class='ReportDate_'>";
    $pieces = explode(" ", $repDate);
    $allPage .= "$pieces[0]</div>
 	<div class='ConfidentialImage_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/ConfidentialImage.gif' width='207' height='168' alt='' />
	</div>
 	<div class='p1Signature_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/rajesh.jpg' width='175' height='96' alt='' />
	</div>
	<div class='Orpotech_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/Orpotech.gif' width='330' height='83' alt='' />
	</div>
	<div class='CareerCouncellor_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/CareerCouncellor.gif' width='216' height='48' alt='' />
	</div>	
	<div class='Page1-Footer_'>
		Career Breeder is an initiative of Oprotech Technologies Pvt Ltd. Copyright &copy; 2014. All rights reserved.
	</div>
   <div class='pageNo'>Page:" . $pageNo++ . "</div>
</div>

<div class='p2Table_01'>
	<div class='p3-SiteLogo_'>
		<img  src='wp-content/plugins/cb-test/result_scripts/images/p2_careerBreederLogo.jpg' width='246' height='72' alt='' />
	</div>
	<div class='p3-CareerIndicatorLogo_'>Career Indicator</div>
    <div class='page3-10_'></div><!--Ruler-->
	<div class='GenericRefNo'>
		Ref. No.: $ref_no
	</div>
	<div class='GenericBarcode'>
		<img   src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "Bar3.jpg' width='174' height='29' alt='' />
	</div>
	<div class='p3-Heading1_'>
    Chairman&rsquo;s message	</div>
	<div class='ChairmanImage_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/ChairmanImage.jpg' width='264' height='424' alt='' />
	</div>
	<div class='p3Message_'>
		<p>I visualize the concept of Career Breeder as a Knowledge society. My dream is that people in different disciplines and walks of life would be able to pursue their careers as to make India a developed country.</p>
	</div>
	<div class='p3Signature_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/Signature3.jpg' width='180' height='65' alt='' />
	</div>
	<div class='ChairmanName_'>
		Dr. Pramod Kumar Manglik
	</div>
	<div class='ChairmanInfo_'>
	  <p>Narco Analyst C.B.I.<br />
		  MBBS, M.D. (Pysch.), F.I.P.S.</p>
		<p>Reg. No. - 29136 (U.P Medical Council)<br />
		  - 22791 (Delhi Medical Council)</p>
	</div>
	<div class='GenericFooter'> contactus@careerbreeder.com | ".CONTACT_NUMBER." | www.careerbreeder.com 	</div>
     <div class='pageNo'>Page:" . $pageNo++ . "</div>
</div>

<div class='p3Table_03'>
	<div class='p2-careerBreederLogo_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/p2_careerBreederLogo.jpg'  width='246' height='72'  alt='' />
	</div>
	<div class='p2-CareerIndicatorLogo_'>
		Career Indicator </div>
	<div class='p2Ruler_'>	</div>
	<div class='GenericRefNo'>
		Ref. No.: $ref_no
	</div>
	<div class='GenericBarcode'>
		<img   src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "Bar3.jpg'  width='174' height='29'  alt='' />
	</div>
	<div class='p2-header1_'>
		With this report you will
	</div>
		<div class='p2-list1_'>
		<ul type='disc'>
			<li> Determine personality and aptitude by weighing different aspects against each other and personalised suggestions are provided to balance the extremities of your behaviour</li>
			<li> Avail suggestions on the behavioural characteristics that require an improvement in order to balance the overall personality with poise</li>
			<li> Achieve the heights that you have dreamed of by making you balance your qualities properly and efficiently</li>
			<li> Obtain a set of suitable career choices based on your personality, aptitude and interest</li>
			<li> Get suggestionions on the most appropriate professions out of more than 500 different career possibilities.</li>
			<li> Get guidance on the personality improvements of your career in case you already chose or dreamed about one</li>
		</ul>
	</div>
	
 <div class='p2-text14_'>
		<p>
		
		To lead a satisfying professional life, you should have a balance in all dimensions of your personality. Every individual has certain characteristics that determine the role of each dimension in your personality. Scarcity or deficiency with a difference of less then 20% is considered good.
		
		</p>
	</div>
	<div class='p2-header_'>
		Some examples:  
	</div>
		<div class='p2-list2_'>
	<ul>
		<li>65% extrovert and 35% introvert is considered as good.</li>
		<li>35% extrovert and 65% introvert is also considered as good.</li>
		<li>10% extrovert and 90% introvert needs little improvement.</li>
	
	</ul>
	</div>
	
	<div class='GenericFooter'>
		contactus@careerbreeder.com | ".CONTACT_NUMBER." | www.careerbreeder.com
	</div>
     <div class='pageNo'>Page:" . $pageNo++ . "</div>
</div>";

    $allPage .= "<div class = 'psumTable_01'>
        <div class='page4-03_'>
		<img  src='wp-content/plugins/cb-test/result_scripts/images/p2_careerBreederLogo.jpg'  width='246' height='72'  alt='' />
	</div>

	<div class='page4-06_'>
		Career Indicator
	</div>
    <div class='page4-Ruler_'></div>
	<div class='GenericRefNo'>
		 Ref. No.: $ref_no
	</div>
	
	<div class='GenericBarcode'>
		<img  src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "Bar3.jpg'  width='174' height='29'  alt='' />
	</div>
	
	<div class='page4-18_'>
		Summary of $first_name
	</div>";
     
    $allPage.="<div class = 'Summary1-23_'>
        $first_name's personality:
    </div>
    
	<div class='Summary1-listTable_'>
	  <table width='100 % ' border='0' bgcolor=' #e6eff8'>";
      
        $allPage.="<tr>
            <td width = '45%'>The way you perceive the problem</td>
            <td width = '17%' class = 'BlueTable'>
                <div align = 'left' class = 'BlueTable'>Creative thinking</div>
            </td>
            <td width = '8%'>".round($res['CP']['C'] / ($res['CP']['P'] + $res['CP']['C'])*100, 0)."%</td>
            <td width = '6%'>and</td>
            <td width = '17%'class = 'ClrPink'><font color = '#FF33CC'>Practical thinking</font></td>
            <td width = '7%' >" .(100 - round($res['CP']['C'] / ($res['CP']['P'] + $res['CP']['C']) * 100, 0)) . "%</td>
        </tr>
        
        <tr>
            <td>The way you analyze, think and plan</td>
            <td class='BlueTable'>
                <div align='left' class='BlueTable'>Determination</div>
            </td>
            <td>".round($res['DM']['D'] / ($res['DM']['D'] + $res['DM']['M'])*100, 0)."%</td>
            <td>and</td>
            <td class='ClrPink'><font color='#FF33CC'>Moody</font></td>
            <td>".(100-round($res['DM']['D'] / ($res['DM']['D'] + $res['DM']['M'])*100, 0))."</td>
        </tr>
        <tr>
            <td>How you express your ideas and views</td>
            <td class='BlueTable'><div align='left' class='BlueTable'>Extroversion</div></td>
            <td>" . round($res['EI']['E'] / ($res['EI']['E'] + $res['EI']['I']) *
        100, 0) . "%</td>
            <td>and</td>
            <td class='ClrPink'><font color='#FF33CC'>Introversion</font></td>
            <td>" . (100 - round($res['EI']['E'] / ($res['EI']['E'] + $res['EI']['I']) *
        100, 0)) . "%</td>
        </tr>
        <tr>
            <td>The way you take and follow decisions</td>
            <td class='BlueTable'><div align='left' class='BlueTable'>Quick</div></td>
            <td>" . round($res['QS']['Q'] / ($res['QS']['Q'] + $res['QS']['S']) *
        100, 0) . "%</td>
            <td>and</td>
            <td class='ClrPink'><font color='#FF33CC'>Security mindness</font></td>
            <td>" . (100 - round($res['QS']['Q'] / ($res['QS']['Q'] + $res['QS']['S']) *
        100, 0)) . "%</td>
        </tr>
        
        <tr>
            <td>The way you carry your decision and action</td>
            <td class='BlueTable'><div align='left' class='BlueTable'>Rule bound</div></td>
            <td>" . round($res['RF']['R'] / ($res['RF']['R'] + $res['RF']['F']) *
        100, 0) . "%</td>
            <td>and</td>
            <td class='ClrPink'><font color='#FF33CC'>Flexibilty</font></td>
            <td>" . (100 - round($res['RF']['R'] / ($res['RF']['R'] + $res['RF']['F']) *
        100, 0)) . "%</td>
        
        </tr></table>
   </div>";
 
 if($payFlag==0)
 {
    $allPage.="<div style='position:absolute;
	left:313px;
	top:206px;
	width:405px;
	height:109px; z-index: 2; background-color: #cdc9c8;text-align:center; border: solid; font-weight: bolder;
    font-size: x-large;'> Please pay to see the full report</div>";
 }
 
    $allPage.="<div class='Summary1-Heading-graph_'>$first_name's study habits:</div>
    <div style='position:absolute;z-index-2;top:366px;left:18px;width:345px;border: ridge; font-weight: bold;
    text-align:center;height:16px;font-size:10pt;'>&lt;30:Needs work|
    30-60:Good|
    &gt;60:Right Track</div>
    <div class='Summary1-HeadingGraph2_'>$first_name's success graph:</div>
    <div style='position:absolute;z-index-2;top:366px;left:381px;width:324px;border: ridge; font-weight: bold;
    text-align:center;height:16px;font-size:10pt;'>&lt;30:Needs work|
    30-60:Good|
    &gt;60:Right Track</div>
    <div class='Summary1-Graph2_'><img src='wp-content/plugins/cb-test/result_scripts/tmp/" .
        $ref_no . "resultBar3.png' width='349' height='159' alt=''/></div>
    <div class='Summary1-Graph1_'><img src='wp-content/plugins/cb-test/result_scripts/tmp/" .
        $ref_no . "resultBar4.png' width='328' height='159' alt=''/></div>";
          
        
    if($payFlag==0)
    {
        $allPage.="<div class='Summary1-Graph2_' style='z-index: 2; background-color: #cdc9c8;text-align:center;
                    border: solid; font-weight: bolder;
                    font-size: x-large;height:110px;'>Please pay to see the full report</div>
                    <div class='Summary1-Graph1_' style='z-index: 2; background-color: #cdc9c8;text-align:center;
                    border: solid; font-weight: bolder;
                    font-size: x-large;height:110px;'> Please pay to see the full report</div>";
    }
     
     
$allPage.="<div class='Summary1-HeadingCharacteristics_'>$first_name's characteristics:</div>
        	
        	<div class='Summary1-Table2_'>
        " . $CS_characteristic[0] . "		
        	</div>";
           
           if($payFlag==0)
           {
            $allPage.="<div class='Summary1-Table2_' style='z-index: 2; background-color: #cdc9c8;text-align:center;
                    border: solid; font-weight: bolder;
                    font-size: x-large; height:85px;top:625px;'>Please pay to see the full report</div>";
           } 
            
                $allPage.="<div class='Summary1-BulbImg_'><p>	
        				$first_name!<br/><br/>
        
        				Here is your Report	</p>
        	</div>
        	<div class='Summary1-Pro-mtch_'>
        		Professions matching $first_name's profile:</div><div class=
        'Summary1-Profess-List_'><ul type='disc'>";
    for ($j = 0; $j < sizeof($car); $j++)
        $allPage .= "<li>" . $car[$j]['title'] . "</li>";

    $allPage .= "</ul>
    </div>";

if($payFlag==0)
{    
$allPage.="<div class='Summary1-Profess-List_' style='z-index: 2; width:467px;top:770px;height:82px; background-color: #cdc9c8;text-align:center;
                    border: solid; font-weight: bolder;
                    font-size: x-large;'>Please pay to see the full report</div>";
}

$allPage.="<div class = 'Summary1-45_'>Expert recommends: </div > 
        
        <div class = 'Summary1-list-expert_'> 
        <ul type ='disc'> ";
        
        
    for($k=0;$k<3;$k++)
    {                            
        $allPage.="<li>";
        $allPage.=substr($career_details[$k]->name, 0, -1).": ";        
        if(isset($career_choice[$k]['title_verb_1']))
        {        
             $allPage.= "You need to work on your " . $career_choice[$k]['title_verb_1'];
            
            if (isset($career_choice[$k]['title_verb_2']))
                $allPage .= " and " . $career_choice[$k]['title_verb_2'];
        } else {
            $allPage.="Your personality suits for this profession, keep moving ahead";
                    
        }            
        $allPage .= "</li>";
    }
    
    $allPage .= "</ul> 
        
       </div> ";
       
if($payFlag==0)
{
    $allPage.="<div class = 'Summary1-list-expert_' style='z-index: 2; top:913px; height:64px;width:565px; background-color: #cdc9c8;text-align:center;
                    border: solid; font-weight: bolder;
                    font-size: x-large;'>Please pay to see the full report</div>";
}
       
       
       $allPage.="<div class='GenericFooter'>
		  contactus@careerbreeder.com | ".CONTACT_NUMBER." | www.careerbreeder.com
	   </div>
	 <div class='pageNo'>Page:" . $pageNo++ . "</div>
</div>";

$allPage .= "<div class='p4Table_01'>

	<div class='page4-03_'>
		<img  src='wp-content/plugins/cb-test/result_scripts/images/p2_careerBreederLogo.jpg'  width='246' height='72'  alt='' />
	</div>

	<div class='page4-06_'>
		Career Indicator
	</div>
    <div class='page4-Ruler_'></div>
	<div class='GenericRefNo'>
		 Ref. No.: $ref_no
	</div>
	
	<div class='GenericBarcode'>
		<img  src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "Bar3.jpg'  width='174' height='29'  alt='' />
	</div>
	
	<div class='page4-18_'>
		$first_name's Personality analysis
	</div>
	
	<div class='page4-22_'>
		Thinking process:
	</div>
	
	<div class='p4think1_'>
		<img src='wp-content/plugins/cb-test/result_scripts/images/p4think1.jpg' width='71' height='94' alt='' />
	</div>
	<div class='page4-26_'></div>
	<div class='page4-27_'>
		When we face any problem in life, we mostly have two types of choices, either we try our learned solutions gathered through our experiences in life or we try some new and innovative solutions for the problem. This reflects in our thinking process. 
	</div>";

    if ($res['CP']['C'] > $res['CP']['P'])
    {
        $allPage .= "<div class='CreativeThinker_'>
    		Creative thinker:
    	</div> 
    	<div class='p4Think2_'>
    		<img  src='wp-content/plugins/cb-test/result_scripts/images/p4Think2.jpg' width='61' height='58' alt='' />
    	</div>
    	<div class='page4-38_'>
    		$first_name, you are high in creative thinking. You try to find out some new and specific ways for the resolution of any problem.
    	</div>";
    }
    else
    {
        $allPage .= "<div class='CreativeThinker_'>
    		Practical thinker:
    	</div> 
    	<div class='p4Think2_'>
    		<img  src='wp-content/plugins/cb-test/result_scripts/images/p4PracticalThinker.jpg' width='61' height='58' alt='' />
    	</div>
    	<div class='page4-38_'>
    		$first_name, you are high in practical thinking. You try to find out solutions which are already known and have high practical uses. 
    	</div>";
    }

    $allPage .= "<div class='p4Graph1_'>
		<img src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "resultPie1.png' width='400' height='222' alt=''/>
	</div>
    
    
    
	<div class='page4-63_'>
		Behavior pattern:
	</div>
    <div class='page4-65_'>
		<img  src='wp-content/plugins/cb-test/result_scripts/images/page4_65.jpg' width='67' height='89' alt='' />
	</div>
    <div class='page4-67_'>
        Behavior pattern can be classified as determine type which suggests that we need to plan concrete steps to solve the problem on urgent basis. On the other hand, moody people do not plan on urgent basis.
	</div>";


    if ($res['DM']['D'] > $res['DM']['M'])
    {
        $allPage .= "<div class='p4Herculus_'>
    		<img  src='wp-content/plugins/cb-test/result_scripts/images/p4Herculus.jpg' width='67' height='89' alt='' />
    	</div>	 
    	<div class='page4-74_'>Determined:</div>";
    
    	$allPage.="<div class='page4-78_'>	$first_name, you are high in determination. You find out the solution or result on urgent basis.</div>";
    }
    else
    {
        $allPage .= "<div class='p4Herculus_'>
    		<img  src='wp-content/plugins/cb-test/result_scripts/images/p4Donald.jpg' width='67' height='89' alt='' />
    	</div>	 
    	<div class='page4-74_'>Moody:</div>";
    
        $allPage.="<div class='page4-78_'>	$first_name, you are high in moodiness. You can easily deviate from his basic work and engage in other work.</div>";
    }

          $allPage.= "<div class='p4graph2_'>
                <img src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
              "resultPie2.png' width='400' height='222' alt=''/>
               </div>";
      
      if($payFlag==0)
      {
        $allPage.="<div style='position:absolute;
        	left:4px;
        	top:644px;
	        width:370px;
	        height:222px; z-index: 2;background-color: #cdc9c8;text-align:center;
                    border: solid; font-weight: bolder;
                    font-size: x-large;'>Please pay to see the full report</div>
        <div class='p4graph2_' style='z-index: 2; background-color: #cdc9c8;text-align:center;
                    border: solid; font-weight: bolder;
                    font-size: x-large; width:395px;left:360px;'>Graph not available<br/>Please pay to see the full report</div>";
      }


    $allPage.="<div class='GenericFooter'> contactus@careerbreeder.com | ".CONTACT_NUMBER." | www.careerbreeder.com </div>
    </div><div class='pageNo'>Page:" . $pageNo++ . "</div>
</div>

<div class='p5Table_01'>
	<div class='page4-03_'>
		<img  src='wp-content/plugins/cb-test/result_scripts/images/p2_careerBreederLogo.jpg'  width='246' height='72'  alt='' />
	</div>

	<div class='page4-06_'>
		Career Indicator
	</div>
    <div class='page4-Ruler_'></div>
	<div class='GenericRefNo'>
		 Ref. No.: $ref_no
	</div>
	
	<div class='GenericBarcode'>
		<img  src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "Bar3.jpg'  width='174' height='29'  alt='' />
	</div>
	
	<div class='page4-18_'>
		$first_name's Personality analysis
	</div>
	<div class='SocialExpression_'>
		Social expression:
	</div>
	<div class='p5-artist_'>
		<img  src='wp-content/plugins/cb-test/result_scripts/images/p5_artist.gif' width='107' height='82' alt='' />
	</div>
	<div class='p5-text14_'>
Social expression is the pattern in which we decide to work on our solution. The two major expressions are extrovert behavior where we take help of others to solve the problem and introvert behavior where we solve the problem by own.    </div>";


    $allPage.="<div class='p5-graph-intr_'>
		<img src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "resultPie3.png' width='400' height='222' alt=''/>
	</div>";

    if ($res['EI']['E'] > $res['EI']['I'])
    {
        $allPage .= "<div class='page5-32_'>Extrovert:</div>
        	<div class='p5GroupDisc_'>
        		<img  src='wp-content/plugins/cb-test/result_scripts/images/p5GroupDisc.gif' width='56' height='48' alt='' />
        	</div>";
          
            $allPage.="<div class='p5-text-14-1_'>
        		$first_name, you are high in extrovertness. You can easily express your ideas and views in your social atmosphere.
        	</div>";
    }
    else
    {
        $allPage .= "<div class='page5-32_'>Introvert:</div>
        	<div class='p5GroupDisc_'>
        		<img  src='wp-content/plugins/cb-test/result_scripts/images/p5_introvert.gif' width='56' height='48' alt='' />
        	</div>";
            $allPage.="<div class='p5-text-14-1_'>
        	 $first_name, you are high in introvertness. You find new ways to express your ideas and thoughts other then general prescribe manner.
        	</div>";
    }

if($payFlag==0)
{
    $allPage.="<div class='p5-graph-intr_' style='z-index: 2; background-color: #cdc9c8;text-align:center;
                    border: solid; font-weight: bolder;
                    font-size: x-large; height:210px; width:330px;'>Graph not available<br/>Please pay to see the full report
                    </div>
               <div style='position:absolute;
               left:4px;top:306px;
               width:350px;height:137px;z-index: 2;background-color: #cdc9c8;text-align:center;
               border: solid; font-weight: bolder;
               font-size: x-large;'>Please pay to see the full report</div>";
}

    $allPage .= "<div class='p5-decisionMakingHabbit_'>Decision making habit:</div>
	<div class='p5-findWay_'>
		<img  src='wp-content/plugins/cb-test/result_scripts/images/p5_findWay.gif' width='119' height='120' alt='' />
	</div>
	<div class='p5-text14-3_'>
        Decision making capacity is the fourth pattern in which a person develops habit to solve the problem as early as possible. On the other hand, there is security mildness thinking in which person knows about solution but invest time to verify the outcomes which takes time and slightly delays the result.
	</div>";

    $allPage.="<div class='p5-graphQuick2_'>
		<img src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "resultPie4.png' width='400' height='222' alt=''/>
	</div>";    
   


    if ($res['QS']['Q'] > $res['QS']['S'])
    {
        $allPage .= "<div class='p5-Quick_'>
		<img  src='wp-content/plugins/cb-test/result_scripts/images/p5_Quick.jpg' width='57' height='72' alt='' />
    	</div>
    	<div class='page5-76_'>Quick decision maker:</div>
    	<div class='page5-81_'>
            $first_name, you are high in quick decision making. You have tendency to take risk.
    	</div>";
    }
    else
    {
        $allPage .= "<div class='p5-Quick_'>
    		<img  src='wp-content/plugins/cb-test/result_scripts/images/p5_SecurityMind.gif' width='57' height='72' alt='' />
    	</div>
    	<div class='page5-76_'>Security minded:</div>
    	<div class='page5-81_'>
            $first_name, you are high in security mindness. You have tendency to analyze your action deeply and take moderate level risk.
    	</div>";
    }
    
    if($payFlag==0)
{
    $allPage.="<div class='p5-graphQuick2_' style='position:absolute; z-index: 2; background-color: #cdc9c8;text-align:center;
                    border: solid; font-weight: bolder;
                    font-size: x-large; left:372px;height:260px; width:363px;'>Graph not available<br/>Please pay to see the full report
                    </div>
               <div style='position:absolute;
               left:4px;top:708px;
               width:350px;height:137px;z-index: 2;background-color: #cdc9c8;text-align:center;
               border: solid; font-weight: bolder;
               font-size: x-large;'>Please pay to see the full report</div>";
}

    $allPage .= "<div class='GenericFooter'>
		contactus@careerbreeder.com | ".CONTACT_NUMBER." | www.careerbreeder.com 
	</div>
     <div class='pageNo'>Page:" . $pageNo++ . "</div>
</div>


<div class='p6Table_01'>
	<div class='page4-03_'>
		<img  src='wp-content/plugins/cb-test/result_scripts/images/p2_careerBreederLogo.jpg'  width='246' height='72'  alt='' />
	</div>

	<div class='page4-06_'>
		Career Indicator
	</div>
    <div class='page4-Ruler_'></div>
	<div class='GenericRefNo'>
		 Ref. No.: $ref_no
	</div>
	
	<div class='GenericBarcode'>
		<img  src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "Bar3.jpg'  width='174' height='29'  alt='' />
	</div>
	
	<div class='page4-18_'>
		$first_name's Personality analysis
	</div>
	<div class='GenericBarcode'>
		<img  src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "Bar3.jpg'  width='174' height='29'  alt='' />
	</div>
    <div class='p6WorkAttitude_'>
		Work attitude:</div>
	<div class='p6-painter_'>
		<img  src='wp-content/plugins/cb-test/result_scripts/images/p6_painter.gif' width='96' height='131' alt='' />
	</div>
	<div class='p6-text14-1_'>
        Work attitude is the last pattern in which a person actually deal with solution process. This shows our ability to solve the problem, the first is rule bound attitude in which a person tries those ideas and facts which are prescribed by system and generate result. On the other hand, flexible attitude show easiness of the work and completes task with perfection.
    </div>
    <div class='p6-Graph-Flxblty_'>
		<img src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "resultPie5.png' width='400' height='222' alt=''/>
	</div>";


    if ($res['RF']['F'] > $res['RF']['R'])
    {
        $allPage .= "<div class='p6-FlexHead1_'>Flexible:</div>
    	<div class='p6-flexibility_'>
    		<img  src='wp-content/plugins/cb-test/result_scripts/images/p6_flexibility_new.png' width='61' height='41' alt='' />	</div>
    	<div class='p6-text14-2_'>$first_name, you are high in flexibility. You complete work according to others expectations.</div>";
    }
    else
    {
        $allPage .= "<div class='p6-FlexHead1_'>Rule bound:</div>
            	<div class='p6-flexibility_'>
            		<img  src='wp-content/plugins/cb-test/result_scripts/images/p6_ruleBound.gif' width='61' height='41' alt='' />	</div>
            	<div class='p6-text14-2_'>$first_name, you are high in rule boundness. You are comfortable in following rules and guidelines and take expert help.</div>";
    }


if($payFlag==0)
{
    $allPage.="<div class='p6-Graph-Flxblty_' style='position:absolute; z-index: 2; background-color: #cdc9c8;text-align:center;
                    border: solid; font-weight: bolder;
                    font-size: x-large; left:372px;height:260px; width:350px;'>Graph not available<br/>Please pay to see the full report
                    </div>
               <div style='position:absolute;
               left:4px;top:306px;
               width:350px;height:137px;z-index: 2;background-color: #cdc9c8;text-align:center;
               border: solid; font-weight: bolder;
               font-size: x-large;'>Please pay to see the full report</div>";
}

    $allPage .= "<div class='p6-Blank_'></div>
	<div class='GenericFooter'>
		contactus@careerbreeder.com | ".CONTACT_NUMBER." | www.careerbreeder.com
	</div>
     <div class='pageNo'>Page:" . $pageNo++ . "</div>
</div>";


    $allPage .= "<div class='p9Table_01'>
	<div class='p9-logoCBreeder_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/p2_careerBreederLogo.jpg'  width='246' height='72'  alt='' />	</div>
	<div class='p9-careeLogo_'>
		Career Indicator
	</div>
	<div class='page9-10_'></div>
	<div class='p9-ruler_'></div>
	<div class='page9-12_'></div>
	<div class='p9-successGraph_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/p9_successGraph.gif' width='100' height='99' alt='' />
	</div>
	<div class='GenericRefNo'>
		Ref. No.: $ref_no
	</div>
	<div class='GenericBarcode'>
		<img  src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "Bar3.jpg'  width='174' height='29'  alt='' />
	</div>
	<div class='p9-header-main1_'>
		$first_name's Study habits
	</div>
	<div class='page9-24_'>
		Study habits are the learned behaviors in which we develop certain pattern supported by our intelligence and our social and family surrounding. If we know correct way of learning skills then we can easily improve our performances in exam. Here we are giving some major factors which affect our studies directly. By knowing these we can easily improve our studies related skills. 
	</div>
	<div class='page9-26_'>
		<img  src='wp-content/plugins/cb-test/result_scripts/images/page9_26.gif' width='135' height='80' alt='' />
	</div>
	<div class='page9-29_'>
		Adjustment capacity:
	</div>
	<div class='page9-30_'>
		It shows the person's ability to manage his skill in minimizing hurdles and marching toward goal with the help of his surroundings.
	</div>
	<div class='page9-31_'>
		<img src='wp-content/plugins/cb-test/result_scripts/images/page9_31.gif' width='135' height='76' alt='' />
	</div>
	<div class='page9-33_'>
		Discipline:
	</div>
	<div class='page9-34_'>
		It's the tendency of an individual to follow prescribed guidelines to reach his goal. Discipline s reflects person's ability to follow norms i.e. family, social-cultural, school. 
	</div>
	<div class='page9-37_'>
		Time management:
	</div>
	<div class='page9-38_'>
		<img src='wp-content/plugins/cb-test/result_scripts/images/page9_38.gif' width='135' height='72' alt='' />
	</div>
	<div class='page9-39_'>
		It's a skill by which we effectively plan our work and deliver according to expectations. If we have high time management sense then we can easily complete our task and make ourselves more successful than ever. 
	</div>
	<div class='page9-42_'>
		<img  src='wp-content/plugins/cb-test/result_scripts/images/page9_42.gif' width='135' height='95' alt='' />
	</div>
	<div class='page9-43_'>
		Stress management:
	</div>
	<div class='page9-44_'>
		It's the basic tool of our life by which we save ourselves from expected stress and work with positive frame of mind; if we have high stress management skill then anxiety & fear would not affect our performance.
	</div>";
    
  
    $allPage.="<div class='page9-47_'>
		<img src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "resultBar1.png' width='675' height='235' alt=''/>
	</div>
    <div style='position:absolute;z-index-2;top:676px;left:12px;width:671px;border: none; font-weight: bold;
    text-align:center;height:15px;font-size:13pt;'>Study Improvement Graph&nbsp;(Values in percentage)</div>";
    
    if($payFlag==0)
    {
        $allPage.="<div class='page9-47_' style='z-index:2;left:202px;top:725px;height:208px;
        width:482px;background-color: #cdc9c8;text-align:center;
                    border: solid; font-weight: bolder;
                    font-size: x-large;'>Please pay to see the full report</div>";
    }
    
$allPage.="<div class='GenericFooter'>
		contactus@careerbreeder.com | ".CONTACT_NUMBER." | www.careerbreeder.com
	</div>
     <div class='pageNo'>Page:" . $pageNo++ . "</div>
	
</div>

<div class='p10_tableContent'>	
	<div class='page10-03_'>
		<img src='wp-content/plugins/cb-test/result_scripts/images/p2_careerBreederLogo.jpg'  width='246' height='72'  alt='' />
    </div>
	<div class='p10-careerIndicatorLogo_'>
		Career Indicator 
	</div>
	<div class='page10-10_'> </div>
	<div class='page10-13_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/page10_13.gif' width='88' height='58' alt='' />
	</div>
	<div class='GenericRefNo'>
		Ref. No.: $ref_no
	</div>
	<div class='GenericBarcode'>
		<img   src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "Bar3.jpg'  width='174' height='29'  alt='' />
	</div>
	<div class='page10-19_'>
		$first_name's Success graph
	</div>
<div class='page10-21_'>
Achievement of an action within a specific time and specific parameter which is expected or planed by an individual is success. Here we are giving you basics of success parameters by which you could easily analyze and improve your skill in success sphere. 	</div>
	<div class='page10-25_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/page10_25.gif' width='130' height='81' alt='' />
	</div>
	<div class='p10Head-1_'>
		Body confidence:
	</div>
	<div class='page10-28_'>
        It's an idea or a thought image of a person's self physical appearance. This concept of self appearance has big impact on our thinking and behavior, if we improve this, then it will change our thinking and make our self better adjusted in our society and personal life.	
    </div>
	<div class='page10-29_'> </div>
	<div class='page10-31_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/page10_31.gif' width='129' height='86' alt='' />
	</div>
	<div class='P10Head2_'>
		Imagination capacity: 
	</div>
	<div class='page10-33_'> </div>
	<div class='p10-txt14-2_'>
		It's the power of an individual to imagine new solutions which are not present at that moment. Using this capability a can easily solve new problems; create new dimensions for development and betterment of life.
    </div>
	<div class='page10-37_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/page10_37.gif' width='129' height='90' alt='' />
	</div>
	<div class='p10Interpersonal-Head_'>
		Interpersonal relation skill: 
	</div>
	<div class='page10-39_'></div>
	<div class='page10-40_'>
        It's the capacity of any person to make personal, social or business relation with other person and respectively carry on the relation with mutual benefit. This skill makes a person more acceptable everywhere in society and converts hard labor to smart labor.
    </div>
	<div class='p10Head4_'>
		Problem solving: 
	</div>
	<div class='page10-43_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/page10_43.gif' width='130' height='84' alt='' />
	</div>
	<div class='page10-44_'>
        It's the strength of any person to see the coming problem and development of a specific kind of confidence or hope for the solution of the problem. This is basically a system to approach problem; if we improve this skill then we are in good shape to counter success. 
    </div>
	<div class='p10Head5-1_'>
		Emotional maturity: 
	</div>
	<div class='page10-48_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/page10_48.gif' width='130' height='76' alt='' />
	</div>
	<div class='p10-50-text14_'>
		It comprises of intelligence, emotion and spiritualism. This gives strength to analyze and take proper decisions to resolve a given problem which is acceptable in person's own society.
	</div>";

    $allPage.="<div class='p10-GraphDiv_'>
		<img src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "resultBar2.png' width='678' height='260' alt=''/>
	</div>
    <div style='position:absolute;z-index-2;top:660px;left:14px;width:674px;border: none; font-weight: bold;
    text-align:center;height:15px;font-size:13pt;'>Success Graph&nbsp;(Values in percentage)</div>";
 
    if($payFlag==0)
    {
        $allPage.="<div class='p10-GraphDiv_'style='z-index:2;left:175px;top:710px;height:232px;
        width:515px;background-color: #cdc9c8;text-align:center;
                    border: solid; font-weight: bolder;
                    font-size: x-large;'>Please pay to see the full report</div>";
    }
$allPage.="<div class='GenericFooter'>
		contactus@careerbreeder.com | ".CONTACT_NUMBER." | www.careerbreeder.com 
	</div>
     <div class='pageNo'>Page:" . $pageNo++ . "</div>
</div>";


    $allPage .= "<div class='p7Table_01'>

		<div class='page4-03_'>
		<img  src='wp-content/plugins/cb-test/result_scripts/images/p2_careerBreederLogo.jpg'  width='246' height='72'  alt='' />
	</div>

	<div class='page4-06_'>
		Career Indicator
	</div>
    <div class='page4-Ruler_'></div>
	<div class='GenericRefNo'>
		 Ref. No.: $ref_no
	</div>
	
	<div class='GenericBarcode'>
		<img  src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "Bar3.jpg'  width='174' height='29'  alt='' />
	</div>
	
	<div class='page4-18_'>
		Personalized comments for $first_name
	</div>	 

	<div class='page7-22_'>
		<img   src='" . $CS_image[0] . "' width='61' height='58' alt='' /></div>
	<div class='p7-Head1-sMinded_'>
	  $first_name is " . $CS_title[0] . ":	</div>
	<div class='P7-head1_'><p>	  Characteristics	</p></div>
	  <div class='ListDiv1'>
      
<!-- <ul type='disc'>" . $CS_characteristic[0] . "</ul> -->     

     $CS_characteristic[0]
  </div> 
	<div class='headingSuggestion_'>
		<p >Suggestions</p>
	</div>	 
	<div class='p7list2_'>
    
<!--		<ul type='disc'>" . $CS_suggestion[0] . "</ul>   -->
    
    $CS_suggestion[0]
                
  </div>
<!--	<div class='page7-52_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/page7_70.gif' width='316' height='3' alt='' />
	</div>
	<div class='page7-53_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/page7_70.gif' width='331' height='3' alt='' />
	</div>    -->
    ";


    if (isset($CS_title[1]))
    {
        $allPage .= "<div class='p7-list1054_'>
		 
	</div>
	<div class='page7-55_'>
		<img src='" . $CS_image[1] . "' width='61' height='58' alt='' />
	</div>
	<div class='p7-Char-h3_'> <p>
		Characteristics</p>
	</div>
	<div class='page7-62_'> <p>Suggestions</p> </div>
	<div class='p7-list4_'>
<!--		<ul type='disc'>" . $CS_characteristic[0] . "</ul>  -->

    $CS_suggestion[1]
              
  </div>
  	<div class='p7-Head2-Moody_'>$first_name is " . $CS_title[1] . ":	</div>
	<div class='p7-list3067_'>
<!--  <ul type='disc'>" . $CS_suggestion[0] . "</ul>   -->
    $CS_characteristic[1]
    
    
  </div> 
	
    <div class='page7-85_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/page7_70.gif' width='316' height='4' alt='' />
	</div>
	<div class='page7-86_'>
		<img   src='wp-content/plugins/cb-test/result_scripts/images/page7_70.gif' width='332' height='5' alt='' />
	</div>
	<div class='p7-list3087_'> </div>";

    }


if($payFlag==0)
{
  $allPage.="<div style='position:absolute; top:390px; left:0; width:700px; height:225px; background-color: #cdc9c8;text-align:center;
                    border: solid; font-weight: bolder;
                    font-size: x-large; z-index:2;'>Please pay to see the full report</div>";  
}

    $allPage .= "<div class='GenericFooter'>
		contactus@careerbreeder.com | ".CONTACT_NUMBER." | www.careerbreeder.com 
	</div>
     <div class='pageNo'>Page:" . $pageNo++ . "</div>
</div>";


    for ($z = 0; $z < sizeof($car); $z++)
    {
        $allPage .= "<div class='p8Table_01'>
		<div class='page4-03_'>
		<img  src='wp-content/plugins/cb-test/result_scripts/images/p2_careerBreederLogo.jpg'  width='246' height='72'  alt='' />
	</div>

	<div class='page4-06_'>
		Career Indicator
	</div>
    <div class='page4-Ruler_'></div>
	<div class='GenericRefNo'>
		 Ref. No.: $ref_no
	</div>
	
	<div class='GenericBarcode'>
		<img  src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
            "Bar3.jpg'  width='174' height='29'  alt='' />
	</div>
	
	<div class='page4-18_'>
		Suggested career options for $first_name
	</div>
	<div class='p8-headingMain_'><br/>
		" . $car[$z]['title'] . " (" . $car[$z]['category'] . ")
	</div>
	<div class='p8-text14-1_'>";

        $work1 = $car[$z]['description'];
        if (strlen($work1) <= 400)
        {
            $allPage .= $work1;
        }
        else
        {
            $count = 0;
            for ($x = 0; $x < strlen($work1); $x++)
            {
                $count++;
                if ($count == 300)
                {
                    for ($y = $count; $y < strlen($work1); $y++)
                    {
                        if ($work1[$y] == ';' || $work1[$y] == '.')
                        {
                            $resWork = substr($work1, 0, $y);
                            break;
                        }
                    }
                    break;
                }
            }
            $resWork = $resWork . ".";
            $allPage .= $resWork;
        }

        $image = trim($car[$z]['image']);
    //$image = str_replace(" ", "%20", $image);
        $image=str_replace(" ","",$image);
        $allPage .= "</div>
	<div class='p8-img-musicians_'>
		<img src='" . "wp-content/plugins/cb_manager/images/" . $image .
            "' width='166' height='255' alt='' />
	</div>";
    
    
    $allPage .= "<div class='p8-workHeading2_'>";
    $aw = trim($car[$z]['work']);
    if(isset($aw) && !empty($aw))
        $allPage.="Work:";
                
    $allPage.="</div>

	<div class='p8-Text14-2_'>";


        $work1 = $car[$z]['work'];
        if (strlen($work1) <= 550)
        {
            $allPage .= "$work1</div>
	<div class='page8-31_'>
		Requirements:
	</div>
	<div class='page8-33_'>";
        }
        else
        {
            $count = 0;
            for ($x = 0; $x < strlen($work1); $x++)
            {
                $count++;
                if ($count == 500)
                {
                    for ($y = $count; $y < strlen($work1); $y++)
                    {
                        if ($work1[$y] == ';' || $work1[$y] == '.')
                        {
                            $resWork = substr($work1, 0, $y);
                            break;
                        }
                    }
                    break;
                }
            }

            $resWork = $resWork . ".";

            $allPage .= "$resWork</div>";
            
                  $allPage .= "<div class='page8-31_'>";
        $aw = trim($car[$z]['requirements']);
        if(isset($aw) && !empty($aw))
            $allPage.="Requirements:";
        $allPage.="</div>";

$allPage.="	<div class='page8-33_'>";
        }


        $work1 = $car[$z]['requirements'];

        if (strlen($work1) <= 550)
        {
            $allPage .= "$work1</div>";
        }
        else
        {
            $count = 0;
            for ($x = 0; $x < strlen($work1); $x++)
            {
                $count++;
                if ($count == 500)
                {
                    for ($y = $count; $y < strlen($work1); $y++)
                    {
                        if ($work1[$y] == ';' || $work1[$y] == '.')
                        {
                            $resWork = substr($work1, 0, $y);
                            break;
                        }
                    }
                    break;
                }
            }

            $resWork = $resWork . ".";

            $allPage .= "$resWork</div>";
        }

        $allPage .= "<div class='page8-36_'>";
        $aw = trim($car[$z]['title_examples']);
        if(isset($aw) && !empty($aw))
            $allPage.="Title examples:";
        $allPage.="
	</div>
	<div class='page8-37_'>";

        $title_ex = $car[$z]['title_examples'];
        $len = strlen($title_ex);

        for ($i = 0; $i < $len; $i++)
        {
            $a = ord($title_ex[($i + 1)]);
            $b = ord($title_ex[($i + 2)]);

            if (ord($title_ex[$i]) == 13 && !empty($a) && !empty($b))
            {
                $a = $title_ex[($i - 1)];
                $b = $title_ex[($i - 2)];
                $c = $title_ex[($i - 3)];
                $d = $title_ex[($i - 4)];
                $e = $title_ex[($i - 5)];
                $f = $title_ex[($i - 6)];

                if ($a != ',' && $b != ',' && $c != ',' && $a != '; ' && $b != ';' && $c != ';' &&
                    $d != ';' && $e != ';' && $f != ';')
                    $title_ex[$i] = ',';
            }
        }

        if ($title_ex[$len - 1] == ',')
        {
            $title_ex[$len - 1] = '';
        }

        $allPage .= "$title_ex</div>";
        
        
        $allPage .= "<div class='page8-39_'>";
        $aw = trim($car[$z]['degrees_associated_with_this_career']);
        if(isset($aw) && !empty($aw))
            $allPage.="Degrees associated with this career:";
        $allPage.="</div><div class='page8-40_'>";


        $title_ex = $car[$z]['degrees_associated_with_this_career'];

        $len = strlen($title_ex);

        for ($i = 0; $i < $len; $i++)
        {
            $a = ord($title_ex[($i + 1)]);
            $b = ord($title_ex[($i + 2)]);

            if (ord($title_ex[$i]) == 13 && !empty($a) && !empty($b))
            {
                $title_ex[$i] = ',';
            }
        }

        if ($title_ex[$len - 1] == ',')
        {
            $title_ex[$len - 1] = '';
        }

        $allPage .= "$title_ex</div>
	<div class='p8-contentWrapper042_'>";


        $title_ex = $car[$z]['useful_secondary_school_subjects'];
        $len = strlen($title_ex);

        for ($i = 0; $i < $len; $i++)
        {
            $a = ord($title_ex[($i + 1)]);
            $b = ord($title_ex[($i + 2)]);

            if (ord($title_ex[$i]) == 13 && !empty($a) && !empty($b))
            {
                $title_ex[$i] = ',';
            }
        }

        if ($title_ex[$len - 1] == ',')
        {
            $title_ex[$len - 1] = '';
        }


        $allPage .= "$title_ex</div>";
        
        $allPage .= "<div class='page8-44_'>";
        $aw = trim($car[$z]['useful_secondary_school_subjects']);
        if(isset($aw) && !empty($aw))
            $allPage.="Useful secondary school subjects:";
        $allPage.="</div>";
 
        $allPage .= "<div class='page8-47_'>";
        $aw = trim($car[$z]['employers']);
        if(isset($aw) && !empty($aw))
            $allPage.="Employers:";
        $allPage.="</div>";
        
        $allPage.="
	<div class='page8-49_'>
		" . $car[$z]['employers'] . "    
	</div>";
    
    if($payFlag==0&&$z>0)
    {
        $allPage.="<div style='position:absolute;top:150px;z-index:2;height:825px;width:701px;left:10px;
        background-color: #cdc9c8;text-align:center;
                    border: solid; font-weight: bolder;
                    font-size: x-large;'>Please pay to see the full report</div>";
    }
    
	$allPage.="<div class='GenericFooter'>
		contactus@careerbreeder.com | ".CONTACT_NUMBER." | www.careerbreeder.com 
	</div>
     <div class='pageNo'>Page:" . $pageNo++ . "</div>
	
</div>";
    }
//$i=1;
    for ($j = 0; $j < 3; $j++)
    {
    $allPage .= "
    <div class='DoctorTable_01'>
    <div class='page4-03_'>
    <img  src='wp-content/plugins/cb-test/result_scripts/images/p2_careerBreederLogo.jpg'  width='246' height='72'  alt='' />
    </div>   
    <div class='page4-06_'>
    Career Indicator
    </div>
    <div class='page4-Ruler_'></div>
    <div class='GenericRefNo'>
    Ref. No.: $ref_no
    </div>
    
    <div class='GenericBarcode'>
    <img  src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
    "Bar3.jpg'  width='174' height='29'  alt='' />
    </div>  
    <div class='page4-18_'>
    Career choice - " . substr($career_details[$j]->name,0,-1) . "
    </div>";
    

    $image = trim($career_details[$j]->image);
    $image = str_replace(" ", "%20", $image);
    
    $allPage.="  
    <div class='p8-img-musicians_'>
        <img src='wp-content/plugins/cb_manager/images/".$image."' width='166' height='255' alt='' />
    </div>";
       
    if(!(isset($career_choice[$j]['title_verb_1'])))
    {
    $allPage .= "
    <!--div class='Doctor-head2_'>
    Exactly matched:
    </div-->
    
    
    <div class='Doctor-text1_'>
    <h2 style='color:#c01a76;'>Congrats $first_name, you are in right track. Your personality exactly match to become " .
    substr($career_details[$j]->name, 0, -1) .
    ". Keep moving towards your goal. All the best!
    </h2></div>";
    }
    else
    {
    $allPage .= "
    <div class='Doctor-text1_'>
    $first_name, your " . $career_choice[$j]['change_opposite_1'] .
    " level is high, you need to be little more " . $career_choice[$j]['title_1'] .
    " to become a successful " . substr($career_details[$j]->name, 0, -1) . ".";
    
    
    if(isset($career_choice[$j]['title_2']))
        $allPage .= " You also need to work to increase your ".$career_choice[$j]['title_verb_2'].".";
    
    $allPage.=" Here are the suggestions you need to incorporate to make yourself more competent.
    </div>
    
    <div class='Doctor-sECMIND_'>
    <img   src='".$career_choice[$j]['image_1']."' width='61' height='58' alt='' />
    </div>
    <div class='Doctor-headSecMind_'>
    <p>Increase ".$career_choice[$j]['title_verb_1']."</p>
    </div>
    <div class='Doctor-38_'>
    ".$career_choice[$j]['suggestion_1']."
    </div>";
    
    if(isset($career_choice[$j]['title_2']))
    {              
        $allPage.="
            <div class='Doctor-imgMoody_'>
            <img   src='".$career_choice[$j]['image_2']."' width='61' height='58' alt='' />
            </div>
            <div class='Doctor-HeadMoody_'>
            <p>Increase ".$career_choice[$j]['title_verb_2']."</p>
            </div>
            <div class='Doctor-Moody_'>
            ".$career_choice[$j]['suggestion_2']."
            </div>";
    }
    }


if($payFlag==0&&$j>0)
    {
        $allPage.="
        <div style='position:absolute;top:150px;z-index:2;height:825px;width:695px;left:8px;
        background-color: #cdc9c8;text-align:center;
                    border: solid; font-weight: bolder;     
                    font-size: x-large;'>Please pay to see the full report</div>";
    }

    $allPage .= "
    <div class='GenericFooter'>
    contactus@careerbreeder.com | ".CONTACT_NUMBER." | www.careerbreeder.com
    </div>
    <div class='pageNo'>Page:" . $pageNo++ . "</div>
    </div>";

    } 
    





    $allPage .= "
<div class='p12Table_01'>
	<div class='Contact-us-CarrerIndicator_'>
		<div class='p11-careerIndicatorLogo_'>
		Career Indicator 
	</div>
	</div>
	<div class='Contact-us-Logo_'>
		<img src='wp-content/plugins/cb-test/result_scripts/images/p2_careerBreederLogo.jpg'  width='246' height='72'  alt='' />	</div>
	<div class='Contact-us-HeadinLine_'>
  </div>
	<div class='Contact-us-b1_'></div>
	<div class='GenericRefNo'>
		Ref. No.: $ref_no			
  </div>
	<div class='GenericBarcode'>
		<img src='wp-content/plugins/cb-test/result_scripts/tmp/" . $ref_no .
        "Bar3.jpg'  width='174' height='29'   alt='Barcode'/>
	</div>
	<div class='Contact-us-Contact24X7Image_'>
		<img src='wp-content/plugins/cb-test/result_scripts/images/Contact-us_Contact24X7Image.gif' width='709' height='200' alt='' />
	</div>
	<div class='Contact-us-Contact24X7p2_'>
		<img src='wp-content/plugins/cb-test/result_scripts/images/Contact-us_Contact24X7p2.gif' width='439' height='213' alt='' />	</div>
	<div class='Contact-us-Address_' >
	<p style='padding-left:45px;'><br />
<span style='font-size:14px;font-weight:bold'> Corporate Office (Head Quarters):</span><br />
	 Career Breeder<br />
	 Hari Complex, Plot no 20, Sector 7,<br />
	 Ghansoli, Navi Mumbai<br />
	 Pin Code: 400701
     <br /><br />
	<span style='font-size:14px;font-weight:bold'>	Registered Office:</span><br />
	Career Breeder<br />
	C/o Oprotech Technologies Pvt Ltd.<br />
	5/142 Awas Vikas Colony<br />
	Farrukhabad, U.P., India - 209625<br />
	</p>
  </div>
	<div class='Contact-us-ContactImageP3_'></div>
	<div class='Contact-us-EmailAddress_'>
		<strong>Email: contactus@careerbreeder.com<br />Tel: ".CONTACT_NUMBER."<br />
                Website: www.careerbreeder.com
		</strong>
	</div>
	<div class='Contact-us-ContactUsImageFooter_'>
		<img src='wp-content/plugins/cb-test/result_scripts/images/Contact-us_ContactUsImageFo.gif' width='709' height='26' alt='' />
	</div>
	<div class='Contact-us-Smily_'>
		<img src='wp-content/plugins/cb-test/result_scripts/images/Contact-us_Smily.gif' width='190' height='49' alt='' />	</div>
	<div class='Contact-us-DidYouKnowText_'>
	<strong>&nbsp;&nbsp;As part of a social responsibility, whenever you buy anything from
			us, a part of it goes for Social Welfare.</strong>	</div>
	<div class='GenericFooter'>
		contactus@careerbreeder.com | ".CONTACT_NUMBER." | www.careerbreeder.com 
	</div>
     <div class='pageNo'>Page:" . $pageNo++ . "</div>
</div>
</body>
</html>";

    $dompdf = new DOMPDF();
    $dompdf->load_html($allPage);
    $dompdf->set_paper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('report.pdf', array('Attachment' => false));

}

function cb_test_helper123($char)
{
    switch ($char)
    {
        case 'P':
        case 'E':
        case 'D':
        case 'R':
        case 'Q':
            return 0;
        case 'C':
        case 'M':
        case 'I':
        case 'F':
        case 'S':
            return 1;
        default:
            return 2;
    }
}


function getSecQuesNum($qNo)
{
    $qNo++;
    if ($qNo <= 70)
    {
        $qNum = 0;

        $secNo = $qNo % 5;

        $qNum = floor($qNo / 5);

        if ($secNo == 0)
        {
            $secNo = 5;
        }
        else
        {
            $qNum++;
        }

    }
    else
    {
        $secNo = ($qNo - 70) % 4;
        $qNum = floor(($qNo - 70) / 4);

        if ($secNo == 0)
        {
            $secNo = 5;
        }
        else
        {
            $qNum++;
        }

        $qNum = $qNum + 14;
    }


    return array('sNo' => ($secNo - 1), 'qNo' => ($qNum - 1));
}


function globalQuesNo($secNo, $qNo)
{
    if ($qNo < 14)
        return ($qNo * 5) + $secNo;
    else
    {
        if ($secNo == 4)
            $secNo = 3;

        $temp = $qNo - 14;
        $no = ($qNo * 5) - $temp + $secNo;

        return $no;

    }
}
function cb_ci_characteristic($code)
{
    switch ($code)
    {
        case 'C':
            $sug['title'] = 'Creative Thinker';
            $sug['title_verb'] = 'Creative Thinking';
            $sug['characteristic'] = '<ul>
                          <li>These are people who always try new  and innovative solution for a problem.</li>
                          <li>Try to do things with perfection.</li>
                          <li>Have a quality to see the hidden  side of a problem.</li>
                          <li>Have a very good observation skill.</li>
                          <li>Have a good power of visualization.</li>
                      </ul>';
            $sug['suggestion'] = '<ul>
                          <li>Avoid  multi tasking attitude.</li>
                          <li>Learn  money management. </li>
                          <li>Always  make priority chart when you work or study. </li>
                          <li>Don&prime;t  purchase too many things at once.</li>
                          <li>Study  biography of leaders like Mahatma Gandhi. </li>
                </ul>';
            $sug['opposite'] = 'P';
            $sug['image'] = 'wp-content/plugins/cb-test/result_scripts/images/p4Think2.jpg';
            return $sug;


        case 'P':
            $sug['title'] = 'Practical Thinker';
            $sug['title_verb'] = 'Practical Thinking';
            $sug['characteristic'] = '<ul>
                      <li>These are people who will solve  problems with experiences and learning. </li>
                      <li>Always use present resources for  improvement.</li>
                      <li>They are always appreciated by  others for their hard work.</li>
                      <li>They are very good planners.</li>
                      <li>They are very good in money  management and money handling.</li>
                    </ul>';
            $sug['suggestion'] = '<ul>
                      <li>Listen  to classical music. </li>
                      <li>Join  hobby classes like drawing, singing, musical instruments, craft workshop, etc. </li>
                      <li>Whenever  you have a problem, first write it down on a paper and try to find as many solutions  as possible.</li>
                      <li>Learn  chucking phenomena for memorizing.</li>
                      <li>Learn  self hypnosis.</li>
                </ul>';
            $sug['opposite'] = 'C';
            $sug['image'] = 'wp-content/plugins/cb-test/result_scripts/images/p4PracticalThinker.jpg';
            return $sug;
        case 'D':
            $sug['title'] = 'Determined';
            $sug['title_verb'] = 'Determination';
            $sug['characteristic'] = '<ul>
                      <li>These are people who are very  organized. </li>
                      <li>Like to do work in systematic  manner. </li>
                      <li>Have a very strong analytical power.</li>
                      <li>They have good sense of their  priorities and their life goals.</li>
                      <li>Will always make decisions with the  help of realistic facts. </li>
                    </ul>';
            $sug['suggestion'] = '<ul>
                      <li>Always  respect your family &amp; social relationships.</li>
                      <li>Before  making a goal, consult your elders. </li>
                      <li>Always  make a backup plan for your work so if one fails, you have other.</li>
                      <li>Give  some time to social work or join any NGO and help others.</li>
                      <li>Make  a mentor for your life success.</li>
                </ul>';
            $sug['opposite'] = 'M';
            $sug['image'] = 'wp-content/plugins/cb-test/result_scripts/images/p4Herculus.jpg';
            return $sug;
        case 'M':
            $sug['title'] = 'Moody';
            $sug['title_verb'] = 'Moodiness';
            $sug['characteristic'] = '<ul>
                      <li>These are people who work according  to their own schedule.</li>
                      <li>Give preference to their emotion at  work.</li>
                      <li>They are very caring and get  respect in society. </li>
                      <li>They are good motivators.</li>
                      <li>They are socially well recognized and  have a huge contribution to the society.</li>
                    </ul>';
            $sug['suggestion'] = '<ul>
                      <li>Do  not deviate from your work plan. Be firm.</li>
                      <li>Always  make your work plan with the focus on the end result. </li>
                      <li>Always  consult with a successful person for your career suggestions.</li>
                      <li>Make  a routine or a day plan every day. </li>
                      <li>Learn  to say NO in life.</li>
                </ul>';
            $sug['opposite'] = 'D';
            $sug['image'] = 'wp-content/plugins/cb-test/result_scripts/images/p4Donald.jpg';
            return $sug;
        case 'E':
            $sug['title'] = 'Extrovert';
            $sug['title_verb'] = 'Extroversion';
            $sug['characteristic'] = '<ul>
                          <li>These are people who have strong communication skills.</li>
                          <li>They are team players and prefer to  work in groups.</li>
                          <li>They are generally called &ldquo;leaders&rdquo;.</li>
                          <li>Express their emotions very easily. </li>
                          <li>They are socially very active. </li>
                      </ul>';
            $sug['suggestion'] = '<ul>
                      <li>Right  down in a diary every day.</li>
                      <li>Make  a role model.</li>
                      <li>Always  try to listen to other people carefully.</li>
                      <li>Schedule  your whole day in the morning.</li>
                      <li>Do  some meditation &amp; relaxation exercises every day.</li>
                </ul>';
            $sug['opposite'] = 'I';
            $sug['image'] = 'wp-content/plugins/cb-test/result_scripts/images/p5GroupDisc.gif';
            return $sug;
        case 'I':
            $sug['title'] = 'Introvert';
            $sug['title_verb'] = 'Introversion';
            $sug['characteristic'] = '<ul>
                          <li>These are people who have limited  power to communicate.</li>
                          <li>Have good success rate when they work  alone.</li>
                          <li>Have strong understanding of the emotions  of others. </li>
                          <li>Give much time to thinking before  any work.</li>
                          <li>Rarely express emotions in public. </li>
                      </ul>';
            $sug['suggestion'] = '<ul>
                          <li>Learn  a new way to express your emotion.</li>
                          <li>Change  the color of the clothes. Wear bright colors.</li>
                          <li>Join  any health &amp; fitness center.</li>
                          <li>Avoid  virtual community (face book, Chatting).</li>
                          <li>Read  books like &lsquo;How to influence people &amp; friends&rsquo; by Dale Carnegie.</li>
                </ul>';
            $sug['opposite'] = 'E';
            $sug['image'] = 'wp-content/plugins/cb-test/result_scripts/images/p5_introvert.gif';
            return $sug;
        case 'Q':
            $sug['title'] = 'Quick decision maker';
            $sug['title_verb'] = 'Quick decision making';
            $sug['characteristic'] = '<ul>
                          <li>These are people who take decisions  on the spot.</li>
                          <li>Feel confident about themselves.</li>
                          <li>Utilize opportunity very well.</li>
                          <li>They are generally called SMART.</li>
                          <li>Have good level of will power. </li>
                    </ul>';
            $sug['suggestion'] = '<ul>
                          <li>Always  consult with an expert whenever you plan a new work.</li>
                          <li>Never  take too many risks at once.</li>
                          <li>Always  review your work plan within a certain time limit.</li>
                          <li>Always  make a backup plan so if anything goes wrong you have options. </li>
                          <li>Make  a proper work plan with specific needs and results. </li>
                </ul>';
            $sug['opposite'] = 'S';
            $sug['image'] = 'wp-content/plugins/cb-test/result_scripts/images/p5_Quick.jpg';
            return $sug;
        case 'S':
            $sug['title'] = 'Security minded';
            $sug['title_verb'] = 'Security mindness';
            $sug['characteristic'] = '<ul>
                          <li>These are people who analyse things  and then take decisions. </li>
                          <li>They are emotionally very mature. </li>
                          <li>They are generally called visionaries.</li>
                          <li>Their planning and goal-setting skills are perfect. </li>
                          <li>They are generally highly  successful.</li>
                    </ul>';
            $sug['suggestion'] = '<ul>
                          <li>Always  try to finish your work plan and your plan must have a fixed Time Line. </li>
                          <li>Join  adventure sports (river rafting, rock climbing).</li>
                          <li>Whatever  work you do give it your 100%.</li>
                          <li>Play  games like tennis, chess and/or horse riding. </li>
                          <li>Take  part in cultural activities. </li>
                </ul>';
            $sug['opposite'] = 'Q';
            $sug['image'] = 'wp-content/plugins/cb-test/result_scripts/images/p5_SecurityMind.gif';
            return $sug;
        case 'R':
            $sug['title'] = 'Rule bound';
            $sug['title_verb'] = 'Rule boundness';
            $sug['characteristic'] = '<ul>
                          <li>These are people who always complete their work  on time.</li>
                          <li>Always respect rules and follow them.</li>
                          <li>Work towards a goal and cannot be distracted easily.</li>
                          <li>Always fix a very high target point for them.</li>
                          <li>Have a nearly fixed routine life.</li>
                    </ul>';
            $sug['suggestion'] = '<ul>
                          <li>No  work plan can be perfect. So be flexible and change your work plan when one  plan does not work.</li>
                          <li>Always  try to work in a group and help others in their work. </li>
                          <li>Develop  some hobbies to enjoy life. For example, learn music, dance etc. </li>
                          <li>Learn  some yoga techniques to manage your stress and relax. </li>
                </ul>';
            $sug['opposite'] = 'F';
            $sug['image'] = 'wp-content/plugins/cb-test/result_scripts/images/p6_ruleBound.gif';
            return $sug;
        case 'F':
            $sug['title'] = 'Flexible';
            $sug['title_verb'] = 'Flexibility';
            $sug['characteristic'] = '<ul>
                          <li>These are people who simultaneously start multiple tasks. </li>
                          <li>They are very adjustable in their environment. </li>
                          <li>They are called happy go lucky person.</li>
                          <li>Get bored easily so they like changes. </li>
                          <li>They  are slightly weak in time management.</li>
                    </ul>';
            $sug['suggestion'] = '<ul>
                          <li>Set  priorities and complete your target one by one.</li>
                          <li>Never  lose hope. First complete your work and then rest. </li>
                          <li>Read  biography of some great people like Mahatma Gandhi, Abraham Lincoln, Dhiru Bhai  Ambani etc.</li>
                          <li>Be  aggressive in your work.</li>
                          <li>Learn time management skills. </li>
                </ul>';
            $sug['opposite'] = 'R';
            $sug['image'] = 'wp-content/plugins/cb-test/result_scripts/images/p6_flexibility_new.png';
            return $sug;
    }
}

function stringToArray($array,$a)
{
    $array = explode(",",$array);
    
    for($i=0;$i<sizeof($array);$i++)
        $array[$i] = Array($array[$i][0],$array[$i][1],$array[$i][2],$array[$i][3],$array[$i][4]);


    $r = array();
    $k=0;
    
    
    for($i=0; $i<strlen($a); $i++) 
    {
        $flg=1;
        for($x=0;$x<sizeof($array);$x++)
        {
            for($j=0;$j<sizeof($array[$x]);$j++)
             {
                 if($array[$x][$j]==$a[$i])
                 {
                       $flg=0;
                      break;
                 }
             }
        }
            if($flg)
            {
                $r[$k++] = $a[$i];
            }
    }
    return $r;
}

?>