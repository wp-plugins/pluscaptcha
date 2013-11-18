<!-- Additional options in admin PlusCaptcha Settings -->
<?php 
global $error, $wpdb; 
$userslogin = $wpdb->get_col("SELECT user_login FROM  $wpdb->users ", 0);
?>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('#plscptf_additions_options').change( function() {
			if(jQuery(this).is(':checked') )
				jQuery('.plscptf_additions_block').removeClass('plscptf_hidden');
			else
				jQuery('.plscptf_additions_block').addClass('plscptf_hidden');
		});
		jQuery('#plscptf_change_label').change( function() {
			if(jQuery(this).is(':checked') )
				jQuery('.plscptf_change_label_block').removeClass('plscptf_hidden');
			else
				jQuery('.plscptf_change_label_block').addClass('plscptf_hidden');
		});
		jQuery('#plscptf_display_add_info').change( function() {
			if(jQuery(this).is(':checked') )
				jQuery('.plscptf_display_add_info_block').removeClass('plscptf_hidden');
			else
				jQuery('.plscptf_display_add_info_block').addClass('plscptf_hidden');
		});
	});
</script>
<div class="wrap" style="padding-top: none;" id="PlusCaptcha_form_contact_options" style="<?php echo $display_cfoptions;?>">
  <div class="error" style="width:99%; float: left; <?php if (empty($error)) echo 'display:none'; ?>" ><p><strong><?php echo $error; ?></strong></p></div>
  <br>
  <table class="form-table">
    <tr valign="top">
      <th scope="row" style="width:195px; color: white; text-shadow: none;"><?php _e("Use email of wordpress user:", 'PlusCaptcha'); ?> </th>
      <td style="width:15px;">
        <input type="radio" id="plscptf_select_email_user" name="plscptf_select_email" value="user" <?php if ($plscptf_options['plscptf_select_email'] == 'user') echo "checked=\"checked\" "; ?>/>
      </td>
      <td>
        <select name="plscptf_user_email" style="width:130px;">
          <option disabled><?php _e("Select user name", 'PlusCaptcha'); ?></option>
          <?php while (list( $key, $value ) = each($userslogin)) { ?>
            <option value="<?php echo $value; ?>" 
              <?php if ($plscptf_options['plscptf_user_email'] == $value) echo "selected=\"selected\" "; ?>><?php echo $value; ?>
            </option>
          <?php } ?>
        </select>
        <span class="plscptf_info" style="font-family: sans-serif; font-size: 12px; font-style: italic; color: white; display: table; padding-left: 20px;"><?php _e("Set a name of user who will get messages from a contact form.", 'PlusCaptcha'); ?></span>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row" style="color: white; text-shadow: none;"><?php _e("Use this email:", 'PlusCaptcha'); ?> </th>
      <td>
        <input type="radio" id="plscptf_select_email_custom" name="plscptf_select_email" value="custom" <?php if ($plscptf_options['plscptf_select_email'] == 'custom') echo "checked=\"checked\" "; ?>/>
      </td>
      <td>
        <input type="text" name="plscptf_custom_email" value="<?php echo $plscptf_options['plscptf_custom_email']; ?>" onfocus="document.getElementById('plscptf_select_email_custom').checked = true;" />
        <span class="plscptf_info" style="font-family: sans-serif; font-size: 12px; font-style: italic; color: white; display: table; padding-left: 20px;"><?php _e("Set an email address which will be used for messages receiving.", 'PlusCaptcha'); ?></span>
      </td>
    </tr>
    <tr valign="top">
      <th colspan="3" scope="row" style="background-color: #DFDFE0; margin-top: 20px; display: block;"><input type="checkbox" id="plscptf_additions_options" name="plscptf_additions_options" style="margin-right: 30px;"  value="1" <?php if ($plscptf_options['plscptf_additions_options'] == '1') echo "checked=\"checked\" "; ?> /> <?php _e("Additional options", 'PlusCaptcha'); ?></th>
    </tr>
	
</table> 

