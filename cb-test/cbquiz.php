<?php
/*
Plugin Name: Career Breeder Quiz
Plugin URI: http://www.careerbreeder.com
Description: Plugin for Psychometric Tests
Author: Career Breeder Team
Version: 1.0
Author URI: http://www.careerbreeder.com
*/

define('APTITUDE_TEST', '17');
define('IQ_TEST', '15');

$admin_email = get_option( 'admin_email' );
define('CONTACT_NUMBER', "+91-941-000-7819");
define('SUPPORT_EMAIL_ID', '$admin_email');

define('CBQUIZ_VERSION', 1.1);
define('CBQUIZ_PLUGIN_URL', plugin_dir_url(__file__));

include ('cbquiz_result.php');
include ('cbquiz_bill.php');
include ('cbquiz_after_login.php');
include ('user_test_history.php');
include ('connect.php');


function cbquiz_admin()
{
    global $wpdb;
    include ('cbquiz_admin.php');
        if (isset($_GET['question_id'])) {
            include ('create_question.php');
        } else
            if (isset($_GET['section_id'])) {
                include ('create_section.php');
            } 
             else if (isset($_GET['test_id'])) {
                
                    if(isset($_GET['publish-test'])) {
                            include ('cbquiz_cache.php');
                            cbquiz_cache_test($_GET['test_id']);
                        }
                    include ('cbquiz_view_test.php');
                    
                    } else
                        if (isset($_GET['test_test'])) {
                            include ('create_test.php');
                        } else {
                            cbquiz_tests();
                        }
}

function cbquiz_results_page()
{
	include ('cbquiz_view_results_front_page.php');
}
function cbquiz_admin_actions()
{
    add_menu_page("CB Quiz", "CB Quiz", 1, "CB-Quiz", "cbquiz_admin", site_url().'/wp-content/themes/cb/images/favicon.ico');
    add_submenu_page("CB-Quiz", "Results", "Results","manage_options", "cbquiz-view-results","cbquiz_results_page");
}

