<?php
/*
Plugin Name: jFormer for WordPress
Plugin URI: http://unifiedmicrosystems.com/wordpress/plugins/jformer-for-wp/
Description: jFormer is an awesome form framework written on top of jQuery that allows you to quickly generate beautiful, standards compliant forms (http://www.jformer.com/). This plugin allows for the easy use of the framework from within Wordpress.
Version: 1.0.0
Author: Christopher Morley
Author URI: http://www.unifiedmicrosystems.com

Thanks to Roman Wünsche and Wordpress Stack Exchange for help with the template redirects.

Copyright 2011 Unified Microsystems

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once('jFormer/jformer.php');

/*interface JFormerForWPFormClass
{
  public function GetForm();
  public function ProcessForm();
}*/

class JFormerForWP
{
	static $errors = false; /* for debugging */
	
	const DB_VERSION = 1; // This number represents the current version of the plugins table structure. Increment this every time you modify the scheme of the database tables that you create for your plugin.

	static $pluginPath;  
	static $pluginUrl;	

	public static function makeFormFactory($id,$class)
	{
		$classPath = self::$pluginPath . "/forms/{$class}.php";
		require_once $classPath; 
		/*$this->registry[$id] = new $class();*/
	}

	/* called each request */
	public static function init()
	{
		self::$pluginPath = dirname(__FILE__);  // Set Plugin Path  
		self::$pluginUrl = WP_PLUGIN_URL . '/jformer-for-wp/'; // Set Plugin URL  

		self::addRewriteRules();
		add_filter( 'query_vars', array(__CLASS__, 'addQueryVars'));
		add_action( 'template_redirect', array(__CLASS__, 'formDisplay'));
	
		//add_action('wp_print_styles', array(__CLASS__, 'styles'));
		//add_action('wp_print_scripts', array(__CLASS__, 'scripts') );
		add_shortcode('jformer', array(__CLASS__, 'shortcodeHandler'));
		
		/* for ajax functionality */
		add_action('wp_ajax_nopriv_jFormerForWp', array(__CLASS__, 'ajaxHandler'));
		add_action('wp_ajax_jFormerForWp', array(__CLASS__, 'ajaxHandler'));
		
		self::$errors = new WP_Error();
	}
	
	public static function ajaxHandler()
	{
		echo self::getForm($_POST['jFormerId123']);
	}
	
	public static function getForm($id=null)
	{          
	   JFormerForWP::scripts();

       $splash = "";
       if($id ==1) {
 	   JFormerForWP::styles_c();
                   $formFileName = self::$pluginPath . '/forms/'. 'register' .  '.php';
                $test_details = 
                array('title' => "Sign up" ,
                      'submitButtonText' => 'Register me now' ,
                      'action' => admin_url('admin-ajax.php'),
                );
        }
        else if($id == 2) {
 	   JFormerForWP::styles_c();
            $formFileName = self::$pluginPath . '/forms/'. 'order' .  '.php';
            $test_details = array('title' => "" ,
                              'submitButtonText' => 'Pay now' ,
                              'action' => admin_url('admin-ajax.php'),
                    );
        }
       else if($id ==3) {
   	   JFormerForWP::styles_c();
          $formFileName = self::$pluginPath . '/forms/'. 'register' .  '.php';
                $test_details = 
                array('title' => "" ,
                      'submitButtonText' => 'Take me to payment page' ,
                      'action' => admin_url('admin-ajax.php'),
                );
        }
        else if($id == 4) {
 	   JFormerForWP::styles_c();
            $formFileName = self::$pluginPath . '/forms/'. 'abroad' .  '.php';
            $test_details = array('title' => "" ,
                              'submitButtonText' => 'Guide me' ,
                              'action' => admin_url('admin-ajax.php'),
                    );
        }
        else if($id == 5) {
 	   JFormerForWP::styles_c();
            $formFileName = self::$pluginPath . '/forms/'. 'mentor' .  '.php';
            $test_details = array('title' => "" ,
                              'submitButtonText' => 'I am ready to enlighten my country members' ,
                              'action' => admin_url('admin-ajax.php'),
                    );
        }        
        else if($id>1000){
  	   JFormerForWP::styles();
           $formFileName = self::$pluginPath . '/forms/'. 'fbtest' .  '.php';
             $splash = array(
                            'content' => JFormerForWP::splashScreen($id),
                            'splashButtonText' => 'I\'m ready',
                         );                       
            $test_details = array(
                        'title' => "<div class=\"load_overlay\"><div class=\"loading_text\">Loading....</div></div>" ,
                        'submitButtonText' => 'Submit the test' ,
                        'action' => admin_url('admin-ajax.php'),
                        'splashPage' => $splash,
                        'pageNavigator' => false,
                );
                $_SESSION['test_id'] = $id;		  
		}
        else {
 	   JFormerForWP::styles();
            $formFileName = self::$pluginPath . '/forms/'. 'test' .  '.php';
             $splash = array(
                            'content' => JFormerForWP::splashScreen($id),
                            'splashButtonText' => 'Start Test',
                         );                       
            $test_details = array(
                        'title' => "<div class=\"load_overlay\"><div class=\"loading_text\">Loading....</div></div>" ,
                        'submitButtonText' => 'Submit the test' ,
                        'action' => admin_url('admin-ajax.php'),
                        'splashPage' => $splash,
                );
                $_SESSION['test_id'] = $id;
		} 
        $formFileName = realpath($formFileName);

		$customCssFile =  self::$pluginPath . '/css/' . $id . '.css';
		$customCssFile = realpath($customCssFile);

		if(file_exists($customCssFile)==true)
		{	
			wp_enqueue_style($id , self::$pluginUrl. 'css/' . $id . '.css');
		}

		$form = new JFormer('test', $test_details);
        $test_id = $id;
        include($formFileName); // include the found form (which contains code which manipulates $form
		
		return $form->processRequest();	
    }

