<?php
/* WordPress Social Login support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('soapery_sociallogin_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sociallogin_theme_setup', 1 );
	function soapery_sociallogin_theme_setup() {
		if (is_admin()) {
			add_filter( 'soapery_filter_required_plugins',					'soapery_sociallogin_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'soapery_sociallogin_required_plugins' ) ) {
	function soapery_sociallogin_required_plugins($list=array()) {
		if (in_array('sociallogin', soapery_storage_get('required_plugins'))) {
				$list[] = array(
					'name' 		=> 'WordPress Social Login',
					'slug' 		=> 'wordpress-social-login',
					'required' 	=> false
					);
		}
		return $list;
	}
}
?>