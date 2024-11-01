<?php
/**
 * Gutenberg Pack Loader.
 *
 * @package Gutenberg Pack
 */

if ( ! class_exists( 'Gutenberg_Pack' ) ) {

	/**
	 * Class Gutenberg_Pack.
	 */
	final class Gutenberg_Pack {

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

			// Activation hook.
			register_activation_hook( GUTENBERG_PACK_FILE, array( $this, 'activation_reset' ) );

			// deActivation hook.
			register_deactivation_hook( GUTENBERG_PACK_FILE, array( $this, 'deactivation_reset' ) );

			if ( ! $this->is_gutenberg_active() ) {
				/* TO DO */
				add_action( 'admin_notices', array( $this, 'gutenberg_pack_fails_to_load' ) );
				return;
			}

			$this->define_constants();

			$this->loader();

			add_action( 'plugins_loaded', array( $this, 'load_plugin' ) );
		}

		/**
		 * Loads Other files.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function loader() {
			require( GUTENBERG_PACK_DIR . 'includes/class-gutenberg-pack-helper.php' );
		}

		/**
		 * Defines all constants
		 *
		 * @since 1.0.0
		 */
		public function define_constants() {
			define( 'GUTENBERG_PACK_BASE', plugin_basename( GUTENBERG_PACK_FILE ) );
			define( 'GUTENBERG_PACK_DIR', plugin_dir_path( GUTENBERG_PACK_FILE ) );
			define( 'GUTENBERG_PACK_URL', plugins_url( '/', GUTENBERG_PACK_FILE ) );
			define( 'GUTENBERG_PACK_VER', '1.0.1' );
 			define( 'GUTENBERG_PACK_SLUG', 'gutenberg_pack' );
			define( 'GUTENBERG_PACK_CATEGORY', 'Gutenberg Pack' );
		}

		/**
		 * Loads plugin files.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		function load_plugin() {

			$this->load_textdomain();

			require( GUTENBERG_PACK_DIR . 'includes/class-gutenberg-pack-core.php' );

		}

		/**
		 * Check if Gutenberg is active
		 *
		 * @since 1.1.0
		 *
		 * @return boolean
		 */
		public function is_gutenberg_active() {
			return function_exists( 'register_block_type' );
		}

		/**
		 * Load Gutenberg Pack Text Domain.
		 * This will load the translation textdomain depending on the file priorities.
		 *      1. Global Languages /wp-content/languages/gutenberg-pack/ folder
		 *      2. Local dorectory /wp-content/plugins/gutenberg-pack/languages/ folder
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function load_textdomain() {
			// Default languages directory for "gutenberg-pack".
			$lang_dir = GUTENBERG_PACK_DIR . 'languages/';

			/**
			 * Filters the languages directory path to use for AffiliateWP.
			 *
			 * @param string $lang_dir The languages directory path.
			 */
			$lang_dir = apply_filters( 'gutenberg_pack_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter.
			global $wp_version;

			$get_locale = get_locale();

			if ( $wp_version >= 4.7 ) {
				$get_locale = get_user_locale();
			}

			/**
			 * Language Locale for Gutenberg Pack
			 *
			 * @var $get_locale The locale to use. Uses get_user_locale()` in WordPress 4.7 or greater,
			 *                  otherwise uses `get_locale()`.
			 */
			$locale = apply_filters( 'plugin_locale', $get_locale, 'gutenberg-pack' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'gutenberg-pack', $locale );

			// Setup paths to current locale file.
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/gutenberg-pack/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/gutenberg-pack/ folder.
				load_textdomain( 'gutenberg-pack', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/gutenberg-pack/languages/ folder.
				load_textdomain( 'gutenberg-pack', $mofile_local );
			} else {
				// Load the default language files.
				load_plugin_textdomain( 'gutenberg-pack', false, $lang_dir );
			}
		}

		/**
		 * Fires admin notice when Gutenberg is not installed and activated.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function gutenberg_pack_fails_to_load() {
			$class = 'notice notice-error';
			/* translators: %s: html tags */
			$message = sprintf( __( 'The %1$sGutenberg Pack for Gutenberg%2$s plugin requires %1$sGutenberg%2$s plugin installed & activated.', 'gutenberg-pack' ), '<strong>', '</strong>' );

			$plugin = 'gutenberg/gutenberg.php';

			if ( _is_gutenberg_installed( $plugin ) ) {
				if ( ! current_user_can( 'activate_plugins' ) ) {
					return;
				}

				$action_url   = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
				$button_label = __( 'Activate Gutenberg', 'gutenberg-pack' );

			} else {
				if ( ! current_user_can( 'install_plugins' ) ) {
					return;
				}

				$action_url   = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=gutenberg' ), 'install-plugin_gutenberg' );
				$button_label = __( 'Install Gutenberg', 'gutenberg-pack' );
			}

			$button = '<p><a href="' . $action_url . '" class="button-primary">' . $button_label . '</a></p><p></p>';

			printf( '<div class="%1$s"><p>%2$s</p>%3$s</div>', esc_attr( $class ), $message, $button );
		}

		/**
		 * Activation Reset
		 */
		function activation_reset() {
		}

		/**
		 * Deactivation Reset
		 */
		function deactivation_reset() {
		}
	}

	/**
	 *  Prepare if class 'Gutenberg_Pack' exist.
	 *  Kicking this off by calling 'get_instance()' method
	 */
	Gutenberg_Pack::get_instance();
}

/**
 * Is Gutenberg plugin installed.
 */
if ( ! function_exists( '_is_gutenberg_installed' ) ) {

	/**
	 * Check if Gutenberg Pro is installed
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_path Plugin path.
	 * @return boolean true | false
	 * @access public
	 */
	function _is_gutenberg_installed( $plugin_path ) {
		$plugins = get_plugins();

		return isset( $plugins[ $plugin_path ] );
	}
}
