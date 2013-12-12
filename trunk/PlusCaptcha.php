<?php
/*
Plugin Name: PlusCaptcha
Plugin URI: http://www.pluscaptcha.com/
Description: No more spam! Save the ecology! The world's first interactive captcha. Balance between design and safety. A captcha simple to install and use.
Version: 1.1.2
Author: IT Oeste
Author URI: http://www.itoeste.com/
License: GNU GPL2
*/
/*
Copyright (C) 2013 IT Oeste. (www.pluscaptcha.com). All rights reserved.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License,
version 2, as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/* Ocultar errores */
require_once(ABSPATH . 'wp-settings.php');
error_reporting(0);
/* *************** */

define('PlusCaptcha_SITE_URL', 'http://www.pluscaptcha.com/');
// for backward compatibility - 2.0
defined('WP_PLUGIN_DIR') or define('WP_PLUGIN_DIR', ABSPATH . '/wp-content/plugins');

// define absolute path to plugin
define('PlusCaptcha_DIR_NAME', basename( dirname(__FILE__) ));
define('PlusCaptcha_ROOT', WP_PLUGIN_DIR . '/' . PlusCaptcha_DIR_NAME);
define('PlusCaptcha_URL', WP_PLUGIN_URL . '/' . PlusCaptcha_DIR_NAME);
// define absolute path to plugin  PlusCaptcha.php
define('PlusCaptcha_PHP_PATH', PlusCaptcha_URL . '/library/PlusCaptcha.php');
// define absolute path to plugin library
define('PlusCaptcha_LIBRARY', PlusCaptcha_ROOT . '/library');
// define absolute path to plugin templates
define('PlusCaptcha_TEMPLATE', PlusCaptcha_ROOT . '/template');
define('PlusCaptcha_ERROR_MESSAGE', 'The solution of task you submitted was incorrect. Please read the instruction and try again.');
define('PlusCaptcha_ERROR_MESSAGE_BR', 'The solution of task you submitted was incorrect.<br>Please read the instruction and try again.');


/*
********************** External Plugin Used ***********************
*/

	/*
	 Thanks to Sweetcaptcha project to help to us with EcoPlus! Fight for the nature!
	 Basecode Was Used.
	*/
	/*
	 Thanks to Comment Form Inline Errors @ latorante! (Edited Code)
	*/
	include dirname(__FILE__) . '/external/comment-form-inline-errors.php';
	/*
	 Thanks to Simple Session Support @ Peter Wooster! (Editen Code)
	*/
	include dirname(__FILE__) . '/external/simple-session-support.php';
	
	
/*
********************** ******************** ***********************
*/


// prepare wordpress version for check
$wp_versions = explode( '.', $wp_version );

add_action('init', 'PlusCaptcha_init', 10);
function PlusCaptcha_init() {
  //global $wpdb;
  wp_enqueue_script('jquery');
  // TODO: http://www.pluscaptcha.com/min/?f=res/js/captchi.jquery-ui-1.8.6.custom.min.js -> v1.9.2 - 2012-11-27
  //wp_enqueue_script( 'jquery-ui.custom', plugins_url( 'js/jquery-ui.custom.min.js', __FILE__ ) ); // v1.9.2 - 2012-11-27
  //wp_enqueue_script('jquery-ui-core'); // use WP jquery.ui.core.min.js
  
  wp_enqueue_script('jquery-ui-core', false, array('jquery'));
  wp_enqueue_script('jquery-ui-draggable', false, array('jquery'));
  wp_enqueue_script('jquery-ui-droppable', false, array('jquery'));
  wp_enqueue_script( 'plscptf', plugins_url( 'js/plscptf.js', __FILE__ ) );
}

	// No more PlusCaptcha.php, directly code:
	
	// in case this is called like standalone script - we need wordpress functions available
	if ( ! function_exists( 'get_option' ) ) {
		// absolute path to wp installation root
		$wordpress_path = realpath ( dirname ( __FILE__ ) . '/../../../' );
		require_once $wordpress_path . '/wp-load.php';
	}
	
	session_start();
	
	
