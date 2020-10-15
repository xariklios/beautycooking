<?php
/**
 * Theme/Skin colors and fonts customization
 */


// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'soapery_core_customizer_theme_setup' ) ) {
	add_action( 'soapery_action_before_init_theme', 'soapery_core_customizer_theme_setup', 1 );
	function soapery_core_customizer_theme_setup() {

		// Add core customization in the custom css
		add_filter( 'soapery_filter_add_styles_inline', 			'soapery_core_customizer_add_custom_styles' );
		// Add core customizer scripts inline
		add_filter('soapery_action_add_scripts_inline',			'soapery_core_customizer_add_scripts_inline');

		// Load Color schemes then Theme Options are loaded
		add_action('soapery_action_load_main_options',				'soapery_core_customizer_load_options');

		// Recompile LESS and save CSS
		add_action('soapery_action_compile_less',					'soapery_core_customizer_compile_less');
		add_filter('soapery_filter_prepare_less',					'soapery_core_customizer_prepare_less');

		if ( is_admin() ) {
	
			// Ajax Save and Export Action handler
			add_action('wp_ajax_soapery_options_save', 				'soapery_core_customizer_save_options');

	
			// Ajax Delete color scheme Action handler
			add_action('wp_ajax_soapery_options_scheme_delete', 		'soapery_core_customizer_scheme_delete');


			// Ajax Copy color scheme Action handler
			add_action('wp_ajax_soapery_options_scheme_copy', 			'soapery_core_customizer_scheme_copy');

		}
		
	}
}

if ( !function_exists( 'soapery_core_customizer_theme_setup2' ) ) {
	add_action( 'soapery_action_before_init_theme', 'soapery_core_customizer_theme_setup2', 11 );
	function soapery_core_customizer_theme_setup2() {

		if ( is_admin() ) {

			// Add Theme Options in WP menu
			add_action('admin_menu', 								'soapery_core_customizer_admin_menu_item');
		}
		
	}
}

// Add 'Color Schemes' in the menu 'Theme Options'
if ( !function_exists( 'soapery_core_customizer_admin_menu_item' ) ) {
	//Handler of add_action('admin_menu', 'soapery_core_customizer_admin_menu_item');
	function soapery_core_customizer_admin_menu_item() {
		soapery_admin_add_menu_item('theme', array(
			'page_title' => esc_html__('Fonts & Colors', 'soapery'),
			'menu_title' => esc_html__('Fonts & Colors', 'soapery'),
			'capability' => 'manage_options',
			'menu_slug'  => 'soapery_options_customizer',
			'callback'   => 'soapery_core_customizer_page',
			'icon'		 => ''
			)
		);
	}
}


// Load Font settings and Color schemes when Theme Options are loaded
if ( !function_exists( 'soapery_core_customizer_load_options' ) ) {
	//Handler of add_action( 'soapery_action_load_main_options', 'soapery_core_customizer_load_options' );
	function soapery_core_customizer_load_options() {
		$mode = isset($_POST['mode']) ? $_POST['mode'] : '';
		$override = isset($_POST['override']) ? $_POST['override'] : '';
		if ($mode!='reset' || $override!='customizer') {
			$schemes = get_option( soapery_storage_get('options_prefix') . '_options_custom_colors' );
			if (!empty($schemes)) soapery_storage_set('custom_colors', $schemes);
			$fonts = get_option( soapery_storage_get('options_prefix') . '_options_custom_fonts' );
			if (!empty($fonts)) soapery_storage_set('custom_fonts', $fonts);
		}
	}
}


// Ajax Save and Export Action handler
if ( !function_exists( 'soapery_core_customizer_save_options' ) ) {
	//Handler of add_action('wp_ajax_soapery_options_save', 'soapery_core_customizer_save_options');
	//Handler of add_action('wp_ajax_nopriv_soapery_options_save', 'soapery_core_customizer_save_options');
	function soapery_core_customizer_save_options() {

		$mode = $_POST['mode'];
		$override = empty($_POST['override']) ? '' : $_POST['override'];

		if (!in_array($mode, array('save', 'reset')) || !in_array($override, array('customizer')))
			return;

		if ( !wp_verify_nonce( soapery_get_value_gp('nonce'), admin_url('admin-ajax.php') ) || !current_user_can('manage_options'))
			die();

		parse_str($_POST['data'], $data);

		// Refresh array with schemes from POST data
		if ($mode == 'save') {
			$colors = soapery_storage_get('custom_colors');
			if (is_array($colors) && count($colors) > 0) {
				$order = !empty($data['soapery_options_schemes_order']) ? explode(',', $data['soapery_options_schemes_order']) : array_keys($colors);
				$schemes = array();
				foreach ($order as $slug) {
					$new_slug = $data[$slug.'-slug'];
					if (empty($new_slug)) $new_slug = soapery_get_slug($scheme['title']);
					if (is_array($colors[$slug]) && count($colors[$slug]) > 0) {
						$schemes[$new_slug] = array();
						foreach ($colors[$slug] as $key=>$value) {
							$schemes[$new_slug][$key] = isset($data[$slug.'-'.$key]) ? $data[$slug.'-'.$key] : $value;
						}
					}
				}
				soapery_storage_set('custom_colors', $schemes);
			}
		}
		update_option( soapery_storage_get('options_prefix') . '_options_custom_colors', apply_filters('soapery_filter_save_custom_colors', $schemes));

		// Refresh array with fonts from POST data
		if ($mode == 'save') {
			$fonts = soapery_storage_get('custom_fonts');
			if (is_array($fonts) && count($fonts) > 0) {
				foreach ($fonts as $slug=>$font) {
					if (is_array($font) && count($font) > 0) {
						foreach ($font as $key=>$value) {
							if (isset($data[$slug.'-'.$key]))
								$fonts[$slug][$key] = soapery_is_inherit_option($data[$slug.'-'.$key]) ? '' : $data[$slug.'-'.$key];
						}
					}
				}
			}
		}
		soapery_storage_set('custom_fonts', $fonts);
		update_option( soapery_storage_get('options_prefix') . '_options_custom_fonts', apply_filters('soapery_filter_save_custom_fonts', $fonts));
		
		// Recompile LESS files with new fonts and colors
		do_action('soapery_action_compile_less');
		
		die();
	}
}


