<br />
<form method="GET">
	<input type="hidden" name="page" value="cbquiz-view-results"/>
  <input type="text" name="test_id" placeholder="Input test id" value="<?php if(isset($_REQUEST['results'])){ echo $_REQUEST['test_id']; } ?>">
  <button type="submit" name="results" value="view">Search Test</button>
</form>
<?php
	global $wpdb;
	if (isset($_REQUEST['results'])) {
		$test_id = $_REQUEST['test_id'];
    	$table_name = $wpdb->prefix."cb_test";
    	$query = $wpdb->query("SELECT test_id FROM $table_name WHERE test_id = $test_id");
    	if ($query) {
    		include 'cbquiz_view_results.php';
    	}
    	else{
    		echo "Wrong ID";
    	}
	}
?>
