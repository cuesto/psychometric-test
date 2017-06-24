<?php
    wp_enqueue_style("style1css",plugins_url( 'css/style1.css', __FILE__ ));
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
        //echo "<div class='payment-details'>";
        if(isset($coupon))
        {
            $ref_no_details['status'];
        }    
        //print_r($ref_no_details);
        /*echo '<table class="order-table" cellpadding="5"  cellspacing="10">
            <tr>
                <th>Name</th>
                <th>Cost</th>
            </tr>
            <tr>
                <td>'.*/$ref_no_details['item_name']; $ref_no;
                $ref_no_details['item_cost']; $ref_no_details['currency'];
            
        if(isset($coupon) && $ref_no_details['discount']>0)
        {
            $ref_no_details['discount']; $ref_no_details['currency'];
        }
            $ref_no_details['total']; $ref_no_details['currency'];


    echo "<div class='payment-form-details'>"; 
    $form->addJFormComponentArray(array(
        new JFormComponentMultipleChoice('payment_type', 'How would you like to pay?', array(
            array('value' => 'ib', 'label' =>'Internet Banking/ Debit Card/ Credit Card'),
            //array('value' => 'cod','label' => 'Cash on Delivery (COD)'), 
            array('value' => 'neft', 'label' => 'NEFT/RTGS'),
            //array('value' => 'cheque', 'label' => 'Cheque/Demand Draft'),
            /*array('value' => 'paypal', 'label' => '<span style="background: url(images/button-bg.png) repeat-x scroll left center;
border: 1px solid;
cursor: pointer;
-moz-box-shadow: 0 1px 3px rgba(0,0,0,0.15);
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
border-color: #DEDEDE #BBB #BFBFBF #DEDEDE;
margin: 0 0 1em;
padding: .65em .75em;">Paypal</span>'),*/
                ),
                array(
                    'multipleChoiceType' => 'radio',
                    'validationOptions' => array('required'),
        )),
        new JFormComponentHidden('ref_no', $ref_no),
        new JFormComponentHidden('coupon', $coupon),
/**
 * For EBS
 */
        new JFormComponentName('name', 'Name:', array(
            'validationOptions' => array('required'),
            'middleInitialHidden' => true,
            'initialValue' => $firstName,
            'dependencyOptions' => array(
                'dependentOn' => 'payment_type',
                'display' => 'hide',
                'jsFunction' => 'jQuery("#payment_type-choice1").is(":checked");'
            ),
        )),
        new JFormComponentAddress('billing', 'Billing Address:', array(
            'validationOptions' => array('required'),
            'dependencyOptions' => array(
                'dependentOn' => 'payment_type',
                'display' => 'hide',
                'jsFunction' => 'jQuery("#payment_type-choice1").is(":checked");'
            ),
        )),

        new JFormComponentSingleLineText('email', 'E-mail:', array(
            'width' => 'longest',
            'initialValue' => $user_info->email,
            'validationOptions' => array('required', 'email'),
            'dependencyOptions' => array(
                'dependentOn' => 'payment_type',
                'display' => 'hide',
                'jsFunction' => 'jQuery("#payment_type-choice1").is(":checked");'
            ),
        )),
        new JFormComponentSingleLineText('contact_number', 'Mobile Number:', array(
            'mask' => '9999999999',
            'initialValue' => $user_info->contact_number,
            'validationOptions' => array('required'),
            'dependencyOptions' => array(
                'dependentOn' => 'payment_type',
                'display' => 'hide',
                'jsFunction' => 'jQuery("#payment_type-choice1").is(":checked");'
            ),
        )),



/**
 * For COD
 */
 
        /*new JFormComponentName('name_cod', 'Name:', array(
            'validationOptions' => array('required'),
            'middleInitialHidden' => true,
            'initialValue' => $firstName,
            'dependencyOptions' => array(
                'dependentOn' => 'payment_type',
                'display' => 'hide',
                'jsFunction' => 'jQuery("#payment_type-choice2").is(":checked");'
            ),
        )),
         new JFormComponentAddress('billing_cod', 'Billing Address:', array(
            'validationOptions' => array('required'),
            'dependencyOptions' => array(
                'dependentOn' => 'payment_type',
                'display' => 'hide',
                'jsFunction' => 'jQuery("#payment_type-choice2").is(":checked");'
            ),
        )),

        new JFormComponentSingleLineText('email_cod', 'E-mail:', array(
            'width' => 'longest',
            'initialValue' => $user_info->email,
            'validationOptions' => array('required', 'email'),
            'dependencyOptions' => array(
                'dependentOn' => 'payment_type',
                'display' => 'hide',
                'jsFunction' => 'jQuery("#payment_type-choice2").is(":checked");'
            ),
        )),
        new JFormComponentSingleLineText('contact_number_cod', 'Mobile Number:', array(
            'mask' => '9999999999',
            'initialValue' => $user_info->contact_number,
            'validationOptions' => array('required'),
            'dependencyOptions' => array(
                'dependentOn' => 'payment_type',
                'display' => 'hide',
                'jsFunction' => 'jQuery("#payment_type-choice2").is(":checked");'
            ),
        )),
        new JFormComponentTextArea('comments_cod', 'Any instructions to collect payment (optional):', array(
            'dependencyOptions' => array(
                'dependentOn' => 'payment_type',
                'display' => 'hide',
                'jsFunction' => 'jQuery("#payment_type-choice2").is(":checked");'
            ),
        )),*/

    new JFormComponentMultipleChoice('terms', '', array(
        array('value' => 'agree', 'label' => 'I agree to the site <a href="/legal/terms-and-conditions/" target="_blank" data-bitly-type="bitly_hover_card">Terms and Conditions</a>?'),
            ),
            array(
                'validationOptions' => array('required'),
    )),
    new JFormComponentMultipleChoice('policies', '', array(
        array('value' => 'agree', 'label' => 'I agree to the site <a href="'.site_url('refund-policy').'" target="_blank" data-bitly-type="bitly_hover_card">Cancellation and Return Policy</a>?'),
            ),
            array(
                'validationOptions' => array('required'),
    )),

/*        new JFormComponentMultipleChoice('billing_shipping', '',
            array(
                array('value' => '1', 'label' => 'My Shipping Address is different than my billing address'),
            ),
            array(
                'dependencyOptions' => array(
                'dependentOn' => 'payment_type',
                'display' => 'hide',
                'jsFunction' => 'jQuery("#payment_type-choice1").is(":checked");'
            ),
)
        ),
        new JFormComponentAddress('shipping', 'Shipping Address:', array(
            'dependencyOptions' => array(
                'dependentOn' => array('billing_shipping','payment_type'),
                'display' => 'hide',
                'jsFunction' => 'jQuery("#billing_shipping-choice1").is(":checked") && jQuery("#payment_type-choice2").is(":checked");'
            ),
                'validationOptions' => array('required'),
        )),
        new JFormComponentSingleLineText('shipping_contact_number', 'Shipping Address Mobile Number:', array(
//            'mask' => '(999) 999-9999',
            'validationOptions' => array('required'),
            'dependencyOptions' => array(
                'dependentOn' => array('billing_shipping','payment_type'),
                'display' => 'hide',
                'jsFunction' => 'jQuery("#billing_shipping-choice1").is(":checked") && jQuery("#payment_type-choice2").is(":checked");'
            ),
        )),
 
        new JFormComponentSingleLineText('shipping_contact_number', 'Shipping Mobile Number:', array(

        new JFormComponentSingleLineText('address', 'Address:', array(
            'validationOptions' => array('required'),
            'dependencyOptions' => array(
                'dependentOn' => 'payment_type',
                'display' => 'hide',
                'jsFunction' => 'jQuery("#payment_type-choice2").is(":checked");'
            ),
        )),

            'dependencyOptions' => array(
                'dependentOn' => 'billing_shipping',
                'display' => 'hide',
                'jsFunction' => 'jQuery("#billing_shipping-choice1").is(":checked") && jQuery("#payment_type-choice2").is(":checked");'
            ),
            'mask' => '(999) 999-9999',
            'validationOptions' => array('required')
        )),
    
        new JFormComponentMultipleChoice('terms', '', array(
            array('value' => 'agree', 'label' => 'Do you agree to the site <a href="/legal/terms-and-conditions/" target="_blank" data-bitly-type="bitly_hover_card">Terms and Conditions</a>?'),
                ),
                array(
                    'validationOptions' => array('required'),
        )),
*/
));
    
