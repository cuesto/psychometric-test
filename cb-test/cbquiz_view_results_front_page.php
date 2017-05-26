<br />
<form method="GET">
	<input type="hidden" name="page" value="cbquiz-view-results"/>
  <input type="hidden" name="test_id" placeholder="Input test id" value=17>
  <button type="submit" name="results" value="view" class="button">View Results</button>
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
    		echo "<h2>Wrong ID</h2>";
    	}
    }
?>
