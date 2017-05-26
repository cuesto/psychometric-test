<?php
	function onSubmit($formValues)
	{

		$response = $formValues->page->section[0]->component[0].'<h2>Submission Complete</h2><p>Thanks for contacting us, we will be in touch presently.</p>';
		return array(
		'successPageHtml' => $response, 'successJs' => 'setTimeout("self.close();",2000);');
	}

    $form->addJFormComponentArray(array(
		new JFormComponentSingleLineText('name', 'Name:', array(
            'width' => 'longest',
            'validationOptions' => array('required'),
        )),
        new JFormComponentSingleLineText('email', 'E-mail address:', array(
            'width' => 'longest',
            'validationOptions' => array('required', 'email'), // notice the validation options
        ))
    ));
?>