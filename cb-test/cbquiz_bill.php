<?php

function cb_billing() {

    //EBS
    if(isset($_GET['DR']))
    {
        $secret_key = "81c45e7446661539ebf180afae2bdaaa";
        include('Rc43.php');
 	    $DR = preg_replace("/\s/","+",$_GET['DR']);

        $rc4 = new Crypt_RC4($secret_key);
        $QueryString = base64_decode($DR);
        $rc4->decrypt($QueryString);
        $QueryString = split('&',$QueryString);
        
        $response = array();
        foreach($QueryString as $param){
            $param = split('=',$param);
            $response[$param[0]] = urldecode($param[1]);
    	 }
        //Mode is EBS
        $error = ebs_billing($response);
    } else {
        $error= "Sorry some unknown error occured.";
    }
    
//    print_r($error);
    //Put other payment authentication over here
    
    if($error==1)
    {
        //Mail the user
        //Show the bill
        //Give link to view results
    } else {
        echo $error;
    }
}


function ebs_billing($response) {
    global $wpdb;
    
    $billing['ResponseCode'] = $response['ResponseCode'];
    $billing['ResponseMessage'] = $response['ResponseMessage'];
    $billing['DateCreated'] = $response['DateCreated'];
    $billing['PaymentID'] = $response['PaymentID'];
    $billing['MerchantRefNo'] = $response['MerchantRefNo'];
    $billing['Amount'] = $response['Amount'];
    $billing['Mode'] = $response['Mode'];
    $billing['BillingName'] = $response['BillingName'];
    $billing['BillingAddress'] = $response['BillingAddress'];
    $billing['BillingCity'] = $response['BillingCity'];
    $billing['BillingState'] = $response['BillingState'];
    $billing['BillingPostalCode'] = $response['BillingPostalCode'];
    $billing['BillingCountry'] = $response['BillingCountry'];
    $billing['BillingPhone'] = $response['BillingPhone'];
    $billing['BillingEmail'] = $response['BillingEmail'];
    $billing['DeliveryName'] = $response['DeliveryName'];
    $billing['DeliveryAddress'] = $response['DeliveryAddress'];
    $billing['DeliveryCity'] = $response['DeliveryCity'];
    $billing['DeliveryState'] = $response['DeliveryState'];
    $billing['DeliveryPostalCode'] = $response['DeliveryPostalCode'];
    $billing['DeliveryCountry'] = $response['DeliveryCountry'];
    $billing['DeliveryPhone'] = $response['DeliveryPhone'];
    $billing['Description'] = $response['Description'];
    $billing['IsFlagged'] = $response['IsFlagged'];
    $billing['TransactionID'] = $response['TransactionID'];
    
    $billing['status'] = "Successful";
    
    $table_name = $wpdb->prefix."cb_ebs_billing";
    $wpdb->insert($table_name,$billing);
    
    $error = Array();
    
    //Ebs Validation Logic
    if($billing['ResponseCode'] == '0')
    {
        $billing['ref_no'] = $billing['MerchantRefNo'];
        //Check if Reference Number and Amount matches
        $ref_no = $billing['ref_no'];
        
        //fetch amount
        $table_name1 = $wpdb->prefix."cb_test";
        $table_name2 = $wpdb->prefix."cb_result_master";
        $amount = $wpdb->get_results("SELECT cost FROM `$table_name1`,`$table_name2` WHERE $table_name1.test_id = $table_name2.test_id AND $table_name2.ref_no = $ref_no ");
        $amount = $amount[0]->cost;
        
        if($billing['IsFlagged']== 'Yes')
        {
            $error[] = "Thank you for your payment. Please provide us 24 hours. We will get back to you shortly.";
        } else {
            //Everything is fine
            cb_affiliate();
            cb_paid($ref_no);
            return cb_billing_format($billing);        
            //Mail the user;
        }
        
    } else {
        $error[] = 'Response Code Error';
    }
    return $error;
}

