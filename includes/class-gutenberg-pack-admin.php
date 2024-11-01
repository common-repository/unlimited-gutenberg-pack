<?php
/**
 * Gutenberg Pack Admin.
 *
 * @package Gutenberg Pack
 */

if ( ! class_exists( 'Gutenberg_Pack_Admin' ) ) {

	/**
	 * Class Gutenberg_Pack_Admin.
	 */
	final class Gutenberg_Pack_Admin {

		/**
		 * Calls on initialization
		 *
		 * @since 0.0.1
		 */
		public static function init() {

			self::initialize_ajax();
			self::initialise_plugin();
			add_action( 'after_setup_theme', __CLASS__ . '::init_hooks' );
		}

		/**
		 * Adds the admin menu and enqueues CSS/JS if we are on
		 * the builder admin settings page.
		 *
		 * @since 0.0.1
		 * @return void
		 */
		static public function init_hooks() {
			if ( ! is_admin() ) {
				return;
			}

			// Add Gutenberg Pack menu option to admin.
			add_action( 'network_admin_menu', __CLASS__ . '::menu' );
			add_action( 'admin_menu', __CLASS__ . '::menu' );

			add_action( 'gutenberg_pack_render_admin_content', __CLASS__ . '::render_content' );

			// Enqueue admin scripts.
			if ( isset( $_REQUEST['page'] ) && GUTENBERG_PACK_SLUG == $_REQUEST['page'] ) {

				add_action( 'admin_enqueue_scripts', __CLASS__ . '::styles_scripts' );

				self::save_settings();
			}
		}

		/**
		 * Initialises the Plugin Name.
		 *
		 * @since 0.0.1
		 * @return void
		 */
		static public function initialise_plugin() {

			define( 'GUTENBERG_PACK_PLUGIN_NAME', 'Gutenberg Pack' );
		}

		/**
		 * Renders the admin settings menu.
		 *
		 * @since 0.0.1
		 * @return void
		 */
		static public function menu() {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			add_submenu_page(
				'options-general.php',
                GUTENBERG_PACK_PLUGIN_NAME,
                GUTENBERG_PACK_PLUGIN_NAME,
				'manage_options',
				GUTENBERG_PACK_SLUG,
				__CLASS__ . '::render'
			);
		}

		/**
		 * Renders the admin settings.
		 *
		 * @since 0.0.1
		 * @return void
		 */
		static public function render() {
			$action = ( isset( $_GET['action'] ) ) ? $_GET['action'] : '';
			$action = ( ! empty( $action ) && '' != $action ) ? $action : 'general';
			$action = str_replace( '_', '-', $action );

			// Enable header icon filter below.
			$gutenberg_pack_icon                 = apply_filters( 'gutenberg_pack_header_top_icon', false );
			$gutenberg_pack_visit_site_url       = apply_filters( 'gutenberg_pack_site_url', 'http://gutenbergpack.com/' );
			$gutenberg_pack_header_wrapper_class = apply_filters( 'gutenberg_pack_header_wrapper_class', array( $action ) );

			include_once GUTENBERG_PACK_DIR . 'admin/gutenberg-pack-admin.php';
		}

		/**
		 * Renders the admin settings content.
		 *
		 * @since 0.0.1
		 * @return void
		 */
		static public function render_content() {

			$action = ( isset( $_GET['action'] ) ) ? $_GET['action'] : '';
			$action = ( ! empty( $action ) && '' != $action ) ? $action : 'general';
			$action = str_replace( '_', '-', $action );

			$gutenberg_pack_header_wrapper_class = apply_filters( 'gutenberg_pack_header_wrapper_class', array( $action ) );

			include_once GUTENBERG_PACK_DIR . 'admin/gutenberg-pack-' . $action . '.php';
		}

		/**
		 * Enqueues the needed CSS/JS for the builder's admin settings page.
		 *
		 * @since 1.0
		 */
		static public function styles_scripts() {

			// Styles.
			wp_enqueue_style( 'gutenberg-pack-admin-settings', GUTENBERG_PACK_URL . 'admin/assets/css/admin.css', array(), GUTENBERG_PACK_VER );
			// Script.
			wp_enqueue_script( 'gutenberg-pack-admin-settings', GUTENBERG_PACK_URL . 'admin/assets/js/admin.js', array( 'jquery', 'wp-util', 'updates' ), GUTENBERG_PACK_VER );

			$localize = array(
				'ajax_nonce'   => wp_create_nonce( 'gutenberg-pack-block-nonce' ),
				'activate'     => __( 'Activate', 'gutenberg-pack' ),
				'deactivate'   => __( 'Deactivate', 'gutenberg-pack' ),
				'enable_beta'  => __( 'Enable Beta Updates', 'gutenberg-pack' ),
				'disable_beta' => __( 'Disable Beta Updates', 'gutenberg-pack' ),
			);

			wp_localize_script( 'gutenberg-pack-admin-settings', 'gutenberg_pack_obj', apply_filters( 'gutenberg_pack_js_localize', $localize ) );
		}

		/**
		 * Save All admin settings here
		 */
		static public function save_settings() {

			// Only admins can save settings.
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// Let extensions hook into saving.
			do_action( 'gutenberg_pack_admin_settings_save' );
		}

		/**
		 * Initialize Ajax
		 */
		static public function initialize_ajax() {
			// Ajax requests.
			add_action( 'wp_ajax_gutenberg_pack_activate_widget', __CLASS__ . '::activate_widget' );
			add_action( 'wp_ajax_gutenberg_pack_deactivate_widget', __CLASS__ . '::deactivate_widget' );

			add_action( 'wp_ajax_gutenberg_pack_bulk_activate_widgets', __CLASS__ . '::bulk_activate_widgets' );
			add_action( 'wp_ajax_gutenberg_pack_bulk_deactivate_widgets', __CLASS__ . '::bulk_deactivate_widgets' );
		}

		/**
		 * Activate module
		 */
		static public function activate_widget() {

			check_ajax_referer( 'gutenberg-pack-block-nonce', 'nonce' );

			$block_id            = sanitize_text_field( $_POST['block_id'] );
			$blocks              = Gutenberg_Pack_Helper::get_admin_settings_option( '_gutenberg_pack_blocks', array() );
			$blocks[ $block_id ] = $block_id;
			$blocks              = array_map( 'esc_attr', $blocks );

			// Update blocks.
			Gutenberg_Pack_Helper::update_admin_settings_option( '_gutenberg_pack_blocks', $blocks );

			echo $block_id;

			die();
		}

		/**
		 * Deactivate module
		 */
		static public function deactivate_widget() {

			check_ajax_referer( 'gutenberg-pack-block-nonce', 'nonce' );

			$block_id            = sanitize_text_field( $_POST['block_id'] );
			$blocks              = Gutenberg_Pack_Helper::get_admin_settings_option( '_gutenberg_pack_blocks', array() );
			$blocks[ $block_id ] = 'disabled';
			$blocks              = array_map( 'esc_attr', $blocks );

			// Update blocks.
			Gutenberg_Pack_Helper::update_admin_settings_option( '_gutenberg_pack_blocks', $blocks );

			echo $block_id;

			die();
		}

		/**
		 * Activate all module
		 */
		static public function bulk_activate_widgets() {

			check_ajax_referer( 'gutenberg-pack-block-nonce', 'nonce' );

			// Get all widgets.
			$all_blocks = Gutenberg_Pack_Helper::$block_list;
			$new_blocks = array();

			// Set all extension to enabled.
			foreach ( $all_blocks  as $slug => $value ) {
				$_slug                = str_replace( 'gutenbergpack/', '', $slug );
				$new_blocks[ $_slug ] = $_slug;
			}

			// Escape attrs.
			$new_blocks = array_map( 'esc_attr', $new_blocks );

			// Update new_extensions.
			Gutenberg_Pack_Helper::update_admin_settings_option( '_gutenberg_pack_blocks', $new_blocks );

			echo 'success';

			die();
		}

		/**
		 * Deactivate all module
		 */
		static public function bulk_deactivate_widgets() {

			check_ajax_referer( 'gutenberg-pack-block-nonce', 'nonce' );

			// Get all extensions.
			$old_blocks = Gutenberg_Pack_Helper::$block_list;
			$new_blocks = array();

			// Set all extension to enabled.
			foreach ( $old_blocks  as $slug => $value ) {
				$_slug                = str_replace( 'gutenbergpack/', '', $slug );
				$new_blocks[ $_slug ] = 'disabled';
			}

			// Escape attrs.
			$new_blocks = array_map( 'esc_attr', $new_blocks );

			// Update new_extensions.
			Gutenberg_Pack_Helper::update_admin_settings_option( '_gutenberg_pack_blocks', $new_blocks );

			echo 'success';

			die();
		}


	}

	Gutenberg_Pack_Admin::init();

}

