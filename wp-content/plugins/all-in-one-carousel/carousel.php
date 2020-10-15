<?php
/**
 * Plugin Name: Visual Composer - All in One Carousel
 * Description: Visual Composer - All in One Carousel for post, video, testimonial, custom HTML, youtube, Vimeo
 * Version: 1.0
 * Author: Hitesh Khunt
 * Author URI: http://www.saragna.com/Hitesh-Khunt
 * Plugin URI: http://plugin.saragna.com/vc-addon
 * License: GPLv2 or later
 Text Domain: svc_carousel
 *
 */

$svgrVersion = "1.0";

$currentFile = __FILE__;

$currentFolder = dirname($currentFile);

add_action('admin_init','svc_carousel_Init_Addon');
require_once( 'inc/add-param.php' );
require_once( 'inc/all_function.php' );
require_once( 'addons/carousel/post-grid.php' );

wp_enqueue_style( 'svc-fontawosem-css', plugins_url( ltrim( 'assets/css/font-awesome.min.css', '/' ), __FILE__ ), array(), '' );
if(is_admin()){
	wp_enqueue_style( 'svc-admin-css', plugins_url( ltrim( 'assets/css/admin.css', '/' ), __FILE__ ), array(), '' );
}
if(!is_admin()){
	wp_enqueue_style( 'svc-front-css', plugins_url( ltrim( 'assets/css/front.css', '/' ), __FILE__ ), array(), '' );
}


function svc_carousel_Init_Addon() {
	$required_vc 	= '3.9.9';
	if (defined('WPB_VC_VERSION')){
		if (version_compare($required_vc, WPB_VC_VERSION, '>')) {
			add_action('admin_notices', 'svc_carousel_Admin_Notice_Version');
		}
	}else{
		add_action('admin_notices', 'svc_carousel_Admin_Notice_Activation');
	}
}
function svc_carousel_Admin_Notice_Version() {
		echo '<div class="updated"><p>The <strong>Visual Composer All in One Carousel</strong> add-on requires <strong>Visual Composer</strong> version 4.0.0 or greater.</p></div>';	
	}
function svc_carousel_Admin_Notice_Activation() {
	echo '<div class="updated"><p>The <strong>Visual Composer All in One Carousel</strong> add-on requires the <strong>Visual Composer</strong> Plugin installed and activated.</p></div>';
}
?>