	public static function shortcodeHandler($atts, $content)
	{
		extract( shortcode_atts( array(
		'id' => '0',
		'class' => '',
		'text' => ''
		), $atts ) );
		
		// echo 'dumping shortcode values. content: ' . $content . ', id: ' . $id . ', class: ' . $class . '<br >';
		$id = $_GET['test_id'];
		$options = self::getOptions();
		$permastructString = $options['url_string'];
		
		
		if( $text == '' ) 
		{
			return self::getForm($id); /* return the actual form */
		}
		else /* return a link to the form */
		{
			if( $class=='' )
				return '<a href="' . home_url() . '/' . $permastructString . '/' . $id . '" title="'. $title .'">' . $text . '</a>';
			else
				return '<a href="' . home_url() . '/' . $permastructString . '/' . $id . '" title="'. $title .'" class="'. $class .'">' . $text . '</a>';
		}
	}
	
	public static function getOptions()
	{
		$options = get_option('jformer_options',array('url_string' => 'forms'));		
		return $options;
	}
	
	public static function addRewriteRules()
	{
		$options = self::getOptions();
		add_rewrite_rule( $options['url_string'] . '/?([^/]*)','index.php?formid=$matches[1]', 'top' ); 
    }
	
	// if we wish to use filter actions instead of other rewrite methods
	// add_filter( 'rewrite_rules_array', array(__CLASS__,'addRewriteRules'));
	/* public static function addRewriteRules($rules) 
	{ 
		$newRules = array( 
			'forms/?([^/]*)' => 'index.php?formid=$matches[1]', 
			); 
		return $newRules + $rules; // add the rules on top of the array 
    }*/
	
	public static function addQueryVars($vars)
	{
		$vars[] = 'formid';
		return $vars;
	}
	
	public static function formDisplay()
	{
		if( $formid = get_query_var( 'formid' ) )
		{
			$formid = strtolower($formid);
			$formCode = self::getForm($formid);
			if($formCode != -1)
			{
				include ( self::$pluginPath . '/html/redirect-page-header.php' );
				echo $formCode;
				include ( self::$pluginPath . '/html/redirect-page-footer.php' );
				exit;
			}
		}
	}

	public static function activate() 
	{
		if (version_compare(PHP_VERSION, '5', '<'))
		{
			deactivate_plugins(basename(__FILE__));
			wp_die(printf('Sorry, this plugin requires PHP 5 or higher. Your PHP version is "%s". Ask your web hosting service how to enable PHP 5 on this servers.', PHP_VERSION));
		}
	
		self::addRewriteRules();
		flush_rewrite_rules();
	}
	
