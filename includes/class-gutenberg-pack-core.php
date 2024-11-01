<?php
/**
 * Gutenberg Pack Core Plugin.
 *
 * @package Gutenberg Pack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Gutenberg_Pack_Core.
 *
 * @package Gutenberg Pack
 */
class Gutenberg_Pack_Core {

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {

		$this->includes();
	}

	/**
	 * Includes.
	 *
	 * @since 1.0.0
	 */
	private function includes() {

		require( GUTENBERG_PACK_DIR . 'includes/class-gutenberg-pack-admin.php' );
		require( GUTENBERG_PACK_DIR . 'includes/class-gutenberg-pack-init.php' );
	}
}

/**
 *  Prepare if class 'Gutenberg_Pack_Core' exist.
 *  Kicking this off by calling 'get_instance()' method
 */
Gutenberg_Pack_Core::get_instance();