//echo cb_billing_format(1);
function cb_billing_format($details)
{
    /*
    $a = '<link href="css/billstyle.css" rel="stylesheet" type="text/css" /> 
<div class="bill_wrapper">
 <div class="bill_wrapper_body">	
	<div class="bill_header">
    <div class="spacer"></div>
    <div class="bill_head_containor">
    	<div class="bill_logo">
        	<img src="images/bill_logo.jpg" width="297" height="100" />        
        </div>
    	<div class="invoice_containor">
        	<div class="blank_left"></div>
       	  <div class="invoice">
       	  <img src="images/Untitled-7.jpg" />
                <p id="invoice_txt"><strong><span style="color:#C09">Invoice Number :</span></strong> <span style="color:#000"><strong>'.$details['TransactionID'].'</strong></span></p>
          		<p id="Service_txt"><strong><span style="color:#C09">Service Tax Number :</span></strong> <span style="color:#000">#AABCO4987GSE001<strong></strong></span></p>
          </div>
        
        </div>
    </div>
    	
    </div>
    <div class="clearfix"></div>
    <div class="main">
    	<div class="invoice_details">
        	<div class="left_details" id="left_details">
					<p id="name_txt"><strong><span style="color:#000">'.$details['BillingName'].'</span></strong> </p>
            		<p id="email_txt"><strong><span style="color:#C09">Email:</span></strong> <span style="color:#000"><strong>'.$billing['BillingEmail'].'</strong></span></p>
					<p id="phone_txt"><strong><span style="color:#C09; padding-left:30px">Contact Number:</span></strong> <span style="color:#000"><strong>'.$billing['BillingPhone'].'</strong></span></p>            
            </div>
            <div class="middle_details_txt">
            		
            
            </div>
            
        <div class="right_details">
            	<p id="name_txt"><strong><span style="color:#C09">Career Breeder Reference Number :</span></strong> <span><strong>'.$details['MerchantRefNo'].'</strong></span>   </p>
				<p id="name_txt"><strong><span style="color:#C09">Purchase Date:</span></strong><span><strong>'.date("d/m/y").'</strong></span>   </p>
				<p id="name_txt"><strong><span style="color:#C09">Payment Status:</span></strong><span><strong>'.$details['status'].'</strong></span>   </p>           
          </div>
        </div>
        
      <div id="purchase_details_bg">
        	<div class="item_box">
            <p style="color:#3a3839; font-size:15px; padding-top:10px; font-weight:600; font-family:Verdana, Geneva, sans-serif;"><span style="padding-left:30px;">Purchase Details</span><span style="padding-left:270px;">Items Cost</span><!--span style="padding-left:70px;">Service Tax</span--><span style="padding-left:100px;">Amount</span></p>
            </div>
        	
            <div class="divide_line"></div>
            <div class="puchase_details_inner_box1">
            		<p style="font-size:14px; padding-top:40px; color:#313461; width:330px; padding-left:10px; height:auto;">Career Indicator</p>
            </div>
    <!--div class="puchase_details_inner_box2">
            	<p style="padding-top:40px; padding-left:30px; font:Verdana, Geneva, sans-serif; font-size:17px"><span>Rs. 499*49</span></p>
            
            </div>
   <div class="puchase_details_inner_box2">
            	<p style="padding-top:40px; padding-left:50px; font:Verdana, Geneva, sans-serif; font-size:17px"></span></p>
            
        </div-->
  <div class="puchase_details_inner_box2">
            	<p style="padding-top:40px; padding-left:70px; font:Verdana, Geneva, sans-serif; font-size:17px"><span>Rs.<strong>'.$details['amount'].'</strong></span></p>
            
  </div>                   
        <div style="clear:both"></div>
        <div class="divide_line"></div>
        
        <div class="puchase_details_inner_box3">
        	<div class="total_left">
            
            </div>
            <!--div class="total_right">
            	<div class="right_top">
            	  <p style=" padding-top:10px; font:Verdana, Geneva, sans-serif; font-size:17px; font-weight:600"><strong><span style="color:#C09;">Total</span></strong> <span style="padding-left:306px;"><span>Rs.</span> 24,451</span>   </p>
                  
                </div>
                <div class="right_bottom">
                 <p style=" padding-top:0px; font:Verdana, Geneva, sans-serif; font-size:17px; font-weight:600"><strong><span style="color:#C09;">Discount</span></strong> <span style="padding-left:280px;"><span> Rs.</span> 8</span>   </p>
                
                </div>
                
            </div-->
            	
            </div>
            <div style="clear:both"></div>
            <div class="divide_line"></div>	
        	<p style=" padding-top:20px; padding-left:400px; font:Verdana, Geneva, sans-serif; font-size:17px; font-weight:600"><strong><span style="color:#C09;">Grand Total</span></strong> <span style="padding-left:280px;"><span> Rs. </span>24,451</span>   </p>
        
        </div>
    
    </div>
    
    <div class="bill_footer">
    	
    </div>
  </div>  
</div>
';

    
    */
    
    
    
    $a = '
    	<div class="tst_hdrmain">
        	<div class="tst_hdrmainlft">     
                	
                    
                <div class="clear"></div>
               
                <!--social ntwork -->
                
<div id="social_ntwork">
                    	<div class="classi_txt2">An email has been sent to you regarding the confirmation of payment.</div>
              <div class="clear"></div>
              
              <div id="shipepd_noti">
              	<a>Your Test result package has been shipped through below details</a>
                	<div id="ship_notidtl">
                    	<ul>
                        	<li>
                            	<span class="ship_form"><a>Billing Name</a></span>
                            	<span class="ship_formrgt"><a>'.$details['BillingName'].'</a></span>
                            </li>
                            <li>
                            	<span class="ship_form"><a>Reference Number</a></span>
                            	<span class="ship_formrgt"><a>'.$details['MerchantRefNo'].'</a></span>
                            </li>
                            <li><span class="ship_form"><a>Transaction ID</a></span>
                            	<span class="ship_formrgt"><a>'.$details['TransactionID'].'</a></span>
                            </li>
                            <li><span class="ship_form"><a>Amount</a></span>
                            	<span class="ship_formrgt"><a>'.$details['amount'].'</a></span>
                            </li>
                            <li><span class="ship_form"><a>Amount</a></span>
                            	<span class="ship_formrgt"><a>'.$details['amount'].'</a></span>
                            </li>
                        </ul>
                    </div>
              </div>
                      </div>
    
            </div>
            
            <div class="tst_hdrmainrgt">
            	
                <div id="uin">
                	<span class="fcm_txt1" id="uin_header1"><a href="'.site_url("view-results").'>Your Bill is also Ready</a></span>
                    
                </div>
                
                <div class="clear"></div>
                 
                 <div id="view_bill"><a href="#" title="view bill"></a></div>
                                
            </div>
        
        </div><!--tst_hdrmain ends -->';
        $a .= '<a href="http://careerbreeder.com/view-results/?ref_no='.$details['MerchantRefNo'].'"/>Click here to view your results</a>';
        return $a;
}

function cb_affiliate() {
    
    $data = array(
    	'customer-id' => array( 'title' => 'Customer Id', 'domain' => 'my_order_plugin', 'value' => $customer_id ),
    	'order-id'    => array( 'title' => 'Order Id', 'domain' => 'my_order_plugin', 'value' => $order_id )
    );
    if ( $affiliate_id = affiliates_suggest_referral( $post_id, "Referral for order number $order_id", $data ) ) {
    	$affiliate = affiliates_get_affiliate( $affiliate_id );
    	$affiliate_email = $affiliate['email'];
    	if ( !empty( $affiliate_email ) ) {
    		$message = "Dear Affiliate, an order has been made. You will be credited as soon as payment is received.";
            if(!function_exists('wp_get_current_user')) {
                include(ABSPATH . "wp-includes/pluggable.php"); 
            }
    	//	my_order_plugin_send_an_email( $affiliate_email, $message );
    	}
    }
}

function cb_paid($ref_no) {
    global $wpdb;
    $wpdb->update( $wpdb->prefix."cb_result_master", Array('payment' => '1'), Array('ref_no' => $ref_no));
}
add_shortcode('cb_billing','cb_billing');
?>