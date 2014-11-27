<?php

# Clean
# Limpiar data
function tags($tags){  
	$tags = strip_tags($tags);  
	$tags = stripslashes($tags);  
	$tags = htmlentities($tags);
	$tags = addslashes($tags);
	return trim($tags);  
} 

tags($_REQUEST);
tags($_POST);
tags($_GET);

// Display PlusCaptcha Contact Form in front end - page or post
if (!function_exists('plscptf_display_form')) {

  function plscptf_display_form() {
    global $error_message, $plscptf_options, $result;
    $plscptf_options = get_option('PlusCaptcha_form_contact_options');
    $content = "";

    $page_url = ( isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" ? "https://" : "http://" ) . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    // If contact form submited
    $name = isset($_REQUEST['plscptf_contact_name']) ? $_REQUEST['plscptf_contact_name'] : "";
    $email = isset($_REQUEST['plscptf_contact_email']) ? $_REQUEST['plscptf_contact_email'] : "";
    $subject = isset($_REQUEST['plscptf_contact_subject']) ? $_REQUEST['plscptf_contact_subject'] : "";
    $message = isset($_REQUEST['plscptf_contact_message']) ? $_REQUEST['plscptf_contact_message'] : "";
    $send_copy = isset($_REQUEST['plscptf_contact_send_copy']) ? $_REQUEST['plscptf_contact_send_copy'] : "";
    // If it is good
    if (true === $result) {
      $_SESSION['plscptf_send_mail'] = true;
      if ($plscptf_options['plscptf_action_after_send'] == 1)
        $content .= $plscptf_options['plscptf_thank_text'];
      else
        $content .= "<script type='text/javascript'>window.location.href = '" . $plscptf_options['plscptf_redirect_url'] . "';</script>";
    }
    else if (false === $result) {
      // If email not be delivered
      $error_message['error_form'] = __("Sorry, your e-mail could not be delivered.", 'PlusCaptcha');
    } else {
      $_SESSION['plscptf_send_mail'] = false;
      // Output form
      $content .= '<form method="post" id="plscptf_contact_form" action="' . $page_url . '" enctype="multipart/form-data">';
      if (isset($error_message['error_form'])) {
        $content .= '<div class="error-form">' . $error_message['error_form'] . '</div>';
      }
      $content .= '<div class="input-label">
					<label for="plscptf_contact_name">' . $plscptf_options['plscptf_name_label'] . '<span class="required"> *</span></label>
				</div>';
      if (isset($error_message['error_name'])) {
        $content .= '<div class="error-form">' . $error_message['error_name'] . '</div>';
      }
      $content .= '<div class="input">
					<input class="text" type="text" size="40" value="' . $name . '" name="plscptf_contact_name" id="plscptf_contact_name" />
				</div>

				<div class="input-label">
					<label for="plscptf_contact_email">' . $plscptf_options['plscptf_email_label'] . '<span class="required"> *</span></label>
				</div>';
      if (isset($error_message['error_email'])) {
        $content .= '<div class="error-form">' . $error_message['error_email'] . '</div>';
      }
      $content .= '<div class="input">
					<input class="text" type="text" size="40" value="' . $email . '" name="plscptf_contact_email" id="plscptf_contact_email" />
				</div>

				<div class="input-label">
					<label for="plscptf_contact_subject">' . $plscptf_options['plscptf_subject_label'] . '<span class="required"> *</span></label>
				</div>';
      if (isset($error_message['error_subject'])) {
        $content .= '<div class="error-form">' . $error_message['error_subject'] . '</div>';
      }
      $content .= '<div class="input">
					<input class="text" type="text" size="40" value="' . $subject . '" name="plscptf_contact_subject" id="plscptf_contact_subject" />
				</div>

				<div class="input-label">
					<label for="plscptf_contact_message">' . $plscptf_options['plscptf_message_label'] . '<span class="required"> *</span></label>
				</div>';
      if (isset($error_message['error_message'])) {
        $content .= '<div class="error-form">' . $error_message['error_message'] . '</div>';
      }
      $content .= '<div class="input">
					<textarea rows="5" cols="30" name="plscptf_contact_message" id="plscptf_contact_message">' . $message . '</textarea>
				</div>';
      if (isset($error_message['error_captcha'])) {
        $content .= '<div class="error-form">' . $error_message['error_captcha'] . '</div>';
      }

      $content .= PlusCaptcha_shortcode();

      if ($plscptf_options['plscptf_send_copy'] == 1) {
        $content .= '<div style="text-align: left;">
						<input type="checkbox" value="1" name="plscptf_contact_send_copy" id="plscptf_contact_send_copy" style="text-align: left; margin: 0;" ' . ( $send_copy == '1' ? " checked=\"checked\" " : "" ) . ' />
						<label for="plscptf_contact_send_copy">' . __("Send me a copy", 'PlusCaptcha') . '</label>
					</div>';
      }

      $content .= '<div style="text-align: left; padding-top: 8px;">
					<input type="hidden" value="send" name="plscptf_contact_action"><input type="hidden" value="Version: 3.13" />
					<input type="submit" value="' . __("Submit", 'PlusCaptcha') . '" style="cursor: pointer; margin: 0pt; text-align: center;margin-bottom:10px;" /> 
				</div>
				</form>';
    }
    return $content;
  }

}
//**********************************************************************************************************************
if (!function_exists('plscptf_check_and_send')) {

  function plscptf_check_and_send() {
    //die ('plscptf_check_and_send'); 
    global $result;
    $plscptf_options = get_option('PlusCaptcha_form_contact_options');
    if (isset($_REQUEST['plscptf_contact_action'])) {
      // Check all input data
      $result = plscptf_check_form();
    }
    if (true === $result) { // OK
      $_SESSION['plscptf_send_mail'] = true;
      if ($plscptf_options['plscptf_action_after_send'] == 0) {
        wp_redirect($plscptf_options['plscptf_redirect_url']);
        exit;
      }
    }
  }

}
// Check PlusCaptcha Contact Form input data
if (!function_exists('plscptf_check_form')) {

  function plscptf_check_form() {
    global $error_message;
    global $plscptf_options;
    if (empty($plscptf_options))
      $plscptf_options = get_option('PlusCaptcha_form_contact_options');
    $result = "";
    // Error messages array
    $error_message = array();
    $error_message['error_name'] = __("Please input your name.", 'PlusCaptcha');
    $error_message['error_email'] = __("Please input your e-mail address.", 'PlusCaptcha');
    $error_message['error_subject'] = __("Subject required.", 'PlusCaptcha');
    $error_message['error_message'] = __("Message text required.", 'PlusCaptcha');
    $error_message['error_form'] = __("Please correct your input data below and try again.", 'PlusCaptcha');
    // Check information
    if ("" != $_REQUEST['plscptf_contact_name'])
      unset($error_message['error_name']);
    if ("" != $_REQUEST['plscptf_contact_email'] && preg_match("/^(?:[a-z0-9]+(?:[a-z0-9\-_\.]+)?@[a-z0-9]+(?:[a-z0-9\-\.]+)?\.[a-z]{2,5})$/i", trim($_REQUEST['plscptf_contact_email'])))
      unset($error_message['error_email']);
    if ("" != $_REQUEST['plscptf_contact_subject'])
      unset($error_message['error_subject']);
    if ("" != $_REQUEST['plscptf_contact_message'])
      unset($error_message['error_message']);
    
    PlusCaptcha_validate_contact_form($error_message);

    if (1 == count($error_message)) { // OK
      unset($error_message['error_form']);
      $result = plscptf_send_mail();
    }

    return $result;
  }

}

// Send mail function
if (!function_exists('plscptf_send_mail')) {

  function plscptf_send_mail() {
    global $plscptf_options, $path_of_uploaded_file;
    $to = "";
    if (isset($_SESSION['plscptf_send_mail']) && $_SESSION['plscptf_send_mail'] == true)
      return true;
    if ($plscptf_options['plscptf_select_email'] == 'user') {
      if (function_exists('get_userdatabylogin') && false !== $user = get_userdatabylogin($plscptf_options['plscptf_user_email'])) {
        $to = $user->user_email;
      } else if (false !== $user = get_user_by('login', $plscptf_options_submit['plscptf_user_email']))
        $to = $user->user_email;
    }
    else {
      $to = $plscptf_options['plscptf_custom_email'];
    }
    if ("" == $to) {
      // If email options are not certain choose admin email
      $to = get_option("admin_email");
    }
    if ("" != $to) {
      // subject
      $subject = $_REQUEST['plscptf_contact_subject'];
      $user_info_string = '';
      $userdomain = '';
      $form_action_url = '';
      $attachments = array();
      $headers = "";

      if (getenv('HTTPS') == 'on') {
        $form_action_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      } else {
        $form_action_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      }

      if ($plscptf_options['plscptf_display_add_info'] == 1) {
        $userdomain = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        if ($plscptf_options['plscptf_display_add_info'] == 1 ||
                $plscptf_options['plscptf_display_sent_from'] == 1 ||
                $plscptf_options['plscptf_display_coming_from'] == 1 ||
                $plscptf_options['plscptf_display_user_agent'] == 1) {
          $user_info_string .= '<tr>
							<td><br /></td><td><br /></td>
						</tr>';
        }
        if ($plscptf_options['plscptf_display_sent_from'] == 1) {
          $user_info_string .= '<tr>
							<td>' . __('Sent from (ip address)', 'PlusCaptcha') . ':</td><td>' . $_SERVER['REMOTE_ADDR'] . " ( " . $userdomain . " )" . '</td>
						</tr>';
        }
        if ($plscptf_options['plscptf_display_date_time'] == 1) {
          $user_info_string .= '<tr>
							<td>' . __('Date/Time', 'PlusCaptcha') . ':</td><td>' . date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime(current_time('mysql'))) . '</td>
						</tr>';
        }
        if ($plscptf_options['plscptf_display_coming_from'] == 1) {
          $user_info_string .= '<tr>
							<td>' . __('Coming from (referer)', 'PlusCaptcha') . ':</td><td>' . $form_action_url . '</td>
						</tr>';
        }
        if ($plscptf_options['plscptf_display_user_agent'] == 1) {
          $user_info_string .= '<tr>
							<td>' . __('Using (user agent)', 'PlusCaptcha') . ':</td><td>' . plscptf_clean_input($_SERVER['HTTP_USER_AGENT']) . '</td>
						</tr>';
        }
      }
      // message
      $message = '
			<html>
			<head>
				<title>' . __("Contact from", 'PlusCaptcha') . get_bloginfo('name') . '</title>
			</head>
			<body>
				<table>
					<tr>
						<td width="160">' . __("Name", 'PlusCaptcha') . '</td><td>' . $_REQUEST['plscptf_contact_name'] . '</td>
					</tr>
					<tr>
						<td>' . __("Email", 'PlusCaptcha') . '</td><td>' . $_REQUEST['plscptf_contact_email'] . '</td>
					</tr>
					<tr>
						<td>' . __("Subject", 'PlusCaptcha') . '</td><td>' . $_REQUEST['plscptf_contact_subject'] . '</td>
					</tr>
					<tr>
						<td>' . __("Message", 'PlusCaptcha') . '</td><td>' . $_REQUEST['plscptf_contact_message'] . '</td>
					</tr>
					<tr>
						<td>' . __("Site", 'PlusCaptcha') . '</td><td>' . get_bloginfo("url") . '</td>
					</tr>
					<tr>
						<td><br /></td><td><br /></td>
					</tr>
					' . $user_info_string . '
				</table>
			</body>
			</html>
			';
      if ($plscptf_options['plscptf_mail_method'] == 'wp-mail') {
        // To send HTML mail, the Content-type header must be set
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        // Additional headers
        $headers .= 'From: ' . $_REQUEST['plscptf_contact_email'] . "\r\n";
        if (isset($_REQUEST['plscptf_contact_send_copy']) && $_REQUEST['plscptf_contact_send_copy'] == 1)
          wp_mail($_REQUEST['plscptf_contact_email'], stripslashes($subject), stripslashes($message), $headers, $attachments);

        // Mail it
        return wp_mail($to, stripslashes($subject), stripslashes($message), $headers, $attachments);
      }
      else {
        // HTML e-mail, we should set Content-type header
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        // Additional headers
        $headers .= 'From: ' . $_REQUEST['plscptf_contact_email'] . "\r\n";
        if (isset($_REQUEST['plscptf_contact_send_copy']) && $_REQUEST['plscptf_contact_send_copy'] == 1)
          @mail($_REQUEST['plscptf_contact_email'], stripslashes($subject), stripslashes($message), $headers);

        return @mail($to, stripslashes($subject), stripslashes($message), $headers);
      }
    }
    return false;
  }

}

function plscptf_clean_input($string, $preserve_space = 0) {
  if (is_string($string)) {
    if ($preserve_space) {
      return plscptf_sanitize_string(strip_tags(stripslashes($string)), $preserve_space);
    }
    return trim(plscptf_sanitize_string(strip_tags(stripslashes($string))));
  } else if (is_array($string)) {
    reset($string);
    while (list($key, $value ) = each($string)) {
      $string[$key] = plscptf_clean_input($value, $preserve_space);
    }
    return $string;
  } else {
    return $string;
  }
}

// protect and validate form vars

function plscptf_sanitize_string($string, $preserve_space = 0) {
  if (!$preserve_space)
    $string = preg_replace("/ +/", ' ', trim($string));

  return preg_replace("/[<>]/", '_', $string);
}

function plscptf_email_name_filter($data) {
  global $plscptf_options;
  if (isset($plscptf_options['plscptf_from_field']) && trim($plscptf_options['plscptf_from_field']) != "")
    return stripslashes($plscptf_options['plscptf_from_field']);
  else
    return $data;
}

function PlusCaptcha_validate_contact_form(&$errors) {
	global $PlusCaptcha_instance;
	//$scValues = PlusCaptcha_get_values();
	if (get_result(simpleSessionGet("contacto_shortcode", ""),true) != true ) {
   		$errors['error_captcha'] = '<strong>'.__( 'ERROR', 'PlusCaptcha' ) . '</strong>: ' . __(PlusCaptcha_ERROR_MESSAGE_BR, 'PlusCaptcha' );
	}else{
		//$_SESSION["passport"] = false;
	}
	return $errors;
}

add_shortcode('im_helping_with_ecoplus', 'plscptf_display_form');
add_action('init', 'plscptf_check_and_send');
add_filter('wp_mail_from_name', 'plscptf_email_name_filter', 10, 1);

?>