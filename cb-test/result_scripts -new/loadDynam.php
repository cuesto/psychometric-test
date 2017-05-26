<?php


  //include all the pages
    //Initialize
    for($i=0;$i<5;$i++){
        $res[$i]['a']=0;
        $res[$i]['b']=0;
    }
    
    //result array
    $answers = Array(
        0 => Array (
            1 => Array ('answer' => 'C'),
            2 => Array ('answer' => 'P'),
            3 => Array ('answer' => 'P'),
            4 => Array ('answer' => 'P'),
            5 => Array ('answer' => 'C'),
            6 => Array ('answer' => 'P'),
            7 => Array ('answer' => 'C'),
            8 => Array ('answer' => 'P'),
            9 => Array ('answer' => 'P'),
            10 => Array ('answer' => 'P'),
            11 => Array ('answer' => 'P'),
            12 => Array ('answer' => 'P'),
            13 => Array ('answer' => 'C'),
            14 => Array ('answer' => 'C'),
            15 => Array ('answer' => 'P'),
            16 => Array ('answer' => 'C'),
            17 => Array ('answer' => 'C'),
            18 => Array ('answer' => 'P'),
            19 => Array ('answer' => 'C'),
            20 => Array ('answer' => 'C'),
        ),
        1 => Array (
            1 => Array ('answer' => 'D'),
            2 => Array ('answer' => 'M'),
            3 => Array ('answer' => 'D'),
            4 => Array ('answer' => 'D'),
            5 => Array ('answer' => 'D'),
            6 => Array ('answer' => 'D'),
            7 => Array ('answer' => 'D'),
            8 => Array ('answer' => 'D'),
            9 => Array ('answer' => 'D'),
            10 => Array ('answer' => 'M'),
            11 => Array ('answer' => 'D'),
            12 => Array ('answer' => 'D'),
            13 => Array ('answer' => 'D'),
            14 => Array ('answer' => 'M'),
            15 => Array ('answer' => 'D'),
            16 => Array ('answer' => 'D'),
            17 => Array ('answer' => 'D'),
            18 => Array ('answer' => 'D'),
            19 => Array ('answer' => 'D'),
            20 => Array ('answer' => 'D'),
        ),
        2 => Array (
            1 => Array ('answer' => 'E'),
            2 => Array ('answer' => 'E'),
            3 => Array ('answer' => 'E'),
            4 => Array ('answer' => 'I'),
            5 => Array ('answer' => 'E'),
            6 => Array ('answer' => 'E'),
            7 => Array ('answer' => 'E'),
            8 => Array ('answer' => 'E'),
            9 => Array ('answer' => 'E'),
            10 => Array ('answer' => 'E'),
            11 => Array ('answer' => 'E'),
            12 => Array ('answer' => 'E'),
            13 => Array ('answer' => 'E'),
            14 => Array ('answer' => 'E'),
            15 => Array ('answer' => 'I'),
            16 => Array ('answer' => 'I'),
            17 => Array ('answer' => 'E'),
            18 => Array ('answer' => 'E'),
            19 => Array ('answer' => 'I'),
            20 => Array ('answer' => 'E'),
        ),
        3 => Array (
            1 => Array ('answer' => 'Q'),
            2 => Array ('answer' => 'S'),
            3 => Array ('answer' => 'S'),
            4 => Array ('answer' => 'Q'),
            5 => Array ('answer' => 'S'),
            6 => Array ('answer' => 'S'),
            7 => Array ('answer' => 'Q'),
            8 => Array ('answer' => 'S'),
            9 => Array ('answer' => 'Q'),
            10 => Array ('answer' => 'S'),
            11 => Array ('answer' => 'Q'),
            12 => Array ('answer' => 'S'),
            13 => Array ('answer' => 'S'),
            14 => Array ('answer' => 'Q'),
        ),
        4 => Array (
            1 => Array ('answer' => 'R'),
            2 => Array ('answer' => 'R'),
            3 => Array ('answer' => 'R'),
            4 => Array ('answer' => 'R'),
            5 => Array ('answer' => 'R'),
            6 => Array ('answer' => 'R'),
            7 => Array ('answer' => 'R'),
            8 => Array ('answer' => 'F'),
            9 => Array ('answer' => 'F'),
            10 => Array ('answer' => 'R'),
            11 => Array ('answer' => 'R'),
            12 => Array ('answer' => 'R'),
            13 => Array ('answer' => 'R'),
            14 => Array ('answer' => 'F'),
            15 => Array ('answer' => 'F'),
            16 => Array ('answer' => 'R'),
            17 => Array ('answer' => 'R'),
            18 => Array ('answer' => 'R'),
            19 => Array ('answer' => 'R'),
            20 => Array ('answer' => 'R'),
        ),
    );
    
    //fetch user's answers and store in an array
    global $wpdb;
    $table_name = $wpdb->prefix."cb_result_master";
    $answersTicked = $wpdb->get_results("SELECT * FROM $table_name WHERE ref_no =170113000402");
    $result = json_decode($answersTicked[0]->result);
    //map answers and give marks;
    $marksObtained = 0;
    for($i=0;$i<sizeof($result);$i++)
    {
        //find the question number
        $question_id = $result[$i]->question_id;
        $table_name = $wpdb->prefix."cb_questions";
        $question_no = $wpdb->get_results("SELECT * FROM $table_name WHERE question_id = $question_id");
        $section_id = $question_no[0]->section_id; 
        
        $question_no = $question_no[0]->question_no;

        //fetch option number
        $option_id = $result[$i]->option_id;
        $table_name = $wpdb->prefix."cb_option";
        $option_no = $wpdb->get_results("SELECT * FROM $table_name WHERE option_id = $option_id");
        $option_no = $option_no[0]->question_no;
       
           //get section number
        $table_name = $wpdb->prefix."cb_section";
        $section_no = $wpdb->get_results("SELECT * FROM $table_name WHERE section_id = $section_id");
        $section_no = $section_no[0]->section_no; 
        //echo $section_no."<br/>";
        
        
        /**
         * Separate Question Index and Section Index from the given question number
         * Section Index is zero index but question index is 1 index
         */
        if($question_no<=70)
        {
            $section_index = $question_no%5-1;
            if($section_index==-1)
            {
                $section_index=4;
                $question_index = ($question_no-$question_no%5)/5;
            } else
                $question_index = ($question_no-$question_no%5)/5+1;
        } else if($question_no>= 71){
            $question_no -= 70;
            $section_index = $question_no%4-1;
            if($section_index==-1)
            {
                $section_index=4;
                $question_index = ($question_no-$question_no%4)/4;
            } else
                $question_index = ($question_no-$question_no%4)/4+1;            
            $question_index += 14;
        } else {
            echo "Something went wrong. Too many questions to handle";
        }
        
//        echo $question_index.",".$section_index
        
        if($option_no == 0)
        {
            if(cb_test_helper123($answers[$section_index][$question_index]['answer'])==0)
            {
                $res[$section_index]['a']++;
            }else if(cb_test_helper123($answers[$section_index][$question_index]['answer'])==1)
            {
                $res[$section_index]['b']++;
            }else 
            {
                print_r($answers[$section_index][$question_index]);
                echo "Something is wrong $section_no , $question_no,";
            }
        } else if($option_no == 1) {
            if(cb_test_helper123($answers[$section_index][$question_index]['answer'])==1)
            {
                $res[$section_index]['a']++;
            }else if(cb_test_helper123($answers[$section_index][$question_index]['answer'])==0)
            {
                $res[$section_index]['b']++;
            }else 
            {
                print_r($answers[$section_index][$question_index]);
                echo "Something is wrong $section_no , $question_no,";
            }
        } else {
            echo "Some error occured in Question Number $i,$option_no,$option_id,$section_no";
        }
        
    }

    $code = Array();
    
    
    
    if($res[0]['a']>$res[0]['b'])
        $code[] = "P";
    else
        $code[] = "C";
    
    if($res[1]['a']>$res[1]['b'])
        $code[] = "D";
    else
        $code[] = "M";
    
    if($res[2]['a']>$res[2]['b'])
        $code[] = "E";
    else
        $code[] = "I";
    
    if($res[3]['a']>$res[3]['b'])
        $code[] = "Q";
    else
        $code[] = "S";
    
    if($res[4]['a']>$res[4]['b'])
        $code[] = "R";
    else
        $code[] = "F";
    $compressedCode = implode("",$code);
    
    echo "Compressed Code : ".$compressedCode;
    
    $result = $res; 
    
    
    echo "<br/>Result Part 1 = ";
    echo $res[0]['a']."..";
    echo $res[0]['b'];
    
    
    echo "<br/>Result Part 2 = ";
    echo $res[1]['a']."..";
    echo $res[1]['b'];
    
     echo "<br/>Result Part 3 = ";
    echo $res[2]['a']."..";
    echo $res[2]['b'];
    
     echo "<br/>Result Part 4 = ";
    echo $res[3]['a']."..";
    echo $res[3]['b'];
    
     echo "<br/>Result Part 5 = ";
    echo $res[4]['a']."..";
    echo $res[4]['b'];
    
    $var1=$res[0]['a'];
    $var2=$res[0]['b'];
    
    $var1=($var1/20)*100;
    $var2=($var2/20)*100;
    
    
    $var3=$res[1]['a'];
    $var4=$res[1]['b'];
    
    $var3=($var3/20)*100;
    $var4=($var4/20)*100;
    
    
    
    
    $var5=$res[2]['a'];
    $var6=$res[2]['b'];
    
    $var5=($var5/20)*100;
    $var6=($var6/20)*100;
    
    
    
    
    $var7=$res[3]['a'];
    $var8=$res[3]['b'];
    
    $var7=($var7/14)*100;
    $var8=($var8/14)*100;
    
    
    
    $var9=$res[4]['a'];
    $var10=$res[4]['b'];
    
    $var9=($var9/20)*100;
    $var10=($var10/20)*100;
    
   
    echo "<br/>".$var1;
    echo "<br/>".$var2;
    
    echo "<br/>".$var3;
    echo "<br/>".$var4;
    
    echo "<br/>".$var5;
    echo "<br/>".$var6;
    
    echo "<br/>".$var7;
    echo "<br/>".$var8;
    
    echo "<br/>".$var9;
    echo "<br/>".$var10;
    
?>