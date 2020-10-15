<?php
/**
 * Skin file for the theme.
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('soapery_action_skin_theme_setup')) {
	add_action( 'soapery_action_init_theme', 'soapery_action_skin_theme_setup', 1 );
	function soapery_action_skin_theme_setup() {

		// Add skin fonts in the used fonts list
		add_filter('soapery_filter_used_fonts',			'soapery_filter_skin_used_fonts');
		// Add skin fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('soapery_filter_list_fonts',			'soapery_filter_skin_list_fonts');

		// Add skin stylesheets
		add_action('soapery_action_add_styles',			'soapery_action_skin_add_styles');
		// Add skin inline styles
		add_filter('soapery_filter_add_styles_inline',		'soapery_filter_skin_add_styles_inline');
		// Add skin responsive styles
		add_action('soapery_action_add_responsive',		'soapery_action_skin_add_responsive');
		// Add skin responsive inline styles
		add_filter('soapery_filter_add_responsive_inline',	'soapery_filter_skin_add_responsive_inline');

		// Add skin scripts
		add_action('soapery_action_add_scripts',			'soapery_action_skin_add_scripts');
		// Add skin scripts inline
		add_filter('soapery_action_add_scripts_inline',	'soapery_action_skin_add_scripts_inline');

		// Add skin less files into list for compilation
		add_filter('soapery_filter_compile_less',			'soapery_filter_skin_compile_less');


		/* Color schemes
		
		// Accenterd colors
		accent1			- theme accented color 1
		accent1_hover	- theme accented color 1 (hover state)
		accent2			- theme accented color 2
		accent2_hover	- theme accented color 2 (hover state)		
		accent3			- theme accented color 3
		accent3_hover	- theme accented color 3 (hover state)		
		
		// Headers, text and links
		text			- main content
		text_light		- post info
		text_dark		- headers
		inverse_text	- text on accented background
		inverse_light	- post info on accented background
		inverse_dark	- headers on accented background
		inverse_link	- links on accented background
		inverse_hover	- hovered links on accented background
		
		// Block's border and background
		bd_color		- border for the entire block
		bg_color		- background color for the entire block
		bg_image, bg_image_position, bg_image_repeat, bg_image_attachment  - first background image for the entire block
		bg_image2,bg_image2_position,bg_image2_repeat,bg_image2_attachment - second background image for the entire block
		
		// Alternative colors - highlight blocks, form fields, etc.
		alter_text		- text on alternative background
		alter_light		- post info on alternative background
		alter_dark		- headers on alternative background
		alter_link		- links on alternative background
		alter_hover		- hovered links on alternative background
		alter_bd_color	- alternative border
		alter_bd_hover	- alternative border for hovered state or active field
		alter_bg_color	- alternative background
		alter_bg_hover	- alternative background for hovered state or active field 
		alter_bg_image, alter_bg_image_position, alter_bg_image_repeat, alter_bg_image_attachment - background image for the alternative block
		
		*/

		// Add color schemes
		soapery_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'soapery'),

			// Accent colors
			'accent1'				=> '#9ed8e5',
			'accent1_hover'			=> '#42c1dd',
			'accent2'				=> '#f5d4af',
			'accent2_hover'			=> '#f5d4af',
			'accent3'				=> '#b9b0dd',
			'accent3_hover'			=> '#b9b0dd',
			'accent4'				=> '#ffb4b5',
			'accent4_hover'			=> '#ffb4b5',
			'light-green'			=> '#ade1c5',
			'rose'					=> '#f3cfdd',
			
			// Headers, text and links colors
			'text'					=> '#7e8485',
			'text_light'			=> '#a5a9a9',
			'text_dark'				=> '#4b5354',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
			
			// Whole block border and background
			'bd_color'				=> '#dbdddd',
			'bg_color'				=> '#ffffff',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#939cb3',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#189799',
			'alter_bd_color'		=> '#dddddd',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#f5f7fa',
			'alter_bg_hover'		=> '#f0f0f0',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);


		/* Font slugs:
		h1 ... h6	- headers
		p			- plain text
		link		- links
		info		- info blocks (Posted 15 May, 2015 by John Doe)
		menu		- main menu
		submenu		- dropdown menus
		logo		- logo text
		button		- button's caption
		input		- input fields
		*/

		// Add Custom fonts
		soapery_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'soapery'),
			'description'	=> '',
			'font-family'	=> 'Josefin Sans',
			'font-size' 	=> '3.571em',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '',
			'margin-top'	=> '0.15em',
			'margin-bottom'	=> '0.15em'
			)
		);
		soapery_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'soapery'),
			'description'	=> '',
			'font-family'	=> 'Josefin Sans',
			'font-size' 	=> '3.214em',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '',
			'margin-top'	=> '0.18em',
			'margin-bottom'	=> '0.18em'
			)
		);
		soapery_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'soapery'),
			'description'	=> '',
			'font-family'	=> 'Josefin Sans',
			'font-size' 	=> '2.571em',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '',
			'margin-top'	=> '0.36em',
			'margin-bottom'	=> '0.36em'
			)
		);
		soapery_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'soapery'),
			'description'	=> '',
			'font-family'	=> 'Josefin Sans',
			'font-size' 	=> '2.143em',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '',
			'margin-top'	=> '0.56em',
			'margin-bottom'	=> '0.56em'
			)
		);
		soapery_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'soapery'),
			'description'	=> '',
			'font-family'	=> 'Josefin Sans',
			'font-size' 	=> '1.714em',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '',
			'margin-top'	=> '0.79em',
			'margin-bottom'	=> '0.79em'
			)
		);
		soapery_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'soapery'),
			'description'	=> '',
			'font-family'	=> 'Josefin Sans',
			'font-size' 	=> '1em',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '',
			'margin-top'	=> '0.95em',
			'margin-bottom'	=> '0.95em'
			)
		);
		soapery_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'soapery'),
			'description'	=> '',
			'font-family'	=> 'Lato',
			'font-size' 	=> '14px',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.75em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		soapery_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'soapery'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		soapery_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'soapery'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1em',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.5em'
			)
		);
		soapery_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'soapery'),
			'description'	=> '',
			'font-family'	=> 'Josefin Sans',
			'font-size' 	=> '1em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '',
			'margin-top'	=> '1.2em',
			'margin-bottom'	=> '1.2em'
			)
		);
		soapery_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'soapery'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> ''
			)
		);
		soapery_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'soapery'),
			'description'	=> '',
			'font-family'	=> 'Josefin Sans',
			'font-size' 	=> '2.8571em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '0.9em',
			'margin-top'	=> '2em',
			'margin-bottom'	=> '0.75em'
			)
		);
		soapery_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'soapery'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);
		soapery_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'soapery'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Skin's fonts
