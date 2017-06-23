<?php 
		$test_id = $_GET['test_id'];
	    $ref_no = $_GET['ref_no'];
	    
	    if(isset($_GET['coupon']))
	        $coupon = $_GET['coupon'];
	    
	    
	    if(!isset($ref_no))
	        ;// redirect to homepage
	    
	    //Set initial parameters
	    $ref_no_details = ref_no_details($ref_no,$coupon);
	    $user_info = get_userdata(get_current_user_id());    

	    $CBConnect = new CB_Connect;
	    $firstName = $CBConnect->get_first_name();
	    echo "<div class='payment-details'>";
	    if(isset($coupon))
	    {
	        echo "<strong><font size=\"+1\" color=\"red\">".$ref_no_details['status']."</font></strong>";
	    }    
	    //print_r($ref_no_details);
	    echo '<table class="order-table" cellpadding="5"  cellspacing="10">
	        <tr>
	        	<th>Name</th>
	        	<th>Cost</th>
	        </tr>
	        <tr>
	           	<td>'.$ref_no_details['item_name'].', (Reference Number: '.$ref_no.')</td>
	            <td>'.$ref_no_details['item_cost']."(".$ref_no_details['currency'].')</td>
	        </tr>';
	    if(isset($coupon) && $ref_no_details['discount']>0)
	    {
	        echo '<tr>
	            <td>Discount</td>
	            <td>'.$ref_no_details['discount']."(".$ref_no_details['currency'].')</td>
	        </tr>';
	    }
	    echo  '
	        <tr class="total">
	            <td>Total</td>
	            <td>'.$ref_no_details['total']."(".$ref_no_details['currency'].')</td>
	        </tr>
	    </table>';    

	    echo "<hr/>";
	    echo '<p>Got a coupon? Enter it here.</p><form name="coup" method="get">
	        <input type="hidden" name="ref_no" value="'.$ref_no.'"/>
	        <input type="hidden" name="test_id" value="2"/>
	        <input type="text" name="coupon" value="'.$_GET['coupon'].'">
	        <input type="submit" class="btn"/>
	    </form>';

	    echo "</div>";
?>