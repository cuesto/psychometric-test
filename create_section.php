<?php
    $test_id = $_GET['test_id'];
    $section_id = $_GET['section_id'];
    create_section();
    
    $table_name = $wpdb->prefix."cb_test";    
    $test_name = $wpdb->get_results("SELECT name FROM $table_name WHERE test_id = $test_id");
    $test_name = $test_name[0]->name;

    $section='';
    if(isset($section_id))
    {
        $table_name = $wpdb->prefix."cb_section";    
        $section = $wpdb->get_results("SELECT * FROM $table_name WHERE section_id = $section_id");
        $section=$section[0];
    }
?>
<h1><?php echo $test_name; ?></h1>
<a href="<?php echo attribute_escape( remove_query_arg('section_id')); ?>">Back</a>
    <form name="cb_add_section" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    
    <table>
    <tr>
        <td>Section Name</td>
        <td><input type="text" name="section_name" value="<?php echo $section->name; ?>"/></td>
    </tr>
    <tr>
        <td>Duration (in secs)</td>
        <td><input type="text" name="section_duration" value="<?php echo $section->duration; ?>"/>Leave blank if not applicable</td>
    </tr>
    <tr>
        <td>Description</td>
        <td><textarea name="description" rows="4" cols="100"><?php if(!empty($section->description)) echo $section->description; ?></textarea></td>
    </tr>
    </table>
    <input type="Submit" value="<?php if($_GET['section_id']!= 'new') echo "Update Section"; else echo "Create Section"; ?>" name="cbquiz_create_section"/>
    <input type="hidden" value="qid" name="<?php echo $section_id; ?>"/>
    </form>
<?php
    function create_section()
    {
        global $wpdb;
        if(isset($_POST['cbquiz_create_section']))
        {
            $cbquiz_create_section['test_id'] = $_GET['test_id'];
            $cbquiz_create_section['name'] = $_POST['section_name'];
            $cbquiz_create_section['description'] = $_POST['description'];
            $cbquiz_create_section['duration'] = $_POST['section_duration'];
            
            if($_GET['section_id']!= "new"){
                $wpdb->update( $wpdb->prefix."cb_section", $cbquiz_create_section, Array('section_id' => $_GET['section_id']));
            }
            else {
                $table_name = $wpdb->prefix."cb_section";
                $wpdb->insert($table_name, $cbquiz_create_section);
            }
            echo "<h2>";
            _e("Section Created Successfully","cbquiz");
            echo "</h2>";
        }
    }
?>