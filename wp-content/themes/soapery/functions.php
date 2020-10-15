<?php
/**
 * Theme sprecific functions and definitions
 */

/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'soapery_theme_setup' ) ) {
	add_action( 'soapery_action_before_init_theme', 'soapery_theme_setup', 1 );
	function soapery_theme_setup() {

        // Add default posts and comments RSS feed links to head
        add_theme_support( 'automatic-feed-links' );

        // Enable support for Post Thumbnails
        add_theme_support( 'post-thumbnails' );

        // Custom header setup
        add_theme_support( 'custom-header', array('header-text'=>false));

        // Custom backgrounds setup
        add_theme_support( 'custom-background');

        // Supported posts formats
        add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') );

        // Autogenerate title tag
        add_theme_support('title-tag');

        // Add user menu
        add_theme_support('nav-menus');

        // Add wide and full blocks support
        add_theme_support( 'align-wide' );

        // WooCommerce Support
        add_theme_support( 'woocommerce' );

		// Register theme menus
		add_filter( 'soapery_filter_add_theme_menus',		'soapery_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'soapery_filter_add_theme_sidebars',	'soapery_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'soapery_filter_importer_options',		'soapery_set_importer_options' );

		// Add theme required plugins
		add_filter( 'soapery_filter_required_plugins',		'soapery_add_required_plugins' );

		// Init theme after WP is created
		add_action( 'wp',									'soapery_core_init_theme' );

		// Add theme specified classes into the body
		add_filter( 'body_class', 'soapery_body_classes' );

				// Add data to the head and to the beginning of the body
		add_action('wp_head',								'soapery_head_add_page_meta', 1);
		add_action('before',								'soapery_body_add_gtm');
		add_action('before',								'soapery_body_add_toc');
		add_action('before',								'soapery_body_add_page_preloader');

		// Add data to the footer (priority 1, because priority 2 used for localize scripts)
		add_action('wp_footer',								'soapery_footer_add_views_counter', 1);
		add_action('wp_footer',								'soapery_footer_add_theme_customizer', 1);
		add_action('wp_footer',								'soapery_footer_add_scroll_to_top', 1);
		add_action('wp_footer',								'soapery_footer_add_custom_html', 1);
		add_action('wp_footer',								'soapery_footer_add_gtm2', 1);

		// Set list of the theme required plugins
		soapery_storage_set('required_plugins', array(
			'essgrids',
			'instagram_widget',
			'revslider',
			'tribe_events',
			'mailchimp',
			'trx_utils',
			'visual_composer',
			'sociallogin',
			'woocommerce',
            'wp_gdpr_compliance',
            )
		);

		
	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'soapery_add_theme_menus' ) ) {
	function soapery_add_theme_menus($menus) {
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'soapery_add_theme_sidebars' ) ) {
	function soapery_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'soapery' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'soapery' )
			);
			if (function_exists('soapery_exists_woocommerce') && soapery_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'soapery' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Add theme required plugins
if ( !function_exists( 'soapery_add_required_plugins' ) ) {
	function soapery_add_required_plugins($plugins) {
		$plugins[] = array(
			'name' 		=> 'Soapery Utilities',
			'version'	=> '3.2',					// Minimal required version
			'slug' 		=> 'trx_utils',
			'source'	=> soapery_get_file_dir('plugins/install/trx_utils.zip'),
			'force_activation'   => false,			// If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => true,			// If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'required' 	=> true
		);
		return $plugins;
	}
}


//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( ! function_exists( 'soapery_importer_set_options' ) ) {
    add_filter( 'trx_utils_filter_importer_options', 'soapery_importer_set_options', 9 );
    function soapery_importer_set_options( $options=array() ) {
        if ( is_array( $options ) ) {
            // Save or not installer's messages to the log-file
            $options['debug'] = false;
            // Prepare demo data
            if ( is_dir( SOAPERY_THEME_PATH . 'demo/' ) ) {
                $options['demo_url'] = SOAPERY_THEME_PATH . 'demo/';
            } else {
                $options['demo_url'] = esc_url( soapery_get_protocol().'://demofiles.ancorathemes.com/soapery/' ); // Demo-site domain
            }

            // Required plugins
            $options['required_plugins'] =  array(
                'essential-grid',
                'instagram_widget',
                'revslider',
                'the-events-calendar',
                'mailchimp-for-wp',
                'trx_utils',
                'js_composer',
                'sociallogin',
                'woocommerce'
            );

            $options['theme_slug'] = 'soapery';

            // Set number of thumbnails to regenerate when its imported (if demo data was zipped without cropped images)
            // Set 0 to prevent regenerate thumbnails (if demo data archive is already contain cropped images)
            $options['regenerate_thumbnails'] = 3;
            // Default demo
            $options['files']['default']['title'] = esc_html__( 'Education Demo', 'soapery' );
            $options['files']['default']['domain_dev'] = esc_url(soapery_get_protocol().'://soapery.dv.ancorahemes.com'); // Developers domain
            $options['files']['default']['domain_demo']= esc_url(soapery_get_protocol().'://soapery.ancorathemes.com'); // Demo-site domain

        }
        return $options;
    }
}