wp_enqueue_style( 'PlusCaptcha_Stylesheet', plugins_url( 'css/style.css', __FILE__ ) ); // TODO
// split action to admin and public part
if (is_admin()) {
	require_once PlusCaptcha_LIBRARY . '/admin.php';
	// Add admin notices.
	//add_action('admin_notices', 'PlusCaptcha_admin_notices');
	// add link to settings menu
	add_action('admin_menu', 'PlusCaptcha_admin_menu');
	
	// TODO: add activation hook -> default option values - XXX - doesn't work since wp 3.1 and below 2.8
	//register_activation_hook( __FILE__, 'PlusCaptcha_activate' );
	
	// because various problems with register activation hook trough Wordpress versions - check if Sweet Captcha is installed, otherwise set default values
	add_action('admin_menu', 'PlusCaptcha_activate');
	add_action('admin_menu', 'Generate_New_Account');
	
	// add uninstall hook -> remove option values - only for wordpress version >= 2.7
	if ( ( $wp_versions[ 0 ] >=2 ) && ( $wp_versions[ 1 ] >= 7 ) ) {
		register_uninstall_hook( __FILE__, 'PlusCaptcha_uninstall' );
	}
  
} else {
	require_once PlusCaptcha_LIBRARY . '/public.php';
	// add jquery to all public pages
	//add_action( 'login_head' , 'PlusCaptcha_login_head' );
  //wp_enqueue_script( 'jquery' );

  // add Sweet Captcha to comment form
	if ( get_option( 'PlusCaptcha_form_comment' ) ) {
		add_action( 'comment_form', 'PlusCaptcha_comment_form', 1 );
		add_filter( 'preprocess_comment', 'PlusCaptcha_comment_form_check', 1 );
	}
	
	// add Sweet Captcha to login form
	if ( get_option( 'PlusCaptcha_form_login' ) ) {
    // only for version >= 2.8
		if ( ( ( $wp_versions[ 0 ] >= 2 ) || ( $wp_versions[ 1 ] > 7 ) ) ) {
			add_action( 'login_form', 'PlusCaptcha_login_form', 1 );
			add_filter( 'authenticate', 'PlusCaptcha_authenticate', 40, 3 );
		}
    // add PlusCaptcha to BuddyPress login form
    add_action( 'bp_sidebar_login_form', 'PlusCaptcha_login_form' );
	}
	
	// add Sweet Captcha to lost password form
	if ( get_option( 'PlusCaptcha_form_lost' ) ) {
		add_action( 'lostpassword_form', 'PlusCaptcha_login_form', 1 );
		add_filter( 'allow_password_reset', 'PlusCaptcha_lost_password_check', 1 );
	}
	
	// add Sweet Captcha to registration form
	/*if ( get_option( 'PlusCaptcha_form_registration' ) ) {
		add_action('register_form', 'PlusCaptcha_registration_form' );
		add_filter('registration_errors', 'PlusCaptcha_register_form_check', 1);
		
		add_action('bp_before_registration_submit_buttons', 'PlusCaptcha_before_registration_submit_buttons' );
		add_action('bp_signup_validate', 'PlusCaptcha_signup_validate' );
		
		// adding PlusCaptcha to Network Wordpress registration form - WP version >= 3
		if ( ( $wp_versions[ 0 ] > 2 ) ) {
			add_action('signup_extra_fields', 'PlusCaptcha_signup_extra_fields' );
			add_filter('wpmu_validate_user_signup', 'PlusCaptcha_wpmu_validate_user_signup' );
		}
	}*/
}

// PlusCaptcha Contact Form 
if ( get_option( 'PlusCaptcha_form_contact' ) ) {
  require_once 'library/shortcode-and-others.php';
}

/* Sample of adding header to PlusCaptcha form */
/*
function PlusCaptcha_header() {
  return '<label>Captcha Anti-Spam Question (required)</label>';
}
*/