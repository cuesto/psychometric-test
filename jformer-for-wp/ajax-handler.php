<?php

require_once('jformer-for-wp.php');

function onSubmit($formValues)
{
	return array(
	'successPageHtml' => '<h2>Submission Complete</h2>
	<p>Thanks for contacting us, we will be in touch presently.</p>'
	);
}

$formID = $_POST['jFormerId'];

echo JFormerForWP::GetForm($formID);

exit;
?>