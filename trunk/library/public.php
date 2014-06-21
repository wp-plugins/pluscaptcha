<?php

/**
 * Get PlusCaptcha values from POST data
 * @return array
 */
function PlusCaptcha_get_values() {
	return array(
		'sckey'		=> ( isset( $_POST[ 'sckey' ] ) ? $_POST[ 'sckey' ] : '' ),
		'scvalue'	=> ( isset( $_POST[ 'scvalue' ] ) ? $_POST[ 'scvalue' ] : '' )
	);
}

function get_result($entrada,$shortcode=false) {

	$limpiado = strip_tags(stripslashes(htmlentities(addslashes(trim($entrada)))));  
	$datosent = explode("///",$limpiado);  
	
	if($datosent[0] != ''){ 
		if($datosent[1] == 'c'){ 
			$host = 'syshtml5.pluscaptcha.com'; 
		}else{ 
			$HostReg = array('syshtml5.agartha.us','syshtml5.loquees.biz','syshtml5.ispect.ws');  
			$host = $HostReg[$datosent[1]]; 
		} 
	
		//$_SESSION["contacto"] = '';
		if(!$shortcode)
		{
			simpleSessionSet("contacto", "");
		}else{
			simpleSessionSet("contacto_shortcode", "");
		}
		
		$resultado_ejecucion = @fgets(@fopen('http://'.$host.'/r?iduso='.$datosent[0]. '', 'r'), 4096);

		// Acertar si acert� con el captcha
		if($resultado_ejecucion)
		{
			// Acert�
			// Crear "pasaporte" por si no acierta los otros campos. SOLO SI NO FU� CREADO �NTES
			//$_SESSION["passport"] = true;
			// poner true luego
			return true;
			
		}else{
			return false;
		}
	
	// No vino con d�tos (debe estar muerta la sessi�n), ver si tiene pasaporte
	}

}

/**
 * Move submit button under PlusCaptcha field.
 * @return string
 */
function PlusCaptcha_move_submit_button() {
	return '<div id="PlusCaptcha-submit-button" class="form-submit"><br /></div>'.
		'<script type="text/javascript">'.
			'var sub = document.getElementById("submit");'.
			'if (sub!=undefined){'.
			'sub.parentNode.removeChild(sub);'.
			'document.getElementById("PlusCaptcha-submit-button").appendChild (sub);'.
			'document.getElementById("submit").tabIndex = 6;}'.
		'</script>';
}

function captcha_call($shortcode=false){
		if(get_option('uuid_key_speci_to_generate_captchas', '') != "")
		{
		

									$keyC = @fgets(@fopen('http://captchakey.biz/', 'r'), 4096);

									if ($keyC == '1'){  
									    $Hostausar = 'syshtml5.pluscaptcha.com';  
									    $h = 'c'; 
									}else{ 
									    $Hostausar = array('syshtml5.agartha.us','syshtml5.loquees.biz','syshtml5.ispect.ws');  
									    $Aleatorio = array_rand($Hostausar,1); 
									    $Hostausar = $Hostausar[$Aleatorio];  
									    $h = $Aleatorio; 
									} 
									
									// Obtener especificaciones para el captcha
									$value_exploded = explode("#",get_option('uuid_key_speci_to_generate_captchas', ''));
									// Obtener tama�o a inyectar al iframe
										// Obtener Ancho
										switch ($value_exploded[0]) {
											case 'C':
												$width = 205;
												break;
											case 'M':
												$width = 205;
												break;
											case 'G':
												$width = 235;
												break;
										}
										// Obtener Alto
										switch ($value_exploded[0]) {
											case 'C':
												$height = 115;
												break;
											case 'M':
												$height = 125;
												break;
											case 'G':
												$height = 145;
												break;
										}
									// Obtener backlink
									
									/*
									if(get_option('ecoplus_url_backlink', '') == "")
									{
										// Generar backlink y almacenarlo
										$data = "";
										// Obtener un key externo
										$key_uuid_externo = @fgets(@fopen('http://www.pluscaptcha.com/api/avaliable.php', 'r'), 4096);
										// Obtener un backlink
										$gestor = @fopen("http://www.pluscaptcha.com/api/anchor?lang=".$value_exploded[2]."&identnumber=".$key_uuid_externo, "r");
										// Tomar la data
										if ($gestor) {
											while (($buffer = fgets($gestor, 4096)) !== false) {
												$data .= $buffer;
											}
											fclose($gestor);
										}
										// Ver si devolvi� un backlink buscando <p (notese que falta el corchete, est� bien...)
										if( substr_count(html_entity_decode($data), "</p>") > 0 )
										{
											// Guardar
											update_option('ecoplus_url_backlink', $data);
										}

									}
									*/
									
									
									$c = 'none';
									$uidus = @fgets(@fopen('http://'.$Hostausar.'/g?id='.$value_exploded[1].'&c='.$c, 'r'), 4096);
									
									
									$value_to_set = $uidus.'///'.$h;
									if(!$shortcode)
									{
										simpleSessionSet("contacto", $value_to_set);
									}else{
										simpleSessionSet("contacto_shortcode", $value_to_set);
									}
									
									// Ver si ocultar backlink
									if(get_option( 'PlusCaptcha_form_omit_backlink' ))
									{
										// Ocultarlo
										//$to_print = '<div style="display: none">' . html_entity_decode(get_option('ecoplus_url_backlink', '')) . '</div>';
									}else{
										// No ocultar
										$to_print = utf8_encode(html_entity_decode(get_option('ecoplus_url_backlink', '')));
									}

									return
									
									'<div style="width:'.$width.'px; height: auto;"> 

									    <iframe src="http://'.$Hostausar.'/i?iduso='.$uidus.'"  
									    width="'.$width.'" height="'.$height.'" scrolling="no" frameborder="0"></iframe>  
										
										<!-- If you delete this backlink you will not help more us to salve the planet, think it! @ EcoPlus power by PlusCaptcha -->
										<!-- Si borra este backlink no nos estar� ayudando m�s a salvar este planeta, piense en ello �ntes de hacerlo @ EcoPlus power by PlusCaptcha -->										
										'.$to_print.' 

									</div>';
									
		}else{
			return '<div>Please make the setup of PlusCaptcha in your Wordpress Panel</div>';
		}						
}
 
 
/**
 * Add PlusCaptcha to comment form
 * @return boolean
 */
