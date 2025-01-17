<?php
/**
 * Gutenberg Pack Helper.
 *
 * @package Gutenberg Pack
 */

if (!class_exists('Gutenberg_Pack_Helper')) {

    /**
     * Class Gutenberg_Pack_Helper.
     */
    final class Gutenberg_Pack_Helper
    {

        /**
         * Member Variable
         *
         * @since 0.0.1
         * @var instance
         */
        private static $instance;

        /**
         * Member Variable
         *
         * @since 0.0.1
         * @var instance
         */
        public static $block_list;

        /**
         * Google Map Language List
         *
         * @var google_map_languages
         */
        private static $google_map_languages = null;

        /**
         *  Initiator
         *
         * @since 0.0.1
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

            require(GUTENBERG_PACK_DIR . 'includes/class-gutenberg-pack-config.php');
            require(GUTENBERG_PACK_DIR . 'includes/class-gutenberg-pack-block-helper.php');

            self::$block_list = Gutenberg_Pack_Config::get_block_attributes();

            add_action('wp_head', array($this, 'generate_stylesheet'), 80);
        }

        /**
         * Parse CSS into correct CSS syntax.
         *
         * @param array $selectors The block selectors.
         * @param string $id The selector ID.
         * @since 0.0.1
         */
        public static function generate_css($selectors, $id)
        {

            $styling_css = '';

            if (empty($selectors)) {
                return;
            }

            foreach ($selectors as $key => $value) {

                $styling_css .= $id;

                $styling_css .= $key . ' { ';
                $css = '';

                foreach ($value as $j => $val) {

                    $css .= $j . ': ' . $val . ';';
                }

                $styling_css .= $css . ' } ';
            }

            return $styling_css;
        }

        /**
         * Parse CSS into correct CSS syntax.
         *
         * @param string $query Media Query string.
         * @param array $selectors The block selectors.
         * @param string $id The selector ID.
         * @since 0.0.1
         */
        public static function generate_responsive_css($query, $selectors, $id)
        {

            $css = $query . ' { ';
            $css .= self::generate_css($selectors, $id);
            $css .= ' } ';

            return $css;
        }

        /**
         * Generates CSS recurrsively.
         *
         * @param object $block The block object.
         * @since 0.0.1
         */
        public function get_block_css($block)
        {

            // @codingStandardsIgnoreStart

            $block = ( array )$block;

            $name = $block['blockName'];
            $css = '';

            if (!isset($name)) {
                return;
            }

            if (isset($block['attrs']) && is_array($block['attrs'])) {
                $blockattr = $block['attrs'];
                if (isset($blockattr['block_id'])) {
                    $block_id = $blockattr['block_id'];
                }
            }

            switch ($name) {
                case 'gutenbergpack/section':
                    $css .= GUTENBERG_PACK_Block_Helper::get_section_css($blockattr, $block_id);
                    break;

                case 'gutenbergpack/advanced-heading':
                    $css .= GUTENBERG_PACK_Block_Helper::get_adv_heading_css($blockattr, $block_id);
                    break;

                case 'gutenbergpack/info-box':
                    $css .= GUTENBERG_PACK_Block_Helper::get_info_box_css($blockattr, $block_id);
                    break;

                case 'gutenbergpack/buttons':
                    $css .= GUTENBERG_PACK_Block_Helper::get_buttons_css($blockattr, $block_id);
                    break;

                case 'gutenbergpack/testimonial':
                    $css .= GUTENBERG_PACK_Block_Helper::get_testimonial_css($blockattr, $block_id);
                    break;

                case 'gutenbergpack/team':
                    $css .= GUTENBERG_PACK_Block_Helper::get_team_css($blockattr, $block_id);
                    break;

                case 'gutenbergpack/social-share':
                    $css .= GUTENBERG_PACK_Block_Helper::get_social_share_css($blockattr, $block_id);
                    break;

                case 'gutenbergpack/content-timeline':
                    $css .= GUTENBERG_PACK_Block_Helper::get_content_timeline_css($blockattr, $block_id);
                    break;

                case 'gutenbergpack/restaurant-menu':
                    $css .= GUTENBERG_PACK_Block_Helper::get_restaurant_menu_css($blockattr, $block_id);
                    break;

                case 'gutenbergpack/icon-list':
                    $css .= GUTENBERG_PACK_Block_Helper::get_icon_list_css($blockattr, $block_id);
                    break;

                default:
                    // Nothing to do here.
                    break;
            }

            if (isset($block['innerBlocks'])) {
                foreach ($block['innerBlocks'] as $j => $inner_block) {
                    $css .= $this->get_block_css($inner_block);
                }
            }

            echo $css;

            // @codingStandardsIgnoreEnd
        }

        /**
         * Generates stylesheet and appends in head tag.
         *
         * @since 0.0.1
         */
        public function generate_stylesheet()
        {

            if (has_blocks(get_the_ID())) {

                global $post;

                if (!is_object($post)) {
                    return;
                }

                $blocks = $this->parse($post->post_content);

                if (!is_array($blocks) || empty($blocks)) {
                    return;
                }

                ob_start();
                ?>
                <style type="text/css" media="all"
                       id="gutenberg-pack-style-frontend"><?php $this->get_stylesheet($blocks); ?></style>
                <?php
            }
        }

        /**
         * Parse Guten Block.
         *
         * @param string $content the content string.
         * @since 1.1.0
         */
        public function parse($content)
        {

            global $wp_version;

            return (version_compare($wp_version, '5', '>=')) ? parse_blocks($content) : gutenberg_parse_blocks($content);
        }

        /**
         * Generates stylesheet for reusable blocks.
         *
         * @param array $blocks Blocks array.
         * @since 1.1.0
         */
        public function get_stylesheet($blocks)
        {

            foreach ($blocks as $i => $block) {

                if (is_array($block)) {

                    if ('core/block' == $block['blockName']) {

                        $id = (isset($block['attrs']['ref'])) ? $block['attrs']['ref'] : 0;

                        if ($id) {

                            $content = get_post_field('post_content', $id);

                            $reusable_blocks = $this->parse($content);

                            $this->get_stylesheet($reusable_blocks);
                        }
                    } else {

                        // Get CSS for the Block.
                        $this->get_block_css($block);
                    }
                }
            }
        }

        /**
         * Get Buttons default array.
         *
         * @since 0.0.1
         */
        public static function get_button_defaults()
        {

            $default = array();

            for ($i = 1; $i <= 2; $i++) {
                array_push(
                    $default,
                    array(
                        'size' => '',
                        'vPadding' => 10,
                        'hPadding' => 14,
                        'borderWidth' => 1,
                        'borderRadius' => 2,
                        'borderStyle' => 'solid',
                        'borderColor' => '#333',
                        'borderHColor' => '#333',
                        'color' => '#333',
                        'background' => '',
                        'hColor' => '#333',
                        'hBackground' => '',
                    )
                );
            }

            return $default;
        }

        /**
         * Returns an option from the database for
         * the admin settings page.
         *
         * @param  string $key The option key.
         * @param  mixed $default Option default value if option is not available.
         * @param  boolean $network_override Whether to allow the network admin setting to be overridden on subsites.
         * @return string           Return the option value
         */
        public static function get_admin_settings_option($key, $default = false, $network_override = false)
        {

            // Get the site-wide option if we're in the network admin.
            if ($network_override && is_multisite()) {
                $value = get_site_option($key, $default);
            } else {
                $value = get_option($key, $default);
            }

            return $value;
        }

        /**
         * Updates an option from the admin settings page.
         *
         * @param string $key The option key.
         * @param mixed $value The value to update.
         * @param bool $network Whether to allow the network admin setting to be overridden on subsites.
         * @return mixed
         */
        static public function update_admin_settings_option($key, $value, $network = false)
        {

            // Update the site-wide option since we're in the network admin.
            if ($network && is_multisite()) {
                update_site_option($key, $value);
            } else {
                update_option($key, $value);
            }

        }

        /**
         * Is Knowledgebase.
         *
         * @return string
         * @since 0.0.1
         */
        static public function knowledgebase_data()
        {

            $knowledgebase = array(
                'enable_knowledgebase' => true,
                'knowledgebase_url' => 'http://gutenbergpack.com',
            );

            return $knowledgebase;
        }

        /**
         * Is Knowledgebase.
         *
         * @return string
         * @since 0.0.1
         */
        static public function rate_data()
        {

            $support = array(
                'enable_rating' => true,
                'rating_url' => 'https://wordpress.org/support/plugin/unlimited-gutenberg-pack/reviews/?filter=5',
            );

            return $support;
        }

        /**
         * Provide Widget settings.
         *
         * @return array()
         * @since 0.0.1
         */
        static public function get_block_options()
        {

            $blocks = self::$block_list;
            $saved_blocks = self::get_admin_settings_option('_gutenberg_pack_blocks');
            if (is_array($blocks)) {

                foreach ($blocks as $slug => $data) {

                    $_slug = str_replace('gutenbergpack/', '', $slug);

                    if (isset($saved_blocks[$_slug])) {

                        if ('disabled' === $saved_blocks[$_slug]) {
                            $blocks[$slug]['is_activate'] = false;
                        } else {
                            $blocks[$slug]['is_activate'] = true;
                        }
                    } else {
                        $blocks[$slug]['is_activate'] = (isset($data['default'])) ? $data['default'] : false;
                    }
                }
            }

            self::$block_list = $blocks;

            return apply_filters('gutenberg_pack_enabled_blocks', self::$block_list);
        }
    }

    /**
     *  Prepare if class 'Gutenberg_Pack_Helper' exist.
     *  Kicking this off by calling 'get_instance()' method
     */
    Gutenberg_Pack_Helper::get_instance();
}