//------------------------------------------------------------------------------

// Add skin fonts in the used fonts list
if (!function_exists('soapery_filter_skin_used_fonts')) {
	//Handler of add_filter('soapery_filter_used_fonts', 'soapery_filter_skin_used_fonts');
	function soapery_filter_skin_used_fonts($theme_fonts) {
		$theme_fonts['Lato'] = 1;
		$theme_fonts['Josefin Sans'] = 1;
		return $theme_fonts;
	}
}

if (!function_exists('soapery_filter_skin_list_fonts')) {
	function soapery_filter_skin_list_fonts($list) {
		if (!isset($list['Lato']))	$list['Lato'] = array('family'=>'sans-serif','link'=>'Lato:400,400italic');
		if (!isset($list['Josefin Sans']))	$list['Josefin Sans'] = array('family'=>'sans-serif','link'=>'Josefin+Sans:400,300,600,700');
		return $list;
	}
}


//------------------------------------------------------------------------------
// Skin's stylesheets
//------------------------------------------------------------------------------
// Add skin stylesheets
if (!function_exists('soapery_action_skin_add_styles')) {
	//Handler of add_action('soapery_action_add_styles', 'soapery_action_skin_add_styles');
	function soapery_action_skin_add_styles() {
		// Add stylesheet files
		wp_enqueue_style( 'soapery-skin-style', soapery_get_file_url('skin.css'), array(), null );
		if (file_exists(soapery_get_file_dir('skin.customizer.css')))
			wp_enqueue_style( 'soapery-skin-customizer-style', soapery_get_file_url('skin.customizer.css'), array(), null );
	}
}

