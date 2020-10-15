<?php
if (is_admin() 
		|| (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true' )
		|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline')
	) {
    require_once trx_utils_get_file_dir('shortcodes/shortcodes_vc_classes.php');
}

// Width and height params
if ( !function_exists( 'soapery_vc_width' ) ) {
	function soapery_vc_width($w='') {
		return array(
			"param_name" => "width",
			"heading" => esc_html__("Width", 'soapery'),
			"description" => wp_kses_data( __("Width of the element", 'soapery') ),
			"group" => esc_html__('Size &amp; Margins', 'soapery'),
			"value" => $w,
			"type" => "textfield"
		);
	}
}
if ( !function_exists( 'soapery_vc_height' ) ) {
	function soapery_vc_height($h='') {
		return array(
			"param_name" => "height",
			"heading" => esc_html__("Height", 'soapery'),
			"description" => wp_kses_data( __("Height of the element", 'soapery') ),
			"group" => esc_html__('Size &amp; Margins', 'soapery'),
			"value" => $h,
			"type" => "textfield"
		);
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'soapery_shortcodes_vc_scripts_admin' ) ) {
	//add_action( 'admin_enqueue_scripts', 'soapery_shortcodes_vc_scripts_admin' );
	function soapery_shortcodes_vc_scripts_admin() {
		// Include CSS 
		wp_enqueue_style ( 'shortcodes_vc_admin-style', trx_utils_get_file_url('shortcodes/theme.shortcodes_vc_admin.css'), array(), null );
		// Include JS
		wp_enqueue_script( 'shortcodes_vc_admin-script', trx_utils_get_file_url('shortcodes/shortcodes_vc_admin.js'), array('jquery'), null, true );
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'soapery_shortcodes_vc_scripts_front' ) ) {
	//add_action( 'wp_enqueue_scripts', 'soapery_shortcodes_vc_scripts_front' );
	function soapery_shortcodes_vc_scripts_front() {
		if (soapery_vc_is_frontend()) {
			// Include CSS 
			wp_enqueue_style ( 'shortcodes_vc_front-style', trx_utils_get_file_url('shortcodes/theme.shortcodes_vc_front.css'), array(), null );
			// Include JS
			wp_enqueue_script( 'shortcodes_vc_front-script', trx_utils_get_file_url('shortcodes/shortcodes_vc_front.js'), array('jquery'), null, true );
			wp_enqueue_script( 'shortcodes_vc_theme-script', trx_utils_get_file_url('shortcodes/theme.shortcodes_vc_front.js'), array('jquery'), null, true );
		}
	}
}

// Add init script into shortcodes output in VC frontend editor
if ( !function_exists( 'soapery_shortcodes_vc_add_init_script' ) ) {
	//add_filter('soapery_shortcode_output', 'soapery_shortcodes_vc_add_init_script', 10, 4);
	function soapery_shortcodes_vc_add_init_script($output, $tag='', $atts=array(), $content='') {
		if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')
				&& ( isset($_POST['shortcodes'][0]['tag']) && $_POST['shortcodes'][0]['tag']==$tag )
		) {
			if (soapery_strpos($output, 'soapery_vc_init_shortcodes')===false) {
				$id = "soapery_vc_init_shortcodes_".str_replace('.', '', mt_rand());
				$output .= '
					<script id="'.esc_attr($id).'">
						try {
							soapery_init_post_formats();
							soapery_init_shortcodes(jQuery("body").eq(0));
							soapery_scroll_actions();
						} catch (e) { };
					</script>
				';
			}
		}
		return $output;
	}
}

// Return vc_param value
if ( !function_exists( 'soapery_get_vc_param' ) ) {
	function soapery_get_vc_param($prm) {
		return soapery_storage_get_array('vc_params', $prm);
	}
}

