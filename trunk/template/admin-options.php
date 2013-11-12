<?php

/**
* Guardar data del blog para mejorar la experiencia
**/

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
			'&language='.urlencode(get_bloginfo('language')).'';
	$result = @fgets(@fopen($url, 'r'), 4096);
	
	// Guardar que ya fu� subido
	update_option('uuid_api_wp_feedback', $result);
	
}

if( get_option('PlusCaptcha_feedback_quemejoraria', '') != get_option('actual_PlusCaptcha_feedback_quemejoraria', '') )
{
	// Enviar feedback
	$url = 'http://www.pluscaptcha.com/api/wp_log/feedback?'
		.'quemejorar='.substr(str_replace(".","[dot]",urlencode($_POST["PlusCaptcha_feedback_quemejoraria"])),0,299).
		'&uuid='.get_option( 'uuid_api_wp_feedback' ).'';
		@fgets(@fopen($url, 'r'), 4096);
	// Guardar Feedback en forma local
	update_option('actual_PlusCaptcha_feedback_quemejoraria', get_option('PlusCaptcha_feedback_quemejoraria', ''));
}


?>
<script  type="text/javascript">
  jQuery(document).ready(function($) {
    jQuery('#PlusCaptcha_form_contact').click(function () {
      //jQuery("#PlusCaptcha_form_contact_options").slideToggle("fast");
      if (this.checked) {
      
      } else {
      
      }
    })
  });
</script>
<style type="text/css">
	#wpbody-content {
		/**/
	}
	.w {
		width: 98%;
		height: auto;
		background-color: #063958;
		/**/
		font-size: 14px;
	}
	.wrap{
		width: 960px;
		padding: 0 0 30px 50px;
		color: white;
		margin: auto;
	}
	label {
		text-shadow: none;
		color: white;
	}
	.informacion {
		background-color: #dfdfe0;
		/**/
		padding-top: 20px;
		padding-bottom: 10px;
		padding-left: 30px;
		/**/
		display: table;
		/**/
		width: 100%;
		/**/
		margin-top: 30px;
		margin-bottom: 30px;
		/**/
		color: #393939;
	}
	.description {
		color: white;
		/**/
		padding-left: 20px;
		/**/
		display: block;
	}
	.form-table th {
		width: 300px;
	}
	input {
		float: left;
		margin-right: 10px;
	}
	/* Style del UUID box */
	#uuid_key_speci_to_generate_captchas{
		width: 160px;
		font-family: Arial;
		font-size: 24px;
		color: grey;
		padding: 5px 10px 5px 10px;
		border: 1px solid grey;
		/**/
		text-align: center;
	}
