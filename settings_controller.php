<?php

class WPAC_Settings extends WPAC {
	public $html         = "";
	public $fields       = array();
	public $field_groups = array();
	public $updated      = false;
	public $error        = false;
	public $notice       = "";

	function __construct($schema) {
		$this->plugin_info();
		
		require_once(self::$plugin_path . "fields/_field.php");
		self::require_dir("/fields");
		
		if (is_string($schema)) {
			$schema = $this->get_schema( $schema );
		}

		$schema = self::apply_filters("schema", $schema);

		$this->create_field_groups($schema);
		$this->schema = $schema;
	}

/* ==========================================================================
	Field Interaction
	========================================================================= */

	public function create_field_groups($groups) {
		foreach ($groups as $group_name => $fields) {
			$group = array();

			foreach ($fields as $field_name => $field_meta) {
				$field                     = $this->create_field($field_name, $field_meta);
				$group[]                   = $field;
				$this->fields[$field_name] = $field;
			}

			$this->field_groups[$group_name] = $group;
		}
		return $this->field_groups;
	}

	public function get_schema( $schema ) {
		return include(self::$plugin_path . "schema/" . $schema . ".php");
	}

	public function create_field($field_name, $field_meta) {
		$type  = $this->get_field_class($field_meta["type"]);
		$Class = class_exists($type) ? $type : "WPAC_Field";

		return new $Class($field_name, $field_meta);
	}

	public function get_field_class($type) {
		$type = str_replace("-", " ", $type);
		$type = ucwords($type);
		$type = str_replace(" ", "_", $type);
		$type = "WPAC_" . $type;
		return $type;
	}

/* ==========================================================================
	Processing
	========================================================================= */

	public function update($update_array) {
		if (isset($update_array["color_update"]) && $update_array["color_update"]) {
			$this->compile($update_array["compiled_css"]);
			$this->notice =  "Your browser may prevent you from seeing changes made to the color scheme immediately. Please hard refresh/clear your cache if you do not see your changes.";
		}	

		$this->save_fields($update_array);

		$this->updated = true;
	}

	public function save_fields($update_array) {
		foreach ($this->fields as $name => $field) {
			if ( !isset($update_array[$name]) ) {
				$update_array[$name] = false;
			}
			$field->save($update_array[$name]);
		}
	}

	public function compile($css) {
		$this->compiler = new WPAC_Compiler($css);
		$this->compiler->compile();

		if (!$this->compiler->is_compiled()) {
			$this->error = $this->compiler->error;
		}
	}

/* ==========================================================================
	HTML Interaction
	========================================================================= */

	public function get_template( $name, $subfolder = "templates/" ) {
		ob_start();
		$settings = $this;
		include(self::$plugin_path . $subfolder . $name . ".php");
		return ob_get_clean();
	}

	public function the_template( $name ) {
		echo $this->get_template( $name );
	}

	public function render_group( $group_name ) {
		echo $this->generate_html( $this->field_groups[$group_name] );
	}

	public function update_message() {
		if (!$this->updated) {
			return;
		}

		if ($this->error) {
			echo $this->notice("Error Saving.", "error", $this->error);
		} else {
			echo $this->notice("Settings saved.", "updated", $this->notice);
		}
	}
		
	public function inactive_scheme_message() {
		if (!$this->color_scheme_exists()) {
			return;
		}

		if (self::ABBREV !== get_user_option("admin_color")) {
			echo $this->notice("", "update-nag", "The " . self::ABBREV . " color scheme is not set as your color scheme. Please select it in your user profile to see color changes take effect.");
		}
	}	

	public function generate_html( $fields ) {
		$html = "";
		foreach ($fields as $name => $field) {
			$html .= $field->get_html();
		}
		return $html;
	}

}