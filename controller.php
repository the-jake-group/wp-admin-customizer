<?php

class WPAC_Controller extends WPAC {

	function __construct() {
		$this->plugin_info();
	}

	public function create_options_page() {
		add_options_page('Custom Admin Theme Options', 'Admin Theme', 'manage_options', self::SLUG, array($this, "output_options"));
	}

	public function output_options() {
		$this->settings = new WPAC_Settings("settings-schema");
		$this->maybe_process();
		$this->settings->the_template("settings");
	}

	private function maybe_process() {
		if (empty($_POST)) {
			return false;
		}

		check_admin_referer(self::SLUG);
		
		$this->settings->update($_POST);
	}

	function enqueue_options_scripts($hook) {
		if ( substr($hook, -strlen(self::SLUG)) === self::SLUG) {
			wp_enqueue_style( 'wpac_css', self::$plugin_url . '/assets/css/wpac.css' );

			wp_register_script("wpac_js", self::$plugin_url . "/assets/js/wpac.js", array( "jquery", "wp-color-picker"));
			wp_localize_script("wpac_js", "plugin_root", self::$plugin_url);
			wp_enqueue_script("wpac_js");
		}
	}

/* ==========================================================================
	Custom Stuff -- Move this to other files?
	========================================================================= */

	function remove_widgets() {
		$widgets = self::get_option("dashboard-widgets");
		global $wp_meta_boxes;
	
		foreach ( $widgets as $widget_name => $ignored )	{
			if ($ignored) {
				remove_meta_box($widget_name, "dashboard", "core");
				remove_meta_box($widget_name, "dashboard", "normal");
				remove_meta_box($widget_name, "dashboard", "advanced");
				remove_meta_box($widget_name, "dashboard", "side");
			}
		}
	}



	function create_color_scheme() {
		if (!self::color_scheme_exists()) {
			return;
		}
		$base         = self::get_option("base-color");
		$highlight    = self::get_option("highlight-color");
		$action       = self::get_option("action-color");
		$notification = self::get_option("notification-color");

		wp_admin_css_color( 
			self::ABBREV, 
			self::ABBREV,
			self::upload_dir() . self::FILE_NAME,
			array( $base, $highlight, $action, $notification )
		);
	}


	function set_default_color_scheme($user_id) {
		if (self::color_scheme_exists()) {
			update_user_meta($user_id, 'admin_color', self::ABBREV);
		}
	}


	function remove_color_schemes($user_id) {
		if (self::color_scheme_exists()) {
			global $_wp_admin_css_colors;
			foreach ($_wp_admin_css_colors as $name => $data) {
				global $_wp_admin_css_colors;
				if (self::ABBREV !== $name) {
					unset($_wp_admin_css_colors[$name]);
				}
			}
		}
	}


	function change_login_logo() {
		$logo_id  = self::get_option("login-img");

		if (!$logo_id) {
			return;
		}

		$logo_src = wp_get_attachment_image_src($logo_id, "full");

		echo sprintf(
			'<style type="text/css">
				body.login div#login h1 a {
					background-image: url(%s);
					max-width: 100%%;
					width: %spx;
					height: %spx;
					background-size: contain;
				}
			</style>',
			$logo_src[0],
			$logo_src[1],
			$logo_src[2]
		);
	}


	function change_logo_link($url) {
		if (self::get_option("login-img")) {
			return get_bloginfo('url') . '/';
		}
	}

	function change_logo_title($title) {
		if (self::get_option("login-img")) {
			return get_bloginfo('name');
		}	
	}


}
