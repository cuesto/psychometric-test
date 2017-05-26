=== Plugin Name ===
Contributors: g18c
Donate link: http://www.jformer.com/donate/
Tags: jformer forms
Requires at least: 3.2.1
Tested up to: 3.2.1

jFormer is a form framework written on top of jQuery that allows you to quickly generate beautiful, standards compliant forms. 


== Description ==

jFormer is a form framework written on top of jQuery that allows you to quickly generate beautiful, standards compliant forms. Leveraging the latest techniques in web design, jFormer helps you create web forms that:
- Validate client-side
- Validate server-side
- Process without changing pages (using AJAX)

This plugin allows the use of jFormer in Wordpress.


== Installation ==

1. Upload 'jformer-for-wp` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Set jFormer settings under 'Settings -> jFormer Plugin'
   a. Set Rewrite String for your forms to be made available under the specified slug, i.e. 'forms'
   
== Usage ==
1. Upload your form in the plugin directory 'jformer-for-wp/forms/', this file is included by the plugin and within the file you can:
   a. Create fields and other standard jFormer options by manipulating a $form object (this is a jFormer object created by the plugin).
   b. Process submissions by defining a 'function onSubmit($formValues)' function.
2. To use the above form, you have the following options:
   a. Link directly to the form by visiting your Wordpress site with the custom slug defined on the plugin settings page, followed by the form name, for example: 'http://www.yourdomain.com/forms/contact'
   b. Display the form within the page/post with the shortcode '[jformer id=formid]' where formid is the name of the form.
   c. Display a link to the form within the page/post with the shortcode '[jformer id=formid text="hyper link text" class=]' where text is the text to display and class is the class used for the html link.
   
Note: Due to an issue with jFormer it cannot send submissions to a URL with a dash in it, therefore keep all form file names to one word without any dashes.

A sample contact form exists in the jformer-for-wp/forms/ directory for your reference.


== Frequently Asked Questions ==

= How Do I use jFormer? =

This is only a plug-in not the library, for all other questions on how to use jFormer, please visitplease visit http://www.jformer.com for more information

= My form just shows 'Processing' but doesn't actually do anything =

Check the form being used does not have a '-' dash in its name.


== Screenshots ==

None at this time.


== Changelog ==

= 1.0.0 =
* Initial release.


== Upgrade Notice ==

None.

== Notes ==

== Changes to jformer.js ==

In order to integrate jformer.js into Wordpress AJAX processing the following changes were required:

= add jformer.js - line 2335 =
 // Wrap all of the form responses into an object based on the component jFormComponentType
        var formData = $('<input type="hidden" name="jFormer" />').attr('value', encodeURI(jFormerUtility.jsonEncode(this.getData()))); // Set all non-file values in one form object
        var formIdentifier = $('<input type="hidden" name="jFormerId" value="'+this.id+'" />');
        formClone.append(formData);
        formClone.append(formIdentifier);
		
		var formExtra = $('<input type="hidden" name="action" value="jFormerForWp" />'); 
		formClone.append(formExtra); 
		
= add jformer.php - line 730 =
  function processRequest($silent = false) {
		ob_start(); /* added manually for Wordpress plugin */
	
		print_r($_POST);

= add jformer.php - line 817 =
		$output = ob_get_clean();
		return $output;