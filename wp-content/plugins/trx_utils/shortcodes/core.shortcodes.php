<?php
/**
 * Soapery Framework: shortcodes manipulations
 *
 * @package	soapery
 * @since	soapery 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('soapery_sc_theme_setup')) {
	add_action( 'soapery_action_init_theme', 'soapery_sc_theme_setup', 1 );
	function soapery_sc_theme_setup() {
		// Add sc stylesheets
		add_action('soapery_action_add_styles', 'soapery_sc_add_styles', 1);
	}
}

if (!function_exists('soapery_sc_theme_setup2')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_theme_setup2' );
	function soapery_sc_theme_setup2() {

		if ( !is_admin() || isset($_POST['action']) ) {
			// Enable/disable shortcodes in excerpt
			add_filter('the_excerpt', 					'soapery_sc_excerpt_shortcodes');
	
			// Prepare shortcodes in the content
			if (function_exists('soapery_sc_prepare_content')) soapery_sc_prepare_content();
		}

		// Add init script into shortcodes output in VC frontend editor
		add_filter('soapery_shortcode_output', 'soapery_sc_add_scripts', 10, 4);

		// AJAX: Send contact form data
		add_action('wp_ajax_send_form',			'soapery_sc_form_send');
		add_action('wp_ajax_nopriv_send_form',	'soapery_sc_form_send');

		// Show shortcodes list in admin editor
		add_action('media_buttons',				'soapery_sc_selector_add_in_toolbar', 11);

	}
}


// Register shortcodes styles
if ( !function_exists( 'soapery_sc_add_styles' ) ) {
	//add_action('soapery_action_add_styles', 'soapery_sc_add_styles', 1);
	function soapery_sc_add_styles() {
		// Shortcodes
		wp_enqueue_style( 'soapery-shortcodes-style',	trx_utils_get_file_url('shortcodes/theme.shortcodes.css'), array(), null );
	}
}


// Register shortcodes init scripts
if ( !function_exists( 'soapery_sc_add_scripts' ) ) {
	//add_filter('soapery_shortcode_output', 'soapery_sc_add_scripts', 10, 4);
	function soapery_sc_add_scripts($output, $tag='', $atts=array(), $content='') {

		if (soapery_storage_empty('shortcodes_scripts_added')) {
			soapery_storage_set('shortcodes_scripts_added', true);
			//wp_enqueue_style( 'soapery-shortcodes-style', trx_utils_get_file_url('shortcodes/theme.shortcodes.css'), array(), null );
			wp_enqueue_script( 'soapery-shortcodes-script', trx_utils_get_file_url('shortcodes/theme.shortcodes.js'), array('jquery'), null, true );
		}
		
		return $output;
	}
}


/* Prepare text for shortcodes
-------------------------------------------------------------------------------- */

// Prepare shortcodes in content
if (!function_exists('soapery_sc_prepare_content')) {
	function soapery_sc_prepare_content() {
		if (function_exists('soapery_sc_clear_around')) {
			$filters = array(
				array('soapery', 'sc', 'clear', 'around'),
				array('widget', 'text'),
				array('the', 'excerpt'),
				array('the', 'content')
			);
			if (function_exists('soapery_exists_woocommerce') && soapery_exists_woocommerce()) {
				$filters[] = array('woocommerce', 'template', 'single', 'excerpt');
				$filters[] = array('woocommerce', 'short', 'description');
			}
			if (is_array($filters) && count($filters) > 0) {
				foreach ($filters as $flt)
					add_filter(join('_', $flt), 'soapery_sc_clear_around', 1);	// Priority 1 to clear spaces before do_shortcodes()
			}
		}
	}
}

// Enable/Disable shortcodes in the excerpt
if (!function_exists('soapery_sc_excerpt_shortcodes')) {
	function soapery_sc_excerpt_shortcodes($content) {
		if (!empty($content)) {
			$content = do_shortcode($content);
			//$content = strip_shortcodes($content);
		}
		return $content;
	}
}



