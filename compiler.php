<?php
//	TODO: Performance audit RE: concatenation of CSS in Compiler#compile.


/**
 * Create a new Compiler.  Requires a style object on construct. 
 * Then call compile.  Returns true if successfully written
 * to a file, false if there is an unknown error.  Throws an exception
 * if a known error occurred.
 */

class WPAC_Compiler extends WPAC {

	public $error;

	private $compiled = false;
	private $style;
	private $css_text;
	private $less;
	private $css;
	private $css_comment = "/*\r\nCSS compiled using the WP Admin Customizer plugin\r\n*/\r\n";

	function __construct( $css_text ) {
		$this->plugin_info();
		$this->css_text = stripslashes($css_text);
		$this->compress = false;
	}

	public function is_compiled() {
		return $this->compiled;
	}

	public function compile() {
		if ($this->compress) {
			$this->css_text = $this->_minify_css( $this->css_text );
		}
		$this->css_text = $this->css_comment . $this->css_text;
		return $this->_write_to_file( $this->css_text, self::upload_dir("path") );
	}


	private function _minify_css( $css_string ) { // Not currently used;
		require_once( "lib/YUI-CSS-compressor-PHP-port/cssmin.php" );
		$compressor = new CSSmin();
		return $compressor->run($css_string);
	}


	private function _write_to_file( $text, $path ) {
		$fs = self::activate_file_system();

		if (is_string($fs)) {
			$this->error = $fs; // Error string;
			return false;
		}

		if( !$fs->is_dir($path) ) {
			$fs->mkdir($path);
		}

		if ( !$fs->put_contents($path . self::FILE_NAME, $text, FS_CHMOD_FILE) ) {
			$this->error = "Unable to save CSS File. Check your filesystem credentials and try again.";
			return false;
		}

		return true;
	}

}

?>