// Set vc_param value
if ( !function_exists( 'soapery_set_vc_param' ) ) {
	function soapery_set_vc_param($prm, $val) {
		soapery_storage_set_array('vc_params', $prm, $val);
	}
}


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'soapery_shortcodes_vc_theme_setup' ) ) {
	//if ( soapery_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'soapery_action_before_init_theme', 'soapery_shortcodes_vc_theme_setup', 20 );
	else
		add_action( 'soapery_action_after_init_theme', 'soapery_shortcodes_vc_theme_setup' );
	function soapery_shortcodes_vc_theme_setup() {


		// Set dir with theme specific VC shortcodes
		if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
			vc_set_shortcodes_templates_dir( soapery_get_folder_dir('shortcodes/vc' ) );
		}
		
		// Add/Remove params in the standard VC shortcodes
		vc_add_param("vc_row", array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'soapery'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'soapery') ),
					"group" => esc_html__('Color scheme', 'soapery'),
					"class" => "",
					"value" => array_flip(soapery_get_list_color_schemes(true)),
					"type" => "dropdown"
		));
		vc_add_param("vc_row", array(
					"param_name" => "inverse",
					"heading" => esc_html__("Inverse colors", 'soapery'),
					"description" => wp_kses_data( __("Inverse all colors of this block", 'soapery') ),
					"group" => esc_html__('Color scheme', 'soapery'),
					"class" => "",
					"std" => "no",
					"value" => array(esc_html__('Inverse colors', 'soapery') => 'yes'),
					"type" => "checkbox"
		));

		if (soapery_shortcodes_is_used() && class_exists('SOAPERY_VC_ShortCodeSingle')) {

			// Set VC as main editor for the theme
			vc_set_as_theme( true );
			
			// Enable VC on follow post types
			vc_set_default_editor_post_types( array('page', 'team') );
			
			// Disable frontend editor
			//vc_disable_frontend();

			// Load scripts and styles for VC support
			add_action( 'wp_enqueue_scripts',		'soapery_shortcodes_vc_scripts_front');
			add_action( 'admin_enqueue_scripts',	'soapery_shortcodes_vc_scripts_admin' );

			// Add init script into shortcodes output in VC frontend editor
			add_filter('soapery_shortcode_output', 'soapery_shortcodes_vc_add_init_script', 10, 4);

			// Remove standard VC shortcodes
			vc_remove_element("vc_button");
			vc_remove_element("vc_posts_slider");
			vc_remove_element("vc_gmaps");
			vc_remove_element("vc_teaser_grid");
			vc_remove_element("vc_progress_bar");
//			vc_remove_element("vc_facebook");
//			vc_remove_element("vc_tweetmeme");
//			vc_remove_element("vc_googleplus");
//			vc_remove_element("vc_facebook");
//			vc_remove_element("vc_pinterest");
			vc_remove_element("vc_message");
			vc_remove_element("vc_posts_grid");
//			vc_remove_element("vc_carousel");
//			vc_remove_element("vc_flickr");
			vc_remove_element("vc_tour");
//			vc_remove_element("vc_separator");
//			vc_remove_element("vc_single_image");
			vc_remove_element("vc_cta_button");
//			vc_remove_element("vc_accordion");
//			vc_remove_element("vc_accordion_tab");
			vc_remove_element("vc_toggle");
			vc_remove_element("vc_tabs");
			vc_remove_element("vc_tab");
//			vc_remove_element("vc_images_carousel");
			
			// Remove standard WP widgets
			vc_remove_element("vc_wp_archives");
			vc_remove_element("vc_wp_calendar");
			vc_remove_element("vc_wp_categories");
			vc_remove_element("vc_wp_custommenu");
			vc_remove_element("vc_wp_links");
			vc_remove_element("vc_wp_meta");
			vc_remove_element("vc_wp_pages");
			vc_remove_element("vc_wp_posts");
			vc_remove_element("vc_wp_recentcomments");
			vc_remove_element("vc_wp_rss");
			vc_remove_element("vc_wp_search");
			vc_remove_element("vc_wp_tagcloud");
			vc_remove_element("vc_wp_text");
			
			
			soapery_storage_set('vc_params', array(
				
				// Common arrays and strings
				'category' => esc_html__("Soapery shortcodes", 'soapery'),
			
				// Current element id
				'id' => array(
					"param_name" => "id",
					"heading" => esc_html__("Element ID", 'soapery'),
					"description" => wp_kses_data( __("ID for the element", 'soapery') ),
					"group" => esc_html__('ID &amp; Class', 'soapery'),
					"value" => "",
					"type" => "textfield"
				),
			
				// Current element class
				'class' => array(
					"param_name" => "class",
					"heading" => esc_html__("Element CSS class", 'soapery'),
					"description" => wp_kses_data( __("CSS class for the element", 'soapery') ),
					"group" => esc_html__('ID &amp; Class', 'soapery'),
					"value" => "",
					"type" => "textfield"
				),

				// Current element animation
				'animation' => array(
					"param_name" => "animation",
					"heading" => esc_html__("Animation", 'soapery'),
					"description" => wp_kses_data( __("Select animation while object enter in the visible area of page", 'soapery') ),
					"group" => esc_html__('ID &amp; Class', 'soapery'),
					"class" => "",
					"value" => array_flip(soapery_get_sc_param('animations')),
					"type" => "dropdown"
				),
			
				// Current element style
				'css' => array(
					"param_name" => "css",
					"heading" => esc_html__("CSS styles", 'soapery'),
					"description" => wp_kses_data( __("Any additional CSS rules (if need)", 'soapery') ),
					"group" => esc_html__('ID &amp; Class', 'soapery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				// Margins params
				'margin_top' => array(
					"param_name" => "top",
					"heading" => esc_html__("Top margin", 'soapery'),
					"description" => wp_kses_data( __("Margin above this shortcode", 'soapery') ),
					"group" => esc_html__('Size &amp; Margins', 'soapery'),
					"std" => "inherit",
					"value" => array_flip(soapery_get_sc_param('margins')),
					"type" => "dropdown"
				),
			
				'margin_bottom' => array(
					"param_name" => "bottom",
					"heading" => esc_html__("Bottom margin", 'soapery'),
					"description" => wp_kses_data( __("Margin below this shortcode", 'soapery') ),
					"group" => esc_html__('Size &amp; Margins', 'soapery'),
					"std" => "inherit",
					"value" => array_flip(soapery_get_sc_param('margins')),
					"type" => "dropdown"
				),
			
				'margin_left' => array(
					"param_name" => "left",
					"heading" => esc_html__("Left margin", 'soapery'),
					"description" => wp_kses_data( __("Margin on the left side of this shortcode", 'soapery') ),
					"group" => esc_html__('Size &amp; Margins', 'soapery'),
					"std" => "inherit",
					"value" => array_flip(soapery_get_sc_param('margins')),
					"type" => "dropdown"
				),
				
				'margin_right' => array(
					"param_name" => "right",
					"heading" => esc_html__("Right margin", 'soapery'),
					"description" => wp_kses_data( __("Margin on the right side of this shortcode", 'soapery') ),
					"group" => esc_html__('Size &amp; Margins', 'soapery'),
					"std" => "inherit",
					"value" => array_flip(soapery_get_sc_param('margins')),
					"type" => "dropdown"
				)
			) );
			
			// Add theme-specific shortcodes
			do_action('soapery_action_shortcodes_list_vc');

		}
	}
}

