<?php
// settings definiiton
$PlusCaptcha_options = array(
    'uuid_key_speci_to_generate_captchas' => array(
												'title' => __('ID of 9 Characters', 'PlusCaptcha'), 
												'description' => __('Get ID (UUID) registering on www.pluscaptcha.com and customizing your captcha!', 'PlusCaptcha'),
												'maxchars' => __('maxlength="9"')
											),
    'background_color_inside_iframe' => array(
												'title' => __('Background Color Inside Iframe', 'PlusCaptcha'), 
												'description' => __('Set none or the color code without hashtag, ex: ffa3c8, ffd5a3'),
												'maxchars' => __('maxlength="6"')
											),
    //'PlusCaptcha_key' => array('title' => __('PlusCaptcha Key', 'PlusCaptcha'), 'description' => __('Insert PlusCaptcha Key', 'PlusCaptcha')),
    //'PlusCaptcha_secret' => array('title' => __('PlusCaptcha Secret', 'PlusCaptcha'), 'description' => __('Insert PlusCaptcha Secret', 'PlusCaptcha')),
//    'PlusCaptcha_public_url'			=> array('title' => __('PlusCaptcha Public URL', 'PlusCaptcha'), 'description' => __('Default values is "/wp-content/plugins/PlusCaptcha/library/PlusCaptcha.php" - don\'t change it unless you know what are you doing.', 'PlusCaptcha')),
    'PlusCaptcha_form_comment' => array('title' => __('Captcha in Comment Form', 'PlusCaptcha')),
    //'PlusCaptcha_form_registration' => array('title' => __('Captcha in Register Form', 'PlusCaptcha')),
    'PlusCaptcha_form_lost' => array('title' => __('Captcha in Password Recovery of Login', 'PlusCaptcha')),
    'PlusCaptcha_form_login' => array('title' => __('Captcha in Login Form', 'PlusCaptcha')),
	    'PlusCaptcha_form_omit_users' => array('title' => __('Hide Captcha to Logged Users', 'PlusCaptcha')),
	    'PlusCaptcha_form_omit_backlink' => array('title' => __('Hide Backlink', 'PlusCaptcha'), 'description' => __('If you hide the backlink you will not Collaborate More with EcoPlus mission.', 'PlusCaptcha')),
		'PlusCaptcha_form_feedback' => array('title' => __('Feedback Program', 'PlusCaptcha'), 'description' => __('The "Feedback Program" help to us make the service better every day. <a href="#">Read More.</a>', 'PlusCaptcha'), 'disabled' => true),
		'PlusCaptcha_feedback_quemejoraria' => array(
														'title' => __('What do you love of PlusCaptcha and what do you think we can amend?', 'PlusCaptcha'),
														'maxchars' => __('maxlength="299"')
		),
    'PlusCaptcha_form_contact' => array('title' => __('<b>Contact Form</b>', 'PlusCaptcha'), 'description' => __('Insert a contact form where you wanna. In one page, post, or everywhere with a simple code. Just copy this code: [im_helping_with_ecoplus] in the description of your page, post, etc.', 'PlusCaptcha')),
);

/**
 * @return true if PlusCaptcha is properly registered.
 */
function PlusCaptcha_is_registered() {
  return (
  			(get_option('uuid_key_speci_to_generate_captchas', '')) 
			&& (get_option('PlusCaptcha_feedback_quemejoraria', '')) 
			//&& (get_option('PlusCaptcha_key', '')) 
			//&& (get_option('PlusCaptcha_secret', ''))
 		 );
}

/**
 * Display admin notices.
 * @return void
 */
function PlusCaptcha_admin_notices() {
  // If the plugin is not configured yet.
  if (!PlusCaptcha_is_registered()) {
    echo '<div class="error PlusCaptcha" style="text-align: center; float:left;width:99%;">
      <p style="color: red; font-size: 14px; font-weight: bold;">' . __('Your PlusCaptcha plugin is not setup yet') 
      . '</p><p>' . __('Click ') . '<a href="options-general.php?page=PlusCaptcha">' . __('here') . '</a> ' 
      . __('to finish setup.') . '</p></div>'
    ;
  }
}

/**
 * Add PlusCaptcha settings link to admin menu
 * @return void
 */
