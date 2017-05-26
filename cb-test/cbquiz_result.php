<?php
function cbquiz_results($atts)
{
    global $wpdb;
    $ref_no=$atts;
    if(isset($_SESSION['ref_no']))
        $ref_no = $_SESSION['ref_no'];
    if(isset($_GET['ref_no']))
        $ref_no = $_GET['ref_no'];
            
    //define array of linked scripts
    $result_scripts = Array (
    '15' => 'iq_test',
    '17' => 'cb_ci',
    );
    define('PDF',1);
    define('PAGE',2);
    
    $page_type = Array (
        'iq_test' => PAGE,
        'cb_ci' => PDF );
    
    //if user is not logged in, redirect him to login page    
    if(is_user_logged_in())
    {
    
        //get test id 
        $table_name = $wpdb->prefix."cb_result_master";
        $test_id = $wpdb->get_results("SELECT * FROM $table_name WHERE ref_no = $ref_no");
        $test_id = $test_id[0]->test_id;
        //on the basis of test id call function
        try {
            if(isset($result_scripts[$test_id])) {    
                require_once('result_scripts/'.$result_scripts[$test_id].".php");
                $functionName = $result_scripts[$test_id];
                return call_user_func($functionName, $ref_no);
            } else {
                return "Ooops, something went wrong.";
            }
        }
        catch(exception $e) 
        {
            return "Ooops, something went wrong.";
        } 
    }
    else
    {
        echo "Please login to view results<br/>";
                 $args = array(
        'echo' => false,
        'redirect' => site_url( $_SERVER['REQUEST_URI'] ), 
        'form_id' => 'loginform',
        'label_username' => __( 'Username' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in' => __( 'Log In' ),
        'id_username' => 'user_login',
        'id_password' => 'user_pass',
        'id_remember' => 'rememberme',
        'id_submit' => 'wp-submit',
        'remember' => true,
        'value_username' => NULL,
        'value_remember' => false );
        
        echo wp_login_form($args);
    }
}
    
add_shortcode('cbquiz_results', 'cbquiz_results');
?>