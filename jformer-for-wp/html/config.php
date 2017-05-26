<?php 
/***** 
	This file generates the Plugin Settings page. When creating a Settings page, try to use similar markup and classes as the rest of the WordPress Administration pages. By doing this, your Plugin page feels similar to the WordPress core Administration pages.

*****/
?>

<div class="wrap"> <!-- WordPress Admin pages begin with this div -->

<form action="options.php" method="post">

<?php settings_fields('jformer_options'); ?>

<?php do_settings_sections('jformer_plugin'); ?>

<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />

</form>

</div>