	public static function deactivate()
	{
		flush_rewrite_rules();
		delete_option('jformer_options');
	}
	
	/* load_plugin_textdomain simply tells WordPress the textdomain we set for our translation strings, and tells it where to look for our language files.  */
	public static function setLanguage()
	{
		load_plugin_textdomain( 'JFormerForWP', null,  dirname( plugin_basename( __FILE__ ) ) . '/languages/');
	}
	
	
	public static function addAdminPage()
	{
		add_options_page( 'jFormer', 'jFormer Plugin', 'manage_options', 'jFormerForWP', array( __CLASS__ , 'displayAdminPageHtml' ));
	}
	
	public static function adminInit()
	{
		register_setting( 'jformer_options', 'jformer_options', array(__CLASS__,'validateOptions') );
		add_settings_section('jformer_main', 'jFormer Settings', array(__CLASS__,'sectionText'), 'jformer_main_options');
		add_settings_field('jformer_url_string', 'Rewrite String', array(__CLASS__,'displayUrlStringSetting'), 'jformer_main_options', 'jformer_main');
		// add_action('admin_notices', array(__CLASS__,'displayAdminNotices'));  
	}
	
	public static function sectionText()
	{
		echo '<p>Main description</p>';
	}
	
	public static function displayUrlStringSetting()
	{
		$options = self::getOptions();
		echo "<input id='jformer_url_string' name='jformer_options[url_string]' size='40' type='text' value='{$options['url_string']}' />";
	}
	
	public static function validateOptions($input)
	{
		$input['url_string'] = trim($input['url_string']);
		
		if(!preg_match('/^[a-z0-9]{1,}$/i', $input['url_string']))
		{
			add_settings_error('jFormerForWP', 'url_string_error', 'Invalid URL string, please fix ensure string consists of alphanumeric characters only and is at least 1 character long', 'error' );
		}
		else
		{
			// add_settings_error('jFormerForWP', 'url_string_updated', 'String updated', 'updated' );
			$validatedOptions['url_string'] = $input['url_string'];
		}
		
		return $validatedOptions;
	}
	
	public static function registerAdminSettings()
	{
		/* The first parameter is the name of the Settings group that is going to be saved. As you can see I have two separate groups of settings that can be saved independently. The second parameter is the name of the setting you want to save. Notice that I've added the plugin name to the beginning of the setting to avoid a naming conflict in the database.*/
		register_setting('JFormerForWPSettings', 'JFormerForWPSettings_option1');
	
	}
	
	/*	This is the callback function that we defined above to run when the user clicks on the link to get to our plugin's configuration page. You could simple us an echo statement in this function to output all of your html here, but in order to keep things a little cleaner and more organized, I use the following method to retrieve the html from an external file. */		
	public static function displayAdminPageHtml()
	{	
		?>
		<div class="wrap">
			<h2>jFormer for WordPress Plugin Options</h2>
			Options relating to the Custom Plugin.
			<form action="options.php" method="post">
				<?php	
				settings_fields('jformer_options'); ?>
				<?php do_settings_sections('jformer_main_options'); ?>
				<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
			</form>
		</div>
		<?php
	}
	
	public static function displayAdminNotices()
	{
		$errors = get_settings_errors();
		//var_dump($errors);
		
		if(!isset($errors))
			return;
		
		$flushedRules = false;
		
		foreach($errors as $set_error)
		{  	
			if( ($set_error['code'] == 'url_string_updated') )
			{
				/* on first activation, for some reason two of these messages are received! lets make it only work on one of them... */
				if($flushedRules==false)
				{
					self::showMessage('Rewrite string updated... flusing rewrite rules');
					flush_rewrite_rules();
				}
				$flushedRules = true;
			
			}
			else
			{
				self::showMessage("<p>class='setting-error-message' title='" . $set_error['setting'] . "'>" . $set_error['message'] . "<p>", 'error'); 
			}
        }  
	}
	
	function showMessage($message, $msgclass = 'updated')
	{  
		echo "<div id='message' class='$msgclass'>$message</div>";  
	}
    