<table style="background-color: white; width: 100%; margin-top: 10px; color: #666666;" class="plscptf_additions_block plscptf_hidden"> 

	

    <tr valign="top" class="plscptf_additions_block <?php if ($plscptf_options['plscptf_additions_options'] == '0') echo "plscptf_hidden"; ?>">
      <th scope="row"><?php _e("Display Send me a copy block", 'PlusCaptcha'); ?></th>
      <td colspan="2">
        <input type="checkbox" id="plscptf_send_copy" name="plscptf_send_copy" value="1" <?php if ($plscptf_options['plscptf_send_copy'] == '1') echo "checked=\"checked\" "; ?>/>
      </td>
    </tr>
    <tr class="plscptf_additions_block <?php if ($plscptf_options['plscptf_additions_options'] == '0') echo "plscptf_hidden"; ?>">
      <th rowspan="2"><?php _e('What use?', 'PlusCaptcha'); ?></th>
      <td>
        <input type='radio' name='plscptf_mail_method' value='wp-mail' <?php if ($plscptf_options['plscptf_mail_method'] == 'wp-mail') echo "checked=\"checked\" "; ?>/>
      </td>
      <td>
        <?php _e('Wp-mail', 'mail-send'); ?> 
        <span  class="plscptf_info">(<?php _e('To send mail you can use the wordpress wp_mail function', 'mail_send'); ?>)</span>
      </td>
    </tr>
    <tr class="plscptf_additions_block <?php if ($plscptf_options['plscptf_additions_options'] == '0') echo "plscptf_hidden"; ?>">
      <td>
        <input type='radio' name='plscptf_mail_method' value='mail' <?php if ($plscptf_options['plscptf_mail_method'] == 'mail') echo "checked=\"checked\" "; ?>/>
      </td>
      <td>
        <?php _e('Mail', 'mail-send'); ?> 
        <span  class="plscptf_info">(<?php _e('To send mail you can use the php mail function', 'mail_send'); ?>)</span>
      </td>
    </tr>
    <tr valign="top" class="plscptf_additions_block <?php if ($plscptf_options['plscptf_additions_options'] == '0') echo "plscptf_hidden"; ?>">
      <th scope="row"><?php _e("Change FROM fields of the contact form", 'PlusCaptcha'); ?></th>
      <td colspan="2">
        <input type="text" style="width:200px;" name="plscptf_from_field" value="<?php echo stripslashes($plscptf_options['plscptf_from_field']); ?>" /><br />
      </td>
    </tr>
    <tr valign="top" class="plscptf_additions_block <?php if ($plscptf_options['plscptf_additions_options'] == '0') echo "plscptf_hidden"; ?>">
      <th scope="row"><?php _e("Display additional info in email", 'PlusCaptcha'); ?></th>
      <td>
        <input type="checkbox" id="plscptf_display_add_info" name="plscptf_display_add_info" value="1" <?php if ($plscptf_options['plscptf_display_add_info'] == '1') echo "checked=\"checked\" "; ?>/>
      </td>
      <td class="plscptf_display_add_info_block <?php if ($plscptf_options['plscptf_display_add_info'] == '0') echo "plscptf_hidden"; ?>">
        <input type="checkbox" id="plscptf_display_sent_from" name="plscptf_display_sent_from" value="1" <?php if ($plscptf_options['plscptf_display_sent_from'] == '1') echo "checked=\"checked\" "; ?>/> <span style="margin-left: 10px;" class="plscptf_info"><?php _e("Sent from (ip address)", 'PlusCaptcha'); ?></span><br />
        <input type="checkbox" id="plscptf_display_date_time" name="plscptf_display_date_time" value="1" <?php if ($plscptf_options['plscptf_display_date_time'] == '1') echo "checked=\"checked\" "; ?>/> <span style="margin-left: 10px;" class="plscptf_info"><?php _e("Date/Time", 'PlusCaptcha'); ?></span><br />
        <input type="checkbox" id="plscptf_display_coming_from" name="plscptf_display_coming_from" value="1" <?php if ($plscptf_options['plscptf_display_coming_from'] == '1') echo "checked=\"checked\" "; ?>/> <span style="margin-left: 10px;" class="plscptf_info"><?php _e("Coming from (referer)", 'PlusCaptcha'); ?></span><br />
        <input type="checkbox" id="plscptf_display_user_agent" name="plscptf_display_user_agent" value="1" <?php if ($plscptf_options['plscptf_display_user_agent'] == '1') echo "checked=\"checked\" "; ?>/> <span style="margin-left: 10px;" class="plscptf_info"><?php _e("Using (user agent)", 'PlusCaptcha'); ?></span><br />
      </td>
    </tr>
    <tr valign="top" class="plscptf_additions_block <?php if ($plscptf_options['plscptf_additions_options'] == '0') echo "plscptf_hidden"; ?>">
      <th scope="row"><?php _e("Change label for fields of the contact form", 'PlusCaptcha'); ?></th>
      <td>
        <input type="checkbox" id="plscptf_change_label" name="plscptf_change_label" value="1" <?php if ($plscptf_options['plscptf_change_label'] == '1') echo "checked=\"checked\" "; ?>/>
      </td>
      <td class="plscptf_change_label_block <?php if ($plscptf_options['plscptf_change_label'] == '0') echo "plscptf_hidden"; ?>">
        <div style="width: 200px;">
			<input type="text" name="plscptf_name_label" value="<?php echo $plscptf_options['plscptf_name_label']; ?>" style="float: right;" /> <span class="plscptf_info"><?php _e("Name:", 'PlusCaptcha'); ?></span>
		</div>
		<br />
        <div style="width: 200px;">
			<input type="text" name="plscptf_email_label" value="<?php echo $plscptf_options['plscptf_email_label']; ?>" style="float: right;" /> <span class="plscptf_info"><?php _e("E-Mail Address:", 'PlusCaptcha'); ?></span>
		</div>
		<br />
        <div style="width: 200px;">
		<input type="text" name="plscptf_subject_label" value="<?php echo $plscptf_options['plscptf_subject_label']; ?>" style="float: right;" /> <span class="plscptf_info"><?php _e("Subject:", 'PlusCaptcha'); ?></span>
		</div>
		<br />
        <div style="width: 200px;">
		<input type="text" name="plscptf_message_label" value="<?php echo $plscptf_options['plscptf_message_label']; ?>" style="float: right;" /> <span class="plscptf_info"><?php _e("Message:", 'PlusCaptcha'); ?></span>
		</div>
      </td>
    </tr>
    <tr valign="top" class="plscptf_additions_block <?php if ($plscptf_options['plscptf_additions_options'] == '0') echo "plscptf_hidden"; ?>">
      <th scope="row"><?php _e("Action after the send mail", 'PlusCaptcha'); ?></th>
      <td colspan="2" class="plscptf_action_after_send_block">
	  
	  	<div>
        <input style="float: left;" type="radio" id="plscptf_action_after_send" name="plscptf_action_after_send" value="1" <?php if ($plscptf_options['plscptf_action_after_send'] == '1') echo "checked=\"checked\" "; ?>/> <span class="plscptf_info"><?php _e("Display text", 'PlusCaptcha'); ?></span>
        <input style="margin-left: 20px; margin-right: 10px;" type="text" name="plscptf_thank_text" value="<?php echo $plscptf_options['plscptf_thank_text']; ?>" /> <span class="plscptf_info"><?php _e("Text", 'PlusCaptcha'); ?></span>
		</div>
		
		<br />
		
		<div>
        <input style="float: left;"  type="radio" id="plscptf_action_after_send" name="plscptf_action_after_send" value="0" <?php if ($plscptf_options['plscptf_action_after_send'] == '0') echo "checked=\"checked\" "; ?>/> <span class="plscptf_info"><?php _e("Redirect to page", 'PlusCaptcha'); ?></span>
        <input style="margin-left: 20px; margin-right: 10px;" type="text" name="plscptf_redirect_url" value="<?php echo $plscptf_options['plscptf_redirect_url']; ?>" /> <span class="plscptf_info"><?php _e("Url", 'PlusCaptcha'); ?></span><br />
		</div>
		
      </td>
	  
	  
	  
  </table>    
</div>
