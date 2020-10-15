<?php
/* Gutenberg support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('soapery_gutenberg_theme_setup')) {
    add_action( 'soapery_action_before_init_theme', 'soapery_gutenberg_theme_setup', 1 );
    function soapery_gutenberg_theme_setup() {
        if (is_admin()) {
            add_filter( 'soapery_filter_required_plugins', 'soapery_gutenberg_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'soapery_exists_gutenberg' ) ) {
    function soapery_exists_gutenberg() {
        return function_exists( 'the_gutenberg_project' ) && function_exists( 'register_block_type' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'soapery_gutenberg_required_plugins' ) ) {
    //add_filter('soapery_filter_required_plugins',    'soapery_gutenberg_required_plugins');
    function soapery_gutenberg_required_plugins($list=array()) {
        if (in_array('gutenberg', (array)soapery_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('Gutenberg', 'soapery'),
                'slug'         => 'gutenberg',
                'required'     => false
            );
        return $list;
    }
}