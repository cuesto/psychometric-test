<?php
if(isset($_POST['update_question_no_submit']))
{

    $qno=0;
    while(1)
    {
        $q_name = 'que'.$qno;
        if(isset($_POST[$q_name]))
        {
            $question_id = $_POST['queid'.$qno];
            $set['question_no'] = $_POST[$q_name];
            $wpdb->update($wpdb->prefix."cb_questions",$set,Array('question_id'=>$question_id));
            $qno++; 
        }
        else {
            break;
        }
    }
}

if(isset($_POST['apply_test_changes']))
{
    $table_name = $wpdb->prefix."cb_test";
    $post_id = $wpdb->get_results("SELECT post_id FROM $table_name WHERE test_id=".$_GET['test_id']);
    $new_post['ID'] = $post_id[0]->post_id;
    $new_post['post_content'] = cbquiz_user($_GET['test_id']);
    wp_update_post($new_post);
    echo "<h2>Test Modifications have been successfuly applied</h2>";
}

if(isset($_POST['update_section_no_submit']))
{

    $sno=0;
    while(1)
    {
        $s_name = 'sec'.$sno;
        if(isset($_POST[$s_name]))
        {
            $section_id = $_POST['secid'.$sno];
            $set['section_no'] = $_POST[$s_name];
            $wpdb->update($wpdb->prefix."cb_section",$set,Array('section_id'=>$section_id));
            $sno++; 
        }
        else {
            break;
        }
    }
}

?>
<?php
$test_id = $_GET['test_id'];

$table_name = $wpdb->prefix."cb_test";    
$test_name = $wpdb->get_results("SELECT name FROM $table_name WHERE test_id = $test_id");
$test_name = $test_name[0]->name;

$table_name = $wpdb->prefix."cb_questions";
$questions = $wpdb->get_results("SELECT * FROM $table_name WHERE test_id = $test_id ORDER BY question_no ASC");

$table_name = $wpdb->prefix."cb_section";
$sections = $wpdb->get_results("SELECT * FROM $table_name WHERE test_id = $test_id  ORDER BY section_no ASC");
?>
<br />
<h1><?php echo $test_name; ?></h1>
<h3> <strong>Actions -> </strong>
<a href="<?php echo attribute_escape( add_query_arg( 'question_id', 'new' ) ); ?>">Create Question</a> | 
<a href="<?php echo attribute_escape( add_query_arg( 'section_id', 'new' ) ); ?>">Create Section</a> |  
<a href="<?php echo attribute_escape( add_query_arg( 'publish-test', 'true' ) ); ?>">Publish changes</a>
</h3>
<h2>Questions</h2><form name="update_question_no" method="post" action="">
<table width="100%" border="1">
<tr>
<th>Question Number</th>
<th>Name</th>
<th>Duration</th>
<th>Section ID</th>
<th>Type</th>
<th>Edit</th>
<th>Delete</th>
</tr>
<?php
$qno =0;
foreach($questions as $question)
{  
    echo "<tr>";
        echo '<td><input type="text" name="que'.$qno.'" value="'.$question->question_no.'"/></td>';
        echo '<td>'.$question->question.'</td>';
        echo '<td>'.$question->duration.'</td>';
        echo '<td>'.$question->section_id.'</td>';
        echo '<td>'.$question->type.'</td>';
        echo '<td><a href="'.attribute_escape( add_query_arg( 'question_id', $question->question_id ) ).'">Edit</a></td>';
        echo '<td><a href="#">Delete</a></td>';
        echo '<input type="hidden" name="queid'.$qno.'" value="'.$question->question_id.'" />';
    echo "</tr>";
        $qno++;
}

?>
</table>
<input type="submit" name="update_question_no_submit" value="Update Question Number"/>
</form>

<br />
<h2>Sections</h2><form name="update_section_no" method="post" action="">
<table width="100%" border="1">
<tr>
<th>Section Number</th>
<th>Name</th>
<th>Duration</th>
<th>Section ID</th>
<th>Edit</th>
<th>Delete</th>
</tr>
<?php
$sno =0;
foreach($sections as $section)
{
    echo "<tr>";
        echo '<td><input type="text" name="sec'.$sno.'" value="'.$sno.'"/></td>';
        echo '<td>'.$section->name.'</td>';
        echo '<td>'.$section->duration.'</td>';
        echo '<td>'.$section->section_id.'</td>';
        echo '<td><a href="'.attribute_escape( add_query_arg( 'section_id', $section->question_id ) ).'">Edit</a></td>';
        echo '<td><a href="#">Delete</a></td>';
        $sno++;
    echo '<input type="hidden" name="secid'.$sno.'" value="'.$section->section_id.'" />';
    echo "</tr>";
}

?>
</table>
<input type="submit" name="update_section_no_submit" value="Update Section Number"/>
</form>


<form name="cbquiz_apply_test_changes" method="post" action="">
<input type="submit" name="apply_test_changes" value="Apply changes in test"/>
</form>
