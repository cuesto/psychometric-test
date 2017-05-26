<a href="<?php echo attribute_escape( add_query_arg( 'test_test', 'new' ) ); ?>">Create Test</a>
<?php function cbquiz_tests() {
    global $wpdb;
    $table_name = $wpdb->prefix."cb_test";
    
    $tests = $wpdb->get_results("SELECT * FROM $table_name");
    ?>
    <table width="100%">
    <tr>
        <th>S.no</th>
        <th>Name</th>
        <th>Description</th>
        <th>Cost</th>
        <th>Duration</th>
        <th>View</th>
        <th>Edit</th>
        </tr>
    <?php
    $sno=0;
        foreach($tests as $test)
        { 
            echo "<tr>";
                echo "<td>".++$sno."</td>";
                echo "<td>".$test->name."</td>";
                echo "<td>".$test->description."</td>";
                echo "<td>".$test->cost."</td>";
                echo "<td>".$test->duration."</td>";
                echo "<td><a href=\"".attribute_escape( add_query_arg( 'test_id', $test->test_id ) )."\">View</a></td>";
                echo "<td><a href=\"".attribute_escape( add_query_arg( 'test_test', $test->test_id ) )."\">Edit</a></td>";
            echo "</tr>";
        }
     echo "</table>";
    }
    
?>