function PlusCaptcha_admin_menu() {
  //$menu_item = "<div class='admin-menu-item'>PlusCaptcha</div>";
  //add_options_page(__('PlusCaptcha', 'PlusCaptcha'), __($menu_item, 'PlusCaptcha'), 'manage_options', 'PlusCaptcha', 'PlusCaptcha_options_page');
  add_menu_page(__('PlusCaptcha', 'PlusCaptcha'), __('PlusCaptcha', 'PlusCaptcha'), 'manage_options', 'PlusCaptcha', 'PlusCaptcha_options_page', 
          PlusCaptcha_URL.'/images/menu-icon.png');
}

/**
 * PlusCaptcha options page logic
 * @return void
 */
function PlusCaptcha_options_page() {
  //must check that the user has the required capability 
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }

  //$skip_register = ((isset($_REQUEST['skip_register'])) && ($_REQUEST['skip_register'] == 1));
  //if (true) {
    sweetcatpcha_main_settings();
  //} else {
    PlusCaptcha_register_form();
  //}
}

/**
 * Displays the PlusCaptcha register form.
 * @return void
 */
function PlusCaptcha_register_form() {
  global $PlusCaptcha_instance;

  $hidden_field_name = 'mt_submit_hidden';
  $form_html = 'Could not load registration form.';
  //var_export($_POST);
  // See if the user has posted us some information
  // If they did, this hidden field will be set to 'Y'
  if ((isset($_POST[$hidden_field_name])) && ($_POST[$hidden_field_name] == 'Y')) {
    $result = json_decode(submit_register_form($_POST), true);
    if ($result['error']) {
      if (!empty($result['html'])) {
        $form_html = $result['html'];
      }
    } else {
      update_option('uuid_key_speci_to_generate_captchas', $result['app_id']);
	  update_option('PlusCaptcha_feedback_quemejoraria', $_POST["PlusCaptcha_feedback_quemejoraria"]);
      //update_option('PlusCaptcha_key', $result['key']);
      //update_option('PlusCaptcha_secret', $result['secret']);

      // Load the main options page, and ignore the post data (since it's missing all the options!).
      sweetcatpcha_main_settings(true);

      // Hide the "your plugin is not set up yet" message.
      echo "
				<script type=\"text/javascript\" language=\"javascript\">
					jQuery('div.error.PlusCaptcha').hide();
				</script>
			";
      return;
    }
  } else {
    //$form_html = $PlusCaptcha_instance->get_register_form();
	$form_html = "NONE";
  }
  // Fill Register Form fields
  $website = json_encode(empty($_POST['website']) ? "http://{$_SERVER['SERVER_NAME']}/" : $_POST['website']);
  $email = json_encode($_POST['email']);
  
  //jQuery('<div><input type="text" class="field" name="dynamic[]" value="' + i + '" /></div>').fadeIn('slow').appendTo('.inputs');
  $form_html .= "<script type=\"text/javascript\" language=\"javascript\">\n";
  $form_html .= "    jQuery('input[name=website]').addClass('requiredField');\n";
  $form_html .= "    jQuery('input[name=email]').addClass('requiredField');\n";
  $form_html .= "    jQuery('select[name=site_category]').addClass('requiredField');\n";
    
  $form_html .= "    jQuery('input[name=website]').val($website);\n";
  $form_html .= "    jQuery('input[name=email]').val($email);\n";

  if (isset($_POST['language'])) {
    $language = (int) $_POST['language'];
    $form_html .= "    jQuery('select[name=language]').val($language);\n";
  }
  if (isset($_POST['category'])) {
    $category = (int) $_POST['category'];
    $form_html .= "    jQuery('select[name=category]').val($category);\n";
  }
  if (isset($_POST['site_category'])) {
    $site_category = (int) $_POST['site_category'];
    $form_html .= "    jQuery('select[name=site_category]').val($site_category);\n";
  }
  if (isset($_POST['gender'])) {
    $gender = (int) $_POST['gender'];
    $form_html .= "    jQuery('select[name=gender]').val($gender);\n";
  }
  $form_html .= "</script>\n";
  
  $form_html = preg_replace('/category:/', 'PlusCaptcha design:', $form_html);
  //$form_html = preg_replace('/Please fill in your site details/', 'Fill in your PlusCaptcha details to activate:', $form_html);
  $form_html = preg_replace('/language:/', 'PlusCaptcha language:', $form_html);
  //$form_html = preg_replace('/PlusCaptcha theme:/', 'PlusCaptcha design:', $form_html);
  //$form_html = str_lreplace("</tr>", '</tr><tr><td class="left">Website category:</td><td class="right"><select name="site_category">'.$select_html.'</select></td></tr>',$form_html);
  
  $form_html .= "<script type=\"text/javascript\">\n jQuery('input[name=email_verify]').val(jQuery('input[name=email]').val()); jQuery('input[name=email_verify]').parent().parent().hide(); </script>\n";
  $form_html .= "<script type=\"text/javascript\">\n jQuery('input[name=email]').change(function() {jQuery('input[name=email_verify]').val(jQuery(this).val());}); </script>\n";
  
  /*
  $cats = file(PlusCaptcha_ROOT.'/site-categories.txt');
  $select_html = '';
  foreach ($cats as $cat) {
    $cat =  trim ( $cat);
    $select_html .= "<option value='$cat'>$cat</option>";
  }
  //$form_html = preg_replace(strrev("|</tr>|"),'</tr><tr><td></td><td></td></tr>',$form_html,1);
  $form_html = str_lreplace("</tr>", '</tr><tr><td class="left">Website category:</td><td class="right"><select name="site_category">'.$select_html.'</select></td></tr>',$form_html);
  */


}

