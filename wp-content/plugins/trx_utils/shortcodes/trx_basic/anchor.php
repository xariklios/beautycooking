<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_anchor_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_anchor_theme_setup' );
	function soapery_sc_anchor_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_anchor_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_anchor_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_anchor id="unique_id" description="Anchor description" title="Short Caption" icon="icon-class"]
*/

if (!function_exists('soapery_sc_anchor')) {	
	function soapery_sc_anchor($atts, $content = null) {
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"description" => '',
			"icon" => '',
			"url" => "",
			"separator" => "no",
			// Common params
			"id" => ""
		), $atts)));
		$output = $id 
			? '<a id="'.esc_attr($id).'"'
				. ' class="sc_anchor"' 
				. ' title="' . ($title ? esc_attr($title) : '') . '"'
				. ' data-description="' . ($description ? esc_attr(soapery_strmacros($description)) : ''). '"'
				. ' data-icon="' . ($icon ? $icon : '') . '"' 
				. ' data-url="' . ($url ? esc_attr($url) : '') . '"' 
				. ' data-separator="' . (soapery_param_is_on($separator) ? 'yes' : 'no') . '"'
				. '></a>'
			: '';
		return apply_filters('soapery_shortcode_output', $output, 'trx_anchor', $atts, $content);
	}
	soapery_require_shortcode("trx_anchor", "soapery_sc_anchor");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_anchor_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_anchor_reg_shortcodes');
	function soapery_sc_anchor_reg_shortcodes() {
	
		soapery_sc_map("trx_anchor", array(
			"title" => esc_html__("Anchor", 'soapery'),
			"desc" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__("Anchor's icon",  'soapery'),
					"desc" => wp_kses_data( __('Select icon for the anchor from Fontello icons set',  'soapery') ),
					"value" => "",
					"type" => "icons",
					"options" => soapery_get_sc_param('icons')
				),
				"title" => array(
					"title" => esc_html__("Short title", 'soapery'),
					"desc" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Long description", 'soapery'),
					"desc" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"url" => array(
					"title" => esc_html__("External URL", 'soapery'),
					"desc" => wp_kses_data( __("External URL for this TOC item", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"separator" => array(
					"title" => esc_html__("Add separator", 'soapery'),
					"desc" => wp_kses_data( __("Add separator under item in the TOC", 'soapery') ),
					"value" => "no",
					"type" => "switch",
					"options" => soapery_get_sc_param('yes_no')
				),
				"id" => soapery_get_sc_param('id')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_anchor_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_anchor_reg_shortcodes_vc');
	function soapery_sc_anchor_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_anchor",
			"name" => esc_html__("Anchor", 'soapery'),
			"description" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_anchor',
			"class" => "trx_sc_single trx_sc_anchor",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Anchor's icon", 'soapery'),
					"description" => wp_kses_data( __("Select icon for the anchor from Fontello icons set", 'soapery') ),
					"class" => "",
					"value" => soapery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Short title", 'soapery'),
					"description" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Long description", 'soapery'),
					"description" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("External URL", 'soapery'),
					"description" => wp_kses_data( __("External URL for this TOC item", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "separator",
					"heading" => esc_html__("Add separator", 'soapery'),
					"description" => wp_kses_data( __("Add separator under item in the TOC", 'soapery') ),
					"class" => "",
					"value" => array("Add separator" => "yes" ),
					"type" => "checkbox"
				),
				soapery_get_vc_param('id')
			),
		) );
		
		class WPBakeryShortCode_Trx_Anchor extends SOAPERY_VC_ShortCodeSingle {}
	}
}
?>