echo "</div>";

// Set the function for a successful form submission
function onSubmit($formValues) {
    global $wpdb; 
    
    //fetch reference number
    $ref_no= $formValues->ref_no;
    $coupon = $formValues->coupon;
    $response = "";    
    //fetch amount
    $ref_no_details = ref_no_details($ref_no,$coupon);
    $amount = $ref_no_details['total'];
    
    if($formValues->payment_type=="ib")
    {    
        //return url
        $return_url = site_url("billing")."?DR={DR}";
        
        //account ID
        $account_id = '10106';
        $ebs_key = '81c45e7446661539ebf180afae2bdaaa';        
        $mode = "LIVE";
        
        
        $hash = $ebs_key."|".$account_id."|".$amount."|".$ref_no."|".$return_url."|".$mode;
        $secure_hash = md5($hash);
//        return array( 'successPageHtml' => $hash );

        $response .= '<form  method="post" action="https://secure.ebs.in/pg/ma/sale/pay" name="frmTransaction" id="frmTransaction">
            <input name="account_id" type="hidden" value="'.$account_id.'">
            <input name="return_url" type="hidden" size="60" value="'.$return_url.'" />
            <input name="mode" type="hidden" size="60" value="'.$mode.'" />
            <input name="reference_no" type="hidden" value="'.$ref_no.'" />
            <input name="amount" type="hidden" value="'.$amount.'"/>
            <input name="description" type="hidden" value="Career Breeder" /> 
            <input name="name" type="hidden" maxlength="255" value="'.$formValues->name->firstName." ".$formValues->name->lastName.'" />
            <input name="address" type="hidden" maxlength="255" value="'.$formValues->billing->addressLine1.'" />
            <input name="city" type="hidden" maxlength="255" value="'.$formValues->billing->city.'" />
            <input name="state" type="hidden" maxlength="255" value="'.$formValues->billing->state.'" />
            <input name="postal_code" type="hidden" maxlength="255" value="'.$formValues->billing->zip.'" />
            <input name="country" type="hidden" maxlength="255" value="'.$formValues->billing->country.'" />
            <input name="phone" type="hidden" maxlength="255" value="'.$formValues->contact_number.'" />
            <input name="email" type="hidden" size="60" value="'.$formValues->email.'" />
            <input name="secure_hash" type="hidden" size="60" value="'.$secure_hash.'" />
            <input name="submitted" value="Submit" type="submit" />
        </form>';
        $response.= '<script>document.forms["frmTransaction"].submit();</script>';
    }
    else if($formValues->payment_type=="neft" ||$formValues->payment_type=="cheque" )
    {
        if($formValues->payment_type=="neft")
            $response .= "<h2>Payment Instructions for NEFT/RTGS Transfer</h2>";
        else if($formValues->payment_type=="cheque")
            $response .= "<h2>Payment Instructions for Cheque/Demand Draft</h2>";
            
        $response .= "Name: Oprotech Technologies Private Limited<br/>";
        $response .= "Amount: Rs $amount<br/>";
        $response .= "Bank Name: HDFC Bank<br/>";
        $response .= "Account Number: 1894-256-0000-565<br/>";
        $response .= "IFSC Code: HDFC0001894<br/>";
        $response .= "MICR Code: 209240002<br/>";
        $response .= "Registered E-mail ID: contactus@oprotech.com<br/>";
        $response .= "Address: Career Breeder, Oprotech Technologies Private Limited,<br/>
        5/142 Awas Vikas Colony, Farrukhabad, U.P., India. Pin Code- 209625<br/><br/>";
        $response .= "In case of any query please contact 8055555248<br/>";
    } 
    else if($formValues->payment_type=="cod")
    {    
        $instructions = "Thank you for the standing instructions for Cash on Delivery. Our representative will visit your place for payment collection.<br /><br />";
        $instructions .= "First Name: ".$formValues->name_cod->firstName."<br />
                      Last Name: ".$formValues->name_cod->lastName."<br />
                      Reference Number: $ref_no <br />
                      Amount: $amount <br />
                      Address Line 1: ".$formValues->billing_cod->addressLine1."<br />
                      City: ".$formValues->billing_cod->city."<br />
                      State: ".$formValues->billing_cod->state."<br />
                      Postal_code: ".$formValues->billing_cod->zip."<br />
                      Country: ".$formValues->billing_cod->country."<br />
                      Phone: ".$formValues->contact_number_cod."<br />
                      Email: ".$formValues->email_cod."<br />
                      Standing Insructions: ".$formValues->comments_cod."<br /><br /><br />";
                      
        $instructions .= $formValues->name_cod->lastName.", you will soon receive a call from our representative for further steps";
        
        
        $response.=$instructions;
        
        $CBConnect = new CB_Connect;
        $instructions.= $CBConnect->get_email_footer();

        $from = '"Career Breeder" <contactus@careerbreeder.com>';
        $headers = 'From: '.$from . "\r\n
        List-Unsubscribe: <mailto:contactus@careerbreeder.com>, <http://careerbreeder.com/member/unsubscribe/?listname=espc-tech@domain.com?id=12345N>\r\n
        Cc: contactus@careerbreeder.com";
        // Always set content-type when sending HTML email
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

        $subject = "Standing Instructions for Cash on Delivery: Career Breeder";
        $msg = "Dear ".$formValues->name_cod->firstName.",<br/><br/>".$instructions;
        wp_mail( $formValues->email_cod, $subject, $msg, $headers );
        wp_mail( "contactus@careerbreeder.com", $subject, $msg, $headers );
        
    }
/*    else if($formValues->payment_type=="paypal")
    {
        $response .= do_shortcode("[nicepaypallite name=\"CB-Test\" amount=\"$amount\" sku=\"$ref_no\"]");
    }*/
    return array( 'successPageHtml' => $response );

}
?>