/*
// Remove spaces and line breaks between close and open shortcode brackets ][:
[trx_columns]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
[/trx_columns]

convert to

[trx_columns][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][/trx_columns]
*/
if (!function_exists('soapery_sc_clear_around')) {
	function soapery_sc_clear_around($content) {
		if (!empty($content)) $content = preg_replace("/\](\s|\n|\r)*\[/", "][", $content);
		return $content;
	}
}






/* Shortcodes support utils
---------------------------------------------------------------------- */

// Soapery shortcodes load scripts
if (!function_exists('soapery_sc_load_scripts')) {
	function soapery_sc_load_scripts() {
		wp_enqueue_script( 'soapery-shortcodes_admin-script', trx_utils_get_file_url('shortcodes/shortcodes_admin.js'), array('jquery'), null, true );
		wp_enqueue_script( 'soapery-selection-script',  soapery_get_file_url('js/jquery.selection.js'), array('jquery'), null, true );
		wp_localize_script( 'soapery-shortcodes_admin-script', 'SOAPERY_SHORTCODES_DATA', soapery_storage_get('shortcodes') );
	}
}

// Soapery shortcodes prepare scripts
if (!function_exists('soapery_sc_prepare_scripts')) {
	function soapery_sc_prepare_scripts() {
		if (!soapery_storage_isset('shortcodes_prepared')) {
			soapery_storage_set('shortcodes_prepared', true);
			?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					SOAPERY_STORAGE['shortcodes_cp'] = '<?php echo is_admin() ? (!soapery_storage_empty('to_colorpicker') ? soapery_storage_get('to_colorpicker') : 'wp') : 'custom'; ?>';	// wp | tiny | custom
				});
			</script>
			<?php
		}
	}
}

// Show shortcodes list in admin editor
if (!function_exists('soapery_sc_selector_add_in_toolbar')) {
	//add_action('media_buttons','soapery_sc_selector_add_in_toolbar', 11);
	function soapery_sc_selector_add_in_toolbar(){

		if ( !soapery_options_is_used() ) return;

		soapery_sc_load_scripts();
		soapery_sc_prepare_scripts();

		$shortcodes = soapery_storage_get('shortcodes');
		$shortcodes_list = '<select class="sc_selector"><option value="">&nbsp;'.esc_html__('- Select Shortcode -', 'soapery').'&nbsp;</option>';

		if (is_array($shortcodes) && count($shortcodes) > 0) {
			foreach ($shortcodes as $idx => $sc) {
				$shortcodes_list .= '<option value="'.esc_attr($idx).'" title="'.esc_attr($sc['desc']).'">'.esc_html($sc['title']).'</option>';
			}
		}

		$shortcodes_list .= '</select>';

		soapery_show_layout($shortcodes_list);
	}
}

// Soapery shortcodes builder settings
require_once trx_utils_get_file_dir('shortcodes/shortcodes_settings.php');

// VC shortcodes settings
if ( class_exists('WPBakeryShortCode') ) {
    require_once trx_utils_get_file_dir('shortcodes/shortcodes_vc.php');
}

// Soapery shortcodes implementation
// Using require_once trx_utils_get_file_dir(), because shortcodes can be replaced in the child theme
require_once trx_utils_get_file_dir('shortcodes/trx_basic/anchor.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/audio.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/blogger.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/br.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/call_to_action.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/chat.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/columns.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/content.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/form.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/googlemap.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/hide.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/image.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/infobox.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/line.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/list.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/price_block.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/promo.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/quote.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/reviews.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/section.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/skills.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/slider.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/socials.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/table.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/title.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/twitter.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/video.php');

require_once trx_utils_get_file_dir('shortcodes/trx_optional/accordion.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/button.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/countdown.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/dropcaps.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/gap.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/highlight.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/icon.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/number.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/parallax.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/popup.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/price.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/search.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/tabs.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/toggles.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/tooltip.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/zoom.php');

?>