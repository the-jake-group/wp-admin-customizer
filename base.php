<?php

/*
Plugin Name: WP-Admin Customizer
Plugin URI: http://www.thejakegroup.com/wordpress
Description: A custom theme developed by The Jake Group for your Wordpress Admin Site.
Author: Tyler Bruffy
Version: 1.0
Author URI: hhttp://www.thejakegroup.com/wordpress/
*/

require_once('config.php');
require_once('utility.php');
require_once('dashboard.php');

define( 'TJG_AT_PATH', plugin_dir_path(__FILE__));
define( 'TJG_AT_URL', plugins_url('', __FILE__));

/**
	Extremely basic functions
*/

function tjg_at_add_custom_css() {
		global $wpdb;

		echo '<link rel="stylesheet" type="text/css" href="' .plugins_url('css/'.$wpdb->prefix.'override.css', __FILE__)."?ver=".time(). '">';
		echo '<link rel="stylesheet" type="text/css" href="' .plugins_url('css/plugin.css', __FILE__)."?ver=".time(). '">';
		echo '<script type="text/javascript" src="' .plugins_url('js/plugin.js', __FILE__)."?ver=".time(). '"></script>';
}

function tjg_at_theme_admin_menu() {
	if (!HIDE_SETTINGS) {
		add_options_page('Custom Admin Theme Options', 'Admin Theme', 'manage_options', 'admin-theme-menu', 'tjg_at_admin_theme_settings');
	}
}

function tjg_at_admin_theme_settings()	{
	include 'settings.php';
}

function tjg_at_custom_login_headerurl($url) {
	$customLogo = get_option("admin-theme-login-logo");
	if ($customLogo != "wordpress") {
		return get_bloginfo('url') . '/';
	}
}

function tjg_at_custom_login_headertitle($title) {
	$customLogo = get_option("admin-theme-login-logo");
	if ($customLogo != "wordpress") {
		return get_bloginfo('name');
	}	
}

function ilc_farbtastic_script() {
	wp_enqueue_style( 'farbtastic' );
	wp_enqueue_script( 'farbtastic' );
}

/**
	Plugin activation hook and function.
**/

register_activation_hook( __FILE__, 'tjg_at_admin_theme_activate' );

function tjg_at_admin_theme_activate()	{
	add_option('admin-theme-primary-color', '#464646');
	add_option('admin-theme-small-logo', 'wordpress');
	add_option('admin-theme-custom-hover', 'false');
	add_option('admin-theme-login-logo', 'wordpress');
	add_option('admin-theme-rounded-corners', 'false');

	add_option("admin-theme-dashboard_plugins", 'true');
	add_option("admin-theme-dashboard_recent_comments", 'true');
	add_option("admin-theme-dashboard_primary", 'true');
	add_option("admin-theme-dashboard_incoming_links", 'true');
	add_option("admin-theme-dashboard_right_now", 'true');
	add_option("admin-theme-dashboard_secondary", 'true');
	add_option("admin-theme-dashboard_recent_drafts", 'true');
	add_option("admin-theme-dashboard_quick_press", 'true');
	add_option("admin-theme-wp_welcome_panel", 'true');

}

/**
	Custom Admin Footer text
*/
function tjg_at_modify_footer_admin () {
	if ( defined('DEVELOPER_URL') && defined('DEVELOPER_NAME')) {
		echo 'Website by <a href="'.DEVELOPER_URL.'">'.DEVELOPER_NAME.'</a>.  ';
	}
	echo 'Powered by <a href="http://WordPress.org">WordPress</a>.';	
}

add_filter('admin_footer_text', 'tjg_at_modify_footer_admin');

/**
	Actions/Filters, these stay in this file.
*/

add_action('admin_head', 'tjg_at_add_custom_css');
add_action('admin_menu', 'tjg_at_theme_admin_menu');

add_action('login_head', 'tjg_at_add_custom_css');
add_filter('login_headerurl', 'tjg_at_custom_login_headerurl');
add_filter('login_headertitle', 'tjg_at_custom_login_headertitle');

add_action('init', 'ilc_farbtastic_script');

add_action('wp_dashboard_setup', 'tjg_at_remove_dashboard_widgets');	//	Removes dashboard widgets (dashboard.php)
add_action('wp_dashboard_setup', 'tjg_at_add_dashboard_add_widgets' );	//	Adds dashboard widgets. (dashboard.php)

?>