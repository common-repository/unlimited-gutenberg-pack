<?php
/**
 * Gutenberg Pack Block Helper.
 *
 * @package Gutenberg Pack
 */

if ( ! class_exists( 'Gutenberg_Pack_Block_Helper' ) ) {

	/**
	 * Class GUTENBERG_PACK_Block_Helper.
	 */
	class Gutenberg_Pack_Block_Helper {

		/**
		 * Get Section Block CSS
		 *
		 * @since 0.0.1
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array The Widget List.
		 */
		public static function get_section_css( $attr, $id ) {

			// @codingStandardsIgnoreStart

			$defaults = Gutenberg_Pack_Helper::$block_list['gutenbergpack/section']['attributes'];

			$attr = array_merge( $defaults, $attr );

			$bg_type = ( isset( $attr['backgroundType'] ) ) ? $attr['backgroundType'] : 'none';

			$style = array(
				'padding-top'    => $attr['topPadding'] . 'px',
				'padding-bottom' => $attr['bottomPadding'] . 'px',
				'padding-left'   => $attr['leftPadding'] . 'px',
				'padding-right'  => $attr['rightPadding'] . 'px',
			);

			if ( 'right' == $attr['align'] ) {
				$style['margin-right']  = $attr['rightMargin'] . 'px';
				$style['margin-left']   = 'auto';
				$style['margin-top']    = $attr['topMargin'] . 'px';
				$style['margin-bottom'] = $attr['bottomMargin'] . 'px';
			} elseif ( 'left' == $attr['align'] ) {
				$style['margin-right']  = 'auto';
				$style['margin-left']   = $attr['leftMargin'] . 'px';
				$style['margin-top']    = $attr['topMargin'] . 'px';
				$style['margin-bottom'] = $attr['bottomMargin'] . 'px';
			} elseif ( 'center' == $attr['align'] ) {
				$style['margin-right']  = 'auto';
				$style['margin-left']   = 'auto';
				$style['margin-top']    = $attr['topMargin'] . 'px';
				$style['margin-bottom'] = $attr['bottomMargin'] . 'px';
			} else {
				$style['margin-right']  = $attr['rightMargin'] . 'px';
				$style['margin-left']   = $attr['leftMargin'] . 'px';
				$style['margin-top']    = $attr['topMargin'] . 'px';
				$style['margin-bottom'] = $attr['bottomMargin'] . 'px';
			}

			if ( "none" != $attr['borderStyle'] ) {
				$style["border-style"] = $attr['borderStyle'];
				$style["border-width"] = $attr['borderWidth'] . "px";
				$style["border-radius"] = $attr['borderRadius'] . "px";
				$style["border-color"] =  $attr['borderColor'];
			}

			$position = str_replace( '-', ' ', $attr['backgroundPosition'] );

			$section_width = '100%';

			if ( isset( $attr['contentWidth'] ) ) {

				if ( 'boxed' == $attr['contentWidth'] ) {
					if ( isset( $attr['width'] ) ) {
						$section_width = $attr['width'] . 'px';
					}
				}
			}

			$style['max-width'] = $section_width;

			if ( 'color' === $bg_type ) {

				$style['background-color'] = $attr['backgroundColor'];

			} elseif ( 'image' === $bg_type ) {

				$style['background-image']      = ( isset( $attr['backgroundImage'] ) ) ? "url('" . $attr['backgroundImage']['url'] . "' )" : null;
				$style['background-position']   = $position;
				$style['background-attachment'] = $attr['backgroundAttachment'];
				$style['background-repeat']     = $attr['backgroundRepeat'];
				$style['background-size']       = $attr['backgroundSize'];

			} elseif ( 'gradient' === $bg_type ) {
				$style['background-color'] = 'transparent';

				if ( 'linear' === $attr['gradientType'] ) {

					$style['background-image'] = 'linear-gradient(' . $attr['gradientAngle'] . 'deg, ' . $attr['gradientColor1'] . ' ' . $attr['gradientLocation1'] . '%, ' . $attr['gradientColor2'] . ' ' . $attr['gradientLocation2'] . '%)';
				} else {

					$style['background-image'] = 'radial-gradient( at center center, ' . $attr['gradientColor1'] . ' ' . $attr['gradientLocation1'] . '%, ' . $attr['gradientColor2'] . ' ' . $attr['gradientLocation2'] . '%)';
				}
			}

			$inner_width = '100%';

			if ( isset( $attr['contentWidth'] ) ) {
				if ( 'boxed' != $attr['contentWidth'] ) {
					if ( isset( $attr['innerWidth'] ) ) {
						$inner_width = $attr['innerWidth'] . 'px';
					}
				}
			}

			$selectors = array(
				'.gutenberg-pack-section__wrap'        => $style,
				' .gutenberg-pack-section__video-wrap' => array(
					'opacity' => ( isset( $attr['backgroundVideoOpacity'] ) && '' != $attr['backgroundVideoOpacity'] ) ? ( ( 100 - $attr['backgroundVideoOpacity'] ) / 100 ) : 0.5,
				),
				' .gutenberg-pack-section__inner-wrap' => array(
					'max-width' => $inner_width,
				),
			);

			if ( 'video' == $bg_type ) {
				$selectors[' > .gutenberg-pack-section__overlay'] = array(
					'opacity'          => 1,
					'background-color' => $attr['backgroundVideoColor'],
				);
			} else if ( 'image' == $bg_type ) {
				$selectors[' > .gutenberg-pack-section__overlay'] = array(
					'opacity' => ( isset( $attr['backgroundOpacity'] ) && '' != $attr['backgroundOpacity'] ) ? $attr['backgroundOpacity'] / 100 : 0,
					'background-color' => $attr['backgroundImageColor'],
				);
			} else {
				$selectors[' > .gutenberg-pack-section__overlay'] = array(
					'opacity' => ( isset( $attr['backgroundOpacity'] ) && '' != $attr['backgroundOpacity'] ) ? $attr['backgroundOpacity'] / 100 : 0,
				);
			}

			// @codingStandardsIgnoreEnd

			return Gutenberg_Pack_Helper::generate_css( $selectors, '#gutenberg-pack-section-' . $id );
		}

		/**
		 * Get Advanced Heading Block CSS
		 *
		 * @since 0.0.1
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array The Widget List.
		 */
		public static function get_adv_heading_css( $attr, $id ) {

			// @codingStandardsIgnoreStart

			$defaults = Gutenberg_Pack_Helper::$block_list['gutenbergpack/advanced-heading']['attributes'];

			$attr = array_merge( $defaults, (array) $attr );

			$selectors = array(
				' .gutenberg-pack-heading-text'        => array(
					'text-align' => $attr['headingAlign'],
					'font-size' => $attr['headFontSize'] . "px",
					'color' => $attr['headingColor'],
					'margin-bottom' => $attr['headSpace'] . "px",
				),
				' .gutenberg-pack-separator-wrap' => array(
					'text-align' => $attr['headingAlign'],
				),
				' .gutenberg-pack-separator' => array(
					'border-top-width' => $attr['separatorHeight'] . "px",
					'width' => $attr['separatorWidth'] . "%",
					'border-color' => $attr['separatorColor'],
					'margin-bottom' => $attr['separatorSpace'] . "px",
				),
				' .gutenberg-pack-desc-text' => array(
					'text-align' => $attr['headingAlign'],
					'font-size' => $attr['subHeadFontSize'] . "px",
					'color' => $attr['subHeadingColor'],
					'margin-bottom' => $attr['subHeadSpace'] . "px",
				)

			);

			// @codingStandardsIgnoreEnd

			return Gutenberg_Pack_Helper::generate_css( $selectors, '#gutenberg-pack-adv-heading-' . $id );
		}

		/**
		 * Get Multi Buttons Block CSS
		 *
		 * @since 0.0.1
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array The Widget List.
		 */
		public static function get_buttons_css( $attr, $id ) {

			// @codingStandardsIgnoreStart

			$defaults = Gutenberg_Pack_Helper::$block_list['gutenbergpack/buttons']['attributes'];

			$attr = array_merge( $defaults, (array) $attr );

			$alignment = ( $attr['align'] == 'left' ) ? 'flex-start' : ( ( $attr['align'] == 'right' ) ? 'flex-end' : 'center' );

			$m_selectors = array();
			$t_selectors = array();

			$selectors = array(
				' .gutenberg-pack-button__wrapper' => array(
					'margin-left' => (  $attr['gap']/2 ) . 'px',
					'margin-right' => (  $attr['gap']/2 ) . 'px'
				),
				' .gutenberg-pack-button__wrapper:first-child' => array (
					'margin-left' => 0
				),
				' .gutenberg-pack-button__wrapper:last-child' => array (
					'margin-right' => 0
				),
				' .gutenberg-pack-buttons__wrap' => array (
					'justify-content' => $alignment,
					'-webkit-box-pack'=> $alignment,
					'-ms-flex-pack' => $alignment,
					'justify-content' => $alignment,
					'-webkit-box-align' => $alignment,
					'-ms-flex-align' => $alignment,
					'align-items' => $alignment,
				)
			);

			foreach ( $attr['buttons'] as $key => $button ) {

				$button['size'] = ( isset( $button['size'] ) ) ? $button['size'] : '';
				$button['borderWidth'] = ( isset( $button['borderWidth'] ) ) ? $button['borderWidth'] : '';
				$button['borderStyle'] = ( isset( $button['borderStyle'] ) ) ? $button['borderStyle'] : '';
				$button['borderColor'] = ( isset( $button['borderColor'] ) ) ? $button['borderColor'] : '';
				$button['borderRadius'] = ( isset( $button['borderRadius'] ) ) ? $button['borderRadius'] : '';
				$button['background'] = ( isset( $button['background'] ) ) ? $button['background'] : '';
				$button['hBackground'] = ( isset( $button['hBackground'] ) ) ? $button['hBackground'] : '';
				$button['borderHColor'] = ( isset( $button['borderHColor'] ) ) ? $button['borderHColor'] : '';
				$button['vPadding'] = ( isset( $button['vPadding'] ) ) ? $button['vPadding'] : '';
				$button['hPadding'] = ( isset( $button['hPadding'] ) ) ? $button['hPadding'] : '';
				$button['color'] = ( isset( $button['color'] ) ) ? $button['color'] : '';
				$button['hColor'] = ( isset( $button['hColor'] ) ) ? $button['hColor'] : '';

				if ( $attr['btn_count'] <= $key ) {
					break;
				}

				$selectors[' .gutenberg-pack-buttons-repeater-' . $key] = array (
					'font-size'  => $button['size'] . 'px',
					'border' => $button['borderWidth'] . 'px ' . $button['borderStyle'] . ' ' . $button['borderColor'],
					'border-radius'  => $button['borderRadius'] . 'px',
					'background' => $button['background']
				);

				$selectors[' .gutenberg-pack-buttons-repeater-' . $key . ':hover'] = array (
					'background' => $button['hBackground'],
					'border' => $button['borderWidth'] . 'px ' . $button['borderStyle'] . ' ' . $button['borderHColor'],
				);

				$selectors[' .gutenberg-pack-buttons-repeater-' . $key . ' a.gutenberg-pack-button__link'] = array (
					'padding'  => $button['vPadding'] . 'px ' . $button['hPadding'] . 'px',
					'color' => $button['color']
				);

				$selectors[' .gutenberg-pack-buttons-repeater-' . $key . ':hover a.gutenberg-pack-button__link'] = array (
					'color' => $button['hColor']
				);
			}

			if ( "desktop" == $attr['stack'] ) {

				$selectors[" .gutenberg-pack-button__wrapper"] = array (
					'margin-left' => 0,
					'margin-right' => 0,
					"margin-bottom" => $attr['gap'] . "px"
				);

				$selectors[" .gutenberg-pack-buttons__wrap"] = array (
					 "flex-direction" => "column"
				);

				$selectors[" .gutenberg-pack-button__wrapper:last-child"] = array (
					"margin-bottom" => 0
				);

			} else if ( "tablet" == $attr['stack'] ) {

				$t_selectors[" .gutenberg-pack-button__wrapper"] = array (
					'margin-left' => 0,
					'margin-right' => 0,
					"margin-bottom" => $attr['gap'] . "px"
				);

				$t_selectors[" .gutenberg-pack-buttons__wrap"] = array (
					 "flex-direction" => "column"
				);

				$t_selectors[" .gutenberg-pack-button__wrapper:last-child"] = array (
					"margin-bottom" => 0
				);

			} else if ( "mobile" == $attr['stack'] ) {

				$m_selectors[" .gutenberg-pack-button__wrapper"] = array (
					'margin-left' => 0,
					'margin-right' => 0,
					"margin-bottom" => $attr['gap'] . "px"
				);

				$m_selectors[" .gutenberg-pack-buttons__wrap"] = array (
					 "flex-direction" => "column"
				);

				$m_selectors[" .gutenberg-pack-button__wrapper:last-child"] = array (
					"margin-bottom" => 0
				);
			}

			// @codingStandardsIgnoreEnd

			$desktop = Gutenberg_Pack_Helper::generate_css( $selectors, '#gutenberg-pack-buttons-' . $id );

			$tablet = Gutenberg_Pack_Helper::generate_responsive_css( '@media only screen and (max-width: 976px)', $t_selectors, '#gutenberg-pack-buttons-' . $id );

			$mobile = Gutenberg_Pack_Helper::generate_responsive_css( '@media only screen and (max-width: 767px)', $m_selectors, '#gutenberg-pack-buttons-' . $id );

			return $desktop . $tablet . $mobile;
		}


		/**
		 * Get Info Box CSS
		 *
		 * @since 0.0.1
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array The Widget List.
		 */
		public static function get_info_box_css( $attr, $id ) {

			// @codingStandardsIgnoreStart.
			$defaults = Gutenberg_Pack_Helper::$block_list['gutenbergpack/info-box']['attributes'];

			$attr = (object) array_merge( $defaults, (array) $attr );

			$selectors = array(
				' .gutenberg-pack-ifb-icon'  => array(
					'height'      => $attr->iconSize. "px",
					'width'       => $attr->iconSize. "px",
					'line-height' => $attr->iconSize. "px",
				),
				' .gutenberg-pack-ifb-icon > span' => array(
					'font-size'   => $attr->iconSize. "px",
					'height'      => $attr->iconSize. "px",
					'width'       => $attr->iconSize. "px",
					'line-height' => $attr->iconSize. "px",
					'color'       => $attr->iconColor,
				),
				' .gutenberg-pack-ifb-icon:hover > span' => array(
					'color' => $attr->iconHover ,
				),
	            ' .gutenberg-pack-infobox__content-wrap .gutenberg-pack-ifb-imgicon-wrap' => array(
	                    'margin-left'   => $attr->iconLeftMargin.'px',
	                    'margin-right'  => $attr->iconRightMargin.'px',
	                    'margin-top'    => $attr->iconTopMargin.'px',
	                    'margin-bottom' => $attr->iconBottomMargin.'px',
	            ),

	            // Image.
	            ' .gutenberg-pack-ifb-image-content > img' => array(
	            		'width'=> $attr->imageWidth.'px',
	                    'max-width'=> $attr->imageWidth.'px',
	            ),

	            ' .gutenberg-pack-infobox .gutenberg-pack-ifb-image-content img' => array(
	            		'border-radius' => $attr->iconimgBorderRadius.'px',
	                ),


	            // CTA style .
	            ' .gutenberg-pack-infobox-cta-link' => array(
	                'font-size'   => $attr->ctaFontSize.'px',
	                'color'       => $attr->ctaLinkColor,
	            ),
	            ' .gutenberg-pack-ifb-button-wrapper .gutenberg-pack-infobox-cta-link' => array(
	                'font-size'        => $attr->ctaFontSize.'px',
	                'color'            => $attr->ctaBtnLinkColor,
	                'background-color' => $attr->ctaBgColor,
	                'border-style'     => $attr->ctaBorderStyle,
	                'border-color'     => $attr->ctaBorderColor,
	                'border-radius'    => $attr->ctaBorderRadius . "px",
	                'border-width'     => $attr->ctaBorderWidth . "px",
	                'padding-top'      => $attr->ctaBtnVertPadding . "px",
	                'padding-bottom'   => $attr->ctaBtnVertPadding . "px",
	                'padding-left'     => $attr->ctaBtnHrPadding . "px",
	                'padding-right'    => $attr->ctaBtnHrPadding . "px",

	            ),

	           // Prefix Style.
	            ' .gutenberg-pack-ifb-title-prefix' => array(
	                'font-size'     => $attr->prefixFontSize.'px',
	                'color'         => $attr->prefixColor,
	                'margin-bottom' => $attr->prefixSpace.'px',
	            ),

	            // Title Style.
	            ' .gutenberg-pack-ifb-title' => array(
	                'font-size'     => $attr->headFontSize.'px',
	                'color'         => $attr->headingColor,
	                'margin-bottom' => $attr->headSpace.'px',
	            ),

	            // Description Style.
	            ' .gutenberg-pack-ifb-desc' => array(
	                'font-size'     => $attr->subHeadFontSize.'px',
	                'color'         => $attr->subHeadingColor,
	                'margin-bottom' => $attr->subHeadSpace.'px',
	            ),

	            // Seperator.
	            ' .gutenberg-pack-ifb-separator' => array(
	                'width'            => $attr->seperatorWidth.'%',
	                'border-top-width' => $attr->seperatorThickness.'px',
	                'border-top-color' => $attr->seperatorColor,
	                'border-top-style' => $attr->seperatorStyle,
	            ),
	            ' .gutenberg-pack-ifb-separator-parent' => array(
	                'margin-bottom' => $attr->seperatorSpace.'px',
	            ),

			);

			if( 'above-title' === $attr->iconimgPosition ||  'below-title' === $attr->iconimgPosition ){
               	$selectors[' .gutenberg-pack-infobox__content-wrap'] = array(
	                'text-align' => $attr->headingAlign,
	            );
            }

			// @codingStandardsIgnoreEnd.
			return Gutenberg_Pack_Helper::generate_css( $selectors, '#gutenberg-pack-infobox-' . $id );
		}

		/**
		 * Get Testimonial CSS
		 *
		 * @since 0.0.1
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array The Widget List.
		 */
		public static function get_testimonial_css( $attr, $id ) {

			// @codingStandardsIgnoreStart.

			$defaults = Gutenberg_Pack_Helper::$block_list['gutenbergpack/testimonial']['attributes'];

			$attr = (object) array_merge( $defaults, (array) $attr );

			$position = str_replace( '-', ' ', $attr->backgroundPosition );
			$selectors = array(
				' .gutenberg-pack-testimonial__wrap' => array(
					'padding-left'   => ( ($attr->columnGap) /2 ) . 'px',
					'padding-right'  => ( ($attr->columnGap) /2 ) . 'px',
					'margin-bottom' => $attr->rowGap . 'px',
				),
				' .gutenberg-pack-testimonial__wrap .gutenberg-pack-tm__image-content' => array(
					'padding-left'   => $attr->imgHrPadding . 'px',
					'padding-right'  => $attr->imgHrPadding . 'px',
					'padding-top'   => $attr->imgVrPadding . 'px',
					'padding-bottom'  => $attr->imgVrPadding . 'px',
				),
				' .gutenberg-pack-tm__image img' => array(
					'width'   => $attr->imageWidth . 'px',
					'max-width'  => $attr->imageWidth . 'px',
				),
				' .gutenberg-pack-tm__content' => array(
					'text-align'   => $attr->headingAlign,
					'padding'  => $attr->contentPadding . 'px',
				),
				' .gutenberg-pack-tm__author-name' => array(
					'color'   => $attr->authorColor,
					'font-size'  => $attr->nameFontSize . 'px',
					'margin-bottom'  => $attr->nameSpace . 'px',
				),
				' .gutenberg-pack-tm__company' => array(
					'color'   => $attr->companyColor,
					'font-size'  => $attr->companyFontSize . 'px',
				),
				' .gutenberg-pack-tm__desc' => array(
					'color'   => $attr->descColor,
					'font-size'  => $attr->descFontSize . 'px',
					'margin-bottom'  => $attr->descSpace . 'px',
				),
				' .gutenberg-pack-testimonial__wrap.gutenberg-pack-tm__bg-type-color .gutenberg-pack-tm__content' => array(
					'background-color'   => $attr->backgroundColor,
				),
				' .gutenberg-pack-testimonial__wrap.gutenberg-pack-tm__bg-type-image .gutenberg-pack-tm__content' => array(
					'background-image'   => ( isset( $attr->backgroundImage['url'] ) ) ? 'url("'.$attr->backgroundImage['url'].'")' : null,
					'background-position'=> $position,
                    'background-repeat'=> $attr->backgroundRepeat,
                    'background-size'=> $attr->backgroundSize,
				),
				' .gutenberg-pack-testimonial__wrap.gutenberg-pack-tm__bg-type-image .gutenberg-pack-tm__overlay' => array(
					'background-color'   => $attr->backgroundImageColor,
					'opacity'   => ( isset( $attr->backgroundOpacity ) && '' != $attr->backgroundOpacity ) ? ( ( 100 - $attr->backgroundOpacity ) / 100 ) : '0.5',
				),
				' .gutenberg-pack-testimonial__wrap .gutenberg-pack-tm__content' => array(
					'border-color'   => $attr->borderColor,
					'border-style'   => $attr->borderStyle,
					'border-width'  => $attr->borderWidth . 'px',
					'border-radius'  => $attr->borderRadius . 'px',
				),
			);

			$r_selectors = array(
				' .gutenberg-pack-tm__content' => array(
					'text-align' => 'center',
				),
			);

			// @codingStandardsIgnoreEnd.
			$desktop = Gutenberg_Pack_Helper::generate_css( $selectors, '#gutenberg-pack-testimonial-' . $id );

			$mobile = Gutenberg_Pack_Helper::generate_responsive_css( '@media only screen and (max-width: 767px)', $r_selectors, '#gutenberg-pack-testimonial-' . $id );

			return $desktop . $mobile;

		}

		/**
		 * Get Team Block CSS
		 *
		 * @since 0.0.1
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array The Widget List.
		 */
		public static function get_team_css( $attr, $id ) {

			// @codingStandardsIgnoreStart

			$defaults = Gutenberg_Pack_Helper::$block_list['gutenbergpack/team']['attributes'];

			$attr = array_merge( $defaults, (array) $attr );

			$selectors = array(
				" p.gutenberg-pack-team__desc" => array(
					"font-size" => $attr['descFontSize'] . "px",
					"color" => $attr['descColor'],
					"margin-bottom" => $attr['descSpace'] . "px",
				),
				" .gutenberg-pack-team__prefix" => array(
					"font-size" => $attr['prefixFontSize'] . "px",
					"color" => $attr['prefixColor'],
				),
				" .gutenberg-pack-team__desc-wrap" => array(
					"margin-top" => $attr['prefixSpace'] . "px",
				),
				" .gutenberg-pack-team__social-icon a" => array(
					"color" => $attr['socialColor'],
					"font-size" => $attr['socialFontSize'] . "px",
					"width" => $attr['socialFontSize'] . "px",
					"height" => $attr['socialFontSize'] . "px",
				),
				" .gutenberg-pack-team__social-icon:hover a" => array(
					"color" => $attr['socialHoverColor'],
				),
				".gutenberg-pack-team__image-position-left .gutenberg-pack-team__social-icon" => array(
					"margin-right" => $attr['socialSpace'] . "px",
					"margin-left" => "0",
				),
				".gutenberg-pack-team__image-position-right .gutenberg-pack-team__social-icon" => array(
					"margin-left" => $attr['socialSpace'] . "px",
					"margin-right" => "0",
				),
				".gutenberg-pack-team__image-position-above.gutenberg-pack-team__align-center .gutenberg-pack-team__social-icon" => array(
					"margin-right" => ( $attr['socialSpace'] / 2 ) . "px",
					"margin-left" => ( $attr['socialSpace'] / 2 ) . "px",
				),
				".gutenberg-pack-team__image-position-above.gutenberg-pack-team__align-left .gutenberg-pack-team__social-icon" => array(
					"margin-right" => $attr['socialSpace'] . "px",
					"margin-left" => "0",
				),
				".gutenberg-pack-team__image-position-above.gutenberg-pack-team__align-right .gutenberg-pack-team__social-icon" => array(
					"margin-left" => $attr['socialSpace'] . "px",
					"margin-right" => "0",
				),
				" .gutenberg-pack-team__image-wrap" => array(
					"margin-top" => $attr['imgTopMargin'] . "px",
					"margin-bottom" => $attr['imgBottomMargin'] . "px",
					"margin-left" => $attr['imgLeftMargin'] . "px",
					"margin-right" => $attr['imgRightMargin'] . "px",
					"width" => $attr['imgWidth'] . "px"
				),
			);

			if( 'above' == $attr['imgPosition'] ) {
				if ( 'center' == $attr['align'] ) {
					$selectors[" .gutenberg-pack-team__image-wrap"]["margin-left"] = "auto";
					$selectors[" .gutenberg-pack-team__image-wrap"]["margin-right"] = "auto";
				} else if ( 'left' == $attr['align'] ) {
					$selectors[" .gutenberg-pack-team__image-wrap"]["margin-right"] = "auto";
				} else if ( 'right' == $attr['align'] ) {
					$selectors[" .gutenberg-pack-team__image-wrap"]["margin-left"] = "auto";
				}
			}

			if ( "above" != $attr['imgPosition'] ) {
				if ( "middle" == $attr['imgAlign'] ) {
					$selectors[" .gutenberg-pack-team__image-wrap"]["align-self"] = "center";
				}
			}

			$selectors[" " . $attr['tag'] . ".gutenberg-pack-team__title"] = array(
				"font-size" => $attr['titleFontSize'] . "px",
				"color" => $attr['titleColor'],
				"margin-bottom" => $attr['titleSpace'] . "px",
			);

			// @codingStandardsIgnoreEnd

			return Gutenberg_Pack_Helper::generate_css( $selectors, '#gutenberg-pack-team-' . $id );
		}

		/**
		 * Get Social Share Block CSS
		 *
		 * @since 0.0.1
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array The Widget List.
		 */
		public static function get_social_share_css( $attr, $id ) {

			// @codingStandardsIgnoreStart

			$defaults = Gutenberg_Pack_Helper::$block_list['gutenbergpack/social-share']['attributes'];

			$attr = array_merge( $defaults, (array) $attr );

			$alignment = ( $attr['align'] == 'left' ) ? 'flex-start' : ( ( $attr['align'] == 'right' ) ? 'flex-end' : 'center' );

			$m_selectors = array();
			$t_selectors = array();

			$selectors[".gutenberg-pack-social-share__layout-vertical .gutenberg-pack-ss__wrapper"] = array(
				"margin-left"  => 0,
				"margin-right"  => 0,
				"margin-bottom"  => $attr['gap'] . "px"
			);

			$selectors[".gutenberg-pack-social-share__layout-vertical .gutenberg-pack-social-share__wrap"] = array(
				"flex-direction" => "column"
			);

			$selectors[".gutenberg-pack-social-share__layout-vertical .gutenberg-pack-ss__wrapper:last-child"] = array(
				"margin-bottom"  => 0
			);

			$selectors[".gutenberg-pack-social-share__layout-horizontal .gutenberg-pack-ss__wrapper"] = array(
				"margin-left"  => ( $attr['gap']/2 ) . "px",
				"margin-right"  => ( $attr['gap']/2 ) . "px"
			);

			$selectors[".gutenberg-pack-social-share__layout-horizontal .gutenberg-pack-ss__wrapper:first-child"] = array(
				"margin-left"  => 0
			);

			$selectors[".gutenberg-pack-social-share__layout-horizontal .gutenberg-pack-ss__wrapper:last-child"] = array(
				"margin-right"  => 0
			);

			$selectors[" .gutenberg-pack-ss__wrapper"] = array(
				"width" => $attr['size'] . "px"
			);

			$selectors[" .gutenberg-pack-ss__source-image"] = array(
				"width" => $attr['size'] . "px"
			);

			$selectors[" .gutenberg-pack-ss__source-icon"] = array(
				"width" => $attr['size'] . "px",
				"height" => $attr['size'] . "px",
				"font-size" => $attr['size'] . "px"
			);

			$selectors[" .gutenberg-pack-ss__source-icon:before"] = array(
				"width" => $attr['size'] . "px",
				"height" => $attr['size'] . "px",
				"font-size" => $attr['size'] . "px"
			);

			foreach ( $attr['socials'] as $key => $social ) {

				$social['icon_color'] = ( isset( $social['icon_color'] ) ) ? $social['icon_color'] : '';
				$social['icon_hover_color'] = ( isset( $social['icon_hover_color'] ) ) ? $social['icon_hover_color'] : '';

				if ( $attr['social_count'] <= $key ) {
					break;
				}

				$selectors[" .gutenberg-pack-ss-repeater-" . $key . " a.gutenberg-pack-ss__link"] = array (
					"color" => $social['icon_color']
				);

				$selectors[" .gutenberg-pack-ss-repeater-" . $key . ":hover a.gutenberg-pack-ss__link"] = array (
					"color" => $social['icon_hover_color']
				);
			}

			$selectors[" .gutenberg-pack-social-share__wrap"] = array(
				"justify-content"  => $alignment,
				"-webkit-box-pack" => $alignment,
				"-ms-flex-pack" => $alignment,
				"justify-content" => $alignment,
			);

			if ( 'horizontal' == $attr['social_layout'] ) {

				if ( "desktop" == $attr['stack'] ) {

					$selectors[" .gutenberg-pack-ss__wrapper"] = array (
						"margin-left"  => 0,
						"margin-right"  => 0,
						"margin-bottom"  => $attr['gap'] . "px"
					);

					$selectors[" .gutenberg-pack-social-share__wrap"] = array (
						"flex-direction" => "column"
					);

					$selectors[" .gutenberg-pack-ss__wrapper:last-child"] = array (
						"margin-bottom" => 0
					);

				} else if ( "tablet" == $attr['stack'] ) {

					$t_selectors[" .gutenberg-pack-ss__wrapper"] = array (
						"margin-left" => 0,
						"margin-right" => 0,
						"margin-bottom" => $attr['gap'] . "px"
					);

					$t_selectors[" .gutenberg-pack-social-share__wrap"] = array (
						"flex-direction" => "column"
					);

					$t_selectors[" .gutenberg-pack-ss__wrapper:last-child"] = array (
						"margin-bottom" => 0
					);

				} else if ( "mobile" == $attr['stack'] ) {

					$m_selectors[" .gutenberg-pack-ss__wrapper"] = array (
						"margin-left" => 0,
						"margin-right" => 0,
						"margin-bottom" => $attr['gap'] . "px"
					);

					$m_selectors[" .gutenberg-pack-social-share__wrap"] = array (
						"flex-direction" => "column"
					);

					$m_selectors[" .gutenberg-pack-ss__wrapper:last-child"] = array (
						"margin-bottom" => 0
					);
				}
			}

			// @codingStandardsIgnoreEnd

			$desktop = Gutenberg_Pack_Helper::generate_css( $selectors, '#gutenberg-pack-social-share-' . $id );

			$tablet = Gutenberg_Pack_Helper::generate_responsive_css( '@media only screen and (max-width: 976px)', $t_selectors, '#gutenberg-pack-social-share-' . $id );

			$mobile = Gutenberg_Pack_Helper::generate_responsive_css( '@media only screen and (max-width: 767px)', $m_selectors, '#gutenberg-pack-social-share-' . $id );

			return $desktop . $tablet . $mobile;
		}

		/**
		 * Get Icon List Block CSS
		 *
		 * @since 0.0.1
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array The Widget List.
		 */
		public static function get_icon_list_css( $attr, $id ) {

			// @codingStandardsIgnoreStart

			$defaults = Gutenberg_Pack_Helper::$block_list['gutenbergpack/icon-list']['attributes'];

			$attr = array_merge( $defaults, (array) $attr );

			$alignment = ( $attr['align'] == 'left' ) ? 'flex-start' : ( ( $attr['align'] == 'right' ) ? 'flex-end' : 'center' );

			$m_selectors = array();
			$t_selectors = array();

			$selectors[".gutenberg-pack-icon-list__layout-vertical .gutenberg-pack-icon-list__wrapper"] = array(
				"margin-left"  => 0,
				"margin-right"  => 0,
				"margin-bottom"  => $attr['gap'] . "px"
			);

			$selectors[".gutenberg-pack-icon-list__layout-vertical .gutenberg-pack-icon-list__wrap"] = array(
				 "flex-direction" => "column"
			);

			$selectors[".gutenberg-pack-icon-list__layout-vertical .gutenberg-pack-icon-list__wrapper:last-child"] = array(
				"margin-bottom"  => 0
			);

			$selectors[".gutenberg-pack-icon-list__layout-horizontal .gutenberg-pack-icon-list__wrapper"] = array(
				"margin-left"  => ( $attr['gap']/2 ) . "px",
				"margin-right"  => ( $attr['gap']/2 ) . "px"
			);

			$selectors[".gutenberg-pack-icon-list__layout-horizontal .gutenberg-pack-icon-list__wrapper:first-child"] = array(
				"margin-left"  => 0
			);

			$selectors[".gutenberg-pack-icon-list__layout-horizontal .gutenberg-pack-icon-list__wrapper:last-child"] = array(
				"margin-right"  => 0
			);

			$selectors[" .gutenberg-pack-icon-list__source-image"] = array(
				"width" => $attr['size'] . "px"
			);

			$selectors[" .gutenberg-pack-icon-list__source-icon"] = array(
				"width" => $attr['size'] . "px",
				"height" => $attr['size'] . "px",
				"font-size" => $attr['size'] . "px"
			);

			$selectors[" .gutenberg-pack-icon-list__source-icon:before"] = array(
				"width" => $attr['size'] . "px",
				"height" => $attr['size'] . "px",
				"font-size" => $attr['size'] . "px"
			);

			$selectors[" .gutenberg-pack-icon-list__label-wrap"] = array(
				"text-align" => $attr['align']
			);

			if ( 'right' == $attr['align'] ) {
				$selectors[":not(.gutenberg-pack-icon-list__no-label) .gutenberg-pack-icon-list__source-wrap"] = array(
					"margin-left" => $attr['inner_gap'] . "px"
				);
				$selectors[" .gutenberg-pack-icon-list__content-wrap"] = array(
					"flex-direction" => "row-reverse"
				);
			} else {
				$selectors[":not(.gutenberg-pack-icon-list__no-label) .gutenberg-pack-icon-list__source-wrap"] = array(
					"margin-right" => $attr['inner_gap'] . "px"
				);
			}

			foreach ( $attr['icons'] as $key => $icon ) {

				$icon['icon_color'] = ( isset( $icon['icon_color'] ) ) ? $icon['icon_color'] : '';
				$icon['icon_hover_color'] = ( isset( $icon['icon_hover_color'] ) ) ? $icon['icon_hover_color'] : '';
				$icon['label_color'] = ( isset( $icon['label_color'] ) ) ? $icon['label_color'] : '';
				$icon['label_hover_color'] = ( isset( $icon['label_hover_color'] ) ) ? $icon['label_hover_color'] : '';

				if ( $attr['icon_count'] <= $key ) {
					break;
				}

				$selectors[" .gutenberg-pack-icon-list-repeater-" . $key . " .gutenberg-pack-icon-list__source-icon"] = array (
					"color" => $icon['icon_color']
				);

				$selectors[" .gutenberg-pack-icon-list-repeater-" . $key . ":hover .gutenberg-pack-icon-list__source-icon"] = array (
					"color" => $icon['icon_hover_color']
				);

				$selectors[" .gutenberg-pack-icon-list-repeater-" . $key . " .gutenberg-pack-icon-list__label"] = array (
					"color" => $icon['label_color'],
					"font-size" => $attr['fontSize'] . 'px'
				);

				$selectors[" .gutenberg-pack-icon-list-repeater-" . $key . ":hover .gutenberg-pack-icon-list__label"] = array (
					"color" => $icon['label_hover_color']
				);
			}

			$selectors[" .gutenberg-pack-icon-list__wrap"] = array(
				"justify-content"  => $alignment,
				"-webkit-box-pack" => $alignment,
				"-ms-flex-pack" => $alignment,
				"justify-content" => $alignment,
				"-webkit-box-align" => $alignment,
				"-ms-flex-align" => $alignment,
				"align-items" => $alignment,
			);

			if ( 'horizontal' == $attr['icon_layout'] ) {

				if ( "tablet" == $attr['stack'] ) {

					$t_selectors[" .gutenberg-pack-icon-list__wrap .gutenberg-pack-icon-list__wrapper"] = array (
						"margin-left" => 0,
						"margin-right" => 0,
						"margin-bottom" => $attr['gap'] . "px"
					);

					$t_selectors[" .gutenberg-pack-icon-list__wrap"] = array (
						"flex-direction" => "column"
					);

					$t_selectors[" .gutenberg-pack-icon-list__wrap .gutenberg-pack-icon-list__wrapper:last-child"] = array (
						"margin-bottom" => 0
					);

				} else if ( "mobile" == $attr['stack'] ) {

					$m_selectors[" .gutenberg-pack-icon-list__wrap .gutenberg-pack-icon-list__wrapper"] = array (
						"margin-left" => 0,
						"margin-right" => 0,
						"margin-bottom" => $attr['gap'] . "px"
					);

					$m_selectors[" .gutenberg-pack-icon-list__wrap"] = array (
						"flex-direction" => "column"
					);

					$m_selectors[" .gutenberg-pack-icon-list__wrap .gutenberg-pack-icon-list__wrapper:last-child"] = array (
						"margin-bottom" => 0
					);
				}
			}

			// @codingStandardsIgnoreEnd

			$desktop = Gutenberg_Pack_Helper::generate_css( $selectors, '#gutenberg-pack-icon-list-' . $id );

			$tablet = Gutenberg_Pack_Helper::generate_responsive_css( '@media only screen and (max-width: 976px)', $t_selectors, '#gutenberg-pack-icon-list-' . $id );

			$mobile = Gutenberg_Pack_Helper::generate_responsive_css( '@media only screen and (max-width: 767px)', $m_selectors, '#gutenberg-pack-icon-list-' . $id );

			return $desktop . $tablet . $mobile;
		}

		/**
		 * Get Content Timeline Block CSS
		 *
		 * @since 0.0.1
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array The Widget List.
		 */
		public static function get_content_timeline_css( $attr, $id ) {

			// @codingStandardsIgnoreStart

			$defaults = Gutenberg_Pack_Helper::$block_list['gutenbergpack/content-timeline']['attributes'];

			$attr = array_merge( $defaults, (array) $attr );

			$t_selectors = array();

			$selectors[" .gutenberg-pack-timeline__heading"] = array(
				"text-align"  => $attr['align'],
				"color"  => $attr['headingColor'],
				"font-size"  => $attr['headFontSize'] . "px"
			);

			$selectors[" .gutenberg-pack-timeline__heading-text"] = array(
				"margin-bottom"  => $attr['headSpace'] . "px"
			);

			$selectors[" .gutenberg-pack-timeline-desc-content"] = array(
				"text-align"  => $attr['align'],
				"color"  => $attr['subHeadingColor'],
				"font-size"  => $attr['subHeadFontSize'] . "px",
			);
			$selectors[' .gutenberg-pack-timeline__events-new'] = array(
                    'text-align' => $attr['align']
                );
            $selectors['.gutenberg-pack-timeline__date-inner'] = array(
                    'text-align' => $attr['align']
                );

            $selectors[' .gutenberg-pack-timeline__center-block .gutenberg-pack-timeline__day-right .gutenberg-pack-timeline__arrow:after'] = array(
                    'border-left-color'  => $attr['backgroundColor']
                );

           	$selectors[' .gutenberg-pack-timeline__right-block .gutenberg-pack-timeline__day-right .gutenberg-pack-timeline__arrow:after'] = array(
                    'border-left-color'  => $attr['backgroundColor']
                );

            $selectors[' .gutenberg-pack-timeline__center-block .gutenberg-pack-timeline__day-left .gutenberg-pack-timeline__arrow:after'] = array(
                    'border-right-color'  => $attr['backgroundColor']
                );

            $selectors[' .gutenberg-pack-timeline__left-block .gutenberg-pack-timeline__day-left .gutenberg-pack-timeline__arrow:after'] = array(
                    'border-right-color'  => $attr['backgroundColor']
                );

            $selectors[' .gutenberg-pack-timeline__line__inner'] = array(
                    'background-color'  => $attr['separatorFillColor']
                );

            $selectors[' .gutenberg-pack-timeline__line'] = array(
                    'background-color'  => $attr['separatorColor'],
                    'width'  => $attr['separatorwidth'].'px'
                );

            $selectors[' .gutenberg-pack-timeline__right-block .gutenberg-pack-timeline__line'] = array(
                    'right' => 'calc( '.$attr['connectorBgsize'].'px / 2 )',
                );

            $selectors[' .gutenberg-pack-timeline__left-block .gutenberg-pack-timeline__line'] = array(
                    'left' => 'calc( '.$attr['connectorBgsize'].'px / 2 )',
                );

            $selectors[' .gutenberg-pack-timeline__center-block .gutenberg-pack-timeline__line'] = array(
                    'right' => 'calc( '.$attr['connectorBgsize'].'px / 2 )',
                );

            $selectors[' .gutenberg-pack-timeline__marker'] = array(
                    'background-color' => $attr['separatorBg'],
                    'min-height'=> $attr['connectorBgsize'].'px',
                    'min-width' => $attr['connectorBgsize'].'px',
                    'line-height' => $attr['connectorBgsize'].'px',
                    'border'=> $attr['borderwidth'].'px solid'.$attr['separatorBorder'],
                );

            $selectors[' .gutenberg-pack-timeline__left-block .gutenberg-pack-timeline__left .gutenberg-pack-timeline__arrow'] = array(
                    'height' => $attr['connectorBgsize'].'px',
                );

            $selectors[' .gutenberg-pack-timeline__right-block .gutenberg-pack-timeline__right .gutenberg-pack-timeline__arrow'] = array(
                    'height' => $attr['connectorBgsize'].'px',
                );

            $selectors[' .gutenberg-pack-timeline__center-block .gutenberg-pack-timeline__left .gutenberg-pack-timeline__arrow'] = array(
                    'height' => $attr['connectorBgsize'].'px',
                );

            $selectors[' .gutenberg-pack-timeline__center-block .gutenberg-pack-timeline__right .gutenberg-pack-timeline__arrow'] = array(
                    'height' => $attr['connectorBgsize'].'px',
                );

            $selectors[' .gutenberg-pack-timeline__center-block .gutenberg-pack-timeline__marker'] = array(
                    'margin-left' => $attr['horizontalSpace'].'px',
                    'margin-right'=> $attr['horizontalSpace'].'px',
                );

            $selectors[' .gutenberg-pack-timeline__field:not(:last-child)'] = array(
                    'margin-bottom' => $attr['verticalSpace'].'px',
                );

            $selectors[' .gutenberg-pack-timeline__date-hide.gutenberg-pack-timeline__date-inner'] = array(
                    'margin-bottom' => $attr['dateBottomspace'].'px',
                    'color'=> $attr['dateColor'],
                    'font-size' => $attr['dateFontsize'].'px',
                    'text-align'=> $attr['align'],
                );

            $selectors[' .gutenberg-pack-timeline__left-block .gutenberg-pack-timeline__day-new.gutenberg-pack-timeline__day-left'] = array(
                    'margin-left' => $attr['horizontalSpace'].'px',
                );

            $selectors[' .gutenberg-pack-timeline__right-block .gutenberg-pack-timeline__day-new.gutenberg-pack-timeline__day-right'] = array(
                    'margin-right' => $attr['horizontalSpace'].'px',
                );

             $selectors[' .gutenberg-pack-timeline__date-new'] = array(
                    'color'=> $attr['dateColor'],
                    'font-size' => $attr['dateFontsize'].'px',
                );

            $selectors[' .gutenberg-pack-timeline__events-inner-new'] = array(
                    'background-color' => $attr['backgroundColor'],
                    'border-radius' => $attr['borderRadius'].'px',
                    'padding'=> $attr['bgPadding'].'px',
                );

            $selectors[' .gutenberg-pack-timeline__main .gutenberg-pack-timeline__icon-new'] = array(
                    'color'=> $attr['iconColor'],
                    'font-size' => $attr['iconSize'].'px',
                );

            $selectors[' .gutenberg-pack-timeline__field.gutenberg-pack-timeline__animate-border:hover .gutenberg-pack-timeline__marker'] = array(
                    'background' => $attr['iconBgHover'],
                    'border-color'=> $attr['borderHover'],
                );

            $selectors[' .gutenberg-pack-timeline__main .gutenberg-pack-timeline__marker.gutenberg-pack-timeline__in-view-icon'] = array(
                    'background' => $attr['iconBgFocus'],
                    'border-color'=> $attr['borderFocus'],
                );


            $selectors[' .gutenberg-pack-timeline__field.gutenberg-pack-timeline__animate-border:hover .gutenberg-pack-timeline__icon-new'] = array(
                    'color'=> $attr['iconHover'],
                );

            $selectors[' .gutenberg-pack-timeline__main .gutenberg-pack-timeline__marker.gutenberg-pack-timeline__in-view-icon .gutenberg-pack-timeline__icon-new'] = array(
                    'color'=> $attr['iconFocus'],
                );

            $t_selectors[' .gutenberg-pack-timeline__center-block .gutenberg-pack-timeline__marker'] = array(
	            'margin-left' => 0,
	            'margin-right' => 0,
	        );

	       	$t_selectors[" .gutenberg-pack-timeline__center-block.gutenberg-pack-timeline__responsive-tablet .gutenberg-pack-timeline__heading"] = array(
				"text-align"  => 'left',
			);
			$t_selectors[" .gutenberg-pack-timeline__center-block.gutenberg-pack-timeline__responsive-tablet .gutenberg-pack-timeline-desc-content"] = array(
				"text-align"  => 'left',
			);

			$t_selectors[' .gutenberg-pack-timeline__center-block.gutenberg-pack-timeline__responsive-tablet .gutenberg-pack-timeline__events-new'] = array(
			        'text-align' => 'left'
			    );
			$t_selectors['.gutenberg-pack-timeline__center-block.gutenberg-pack-timeline__responsive-tablet .gutenberg-pack-timeline__date-inner'] = array(
			        'text-align' => 'left'
			    );
			$t_selectors[' .gutenberg-pack-timeline__center-block.gutenberg-pack-timeline__responsive-tablet .gutenberg-pack-timeline__date-hide.gutenberg-pack-timeline__date-inner'] = array(
					'text-align'=> 'left',
			);

	        $m_selectors[' .gutenberg-pack-timeline__center-block .gutenberg-pack-timeline__marker'] = array(
	            'margin-left' => 0,
	            'margin-right' => 0,
	        );

	        $m_selectors[' .gutenberg-pack-timeline__center-block .gutenberg-pack-timeline__day-new.gutenberg-pack-timeline__day-left'] = array(
	            'margin-left' => $attr['horizontalSpace'].'px',
	        );
	        $m_selectors[' .gutenberg-pack-timeline__center-block .gutenberg-pack-timeline__day-new.gutenberg-pack-timeline__day-right'] = array(
	            'margin-left' => $attr['horizontalSpace'].'px',
	        );

	        $m_selectors[" .gutenberg-pack-timeline__center-block.gutenberg-pack-timeline__responsive-mobile .gutenberg-pack-timeline__heading"] = array(
				"text-align"  => 'left',
			);
			$m_selectors[" .gutenberg-pack-timeline__center-block.gutenberg-pack-timeline__responsive-mobile .gutenberg-pack-timeline-desc-content"] = array(
				"text-align"  => 'left',
			);

			$m_selectors[' .gutenberg-pack-timeline__center-block.gutenberg-pack-timeline__responsive-mobile .gutenberg-pack-timeline__events-new'] = array(
			        'text-align' => 'left'
			    );
			$m_selectors['.gutenberg-pack-timeline__center-block.gutenberg-pack-timeline__responsive-mobile .gutenberg-pack-timeline__date-inner'] = array(
			        'text-align' => 'left'
			    );
			$m_selectors[' .gutenberg-pack-timeline__center-block.gutenberg-pack-timeline__responsive-mobile .gutenberg-pack-timeline__date-hide.gutenberg-pack-timeline__date-inner'] = array(
					'text-align'=> 'left',
			);
			// @codingStandardsIgnoreEnd

			$desktop = Gutenberg_Pack_Helper::generate_css( $selectors, '#gutenberg-pack-ctm-' . $id );
			$tablet  = Gutenberg_Pack_Helper::generate_responsive_css( '@media only screen and (max-width: 1024px)', $t_selectors, '#gutenberg-pack-ctm-' . $id );
			$mobile  = Gutenberg_Pack_Helper::generate_responsive_css( '@media only screen and (max-width: 767px)', $m_selectors, '#gutenberg-pack-ctm-' . $id );

			return $desktop . $tablet . $mobile;
		}

		/**
		 * Get Restaurant Menu Block CSS
		 *
		 * @since 1.0.2
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array The Widget List.
		 */
		public static function get_restaurant_menu_css( $attr, $id ) {

			// @codingStandardsIgnoreStart

			$defaults = Gutenberg_Pack_Helper::$block_list['gutenbergpack/restaurant-menu']['attributes'];

			$attr = array_merge( $defaults, (array) $attr );

			$m_selectors = array();
			$t_selectors = array();

			$selectors[" .gutenberg-pack-rest_menu__wrap"] = array(
				'padding-left'  => ($attr['columnGap']/2) . "px",
				'padding-right'  => ($attr['columnGap']/2). "px",
				'margin-bottom'  => $attr['rowGap'] . "px"
			);

			 $selectors[' .gutenberg-pack-rest_menu__wrap .gutenberg-pack-rm__image-content'] = array(
                    'padding-left' =>  $attr['imgHrPadding'] .'px',
                    'padding-right' =>  $attr['imgHrPadding'] .'px',
                    'padding-top' =>  $attr['imgVrPadding'] .'px',
                    'padding-bottom' =>  $attr['imgVrPadding'] .'px',
                );

            $selectors[' .gutenberg-pack-rm__image img'] = array(
                    'width'=>  $attr['imageWidth'] .'px',
                    'max-width'=>  $attr['imageWidth'] .'px',
                );

            $selectors[' .gutenberg-pack-rm__content'] = array(
                    'text-align' =>  $attr['headingAlign'] ,
                    'padding-left'  => $attr['contentHrPadding'] . 'px',
					'padding-right' => $attr['contentHrPadding'] . 'px',
					'padding-top'   => $attr['contentVrPadding'] . 'px',
					'padding-top'  => $attr['contentVrPadding'] . 'px',
                );

            $selectors[' .gutenberg-pack-rm__title'] = array(
                    'font-size' =>  $attr['titleFontSize'] .'px',
                    'color'=>  $attr['titleColor'] ,
                    'margin-bottom'=>  $attr['titleSpace'] .'px',
                );

            $selectors[' .gutenberg-pack-rm__price'] = array(
                    'font-size' =>  $attr['priceFontSize'].'px',
                    'color'=>  $attr['priceColor'],
                );

            $selectors[' .gutenberg-pack-rm__desc'] = array(
                    'font-size' =>  $attr['descFontSize'].'px',
                    'color'=>  $attr['descColor'],
                    'margin-bottom'=>  $attr['descSpace'].'px',
                );

            if ( $attr['seperatorStyle'] != "none" ) {
                $selectors[' .gutenberg-pack-rest_menu__wrap .gutenberg-pack-rm__separator'] = array(
                    'border-top-color'=>  $attr['seperatorColor'],
                    'border-top-style'=> $attr['seperatorStyle'],
                    'border-top-width'=> $attr['seperatorThickness'] . "px",
                    'width'=> $attr['seperatorWidth'] . "%",
                );
            }

			$r_selectors[' .gutenberg-pack-rest_menu__wrap.gutenberg-pack-rm__desk-column-'.$attr['columns'].':nth-child('.$attr['columns'].'n+1)'] = array(
			        'margin-left'=>  '0%',
			        'clear'=> 'left',
			    );

			$t_selectors[' .gutenberg-pack-rest_menu__wrap.gutenberg-pack-rm__desk-column-'.$attr['columns'].':nth-child('.$attr['tcolumns'].'n+1)'] = array(
			        'margin-left'=>  '0%',
			        'clear'=> 'left',
			    );

			$m_selectors[' .gutenberg-pack-rest_menu__wrap.gutenberg-pack-rm__desk-column-'.$attr['columns'].':nth-child('.$attr['mcolumns'].'n+1)'] = array(
			        'margin-left'=> '0%',
			        'clear'=> 'left',
			    );

			// @codingStandardsIgnoreEnd

			$desktop   = Gutenberg_Pack_Helper::generate_css( $selectors, '#gutenberg-pack-rm-' . $id );
			$r_desktop = Gutenberg_Pack_Helper::generate_responsive_css( '@media only screen and (min-width: 1024px)', $r_selectors, '#gutenberg-pack-rm-' . $id );
			$tablet    = Gutenberg_Pack_Helper::generate_responsive_css( '@media only screen and (min-width: 768px) and (max-width: 1023px)', $t_selectors, '#gutenberg-pack-rm-' . $id );
			$mobile    = Gutenberg_Pack_Helper::generate_responsive_css( '@media only screen and (max-width: 767px)', $m_selectors, '#gutenberg-pack-rm-' . $id );

			return $desktop . $r_desktop . $tablet . $mobile;
		}
	}
}
