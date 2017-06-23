<?php 
	global $wpdb;
	$error = "";
	$result[0] = get_option('payu_salt');
	$result[1] = get_option('payu_merchant');
	$result[2] = get_option('payu_status');
	if (isset($_POST['pay_submitbtn'])) {
		$salttid = $_POST['pay_salt'];
		$merchantid = $_POST['pay_merchant'];
		$status = 0;
		if (!empty($_POST['pay_salt']) && !empty($_POST['pay_merchant'])) {
			if (empty($result[0]) && empty($result[1]) && empty($result[2])) {
				add_option('payu_salt',$salttid);
				add_option('payu_merchant',$merchantid);
				add_option('payu_status',$status);
				
				$result[0] = get_option('payu_salt');
				$result[1] = get_option('payu_merchant');
				$result[2] = get_option('payu_status');
			}
			else {
				$status = $_POST['status'];
				update_option('payu_salt',$salttid);
				update_option('payu_merchant',$merchantid);
				update_option('payu_status',$status);
				
				$result[0] = get_option('payu_salt');
				$result[1] = get_option('payu_merchant');
				$result[2] = get_option('payu_status');
			}
		}
		else {
			$error = "<strong style='color:red'>Please fill all fields.</strong>";
		}
	}
	/*if (isset($_POST['statusbtn'])) {
		$status = $_POST['status'];
		$wpdb->update($table_name,Array('payu_status' => $status),Array('id' => 1));
		$result = $wpdb->get_results($sql);
	}*/
?>
<div>
	<div>
		<h2>PayUMoney Settings</h2>
	</div>
	<div>
		<form method="POST" class="form">
			<table class="table">
				<tr>
					<td>
						<label><strong>Salt Id</strong></label>
					</td>
					<td>
						<input type="text" name="pay_salt" value="<?php echo $result[0]; ?>">
					</td>
				</tr>
				<tr>
					<td>
						<label><strong>Merchant Id</strong></label>
					</td>
					<td>
						<input type="text" name="pay_merchant" value="<?php echo $result[1]; ?>">
					</td>
				</tr>
				<tr>
					<td>
						<h2>Choose the 
					</td>
					<td>
						<h2>mode of payment gateway</h2>
					</td>
				<tr>
					<td>
						<input type="radio" name="status" value="0" <?php if($result[2] == 0) echo " checked = 'ckecked'"; ?>>
					
						<lable>Test Mode</lable><br />
					</td>
				</tr>
				<tr>
					<td>
						<input type="radio" name="status" value="1" <?php if($result[2] == 1) echo " checked = 'ckecked'"; ?>>
					
						<lable>Live Mode</lable>
					</td>
				</tr>
				<tr>
					<td>
						<br><button type="submit" class="button button-primary" name="pay_submitbtn">Save</button>
					</td>
					<td>
						<?php echo $error; ?>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>