function cbquiz_install()
{
    global $wpdb;
    global $cbquiz_db_version;

    require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

    $table_name = $wpdb->prefix . "cb_connect";
	$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
	  		`connect_id` int(5) NOT NULL,
			 `uid` int(5) NOT NULL,
			 `first_name` varchar(50) NOT NULL,
			 `last_name` varchar(50) NOT NULL,
			 `contact_no` varchar(15) NOT NULL,
			 `email` varchar(50) NOT NULL,
			 `discussion` longtext NOT NULL,
			 `status` varchar(1) NOT NULL COMMENT '1- New, 2- In Progress, 3- Taken, 4- Not taken',
			 `product_id` varchar(5) NOT NULL,
			 `product_ref_no` varchar(100) NOT NULL,
			 `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
			) ENGINE=MyISAM DEFAULT CHARSET=latin1";
    dbDelta($sql);

    $table_name = $wpdb->prefix . "cb_connect_product";
    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
          `product_id` int(5) NOT NULL,
          `product_name` varchar(100) NOT NULL,
          `amount` varchar(5) NOT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1";
    dbDelta($sql);

    $table_name = $wpdb->prefix . "cb_result_master";
    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
          `uid` int(5) NOT NULL,
          `test_id` int(5) NOT NULL,
          `ref_no` varchar(100) NOT NULL,
          `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `result` varchar(5000) NOT NULL,
          `result_id` int(10) NOT NULL,
          `payment` int(1) NOT NULL DEFAULT '0' COMMENT '0- Not paid yet, 1-Paid',
          `email_status` int(1) NOT NULL,
          `email_update_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1";
    dbDelta($sql);

    $table_name = $wpdb->prefix . "cb_questions";
    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
        `question_id` int(10) NOT NULL AUTO_INCREMENT,
        `section_id` int(5) NOT NULL,
        `question` varchar(1000) NOT NULL,
        `type` int(1) NOT NULL COMMENT '0- One choice, 1- Many options, 2- Subjective',
        `question_no` int(2) NOT NULL,
        `duration` int(3) NOT NULL,
        `test_id` int(5) NOT NULL,
        PRIMARY KEY (`question_id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1";
    dbDelta($sql);

    $table_name = $wpdb->prefix . "cb_test";
    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
          `test_id` int(5) NOT NULL AUTO_INCREMENT,
          `name` varchar(100) NOT NULL,
          `description` text NOT NULL,
          `cost` float NOT NULL,
          `duration` int(10) NOT NULL,
          `rules` longtext NOT NULL,
          `about_test` longtext NOT NULL,
          `test_advantage` longtext NOT NULL,
          `test_features` longtext NOT NULL,
          `test_process` longtext NOT NULL,
          `sample_report` varchar(200) NOT NULL,
          `active` int(1) NOT NULL,
          `cache` mediumtext NOT NULL,
          PRIMARY KEY (`test_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
    dbDelta($sql);

    $table_name = $wpdb->prefix . "cb_option";
    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
          `option_id` int(5) NOT NULL,
          `question_id` int(5) NOT NULL,
          `name` varchar(500) NOT NULL,
          `question_no` int(2) NOT NULL,
          `extra` varchar(500) NOT NULL,
          PRIMARY KEY (`option_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
    dbDelta($sql);

    $table_name = $wpdb->prefix . "cb_section";
    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
          `section_id` int(5) NOT NULL AUTO_INCREMENT,
          `name` varchar(500) NOT NULL,
          `description` varchar(1000) NOT NULL,
          `duration` int(5) NOT NULL,
          `test_id` int(5) NOT NULL,
          `section_no` int(3) NOT NULL,
          PRIMARY KEY (`section_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
    dbDelta($sql);

    $table_name = $wpdb->prefix . "cb_ebs_billing";
    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
		  `ResponseCode` varchar(50) NOT NULL,
		  `ResponseMessage` varchar(50) NOT NULL,
		  `DateCreated` varchar(50) NOT NULL,
		  `PaymentID` varchar(50) NOT NULL,
		  `MerchantRefNo` varchar(50) NOT NULL,
		  `Amount` float NOT NULL,
		  `Mode` varchar(10) NOT NULL,
		  `BillingName` varchar(100) NOT NULL,
		  `BillingAddress` varchar(500) NOT NULL,
		  `BillingCity` varchar(200) NOT NULL,
		  `BillingState` varchar(200) NOT NULL,
		  `BillingPostalCode` int(10) NOT NULL,
		  `BilllingCountry` varchar(50) NOT NULL,
		  `BillingPhone` varchar(15) NOT NULL,
		  `BillingEmail` varchar(200) NOT NULL,
		  `DeliveryName` varchar(500) NOT NULL,
		  `DeliveryAddress` varchar(500) NOT NULL,
		  `DeliveryCity` varchar(200) NOT NULL,
		  `DeliveryState` varchar(200) NOT NULL,
		  `DeliveryPostalCode` int(10) NOT NULL,
		  `DeliveryCountry` varchar(50) NOT NULL,
		  `DeliveryPhone` varchar(15) NOT NULL,
		  `Description` varchar(500) NOT NULL,
		  `IsFlagged` varchar(200) NOT NULL,
		  `TransactionID` varchar(100) NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=latin1";
	dbDelta( $sql );

	$table_name = $wpdb->prefix . "cb_ci_degree";
    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
          `degree_id` int(2) NOT NULL AUTO_INCREMENT,
          `degree` varchar(100) NOT NULL, 
          PRIMARY KEY (`degree_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8";
    dbDelta($sql);

    $sql = "INSERT INTO `$table_name` (`degree_id`, `degree`) VALUES
            (1, '8th Class or below'),
            (2, '9-10th Class'),
            (3, '11-12th Class'),
            (4, 'Student - Graduation'),
            (5, 'Student - Postgraduation'),
            (6, 'Student - Doctorate'),
            (7, 'Fresher - 0-2 Years Experience'),
            (8, 'Young Professionals - 2-5 Years Experience'),
            (9, 'Experienced Professional - 5-8 Years Experience'),
            (10, 'Senior Professionals - 8+ Years Experience')";
    $wpdb->query( $sql );

    add_option("cbquiz_db_version", $cbquiz_db_version);

}


function cbquiz_install_data()
{
    global $wpdb;
    $welcome_name = "Mr. WordPress";
    $welcome_text = "Congratulations, you just completed the installation!";
    $rows_affected = $wpdb->insert($table_name, array('time' => current_time('mysql'),
        'name' => $welcome_name, 'text' => $welcome_text));
}


register_activation_hook(__file__, 'cbquiz_install');
add_action('admin_menu', 'cbquiz_admin_actions');



function my_plugin_options()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    echo '<div class="wrap">';
    echo '<p>Here is where the form would go if I actually had options.</p>';
    echo '</div>';
}


/*//to start the session
add_action('init', 'eshopsession', 1);
if (!function_exists('eshopsession')) {
    function eshopsession()
    {
        if (!session_id()) {
            ;//session_start();
        }
    }
}*/
//to remove top bar
add_filter('show_admin_bar', '__return_false');

//Update Registration Page

//Function depreceated
//require_once (ABSPATH . WPINC . '/registration.php');
global $wpdb, $user_ID;

function ref_no_details($ref_no, $coupon = null)
{
    global $wpdb;
    $currency = "INR";

    $details = new CB_Connect;
    $details = $details->get($ref_no);
    
    $url = site_url()."/coupon/searchCouponResults.php?coupon=$coupon&product=All";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $discount = curl_exec($ch);
    curl_close($ch);    
    if ($discount == 2) {
        $details['discount'] = 0;
        $details['status'] = 'Sorry, selected coupon doesn\'t exist.';
    } else
        if ($discount == 3) {
            $details['discount'] = 0;
            $details['status'] = 'Sorry, selected coupon is expired.';
        } else {
            $discount = json_decode(trim($discount, true),true);
            $details['discount'] = $details['item_cost'] * $discount['Coupon_Discount'] / 100;
            $details['status'] = "Coupon successfully applied";
        }

    $details['total'] = $details['item_cost'] - $details['discount'];
    $details['currency'] = $currency;

    return $details;
}


//For Connect
function cb_connect_order()
{
//    echo get_the_title();

    $connect = new CB_Connect;
    if(isset($_GET['ref_no']))
    {
        if($_GET['ref_no']<100 && is_user_logged_in() && $_GET['ref_no']>0)
        {
            $details['product_id'] = $_GET['ref_no'];
            $details['uid'] = get_current_user_id();
            $ref_no = $connect->insert($details);
            wp_redirect(site_url("order")."?test_id=2&ref_no=$ref_no");
            exit();
        }
    }
}

add_action('init', 'cb_connect_order');


/**
 * Update user information in Admin page
 */


function test_modify_user_table( $column ) {
    $column['contact_number'] = 'Contact Number';
 
    return $column;
}
 
add_filter( 'manage_users_columns', 'test_modify_user_table' );
 
function test_modify_user_table_row( $val, $column_name, $user_id ) {
    $user = get_userdata( $user_id );
 
    switch ($column_name) {
        case 'contact_number' :
            return $user->contact_number;
            break;
 
        default:
    }
    return $return;
}
 
add_filter( 'manage_users_custom_column', 'test_modify_user_table_row', 10, 3 );


function cb_custom_user_create() {
    global $pagenow;

    # do this only in page user-new.php
    if(!($pagenow == 'user-new.php'))
        return;
        
    custom_edit_user_profile(null);
}


function custom_edit_user_profile($user_info){

    # do this only if you can
    if(!current_user_can('manage_options'))
        return false;

?>
<table id="table_contact_number" style="display:none;">
    <tr>
        <th><label for="contact_number">Contact Number</label></th>
        <td><input type="text" name="contact_number" id="contact_number" value="<?php echo esc_attr( get_the_author_meta( 'contact_number', $user_info->ID ) ); ?>"/></td>
    </tr>
    <tr>
        <th><label for="degree">Degree</label></th>
        <td><select class="input" name="degree" id="degree">
<?php
            global $wpdb;
            $degrees = $wpdb->get_results("SELECT * FROM wp_cb_ci_degree");
            foreach($degrees as $degree)
            {
                if($degree->degree_id == $user_info->degree_id)
                    echo "<option value=\"".$degree->degree_id."\" selected>".$degree->degree."</option>";                                        
                else
                    echo "<option value=\"".$degree->degree_id."\">".$degree->degree."</option>";            
            }
?>      </td>

    </tr>
</table>
<script>
jQuery(function($){
    //Move my HTML code below user's role
    jQuery('#table_contact_number tr').insertAfter($('#last_name').parentsUntil('tr').parent());
});
</script>
<?php
}
add_action('edit_user_profile', 'custom_edit_user_profile');
add_action('admin_footer_text', 'cb_custom_user_create');


function save_custom_user_profile_fields($uid){
    # again do this only if you can
    if(!current_user_can('manage_options'))
        return false;
        
    global $wpdb;
    
    
    $wpdb->update('wp_users', Array('degree_id'=>$_POST['degree']), Array('ID' => $uid));
    $wpdb->update('wp_users', Array('contact_number'=>$_POST['contact_number']), Array('ID' => $uid));
}
add_action('user_register', 'save_custom_user_profile_fields');
add_action('edit_user_profile_update', 'save_custom_user_profile_fields');
?>