<?php
function user_test_history()
{
    $test_history = '<table width="80%" border="1px">
        <tr>
            <th>Name</th>
            <th>Date of assessment</th>
            <th>Action</th>
        </tr>';
        
    global $wpdb;
    global $current_user;
    get_currentuserinfo();
    $uid = $current_user->ID;

    $table_name1 = $wpdb->prefix . "cb_test";
    $table_name2 = $wpdb->prefix . "cb_result_master";
    $testDetails = $wpdb->get_results("SELECT * FROM `$table_name1` WHERE active=1 ");
    
    $test_id = "";
    
    $tests;
    
    foreach ($testDetails as $test) {
        $test_id .= $test->test_id.",";
        $tests[$test->test_id] = $test;
    }
    
    $test_id = substr($test_id,0,strlen($test_id)-1);

    $testGiven = $wpdb->get_results("SELECT UNIX_TIMESTAMP(time) as timest, ref_no, payment, time, test_id FROM `$table_name2` WHERE uid = $uid AND test_id IN (" .
        $test_id .") ORDER BY timest  DESC");
        
    foreach ($testGiven as $usertest) {
        $test_history .= "<tr>";
        $test_history .= "<td>" . $tests[$usertest->test_id]->name . "</td>";
        if (isset($usertest->payment)) {
            $test_history .= '<td>'.date('d-m-Y',strtotime($usertest->time)).'</td>';
            
            if ($usertest->payment == 1 || $tests[$usertest->test_id]->cost == 0)
                $test_history .= '<td><a href="' . site_url("view-results") . "?ref_no=" . $usertest->
                    ref_no . '">View Results</a></td>';
            else
                $test_history .= '<td><a href="' . site_url("order") . "?ref_no=" . $usertest->
                    ref_no . '&test_id=2">Pay and see full report</a><br />
                    <a href="' . site_url("view-results") . "?ref_no=" . $usertest->
                    ref_no . '&test_id=2">View sample report</a></td>';
                    

        } else {
            $test_history .= '<td>-</td>';
            $test_history .= '<td><a href="' . site_url("aptitude-test-questions") .
                "?test_id=" . $tests[$usertest->test_id]->test . '">Give test</a></td>';
        }
        $test_history .= "</tr>";
    }


    $test_history .= '</table>';
    
    return $test_history;
}

add_shortcode("user_test_history", "user_test_history");
?>