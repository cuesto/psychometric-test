<?php
	wp_enqueue_style("stylecss",plugins_url( 'css/style.css', __FILE__ ));
	if (!is_user_logged_in()) {
		echo '<h2>OOOPPPPPPSSSSS something went wrong. Please <a href="'.site_url().'">click here</a> to go back.</h2>';
	    exit();
	}
	else {
		global $wpdb;
		$user_id = get_current_user_id();
		$table1 = $wpdb->prefix . "cb_result_master";
		$table2 = $wpdb->prefix . "users";
		$table3 = $wpdb->prefix . "cb_test";
		$sql = "SELECT t1.ref_no AS ref_no, t3.name as name FROM `$table1` t1, `$table2` t2, `$table3` t3 WHERE t1.uid = t2.ID AND t1.test_id = t3.test_id AND t2.ID = '".$user_id."' ";
		$reference_nos = $wpdb->get_results($sql);
		if(empty($reference_nos)) {
			echo "<div><h2 style='color: red'>You don't have any report!</h2></div>";
		}
		else { 
			//print_r($reference_nos);
?>
<div>
	<div align="center">
		<table class="order-table" cellpadding="5"  cellspacing="10">
			<th>SL</th>
			<th>Test Name</th>
			<th>Reference No</th>
			<th>Report</th>
			<?php
				$i = 1; 
				foreach ($reference_nos as $reference_no) {
					echo "<tr>";
					echo "<td>".$i++."</td>";
					echo "<td>".$reference_no->name."</td>";
					echo "<td>".$reference_no->ref_no."</td>";
					echo "<td><a href='".site_url(get_option('brand_result_page'))."/?ref_no=".$reference_no->ref_no."' target='_blank'>Download Report</td>";
					echo "</tr>";
				}
			?>
		</table>
	</div>
</div>

<?php } } ?>