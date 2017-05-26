<link href="<?php echo get_template_directory_uri(); ?>/css/select2.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo get_template_directory_uri();?>/js/select2.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery("#taxonomy").select2({width:480});
    jQuery("#languages_known").select2({width:480});
});
</script>
      <?php
//Initialize all variables

if(!is_user_logged_in())
{
    echo "You need to signin to continue.";
    echo "<a href=\"#\">Login / Register</a>";
    exit;
}
else 
{        
    $uid = get_current_user_id();
    $user_info = get_userdata($uid);
    $CBConnect = new CB_Connect;
    $name = $CBConnect->get_first_name();
    
    $args = Array('authors' => $uid, 'post_type'=> 'mentor');
    $pages = get_pages($args);
    

    $pageID='';
    if(isset($pages[0]->ID) && !empty($pages[0]->ID))
        $pageID = $pages[0]->ID;
    
    $languages = Array();
    $languages_selected = get_field('languages_known', $pageID);
    
    if($languages_selected == '')
        $languages_selected = Array();

    $resume = get_field('resume', $pageID);
    $resume = $resume['url'];


    for($i=1;$i<12;$i++)
    {
    
        array_push($languages ,             array(
                        'value' => $i,
                        'label' => cb_mentor_language($i),
                        'selected' => in_array($i,$languages_selected),
                    ));
    }
    
    //Taxonomy
    $args = array('numberposts' => -1, 'order' => 'ASC', 'post_status' => 'publish',
            'post_type' => 'discuss', );
    
        $allProfessions = get_pages($args);
        $discuss = Array();
        $discuss_terms = wp_get_object_terms($pageID, 'specialisation');
        foreach($discuss_terms as $discuss_term)
        {
            array_push($discuss, $discuss_term->name);
        }
        $i=0;
        foreach ($allProfessions as $profession) {
            $allSpecialisation[$i]['value'] = $profession->post_title;
            $allSpecialisation[$i]['label'] = $profession->post_title;
            $allSpecialisation[$i]['selected'] = in_array($profession->post_title, $discuss);
            $i++;
        }

    
    
    $options = array(
    
            new JFormComponentSingleLineText('name', 'Name:', array(
            'width' => 'longest',
            'initialValue' => $name,
            'validationOptions' => array('required'),
        )),
    
        new JFormComponentSingleLineText('contact_number', 'Mobile Number:', array(
            'initialValue' => $user_info->contact_number,
            'validationOptions' => array('required', 'phone'),
        )),
        
        new JFormComponentSingleLineText('experience', 'Work Experience (in years):', array(
            'mask' => '99',
            'initialValue' => get_field('experience', $pageID),
            'validationOptions' => array('required'),
        )),
    
        new JFormComponentSingleLineText('short_description', 'Your one line title:', array(
            'width' => 'longest',
            'maxLength' => 100,
            'initialValue' => get_field('short_description', $pageID),
            'validationOptions' => array('required'),
            'tip' => '<p>Please enter one line description of your profile. It will be heading of your profile. E.g. Senior Manager at Oracle with 10 years of Exp.</p>',
    
        )),
    
        new JFormComponentTextArea('description', 'Your profile:', array(
            'width' => 'longest',
            'height' => 'tall',
            'initialValue' => get_field('description', $pageID),
            'tip' => '<p>Please copy and paste your whole profile over here.</p>',
            'autogrow' => true,
        )),
    
    
        new JFormComponentDropDown('languages_known', 'Languages known:', $languages,
            array(
                'multiple' => true,                        
                'tip' => '<p>Please select only the languages in which you are fluent to counsel.</p>',
        )),
    
        new JFormComponentDropDown('taxonomy', 'Areas of interest:', $allSpecialisation,
            array('multiple' => true)),
    
    
    );
    if(isset($pageID) && !empty($pageID))
    {
        array_push($options, new JFormComponentFile('image', '<img src="'.get_field('image', $pageID).'" width="50" height="50" />Your profile pic:', array(
            'validationOptions' => array('extensionType' => 'image', 'size' => 500 ),
                Array (
                    'tip' => 'Maximum size: 500KB',
                )
            ))
        );
    
        array_push($options, new JFormComponentFile('resume', 'Resume (in pdf/doc/docx format): <a href="'.$resume.'">Your current resume</a>', 
        array(
            'validationOptions' => array('size' => 4096),
        ))
        );
    
    
        
        array_push($options, new JFormComponentHidden('pageId', $pageID));
    } else {
        
        array_push($options, new JFormComponentFile('image', 'Your profile pic:', array(
            'validationOptions' => array('required', 'extensionType' => 'image', 'size' => 500 ),
                Array (
                    'tip' => 'Maximum size: 500KB',
                )
            ))
        );
    
        array_push($options, new JFormComponentFile('resume', 'Resume (in pdf/doc/docx format):', array(
            'validationOptions' => array('required', 'size' => 4096),
        ))
        );
        
       array_push($options, new JFormComponentMultipleChoice('policies', '', array(
            array('value' => 'agree', 'label' => 'I agree to the site <a href="'.site_url('refund-policy').'" target="_blank" data-bitly-type="bitly_hover_card">Cancellation and Return Policy</a>?'),
                ),
                array(
                    'validationOptions' => array('required'),
            ))
        );
    
    }
    
    $form->addJFormComponentArray($options);
}    
// Set the function for a successful form submission
function onSubmit($formValues) {
    global $wpdb; 
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    
    $response = "";    
        
    $pageId = $formValues->pageId;
    
    
    $uid = get_current_user_id();
    
    $CBConnect = new CB_Connect;
    $name = $CBConnect->get_first_name();
    
    $uid = get_current_user_id();
    
    $user_info = get_userdata(get_current_user_id());
    $email = $user_info->user_email;
    
                    
    $contact_number = $formValues->contact_number;
    $experience = $formValues->experience;
    $short_description = $formValues->short_description;
    $description = $formValues->description;
    $languages_known = $formValues->languages_known;
    $taxonomy = $formValues->taxonomy;

    $image = $formValues->image;
    $resume = $formValues->resume;


    $pageId = $formValues->pageId;
    
    if(isset($pageId) && !empty($pageId))
    {
        
        if($user_info->first_name != $name)
        {
            wp_update_post(Array('ID'=>$pageId, 'post_title' => $name));
        }
        
        $response = 'Your profile was updated and is being reviewed. <a href="'.get_permalink($pageId).'"> View updated profile</a>';
    }
    else {
        
        $post = Array (
            'post_title' => $name,
            //'post_author' => $uid,
            'post_status' => 'publish',
            'post_type' => 'mentor',
            'comment_status' => 'open',
        );
        
        $pageId = wp_insert_post($post);
        $response = 'Your profile was created and is being reviewed by our team. Generally it takes 1-2 hours for approval. <br />Once approved, you can view your profile at<a href="'.get_permalink($pageId).'">'.get_permalink($pageId).'</a>';
        
        update_field('uid', $uid, $pageId );
        update_field('username', $email, $pageId );
        update_field('approved', true, $pageId );
        
        //Send email to user and administrator
        $msg = "Dear ".$name.",<br/><br/>
                        
                Thank you for registering as mentor at Career Breeder. Your profile is being reviewed by our team.<br />
                <br />
                Kindly allow us for 1-2 hours for approval of your profile.<br />
                <br />                                
                One approved, you can view your proifle at <a href=\"".get_permalink($pageId)."\">".get_permalink($pageId)."</a>.<br />
        ".$CBConnect->get_email_footer();        
        
        $from = '"Career Breeder" <contactus@careerbreeder.com>';
        $headers = 'From: '.$from . "\r\n
        List-Unsubscribe: <mailto:contactus@careerbreeder.com>, <http://careerbreeder.com/member/unsubscribe/?listname=espc-tech@domain.com?id=12345N>\r\n
        Cc: contactus@careerbreeder.com";
        // Always set content-type when sending HTML email
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

        $subject = "You registered as counselor at Career Breeder";

        wp_mail( $email, $subject, $msg, $headers );
        wp_mail( "contactus@careerbreeder.com", $subject, $msg, $headers );
    }
    
    update_field('experience', $experience, $pageId );
    update_field('short_description', $short_description, $pageId );
    update_field('description', $description, $pageId );
    update_field('languages_known', $languages_known, $pageId );
    
    
    $wpdb->update('wp_users', Array('contact_number'=>$contact_number), Array('ID' => $uid));        
    $wpdb->update('wp_users', Array('first_name'=>$name), Array('ID' => $uid));
    $wpdb->update('wp_users', Array('display_name'=>$name), Array('ID' => $uid));
    
    
    //taxonomy 
    wp_set_object_terms( $pageId, $taxonomy, 'specialisation' );      

    if(isset($image) && !empty($image))
    {
        $file = Array(
            'name' => $image->name,
            'type' => $image->type,
            'size' => $image->size,
            'tmp_name' => $image->tmp_name,
        );
        $override = array( 'test_form' => false );
        $movefile = wp_handle_upload($file , $override );
        if ( $movefile ) 
        {
            // Check the type of tile. We'll use this as the 'post_mime_type'.
            $filetype = wp_check_filetype( basename( $file['name'] ), null );

            // Get the path to the upload directory.
            $wp_upload_dir = wp_upload_dir();

            $attachment = array(
            	'guid'           => $wp_upload_dir['url'] . '/' . basename( $file['name'] ), 
            	'post_mime_type' => $movefile['type'],
            	'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file['name'] ) ),
            	'post_content'   => '',
            	'post_status'    => 'inherit'
            );
            $attachmentId = wp_insert_attachment( $attachment, $movefile['file'], $pageId );
            update_field('image', $attachmentId, $pageId );
        }
    }

    if(isset($resume) && !empty($resume))
    {
        $file = Array(
            'name' => $resume->name,
            'type' => $resume->type,
            'size' => $resume->size,
            'tmp_name' => $resume->tmp_name,
        );
        $override = array( 'test_form' => false );
        $movefile = wp_handle_upload($file , $override );
        if ( $movefile ) 
        {
            // Check the type of tile. We'll use this as the 'post_mime_type'.
            $filetype = wp_check_filetype( basename( $file['name'] ), null );

            // Get the path to the upload directory.
            $wp_upload_dir = wp_upload_dir();

            $attachment = array(
            	'guid'           => $wp_upload_dir['url'] . '/' . basename( $file['name'] ), 
            	'post_mime_type' => $movefile['type'],
            	'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file['name'] ) ),
            	'post_content'   => '',
            	'post_status'    => 'inherit'
            );
            $attachmentId = wp_insert_attachment( $attachment, $movefile['file'], $pageId );
            update_field('resume', $attachmentId, $pageId );
        }
    }


    
    return array( 'successPageHtml' => $response );

}
?>