// Prevent simultaneous editing of posts for Gutenberg and other PageBuilders (VC, Elementor)
if ( ! function_exists( 'trx_utils_gutenberg_disable_cpt' ) ) {
    add_action( 'current_screen', 'trx_utils_gutenberg_disable_cpt' );
    function trx_utils_gutenberg_disable_cpt() {
        $safe_pb = array();
        if ( !empty($safe_pb) && function_exists( 'the_gutenberg_project' ) && function_exists( 'register_block_type' ) ) {
            $current_post_type = get_current_screen()->post_type;
            $disable = false;
            if ( !$disable && in_array('vc', $safe_pb) && function_exists('vc_editor_post_types') ) {
                $post_types = vc_editor_post_types();
                $disable = is_array($post_types) && in_array($current_post_type, $post_types);
            }
            if ( $disable ) {
                remove_filter( 'replace_editor', 'gutenberg_init' );
                remove_action( 'load-post.php', 'gutenberg_intercept_edit_post' );
                remove_action( 'load-post-new.php', 'gutenberg_intercept_post_new' );
                remove_action( 'admin_init', 'gutenberg_add_edit_link_filters' );
                remove_filter( 'admin_url', 'gutenberg_modify_add_new_button_url' );
                remove_action( 'admin_print_scripts-edit.php', 'gutenberg_replace_default_add_new_button' );
                remove_action( 'admin_enqueue_scripts', 'gutenberg_editor_scripts_and_styles' );
                remove_filter( 'screen_options_show_screen', '__return_false' );
            }
        }
    }
}
?>