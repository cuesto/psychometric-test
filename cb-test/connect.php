<?php

class CB_Connect
{
    public static $uid;
    
    function CB_Connect() {
        $this->uid = get_current_user_id();
    }
    
    public function insert($details)
    {
        global $wpdb;
        $uid= $details['uid'];
        $discussion=$details['discussion'];
        
        if(isset($details['status']))
            $status = $details['status'];
        else
            $status = 1;
        
        $product_id=$details['product_id'];
        $product_ref_no=$details['product_ref_no'];
        
        $result = $wpdb->get_results("SELECT * FROM wp_cb_connect WHERE
                ( uid=$uid OR email='$email' )
                AND product_id=$product_id
                AND status != 3
                ");
        $result=$result[0];
        if($result->connect_id>0)
        {
            return $result->connect_id;
        }

       $result = $wpdb->insert(
                'wp_cb_connect',
                Array(
                    'connect_id' => '',                                
                    'uid' => $uid,  
                    'discussion' => $discussion,
                    'status' => $status, 
                    'product_id' => $product_id,
                    'product_ref_no' => $product_ref_no)
                );
        $connect_id = $wpdb->insert_id;
        return $connect_id;
    }
    
    public function get($connect_id)
    {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM wp_cb_connect WHERE connect_id=$connect_id");
        $results=$results[0];
        if($results->connect_id>0)
        {
            $product_id = $results->product_id;
            $results1 = $wpdb->get_results("SELECT * FROM wp_cb_connect_product WHERE product_id=$product_id");
            $results1=$results1[0];
            $details['item_name'] = $results1->product_name;
            $details['item_cost'] = $results1->amount;
        } else {
            $table_name1 = $wpdb->prefix . "cb_test";
            $table_name2 = $wpdb->prefix . "cb_result_master";
            $result = $wpdb->get_results("SELECT cost,name, FROM `$table_name1`,`$table_name2` WHERE $table_name1.test_id = $table_name2.test_id AND $table_name2.ref_no = $connect_id ");
            
            $user_info = get_userdata(get_current_user_id());
            if($user_info->product==1)
            {
                $details['item_cost'] = 499;
                $details['item_name'] = 'Know your career';        
            } 
            else if($user_info->product==2)
            {
                $details['item_cost'] = 999;
                $details['item_name'] = 'Plan with us';
            } 
            else if($user_info->product==3)
            {
                $details['item_cost'] = 4999;
                $details['item_name'] = 'Complete Counseling for a year';        
            }
        }
        return $details;                
    }
    
    public function get_URL($product_id, $user_id=null)    
    {
        if(is_user_logged_in())
            return site_url("order")."?test_id=2&ref_no=$product_id&ref_no1=$user_id";
        else
            return site_url("show-me-the-results")."?test_id=3&ref_no=$product_id";                            
    }
    
    
    public function get_first_name($uid = null) //Actually name of user
    {
        $first_name = '';
        if($uid ==null)
            $uid = $this->uid;
        $user_info = get_userdata($uid);
        //user details
        if (isset($user_info->display_name))
            $first_name .= $user_info->display_name;
        else
            if (isset($user_info->user_nicename))
                $first_name .= $user_info->user_nicename;
            else
                if (isset($user_info->nickname))
                    $first_name .= $user_info->nickname;
                else
                    if (isset($user_info->name))
                        $first_name .= $user_info->name;
                    else
                        if (isset($user_info->user_email))
                            $first_name .= $user_info->user_email;
        $first_name = (ucwords($first_name));
        
        return $first_name;
    }
    
    public function get_email_footer()
    {
        $footer = "<br/><br/>Regards,<br/> <a href=\"http://careerbreeder.com\">Career Breeder</a> Team.<br/>Feel free to call our helpline ".CONTACT_NUMBER.".<br/><br/> 
        <a href=\"https://www.facebook.com/careerbreeder\">Facebook</a> | <a href=\"https://twitter.com/career_breeder\"> Twitter </a> | <a href=\"http://www.youtube.com/watch?v=PPN1XV_IZk0\">
YouTube</a>";
        return $footer;
    }
    
}