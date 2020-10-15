<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'soapery_shortcodes_is_used' ) ) {
	function soapery_shortcodes_is_used() {
		return soapery_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| (is_admin() && soapery_strpos($_SERVER['REQUEST_URI'], 'vc-roles')!==false)			// VC Role Manager
			|| (function_exists('soapery_vc_is_frontend') && soapery_vc_is_frontend());			// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'soapery_shortcodes_width' ) ) {
	function soapery_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", 'soapery'),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'soapery_shortcodes_height' ) ) {
	function soapery_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", 'soapery'),
			"desc" => wp_kses_data( __("Width and height of the element", 'soapery') ),
			"value" => $h,
			"type" => "text"
		);
	}
}

// Return sc_param value
if ( !function_exists( 'soapery_get_sc_param' ) ) {
	function soapery_get_sc_param($prm) {
		return soapery_storage_get_array('sc_params', $prm);
	}
}

// Set sc_param value
if ( !function_exists( 'soapery_set_sc_param' ) ) {
	function soapery_set_sc_param($prm, $val) {
		soapery_storage_set_array('sc_params', $prm, $val);
	}
}

// Add sc settings in the sc list
if ( !function_exists( 'soapery_sc_map' ) ) {
	function soapery_sc_map($sc_name, $sc_settings) {
		soapery_storage_set_array('shortcodes', $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list after the key
if ( !function_exists( 'soapery_sc_map_after' ) ) {
	function soapery_sc_map_after($after, $sc_name, $sc_settings='') {
		soapery_storage_set_array_after('shortcodes', $after, $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list before the key
if ( !function_exists( 'soapery_sc_map_before' ) ) {
	function soapery_sc_map_before($before, $sc_name, $sc_settings='') {
		soapery_storage_set_array_before('shortcodes', $before, $sc_name, $sc_settings);
	}
}

// Compare two shortcodes by title
if ( !function_exists( 'soapery_compare_sc_title' ) ) {
	function soapery_compare_sc_title($a, $b) {
		return strcmp($a['title'], $b['title']);
	}
}



/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'soapery_shortcodes_settings_theme_setup' ) ) {
//	if ( soapery_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'soapery_action_before_init_theme', 'soapery_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'soapery_action_after_init_theme', 'soapery_shortcodes_settings_theme_setup' );
	function soapery_shortcodes_settings_theme_setup() {
		if (soapery_shortcodes_is_used()) {

			// Sort templates alphabetically
			$tmp = soapery_storage_get('registered_templates');
			ksort($tmp);
			soapery_storage_set('registered_templates', $tmp);

			// Prepare arrays 
			soapery_storage_set('sc_params', array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", 'soapery'),
					"desc" => wp_kses_data( __("ID for current element", 'soapery') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", 'soapery'),
					"desc" => wp_kses_data( __("CSS class for current element (optional)", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", 'soapery'),
					"desc" => wp_kses_data( __("Any additional CSS rules (if need)", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'soapery'),
					'ol'	=> esc_html__('Ordered', 'soapery'),
					'iconed'=> esc_html__('Iconed', 'soapery')
				),

				'yes_no'	=> soapery_get_list_yesno(),
				'on_off'	=> soapery_get_list_onoff(),
				'dir' 		=> soapery_get_list_directions(),
				'align'		=> soapery_get_list_alignments(),
				'float'		=> soapery_get_list_floats(),
				'hpos'		=> soapery_get_list_hpos(),
				'show_hide'	=> soapery_get_list_showhide(),
				'sorting' 	=> soapery_get_list_sortings(),
				'ordering' 	=> soapery_get_list_orderings(),
				'shapes'	=> soapery_get_list_shapes(),
				'sizes'		=> soapery_get_list_sizes(),
				'sliders'	=> soapery_get_list_sliders(),
				'controls'	=> soapery_get_list_controls(),
                    'categories'=> is_admin() && soapery_get_value_gp('action')=='vc_edit_form' && substr(soapery_get_value_gp('tag'), 0, 4)=='trx_' && isset($_POST['params']['post_type']) && $_POST['params']['post_type']!='post'
                        ? soapery_get_list_terms(false, soapery_get_taxonomy_categories_by_post_type($_POST['params']['post_type']))
                        : soapery_get_list_categories(),
				'columns'	=> soapery_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), soapery_get_list_files("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), soapery_get_list_icons()),
				'locations'	=> soapery_get_list_dedicated_locations(),
				'filters'	=> soapery_get_list_portfolio_filters(),
				'formats'	=> soapery_get_list_post_formats_filters(),
				'hovers'	=> soapery_get_list_hovers(true),
				'hovers_dir'=> soapery_get_list_hovers_directions(true),
				'schemes'	=> soapery_get_list_color_schemes(true),
				'animations'		=> soapery_get_list_animations_in(),
				'margins' 			=> soapery_get_list_margins(true),
				'blogger_styles'	=> soapery_get_list_templates_blogger(),
				'forms'				=> soapery_get_list_templates_forms(),
				'posts_types'		=> soapery_get_list_posts_types(),
				'googlemap_styles'	=> soapery_get_list_googlemap_styles(),
				'field_types'		=> soapery_get_list_field_types(),
				'label_positions'	=> soapery_get_list_label_positions()
				)
			);

			// Common params
			soapery_set_sc_param('animation', array(
				"title" => esc_html__("Animation",  'soapery'),
				"desc" => wp_kses_data( __('Select animation while object enter in the visible area of page',  'soapery') ),
				"value" => "none",
				"type" => "select",
				"options" => soapery_get_sc_param('animations')
				)
			);
			soapery_set_sc_param('top', array(
				"title" => esc_html__("Top margin",  'soapery'),
				"divider" => true,
				"value" => "inherit",
				"type" => "select",
				"options" => soapery_get_sc_param('margins')
				)
			);
			soapery_set_sc_param('bottom', array(
				"title" => esc_html__("Bottom margin",  'soapery'),
				"value" => "inherit",
				"type" => "select",
				"options" => soapery_get_sc_param('margins')
				)
			);
			soapery_set_sc_param('left', array(
				"title" => esc_html__("Left margin",  'soapery'),
				"value" => "inherit",
				"type" => "select",
				"options" => soapery_get_sc_param('margins')
				)
			);
			soapery_set_sc_param('right', array(
				"title" => esc_html__("Right margin",  'soapery'),
				"desc" => wp_kses_data( __("Margins around this shortcode", 'soapery') ),
				"value" => "inherit",
				"type" => "select",
				"options" => soapery_get_sc_param('margins')
				)
			);

			soapery_storage_set('sc_params', apply_filters('soapery_filter_shortcodes_params', soapery_storage_get('sc_params')));

			// Shortcodes list
			//------------------------------------------------------------------
			soapery_storage_set('shortcodes', array());
			
			// Register shortcodes
			do_action('soapery_action_shortcodes_list');

			// Sort shortcodes list
			$tmp = soapery_storage_get('shortcodes');
			uasort($tmp, 'soapery_compare_sc_title');
			soapery_storage_set('shortcodes', $tmp);
		}
	}
}
?>