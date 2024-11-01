<?php
/**
 * Gutenberg Pack Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package Gutenberg Pack
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Gutenberg_Pack_Init.
 *
 * @package Gutenberg Pack
 */
class Gutenberg_Pack_Init
{

    /**
     * Member Variable
     *
     * @var instance
     */
    private static $instance;

    /**
     *  Initiator
     */
    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    public function __construct()
    {

        // Hook: Frontend assets.
        add_action('enqueue_block_assets', array($this, 'block_assets'));

        // Hook: Editor assets.
        add_action('enqueue_block_editor_assets', array($this, 'editor_assets'));

        add_filter('block_categories', array($this, 'register_block_category'), 10, 2);
    }

    /**
     * Gutenberg block category for Gutenberg Pack.
     *
     * @param array $categories Block categories.
     * @param object $post Post object.
     * @since 1.0.0
     */
    function register_block_category($categories, $post)
    {
        return array_merge(
            $categories,
            array(
                array(
                    'slug' => 'gutenbergpack',
                    'title' => __('Gutenberg Pack', 'gutenberg-pack'),
                ),
            )
        );
    }

    /**
     * Enqueue Gutenberg block assets for both frontend + backend.
     *
     * @since 1.0.0
     */
    function block_assets()
    {
        // Styles.
        wp_enqueue_style(
            'gutenberg-pack-block-css', // Handle.
            GUTENBERG_PACK_URL . 'dist/blocks.style.build.css', // Block style CSS.
            GUTENBERG_PACK_VER
        );

        // Tab Scripts.
        wp_enqueue_script(
            'gutenberg-pack-tab-block-js', // Handle.
            GUTENBERG_PACK_URL . 'assets/js/block-tab.js',
            array('jquery'),
            GUTENBERG_PACK_VER,
            true // Enqueue the script in the footer.
        );


    } // End function editor_assets().

    /**
     * Enqueue Gutenberg block assets for backend editor.
     *
     * @since 1.0.0
     */
    function editor_assets()
    {
        // Scripts.
        wp_enqueue_script(
            'gutenberg-pack-block-editor-js', // Handle.
            GUTENBERG_PACK_URL . 'dist/blocks.build.js',
            array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor'), // Dependencies, defined above.
            GUTENBERG_PACK_VER,
            true // Enqueue the script in the footer.
        );

        // Styles.
        wp_enqueue_style(
            'gutenberg-pack-block-editor-css', // Handle.
            GUTENBERG_PACK_URL . 'dist/blocks.editor.build.css', // Block editor CSS.
            array('wp-edit-blocks'), // Dependency to include the CSS after it.
            GUTENBERG_PACK_VER
        );

        wp_enqueue_script('gutenberg-pack-deactivate-block-js', GUTENBERG_PACK_URL . 'dist/deactivator.build.js', array('wp-blocks'), GUTENBERG_PACK_VER, true);

        $blocks = array();
        $saved_blocks = Gutenberg_Pack_Helper::get_admin_settings_option('_gutenberg_pack_blocks');
        if (is_array($saved_blocks)) {

            foreach ($saved_blocks as $slug => $data) {

                $_slug = 'gutenbergpack/' . $slug;

                if (isset($saved_blocks[$slug])) {

                    if ('disabled' === $saved_blocks[$slug]) {
                        array_push($blocks, $_slug);
                    }
                }
            }
        }

        wp_localize_script(
            'gutenberg-pack-deactivate-block-js',
            'gutenberg_pack_deactivate_blocks',
            array(
                'deactivated_blocks' => $blocks,
            )
        );

        wp_localize_script(
            'gutenberg-pack-block-editor-js',
            'gutenberg_pack_blocks_info',
            array(
                'blocks' => Gutenberg_Pack_Config::get_block_attributes(),
                'category' => 'guternberg-pack',
            )
        );

    } // End function editor_assets().

}

/**
 *  Prepare if class 'Gutenberg_Pack_Init' exist.
 *  Kicking this off by calling 'get_instance()' method
 */
Gutenberg_Pack_Init::get_instance();