// Add theme specified classes into the body
if ( !function_exists('soapery_body_classes') ) {
	function soapery_body_classes( $classes ) {

		$classes[] = 'soapery_body';
		$classes[] = 'body_style_' . trim(soapery_get_custom_option('body_style'));
		$classes[] = 'body_' . (soapery_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'theme_skin_' . trim(soapery_get_custom_option('theme_skin'));
		$classes[] = 'article_style_' . trim(soapery_get_custom_option('article_style'));
		
		$blog_style = soapery_get_custom_option(is_singular() && !soapery_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(soapery_get_template_name($blog_style));
		
		$body_scheme = soapery_get_custom_option('body_scheme');
		if (empty($body_scheme)  || soapery_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = soapery_get_custom_option('top_panel_position');
		if (!soapery_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else 
			$classes[] = 'top_panel_hide';
		$classes[] = soapery_get_sidebar_class();

		if (soapery_get_custom_option('show_video_bg')=='yes' && (soapery_get_custom_option('video_bg_youtube_code')!='' || soapery_get_custom_option('video_bg_url')!=''))
			$classes[] = 'video_bg_show';

		if (soapery_get_theme_option('page_preloader')!='')
			$classes[] = 'preloader';

		return $classes;
	}
}


// Add page meta to the head
if (!function_exists('soapery_head_add_page_meta')) {
	function soapery_head_add_page_meta() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1<?php if (soapery_get_theme_option('responsive_layouts')=='yes') echo ', maximum-scale=1'; ?>">
		<meta name="format-detection" content="telephone=no">
	
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php
	}
}

// Add page preloader styles to the head
if (!function_exists('soapery_head_add_page_preloader_styles')) {
	function soapery_head_add_page_preloader_styles($css) {
		if (($preloader=soapery_get_theme_option('page_preloader'))!='none') {
			$image = soapery_get_theme_option('page_preloader_image');
			$bg_clr = soapery_get_scheme_color('bg_color');
			$link_clr = soapery_get_scheme_color('text_link');
			$css .= '
				#page_preloader {
					background-color: '. esc_attr($bg_clr) . ';'
					. ($preloader=='custom' && $image
						? 'background-image:url('.esc_url($image).');'
						: ''
						)
				    . '
				}
				.preloader_wrap > div {
					background-color: '.esc_attr($link_clr).';
				}';
		}
		return $css;
	}
}

// Add gtm code to the beginning of the body 
if (!function_exists('soapery_body_add_gtm')) {
	function soapery_body_add_gtm() {
		soapery_show_layout(soapery_get_custom_option('gtm_code'));
	}
}

// Add TOC anchors to the beginning of the body 
if (!function_exists('soapery_body_add_toc')) {
	function soapery_body_add_toc() {
		// Add TOC items 'Home' and "To top"
		if (soapery_get_custom_option('menu_toc_home')=='yes' && function_exists('soapery_sc_anchor'))
			soapery_show_layout(soapery_sc_anchor(array(
				'id' => "toc_home",
				'title' => esc_html__('Home', 'soapery'),
				'description' => esc_html__('{{Return to Home}} - ||navigate to home page of the site', 'soapery'),
				'icon' => "icon-home",
				'separator' => "yes",
				'url' => esc_url(home_url('/'))
				)
			)); 
		if (soapery_get_custom_option('menu_toc_top')=='yes' && function_exists('soapery_sc_anchor'))
			soapery_show_layout(soapery_sc_anchor(array(
				'id' => "toc_top",
				'title' => esc_html__('To Top', 'soapery'),
				'description' => esc_html__('{{Back to top}} - ||scroll to top of the page', 'soapery'),
				'icon' => "icon-double-up",
				'separator' => "yes")
				)); 
	}
}

// Add page preloader to the beginning of the body
if (!function_exists('soapery_body_add_page_preloader')) {
	function soapery_body_add_page_preloader() {
		if ( ($preloader=soapery_get_theme_option('page_preloader')) != 'none' && ( $preloader != 'custom' || ($image=soapery_get_theme_option('page_preloader_image')) != '')) {
			?><div id="page_preloader"><?php
				if ($preloader == 'circle') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_circ1"></div><div class="preloader_circ2"></div><div class="preloader_circ3"></div><div class="preloader_circ4"></div></div><?php
				} else if ($preloader == 'square') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_square1"></div><div class="preloader_square2"></div></div><?php
				}
			?></div><?php
		}
	}
}

