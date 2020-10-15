<?php
/* Instagram Widget support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('soapery_instagram_widget_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_instagram_widget_theme_setup', 1 );
	function soapery_instagram_widget_theme_setup() {
		if (soapery_exists_instagram_widget()) {
			add_action( 'soapery_action_add_styles', 						'soapery_instagram_widget_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'soapery_filter_importer_required_plugins',		'soapery_instagram_widget_importer_required_plugins', 10, 2 );
			add_filter( 'soapery_filter_required_plugins',					'soapery_instagram_widget_required_plugins' );
		}
	}
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'soapery_exists_instagram_widget' ) ) {
	function soapery_exists_instagram_widget() {
		return function_exists('wpiw_init');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'soapery_instagram_widget_required_plugins' ) ) {
	//Handler of add_filter('soapery_filter_required_plugins',	'soapery_instagram_widget_required_plugins');
	function soapery_instagram_widget_required_plugins($list=array()) {
		if (in_array('instagram_widget', (array)soapery_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Instagram Widget', 'soapery'),
					'slug' 		=> 'wp-instagram-widget',
					'required' 	=> false
				);
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'soapery_instagram_widget_frontend_scripts' ) ) {
	//Handler of add_action( 'soapery_action_add_styles', 'soapery_instagram_widget_frontend_scripts' );
	function soapery_instagram_widget_frontend_scripts() {
		if (file_exists(soapery_get_file_dir('css/plugin.instagram-widget.css')))
            wp_enqueue_style( 'soapery-plugin.instagram-widget-style',  soapery_get_file_url('css/plugin.instagram-widget.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Widget in the required plugins
if ( !function_exists( 'soapery_instagram_widget_importer_required_plugins' ) ) {
	//Handler of add_filter( 'soapery_filter_importer_required_plugins',	'soapery_instagram_widget_importer_required_plugins', 10, 2 );
	function soapery_instagram_widget_importer_required_plugins($not_installed='', $list='') {
		if (soapery_strpos($list, 'instagram_widget')!==false && !soapery_exists_instagram_widget() )
			$not_installed .= '<br>' . esc_html__('WP Instagram Widget', 'soapery');
		return $not_installed;
	}
}
?>