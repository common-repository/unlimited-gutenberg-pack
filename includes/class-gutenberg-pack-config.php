<?php
/**
 * Gutenberg Pack Config.
 *
 * @package Gutenberg Pack
 */

if (!class_exists('Gutenberg_Pack_Config')) {

    /**
     * Class Gutenberg_Pack_Config.
     */
    class Gutenberg_Pack_Config
    {

        /**
         * Block Attributes
         *
         * @var block_attributes
         */
        public static $block_attributes = null;

        /**
         * Get Widget List.
         *
         * @since 0.0.1
         *
         * @return array The Widget List.
         */
        public static function get_block_attributes()
        {

            if (null === self::$block_attributes) {

                self::$block_attributes = array(

                    'gutenbergpack/testimonial' => array(
                        'slug' => 'testimonial',
                        'title' => __('Testimonial', 'gutenberg-pack'),
                        'description' => __('This block help you to show some amazing feedback from your clients.', 'gutenberg-pack'),
                        'default' => true,
                        'attributes' => array(
                            'textSize' => 17,
                            'textColor' => '#444444',
                            'backgroundColor' => '#f4f6f6',
                        ),
                    ), 'gutenbergpack/button-block' => array(
                        'slug' => 'button-block',
                        'title' => __('Button', 'gutenberg-pack'),
                        'description' => __('This block help you to show any button with link.', 'gutenberg-pack'),
                        'default' => true,
                        'attributes' => array(
                            'align' => 'center',
                            'size' => 'medium',
                            'buttonColor' => '#44c767',
                            'buttonTextColor' => '#ffffff',
                            'buttonRounded' => false,
                        ),
                    ), 'gutenbergpack/tabbed-content' => array(
                        'slug' => 'tabbed-content',
                        'title' => __('Tabbed Content', 'gutenberg-pack'),
                        'description' => __('This block help you to organize your content with Tab.', 'gutenberg-pack'),
                        'default' => true,
                        'attributes' => array(
                            'theme' => '#eeeeee',
                            'titleColor' => '#000000',
                            'timestamp' => 0,
                            'activeTab' => 0,
                            'id' => -1,
                        ),
                    ),
                    'gutenbergpack/call-to-action' => array(
                        'slug' => 'call-to-action',
                        'title' => __('Call to Action', 'gutenberg-pack'),
                        'description' => __('This block help you to show call to action section on your website.', 'gutenberg-pack'),
                        'default' => true,
                        'attributes' => array(
                            'headFontSize' => 30,
                            'headColor' => '#444444',
                            'contentFontSize' => 15,
                            'contentColor' => '#444444',
                            'buttonFontSize' => 14,
                            'buttonColor' => '#E27330',
                            'buttonTextColor' => '#ffffff',
                            'buttonWidth' => '250',
                            'ctaBackgroundColor' => '#f8f8f8',
                            'ctaBorderColor' => '#ECECEC',
                            'ctaBorderSize' => 2,
                            'contentAlign' => 'center',
                        ),
                    ),'gutenbergpack/featured' => array(
                        'slug' => 'featured',
                        'title' => __('Featured', 'gutenberg-pack'),
                        'description' => __('This block help you to show featured section on your website.', 'gutenberg-pack'),
                        'default' => true,
                        'attributes' => array(
                            'column' => 2,
                            'columnOneTitle' => __('Title One','gutenberg-pack'),
                            'columnTwoTitle' => __('Title Two','gutenberg-pack'),
                            'columnThreeTitle' => __('Title Three','gutenberg-pack'),
                            'columnOneBody' => __('Gutenberg is really awesome! Gutenberg Pack makes it more awesome!','gutenberg-pack'),
                            'columnTwoBody' => __('Gutenberg is really awesome! Gutenberg Pack makes it more awesome!','gutenberg-pack'),
                            'columnThreeBody' => __('Gutenberg is really awesome! Gutenberg Pack makes it more awesome!','gutenberg-pack'),

                        ),
                    ),
                );
            }
            return self::$block_attributes;
        }
    }
}