// Add data to the footer
//------------------------------------------------------------------------

// Add post/page views counter
if (!function_exists('soapery_footer_add_views_counter')) {
	function soapery_footer_add_views_counter() {
		// Post/Page views counter
		get_template_part(soapery_get_file_slug('templates/_parts/views-counter.php'));
	}
}

// Add Login/Register popups
if (!function_exists('soapery_footer_add_login_register')) {
	function soapery_footer_add_login_register() {
		if (soapery_get_theme_option('show_login')=='yes') {
			soapery_enqueue_popup();
			// Anyone can register ?
			if ( (int) get_option('users_can_register') > 0) {
				get_template_part(soapery_get_file_slug('templates/_parts/popup-register.php'));
			}
			get_template_part(soapery_get_file_slug('templates/_parts/popup-login.php'));
		}
	}
}

// Add theme customizer
if (!function_exists('soapery_footer_add_theme_customizer')) {
	function soapery_footer_add_theme_customizer() {
		// Front customizer
		if (soapery_get_custom_option('show_theme_customizer')=='yes') {
			get_template_part(soapery_get_file_slug('core/core.customizer/front.customizer.php'));
		}
	}
}

// Add scroll to top button
if (!function_exists('soapery_footer_add_scroll_to_top')) {
	function soapery_footer_add_scroll_to_top() {
		?><a href="#" class="scroll_to_top icon-up" title="<?php esc_attr_e('Scroll to top', 'soapery'); ?>"></a><?php
	}
}

// Add custom html
if (!function_exists('soapery_footer_add_custom_html')) {
	function soapery_footer_add_custom_html() {
		?><div class="custom_html_section"><?php
			soapery_show_layout(soapery_get_custom_option('custom_code'));
		?></div><?php
	}
}

// Add gtm code
if (!function_exists('soapery_footer_add_gtm2')) {
	function soapery_footer_add_gtm2() {
		soapery_show_layout(soapery_get_custom_option('gtm_code2'));
	}
}


// Add theme required plugins
if ( !function_exists( 'soapery_add_trx_utils' ) ) {
    add_filter( 'trx_utils_active', 'soapery_add_trx_utils' );
    function soapery_add_trx_utils($enable=true) {
        return true;
    }
}

/* Include framework core files
------------------------------------------------------------------- */
// If now is WP Heartbeat call - skip loading theme core files (to reduce server and DB uploads)
// Remove comments below only if your theme not work with own post types and/or taxonomies
	require_once( get_template_directory().'/fw/loader.php' );
?>