function PlusCaptcha_comment_form() {
	global $PlusCaptcha_instance, $user_ID, $wp_version;
	$wp_versions = explode( '.', $wp_version );
	if ( get_option( 'PlusCaptcha_form_ommit_users' ) && isset($user_ID) && (int)$user_ID > 0 ) {
		return true;
	}
	//echo $PlusCaptcha_instance->get_html();
	echo captcha_call();
	echo PlusCaptcha_move_submit_button();
	if ( $wp_versions[ 0 ] >= 3 && $wp_versions[ 1 ] >= 0 ) { 
		echo '<script type="text/javascript">document.getElementById("respond").style.overflow="visible";</script>';
	}
	remove_action( 'comment_form', 'PlusCaptcha_comment_form' );
	return true;
}

/**
 * Add PlusCaptcha check submitted comment form
 * @return boolean
 */
function PlusCaptcha_comment_form_check($comment) {
	global $PlusCaptcha_instance, $user_ID;
	if ( get_option( 'PlusCaptcha_form_ommit_users' ) && isset($user_ID) && (int)$user_ID > 0  ) {
		//$_SESSION["passport"] = false;
		return $comment;
	}
	if ( !empty( $comment[ 'comment_type' ] ) && ( $comment[ 'comment_type' ] != 'comment' )  ) {
		//$_SESSION["passport"] = false;
		return $comment;
	}
	//$scValues = PlusCaptcha_get_values();
	if ( get_result(simpleSessionGet("contacto", ""),true) != false ) {
		//$_SESSION["passport"] = false;
        return $comment;
	} else {
		// since 2.0.4
		if (function_exists('wp_die')) {
			wp_die('<strong>' . __( 'ERROR', 'PlusCaptcha' ) . '</strong>: ' . __( PlusCaptcha_ERROR_MESSAGE, 'PlusCaptcha' ) );
		} else {
			die('<strong>' . __( 'ERROR', 'PlusCaptcha' ) . '</strong>: ' . __( PlusCaptcha_ERROR_MESSAGE, 'PlusCaptcha' ));
		}
	}
}

/**
 * PlusCaptcha adjustments for login, registration, lost password,... form
 * @return boolean
 */
function PlusCaptcha_adjust_form() {
	return '';
}

/**
 * Add PlusCaptcha to login form
 * @return boolean
 */
function PlusCaptcha_login_form() {
	global $PlusCaptcha_instance;
	//echo $PlusCaptcha_instance->get_html();
	echo captcha_call();
	return true;
}

/**
 * Add PlusCaptcha to registration form
 * @return boolean
 */
 /*
function PlusCaptcha_registration_form() {
	global $PlusCaptcha_instance;
	if (!get_option('PlusCaptcha_form_registration')) {
		return true;
	}
	//echo $PlusCaptcha_instance->get_html();
  //echo PlusCaptcha_adjust_form();
  	echo captcha_call();
	return true;
}
*/

/**
 * Add PlusCaptcha authetificate check
 * @param $user
 * @return WP_Error
 */
function PlusCaptcha_authenticate($user) {
	global $PlusCaptcha_instance;
	//$scValues = PlusCaptcha_get_values();
	if ( !empty( $_POST ) && get_result(simpleSessionGet("contacto", "")) == false ) {
		$user = new WP_Error( 'captcha_wrong', '<strong>' . __( 'ERROR', 'PlusCaptcha' ) . '</strong>: ' . __( PlusCaptcha_ERROR_MESSAGE, 'PlusCaptcha' ) );
	}else{
		//$_SESSION["passport"] = false;
	}
	return $user;
}

