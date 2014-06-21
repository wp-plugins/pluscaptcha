<?php

$status = @fgets(@fopen("http://www.pluscaptcha.com/status/", 'r'), 4096);

/**
* Guardar data del blog para mejorar la experiencia
**/

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

<?php
if( $message != "" && $status || strlen(get_option('uuid_key_speci_to_generate_captchas', '')) < 9 && $status ) 
{
?>
	<script  type="text/javascript">
	  jQuery(document).ready(function($) {
		$('#ShortData').hide('fast');
	  });
	</script>
<?php
}else{
?>
	<script  type="text/javascript">
	  jQuery(document).ready(function($) {
		$('#Advance').hide();
	  });
	</script>
<?php
}
?>

<div class="w">
	<div class="wrap" style="padding-top: 30px; margin-top: 20px;">
		
		<style type="text/css">
		.ShortData .Data {
			width: 100%;
			height: 431px;
			/**/
			overflow: display;
		}
		.ShortData .Data h1 {
			font-family: Arial;
			font-size: 44px;
			font-weight: bold;
			/**/
			width: 100%;
			text-align: center;
			/**/
			height: 50px;
			line-height: 50px;
			/**/
			color: white;
			/**/
			margin: 0px;
			margin-top: 10px; /*rewrite*/
		}
		.ShortData .Data h3 {
			font-family: Arial;
			font-size: 14px;
			font-weight: bold;
			/**/
			width: 100%;
			text-align: center;
			/**/
			height: 20px;
			line-height: 20px;
			/**/
			color: white;
			/**/
			margin: 0px;
		}
		.ShortData .BoxSites{
			width: 90%;
			margin-left: 5%;
			margin-right: 5%;
			/**/
			height: 200px;
			/**/
			margin-top: 40px;
			margin-bottom: 20px;
			/**/
			border-top: 1px solid white;
			border-bottom: 1px solid white;
			/**/
			overflow: display;
		}
		.ShortData .BoxSites a{
			color: #5AD1F3;
		
		}
		.ShortData .BoxSites a:hover{
			text-decoration: underline;
			color: #0f9ec7;
		}
		.300 {
			width: 300px; 
			margin: auto; 
		}
		.ShortData .BoxMore{
			width: 90%;
			margin-left: 5%;
			margin-right: 5%;
			/**/
			height: 110px;
			/**/
			margin-top: 20px;
			margin-bottom: 20px;
			/**/
		}
		.ShortData .BoxMore .BoxBtn{
			width: 50%;
			height: 110px;
			/**/
			float: left;
		}
		.ShortData .BoxMore .BoxBtn a{
			width: 80%;
			margin: 30px 10% 30px 10%;
			/**/
			height: 60px;
			line-height: 60px;
			/**/
			background: #009cff;
			/**/
			font-family: Arial;
			font-size: 18px;
			font-weight: bold;
			color: white;
			text-decoration: none;
			/**/
			text-align: center;
			/**/
			display: block;
			/**/
			cursor: pointer;
		}
		.ShortData .BoxMore .BoxBtn a:hover{
			background: #008ce5;
		}
		</style>
		
		<div id="ShortData" class="ShortData">
			<?php
			if($status) {
			?>
				<div class="LogoRun"></div>
			<?php }else{ ?>
				<div class="LogoPaused"></div>
			<?php } ?>
			<div class="Data">
				<h1><?php echo ($status) ? 'SERVICE IS RUNNING' : 'IN BRIEF MAINTENANCE'; ?></h1>
				<h3><?php echo ($status) ? 'CHECK YOUR COMMENT FORM' : 'PLEASE COME BACK IN 30 MINUTES'; ?></h3>
				<div class="BoxSites" style="text-align: center;">
					<h3 class="300" style="width: 370px; margin: auto; margin-top: -10px; background: #00609b; display: table;">WHO TRUST IN PLUSCAPTCHA TOO? (BY RANDOM)</h3>
					<?php
						echo @fgets(@fopen("http://www.pluscaptcha.com/api/wp_log/who_use_plugin.php", 'r'), 4096);					
					?>
				</div>
				<div class="BoxMore">
					<div class="BoxBtn">
						<?php if($status) { ?>
							<a onclick="jQuery(document).ready(function($) { 
								$('#ShortData').hide('fast'); 
								$('#Advance').show('slow');
							});">Advance Options</a>
						<?php }else{ ?>
							<a style="background-color:#999999; cursor: auto;">In Mantenience</a>
						<?php } ?>
					</div>
					<div class="BoxBtn" style="text-align: right;">
						<p>
							<strong>Information</strong> - info@pluscaptcha.com<br />
							<strong>Support</strong> - support@pluscaptcha.com<br />
							<strong>In case of Emergency (24/7)</strong><br />
							emergency@pluscaptcha.com
						</p>
					</div>
				</div>
			</div>
		</div>
	
		<div id="Advance">
		
			<!--
			<div style="margin-bottom: 30px;">
				<div class="icon32 icon32-bws" id="icon-options-PlusCaptcha"></div>
				<h2 style="height: 100%; padding-left: 200px; padding-top: 20px; line-height: 40px; text-shadow: none; color: white;"><?php _e('At a turn of the best security.', 'PlusCaptcha'); ?></h2>
			</div>
			-->
			
		  <?php if (!empty($message)): ?>
			<div class="updated" style="width:91%; float: left; background-color: #FFFF99; padding-top: 30px; padding-bottom: 30px; color: #393939; border: none; padding-left: 10%; display: table;">
			  <p><strong><?php echo $message; ?></strong></p>
			</div>
			<br />
		  <?php endif; ?>
			
			<div class="informacion">
				<p style="margin-top: 5px; "><?php _e('Congratulations on your new PlusCaptcha!', 'PlusCaptcha'); ?></p>
				<p>Translate this panel to other languajes: <a>Not available in other languages by this moment!</a></p>
			</div>
			
			
		<?php
		if(
			get_option( 'uuid_api_wp_feedback' ) != "" && 
			get_option( 'registered_account_from_api' ) == true && 
			get_option( 'pluscaptcha_account_user' ) != "" && 
			get_option( 'pluscaptcha_account_password' ) != ""
		)
		{
		?>
		  <!-- Box Personalice -->
		  <div id="personalizacion">
			<div id="right-content-pers">
				<span>
					Personalize your account logging to 
					<br />
					<a href="http://en.pluscaptcha.com/login" target="_blank"><strong style="border: 0px;">en.pluscaptcha.com/login</strong></a> with this mail:
					<br />
					<strong><?php echo get_option( 'pluscaptcha_account_user' ); ?></strong>
					<br />
					and this password:
					<br />
					<strong><?php echo get_option( 'pluscaptcha_account_password' ); ?></strong> 
				</span>
				<br />
				<span style="font-size: 12px; line-height: normal; margin-top: 10px; display: table;">
					(this account was generated automatically for you!,
					<br>
					when you finish, just update your ID of 9 Characters and it's ready!)
				</span>
			</div>
		  </div>	  
		  <!-- --->	 
		  
		  <?php }elseif( get_option( 'registered_account_from_api' ) != true && get_option('uuid_key_speci_to_generate_captchas', '') == "" ){ ?> 
		  
		  <!-- Box Instrucciones -->
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
		  <!-- --->
		  
		  <?php } ?>
		
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
			</p>
		  </form>
		  </div>
	</div>
</div>