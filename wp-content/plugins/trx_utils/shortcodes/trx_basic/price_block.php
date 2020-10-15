<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_price_block_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_price_block_theme_setup' );
	function soapery_sc_price_block_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_price_block_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_price_block_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('soapery_sc_price_block')) {	
	function soapery_sc_price_block($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"style" => 1,
			"title" => "",
			"link" => "",
			"link_text" => "",
			"icon" => "",
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$output = '';
		$class .= ($class ? ' ' : '') . soapery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= soapery_get_css_dimensions_from_values($width, $height);
		if ($money) $money = do_shortcode('[trx_price money="'.esc_attr($money).'" period="'.esc_attr($period).'"'.($currency ? ' currency="'.esc_attr($currency).'"' : '').']');
		$content = do_shortcode(soapery_sc_clear_around($content));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price_block sc_price_block_style_'.max(1, min(3, $style))
						. (!empty($class) ? ' '.esc_attr($class) : '')
						. ($scheme && !soapery_param_is_off($scheme) && !soapery_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
					. '>'
				. (!empty($title) ? '<div class="sc_price_block_title"><span>'.($title).'</span></div>' : '')
				. '<div class="sc_price_block_money">'
					. (!empty($icon) ? '<div class="sc_price_block_icon '.esc_attr($icon).'"></div>' : '')
					. ($money)
				. '</div>'
				. (!empty($content) ? '<div class="sc_price_block_description">'.($content).'</div>' : '')
				. (!empty($link_text) ? '<div class="sc_price_block_link">'.do_shortcode('[trx_button link="'.($link ? esc_url($link) : '#').'"]'.($link_text).'[/trx_button]').'</div>' : '')
			. '</div>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_price_block', $atts, $content);
	}
	soapery_require_shortcode('trx_price_block', 'soapery_sc_price_block');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_price_block_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_price_block_reg_shortcodes');
	function soapery_sc_price_block_reg_shortcodes() {
	
		soapery_sc_map("trx_price_block", array(
			"title" => esc_html__("Price block", 'soapery'),
			"desc" => wp_kses_data( __("Insert price block with title, price and description", 'soapery') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Block style", 'soapery'),
					"desc" => wp_kses_data( __("Select style for this price block", 'soapery') ),
					"value" => 1,
					"options" => soapery_get_list_styles(1, 3),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'soapery'),
					"desc" => wp_kses_data( __("Block title", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"link" => array(
					"title" => esc_html__("Link URL", 'soapery'),
					"desc" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"link_text" => array(
					"title" => esc_html__("Link text", 'soapery'),
					"desc" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon",  'soapery'),
					"desc" => wp_kses_data( __('Select icon from Fontello icons set (placed before/instead price)',  'soapery') ),
					"value" => "",
					"type" => "icons",
					"options" => soapery_get_sc_param('icons')
				),
				"money" => array(
					"title" => esc_html__("Money", 'soapery'),
					"desc" => wp_kses_data( __("Money value (dot or comma separated)", 'soapery') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"currency" => array(
					"title" => esc_html__("Currency", 'soapery'),
					"desc" => wp_kses_data( __("Currency character", 'soapery') ),
					"value" => "$",
					"type" => "text"
				),
				"period" => array(
					"title" => esc_html__("Period", 'soapery'),
					"desc" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'soapery'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'soapery') ),
					"value" => "",
					"type" => "checklist",
					"options" => soapery_get_sc_param('schemes')
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'soapery'),
					"desc" => wp_kses_data( __("Align price to left or right side", 'soapery') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => soapery_get_sc_param('float')
				), 
				"_content_" => array(
					"title" => esc_html__("Description", 'soapery'),
					"desc" => wp_kses_data( __("Description for this price block", 'soapery') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"width" => soapery_shortcodes_width(),
				"height" => soapery_shortcodes_height(),
				"top" => soapery_get_sc_param('top'),
				"bottom" => soapery_get_sc_param('bottom'),
				"left" => soapery_get_sc_param('left'),
				"right" => soapery_get_sc_param('right'),
				"id" => soapery_get_sc_param('id'),
				"class" => soapery_get_sc_param('class'),
				"animation" => soapery_get_sc_param('animation'),
				"css" => soapery_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_price_block_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_price_block_reg_shortcodes_vc');
	function soapery_sc_price_block_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_price_block",
			"name" => esc_html__("Price block", 'soapery'),
			"description" => wp_kses_data( __("Insert price block with title, price and description", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_price_block',
			"class" => "trx_sc_single trx_sc_price_block",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Block style", 'soapery'),
					"desc" => wp_kses_data( __("Select style of this price block", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"std" => 1,
					"value" => array_flip(soapery_get_list_styles(1, 3)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'soapery'),
					"description" => wp_kses_data( __("Block title", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'soapery'),
					"description" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_text",
					"heading" => esc_html__("Link text", 'soapery'),
					"description" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'soapery'),
					"description" => wp_kses_data( __("Select icon from Fontello icons set (placed before/instead price)", 'soapery') ),
					"class" => "",
					"value" => soapery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", 'soapery'),
					"description" => wp_kses_data( __("Money value (dot or comma separated)", 'soapery') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'soapery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", 'soapery'),
					"description" => wp_kses_data( __("Currency character", 'soapery') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'soapery'),
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", 'soapery'),
					"description" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'soapery') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'soapery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'soapery'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'soapery') ),
					"group" => esc_html__('Colors and Images', 'soapery'),
					"class" => "",
					"value" => array_flip(soapery_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'soapery'),
					"description" => wp_kses_data( __("Align price to left or right side", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(soapery_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Description", 'soapery'),
					"description" => wp_kses_data( __("Description for this price block", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				soapery_get_vc_param('id'),
				soapery_get_vc_param('class'),
				soapery_get_vc_param('animation'),
				soapery_get_vc_param('css'),
				soapery_vc_width(),
				soapery_vc_height(),
				soapery_get_vc_param('margin_top'),
				soapery_get_vc_param('margin_bottom'),
				soapery_get_vc_param('margin_left'),
				soapery_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_PriceBlock extends SOAPERY_VC_ShortCodeSingle {}
	}
}
?>