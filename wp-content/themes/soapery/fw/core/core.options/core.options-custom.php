<?php
/**
 * Soapery Framework: Theme options custom fields
 *
 * @package	soapery
 * @since	soapery 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'soapery_options_custom_theme_setup' ) ) {
	add_action( 'soapery_action_before_init_theme', 'soapery_options_custom_theme_setup' );
	function soapery_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'soapery_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'soapery_options_custom_load_scripts' ) ) {
	//Handler of add_action("admin_enqueue_scripts", 'soapery_options_custom_load_scripts');
	function soapery_options_custom_load_scripts() {
		wp_enqueue_script( 'soapery-options-custom-script',	soapery_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );
	}
}


// Show theme specific fields in Post (and Page) options
if ( !function_exists( 'soapery_show_custom_field' ) ) {
	function soapery_show_custom_field($id, $field, $value) {
		$output = '';
		switch ($field['type']) {
			case 'reviews':
				$output .= '<div class="reviews_block">' . trim(soapery_reviews_get_markup($field, $value, true)) . '</div>';
				break;
	
			case 'mediamanager':
				wp_enqueue_media( );
				$output .= '<a id="'.esc_attr($id).'" class="button mediamanager soapery_media_selector"
					data-param="' . esc_attr($id) . '"
					data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'soapery') : esc_html__( 'Choose Image', 'soapery')).'"
					data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'soapery') : esc_html__( 'Choose Image', 'soapery')).'"
					data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
					data-linked-field="'.esc_attr($field['media_field_id']).'"
					>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'soapery') : esc_html__( 'Choose Image', 'soapery')) . '</a>';
				break;
		}
		return apply_filters('soapery_filter_show_custom_field', $output, $id, $field, $value);
	}
}
?>