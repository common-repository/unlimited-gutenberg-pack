<?php
/**
 * General Setting Form
 *
 * @package Gutenberg Pack
 */

$blocks = Gutenberg_Pack_Helper::get_block_options();
$kb_data = Gutenberg_Pack_Helper::knowledgebase_data();
$enable_kb = $kb_data['enable_knowledgebase'];
$kb_url = $kb_data['knowledgebase_url'];

$rate_data = Gutenberg_Pack_Helper::rate_data();
$enable_rating = $rate_data['enable_rating'];
$rating_url = $rate_data['rating_url'];
?>

<div class="gutenberg-pack-container gutenberg-pack-general">
    <div id="poststuff">
        <div id="post-body" class="columns-2">
            <div id="post-body-content">
                <!-- All WordPress Notices below header -->
                <h1 class="screen-reader-text"> <?php _e('General', 'gutenberg-pack'); ?> </h1>
                <div class="widgets postbox">
                    <h2 class="hndle gutenberg-pack-flex gutenberg-pack-widgets-heading">
                        <span><?php esc_html_e('Blocks', 'gutenberg-pack'); ?></span>
                        <div class="gutenberg-pack-bulk-actions-wrap">
                            <a class="bulk-action gutenberg-pack-activate-all button"> <?php esc_html_e('Activate All', 'gutenberg-pack'); ?> </a>
                            <a class="bulk-action gutenberg-pack-deactivate-all button"> <?php esc_html_e('Deactivate All', 'gutenberg-pack'); ?> </a>
                        </div>
                    </h2>
                    <div class="gutenberg-pack-list-section">
                        <?php
                        if (is_array($blocks) && !empty($blocks)) :
                            ?>
                            <ul class="gutenberg-pack-widget-list">
                                <?php
                                foreach ($blocks as $addon => $info) {

                                    $addon = str_replace('gutenbergpack/', '', $addon);

                                    $title_url = (isset($info['title_url']) && !empty($info['title_url'])) ? 'href="' . esc_url($info['title_url']) . '"' : '';
                                    $anchor_target = (isset($info['title_url']) && !empty($info['title_url'])) ? "target='_blank' rel='noopener'" : '';

                                    $class = 'deactivate';
                                    $link = array(
                                        'link_class' => 'gutenberg-pack-activate-widget',
                                        'link_text' => __('Activate', 'gutenberg-pack'),
                                    );

                                    if ($info['is_activate']) {
                                        $class = 'activate';
                                        $link = array(
                                            'link_class' => 'gutenberg-pack-deactivate-widget',
                                            'link_text' => __('Deactivate', 'gutenberg-pack'),
                                        );
                                    }

                                    echo '<li id="' . esc_attr($addon) . '"  class="' . esc_attr($class) . '"><a class="gutenberg-pack-widget-title"' . $title_url . $anchor_target . ' >' . esc_html($info['title']) . '</a><div class="gutenberg-pack-widget-link-wrapper">';

                                    printf(
                                        '<a href="%1$s" class="%2$s"> %3$s </a>',
                                        (isset($link['link_url']) && !empty($link['link_url'])) ? esc_url($link['link_url']) : '#',
                                        esc_attr($link['link_class']),
                                        esc_html($link['link_text'])
                                    );

                                    if ($info['is_activate'] && isset($info['setting_url'])) {

                                        printf(
                                            '<a href="%1$s" class="%2$s"> %3$s </a>',
                                            esc_url($info['setting_url']),
                                            esc_attr('gutenberg-pack-advanced-settings'),
                                            esc_html($info['setting_text'])
                                        );
                                    }

                                    echo '</div></li>';
                                }
                                ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="postbox-container gutenberg-pack-sidebar" id="postbox-container-1">
                <div id="side-sortables">
                    <div class="postbox">
                        <h2 class="hndle gutenberg-pack-normal-cusror">
                            <span class="dashicons dashicons-star-filled"></span>
                            <span><?php esc_html_e('Rate our work', 'gutenberg-pack'); ?></span>
                        </h2>
                        <div class="inside">
                            <p>
                                <?php
                                printf(
                                /* translators: %1$s: gutenberg pack name. */
                                    esc_html__('Love this plugin? Please do not forget to rate us!', 'gutenberg-pack'),
                                    GUTENBERG_PACK_PLUGIN_NAME
                                );
                                ?>
                            </p>
                            <?php
                            $gutenberg_pack_rate_us_link = apply_filters('gutenberg_pack_rate_us_link', $rating_url);
                            $gutenberg_pack_rate_us_link_text = apply_filters('gutenberg_pack_rate_us_link_text', __('Rate Us »', 'gutenberg-pack'));

                            printf(
                            /* translators: %1$s: gutenberg pack support link. */
                                '%1$s',
                                !empty($gutenberg_pack_rate_us_link) ? '<a href=' . esc_url($gutenberg_pack_rate_us_link) . ' target="_blank" rel="noopener">' . esc_html($gutenberg_pack_rate_us_link_text) . '</a>' :
                                    esc_html($gutenberg_pack_rate_us_link_text)
                            );
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /post-body -->
        <br class="clear">
    </div>
</div>