function str_lreplace($search, $replace, $subject) {
  return substr_replace($subject, $replace, strrpos($subject, $search), strlen($search));
}

/**
 * Displays the main PlusCaptcha settings.
 * @return void
 */
function sweetcatpcha_main_settings($ignore_post = false) {
  global $PlusCaptcha_options, $plscptf_options;

  // variables for the field and option names 
  $opt_name = 'mt_favorite_color';
  $hidden_field_name = 'mt_submit_hidden';
  $data_field_name = 'mt_favorite_color';

  PlusCaptcha_contactform_settings();

  // See if the user has posted us some information
  // If they did, this hidden field will be set to 'Y'
  if ((!$ignore_post) && (isset($_POST[$hidden_field_name])) && ($_POST[$hidden_field_name] == 'Y')) {
    $rs = TRUE;

    // Read their posted value
    foreach ($PlusCaptcha_options as $opt_name => $v) {
      $opt_val = isset($_POST[$opt_name]) ? $_POST[$opt_name] : null;

      // Save the posted value in the database
      update_option($opt_name, $opt_val);
    }

    if ( plscptf_settings_save() ) {
      // Put an settings updated message on the screen
      $saved_html = 'Settings saved.';
      if (PlusCaptcha_is_registered()) {
        $saved_html .= "
				<script type=\"text/javascript\" language=\"javascript\">
					jQuery( 'div.error.PlusCaptcha' ).hide();
				</script>
      	";
      }
    }

    $message = $rs ? __($saved_html, 'PlusCaptcha') : __('settings cannot be saved.', 'PlusCaptcha');
  }

  // Read in existing option value from database
  $options_values = PlusCaptcha_options();

  //echo 'plscptf_options='; var_export($plscptf_options);
  require_once PlusCaptcha_TEMPLATE . '/admin-options.php';

}

