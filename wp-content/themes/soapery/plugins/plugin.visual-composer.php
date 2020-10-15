<?php
/* WPBakery PageBuilder support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('soapery_vc_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_vc_theme_setup', 1 );
	function soapery_vc_theme_setup() {
		if (soapery_exists_visual_composer()) {
			if (is_admin()) {
				add_filter( 'soapery_filter_importer_options',				'soapery_vc_importer_set_options' );
			}
			add_action('soapery_action_add_styles',		 				'soapery_vc_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'soapery_filter_importer_required_plugins',		'soapery_vc_importer_required_plugins', 10, 2 );
			add_filter( 'soapery_filter_required_plugins',					'soapery_vc_required_plugins' );
		}
	}
}

// Check if WPBakery PageBuilder installed and activated
if ( !function_exists( 'soapery_exists_visual_composer' ) ) {
	function soapery_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if WPBakery PageBuilder in frontend editor mode
if ( !function_exists( 'soapery_vc_is_frontend' ) ) {
	function soapery_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'soapery_vc_required_plugins' ) ) {
	//Handler of add_filter('soapery_filter_required_plugins',	'soapery_vc_required_plugins');
	function soapery_vc_required_plugins($list=array()) {
		if (in_array('visual_composer', (array)soapery_storage_get('required_plugins'))) {
			$path = soapery_get_file_dir('plugins/install/js_composer.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('WPBakery PageBuilder', 'soapery'),
					'slug' 		=> 'js_composer',
					'source'	=> $path,
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Enqueue VC custom styles
if ( !function_exists( 'soapery_vc_frontend_scripts' ) ) {
	//Handler of add_action( 'soapery_action_add_styles', 'soapery_vc_frontend_scripts' );
	function soapery_vc_frontend_scripts() {
		if (file_exists(soapery_get_file_dir('css/plugin.visual-composer.css')))
            wp_enqueue_style( 'soapery-plugin.visual-composer-style',  soapery_get_file_url('css/plugin.visual-composer.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check VC in the required plugins
if ( !function_exists( 'soapery_vc_importer_required_plugins' ) ) {
	//Handler of add_filter( 'soapery_filter_importer_required_plugins',	'soapery_vc_importer_required_plugins', 10, 2 );
	function soapery_vc_importer_required_plugins($not_installed='', $list='') {
		if (!soapery_exists_visual_composer() )		// && soapery_strpos($list, 'visual_composer')!==false
			$not_installed .= '<br>' . esc_html__('WPBakery PageBuilder', 'soapery');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'soapery_vc_importer_set_options' ) ) {
	//Handler of add_filter( 'soapery_filter_importer_options',	'soapery_vc_importer_set_options' );
	function soapery_vc_importer_set_options($options=array()) {
		if ( in_array('visual_composer', (array)soapery_storage_get('required_plugins')) && soapery_exists_visual_composer() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'wpb_js_templates';
		}
		return $options;
	}
}
?>