// Add skin inline styles
if (!function_exists('soapery_filter_skin_add_styles_inline')) {
	//Handler of add_filter('soapery_filter_add_styles_inline', 'soapery_filter_skin_add_styles_inline');
	function soapery_filter_skin_add_styles_inline($custom_style) {
		// Todo: add skin specific styles in the $custom_style to override
		//       rules from style.css and shortcodes.css
		// Example:
		//		$scheme = soapery_get_custom_option('body_scheme');
		//		if (empty($scheme)) $scheme = 'original';
		//		$clr = soapery_get_scheme_color('accent1');
		//		if (!empty($clr)) {
		// 			$custom_style .= '
		//				a,
		//				.bg_tint_light a,
		//				.top_panel .content .search_wrap.search_style_regular .search_form_wrap .search_submit,
		//				.top_panel .content .search_wrap.search_style_regular .search_icon,
		//				.search_results .post_more,
		//				.search_results .search_results_close {
		//					color:'.esc_attr($clr).';
		//				}
		//			';
		//		}
		return $custom_style;	
	}
}

// Add skin responsive styles
if (!function_exists('soapery_action_skin_add_responsive')) {
	//Handler of add_action('soapery_action_add_responsive', 'soapery_action_skin_add_responsive');
	function soapery_action_skin_add_responsive() {
		$suffix = soapery_param_is_off(soapery_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
		if (file_exists(soapery_get_file_dir('skin.responsive'.($suffix).'.css'))) 
			wp_enqueue_style( 'theme-skin-responsive-style', soapery_get_file_url('skin.responsive'.($suffix).'.css'), array(), null );
	}
}

// Add skin responsive inline styles
if (!function_exists('soapery_filter_skin_add_responsive_inline')) {
	//Handler of add_filter('soapery_filter_add_responsive_inline', 'soapery_filter_skin_add_responsive_inline');
	function soapery_filter_skin_add_responsive_inline($custom_style) {
		return $custom_style;	
	}
}

// Add skin.less into list files for compilation
if (!function_exists('soapery_filter_skin_compile_less')) {
	//Handler of add_filter('soapery_filter_compile_less', 'soapery_filter_skin_compile_less');
	function soapery_filter_skin_compile_less($files) {
		if (file_exists(soapery_get_file_dir('skin.less'))) {
		 	$files[] = soapery_get_file_dir('skin.less');
		}
		return $files;	
	}
}



//------------------------------------------------------------------------------
// Skin's scripts
//------------------------------------------------------------------------------

// Add skin scripts
if (!function_exists('soapery_action_skin_add_scripts')) {
	//Handler of add_action('soapery_action_add_scripts', 'soapery_action_skin_add_scripts');
	function soapery_action_skin_add_scripts() {
		if (file_exists(soapery_get_file_dir('skin.js')))
			wp_enqueue_script( 'theme-skin-script', soapery_get_file_url('skin.js'), array(), null, true );
		if (soapery_get_theme_option('show_theme_customizer') == 'yes' && file_exists(soapery_get_file_dir('skin.customizer.js')))
			wp_enqueue_script( 'theme-skin-customizer-script', soapery_get_file_url('skin.customizer.js'), array(), null );
	}
}

// Add skin scripts inline
if (!function_exists('soapery_action_skin_add_scripts_inline')) {
    //Handler of add_filter('soapery_action_add_scripts_inline', 'soapery_action_skin_add_scripts_inline');
    function soapery_action_skin_add_scripts_inline($vars=array()) {
        // Todo: add skin specific script's vars
        return $vars;
    }
}
?>