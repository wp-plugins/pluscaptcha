<?php

if (!defined('ABSPATH')) { exit; }

if (!class_exists('wpCommentFormInlineErrors')){
    class wpCommentFormInlineErrors
    {

        public function __construct() { add_action('init', array($this, 'init')); }

        /**
         * Hook me up, buttercup
         */

        public function init()
        {
            session_start();
            /* all these hooks are in wp since version 3.0, that's where we aim. */
            add_filter('wp_die_handler', array($this, 'getWpDieHandler'));
            add_action('comment_form_before_fields', array($this, 'displayFormError'));
            add_action('comment_form_logged_in_after', array($this, 'displayFormError'));
            add_filter('comment_form_default_fields',array($this, 'formDefaults'));
            add_filter('comment_form_field_comment',array($this, 'formCommentDefault'));
        }

        /**
         * Admin error helper
         *
         * @param $error
         */

        private function displayAdminError($error) { echo '<div id="message" class="error"><p><strong>' . $error . '</strong></p></div>';  }


        /************************ Let's do this. ************************/

        /**
         * Overwrites wordpress error handeling.
         *
         * @param $handler
         * @return array
         */

        function getWpDieHandler($handler){ return array($this, 'handleWpError'); }


        /**
         * Now this sounds great does it not? :) After refresh, we can
         * display that message. Easy peasy my man. Of course, only if
         * it's not admin error.
         *
         * @param $message
         * @param string $title
         * @param array $args
         */

        function handleWpError($message, $title='', $args=array())
        {
            // this is simple, if it's not admin error, and we simply continue
            // and sort it our way. Meaning, send errors to form itself and display them thru $_SESSION.
            // and yes, we test if comment id is present, not sure how else to test if commenting featured is being used :)
            if(!is_admin() && !empty($_POST['comment_post_ID']) && is_numeric($_POST['comment_post_ID'])){
                $_SESSION['formError'] = $message;
                // let's save those form fields in session as well hey? bit annoying
                // filling everything again and again. might work
                $denied = array('submit', 'comment_post_ID', 'comment_parent');
                foreach($_POST as $key => $value){
                    if(!in_array($key, $denied)){
                        $_SESSION['formFields'][$key] = stripslashes($value);
                    }
                }
                // write, redirect, go
                session_write_close();
                wp_safe_redirect(get_permalink($_POST['comment_post_ID']) . '#formError', 302);
                exit;
            } else {
                _default_wp_die_handler($message, $title, $args);   // this is for the other errors
            }
        }


        /**
         * Display inline form error.
         */

        public function displayFormError()
        {
            $formError = $_SESSION['formError'];
            unset($_SESSION['formError']);
            if(!empty($formError)){
                echo '<div id="formError" class="formError" style="color:red;">';
                echo '<p>'. $formError .'</p>';
                echo '</div><div class="clear clearfix"></div>';
            }
        }


        /**
         * Reset form defaults to value sent, it's nice when form remebers
         * stuff and doesn't force you to fill in shit again and again.
         *
         * @param $fields
         * @return mixed
         */

        function formDefaults($fields)
        {
            $formFields = $_SESSION['formFields'];
            foreach($fields as $key => $field){
                if($this->stringContains('input', $field)){
                    if($this->stringContains('type="text"', $field)){
                        $fields[$key] = str_replace('value=""', 'value="'. stripslashes($formFields[$key]) .'"', $field);
                    }
                } elseif ($this->stringContains('</textarea>', $field)){
                    $fields[$key] = str_replace('</textarea>', stripslashes($formFields[$key]) .'</textarea>', $field);
                }
            }
            return $fields;
        }


        /**
         * Of course comment field is special :) needs special
         * hook for defaults.
         *
         * @param $comment_field
         * @return mixed
         */

        function formCommentDefault($comment_field)
        {
            $formFields = $_SESSION['formFields'];
            unset($_SESSION['formFields']);
            return str_replace('</textarea>', $formFields['comment'] . '</textarea>', $comment_field);
        }


        /**
         * Just little helper for filling the form again.
         *
         * @param $haystack
         * @param $needle
         * @return bool
         */

        public function stringContains($needle, $haystack){ return strpos($haystack, $needle) !== FALSE; }

    }

}

new wpCommentFormInlineErrors();