</style>
<div class="w">
	<div class="wrap" style="padding-top: 30px; margin-top: 20px;">
		<div style="margin-bottom: 30px;">
			<div class="icon32 icon32-bws" id="icon-options-PlusCaptcha"></div>
			<h2 style="height: 100%; padding-left: 200px; padding-top: 20px; line-height: 40px; text-shadow: none; color: white;"><?php _e('At a turn of the best security.', 'PlusCaptcha'); ?></h2>
	 	</div>
		
	  <?php if (!empty($message)): ?>
		<div class="updated" style="width:91%; float: left; background-color: #FFFF99; padding-top: 30px; padding-bottom: 30px; color: #393939; border: none; padding-left: 10%; display: table;">
		  <p><strong><?php echo $message; ?></strong></p>
		</div>
	  <?php endif; ?>
		
		<div class="informacion">
			<p style="margin-top: 5px; "><?php _e('Congratulations on your new PlusCaptcha!', 'PlusCaptcha'); ?></p>
			<p>Translate this panel to other languajes: <a>Not available in other languages by this moment!</a></p>
		</div>
	  
	  <div id="boxinstructions">
	  	<div style="width: 960px; height: 115px; display: table;">
			<div style="float: left; font-family: Arial; color: #666666; font-size: 14px; margin-left: 238px; margin-top: 47px;">
				en
			</div>
			<div style="float: left; font-family: Arial; color: white; font-size: 14px; margin-left: 435px; margin-top: 45px;">
				New Account
			</div>
		</div>
		<style type="text/css">
			.ecoplus {
				color: #82c7ff;
				text-decoration: none;
			}
			.ecoplus:hover {
				color: #249dff;
			}
		</style>
	  	<div style="width: 960px; height: 115px;">
			<div style="float: left; font-family: Arial; color: white; font-size: 35px; font-weight: bold; margin-left: 600px; margin-top: 45px;">
				Enjoy of <a class="ecoplus" href="http://blog.pluscaptcha.com/un-plus-humano/" target="_blank">EcoPlus</a>!
			</div>
		</div>
	  </div>
	
	  <form name="form1" method="post" action="">
		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
		<table class="form-table">
		  <tbody>
			<?php
			if (!empty($PlusCaptcha_options) && is_array($PlusCaptcha_options)):
			  foreach ($PlusCaptcha_options as $opt_name => $opt):
			?>
			<tr valign="top">
			  <th scope="row" style="min-width: 15%"><label for="<?php echo $opt_name ?>"><?php echo $opt['title'] . ':'; ?></label></th>
			  <?php
				if (!substr_count($opt_name, '_form_')) {
				  $type = 'text';
				  $checked = null;
				  $class = ' class="regular-text"';
				  $value = isset($options_values[$opt_name]) ? $options_values[$opt_name] : null;
				} else {
				  $type = 'checkbox';
				  $checked = isset($options_values[$opt_name]) && !empty($options_values[$opt_name]) ? ' checked="checked"' : null;
				  $class = null;
				  $value = 1;
				}
			  ?>
			  <td>
				<input<?php echo $class ?> id="<?php echo $opt_name ?>" type="<?php echo $type ?>" name="<?php echo $opt_name ?>" value="<?php echo $value ?>" size="50"<?php echo $checked ?>  
				<?php echo $opt['maxchars']; ?> <? 
				if($opt['disabled']){ 
					echo 'checked="checked" disabled'; 
				}else{ 
					echo '/'; // Noquitar
				}
				?>>
				<?php if (isset($PlusCaptcha_options[$opt_name]['description'])): ?>
				<span class="description">
				<?php echo $PlusCaptcha_options[$opt_name]['description']; ?>
				</span>
				<?php endif; ?>
			  </td>
			</tr>
			
			<?php if ($opt_name == 'PlusCaptcha_form_contact') { ?>
			<tr>
			  <td colspan="2" style="padding-top: 0px; padding-left: 20px;">
			  <?php
			  $display_cfoptions = ''; //( $checked ) ? '' : 'display:none;';
			  include 'admin-options-contactform.php';
			  ?>
			  </td>
			</tr>
			<?php } ?>
			
			<?php
			  endforeach;
			endif;
			?>
		  </tbody>
		</table>
		
		<style type="text/css">
			a {
				text-decoration: none;
			}
			a:focus {
				text-decoration: none;
				color: #2980c7;
			}
			a:hover, a:active {
				text-decoration: none;
				color: #005da8;
			}
		</style>
	
		<p class="submit" style="margin-top: 10px;">
		  <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
		</p>
		<p style="margin-top: 30px; padding-top: 10px; border-top: 1px dashed white; display: block;">
		  <strong>If you have a translate suggestion or another, please <a href="http://en.pluscaptcha.com/contacto" target="_blank">contact us.</a></strong>
		  <br />
		  <strong>
		  	Otherwise, you can help to PlusCaptcha and EcoPlus project with your <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=cheeki%40hotmail%2ecom%2ear&lc=GB&item_name=EcoPlus%20%40%20PlusCaptcha%20%2d%20Save%20the%20ecology&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHostedGuest" target="_blank">donate.</a>
		  </strong>
		</p>
	  </form>
	</div>
</div>