// Ajax Delete color scheme Action handler
if ( !function_exists( 'soapery_core_customizer_scheme_delete' ) ) {
	//Handler of add_action('wp_ajax_soapery_options_scheme_delete', 'soapery_core_customizer_scheme_delete');
	//Handler of add_action('wp_ajax_nopriv_soapery_options_scheme_delete', 'soapery_core_customizer_scheme_delete');
	function soapery_core_customizer_scheme_delete() {

		if ( !wp_verify_nonce( soapery_get_value_gp('nonce'), admin_url('admin-ajax.php') ) || !current_user_can('manage_options'))
			die();

		$scheme = $_POST['scheme'];
		$colors = soapery_storage_get('custom_colors');
		$order = !empty($_POST['order']) ? explode(',', $_POST['order']) : array_keys($colors);
		$response = array( 'error' => '' );

		// Refresh array with schemes from POST data
		if (isset($colors[$scheme])) {
			if (count($colors) > 1) {
				$schemes = array();
				foreach ($order as $slug) {
					if ($slug == $scheme) continue;
					if (is_array($colors[$slug]) && count($colors[$slug]) > 0) {
						$schemes[$slug] = $colors[$slug];
					}
				}
				soapery_storage_set('custom_colors', $schemes);
				update_option( soapery_storage_get('options_prefix') . '_options_custom_colors', apply_filters('soapery_filter_save_custom_colors', $schemes));
			} else
				$response['error'] = sprintf(esc_html__('You cannot delete last color scheme!', 'soapery'), $scheme);
		} else
			$response['error'] = sprintf(esc_html__('Color Scheme %s not found!', 'soapery'), $scheme);

		// Recompile LESS files with new fonts and colors
		do_action('soapery_action_compile_less');
		
		echo json_encode($response);
		die();
	}
}


// Ajax Copy color scheme Action handler
if ( !function_exists( 'soapery_core_customizer_scheme_copy' ) ) {
	//Handler of add_action('wp_ajax_soapery_options_scheme_copy', 'soapery_core_customizer_scheme_copy');
	//Handler of add_action('wp_ajax_nopriv_soapery_options_scheme_copy', 'soapery_core_customizer_scheme_copy');
	function soapery_core_customizer_scheme_copy() {

		if ( !wp_verify_nonce( soapery_get_value_gp('nonce'), admin_url('admin-ajax.php') ) || !current_user_can('manage_options') )
			die();

		$scheme = $_POST['scheme'];
		$colors = soapery_storage_get('custom_colors');
		$order = !empty($_POST['order']) ? explode(',', $_POST['order']) : array_keys($colors);
		$response = array( 'error' => '' );

		// Refresh array with schemes from POST data
		if (isset($colors[$scheme])) {
			// Generate slug for the scheme's copy
			$i = 0;
			do {
				$new_slug = $scheme.'_copy'.($i ? $i : '');
				$i++;
			} while (isset($colors[$new_slug]));
			// Copy schemes
			$schemes = array();
			foreach ($order as $slug) {
				if (is_array($colors[$slug]) && count($colors[$slug]) > 0) {
					$schemes[$slug] = $colors[$slug];
					if ($slug == $scheme) {
						$schemes[$new_slug] = $colors[$slug];
						$schemes[$new_slug]['title'] .= ' '.esc_html__('(Copy)', 'soapery');
					}
				}
			}
			soapery_storage_set('custom_colors', $schemes);
			update_option( soapery_storage_get('options_prefix') . '_options_custom_colors', apply_filters('soapery_filter_save_custom_colors', $schemes));
		} else
			$response['error'] = sprintf(esc_html__('Color Scheme %s not found!', 'soapery'), $scheme);

		// Recompile LESS files with new fonts and colors
		do_action('soapery_action_compile_less');
		
		echo json_encode($response);
		die();
	}
}

// Recompile LESS files when color schemes or theme options are saved
if (!function_exists('soapery_core_customizer_compile_less')) {
	//Handler of add_action('soapery_action_compile_less', 'soapery_core_customizer_compile_less');
	function soapery_core_customizer_compile_less() {
		if (soapery_get_theme_setting('less_compiler')=='no') return;
		$files = array();
		if (file_exists(soapery_get_file_dir('css/_utils.less'))) 	$files[] = soapery_get_file_dir('css/_utils.less');
		$files = apply_filters('soapery_filter_compile_less', $files);
		if (count($files) > 0) soapery_compile_less($files);
	}
}






/* Customizer page builder
-------------------------------------------------------------------- */

