<?php
/**
 * Soapery Framework: messages subsystem
 *
 * @package	soapery
 * @since	soapery 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('soapery_messages_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_messages_theme_setup' );
	function soapery_messages_theme_setup() {
		// Core messages strings
        add_filter('soapery_action_add_scripts_inline', 'soapery_messages_add_scripts_inline');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('soapery_get_error_msg')) {
	function soapery_get_error_msg() {
		return soapery_storage_get('error_msg');
	}
}

if (!function_exists('soapery_set_error_msg')) {
	function soapery_set_error_msg($msg) {
		$msg2 = soapery_get_error_msg();
		soapery_storage_set('error_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('soapery_get_success_msg')) {
	function soapery_get_success_msg() {
		return soapery_storage_get('success_msg');
	}
}

if (!function_exists('soapery_set_success_msg')) {
	function soapery_set_success_msg($msg) {
		$msg2 = soapery_get_success_msg();
		soapery_storage_set('success_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('soapery_get_notice_msg')) {
	function soapery_get_notice_msg() {
		return soapery_storage_get('notice_msg');
	}
}

if (!function_exists('soapery_set_notice_msg')) {
	function soapery_set_notice_msg($msg) {
		$msg2 = soapery_get_notice_msg();
		soapery_storage_set('notice_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('soapery_set_system_message')) {
	function soapery_set_system_message($msg, $status='info', $hdr='') {
		update_option('soapery_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('soapery_get_system_message')) {
	function soapery_get_system_message($del=false) {
		$msg = get_option('soapery_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			soapery_del_system_message();
		return $msg;
	}
}

if (!function_exists('soapery_del_system_message')) {
	function soapery_del_system_message() {
		delete_option('soapery_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('soapery_messages_add_scripts_inline')) {
    function soapery_messages_add_scripts_inline($vars=array()) {
        // Strings for translation
        $vars["strings"] = array(
            'ajax_error' => esc_html__('Invalid server answer', 'soapery'),
            'bookmark_add' => esc_html__('Add the bookmark', 'soapery'),
            'bookmark_added' => esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'soapery'),
            'bookmark_del' => esc_html__('Delete this bookmark', 'soapery'),
            'bookmark_title' => esc_html__('Enter bookmark title', 'soapery'),
            'bookmark_exists' => esc_html__('Current page already exists in the bookmarks list', 'soapery'),
            'search_error' => esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'soapery'),
            'email_confirm' => esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'soapery'),
            'reviews_vote' => esc_html__('Thanks for your vote! New average rating is:', 'soapery'),
            'reviews_error' => esc_html__('Error saving your vote! Please, try again later.', 'soapery'),
            'error_like' => esc_html__('Error saving your like! Please, try again later.', 'soapery'),
            'error_global' => esc_html__('Global error text', 'soapery'),
            'name_empty' => esc_html__('The name can\'t be empty', 'soapery'),
            'name_long' => esc_html__('Too long name', 'soapery'),
            'email_empty' => esc_html__('Too short (or empty) email address', 'soapery'),
            'email_long' => esc_html__('Too long email address', 'soapery'),
            'email_not_valid' => esc_html__('Invalid email address', 'soapery'),
            'subject_empty' => esc_html__('The subject can\'t be empty', 'soapery'),
            'subject_long' => esc_html__('Too long subject', 'soapery'),
            'text_empty' => esc_html__('The message text can\'t be empty', 'soapery'),
            'text_long' => esc_html__('Too long message text', 'soapery'),
            'send_complete' => esc_html__("Send message complete!", 'soapery'),
            'send_error' => esc_html__('Transmit failed!', 'soapery'),
            'login_empty' => esc_html__('The Login field can\'t be empty', 'soapery'),
            'login_long' => esc_html__('Too long login field', 'soapery'),
            'login_success' => esc_html__('Login success! The page will be reloaded in 3 sec.', 'soapery'),
            'login_failed' => esc_html__('Login failed!', 'soapery'),
            'password_empty' => esc_html__('The password can\'t be empty and shorter then 4 characters', 'soapery'),
            'password_long' => esc_html__('Too long password', 'soapery'),
            'password_not_equal' => esc_html__('The passwords in both fields are not equal', 'soapery'),
            'registration_success' => esc_html__('Registration success! Please log in!', 'soapery'),
            'registration_failed' => esc_html__('Registration failed!', 'soapery'),
            'geocode_error' => esc_html__('Geocode was not successful for the following reason:', 'soapery'),
            'googlemap_not_avail' => esc_html__('Google map API not available!', 'soapery'),
            'editor_save_success' => esc_html__("Post content saved!", 'soapery'),
            'editor_save_error' => esc_html__("Error saving post data!", 'soapery'),
            'editor_delete_post' => esc_html__("You really want to delete the current post?", 'soapery'),
            'editor_delete_post_header' => esc_html__("Delete post", 'soapery'),
            'editor_delete_success' => esc_html__("Post deleted!", 'soapery'),
            'editor_delete_error' => esc_html__("Error deleting post!", 'soapery'),
            'editor_caption_cancel' => esc_html__('Cancel', 'soapery'),
            'editor_caption_close' => esc_html__('Close', 'soapery')
        );
        return $vars;
    }
}
?>