// Sweet Captcha Contact Form settings 
function PlusCaptcha_contactform_settings() {
  global $plscptf_options;

  $plscptf_option_defaults = array(
      'plscptf_user_email' => 'admin',
      'plscptf_custom_email' => '',
      'plscptf_select_email' => 'user',
      'plscptf_additions_options' => 0,
      'plscptf_send_copy' => 0,
      'plscptf_from_field' => get_bloginfo('name'),
      'plscptf_display_add_info' => 1,
      'plscptf_display_sent_from' => 1,
      'plscptf_display_date_time' => 1,
      'plscptf_mail_method' => 'wp-mail',
      'plscptf_display_coming_from' => 1,
      'plscptf_display_user_agent' => 1,
      'plscptf_change_label' => 0,
      'plscptf_name_label' => __("Name:", 'PlusCaptcha'),
      'plscptf_email_label' => __("E-Mail Address:", 'PlusCaptcha'),
      'plscptf_subject_label' => __("Subject:", 'PlusCaptcha'),
      'plscptf_message_label' => __("Message:", 'PlusCaptcha'),
      'plscptf_action_after_send' => 1,
      'plscptf_thank_text' => __("Thank you for contacting us.", 'PlusCaptcha'),
      'plscptf_redirect_url' => ''
  );
  if (!get_option('PlusCaptcha_form_contact_options'))
    add_option('PlusCaptcha_form_contact_options', $plscptf_option_defaults);

  $plscptf_options = get_option('PlusCaptcha_form_contact_options');
  if (is_array($plscptf_options) ) {
    $plscptf_options = array_merge($plscptf_option_defaults, $plscptf_options);
  } else {
    $plscptf_options = $plscptf_option_defaults;
  }
  update_option('PlusCaptcha_form_contact_options', $plscptf_options);
}