    function splashScreen($test_id)
    {
        if($test_id == APTITUDE_TEST)
        {
            $rules = "<li>Test duration is 25 minutes only and you have to to complete all the answers within the timeframe.</li>	<li>Questions are divided in five sections, after each section other sections will come.</li>
                    <li>Each question has two choices. There is no right or wrong answer, you have to choose the one which is more appropriate.</li>
                    <li>You cannot leave any questions in the test.</li>
                    <li>Sit in a calm place and switch off your mobile.</li>";
            return JFormerForWP::splashScreenFormat("", "", "25 minutes", $rules);
        } else if($test_id == IQ_TEST)
        {
            $rules = "<li>Test duration is 20 minutes only.</a></li>
            		<li>Take a copy and pen before you start the test.</li>
                    <li>Sit in a calm place and switch off your mobile.</li>
                    <li>Questions are divided in two sections, after each section other sections will come.</li>";
            return JFormerForWP::splashScreenFormat("", "", "20 minutes", $rules);            
        }
        return "aa".$test_id;
    }
    function splashScreenFormat($first_name = null, $last_name=null, $duration=null, $rules=null)
    {
        $a = '
        	<div class="tst_hdrmainlft">
            	<div class="tst_title">
                    <h2>
                	<span class="test_title1">'.$first_name.'&nbsp;</span>
                    <span class="test_title2">'.$last_name.'</span>
                    </h2>
                </div>
                
                <div class="clear"></div>
                
                <div class="tst_classification">
                	<span class="classi_txt1">Please read guidelines carefully before you start the test</span>
                </div>
                
                <div class="clear"></div>
                
               <div id="tst_guidline">
               	<ul>'.$rules.'
                </ul>
               </div>
                
            </div><!--test header main lft ends -->';
            
           /*<div class="tst_hdrmainrgt">
            	<div id="videoguide_title">
                	<!--timer starts -->
           	  <div id="timer_wrap">
                        	
                        </div>
                    <!--timer ends -->
                <div class="clear"></div>
                    
                </div>
                <div class="clear"></div>
          <div id="tstquest_certification">
                	<!--span class="classi_txt1">Certification</span--><br />
                    <span class="classi_txt2"></span>
                </div>  
                <div class="clear"></div>
                <div class="questtake_tst"></div>                
                <div class="clear"></div>                             
          </div>
        ';*/
        return $a;
    }
      
	
	/* This filter lets us add or own links to this list. I'm using it to provide users with a shortcut to the Plugin's settings page, but it could be used for anything (e.g. to insert a donate link, or link to Plugin suppert forums). The function takes an array of links, adds our link to the array and then returns the modified array. */
	public static function insertSettingsLink($links)
	{
		$settings_link = '<a href="options-general.php?page=JFormerForWP">'.__('Settings','JFormerForWP').'</a>'; 
		 array_unshift( $links, $settings_link ); 
		 return $links; 
	}
	
	/* This function adds JavaScript and CSS to the viewer facing side of WordPress. Scroll up to AdminStyles and AdminScripts() to see how these functions work */
	public static function styles()
	{		
		wp_enqueue_style('jformer', self::$pluginUrl. 'css/style.css');
	}
	
    public static function styles_c()
	{		
		wp_enqueue_style('jformer', self::$pluginUrl. 'css/style-c.css');
	}
    
    
	public static function scripts()
	{
	    wp_register_script('jformer', self::$pluginUrl.'jFormer/jFormer.js', array('jquery'), '1.10.2', true);
		wp_enqueue_script('jformer');
		wp_enqueue_script( 'countdown.min.js', self::$pluginUrl.'countdown.min.js', array('jformer'), '1.10.2', true);
        
		wp_enqueue_script( 'jquery-ui-progressbar' );

		$translation_array = array(
			'plugin_url' => plugin_dir_url( __FILE__ )
		);
		
		wp_localize_script( 'jformer', 'php_encoded', $translation_array );

		
		/**wp_localize_script('jformer','JFormerForWP',
			array(
				'AjaxError'=>__('An error occurred. Please try your action again. If the problem persists please contact the developer.','B2Template'),
				'plugin_url' => plugin_dir_url( __FILE__ )
		));**/
	}
}

register_activation_hook(__FILE__, array('JFormerForWP','activate'));
register_deactivation_hook(__FILE__, array('JFormerForWP','deactivate'));
add_action('init', 'JFormerForWP::init');
add_action('admin_init', 'JFormerForWP::adminInit');
add_action('admin_menu', 'JFormerForWP::addAdminPage');
?>