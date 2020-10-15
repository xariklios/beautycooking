<?php
/**
 * Soapery Framework
 *
 * @package soapery
 * @since soapery 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'SOAPERY_FW_DIR' ) )		define( 'SOAPERY_FW_DIR', 'fw' );
if ( ! defined( 'SOAPERY_THEME_PATH' ) )	define( 'SOAPERY_THEME_PATH',	trailingslashit( get_template_directory() ) );
if ( ! defined( 'SOAPERY_FW_PATH' ) )		define( 'SOAPERY_FW_PATH',		SOAPERY_THEME_PATH . SOAPERY_FW_DIR . '/' );

// Theme timing
if ( ! defined( 'SOAPERY_START_TIME' ) )		define( 'SOAPERY_START_TIME', microtime(true));		// Framework start time
if ( ! defined( 'SOAPERY_START_MEMORY' ) )		define( 'SOAPERY_START_MEMORY', memory_get_usage());	// Memory usage before core loading
if ( ! defined( 'SOAPERY_START_QUERIES' ) )	define( 'SOAPERY_START_QUERIES', get_num_queries());	// DB queries used

// Include theme variables storage
require_once(get_template_directory().'/'.SOAPERY_FW_DIR.'/core/core.storage.php');

// Theme variables storage
//soapery_storage_set('options_prefix', 'soapery'.'_'.trim($theme_slug));	// Used as prefix to store theme's options in the post meta and wp options
soapery_storage_set('options_prefix', 'soapery');	// Used as prefix to store theme's options in the post meta and wp options
soapery_storage_set('page_template', '');			// Storage for current page template name (used in the inheritance system)
soapery_storage_set('widgets_args', array(			// Arguments to register widgets
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5 class="widget_title">',
		'after_title'   => '</h5>',
	)
);

/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_loader_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'soapery_loader_theme_setup', 20 );
	function soapery_loader_theme_setup() {

		soapery_profiler_add_point(esc_html__('After load theme required files', 'soapery'));

		// Before init theme
		do_action('soapery_action_before_init_theme');

		// Load current values for main theme options
		soapery_load_main_options();

		// Theme core init - only for admin side. In frontend it called from header.php
		if ( is_admin() ) {
			soapery_core_init_theme();
		}
	}
}


/* Include core parts
------------------------------------------------------------------------ */
// Manual load important libraries before load all rest files
// core.strings must be first - we use musicband_str...() in the musicband_get_file_dir()
require_once(get_template_directory().'/'.SOAPERY_FW_DIR.'/core/core.strings.php');
// core.files must be first - we use musicband_get_file_dir() to include all rest parts
require_once(get_template_directory().'/'.SOAPERY_FW_DIR.'/core/core.files.php');
// Include debug and profiler
require_once(get_template_directory().'/fw/core/core.debug.php');


// Include custom theme files
soapery_autoload_folder( 'includes' );

// Include core files
soapery_autoload_folder( 'core' );

// Include theme-specific plugins and post types
soapery_autoload_folder( 'plugins' );

// Include theme templates
soapery_autoload_folder( 'templates' );

// Include theme widgets
soapery_autoload_folder( 'widgets' );
?>