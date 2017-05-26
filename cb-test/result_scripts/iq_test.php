<?php
function iq_test($ref_no) {
    //Initialize

//result array
$answers = Array(
    1 => Array ('answer' => '4', 'weight' => '3'),
    2 => Array ('answer' => '2', 'weight' => '2'),
    3 => Array ('answer' => '4', 'weight' => '3'),
    4 => Array ('answer' => '2', 'weight' => '2'),
    5 => Array ('answer' => '1', 'weight' => '3'),
    6 => Array ('answer' => '3', 'weight' => '3'),
    7 => Array ('answer' => '5', 'weight' => '2'),
    8 => Array ('answer' => '1', 'weight' => '3'),
    9 => Array ('answer' => '4', 'weight' => '3'),
    10 => Array ('answer' => '1', 'weight' => '3'),
    11 => Array ('answer' => '2', 'weight' => '3'),
    12 => Array ('answer' => '2', 'weight' => '2'),
    13 => Array ('answer' => '3', 'weight' => '5'),
    14 => Array ('answer' => '5', 'weight' => '2'),
    15 => Array ('answer' => '4', 'weight' => '5'),
    16 => Array ('answer' => '4', 'weight' => '3'),
    17 => Array ('answer' => '4', 'weight' => '3'),
    18 => Array ('answer' => '4', 'weight' => '5'),
    19 => Array ('answer' => '4', 'weight' => '2'),
    20 => Array ('answer' => '4', 'weight' => '3'),
);

//fetch user's answers and store in an array
global $wpdb;
$table_name = $wpdb->prefix."cb_result_master";
$answersTicked = $wpdb->get_results("SELECT * FROM $table_name WHERE ref_no = $ref_no");
$user_id= $answersTicked[0]->uid;

$result = json_decode($answersTicked[0]->result);
//print_r($result);
//map answers and give marks;
$marksObtained = 0;
for($i=0;$i<20;$i++)
{
    //find the question number
    $question_id = $result[$i]->question_id;
    $table_name = $wpdb->prefix."cb_questions";
    $question_no = $wpdb->get_results("SELECT * FROM $table_name WHERE question_id = $question_id");
    $question_no = $question_no[0]->question_no;
    
    $option_id = $result[$i]->option_id;
    $table_name = $wpdb->prefix."cb_option";
    $option_no = $wpdb->get_results("SELECT * FROM $table_name WHERE option_id = $option_id");
    $option_no = $option_no[0]->question_no;
    if(isset($option_no))
        $option_no++;
    if($answers[$question_no]['answer']==$option_no)
        $marksObtained += $answers[$question_no]['weight'];   
}
//update_user_meta($user_id, 'dob', '10-01-1990');
//echo $marksObtained;
//algorithm to calculate score

//Fet person's age from DOB
$dob = get_user_meta($user_id, 'dob');
$ageTime = strtotime($dob[0]); // Get the person's birthday timestamp
$t = time(); // Store current time for consistency
$age = ($ageTime < 0) ? ( $t + ($ageTime * -1) ) : $t - $ageTime;
$year = 60 * 60 * 24 * 365;
$ageYears = floor($age / $year);
$IQ = floor($marksObtained/$ageYears*100);

$info = '<p>How should you <strong>interpret an IQ score</strong>?</p>
<p>
<table cellspacing="0px" cellpadding="2px" id="tabel-pagina" border"1px">
    <tbody>
        <tr>
            <th>IQ</th>
            <th>Percentage of the population with this IQ</th>
            <th>Interpretation</th>
        </tr>
        <tr>
            <td>&gt; 130</td>
            <td>2.1</td>
            <td>Very gifted</td>
        </tr>
        <tr>
            <td>121-130</td>
            <td>6.4</td>
            <td>Gifted</td>
        </tr>
        <tr>
            <td>111-120</td>
            <td>15.7</td>
            <td>Above average intelligence</td>
        </tr>
        <tr>
            <td>90-110</td>
            <td>51.6</td>
            <td>Average intelligence</td>
        </tr>
        <tr>
            <td>80-89</td>
            <td>15.7</td>
            <td>Below average intelligence</td>
        </tr>
        <tr>
            <td>70-79</td>
            <td>6.4</td>
            <td>Retarded</td>
        </tr>
    </tbody>
</table>
</p>
<p>&nbsp;</p>
<p>About 2% of the population has an IQ score lower than 69. Such a low IQ score often is hard to measure using a regular intelligence test. Neither can very high IQ scores be determined accurately. This is because you need a lot of reference measurements to determine a specific score reliably. As very high and very low IQ scores simply do not occur often, this is very hard.</p>
<hr>
</div>
<br/><br/>
For more details please feel free to <a href="'.site_url('contact-us').'">contact us.</a>';
return "Your IQ is <strong>".$IQ."</strong><br/><br/>".$info;
}
?>