// Show Customizer page
if ( !function_exists( 'soapery_core_customizer_page' ) ) {
	function soapery_core_customizer_page() {

		$options = array();

		$start_partition = true;

		// Default color schemes
		$colors = soapery_storage_get('custom_colors');
		if (is_array($colors) && count($colors) > 0) {
			
			$demo_block = '';
			if (soapery_get_theme_setting('customizer_demo') && file_exists(soapery_get_file_dir('core/core.customizer/core.customizer.demo.php'))) {
				ob_start();
				get_template_part(soapery_get_file_slug('core/core.customizer/core.customizer.demo.php'));
				$demo_block = ob_get_contents();
				ob_end_clean();
			}
			$options["partition_schemes"] = array(
				"title" => esc_html__('Color schemes', 'soapery'),
				"override" => "customizer",
				"icon" => "iconadmin-palette",
				"type" => "partition");
			if ($start_partition) {
				$options["partition_schemes"]["start"] = "partitions";
				$start_partition = false;
			}

			$start_tab = true;
						
			foreach ($colors as $slug=>$scheme) {

				$options["tab_{$slug}"] = array(
					"title" => $scheme['title'],
					"override" => "customizer",
					"icon" => "iconadmin-palette",
					"type" => "tab");
				if ($start_tab) {
					$options["tab_{$slug}"]["start"] = "tabs";
					$start_tab = false;
				}

				$options["{$slug}-description"] = array(
					"title" => sprintf(esc_html__('Color scheme "%s"', 'soapery'), $scheme['title']),
					"desc" => wp_kses_data( sprintf(__('Specify the color for each element in the scheme "%s". After that you will be able to use your color scheme for the entire page, any part thereof and/or for the shortcodes!', 'soapery'), $scheme['title']) ),
					"override" => "customizer",
					"type" => "info");




				// Buttons
				$options["{$slug}-buttons_label"] = array(
					"desc" => wp_kses_data( __("You can duplicate current color scheme (appear on new tab) or delete it (if not last scheme)", 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "4_6 first",
					"type" => "label");
	
				$options["{$slug}-button_copy"] = array(
					"title" => esc_html__('Copy',  'soapery'),
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_6",
					"icon" => "iconadmin-docs",
					"action" => "scheme_copy",
					"type" => "button");
	
				$options["{$slug}-button_delete"] = array(
					"title" => esc_html__('Delete',  'soapery'),
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_6 last",
					"icon" => "iconadmin-trash",
					"action" => "scheme_delete",
					"type" => "button");





				// Scheme name and slug
				$options["{$slug}-title_label"] = array(
					"title" => esc_html__('Scheme names', 'soapery'),
					"desc" => wp_kses_data( __('Specify scheme title (to represent this color scheme in the lists) and scheme slug (to use this color scheme in the shortcodes).<br>Attention! If you change scheme title or slug - you must save options (press Save), then reload the page (press F5) after the success saving message appear!', 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");

				$options["{$slug}-title"] = array(
					"title" => esc_html__('Title',  'soapery'),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5",
					"std" => "",
					"val" => $scheme['title'],
					"type" => "text");

				$options["{$slug}-slug"] = array(
					"title" => esc_html__('Slug',  'soapery'),
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5 last",
					"std" => "",
					"val" => $slug,
					"type" => "text");



				// Demo block
				if ($demo_block) {
					$options["{$slug}-demo"] = array(
						"title" => esc_html__('Usage demo', 'soapery'),
						"desc" => wp_kses_data( __('Below you can see the example of decoration of the page with selected colors.', 'soapery') )
									. trim($demo_block),
						"override" => "customizer",
						"type" => "info");
				}



				// Accent colors
if (isset($scheme['accent1'])) {
				$options["{$slug}-accent_info"] = array(
					"title" => esc_html__('Accent colors', 'soapery'),
					"desc" => wp_kses_data( __('Specify colors for theme accented elements (if need). The theme may not use all of the colors.', 'soapery') ),
					"override" => "customizer",
					"type" => "info");

				// Accent 1 color
				$options["{$slug}-accent1_label"] = array(
					"title" => esc_html__('Accent 1', 'soapery'),
					"desc" => wp_kses_data( __('Select color for accented elements and their hover state', 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");


				$options["{$slug}-accent1"] = array(
					"title" => esc_html__('Color', 'soapery'),
					"std" => "",
					"val" => $scheme['accent1'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-accent1_hover"] = array(
					"title" => esc_html__('Hover', 'soapery'),
					"std" => "",
					"val" => $scheme['accent1_hover'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
}

if (isset($scheme['accent2'])) {
				// Accent 2 color
				$options["{$slug}-accent2_label"] = array(
					"title" => esc_html__('Accent 2', 'soapery'),
					"desc" => wp_kses_data( __('Select color for accented elements and their hover state', 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");

				$options["{$slug}-accent2"] = array(
					"std" => "",
					"val" => $scheme['accent2'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-accent2_hover"] = array(
					"std" => "",
					"val" => $scheme['accent2_hover'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
}


if (isset($scheme['accent3'])) {
				// Accent 3 color
				$options["{$slug}-accent3_label"] = array(
					"title" => esc_html__('Accent 3', 'soapery'),
					"desc" => wp_kses_data( __('Select color for accented elements and their hover state', 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");

				$options["{$slug}-accent3"] = array(
					"std" => "",
					"val" => $scheme['accent3'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-accent3_hover"] = array(
					"std" => "",
					"val" => $scheme['accent3_hover'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5 last",
					"style" => "tiny",
					"type" => "color");
}

if (isset($scheme['accent4'])) {
				// Accent 2 color
				$options["{$slug}-accent4_label"] = array(
					"title" => esc_html__('Accent 4', 'soapery'),
					"desc" => wp_kses_data( __('Select color for accented elements and their hover state', 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");

				$options["{$slug}-accent4"] = array(
					"std" => "",
					"val" => $scheme['accent4'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-accent4_hover"] = array(
					"std" => "",
					"val" => $scheme['accent4_hover'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
}



if (isset($scheme['rose'])) {
					// Accent 2 color
					$options["{$slug}-rose_label"] = array(
						"title" => esc_html__('Accent 5', 'soapery'),
						"desc" => wp_kses_data( __('Select color for accented elements and their hover state', 'soapery') ),
						"override" => "customizer",
						"divider" => false,
						"columns" => "2_5 first",
						"type" => "label");

					$options["{$slug}-rose"] = array(
						"std" => "",
						"val" => $scheme['rose'],
						"override" => "customizer",
						"divider" => false,
						"columns" => "1_5",
						"style" => "tiny",
						"type" => "color");
}


if (isset($scheme['text'])) {
				// Text colors
				$options["{$slug}-text_info"] = array(
					"title" => esc_html__('Text and Headers', 'soapery'),
					"desc" => wp_kses_data( __('Specify colors for the plain text, post info blocks and headers', 'soapery') ),
					"override" => "customizer",
					"type" => "info");
	
				// Text - simple text, links in the text and their hover state
				$options["{$slug}-text_label"] = array(
					"title" => esc_html__('Text', 'soapery'),
					"desc" => wp_kses_data( __('Select colors for the text: normal text color, light text (for example - post info) and dark text (headers, bold text, etc.)', 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-text"] = array(
					"title" => esc_html__('Text', 'soapery'),
					"std" => "",
					"val" => $scheme['text'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-text_light"] = array(
					"title" => esc_html__('Light', 'soapery'),
					"std" => "",
					"val" => $scheme['text_light'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-text_dark"] = array(
					"title" => esc_html__('Dark', 'soapery'),
					"std" => "",
					"val" => $scheme['text_dark'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
}

if (isset($scheme['inverse_text'])) {
				// Inverse text
				$options["{$slug}-inverse_label"] = array(
					"title" => esc_html__('Inverse text', 'soapery'),
					"desc" => wp_kses_data( __('Select colors for inversed text (text on accented background)', 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-inverse_text"] = array(
					"title" => esc_html__('Text', 'soapery'),
					"std" => "",
					"val" => $scheme['inverse_text'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-inverse_light"] = array(
					"title" => esc_html__('Light', 'soapery'),
					"std" => "",
					"val" => $scheme['inverse_light'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-inverse_dark"] = array(
					"title" => esc_html__('Dark', 'soapery'),
					"std" => "",
					"val" => $scheme['inverse_dark'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				$options["{$slug}-inverse_labe2l"] = array(
					"title" => esc_html__('Inverse links', 'soapery'),
					"desc" => wp_kses_data( __('Select colors for inversed links (links on accented background)', 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-inverse_link"] = array(
					"title" => esc_html__('Link', 'soapery'),
					"std" => "",
					"val" => $scheme['inverse_link'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-inverse_hover"] = array(
					"title" => esc_html__('Hover', 'soapery'),
					"std" => "",
					"val" => $scheme['inverse_hover'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5 last",
					"style" => "tiny",
					"type" => "color");
}


if (isset($scheme['bg_color'])) {
				// Page/Block colors
				$options["{$slug}-block_info"] = array(
					"title" => esc_html__('Page/Block decoration', 'soapery'),
					"desc" => wp_kses_data( __('Specify border and background to decorate whole page (if scheme accepted to the page) or entire block/section.', 'soapery') ),
					"override" => "customizer",
					"type" => "info");
	
				// Border
				$options["{$slug}-bd_color_label"] = array(
					"title" => esc_html__('Border color', 'soapery'),
					"desc" => wp_kses_data( __('Select the border color and it hover state', 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-bd_color"] = array(
					"title" => esc_html__('Color', 'soapery'),
					"std" => "",
					"val" => $scheme['bd_color'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				$options["{$slug}-bd_color_empty"] = array(
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5",
					"type" => "label");
	
				// Background color
				$options["{$slug}-bg_color_label"] = array(
					"title" => esc_html__('Background color', 'soapery'),
					"desc" => wp_kses_data( __('Select the background color and it hover state', 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-bg_color"] = array(
					"title" => esc_html__('Color', 'soapery'),
					"std" => "",
					"val" => $scheme['bg_color'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				$options["{$slug}-bg_color_empty"] = array(
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5",
					"type" => "label");
}


if (isset($scheme['bg_image'])) {
				// Background image 1
				$options["{$slug}-bg_image_label"] = array(
					"title" => esc_html__('Background image', 'soapery'),
					"desc" => wp_kses_data( __('Select first background image and it display parameters', 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-bg_image"] = array(
					"title" => esc_html__('Image', 'soapery'),
					"std" => "",
					"val" => $scheme['bg_image'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "3_5",
					"type" => "media");

				$options["{$slug}-bg_image_label2"] = array(
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");

				$options["{$slug}-bg_image_position"] = array(
					"title" => esc_html__('Position', 'soapery'),
					"std" => "",
					"val" => $scheme['bg_image_position'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"options" => soapery_get_list_bg_image_positions(),
					"type" => "select");
		
				$options["{$slug}-bg_image_repeat"] = array(
					"title" => esc_html__('Repeat', 'soapery'),
					"std" => "",
					"val" => $scheme['bg_image_repeat'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"options" => soapery_get_list_bg_image_repeats(),
					"type" => "select");

				$options["{$slug}-bg_image_attachment"] = array(
					"title" => esc_html__('Attachment', 'soapery'),
					"std" => "",
					"val" => $scheme['bg_image_attachment'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"options" => soapery_get_list_bg_image_attachments(),
					"type" => "select");
	
				// Background image 2
				$options["{$slug}-bg_image2_label"] = array(
					"title" => esc_html__('Background image 2', 'soapery'),
					"desc" => wp_kses_data( __('Select second background image and it display parameters', 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-bg_image2"] = array(
					"title" => esc_html__('Image', 'soapery'),
					"std" => "",
					"val" => $scheme['bg_image2'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "3_5",
					"type" => "media");

				$options["{$slug}-bg_image2_label2"] = array(
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");

				$options["{$slug}-bg_image2_position"] = array(
					"title" => esc_html__('Position', 'soapery'),
					"std" => "",
					"val" => $scheme['bg_image2_position'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"options" => soapery_get_list_bg_image_positions(),
					"type" => "select");
		
				$options["{$slug}-bg_image2_repeat"] = array(
					"title" => esc_html__('Repeat', 'soapery'),
					"std" => "",
					"val" => $scheme['bg_image2_repeat'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"options" => soapery_get_list_bg_image_repeats(),
					"type" => "select");

				$options["{$slug}-bg_image2_attachment"] = array(
					"title" => esc_html__('Attachment', 'soapery'),
					"std" => "",
					"val" => $scheme['bg_image2_attachment'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5 last",
					"options" => soapery_get_list_bg_image_attachments(),
					"type" => "select");
}

if (isset($scheme['alter_text'])) {
				// Alternative colors (highlight blocks, form fields, etc.)
				$options["{$slug}-alter_info"] = array(
					"title" => esc_html__('Alternative colors: Highlight areas / Input fields', 'soapery'),
					"desc" => wp_kses_data( __('Specify colors to decorate inner blocks or input fields in the forms', 'soapery') ),
					"override" => "customizer",
					"type" => "info");
	
				// Text in the highlight block
				$options["{$slug}-alter_text_label"] = array(
					"title" => esc_html__('Text and Headers', 'soapery'),
					"desc" => wp_kses_data( __('Specify colors for the plain text, post info blocks and headers in the highlight blocks', 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-alter_text"] = array(
					"title" => esc_html__('Text', 'soapery'),
					"std" => "",
					"val" => $scheme['alter_text'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-alter_light"] = array(
					"title" => esc_html__('Light', 'soapery'),
					"std" => "",
					"val" => $scheme['alter_light'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-alter_dark"] = array(
					"title" => esc_html__('Dark', 'soapery'),
					"std" => "",
					"val" => $scheme['alter_dark'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				// Links in the highlight block
				$options["{$slug}-alter_link_label"] = array(
					"title" => esc_html__('Links', 'soapery'),
					"desc" => wp_kses_data( __('Specify colors for the links in the highlight blocks', 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-alter_link"] = array(
					"title" => esc_html__('Color', 'soapery'),
					"std" => "",
					"val" => $scheme['alter_link'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-alter_hover"] = array(
					"title" => esc_html__('Hover', 'soapery'),
					"std" => "",
					"val" => $scheme['alter_hover'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
				
				// Border
				$options["{$slug}-alter_bd_color_label"] = array(
					"title" => esc_html__('Border color', 'soapery'),
					"desc" => wp_kses_data( __('Select the border colors for the normal state and for active (focused) field', 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-alter_bd_color"] = array(
					"title" => esc_html__('Color', 'soapery'),
					"std" => "",
					"val" => $scheme['alter_bd_color'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				$options["{$slug}-alter_bd_hover"] = array(
					"title" => esc_html__('Hover', 'soapery'),
					"std" => "",
					"val" => $scheme['alter_bd_hover'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				// Background Color
				$options["{$slug}-alter_bg_color_label"] = array(
					"title" => esc_html__('Background Color', 'soapery'),
					"desc" => wp_kses_data( __('Select the background colors for the normal state and for active (focused) field', 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-alter_bg_color"] = array(
					"title" => esc_html__('Color', 'soapery'),
					"std" => "",
					"val" => $scheme['alter_bg_color'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				$options["{$slug}-alter_bg_hover"] = array(
					"title" => esc_html__('Hover', 'soapery'),
					"std" => "",
					"val" => $scheme['alter_bg_hover'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				// Background image
				$options["{$slug}-alter_bg_image_label"] = array(
					"title" => esc_html__('Background image', 'soapery'),
					"desc" => wp_kses_data( __('Select alter background image and it display parameters', 'soapery') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-alter_bg_image"] = array(
					"title" => esc_html__('Image', 'soapery'),
					"std" => "",
					"val" => $scheme['alter_bg_image'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "3_5",
					"type" => "media");

				$options["{$slug}-alter_bg_image_label2"] = array(
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");

				$options["{$slug}-alter_bg_image_position"] = array(
					"title" => esc_html__('Position', 'soapery'),
					"std" => "",
					"val" => $scheme['alter_bg_image_position'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"options" => soapery_get_list_bg_image_positions(),
					"type" => "select");
		
				$options["{$slug}-alter_bg_image_repeat"] = array(
					"title" => esc_html__('Repeat', 'soapery'),
					"std" => "",
					"val" => $scheme['alter_bg_image_repeat'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"options" => soapery_get_list_bg_image_repeats(),
					"type" => "select");

				$options["{$slug}-alter_bg_image_attachment"] = array(
					"title" => esc_html__('Attachment', 'soapery'),
					"std" => "",
					"val" => $scheme['alter_bg_image_attachment'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"options" => soapery_get_list_bg_image_attachments(),
					"type" => "select");
}
			}
		}


		// Default fonts settings
		$fonts = soapery_storage_get('custom_fonts');
		if (is_array($fonts) && count($fonts) > 0) {

			$options["partition_fonts"] = array(
				"title" => esc_html__('Fonts', 'soapery'),
				"override" => "customizer",
				"icon" => "iconadmin-font",
				"type" => "partition");
			if ($start_partition) {
				$options["partition_fonts"]["start"] = "partitions";
				$start_partition = false;
			}

			$options["info_fonts_1"] = array(
				"title" => esc_html__('Typography settings', 'soapery'),
				"desc" => wp_kses_data( __('Select fonts, sizes and styles for the headings and paragraphs. You can use Google fonts and custom fonts.<br><br>How to install custom @font-face fonts into the theme?<br>All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!<br>Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.<br>Create your @font-face kit by using http://www.fontsquirrel.com/fontface/generator Fontsquirrel @font-face Generator</a> and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install.', 'soapery') ),
				"type" => "info");

			$show_titles = true;
			
			$list_fonts = soapery_get_list_fonts(true);
			$list_styles = soapery_get_list_fonts_styles(false);
			$list_weight = array(
				'inherit' => esc_html__("Inherit", 'soapery'), 
				'100' => esc_html__('100 (Light)', 'soapery'), 
				'300' => esc_html__('300 (Thin)',  'soapery'),
				'400' => esc_html__('400 (Normal)', 'soapery'),
				'500' => esc_html__('500 (Semibold)', 'soapery'),
				'600' => esc_html__('600 (Semibold)', 'soapery'),
				'700' => esc_html__('700 (Bold)', 'soapery'),
				'900' => esc_html__('900 (Black)', 'soapery')
			);

			foreach ($fonts as $slug=>$font) {
				if (isset($font['font-family'])) {
					$options["{$slug}-font-family"] = array(
						"title" => isset($font['title']) ? $font['title'] : soapery_strtoproper($slug),
						"desc" => isset($font['description']) ? $font['description'] : '',
						"divider" => false,
						"columns" => "2_8 first",
						"std" => "",
						"val" => $font['font-family'] ? $font['font-family'] : 'inherit',
						"options" => $list_fonts,
						"type" => "fonts");
				}
				if (isset($font['font-size'])) {
					$options["{$slug}-font-size"] = array(
						"title" => $show_titles ? esc_html__('Size', 'soapery') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"std" => "",
						"val" => soapery_is_inherit_option($font['font-size']) ? '' : $font['font-size'],
						"type" => "text");
				}
				if (isset($font['line-height'])) {
					$options["{$slug}-line-height"] = array(
						"title" => $show_titles ? esc_html__('Line height', 'soapery') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"std" => "",
						"val" => soapery_is_inherit_option($font['line-height']) ? '' : $font['line-height'],
						"type" => "text");
				} else {
					$options["{$slug}-line-height"] = array(
						"title" => $show_titles ? esc_html__('Line height', 'soapery') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"type" => "label");
				}
				if (isset($font['font-weight'])) {
					$options["{$slug}-font-weight"] = array(
						"title" => $show_titles ? esc_html__('Weight', 'soapery') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"std" => "",
						"val" => $font['font-weight'] ? $font['font-weight'] : 'inherit',
						"options" => $list_weight,
						"type" => "select");
				}
				if (isset($font['font-style'])) {
					$options["{$slug}-font-style"] = array(
						"title" => $show_titles ? esc_html__('Style', 'soapery') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"std" => "",
						"val" => $font['font-style'] ? $font['font-style'] : 'inherit',
						"multiple" => true,
						"options" => $list_styles,
						"type" => "checklist");
				}
				if (isset($font['margin-top'])) {
					$options["{$slug}-margin-top"] = array(
						"title" => $show_titles ? esc_html__('Margin Top', 'soapery') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"std" => "",
						"val" => soapery_is_inherit_option($font['margin-top']) ? '' : $font['margin-top'],
						"type" => "text");
				} else {
					$options["{$slug}-margin-top"] = array(
						"title" => $show_titles ? esc_html__('Margin Top', 'soapery') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"type" => "label");
				}
				if (isset($font['margin-bottom'])) {
					$options["{$slug}-margin-bottom"] = array(
						"title" => $show_titles ? esc_html__('Margin Bottom', 'soapery') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"std" => "",
						"val" => soapery_is_inherit_option($font['margin-bottom']) ? '' : $font['margin-bottom'],
						"type" => "text");
				} else {
					$options["{$slug}-margin-bottom"] = array(
						"title" => $show_titles ? esc_html__('Margin Bottom', 'soapery') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"type" => "label");
				}

				$show_titles = false;
			}
		}

		// Load required styles and scripts for this page
		soapery_core_customizer_load_scripts();
		// Prepare javascripts global variables
		soapery_core_customizer_prepare_scripts();
		
		// Build Options page
		soapery_options_page_start(array(
			'title' => esc_html__('Fonts & Colors', 'soapery'),
			"icon" => "iconadmin-cog",
			"subtitle" => esc_html__('Fonts settings & Color schemes', 'soapery'),
			"description" => wp_kses_data( __('Customize fonts and colors for your site.', 'soapery') ),
			'data' => $options,
			'create_form' => true,
			'buttons' => array('save', 'reset'),
			'override' => 'customizer'
		));

		if (is_array($options) && count($options) > 0) {
			foreach ($options as $id=>$option) { 
				soapery_options_show_field($id, $option);
			}
		}
	
		soapery_options_page_stop();
	}
}



// Prepare LESS variables before LESS files compilation
// Duplicate rules set for each color scheme
if (!function_exists('soapery_core_customizer_prepare_less')) {
	//Handler of add_filter('soapery_filter_prepare_less', 'soapery_core_customizer_prepare_less');
	function soapery_core_customizer_prepare_less() {

		// Prefix for override rules
		$prefix = soapery_get_theme_setting('less_prefix');
		// Use nested selectors: increase .css size, but allow use nested color schemes
		$nested = soapery_get_theme_setting('less_nested');

		$out = '';

		// Custom fonts
		$fonts_list = soapery_get_list_fonts(false);
		$custom_fonts = soapery_get_custom_fonts();

		if (is_array($custom_fonts) && count($custom_fonts) > 0) {
		foreach ($custom_fonts as $slug => $font) {
			
			// Prepare variables with separate font rules
			if (!empty($font['font-family']) && !soapery_is_inherit_option($font['font-family']))
				$out .= "@{$slug}_ff: \"" . esc_attr($font['font-family']) . '"' . (isset($fonts_list[$font['font-family']]['family']) ? ',' . $fonts_list[$font['font-family']]['family'] : '' ) . ";\n";
			else
				$out .= "@{$slug}_ff: inherit;\n";

			if (!empty($font['font-size']) && !soapery_is_inherit_option($font['font-size']))
				$out .= "@{$slug}_fs: " . soapery_prepare_css_value($font['font-size']) . ";\n";
			else
				$out .= "@{$slug}_fs: inherit;\n";
			
			if (!empty($font['line-height']) && !soapery_is_inherit_option($font['line-height']))
				$out .= "@{$slug}_lh: " . soapery_prepare_css_value($font['line-height']) . ";\n";
			else
				$out .= "@{$slug}_lh: inherit;\n";

			if (!empty($font['font-weight']) && !soapery_is_inherit_option($font['font-weight']))
				$out .= "@{$slug}_fw: " . trim($font['font-weight']) . ";\n";
			else
				$out .= "@{$slug}_fw: inherit;\n";

			if (!empty($font['font-style']) && !soapery_is_inherit_option($font['font-style']) && soapery_strpos($font['font-style'], 'i')!==false)
				$out .= "@{$slug}_fl: italic;\n";
			else
				$out .= "@{$slug}_fl: inherit;\n";

			if (!empty($font['font-style']) && !soapery_is_inherit_option($font['font-style']) && soapery_strpos($font['font-style'], 'u')!==false)
				$out .= "@{$slug}_td: underline;\n";
			else
				$out .= "@{$slug}_td: inherit;\n";

			if (!empty($font['margin-top']) && !soapery_is_inherit_option($font['margin-top']))
				$out .= "@{$slug}_mt: " . soapery_prepare_css_value($font['margin-top']) . ";\n";
			else
				$out .= "@{$slug}_mt: inherit;\n";

			if (!empty($font['margin-bottom']) && !soapery_is_inherit_option($font['margin-bottom']))
				$out .= "@{$slug}_mb: " . soapery_prepare_css_value($font['margin-bottom']) . ";\n";
			else
				$out .= "@{$slug}_mb: inherit;\n";

			$out .= "\n";


			// Prepare less-function with summary font settings
			$out .= ".{$slug}_font() {\n";
			if (!empty($font['font-family']) && !soapery_is_inherit_option($font['font-family']))
				$out .= "\tfont-family:\"" . esc_attr($font['font-family']) . '"' . (isset($fonts_list[$font['font-family']]['family']) ? ',' . $fonts_list[$font['font-family']]['family'] : '' ) . ";\n";
			if (!empty($font['font-size']) && !soapery_is_inherit_option($font['font-size']))
				$out .= "\tfont-size:" . soapery_prepare_css_value($font['font-size']) . ";\n";
			if (!empty($font['line-height']) && !soapery_is_inherit_option($font['line-height']))
				$out .= "\tline-height: " . soapery_prepare_css_value($font['line-height']) . ";\n";
			if (!empty($font['font-weight']) && !soapery_is_inherit_option($font['font-weight']))
				$out .= "\tfont-weight: " . trim($font['font-weight']) . ";\n";
			if (!empty($font['font-style']) && !soapery_is_inherit_option($font['font-style']) && soapery_strpos($font['font-style'], 'i')!==false)
				$out .= "\tfont-style: italic;\n";
			if (!empty($font['font-style']) && !soapery_is_inherit_option($font['font-style']) && soapery_strpos($font['font-style'], 'u')!==false)
				$out .= "\ttext-decoration: underline;\n";
			$out .= "}\n\n";

			$out .= ".{$slug}_margins() {\n";
			if (!empty($font['margin-top']) && !soapery_is_inherit_option($font['margin-top']))
				$out .= "\tmargin-top: " . soapery_prepare_css_value($font['margin-top']) . ";\n";
			if (!empty($font['margin-bottom']) && !soapery_is_inherit_option($font['margin-bottom']))
				$out .= "\tmargin-bottom: " . soapery_prepare_css_value($font['margin-bottom']) . ";\n";
			$out .= "}\n\n";
		}
		}

		$out .= "\n";


	
		// Prepare variables with separate colors
		$custom_colors = soapery_get_custom_colors();
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				if (is_array($data) && count($data) > 0) {
					foreach ($data as $key => $value) {
						if ($key == 'title' || soapery_strpos($key, 'bg_image')!==false) continue;
						$out .= "@{$scheme}_{$key}: " . esc_attr(
							!empty($value) 
								? $value
								: (soapery_strpos($key, 'bg_image')!==false
									? 'none'
									: 'inherit'
									)
							) . ";\n";
					}
				}
			}
		}
			
		$out .= "\n";
			

		// Prepare less-function with summary color settings

		// .scheme_color(accent1_hover)
		$out .= ".scheme_color(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "color: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_color(accent1_hover, @alpha)
		$out .= ".scheme_color(@color_name, @alpha) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "@r: red(@@color_var);\n"
					. "@g: green(@@color_var);\n"
					. "@b: blue(@@color_var);\n"
					. "color: rgba(@r, @g, @b, @alpha);\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_bg_color(accent1_hover)
		$out .= ".scheme_bg_color(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "background-color: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_bg_color(accent1_hover, @alpha)
		$out .= ".scheme_bg_color(@color_name, @alpha) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "@r: red(@@color_var);\n"
					. "@g: green(@@color_var);\n"
					. "@b: blue(@@color_var);\n"
					. "background-color: rgba(@r, @g, @b, @alpha);\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_bg_color_self(accent1_hover)
		$out .= ".scheme_bg_color_self(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . "&.scheme_{$scheme}" . ($nested ? ", [class*=\"scheme_\"] &.scheme_{$scheme}" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "background-color: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_bg_color_self(accent1_hover, @alpha)
		$out .= ".scheme_bg_color_self(@color_name, @alpha) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . "&.scheme_{$scheme}" . ($nested ? ", [class*=\"scheme_\"] &.scheme_{$scheme}" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "@r: red(@@color_var);\n"
					. "@g: green(@@color_var);\n"
					. "@b: blue(@@color_var);\n"
					. "background-color: rgba(@r, @g, @b, @alpha);\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_bg(accent1_hover)
		$out .= ".scheme_bg(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "background: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_bg(accent1_hover, @alpha)
		$out .= ".scheme_bg(@color_name, @alpha) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "@r: red(@@color_var);\n"
					. "@g: green(@@color_var);\n"
					. "@b: blue(@@color_var);\n"
					. "background: rgba(@r, @g, @b, @alpha);\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_bg_image()
		$out .= ".scheme_bg_image() {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				if (!empty($data['bg_image']) || !empty($data['bg_image2'])) {
					$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n";
					$comma = '';
					if (!empty($data['bg_image2'])) {
						$out .= "background: url(".esc_url($data['bg_image2']).') '.esc_attr($data['bg_image2_repeat']).' '.esc_attr($data['bg_image2_position']).' '.esc_attr($data['bg_image2_attachment']);
						$comma = ',';
					}
					if (!empty($data['bg_image'])) {
						$out .= ($comma ? $comma : "background:") . "url(".esc_url($data['bg_image']).') '.esc_attr($data['bg_image_repeat']).' '.esc_attr($data['bg_image_position']).' '.esc_attr($data['bg_image_attachment']);
					}
					$out .= ";\n";
					$out .= "}\n";
				}
			}
		}
		$out .= "}\n";

		// .scheme_alter_bg_image()
		$out .= ".scheme_alter_bg_image() {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				if (!empty($data['alter_bg_image'])) {
					$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n";
					$out .= "background: url(".esc_url($data['alter_bg_image']).') '.esc_attr($data['alter_bg_image_repeat']).' '.esc_attr($data['alter_bg_image_position']).' '.esc_attr($data['alter_bg_image_attachment']);
					$out .= "}\n";
				}
			}
		}
		$out .= "}\n";
			
		// .scheme_bd_color(accent1_hover)
		$out .= ".scheme_bd_color(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "border-color: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_bd_color(accent1_hover, @alpha)
		$out .= ".scheme_bd_color(@color_name, @alpha) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "@r: red(@@color_var);\n"
					. "@g: green(@@color_var);\n"
					. "@b: blue(@@color_var);\n"
					. "border-color: rgba(@r, @g, @b, @alpha);\n"
					. "}\n";
			}
		}
		$out .= "}\n";
			
		// .scheme_bdt_color(accent1_hover)
		$out .= ".scheme_bdt_color(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "border-top-color: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";
			
		// .scheme_bdb_color(accent1_hover)
		$out .= ".scheme_bdb_color(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "border-bottom-color: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";
			
		// .scheme_bdl_color(accent1_hover)
		$out .= ".scheme_bdl_color(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "border-left-color: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";
			
		// .scheme_bdr_color(accent1_hover)
		$out .= ".scheme_bdr_color(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "border-right-color: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";

if (soapery_get_theme_setting('less_compiler')=='less') {
		// .scheme_box_shadow(accent1_hover, ~'inset 0 0 0 110px %c')
		// .scheme_box_shadow(accent1_hover, ~'inset 0 0 0 110px rgba(%r, %g, %b, 0.8)')
		$out .= ".scheme_box_shadow(@color_name, @shadow) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "@c: @@color_var;\n"
					. "@r: red(@c);\n"
					. "@g: green(@c);\n"
					. "@b: blue(@c);\n"
					. "@s1: replace(@shadow, '%c', '@{c}');\n"
					. "@s2: replace(@s1, '%r', '@{r}');\n"
					. "@s3: replace(@s2, '%g', '@{g}');\n"
					. "@s4: replace(@s3, '%b', '@{b}');\n"
					. "-webkit-box-shadow: @s4;\n"
					. "-moz-box-shadow: @s4;\n"
					. "box-shadow: @s4;\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_gradient(accent1, 0.6, 100%, rgba(255,255,255,0), 70%);
		$out .= ".scheme_gradient(@color_name, @color_opacity, @color_percent, @color2, @color2_percent) when (@color_percent <= @color2_percent) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "@c: @@color_var;\n"
					. "@r: red(@c);\n"
					. "@g: green(@c);\n"
					. "@b: blue(@c);\n"
					. "background: -moz-linear-gradient(top, rgba(@r,@g,@b,@color_opacity) @color_percent, @color2 @color2_percent);\n"
					. "background: -webkit-gradient(linear, left top, left bottom, color-stop(@color_percent,rgba(@r,@g,@b,@color_opacity)), color-stop(@color2_percent,@color2));\n"
					. "background: -webkit-linear-gradient(top, rgba(@r,@g,@b,@color_opacity) @color_percent, @color2 @color2_percent);\n"
					. "background: -o-linear-gradient(top, rgba(@r,@g,@b,@color_opacity) @color_percent, @color2 @color2_percent);\n"
					. "background: -ms-linear-gradient(top, rgba(@r,@g,@b,@color_opacity) @color_percent, @color2 @color2_percent);\n"
					. "background: linear-gradient(to bottom, rgba(@r,@g,@b,@color_opacity) @color_percent, @color2 @color2_percent);\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		$out .= ".scheme_gradient(@color_name, @color_opacity, @color_percent, @color2, @color2_percent) when (@color_percent > @color2_percent) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "@c: @@color_var;\n"
					. "@r: red(@c);\n"
					. "@g: green(@c);\n"
					. "@b: blue(@c);\n"
					. "background: -moz-linear-gradient(top, @color2 @color2_percent, rgba(@r,@g,@b,@color_opacity) @color_percent);\n"
					. "background: -webkit-gradient(linear, left top, left bottom, color-stop(@color2_percent,@color2), color-stop(@color_percent,rgba(@r,@g,@b,@color_opacity)));\n"
					. "background: -webkit-linear-gradient(top, @color2 @color2_percent, rgba(@r,@g,@b,@color_opacity) @color_percent);\n"
					. "background: -o-linear-gradient(top, @color2 @color2_percent, rgba(@r,@g,@b,@color_opacity) @color_percent);\n"
					. "background: -ms-linear-gradient(top, @color2 @color2_percent, rgba(@r,@g,@b,@color_opacity) @color_percent);\n"
					. "background: linear-gradient(to bottom, @color2 @color2_percent, rgba(@r,@g,@b,@color_opacity) @color_percent);\n"
					. "}\n";
			}
		}
		$out .= "}\n";
}	// if ($less_compiler == 'less')

		return $out;
	}
}




/* Custom styles
-------------------------------------------------------------------- */

// Prepare core custom styles
if (!function_exists('soapery_core_customizer_add_custom_styles')) {
	//Handler of add_filter( 'soapery_filter_add_styles_inline', 'soapery_core_customizer_add_custom_styles' );
	function soapery_core_customizer_add_custom_styles($custom_style) {

		// Submenu width
		$menu_width = soapery_get_theme_option('menu_width');
		if (!empty($menu_width)) {
			$custom_style .= "
				/* Submenu width */
				.menu_side_nav > li ul,
				.menu_main_nav > li ul {
					width: ".intval($menu_width)."px;
				}
				.menu_side_nav > li > ul ul,
				.menu_main_nav > li > ul ul {
					left:".intval($menu_width+4)."px;
				}
				.menu_side_nav > li > ul ul.submenu_left,
				.menu_main_nav > li > ul ul.submenu_left {
					left:-".intval($menu_width+1)."px;
				}
			";
		}
	
		// Logo height
		$logo_height = soapery_get_custom_option('logo_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo header height */
				.sidebar_outer_logo .logo_main,
				.top_panel_wrap .logo_main,
				.top_panel_wrap .logo_fixed {
					height:".intval($logo_height)."px;
				}
			";
		}
	
		// Logo top offset
		$logo_offset = soapery_get_custom_option('logo_offset');
		if (!empty($logo_offset)) {
			$custom_style .= "
				/* Logo header top offset */
				.top_panel_wrap .logo {
					margin-top:".intval($logo_offset)."px;
				}
			";
		}

		// Logo footer height
		$logo_height = soapery_get_theme_option('logo_footer_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo footer height */
				.contacts_wrap .logo img {
					height:".intval($logo_height)."px;
				}
			";
		}

		// Custom css from theme options
		$custom_style .= soapery_get_custom_option('custom_css');

		return $custom_style;
	}
}




/* Custom scripts
-------------------------------------------------------------------- */

// Add customizer scripts
if (!function_exists('soapery_core_customizer_load_scripts')) {
	function soapery_core_customizer_load_scripts() {
		if (file_exists(soapery_get_file_dir('core/core.customizer/core.customizer.css')))
			wp_enqueue_style( 'soapery-core-customizer-style',	soapery_get_file_url('core/core.customizer/core.customizer.css'), array(), null);
		if (file_exists(soapery_get_file_dir('core/core.customizer/core.customizer.js')))
			wp_enqueue_script( 'soapery-core-customizer-script', soapery_get_file_url('core/core.customizer/core.customizer.js'), array(), null, true );
	}
}


// Prepare javascripts global variables for customizer admin page
if ( !function_exists( 'soapery_core_customizer_prepare_scripts' ) ) {
	function soapery_core_customizer_prepare_scripts() {
        soapery_storage_merge_array('js_vars', 'to_strings', array(
            'scheme_delete' => esc_html__("Delete color scheme", 'soapery'),
            'scheme_delete_confirm' => esc_html__("Do you really want to delete this color scheme?", 'soapery'),
            'scheme_delete_complete' => esc_html__("Current color scheme is successfully deleted!", 'soapery'),
            'scheme_delete_failed' => esc_html__("Error while delete color scheme! Try again later.", 'soapery'),
            'scheme_copy' => esc_html__("Copy color scheme", 'soapery'),
            'scheme_copy_confirm' => esc_html__("Duplicate this color scheme?", 'soapery'),
            'scheme_copy_complete' => esc_html__("Current color scheme is successfully duplicated!", 'soapery'),
            'scheme_copy_failed' => esc_html__("Error while duplicate color scheme! Try again later.", 'soapery')
        ));
	}
}

// Add skin scripts inline
if (!function_exists('soapery_core_customizer_add_scripts_inline')) {
    //Handler of add_filter('soapery_action_add_scripts_inline', 'soapery_core_customizer_add_scripts_inline');
    function soapery_core_customizer_add_scripts_inline($vars=array()) {
        $vars['theme_font'] = soapery_get_custom_font_settings('p', 'font-family');
        $vars['theme_skin_color'] = soapery_get_scheme_color('text_dark');
        $vars['theme_skin_bg_color'] = soapery_get_scheme_color('bg_color');
        return $vars;
    }
}




/* Typography utilities
-------------------------------------------------------------------- */

// Return fonts parameters for customization
if ( !function_exists( 'soapery_get_custom_fonts' ) ) {
	function soapery_get_custom_fonts() {
		return apply_filters('soapery_filter_get_custom_fonts', !soapery_storage_empty('custom_fonts') ? soapery_storage_get('custom_fonts') : array());
	}
}

// Add custom font parameters
if (!function_exists('soapery_add_custom_font')) {
	function soapery_add_custom_font($key, $data) {
		if (soapery_storage_empty('custom_fonts', $key)) soapery_storage_set_array('custom_fonts', $key, $data);
	}
}

// Return one or all font settings
if (!function_exists('soapery_get_custom_font_settings')) {
	function soapery_get_custom_font_settings($key, $param_name='') {
		return soapery_storage_get_array('custom_fonts', $key, $param_name);
	}
}

// Return fonts for css generator
if ( !function_exists( 'soapery_get_custom_fonts_properties' ) ) {
	function soapery_get_custom_fonts_properties() {
		$fnt = soapery_get_custom_fonts();
		$rez = array();
		foreach ($fnt as $k=>$f) {
			foreach ($f as $prop=>$val) {
				if ($prop == 'font-style') {
					if (soapery_strpos($val, 'i')!==false)
						$rez[$k.'_fl'] = 'italic';
					if (soapery_strpos($val, 'u')!==false)
						$rez[$k.'_td'] = 'underline';
				} else {
					$p = str_replace(
						array(
							'font-family',
							'font-size',
							'font-weight',
							'line-height',
							'margin-top',
							'margin-bottom'
						),
						array(
							'ff', 'fs', 'fw', 'lh', 'mt', 'mb'
						),
						$prop);
					$rez[$k.'_'.$p] = $val ? $val : 'inherit';
				}
			}
		}
		return $rez;
	}
}

// Return fonts for css generator
if ( !function_exists( 'soapery_get_custom_font_css' ) ) {
	function soapery_get_custom_font_css($fnt) {
		$css = '';
		$fnt = soapery_storage_get_array('custom_fonts', $fnt);
		if (is_array($fnt)) {
			foreach ($fnt as $prop=>$val) {
				if (empty($val) || (soapery_strpos($prop, 'font-')===false && soapery_strpos($prop, 'line-')===false)) continue;
				if ($prop=='font-style') {
					if (soapery_strpos($val, 'i')!==false)
						$css .= ($css ? ';' : '') . $prop . ':italic';
					if (soapery_strpos($val, 'u')!==false)
						$css .= ($css ? ';' : '') . 'text_decoration:underline';
				} else
					$css .= ($css ? ';' : '') . $prop . ':' . $val;
			}
		}
		return $css;
	}
}

// Return fonts for css generator
if ( !function_exists( 'soapery_get_custom_margins_css' ) ) {
	function soapery_get_custom_margins_css($fnt) {
		$css = '';
		$fnt = soapery_storage_get_array('custom_fonts', $fnt);
		if (is_array($fnt)) {
			foreach ($fnt as $prop=>$val) {
				if (empty($val) || soapery_strpos($prop, 'margin-')===false) continue;
				$css .= ($css ? ';' : '') . $prop . ':' . $val;
			}
		}
		return $css;
	}
}






/* Color Scheme utilities
-------------------------------------------------------------------- */

// Add color scheme
if (!function_exists('soapery_add_color_scheme')) {
	function soapery_add_color_scheme($key, $data) {
		if (soapery_storage_empty('custom_colors', $key)) soapery_storage_set_array('custom_colors', $key, $data);
	}
}

// Return color schemes
if ( !function_exists( 'soapery_get_custom_colors' ) ) {
	function soapery_get_custom_colors() {
		return apply_filters('soapery_filter_get_custom_colors', !soapery_storage_empty('custom_colors') ? soapery_storage_get('custom_colors') : array());
	}
}

// Return color schemes list, prepended inherit
if ( !function_exists( 'soapery_get_list_color_schemes' ) ) {
	function soapery_get_list_color_schemes($prepend_inherit=false) {
		$list = array();
		$colors = soapery_storage_get('custom_colors');
		if (!empty($colors) && is_array($colors)) {
			foreach ($colors as $k=>$v) {
				$list[$k] = $v['title'];
			}
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return scheme color
if (!function_exists('soapery_get_scheme_color')) {
	function soapery_get_scheme_color($clr_name='', $clr='') {
		if (empty($clr)) {
			$scheme = soapery_get_custom_option('body_scheme');
			if (empty($scheme) || soapery_storage_empty('custom_colors', $scheme)) $scheme = 'original';
			$clr = soapery_storage_get_array('custom_colors', $scheme, $clr_name);
		}
		return apply_filters('soapery_filter_get_scheme_color', $clr, $clr_name, $scheme);
	}
}

// Return scheme colors
if (!function_exists('soapery_get_scheme_colors')) {
	function soapery_get_scheme_colors($scheme='') {
		$scheme = soapery_get_custom_option('body_scheme');
		if (empty($scheme) || soapery_storage_empty('custom_colors', $scheme)) $scheme = 'original';
		return soapery_storage_get_array('custom_colors', $scheme);
	}
}
?>