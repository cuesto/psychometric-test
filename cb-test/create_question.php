<?php
    $test_id = $_GET['test_id'];
    $question_id = $_GET['question_id'];
    create_question();
    
    $table_name = $wpdb->prefix."cb_test";    
    $test_name = $wpdb->get_results("SELECT name FROM $table_name WHERE test_id = $test_id");
    $test_name = $test_name[0]->name;
    $question='';
    $options='';
    if(isset($question_id))
    {
        $table_name = $wpdb->prefix."cb_questions";    
        $question = $wpdb->get_results("SELECT * FROM $table_name WHERE question_id = $question_id");
        $question=$question[0];

        $table_name = $wpdb->prefix."cb_option";    
        $options = $wpdb->get_results("SELECT * FROM $table_name WHERE question_id = $question_id ORDER BY question_no ASC");
    }
    $table_name = $wpdb->prefix."cb_section";    
    $sections = $wpdb->get_results("SELECT * FROM $table_name WHERE test_id = $test_id ORDER BY section_no ASC");
?>
<h1><?php echo $test_name; ?></h1>
<a href="<?php echo attribute_escape( remove_query_arg('question_id')); ?>">Back</a>
    <form name="cb_add_question" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">
    Question :<textarea name="question_name" rows="4" cols="100"><?php if(!empty($question->question)) echo $question->question; ?></textarea>
    
    <table>
    <tr><td>Question Type:</td>
    <td>
        <select name="question_type">
            <option value="0" <?php if($question->type==0) echo 'selected="selected"'; ?>>Single Choice</option>
            <option value="1" <?php if($question->type==1) echo 'selected="selected"'; ?>>Multple Options correct</option>
            <option value="2" <?php if($question->type==2) echo 'selected="selected"'; ?>>Text</option>  
        </select>
    </td></tr>
    <tr><td>Duration (in secs)</td>
    <td><input type="text" name="question_duration" value="<?php echo $question->duration; ?>"/>Leave blank if not applicable</td></tr>

    <tr><td>Image</td>
    <td><input type="file" name="uploadfile" id="uploadfile" size="35" class="uploadfiles" /></td>
    </tr>

    <tr><td>Section</td><td>
        <select name="section_id">
        <?php foreach($sections as $section)
        {
            echo '<option value="'.$section->section_id.'" ';
            if($section->section_id == $question->section_id)
                echo 'selected="selected"';            
            echo ">".$section->name."</option>";
        } 
        ?>
        </select></td>
        </tr>
        <tr><td></td><td><strong>Option</strong></td><td><strong>Comment</strong></td><td><strong>Image</strong></td></tr>
    <?php
    for($i=0;$i<10;$i++)
    {
        echo "<tr><td>Option ".($i+1)."</td>";
        echo "<td><textarea name=\"que".$i."\" cols=\"80\">".$options[$i]->name."</textarea></td>";
        echo "<td><textarea name=\"ext".$i."\" cols=\"30\">".$options[$i]->name."</textarea></td>";
        echo "<td><input type=\"file\" name=\"uploadfiles".$i."\" id=\"uploadfiles".$i."\" size=\"35\" class=\"uploadfiles\" /></td>";
        echo "</tr>";
    }
    ?>
    </table>
    <input type="Submit" value="<?php if($_GET['question_id']!= 'new') echo "Update Question"; else echo "Create Question"; ?>" name="cbquiz_create_question"/>
    <input type="hidden" value="qid" name="<?php echo $question_id; ?>"/>
    </form>
    <?php
    function create_question()
    {
/**
 * Changes the upload directory to what we would like, instead of what WordPress likes.
 */
        add_filter('upload_dir', 'ml_media_upload_dir');
        function ml_media_upload_dir($upload) {
        	global $user_ID;
        	if ((int)$user_ID > 0) {
        		$upload['subdir'] = "/" . $user_ID;
        		$upload['path'] .= "/../../cb-test/";
        		$upload['url'] .= "/../../cb-test/";
        	}
        
        	return $upload;
        }            

        global $wpdb;
        if(!empty($_POST['cbquiz_create_question']) && isset($_POST['cbquiz_create_question']))
        {
//            $cbquiz_createtest = Array();
            $cbquiz_create_question['test_id'] = $_GET['test_id'];
            $cbquiz_create_question['question'] = trim($_POST['question_name']);
            $cbquiz_create_question['type'] = $_POST['question_type'];
            $cbquiz_create_question['duration'] = $_POST['question_duration'];
            $cbquiz_create_question['section_id'] = $_POST['section_id'];
            
            if($_GET['question_id']!= "new"){
                $wpdb->update( $wpdb->prefix."cb_questions", $cbquiz_create_question, Array('question_id' => $_GET['question_id']));
                $qid = $_GET['question_id'];
            }
            else {

                $table_name = $wpdb->prefix."cb_questions";
                $question = $wpdb->get_results("SELECT question_no FROM $table_name WHERE test_id = ".$_GET['test_id']." ORDER BY question_no DESC LIMIT 1");
                $question=$question[0];
                if(!($question->question_no>=0))
                    $cbquiz_create_question['question_no'] = 0;
                else
                    $cbquiz_create_question['question_no'] = $question->question_no+1;
                
                $table_name = $wpdb->prefix."cb_questions";
                $wpdb->insert($table_name,$cbquiz_create_question);
                $qid = $wpdb->get_results("SELECT question_id FROM $table_name ORDER BY question_id DESC LIMIT 1");
                $qid = $qid[0]->question_id;
            }
                        
            $name = "uploadfile";
            $file_name = $_GET['test_id'].$qid.'q';
            if(file_exists($_FILES[$name]['tmp_name']))
                $upload = wp_upload_bits("$file_name.jpg", null, file_get_contents($_FILES[$name]["tmp_name"]),null);
            $que_option = Array();
            for($i=0;$i<10;$i++)
            {
                $question_option = 'que'.$i;
                $question_option_extra = 'ext'.$i;
                $name = "uploadfiles".$i;
                
                if(isset($_POST[$question_option]))
                {
                    $que_option['name']= trim($_POST[$question_option]);
                    $que_option['question_id']= $qid;
                    $que_option['question_no']= $i;
                    $que_option['extra']= trim($_POST[$question_option_extra]);

                    $table_name = $wpdb->prefix."cb_option";    
                    $options = $wpdb->get_results("SELECT option_id FROM $table_name WHERE question_id = ".$_GET['question_id']." AND question_no = $i");
                    $options =$options[0];

                    if(isset($options->option_id))
                    {
                        $wpdb->update( $wpdb->prefix."cb_option", $que_option, Array('question_no' => $i, 'question_id'=> $_GET['question_id']));

                        $table_name = $wpdb->prefix."cb_option";    
                        $options = $wpdb->get_results("SELECT option_id FROM $table_name WHERE question_id = ".$_GET['question_id']." AND question_no = $i");
                        $options =$options[0];
                        $options= $options->option_id;


                        $name = "uploadfiles".$i;
                        $file_name = $_GET['test_id'].$_GET['question_id'].$options;
                        if(file_exists($_FILES[$name]['tmp_name']))
                            $upload = wp_upload_bits("$file_name.jpg", null, file_get_contents($_FILES[$name]["tmp_name"]),null);
                    } 
                    else {
                        $table_name = $wpdb->prefix."cb_option";
                        $option_id = $wpdb->insert($table_name,$que_option);
                        $name = "uploadfiles".$i;
                        $file_name = $_GET['test_id'].$qid.$option_id;
                        if(file_exists($_FILES[$name]['tmp_name']))
                            $upload = wp_upload_bits("$file_name.jpg", null, file_get_contents($_FILES[$name]["tmp_name"]),null);
                    }                    
                } else {
                    break;
                }
            }
//            $upload = wp_upload_bits($_FILES["uploadfiles0"]["name"], null, file_get_contents($_FILES["uploadfiles0"]["tmp_name"]),null);
//            $wpdb->insert($wpdb->prefix."cb_test",$cbquiz_createtest);


            echo "<h2>";
            _e("Question Created Successfully","cbquiz");
            echo "</h2>";
        }
    }