<?php
    $test_id = $_GET['test_test'];
    create_test();
    
    if($test_id>1)
    {
        $table_name = $wpdb->prefix."cb_test";    
        $test_details = $wpdb->get_results("SELECT * FROM $table_name WHERE test_id = $test_id");
        $test_details=$test_details[0];
        $test_name = $test_name->test_name;
    } else {
        $test_name = "Create new test";
    }
    function create_test()
    {
        global $wpdb;
    $test_id = $_GET['test_test'];
        
        if(isset($_POST['cbquiz_createtest_submit']))
        {
            $cbquiz_createtest = Array();
            $cbquiz_createtest['name'] = $_POST['cbquiz_name'];
            $cbquiz_createtest['description'] = $_POST['cbquiz_description'];
            $cbquiz_createtest['cost'] = $_POST['cbquiz_cost'];
            $cbquiz_createtest['duration'] = $_POST['cbquiz_duration'];
            $cbquiz_createtest['rules'] = $_POST['cbquiz_rules'];
            $cbquiz_createtest['about_test'] = $_POST['about_test'];
            $cbquiz_createtest['test_advantage'] = $_POST['test_advantage'];
            $cbquiz_createtest['test_features'] = $_POST['test_features'];
            $cbquiz_createtest['test_process'] = $_POST['test_process'];
            $cbquiz_createtest['sample_report'] = $_POST['sample_report'];
 
            if($test_id!= "new"){
                $wpdb->update( $wpdb->prefix."cb_test", $cbquiz_createtest, Array('test_id' => $test_id));
            }
            else {
                $table_name = $wpdb->prefix."cb_test";
                $wpdb->insert($table_name, $cbquiz_createtest);
            }
            
            echo "<h2>";
            _e("Test Created/Updated Successfully","cbquiz");
            echo "</h2>";
        }
    }
?>
<h1><?php echo $test_name; ?></h1>
<a href="<?php echo attribute_escape( remove_query_arg('test_test')); ?>">Back</a>
    <form name="cb_add_section" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    
    <table>
			<form name="cbtest_edit" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<input type="hidden" name="oscimp_hidden" value="Y">
				<p><?php _e("Name: " ); ?><input type="text" name="cbquiz_name" value="<?php echo $test_details->name; ?>" size="20"></p>
				<p><?php _e("Description: " ); ?><textarea rows="4" cols="50" name="cbquiz_description"><?php echo $test_details->description; ?></textarea></p>
				<p><?php _e("Cost: " ); ?><input type="text" name="cbquiz_cost" value="<?php echo $test_details->cost; ?>" size="20"></p>
				<p><?php _e("Duration: " ); ?><input type="text" name="cbquiz_duration" value="<?php echo $test_details->duration; ?>" size="20"><?php _e("Enter Duration in seconds only" ); ?></p>
				<p><?php _e("Rules: " ); ?><textarea rows="4" cols="50" name="cbquiz_rules"><?php echo $test_details->rules; ?></textarea></p>
				<p><?php _e("About test: " ); ?><textarea rows="4" cols="50" name="about_test"><?php echo $test_details->about_test; ?></textarea></p>
				<p><?php _e("Test advantage: " ); ?><textarea rows="4" cols="50" name="test_advantage"><?php echo $test_details->test_advantage; ?></textarea></p>
				<p><?php _e("Test features: " ); ?><textarea rows="4" cols="50" name="test_features"><?php echo $test_details->test_features; ?></textarea></p>
				<p><?php _e("Test process: " ); ?><textarea rows="4" cols="50" name="test_process"><?php echo $test_details->test_process; ?></textarea></p>
				<p><?php _e("Sample Report: " ); ?><textarea rows="4" cols="50" name="sample_report"><?php echo $test_details->sample_report; ?></textarea></p>
				<p class="submit">
				<input type="submit" name="cbquiz_createtest_submit" value="<?php _e('Create/Update Test', 'cbquiz' ) ?>" />
				</p>
			</form>