<?php
		wp_register_style( 'jformer.css', CBQUIZ_PLUGIN_URL . 'jformer.css', array(), '1.0.0.0' );
		wp_enqueue_style( 'jformer.css');
	
		wp_register_style( 'jformer.css', CBQUIZ_PLUGIN_URL . 'jformer.css', array(), '1.0.0.0' );
		wp_enqueue_style( 'jformer.css');

		wp_register_script( 'jFormer.js', CBQUIZ_PLUGIN_URL.'jFormer.js', array('jquery'), '1.0.0.0' );
		wp_enqueue_script( 'jFormer.js' );

		wp_register_script( 'countdown.min.js', CBQUIZ_PLUGIN_URL.'countdown.min.js', array('jquery'), '1.0.0.0' );
		wp_enqueue_script( 'countdown.min.js' );

//		wp_register_script( 'cb.js', CBQUIZ_PLUGIN_URL.'cb.js', array('countdown'), '1.0.0.0' );
//		wp_enqueue_script( 'cb.js' );

function cbquiz_user($test_id)
{
    global $wpdb;    
    include('jformer.php');



$table_name = $wpdb->prefix."cb_test";
$test = $wpdb->get_results("SELECT * FROM $table_name WHERE test_id = $test_id");
$table_name = $wpdb->prefix."cb_section";
$sections = $wpdb->get_results("SELECT * FROM $table_name WHERE test_id = $test_id ORDER BY section_no ASC");
$pageNavigation = true;
if($test[0]->duration>0)
    $pageNavigation = false;


// Create the form
$survey = new JFormer('survey', array(
            'submitButtonText' => 'Submit',
            'title' => '<p>'.$test[0]->name.'</p>',
            'pageNavigator' => $pageNavigation,
            'action' => admin_url('admin-ajax.php')
        ));

// Create the form page
$i=1;
foreach($sections as $section)
{
    $jFormPage = 'jFormPage'.$i;
    $$jFormPage = new JFormPage($survey->id . 'Page'.$section->section_id, array(
            'title' => $section->name
    ));
    
    $jFormSection = 'jFormSection'.$i;
    $$jFormSection = new JFormSection($survey->id . $section->section_id, array(
        ));
    
    $table_name= $wpdb->prefix.'cb_questions';
    $questions = $wpdb->get_results("SELECT * FROM $table_name WHERE section_id =".$section->section_id." ORDER BY question_no ASC");
    
    $option_array =Array();
    
    foreach($questions as $question)
    {
        $table_name= $wpdb->prefix.'cb_option';
        $options = $wpdb->get_results("SELECT * FROM $table_name WHERE question_id =".$question->question_id." ORDER BY question_no ASC");
        
        $option_array = Array();
        
        foreach($options as $option)
        {
            array_push(
                $option_array,
                array('value' => $option->question_no , 'label' => $option->name)
            );
        }    

            $required = array(
                'multipleChoiceType' => 'radio',
                'validationOptions' => array('required'), );
            
            if($pageNavigation==false)
                $required = array('multipleChoiceType' => 'radio');
                
    $$jFormSection->addJFormComponentArray(Array(
        new JFormComponentMultipleChoice('question'.$question->question_no, $question->question, $option_array,
                    $required
            )
            ));
            

    }
    $i++;
// Add the section to the page
$$jFormPage->addJFormSection($$jFormSection);

//$$jFormPage->addJFormSection($$jFormSection);

// Add the page to the form
$survey->addJFormPage($$jFormPage,$section->duration."&".$section->section_id);
//$survey->addJFormPage($$jFormPage);
    
}

/*

new JFormComponentMultipleChoice('contact', 'Email me when a new comment is posted.',
            array(
                array('value' => 'yes', 'label' => 'Yes, contact me.'),
                array('value' => 'no', 'label' => 'No, I don\'t like being popular.'),
            ),
            array(
                'multipleChoiceType' => 'radio',
                'validationOptions' => array('required'),
            )
        ),


*/
/*$jFormPage2 = new JFormPage($survey->id . 'Page2', array(
            'title' => 'Page 2'
        ));



// Create the form section
$jFormSection1 = new JFormSection($survey->id . 'Section1', array(
        ));

// Create the form section
$jFormSection2 = new JFormSection($survey->id . 'Section2', array(
        ));

// Add components to the section
$jFormSection1->addJFormComponentArray(Array(
    new JFormComponentLikert('likert1', 'Please evaluate the following statements:',
            array(
                array('value' => '1', 'label' => 'Strongly Disagree', 'sublabel' => '1'),
                array('value' => '2', 'label' => 'Disagree', 'sublabel' => '2'),
                array('value' => '3', 'label' => 'Agree', 'sublabel' => '3'),
                array('value' => '4', 'label' => 'Strongly Agree', 'sublabel' => '4'),
                array('value' => '-', 'label' => 'No Opinion', 'sublabel' => '-'),
            ),
            array(
                array(
                    'name' => 'statement1',
                    'statement' => 'You really like JFormer.',
                    'validationOptions' => array('required'),
                ),
                array(
                    'name' => 'statement2',
                    'statement' => 'Sometimes you dream about JFormer.',
                ),
                array(
                    'name' => 'statement3',
                    'statement' => 'You want to donate to JFormer.',
                    'description' => '<p>You can do that conveniently <a href="/donate/"> here</a></p>',
                    'tip' => '<p>Oh My! I have a tip on this specific statement.</p>'),
            ),
            array(
                'validationOptions' => array('required'),
                'description' => '<p>Likert description.</p>',
            )
    ),
));

$jFormSection2->addJFormComponentArray(array(
    new JFormComponentLikert('likert2', 'Just a few more questions:',
            array(
                array('value' => '1', 'label' => 'Strongly Disagree', 'sublabel' => '1'),
                array('value' => '2', 'label' => 'Disagree', 'sublabel' => '2'),
                array('value' => '-', 'label' => 'No Opinion', 'sublabel' => '-'),
            ),
            array(
                array(
                    'name' => 'question1',
                    'statement' => 'I don\' think I would ever use a likert.',
                    'validationOptions' => array('required'),
                ),
                array(
                    'name' => 'question2',
                    'statement' => 'I could make something cooler than Jformer',
                ),
            ),
            array(
                'validationOptions' => array('required'),
                'description' => '<p>Likert description.</p>',
            )
    ),
));

// Add the section to the page
$jFormPage1->addJFormSection($jFormSection1);

$jFormPage2->addJFormSection($jFormSection2);

// Add the page to the form
$survey->addJFormPage($jFormPage1);
$survey->addJFormPage($jFormPage2);
*/

// Process any request to the form
return  $survey->processRequest();
}

// Set the function for a successful form submission
function onSubmit123($formValues) {
    return array(
        'successPageHtml' => '<p>Thanks for Using jFormer</p>
            <p>Thanks for trying out the Likert Demo</p>'
    );
}

?>