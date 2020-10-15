<?php
/* Booked Appointments support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('soapery_booked_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_booked_theme_setup', 1 );
	function soapery_booked_theme_setup() {
		// Register shortcode in the shortcodes list
		if (soapery_exists_booked()) {
			add_action('soapery_action_add_styles', 					'soapery_booked_frontend_scripts');
			add_action('soapery_action_shortcodes_list',				'soapery_booked_reg_shortcodes');
			if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
				add_action('soapery_action_shortcodes_list_vc',		'soapery_booked_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'soapery_filter_importer_options',			'soapery_booked_importer_set_options' );
				add_filter( 'soapery_filter_importer_import_row',		'soapery_booked_importer_check_row', 9, 4);
			}
		}
		if (is_admin()) {
			add_filter( 'soapery_filter_importer_required_plugins',	'soapery_booked_importer_required_plugins', 10, 2);
			add_filter( 'soapery_filter_required_plugins',				'soapery_booked_required_plugins' );
		}
	}
}


// Check if plugin installed and activated
if ( !function_exists( 'soapery_exists_booked' ) ) {
	function soapery_exists_booked() {
		return class_exists('booked_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'soapery_booked_required_plugins' ) ) {
	//Handler of add_filter('soapery_filter_required_plugins',	'soapery_booked_required_plugins');
	function soapery_booked_required_plugins($list=array()) {
		if (in_array('booked', (array)soapery_storage_get('required_plugins'))) {
			$path = soapery_get_file_dir('plugins/install/booked.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Booked', 'soapery'),
					'slug' 		=> 'booked',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'soapery_booked_frontend_scripts' ) ) {
	//Handler of add_action( 'soapery_action_add_styles', 'soapery_booked_frontend_scripts' );
	function soapery_booked_frontend_scripts() {
		if (file_exists(soapery_get_file_dir('css/plugin.booked.css')))
            wp_enqueue_style( 'soapery-plugin.booked-style',  soapery_get_file_url('css/plugin.booked.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'soapery_booked_importer_required_plugins' ) ) {
	//Handler of add_filter( 'soapery_filter_importer_required_plugins',	'soapery_booked_importer_required_plugins', 10, 2);
	function soapery_booked_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('booked', (array)soapery_storage_get('required_plugins')) && !soapery_exists_booked() )
		if (soapery_strpos($list, 'booked')!==false && !soapery_exists_booked() )
			$not_installed .= '<br>' . esc_html__('Booked Appointments', 'soapery');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'soapery_booked_importer_set_options' ) ) {
	//Handler of add_filter( 'soapery_filter_importer_options',	'soapery_booked_importer_set_options', 10, 1 );
	function soapery_booked_importer_set_options($options=array()) {
		if (in_array('booked', (array)soapery_storage_get('required_plugins')) && soapery_exists_booked()) {
			$options['additional_options'][] = 'booked_%';		// Add slugs to export options for this plugin
		}
		return $options;
	}
}

// Check if the row will be imported
if ( !function_exists( 'soapery_booked_importer_check_row' ) ) {
	//Handler of add_filter('soapery_filter_importer_import_row', 'soapery_booked_importer_check_row', 9, 4);
	function soapery_booked_importer_check_row($flag, $table, $row, $list) {
		if ($flag || strpos($list, 'booked')===false) return $flag;
		if ( soapery_exists_booked() ) {
			if ($table == 'posts')
				$flag = $row['post_type']=='booked_appointments';
		}
		return $flag;
	}
}


// Lists
//------------------------------------------------------------------------

// Return booked calendars list, prepended inherit (if need)
if ( !function_exists( 'soapery_get_list_booked_calendars' ) ) {
	function soapery_get_list_booked_calendars($prepend_inherit=false) {
		return soapery_exists_booked() ? soapery_get_list_terms($prepend_inherit, 'booked_custom_calendars') : array();
	}
}



// Register plugin's shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('soapery_booked_reg_shortcodes')) {
	//Handler of add_filter('soapery_action_shortcodes_list',	'soapery_booked_reg_shortcodes');
	function soapery_booked_reg_shortcodes() {
		if (soapery_storage_isset('shortcodes')) {

			$booked_cals = soapery_get_list_booked_calendars();

			soapery_sc_map('booked-appointments', array(
				"title" => esc_html__("Booked Appointments", 'soapery'),
				"desc" => esc_html__("Display the currently logged in user's upcoming appointments", 'soapery'),
				"decorate" => true,
				"container" => false,
				"params" => array()
				)
			);

			soapery_sc_map('booked-calendar', array(
				"title" => esc_html__("Booked Calendar", 'soapery'),
				"desc" => esc_html__("Insert booked calendar", 'soapery'),
				"decorate" => true,
				"container" => false,
				"params" => array(
					"calendar" => array(
						"title" => esc_html__("Calendar", 'soapery'),
						"desc" => esc_html__("Select booked calendar to display", 'soapery'),
						"value" => "0",
						"type" => "select",
						"options" => soapery_array_merge(array(0 => esc_html__('- Select calendar -', 'soapery')), $booked_cals)
					),
					"year" => array(
						"title" => esc_html__("Year", 'soapery'),
						"desc" => esc_html__("Year to display on calendar by default", 'soapery'),
						"value" => date("Y"),
						"min" => date("Y"),
						"max" => date("Y")+10,
						"type" => "spinner"
					),
					"month" => array(
						"title" => esc_html__("Month", 'soapery'),
						"desc" => esc_html__("Month to display on calendar by default", 'soapery'),
						"value" => date("m"),
						"min" => 1,
						"max" => 12,
						"type" => "spinner"
					)
				)
			));
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('soapery_booked_reg_shortcodes_vc')) {
	//Handler of add_filter('soapery_action_shortcodes_list_vc',	'soapery_booked_reg_shortcodes_vc');
	function soapery_booked_reg_shortcodes_vc() {

		$booked_cals = soapery_get_list_booked_calendars();

		// Booked Appointments
		vc_map( array(
				"base" => "booked-appointments",
				"name" => esc_html__("Booked Appointments", 'soapery'),
				"description" => esc_html__("Display the currently logged in user's upcoming appointments", 'soapery'),
				"category" => esc_html__('Content', 'soapery'),
				'icon' => 'icon_trx_booked',
				"class" => "trx_sc_single trx_sc_booked_appointments",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array()
			) );
			
		class WPBakeryShortCode_Booked_Appointments extends SOAPERY_VC_ShortCodeSingle {}

		// Booked Calendar
		vc_map( array(
				"base" => "booked-calendar",
				"name" => esc_html__("Booked Calendar", 'soapery'),
				"description" => esc_html__("Insert booked calendar", 'soapery'),
				"category" => esc_html__('Content', 'soapery'),
				'icon' => 'icon_trx_booked',
				"class" => "trx_sc_single trx_sc_booked_calendar",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "calendar",
						"heading" => esc_html__("Calendar", 'soapery'),
						"description" => esc_html__("Select booked calendar to display", 'soapery'),
						"admin_label" => true,
						"class" => "",
						"std" => "0",
						"value" => array_flip((array)soapery_array_merge(array(0 => esc_html__('- Select calendar -', 'soapery')), $booked_cals)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "year",
						"heading" => esc_html__("Year", 'soapery'),
						"description" => esc_html__("Year to display on calendar by default", 'soapery'),
						"admin_label" => true,
						"class" => "",
						"std" => date("Y"),
						"value" => date("Y"),
						"type" => "textfield"
					),
					array(
						"param_name" => "month",
						"heading" => esc_html__("Month", 'soapery'),
						"description" => esc_html__("Month to display on calendar by default", 'soapery'),
						"admin_label" => true,
						"class" => "",
						"std" => date("m"),
						"value" => date("m"),
						"type" => "textfield"
					)
				)
			) );
			
		class WPBakeryShortCode_Booked_Calendar extends SOAPERY_VC_ShortCodeSingle {}

	}
}
?>