<?php

function cbquiz_cache_test($test_id)
{
    $result = Array();
    
    global $wpdb;
    
    $table_name = $wpdb->prefix . "cb_test";
    $test = $wpdb->get_results("SELECT duration FROM $table_name WHERE test_id = $test_id");
    
    $result['test_id'] = $test_id;
    $result['duration'] = $test[0]->duration;
    
        
    $table_name = $wpdb->prefix . "cb_section";
    $sections = $wpdb->get_results("SELECT name,section_id,duration FROM $table_name WHERE test_id = $test_id ORDER BY section_no ASC");
    
    foreach ($sections as $section)
    {
        $result['sections'][$section->section_id]['section_id'] =  $section->section_id;
        $result['sections'][$section->section_id]['name'] =  $section->name;
        $result['sections'][$section->section_id]['duration'] =  $section->duration;
        
        $table_name = $wpdb->prefix . 'cb_questions';
        $questions = $wpdb->get_results("SELECT * FROM $table_name WHERE section_id =". $section->section_id . " ORDER BY question_no ASC");
        
        foreach ($questions as $question)
        {
            $result['sections'][$section->section_id]['questions'][$question->question_id]['question_id'] = $question->question_id;
            $result['sections'][$section->section_id]['questions'][$question->question_id]['question'] = $question->question;
                        
            $table_name = $wpdb->prefix . 'cb_option';
            $options = $wpdb->get_results("SELECT * FROM $table_name WHERE question_id =" .$question->question_id . " ORDER BY question_no ASC");
    
            $option_array = array();
    
            foreach ($options as $option)
            {
                $option_value = str_replace('\\', '', $option->name);
                $path = get_bloginfo('wpurl') . "/wp-content/uploads/cb-test/" . $test_id . $question->
                    question_id . $option->option_id . ".jpg";
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/wp-content/uploads/cb-test/" . $test_id .
                    $question->question_id . $option->option_id . ".jpg"))
                {
                    $option_value .= "<img src=\"$path\" />";
                }
                array_push($option_array, array('value' => $option->option_id, 'label' => $option_value));
            }       
            $result['sections'][$section->section_id]['questions'][$question->question_id]['option_array'] = $option_array;
        }
    }
    $set['cache'] = json_encode($result);
    $wpdb->update($wpdb->prefix."cb_test",$set,Array('test_id'=>$test_id));    
    echo "<h2><strong>Successfully published</strong></h2>";
}
?>