function plscptf_settings_save() {
  global $plscptf_options, $wpdb, $error;
  $userslogin = $wpdb->get_col("SELECT user_login FROM  $wpdb->users ", 0);
  $plscptf_options_submit = array();
  // Save data for settings page
  $plscptf_options_submit['plscptf_user_email'] = $_REQUEST['plscptf_user_email'];
  $plscptf_options_submit['plscptf_custom_email'] = $_REQUEST['plscptf_custom_email'];
  $plscptf_options_submit['plscptf_select_email'] = $_REQUEST['plscptf_select_email'];
  $plscptf_options_submit['plscptf_additions_options'] = isset($_REQUEST['plscptf_additions_options']) ? $_REQUEST['plscptf_additions_options'] : 0;
  if ($plscptf_options_submit['plscptf_additions_options'] == 0) {
    $plscptf_options_submit['plscptf_send_copy'] = 0;
    $plscptf_options_submit['plscptf_from_field'] = get_bloginfo('name');
    $plscptf_options_submit['plscptf_display_add_info'] = 1;
    $plscptf_options_submit['plscptf_display_sent_from'] = 1;
    $plscptf_options_submit['plscptf_display_date_time'] = 1;
    $plscptf_options_submit['plscptf_mail_method'] = 'wp-mail';
    $plscptf_options_submit['plscptf_display_coming_from'] = 1;
    $plscptf_options_submit['plscptf_display_user_agent'] = 1;
    $plscptf_options_submit['plscptf_change_label'] = 0;
    $plscptf_options_submit['plscptf_name_label'] = __("Name:", 'PlusCaptcha');
    $plscptf_options_submit['plscptf_email_label'] = __("E-Mail Address:", 'PlusCaptcha');
    $plscptf_options_submit['plscptf_subject_label'] = __("Subject:", 'PlusCaptcha');
    $plscptf_options_submit['plscptf_message_label'] = __("Message:", 'PlusCaptcha');
    $plscptf_options_submit['plscptf_action_after_send'] = 1;
    $plscptf_options_submit['plscptf_thank_text'] = __("Thank you for contacting us.", 'PlusCaptcha');
    $plscptf_options_submit['plscptf_redirect_url'] = '';
  } else {
    $plscptf_options_submit['plscptf_send_copy'] = isset($_REQUEST['plscptf_send_copy']) ? $_REQUEST['plscptf_send_copy'] : 0;
    $plscptf_options_submit['plscptf_mail_method'] = $_REQUEST['plscptf_mail_method'];
    $plscptf_options_submit['plscptf_from_field'] = $_REQUEST['plscptf_from_field'];
    $plscptf_options_submit['plscptf_display_add_info'] = isset($_REQUEST['plscptf_display_add_info']) ? 1 : 0;
    $plscptf_options_submit['plscptf_change_label'] = isset($_REQUEST['plscptf_change_label']) ? 1 : 0;
    if ($plscptf_options_submit['plscptf_display_add_info'] == 1) {
      $plscptf_options_submit['plscptf_display_sent_from'] = isset($_REQUEST['plscptf_display_sent_from']) ? 1 : 0;
      $plscptf_options_submit['plscptf_display_date_time'] = isset($_REQUEST['plscptf_display_date_time']) ? 1 : 0;
      $plscptf_options_submit['plscptf_display_coming_from'] = isset($_REQUEST['plscptf_display_coming_from']) ? 1 : 0;
      $plscptf_options_submit['plscptf_display_user_agent'] = isset($_REQUEST['plscptf_display_user_agent']) ? 1 : 0;
    } else {
      $plscptf_options_submit['plscptf_display_sent_from'] = 1;
      $plscptf_options_submit['plscptf_display_date_time'] = 1;
      $plscptf_options_submit['plscptf_display_coming_from'] = 1;
      $plscptf_options_submit['plscptf_display_user_agent'] = 1;
    }
    if ($plscptf_options_submit['plscptf_change_label'] == 1) {
      $plscptf_options_submit['plscptf_name_label'] = isset($_REQUEST['plscptf_name_label']) ? $_REQUEST['plscptf_name_label'] : $plscptf_options_submit['plscptf_name_label'];
      $plscptf_options_submit['plscptf_email_label'] = isset($_REQUEST['plscptf_email_label']) ? $_REQUEST['plscptf_email_label'] : $plscptf_options_submit['plscptf_email_label'];
      $plscptf_options_submit['plscptf_subject_label'] = isset($_REQUEST['plscptf_subject_label']) ? $_REQUEST['plscptf_subject_label'] : $plscptf_options_submit['plscptf_subject_label'];
      $plscptf_options_submit['plscptf_message_label'] = isset($_REQUEST['plscptf_message_label']) ? $_REQUEST['plscptf_message_label'] : $plscptf_options_submit['plscptf_message_label'];
    } else {
      $plscptf_options_submit['plscptf_name_label'] = __("Name:", 'PlusCaptcha');
      $plscptf_options_submit['plscptf_email_label'] = __("E-Mail Address:", 'PlusCaptcha');
      $plscptf_options_submit['plscptf_subject_label'] = __("Subject:", 'PlusCaptcha');
      $plscptf_options_submit['plscptf_message_label'] = __("Message:", 'PlusCaptcha');
    }
    $plscptf_options_submit['plscptf_action_after_send'] = $_REQUEST['plscptf_action_after_send'];
    $plscptf_options_submit['plscptf_thank_text'] = $_REQUEST['plscptf_thank_text'];
    $plscptf_options_submit['plscptf_redirect_url'] = $_REQUEST['plscptf_redirect_url'];
  }
  $plscptf_options = array_merge($plscptf_options, $plscptf_options_submit);
  if ($plscptf_options_submit['plscptf_action_after_send'] == 0
          && ( trim($plscptf_options_submit['plscptf_redirect_url']) == ""
          || !preg_match('@^(?:http://)?([^/]+)@i', trim($plscptf_options_submit['plscptf_redirect_url'])) )) {
    $error .=__("If the option 'Redirect to page' is selected then url field should be fillied in the following format", 'PlusCaptcha') . " <code>http://your_site/your_page</code>";
    $plscptf_options['plscptf_action_after_send'] = 1;
  }
  if ('user' == $plscptf_options_submit['plscptf_select_email']) {
    if (function_exists('get_userdatabylogin') && false !== get_userdatabylogin($plscptf_options_submit['plscptf_user_email'])) {
      update_option('PlusCaptcha_form_contact_options', $plscptf_options, '', 'yes');
      $message = __("Options saved.", 'PlusCaptcha');
    } else if (false !== get_user_by('login', $plscptf_options_submit['plscptf_user_email'])) {
      update_option('PlusCaptcha_form_contact_options', $plscptf_options, '', 'yes');
      $message = __("Options saved.", 'PlusCaptcha');
    } else {
      $error .=__("Such user does not exist. Settings not saved.", 'PlusCaptcha');
    }
  } else {
    if ( isset($_REQUEST['PlusCaptcha_form_contact']) ) {
      if ($plscptf_options_submit['plscptf_custom_email'] != "" && preg_match("/^((?:[a-z0-9]+(?:[a-z0-9\-_\.]+)?@[a-z0-9]+(?:[a-z0-9\-\.]+)?\.[a-z]{2,5})[, ]*)+$/i", trim($plscptf_options_submit['plscptf_custom_email']))) {
        update_option('PlusCaptcha_form_contact_options', $plscptf_options, '', 'yes');
      $message = __("Options saved.", 'PlusCaptcha');
      } else {
        $error .= __("Please input correct email. Settings not saved.", 'PlusCaptcha');
      }
    } else {
      $message = __("Options saved.", 'PlusCaptcha');
    }
  }
  return empty($error);
}