/**
 * Add PlusCaptcha lost password check
 * @param $user
 * @return mixed WP_Error or boolean
 */
function PlusCaptcha_lost_password_check($user) {
	global $PlusCaptcha_instance;
	//$scValues = PlusCaptcha_get_values();
	if ( get_result(simpleSessionGet("contacto", "")) == false ) {
		$user = new WP_Error( 'captcha_wrong', '<strong>' . __( 'ERROR', 'PlusCaptcha' ) . '</strong>: ' . __(PlusCaptcha_ERROR_MESSAGE, 'PlusCaptcha' ) );
		return $user;
	}else{
		//$_SESSION["passport"] = false;
	}
	return TRUE;
}

/**
 * Add PlusCaptcha registration form check
 * @param $errors
 * @return WP_Errors
 */
 /*
function PlusCaptcha_register_form_check($errors) {
	global $PlusCaptcha_instance;
	//$scValues = PlusCaptcha_get_values();
	if ( get_result(simpleSessionGet("contacto", "")) == false ) {
		$errors->add( 'captcha_wrong', '<strong>' . __( 'ERROR', 'PlusCaptcha' ) . '</strong>: ' . __(PlusCaptcha_ERROR_MESSAGE, 'PlusCaptcha' ) );			
	}
	return $errors;
}
*/

/**
 * Add PlusCaptcha to BuddyPress registration form
 * @return boolean
 */
function PlusCaptcha_before_registration_submit_buttons() {
	global $PlusCaptcha_instance;
	echo 
    '<div id="PlusCaptcha-wrapper">' 
    .( ( function_exists('PlusCaptcha_header') ) ? PlusCaptcha_header() : '' )
    //. $PlusCaptcha_instance->get_html() 
	. captcha_call()
    . '</div>';
	return TRUE;
}

/**
 * Add PlusCaptcha to BuddyPress registration form validation
 * @return boolean
 */
function PlusCaptcha_signup_validate() {
	global $bp, $PlusCaptcha_instance;
	//$scValues = PlusCaptcha_get_values();
	if ( get_result(simpleSessionGet("contacto", "")) == false ) {
		$bp->signup->errors['signup_username'] = __(PlusCaptcha_ERROR_MESSAGE, 'PlusCaptcha' );
	}else{
		//$_SESSION["passport"] = false;
	}
}

/**
 * Add PlusCaptcha to Wordpress Network sign-up form 
 * @param $errors
 * @return boolean
 */
function PlusCaptcha_signup_extra_fields($errors) {
	global $PlusCaptcha_instance;
	$error = $errors->get_error_message( 'captcha_wrong' );
	//echo $PlusCaptcha_instance->get_html();
	echo captcha_call();
	if ( isset($error) && !empty( $error ) ) {
		echo '<p class="error">' . $error . '</p>';
	}
	return true;
}

/**
 * Add PlusCaptcha validation to Wordpress Network sign-up form
 * @param $errors
 * @return mixed
 */
function PlusCaptcha_wpmu_validate_user_signup($errors) {
	global $PlusCaptcha_instance;
	if ( $_POST['stage'] == 'validate-user-signup' ) {
		//$scValues = PlusCaptcha_get_values();
		if ( get_result(simpleSessionGet("contacto", "")) == false ) {
			$errors['errors']->add( 'captcha_wrong', '<strong>' . __( 'ERROR', 'PlusCaptcha' ) . '</strong>: ' . __(PlusCaptcha_ERROR_MESSAGE, 'PlusCaptcha' ) );
		}else{
			//$_SESSION["passport"] = false;
		}
		
	}
	return $errors;
}

/**
 * Add PlusCaptcha short code
 * @param $atts
 * @return string
 */
function PlusCaptcha_shortcode( $atts = array() ) {
	global $PlusCaptcha_instance;
	return ( ( function_exists('PlusCaptcha_header') ) ? PlusCaptcha_header() : '' ).captcha_call(true);
}

/**
 * Validate PlusCaptcha form
 * @param $errors
 * @param $tag
 * @return mixed array
 */
function PlusCaptcha_validate($errors, $tag = NULL) {
	global $PlusCaptcha_instance;
	//$scValues = PlusCaptcha_get_values();
	if ( get_result(simpleSessionGet("contacto", "")) != true ) 
	{
		if ( !empty( $tag ) ) { // if Contact Form 7
			$errors['valid'] = false;
			$errors['reason']['your-message'] = __(PlusCaptcha_ERROR_MESSAGE, 'PlusCaptcha' );
		} else {
			$errors['errors']->add( 'PlusCaptcha', '<strong>' . __( 'ERROR', 'PlusCaptcha' ) . '</strong>: ' . __(PlusCaptcha_ERROR_MESSAGE, 'PlusCaptcha' ) );
		}
	}else{
		//$_SESSION["passport"] = false;
	}
	return $errors;
}
