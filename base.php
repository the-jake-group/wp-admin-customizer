<?php

/*
Plugin Name: WP-Admin Customizer
Plugin URI: http://www.thejakegroup.com/wordpress
Description: A custom theme developed by The Jake Group for your Wordpress Admin Site.
Author: Tyler Bruffy
Version: 1.0
Author URI: hhttp://www.thejakegroup.com/wordpress/
*/

class WPAC {
	const DB_VERSION    = "2.0.0";
	const FILE_NAME     = "colors.css";
	const ABBREV        = "WPAC";
	const PREFIX        = "wpac_";
	const SLUG          = "wpac_options";

	public static $plugin_url;
	public static $plugin_path;

	function __construct() {
		$this->include_files();
		
		$this->control = new WPAC_Controller();	
		
		$this->add_hooks();
		$this->add_filters();
	}

/* ==========================================================================
	Activation
	========================================================================= */

	public function activate() {
		self::set_default("base-color",          "#222222");
		self::set_default("highlight-color",    "#333333");
		self::set_default("notification-color", "#0074a2");
		self::set_default("action-color",       "#2ea2cc");
	}

/* ==========================================================================
	Deactivation
	========================================================================= */

	public static function deactivate() {
		WPAC::reset_user_themes();
	}

/* ==========================================================================
	Uninstall
	========================================================================= */

	public static function uninstall() {
		self::delete_option("base-color");
		self::delete_option("highlight-color");
		self::delete_option("notification-color");
		self::delete_option("action-color");
		self::delete_option("default-scheme");
		self::delete_option("hide-schemes");
		self::delete_option("dashboard-widgets");
		self::delete_option("login-img");

		WPAC::reset_user_themes();
	}


	public static function reset_user_themes() {
		$query_args =  array( 
			"meta_key"   => "admin_color", 
			"meta_value" => self::ABBREV, 
		);
		$users = new WP_User_Query($query_args);

		foreach ($users->results as $index => $user) {
			update_user_meta($user->data->ID, 'admin_color', "fresh");
		}
	}

/* ==========================================================================
	Hooks
	========================================================================= */

	public function add_hooks() {
		register_activation_hook(__FILE__,      array($this, "activate") );
		register_deactivation_hook(__FILE__,    array("WPAC", "deactivate") );
		register_uninstall_hook(__FILE__,       array("WPAC", "uninstall") );

		add_action('admin_menu', 			    array($this->control, "create_options_page"));
		add_action('admin_enqueue_scripts',     array($this->control, "enqueue_options_scripts"));

		add_action('wp_dashboard_setup',        array($this->control, "remove_widgets"));

		add_action('admin_init',                array($this->control, "create_color_scheme"));
		add_action('login_enqueue_scripts',     array($this->control, "change_login_logo"));

		if (self::get_option("default-scheme")) {
			add_action('user_register', array($this->control, "set_default_color_scheme"), 10, 1);
		}

		if (self::get_option("hide-schemes")) {
			add_action('admin_color_scheme_picker', array($this->control, "remove_color_schemes"), 0);
		}
	}

/* ==========================================================================
	Filters
	========================================================================= */

	public function add_filters() {
		if (self::get_option("login-img")) {
			add_filter('login_headerurl',   array($this->control, "change_logo_link"));
			add_filter('login_headertitle', array($this->control, "change_logo_title"));
		}
	}

/* ==========================================================================
	Includes
	========================================================================= */

	public function include_files() {
		require_once('controller.php');
		require_once('compiler.php');
		require_once('settings_controller.php');		
	}

/* ==========================================================================
	General
	========================================================================= */

	public static function plugin_info() {
		self::$plugin_path = plugin_dir_path(__FILE__);
		self::$plugin_url  = plugins_url('', __FILE__);
	}

	public static function apply_filters($name, $var) {
		return apply_filters(self::ABBREV . "/$name", $var);
		return $var;
	}

	public static function notice($message, $class, $extra = "") {
		return sprintf(
			'<div id="setting-error-settings_updated" class="%s settings-error">
				<p><strong>%s</strong> %s</p>
			</div>',
			$class,
			$message,
			$extra
		);
	}

/* ==========================================================================
	Database Interaction
	========================================================================= */

	protected function get_option( $option ) {
		return get_option( self::PREFIX.$option );
	}

	protected function set_option( $option, $value ) {
		$value = $value ? $value : 0;
		return update_option( self::PREFIX . $option, $value );
	}

	protected function set_default( $option, $value ) {
		if (!self::get_option($option)) {
			return update_option( self::PREFIX . $option, $value );
		}
		return;
	}

	protected function delete_option( $option ) {
		return delete_option( self::PREFIX.$option );
	}

/* ==========================================================================
	File System Interaction
	========================================================================= */

	public static function upload_dir($index = "url") {
		$dir_array = wp_upload_dir();
		return $dir_array[$index] . "/" . self::ABBREV . "/";
	}

	public static function require_dir( $path ) {
		foreach (glob(self::$plugin_path . $path . "/*.php") as $filename) {
			require_once($filename);
		}
	}

	public static function activate_file_system() {
		$url = wp_nonce_url('options-general.php?page='.self::SLUG, self::SLUG);
		
		if (false === ($creds = request_filesystem_credentials($url, '', false, false, null) ) ) {
			return "There was an error with your FTP/Filesystem credentials, please try again"; 
		}

		if ( !WP_Filesystem($creds) ) {
			request_filesystem_credentials($url, '', true, false, null);
			return "There was an error with your FTP/Filesystem credentials, please try again";
		}

		global $wp_filesystem;
		return $wp_filesystem;
	}

	public static function color_scheme_exists() {
		$fs = self::activate_file_system();
		return $fs->is_file(self::upload_dir("path").self::FILE_NAME);
	}	

}	

new WPAC();