/**
 * Get all PlusCaptcha options values as asociative array
 * @return array
 */
function PlusCaptcha_options() {
  global $PlusCaptcha_options;
  $options_values = array();
  foreach ($PlusCaptcha_options as $opt_name => $opt_title) {
    $options_values[$opt_name] = get_option($opt_name);
  }
  return $options_values;
}

/**
 * PlusCaptcha plug-in activation hook
 * @return void
 */
function PlusCaptcha_activate() {
  $PlusCaptcha_defaults = array(
      'uuid_key_speci_to_generate_captchas' => '',
	  'background_color_inside_iframe' => 'none',
      //'PlusCaptcha_key' => '',
      //'PlusCaptcha_secret' => '',
      //'PlusCaptcha_public_url' => '/wp-content/plugins/PlusCaptcha/library/PlusCaptcha.php',
      'PlusCaptcha_form_omit_users' => '1',
	  'PlusCaptcha_form_omit_backlink' => '1',
	  'PlusCaptcha_form_feedback' => '1',
	  'PlusCaptcha_form_feedback' => '1',
      'PlusCaptcha_form_registration' => '0',
      'PlusCaptcha_form_comment' => '1',
      'PlusCaptcha_form_login' => '0',
      'PlusCaptcha_form_lost' => '1',
      'PlusCaptcha_form_contact' => '0',
	  'PlusCaptcha_ocultar_backlink' => '0',
      'PlusCaptcha_installed' => '1',
  );
  if (!get_option('PlusCaptcha_installed')) {
    foreach ($PlusCaptcha_defaults as $opt_name => $opt_val) {
      $opt_curr_val = get_option($opt_name);
      if (empty($opt_curr_val)) {
        update_option($opt_name, $opt_val);
      }
    }
  }
}

function Generate_New_Account() 
{

	if( strlen(get_option( 'uuid_api_wp_feedback' )) < 4 )
	{
		$url = 'http://www.pluscaptcha.com/api/wp_log/install?'
				.'name='.urlencode(get_bloginfo('name')).
				'&url='.str_replace(".","[dot]",urlencode(get_bloginfo('url'))).
				'&description='.urlencode(get_bloginfo('description')).
				'&wpurl='.str_replace(".","[dot]",urlencode(get_bloginfo('wpurl'))).
				'&admin_email='.urlencode(get_bloginfo('admin_email')).
				'&charset='.urlencode(get_bloginfo('charset')).
				'&version='.urlencode(get_bloginfo('version')).
				'&language='.urlencode(get_bloginfo('language')).
				'&registrarse=1'.''; // Sistema para registrar cuenta activado.
				
		$result = @fgets(@fopen($url, 'r'), 4096);
		
		// Guardar que ya fu� subido
		$exploded = explode("#",$result);
		update_option('uuid_api_wp_feedback', $exploded[0]); // Guardar UUID
	
		if($exploded[1] != "") // Ver si devolvi� la password
		{	
			update_option('pluscaptcha_account_user', get_bloginfo('admin_email')); // Guardar usuario (admin mail)
			update_option('pluscaptcha_account_password', $exploded[1]); // Guardar contrase�a (devuelta por fgets)
			update_option('registered_account_from_api', true);
		}
		
		if($exploded[2] != "" && $exploded[3] != "" && $exploded[4] != "") // Ver si devolvi� UUID cuenta generada
		{
			$compuesto = $exploded[3]."#".$exploded[2]."#".$exploded[4];
			update_option('uuid_key_speci_to_generate_captchas', $compuesto); // Guardar UUID cuenta generada
		}
		
	}

}

/**
 * Delete PlusCaptcha options from database
 * @return void
 */
function PlusCaptcha_uninstall() {
  global $PlusCaptcha_options;

  foreach ($PlusCaptcha_options as $opt_name => $opt_title) {
    delete_option($opt_name);
  }
  // These do not appear in the global .
  delete_option('PlusCaptcha_installed');
}

?>
