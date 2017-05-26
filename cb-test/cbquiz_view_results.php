<br/><br/>
<h1>Search Candidate</h1>
<form name="customer_search" method="GET">
    <input type="hidden" name="page" value="cbquiz-view-results"/>
    <input type="hidden" name="test_id" value="17"/>
    <input type="hidden" name="results" value="view"/>
    <input type="text" name="query"/>
    <input type="submit" name="submit_query" value="Search Candidate"/>
</form>

<?php
    update_payment();
$test_id = $_GET['test_id'];
$query = $_GET['query'];
$table_name = $wpdb->prefix."cb_test";    
$test_name = $wpdb->get_results("SELECT name FROM $table_name WHERE test_id = $test_id");
$test_name = $test_name[0]->name;

$table_name1 = $wpdb->prefix."cb_result_master";
$table_name2 = $wpdb->prefix."users";

$specific_test = "";
if(isset($_GET['test_id']))
    $specific_test = "WHERE (result.test_id = $test_id )";


if(isset($query))
    $specific_test = "WHERE (result.ref_no like '%".$query."%' or user.ID like'%".$query."%' or user.display_name like'%".$query."%' or user.user_email like'%".$query."%' or user.user_nicename like'%".$query."%')";
$results = $wpdb->get_results("SELECT result.uid,
       user.ID,
       user.user_login,
       user.user_email,
       user.display_name,
       user.user_nicename,
       user.contact_number,
       user.dob,
       result.test_id,
       result.payment,
       result.time,
       result.ref_no
  FROM    $table_name1 result
       LEFT OUTER JOIN
          $table_name2 user
       ON (result.uid = user.ID)
       $specific_test
ORDER BY result.time DESC
LIMIT 70");
    function update_payment()
    {
        global $wpdb;
        if(isset($_POST['payment_done']))
        {
            $cbquiz_payment['ref_no'] = $_POST['payment_ref_no'];
            $payment_done = $_POST['payment_done'];
            
            $cbquiz_payment['payment'] = 1;
            if($payment_done=='Yes')
                $cbquiz_payment['payment'] = 0;
                
            if(isset($_POST['payment_notify']))
            {
                    $email_footer = new CB_Connect;
                    $email_footer=$email_footer->get_email_footer();
                    
                    $uid = $_POST['uid'];
                    $user_info = get_userdata($uid);
                    $name = $user_info->display_name;
                    $email = $user_info->user_email; 
                    
                    $ref_no = $cbquiz_payment['ref_no'];
                    
                    
                    //mail results to the user.
                    $from = '"Career Breeder" <contactus@careerbreeder.com>';
	                $headers = 'From: '.$from . "\r\n
                    List-Unsubscribe: <mailto:contactus@careerbreeder.com>, <http://careerbreeder.com/member/unsubscribe/?listname=espc-tech@domain.com?id=12345N>";
                    // Always set content-type when sending HTML email
                    $headers .= "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

	                $subject = "Your Career Indicator Report is available";

                    $message = "Greetings " . $name . ",<br/>
                        <br/>
                           Thank you for taking <a href=\"".site_url()."career-indicator/\">Career Indicator Assessment</a>. Through this assessment you can assess your career choices against your personality, aptitude and interests.<br/>
                         <br/>                         <br/>
                         
                         <a href=\"".site_url()."/view-results?ref_no=" . $ref_no . "&test_id=2&affiliates=$campaignID \"><strong>Click here to view your report</strong></a>.<br/> 
                         <br/>".$email_footer;


	                wp_mail( $email, $subject, $message, $headers ); //send email to client
	                wp_mail( "contactus@careerbreeder.com", $subject, $message, $headers ); // send one email to admin as well
                
            }
            
            $wpdb->update( $wpdb->prefix."cb_result_master", $cbquiz_payment, Array('ref_no' => $cbquiz_payment['ref_no']));
            echo "<h2>";
            _e("Settings saved","cbquiz");
            echo "</h2>";
        }
    }


?>
<br />
<h1><?php echo $test_name; ?></h1>
<h2>Last 50 Applications</h2>
<table width="100%" border="1">
<tr>
    <th>Reference Number</th>
    <th>Name</th>
    <th>Paid</th>
    <th>Time</th>
    <th>Contact Number</th>
    <th>E-mail ID</th>
    <th>View</th>
    <th>Payment URL</th>
</tr>
<?php

$qno =0;
foreach($results as $result)
{  
    $user_info = get_userdata($result->ID);
    $contact_number = get_user_meta($result->ID, 'contact_number');
    $contact_number = $contact_number[0];
    if(empty($contact_number))
    {
        $contact_number = $result->contact_number;
        $dob = $result->dob;
        if(!empty($result->display_name))
            $name = $result->display_name;
        else
            $name = $result->user_nicename;
            
       	wp_insert_user( array('ID' =>$user_id , 'dob'=> $dob ));
       	wp_insert_user( array('ID' =>$user_id , 'user_nicename'=> $name ));
       	wp_insert_user( array('ID' =>$user_id , 'nickname'=> $name ));
        wp_update_user( array('ID' =>$user_id , 'display_name'=> $name ));            
       	wp_update_user( array('ID' =>$user_id , 'contact_number'=> $contact_number ));        
       	update_user_meta( $user_id, 'dob', $dob );
        update_user_meta( $user_id, 'nickname', $name );
        update_user_meta( $user_id, 'display_name', $name );
       	update_user_meta( $user_id, 'name', $name );
        update_user_meta( $user_id, 'contact_number', $contact_number );
        
    }
    $paid = 'Yes';
    if($result->payment==0)
        $paid = "No";

    echo '<form method="post" action="';
    echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
    echo '">';
    
    echo "<tr>";
        echo '<td>'.$result->ref_no.'</td>';
        if(!empty($user_info->display_name))
            echo "<td>".$user_info->display_name." (".$result->ID.")".'</td>';
        else if(!empty($user_info->user_nicename))
            echo  "<td>".$user_info->user_nicename." (".$result->ID.")".'</td>';
        else if(!empty($name))
            echo  "<td>".$name." (".$result->ID.")".'</td>';
        else
            echo  "<td>N/A</td>";
            
        
        $paid_update = $paid.'<input type="hidden" value="'.$paid.'" name="payment_done"/>';
        $paid_update .= '<input type="submit" value="Revert" name="payment"/>';
        if($paid=='No')
        {
            $paid_update .= '<input type="submit" value="Mark paid and Notify user" name="payment_notify" />';                   
        }
        $paid_update .= '<input type="hidden" value="'.$result->ref_no.'" name="payment_ref_no"/>';
        $paid_update .= "</form>";
        echo '<input type="hidden" value="'.$result->ID.'" name="uid"/>';
        echo '<td>'.$paid_update.'</td>';            
        echo '<td>'.$result->time.'</td>';
        echo '<td>'.$contact_number.'</td>';
        echo '<td>'.$result->user_email.'</td>';
        echo '<td><a href="'.site_url('view-results').'/?ref_no='.$result->ref_no.'">View</a></td>';
        echo '<td><a href="'.site_url('order').'/?ref_no='.$result->ref_no.'&test_id=2">View</a></td>';
    echo "</tr>";
}

?>
</table>