<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_Block_Base' ) ) {

	/**
	 * Base Class For Gutentor for common functions
	 *
	 * @package Gutentor
	 * @since 1.0.1
	 */
	class Gutentor_Block_Base {

		/**
		 * Prevent some functions to called many times
		 *
		 * @access private
		 * @since 1.0.1
		 * @var integer
		 */
		private static $counter = 0;

		/**
		 * Add attributes to register_block_type
		 *
		 * @access protected
		 * @since 3.2.6
		 * @var integer
		 */
		protected $register_block_type_args = array();

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @return object
		 * @since 1.0.1
		 */
		public static function get_base_instance() {
			// Store the instance locally to avoid private static replication.

			static $instance = null;

			// Only run these methods if they haven't been ran previously.
			if ( null === $instance ) {
				$instance = new self();
			}

			// Always return the instance.
			return $instance;

		}

		/**
		 * Run Block
		 *
		 * @access public
		 * @return void
		 * @since 1.0.1
		 */
		public function run() {
			if ( method_exists( $this, 'load_dependencies' ) ) {
				$this->load_dependencies();
			}
			if ( method_exists( $this, 'register_block_type_args' ) ) {
				$this->register_block_type_args();
			}
			add_action( 'init', array( $this, 'register_and_render' ) );

			if ( self::$counter === 0 ) {
				add_filter( 'gutentor_common_attr_default_value', array( $this, 'add_single_item_common_attrs_default_values' ) );
				self::$counter++;
			}
		}

		/**
		 * Register this Block
		 * Callback will aut called by this function register_block_type
		 *
		 * @access public
		 * @return void
		 * @since 1.0.1
		 */
		public function register_and_render() {
			$args = array();

			if ( method_exists( $this, 'render_callback' ) ) {
				$args = array(
					'render_callback' => array( $this, 'render_callback' ),
				);
				if ( $this->block_name === 'p1' ) {
					$attributes = $this->get_attrs();
				} else {
					if ( method_exists( $this, 'get_attrs' ) ) {
						$attributes = array_merge_recursive( $this->get_attrs(), $this->get_common_attrs() );
					} else {
						$attributes = $this->get_common_attrs();
					}
				}

				$args['attributes'] = $attributes;
			}
			if ( $this->register_block_type_args ){
				$args = array_merge( $args, $this->register_block_type_args );
			}  

			register_block_type( 'gutentor/' . $this->block_name, $args );

		}

		/**
		 * Define Common Attributes
		 * It Basically Includes Advanced Attributes
		 *
		 * @since      1.0.0
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 */
		public function get_common_attrs() {
			$common_attrs = array(

				/*column*/
				'blockItemsColumn'                     => array(
					'type'    => 'object',
					'default' => array(
						'desktop' => 'grid-md-4',
						'tablet'  => 'grid-sm-4',
						'mobile'  => 'grid-xs-12',
					),
				),
				'blockSectionHtmlTag'                  => array(
					'type'    => 'string',
					'default' => 'section',
				),

				/*Advanced Attr*/
				'blockComponentAnimation'              => array(
					'type'    => 'object',
					'default' => array(
						'Animation' => 'none',
						'Delay'     => '',
						'Speed'     => '',
						'Iteration' => '',
					),
				),
				'blockComponentBGType'                 => array(
					'type' => 'string',
				),
				'blockComponentBGImage'                => array(
					'type' => 'string',
				),
				'blockComponentBGVideo'                => array(
					'type' => 'object',
				),
				'blockComponentBGColor'                => array(
					'type' => 'object',
				),
				'blockComponentBGImageSize'            => array(
					'type' => 'string',
				),
				'blockComponentBGImagePosition'        => array(
					'type' => 'string',
				),
				'blockComponentBGImageRepeat'          => array(
					'type' => 'string',
				),
				'blockComponentBGImageAttachment'      => array(
					'type' => 'string',
				),
				'blockComponentBGVideoLoop'            => array(
					'type'    => 'object',
					'default' => true,
				),
				'blockComponentBGVideoMuted'           => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'blockComponentEnableOverlay'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockComponentOverlayColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'blockComponentBoxBorder'              => array(
					'type'    => 'object',
					'default' => array(
						'borderStyle'        => 'none',
						'borderTop'          => '',
						'borderRight'        => '',
						'borderBottom'       => '',
						'borderLeft'         => '',
						'borderColorNormal'  => '',
						'borderColorHover'   => '',
						'borderRadiusType'   => 'px',
						'borderRadiusTop'    => '',
						'borderRadiusRight'  => '',
						'borderRadiusBottom' => '',
						'borderRadiusLeft'   => '',
					),
				),
				'blockComponentMargin'                 => array(
					'type'    => 'object',
					'default' => array(
						'type'          => 'px',
						'desktopTop'    => '',
						'desktopRight'  => '',
						'desktopBottom' => '',
						'desktopLeft'   => '',
						'tabletTop'     => '',
						'tabletRight'   => '',
						'tabletBottom'  => 'px',
						'tabletLeft'    => '',
						'mobileTop'     => '',
						'mobileRight'   => '',
						'mobileBottom'  => '',
						'mobileLeft'    => '',
					),
				),
				'blockComponentPadding'                => array(
					'type'    => 'object',
					'default' => array(
						'type'          => 'px',
						'desktopTop'    => '',
						'desktopRight'  => '',
						'desktopBottom' => '',
						'desktopLeft'   => '',
						'tabletTop'     => '',
						'tabletRight'   => '',
						'tabletBottom'  => 'px',
						'tabletLeft'    => '',
						'mobileTop'     => '',
						'mobileRight'   => '',
						'mobileBottom'  => '',
						'mobileLeft'    => '',
					),
				),
				'blockComponentBoxShadowOptions'       => array(
					'type'    => 'object',
					'default' => array(
						'boxShadowColor'    => '',
						'boxShadowX'        => '',
						'boxShadowY'        => '',
						'boxShadowBlur'     => '',
						'boxShadowSpread'   => '',
						'boxShadowPosition' => '',
					),
				),

				/*adv shape*/
				'blockShapeTopSelect'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'blockShapeTopSelectEnableColor'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockShapeTopFlipHorizontally'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockShapeTopFlipVertically'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockShapeTopSelectColor'             => array(
					'type'    => 'object',
					'default' => '',
				),
				'blockShapeTopHeight'                  => array(
					'type'    => 'object',
					'default' => array(
						'type'    => 'px',
						'desktop' => '',
						'tablet'  => '',
						'mobile'  => '',
					),
				),
				'blockShapeTopWidth'                   => array(
					'type'    => 'object',
					'default' => array(
						'type'    => 'px',
						'desktop' => '',
						'tablet'  => '',
						'mobile'  => '',
					),
				),
				'blockShapeTopPosition'                => array(
					'type'    => 'boolean',
					'default' => '',
				),
				'blockShapeBottomSelect'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'blockShapeBottomSelectEnableColor'    => array(
					'type'    => 'boolean',
					'default' => '',
				),
				'blockShapeBottomSelectColor'          => array(
					'type'    => 'object',
					'default' => '',
				),
				'blockShapeBottomHeight'               => array(
					'type'    => 'object',
					'default' => array(
						'type'    => 'px',
						'desktop' => '',
						'tablet'  => '',
						'mobile'  => '',
					),
				),
				'blockShapeBottomWidth'                => array(
					'type'    => 'object',
					'default' => array(
						'type'    => 'px',
						'desktop' => '',
						'tablet'  => '',
						'mobile'  => '',
					),
				),
				'blockShapeBottomPosition'             => array(
					'type'    => 'boolean',
					'default' => '',
				),
				'blockComponentEnableHeight'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockComponentHeight'                 => array(
					'type'    => 'object',
					'default' => array(
						'type'    => 'px',
						'desktop' => '',
						'tablet'  => '',
						'mobile'  => '',
					),
				),
				'blockShapeBottomFlipVertically'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockShapeBottomFlipHorizontally'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockComponentEnablePosition'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockComponentPositionTypeDesktop'    => array(
					'type'    => 'string',
					'default' => 'gutentor-position-default-desktop',
				),
				'blockComponentPositionTypeTablet'     => array(
					'type'    => 'string',
					'default' => 'gutentor-position-default-tablet',
				),
				'blockComponentPositionTypeMobile'     => array(
					'type'    => 'string',
					'default' => 'gutentor-position-default-mobile',
				),
				'blockComponentPositionDesktop'        => array(
					'type'    => 'object',
					'default' => array(
						'type'   => 'px',
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
				),
				'blockComponentPositionDesktopWidth'   => array(
					'type'    => 'object',
					'default' => array(
						'type'  => 'px',
						'width' => '',
					),
				),
				'blockComponentPositionTablet'         => array(
					'type'    => 'object',
					'default' => array(
						'type'   => 'px',
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
				),
				'blockComponentPositionTabletWidth'    => array(
					'type'    => 'object',
					'default' => array(
						'type'  => 'px',
						'width' => '',
					),
				),
				'blockComponentPositionMobile'         => array(
					'type'    => 'object',
					'default' => array(
						'type'   => 'px',
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
				),
				'blockComponentPositionMobileWidth'    => array(
					'type'    => 'object',
					'default' => array(
						'type'  => 'px',
						'width' => '',
					),
				),
				'blockComponentEnableZIndex'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockComponentZIndex'                 => array(
					'type'    => 'object',
					'default' => array(
						'desktop' => '',
						'tablet'  => '',
						'mobile'  => '',
					),
				),
				'blockComponentDesktopDisplayMode'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockComponentTabletDisplayMode'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockComponentMobileDisplayMode'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockComponentRemoveContainerSpace'   => array(
					'type'    => 'object',
					'default' => array(
						'desktop' => '',
						'tablet'  => '',
						'mobile'  => '',
					),
				),
				'blockComponentRemoveRowSpace'         => array(
					'type'    => 'object',
					'default' => array(
						'desktop' => '',
						'tablet'  => '',
						'mobile'  => '',
					),
				),
				'blockComponentRemoveColumnSpace'      => array(
					'type'    => 'object',
					'default' => array(
						'desktop' => '',
						'tablet'  => '',
						'mobile'  => '',
					),
				),

				/* block component title*/
				'blockComponentTitleEnable'            => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'blockComponentTitle'                  => array(
					'type'    => 'string',
					'default' => __( 'Block Title', 'gutentor' ),
				),
				'blockComponentTitleTag'               => array(
					'type'    => 'string',
					'default' => 'h2',
				),
				'blockComponentTitleAlign'             => array(
					'type'    => 'string',
					'default' => 'text-center',
				),
				'blockComponentTitleColorEnable'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'blockComponentTitleColor'             => array(
					'type'    => 'object',
					'default' => array(
						'hex' => '#111111',
					),
				),
				'blockComponentTitleTypography'        => array(
					'type'    => 'object',
					'default' => array(
						'fontType'             => 'default',
						'systemFont'           => '',
						'googleFont'           => '',
						'customFont'           => '',

						'desktopFontSize'      => '',
						'tabletFontSize'       => '',
						'mobileFontSize'       => '',

						'fontWeight'           => '',
						'textTransform'        => '',
						'fontStyle'            => '',
						'textDecoration'       => '',

						'desktopLineHeight'    => '',
						'tabletLineHeight'     => '',
						'mobileLineHeight'     => '',

						'desktopLetterSpacing' => '',
						'tabletLetterSpacing'  => '',
						'mobileLetterSpacing'  => '',
					),
				),
				'blockComponentTitleMargin'            => array(
					'type'    => 'object',
					'default' => array(
						'type'          => 'px',
						'desktopTop'    => '',
						'desktopRight'  => '',
						'desktopBottom' => '',
						'desktopLeft'   => '',

						'tabletTop'     => '',
						'tabletRight'   => '',
						'tabletBottom'  => '',
						'tabletLeft'    => '',

						'mobileTop'     => '',
						'mobileRight'   => '',
						'mobileBottom'  => '',
						'mobileLeft'    => '',

					),
				),
				'blockComponentTitlePadding'           => array(
					'type'    => 'object',
					'default' => array(
						'type'          => 'px',
						'desktopTop'    => '',
						'desktopRight'  => '',
						'desktopBottom' => '',
						'desktopLeft'   => '',

						'tabletTop'     => '',
						'tabletRight'   => '',
						'tabletBottom'  => '',
						'tabletLeft'    => '',

						'mobileTop'     => '',
						'mobileRight'   => '',
						'mobileBottom'  => '',
						'mobileLeft'    => '',

					),
				),
				'blockComponentTitleAnimation'         => array(
					'type'    => 'object',
					'default' => array(
						'Animation' => 'none',
						'Delay'     => '',
						'Speed'     => '',
						'Iteration' => '',
					),
				),
				'blockComponentTitleDesignEnable'      => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'blockComponentTitleSeperatorPosition' => array(
					'type'    => 'string',
					'default' => 'seperator-bottom',
				),

				/*Image Options attr*/
				'blockImageBoxImageOverlayColor'       => array(
					'type'    => 'object',
					'default' => array(
						'enable' => false,
						'normal' => '',
						'hover'  => '',
					),
				),
				'blockFullImageEnable'                 => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockEnableImageBoxWidth'             => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockImageBoxWidth'                   => array(
					'type'    => 'number',
					'default' => '',
				),
				'blockEnableImageBoxHeight'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockImageBoxHeight'                  => array(
					'type'    => 'number',
					'default' => '',
				),
				'blockEnableImageBoxDisplayOptions'    => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockImageBoxDisplayOptions'          => array(
					'type'    => 'string',
					'default' => 'normal-image',
				),
				'blockImageBoxBackgroundImageOptions'  => array(
					'type'    => 'object',
					'default' => array(

						'backgroundImage'      => '',
						'desktopHeight'        => '',
						'tabletHeight'         => '',
						'mobileHeight'         => '',

						'backgroundSize'       => '',
						'backgroundPosition'   => '',
						'backgroundRepeat'     => '',
						'backgroundAttachment' => '',
					),
				),
				'blockEnableImageBoxBorder'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockImageBoxBorder'                  => array(
					'type'    => 'object',
					'default' => array(
						'borderStyle'        => 'none',
						'borderTop'          => '',
						'borderRight'        => '',
						'borderBottom'       => '',
						'borderLeft'         => '',
						'borderColorNormal'  => '',
						'borderColorHover'   => '',
						'borderRadiusType'   => 'px',
						'borderRadiusTop'    => '',
						'borderRadiusRight'  => '',
						'borderRadiusBottom' => '',
						'borderRadiusLeft'   => '',
					),
				),

				/*item Wrap*/
				'blockItemsWrapMargin'                 => array(
					'type'    => 'object',
					'default' => array(
						'type'          => 'px',
						'desktopTop'    => '',
						'desktopRight'  => '',
						'desktopBottom' => '',
						'desktopLeft'   => '',
						'tabletTop'     => '',
						'tabletRight'   => '',
						'tabletBottom'  => '',
						'tabletLeft'    => '',
						'mobileTop'     => '',
						'mobileRight'   => '',
						'mobileBottom'  => '',
						'mobileLeft'    => '',
					),
				),
				'blockItemsWrapPadding'                => array(
					'type'    => 'object',
					'default' => array(
						'type'          => 'px',
						'desktopTop'    => '',
						'desktopRight'  => '',
						'desktopBottom' => '',
						'desktopLeft'   => '',
						'tabletTop'     => '',
						'tabletRight'   => '',
						'tabletBottom'  => '',
						'tabletLeft'    => '',
						'mobileTop'     => '',
						'mobileRight'   => '',
						'mobileBottom'  => '',
						'mobileLeft'    => '',
					),
				),
				'blockItemsWrapAnimation'              => array(
					'type'    => 'object',
					'default' => array(
						'Animation' => 'none',
						'Delay'     => '',
						'Speed'     => '',
						'Iteration' => '',
					),
				),
			);

			return apply_filters( 'gutentor_get_common_attrs', $common_attrs );
		}

		/**
		 * Block Single Items Common attributes
		 * eg Title, Description, Button etc
		 *
		 * @access public
		 * @return array
		 * @since 1.0.1
		 */
		public function get_single_item_common_attrs() {
			return array(

				/*single item title*/
				'blockSingleItemTitleEnable'           => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'blockSingleItemTitleTag'              => array(
					'type'    => 'string',
					'default' => 'h3',
				),
				'blockSingleItemTitleColor'            => array(
					'type'    => 'object',
					'default' => array(
						'enable' => 'false',
						'normal' => array(
							'hex' => '#111111',
						),
						'hover'  => '',
					),
				),
				'blockSingleItemTitleTypography'       => array(
					'type'    => 'object',
					'default' => array(
						'fontType'             => 'default',
						'systemFont'           => '',
						'googleFont'           => '',
						'customFont'           => '',

						'desktopFontSize'      => '',
						'tabletFontSize'       => '',
						'mobileFontSize'       => '',

						'fontWeight'           => '',
						'textTransform'        => '',
						'fontStyle'            => '',
						'textDecoration'       => '',

						'desktopLineHeight'    => '',
						'tabletLineHeight'     => '',
						'mobileLineHeight'     => '',

						'desktopLetterSpacing' => '',
						'tabletLetterSpacing'  => '',
						'mobileLetterSpacing'  => '',
					),
				),
				'blockSingleItemTitleMargin'           => array(
					'type'    => 'object',
					'default' => array(
						'type'          => 'px',
						'desktopTop'    => '',
						'desktopRight'  => '',
						'desktopBottom' => '',
						'desktopLeft'   => '',

						'tabletTop'     => '',
						'tabletRight'   => '',
						'tabletBottom'  => '',
						'tabletLeft'    => '',

						'mobileTop'     => '',
						'mobileRight'   => '',
						'mobileBottom'  => '',
						'mobileLeft'    => '',

					),
				),
				'blockSingleItemTitlePadding'          => array(
					'type'    => 'object',
					'default' => array(
						'type'          => 'px',
						'desktopTop'    => '',
						'desktopRight'  => '',
						'desktopBottom' => '',
						'desktopLeft'   => '',

						'tabletTop'     => '',
						'tabletRight'   => '',
						'tabletBottom'  => '',
						'tabletLeft'    => '',

						'mobileTop'     => '',
						'mobileRight'   => '',
						'mobileBottom'  => '',
						'mobileLeft'    => '',

					),
				),

				/* single item description*/
				'blockSingleItemDescriptionEnable'     => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'blockSingleItemDescriptionTag'        => array(
					'type'    => 'string',
					'default' => 'p',
				),
				'blockSingleItemDescriptionColor'      => array(
					'type'    => 'object',
					'default' => array(
						'enable' => 'false',
						'normal' => '',
						'hover'  => '',
					),
				),
				'blockSingleItemDescriptionTypography' => array(
					'type'    => 'object',
					'default' => array(
						'fontType'             => 'default',
						'systemFont'           => '',
						'googleFont'           => '',
						'customFont'           => '',

						'desktopFontSize'      => '',
						'tabletFontSize'       => '',
						'mobileFontSize'       => '',

						'fontWeight'           => '',
						'textTransform'        => '',
						'fontStyle'            => '',
						'textDecoration'       => '',

						'desktopLineHeight'    => '',
						'tabletLineHeight'     => '',
						'mobileLineHeight'     => '',

						'desktopLetterSpacing' => '',
						'tabletLetterSpacing'  => '',
						'mobileLetterSpacing'  => '',
					),
				),
				'blockSingleItemDescriptionMargin'     => array(
					'type'    => 'object',
					'default' => array(
						'type'          => 'px',
						'desktopTop'    => '',
						'desktopRight'  => '',
						'desktopBottom' => '',
						'desktopLeft'   => '',

						'tabletTop'     => '',
						'tabletRight'   => '',
						'tabletBottom'  => '',
						'tabletLeft'    => '',

						'mobileTop'     => '',
						'mobileRight'   => '',
						'mobileBottom'  => '',
						'mobileLeft'    => '',

					),
				),
				'blockSingleItemDescriptionPadding'    => array(
					'type'    => 'object',
					'default' => array(
						'type'          => 'px',
						'desktopTop'    => '',
						'desktopRight'  => '',
						'desktopBottom' => '',
						'desktopLeft'   => '',

						'tabletTop'     => '',
						'tabletRight'   => '',
						'tabletBottom'  => '',
						'tabletLeft'    => '',

						'mobileTop'     => '',
						'mobileRight'   => '',
						'mobileBottom'  => '',
						'mobileLeft'    => '',

					),
				),

				/*single item button*/
				'blockSingleItemButtonEnable'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockSingleItemButtonLinkOptions'     => array(
					'type'    => 'object',
					'default' => array(
						'openInNewTab' => false,
						'rel'          => '',
					),
				),
				'blockSingleItemButtonColor'           => array(
					'type'    => 'object',
					'default' => array(
						'enable' => true,
						'normal' => array(
							'hex' => '#275cf6',
							'rgb' => array(
								'r' => '39',
								'g' => '92',
								'b' => '246',
								'a' => '1',
							),
						),
						'hover'  => array(
							'hex' => '#1949d4',
							'rgb' => array(
								'r' => '25',
								'g' => '73',
								'b' => '212',
								'a' => '1',
							),
						),
					),
				),
				'blockSingleItemButtonTextColor'       => array(
					'type'    => 'object',
					'default' => array(
						'enable' => true,
						'normal' => array(
							'hex' => '#fff',
						),
						'hover'  => '',
					),
				),
				'blockSingleItemButtonMargin'          => array(
					'type'    => 'object',
					'default' => array(
						'type'          => 'px',
						'desktopTop'    => '',
						'desktopRight'  => '',
						'desktopBottom' => '',
						'desktopLeft'   => '',
						'tabletTop'     => '',
						'tabletRight'   => '',
						'tabletBottom'  => '',
						'tabletLeft'    => '',
						'mobileTop'     => '',
						'mobileRight'   => '',
						'mobileBottom'  => '',
						'mobileLeft'    => '',
					),
				),
				'blockSingleItemButtonPadding'         => array(
					'type'    => 'object',
					'default' => array(
						'type'          => 'px',
						'desktopTop'    => '10',
						'desktopRight'  => '15',
						'desktopBottom' => '10',
						'desktopLeft'   => '15',
						'tabletTop'     => '10',
						'tabletRight'   => '15',
						'tabletBottom'  => '10',
						'tabletLeft'    => '15',
						'mobileTop'     => '10',
						'mobileRight'   => '15',
						'mobileBottom'  => '10',
						'mobileLeft'    => '15',
					),
				),
				'blockSingleItemButtonIconOptions'     => array(
					'type'    => 'object',
					'default' => array(
						'position' => 'hide',
						'size'     => '',
					),
				),
				'blockSingleItemButtonIconMargin'      => array(
					'type'    => 'object',
					'default' => array(
						'type'          => 'px',
						'desktopTop'    => '',
						'desktopRight'  => '',
						'desktopBottom' => '',
						'desktopLeft'   => '',
						'tabletTop'     => '',
						'tabletRight'   => '',
						'tabletBottom'  => '',
						'tabletLeft'    => '',
						'mobileTop'     => '',
						'mobileRight'   => '',
						'mobileBottom'  => '',
						'mobileLeft'    => '',
					),
				),
				'blockSingleItemButtonIconPadding'     => array(
					'type'    => 'object',
					'default' => array(
						'type'          => 'px',
						'desktopTop'    => '',
						'desktopRight'  => '',
						'desktopBottom' => '',
						'desktopLeft'   => '',
						'tabletTop'     => '',
						'tabletRight'   => '',
						'tabletBottom'  => '',
						'tabletLeft'    => '',
						'mobileTop'     => '',
						'mobileRight'   => '',
						'mobileBottom'  => '',
						'mobileLeft'    => '',
					),
				),
				'blockSingleItemButtonBorder'          => array(
					'type'    => 'object',
					'default' => array(
						'borderStyle'        => '',
						'borderTop'          => '',
						'borderRight'        => '',
						'borderBottom'       => '',
						'borderLeft'         => '',
						'borderColorNormal'  => '',
						'borderColorHover'   => '',
						'borderRadiusType'   => 'px',
						'borderRadiusTop'    => '3',
						'borderRadiusRight'  => '3',
						'borderRadiusBottom' => '3',
						'borderRadiusLeft'   => '3',
					),
				),
				'blockSingleItemButtonBoxShadow'       => array(
					'type'    => 'object',
					'default' => array(
						'boxShadowColor'    => '',
						'boxShadowX'        => '',
						'boxShadowY'        => '',
						'boxShadowBlur'     => '',
						'boxShadowSpread'   => '',
						'boxShadowPosition' => '',
					),
				),
				'blockSingleItemButtonTypography'      => array(
					'type'    => 'object',
					'default' => array(
						'fontType'             => 'system',
						'systemFont'           => '',
						'googleFont'           => '',
						'customFont'           => '',

						'desktopFontSize'      => '14',
						'tabletFontSize'       => '14',
						'mobileFontSize'       => '14',

						'fontWeight'           => '',
						'textTransform'        => 'normal',
						'fontStyle'            => '',
						'textDecoration'       => '',

						'desktopLineHeight'    => '',
						'tabletLineHeight'     => '',
						'mobileLineHeight'     => '',

						'desktopLetterSpacing' => '',
						'tabletLetterSpacing'  => '',
						'mobileLetterSpacing'  => '',
					),
				),

				/* single item box title*/
				'blockSingleItemBoxColor'              => array(
					'type'    => 'object',
					'default' => array(
						'enable' => true,
						'normal' => '',
						'hover'  => '',
					),
				),
				'blockSingleItemBoxBorder'             => array(
					'type'    => 'object',
					'default' => array(
						'borderStyle'        => '',
						'borderTop'          => '',
						'borderRight'        => '',
						'borderBottom'       => '',
						'borderLeft'         => '',
						'borderColorNormal'  => '',
						'borderColorHover'   => '',
						'borderRadiusType'   => 'px',
						'borderRadiusTop'    => '3',
						'borderRadiusRight'  => '3',
						'borderRadiusBottom' => '3',
						'borderRadiusLeft'   => '3',
					),
				),
				'blockSingleItemBoxShadowOptions'      => array(
					'type'    => 'object',
					'default' => array(
						'boxShadowColor'    => '',
						'boxShadowX'        => '',
						'boxShadowY'        => '',
						'boxShadowBlur'     => '',
						'boxShadowSpread'   => '',
						'boxShadowPosition' => '',
					),
				),
				'blockSingleItemBoxMargin'             => array(
					'type'    => 'object',
					'default' => array(
						'type'          => 'px',
						'desktopTop'    => '',
						'desktopRight'  => '',
						'desktopBottom' => '',
						'desktopLeft'   => '',

						'tabletTop'     => '',
						'tabletRight'   => '',
						'tabletBottom'  => '',
						'tabletLeft'    => '',

						'mobileTop'     => '',
						'mobileRight'   => '',
						'mobileBottom'  => '',
						'mobileLeft'    => '',

					),
				),
				'blockSingleItemBoxPadding'            => array(
					'type'    => 'object',
					'default' => array(
						'type'          => 'px',
						'desktopTop'    => '',
						'desktopRight'  => '',
						'desktopBottom' => '',
						'desktopLeft'   => '',

						'tabletTop'     => '',
						'tabletRight'   => '',
						'tabletBottom'  => '',
						'tabletLeft'    => '',

						'mobileTop'     => '',
						'mobileRight'   => '',
						'mobileBottom'  => '',
						'mobileLeft'    => '',
					),
				),
			);
		}

		/**
		 * Element Common attributes
		 * eg Title, Description, Button etc
		 *
		 * @access public
		 * @return array
		 * @since 1.0.1
		 */
		public function get_element_common_attrs() {
			return array(

				/*single item title*/
				'eAnimation' => array(
					'type' => 'object',
				),
				'eOnPos'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'ePosTypeD'  => array(
					'type'    => 'string',
					'default' => 'g-pos-d',
				),
				'ePosTypeM'  => array(
					'type'    => 'string',
					'default' => 'g-pos-d',
				),
				'ePosTypeT'  => array(
					'type'    => 'string',
					'default' => 'g-pos-d',
				),
				'ePosD'      => array(
					'type' => 'object',
				),
				'ePosDWidth' => array(
					'type'    => 'object',
					'default' => array(
						'type'  => 'px',
						'width' => '',
					),
				),
				'ePosT'      => array(
					'type' => 'object',
				),
				'ePosTWidth' => array(
					'type'    => 'object',
					'default' => array(
						'type'  => 'px',
						'width' => '',
					),
				),
				'ePosM'      => array(
					'type' => 'object',
				),
				'ePosMWidth' => array(
					'type'    => 'object',
					'default' => array(
						'type'  => 'px',
						'width' => '',
					),
				),
				'eZIndex'    => array(
					'type' => 'object',
				),
				'eHideMode'  => array(
					'type' => 'object',
				),
			);
		}


		/**
		 * Module Common attributes
		 * eg Title, Description, Button etc
		 *
		 * @access public
		 * @return array
		 * @since 1.0.1
		 */
		public function get_module_common_attrs() {
			return array(
				'mTag'               => array(
					'type'    => 'string',
					'default' => 'section',
				),
				/*single item title*/
				'mAnimation'         => array(
					'type' => 'object',
				),
				'pID'                => array(
					'type' => 'string',
				),
				'mOnPos'             => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'mPosTypeD'          => array(
					'type'    => 'string',
					'default' => 'g-pos-d',
				),
				'mPosOptD'           => array(
					'type'    => 'string',
					'default' => 'default',
				),
				'mPosTypeM'          => array(
					'type'    => 'string',
					'default' => 'g-pos-d',
				),
				'mPosOptM'           => array(
					'type'    => 'string',
					'default' => 'default',
				),
				'mPosTypeT'          => array(
					'type'    => 'string',
					'default' => 'g-pos-d',
				),
				'mPosOptT'           => array(
					'type'    => 'string',
					'default' => 'default',
				),
				'mPosD'              => array(
					'type' => 'object',
				),
				'mPosDWidth'         => array(
					'type'    => 'object',
					'default' => array(
						'type'  => 'px',
						'width' => '',
					),
				),
				'mPosT'              => array(
					'type' => 'object',
				),
				'mPosTWidth'         => array(
					'type'    => 'object',
					'default' => array(
						'type'  => 'px',
						'width' => '',
					),
				),
				'mPosM'              => array(
					'type' => 'object',
				),
				'mPosMWidth'         => array(
					'type'    => 'object',
					'default' => array(
						'type'  => 'px',
						'width' => '',
					),
				),
				'mZIndex'            => array(
					'type' => 'object',
				),
				'mHideMode'          => array(
					'type' => 'object',
				),
				'mOnOverlay'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'mOverlayColor'      => array(
					'type'    => 'string',
					'default' => 'normal-image',
				),
				'mBGImageSrc'        => array(
					'type'    => 'string',
					'default' => 'self-hosted-local',
				),
				'mBGVideoSrc'        => array(
					'type'    => 'string',
					'default' => 'self-hosted-local',
				),
				'mBGVideoUrl'        => array(
					'type'    => 'string',
					'default' => 'https://www.youtube.com/watch?v=bGMi7L78hVk',
				),
				'mBGVideoLoop'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'mBGVideoMute'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				/*top shape*/
				'mTShapeOpt'         => array(
					'type'    => 'string',
					'default' => 'gb-svg',
				),
				'mTShapeImg'         => array(
					'type'    => 'object',
					'default' => array(),
				),
				'mTShapeImgSizeType' => array(
					'type'    => 'string',
					'default' => 'full',
				),
				'mTShapeSVGOpt'      => array(
					'type'    => 'string',
					'default' => 'gb-svg-default',
				),
				'mTShapeCustomSVG'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'mTShape'            => array(
					'type' => 'string',
				),
				'mTShapeOnColor'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'mTShapeVFlip'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'mTShapeHFlip'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'mTShapeColor'       => array(
					'type' => 'string',
				),
				'mTShapeHeight'      => array(
					'type' => 'object',
				),
				'mTShapeWidth'       => array(
					'type' => 'object',
				),
				'mTShapePos'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				/*Bottom shape*/
				'mBShapeOpt'         => array(
					'type'    => 'string',
					'default' => 'gb-svg',
				),
				'mBShapeImg'         => array(
					'type'    => 'object',
					'default' => array(),
				),
				'mBShapeImgSizeType' => array(
					'type'    => 'string',
					'default' => 'full',
				),
				'mBShapeSVGOpt'      => array(
					'type'    => 'string',
					'default' => 'gb-svg-default',
				),
				'mBShapeCustomSVG'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'mBShape'            => array(
					'type' => 'string',
				),
				'mBShapeOnColor'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'mBShapeVFlip'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'mBShapeHFlip'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'mBShapeColor'       => array(
					'type' => 'string',
				),
				'mBShapeHeight'      => array(
					'type' => 'object',
				),
				'mBShapeWidth'       => array(
					'type' => 'object',
				),
				'mBShapePos'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
			);
		}

		/**
		 * Module Common attributes
		 * eg Title, Description, Button etc
		 *
		 * @access public
		 * @return array
		 * @since 1.0.1
		 */
		public function get_module_query_elements_common_attrs() {
			return array(
				'blockSortableItems' => array(
					'type'    => 'object',
					'default' => array(
						array(
							'itemValue' => 'featured-image',
							'itemLabel' => __( 'Featured Image' ),
						),
						array(
							'itemValue' => 'title',
							'itemLabel' => __( 'Title' ),
						),
						array(
							'itemValue' => 'primary-entry-meta',
							'itemLabel' => __( 'Primary Entry Meta' ),
						),
						array(
							'itemValue' => 'description',
							'itemLabel' => __( 'Description/Excerpt' ),
						),
						array(
							'itemValue' => 'button',
							'itemLabel' => __( 'Button' ),
						),
						array(
							'itemValue' => 'secondary-entry-meta',
							'itemLabel' => __( 'Secondary Entry Meta' ),
						),
					),
				),
				'pMeta1Sorting'      => array(
					'type'    => 'object',
					'default' => array(
						array(
							'itemValue' => 'meta-author',
							'itemLabel' => __( 'Author' ),
						),
						array(
							'itemValue' => 'meta-date',
							'itemLabel' => __( 'Date' ),
						),
						array(
							'itemValue' => 'meta-category',
							'itemLabel' => __( 'Category' ),
						),
						array(
							'itemValue' => 'meta-comment',
							'itemLabel' => __( 'Comments' ),
						),
						array(
							'itemValue' => 'meta-tag',
							'itemLabel' => __( 'Tag' ),
						),
					),
				),
				'pMeta2Sorting'      => array(
					'type'    => 'object',
					'default' => array(
						array(
							'itemValue' => 'meta-author',
							'itemLabel' => __( 'Author' ),
						),
						array(
							'itemValue' => 'meta-date',
							'itemLabel' => __( 'Date' ),
						),
						array(
							'itemValue' => 'meta-category',
							'itemLabel' => __( 'Category' ),
						),
						array(
							'itemValue' => 'meta-comment',
							'itemLabel' => __( 'Comments' ),
						),
						array(
							'itemValue' => 'meta-tag',
							'itemLabel' => __( 'Tag' ),
						),
					),
				),
				'pOnTitle'           => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pTitleTag'          => array(
					'type'    => 'string',
					'default' => 'h3',
				),
				'pOnDesc'            => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'PExcerptLen'        => array(
					'type'    => 'number',
					'default' => 100,
				),
				'pExcerptLenInWords' => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pOnMeta1'           => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnIconMeta1'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnMeta2'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pOnIconMeta2'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnAuthorMeta1'     => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnAuthorMeta2'     => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnDateMeta1'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnDateMeta2'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnCatMeta1'        => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnCatMeta2'        => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnTagMeta1'        => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnTagMeta2'        => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnCommentMeta1'    => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnCommentMeta2'    => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'categories'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'pOnFImg'            => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pFType'             => array(
					'type'    => 'string',
					'default' => 'img',
				),
				'pFPopupData'        => array(
					'type'    => 'object',
					'default' => array(
						'icon' => array(
							'label' => 'fa-book',
							'value' => (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-play' : 'fas fa-play',
							'code'  => 'f108',
						),
						'iPos' => 'g-v-popup-c',
					),
				),
				'pFVType'            => array(
					'type'    => 'string',
					'default' => 'disable',
				),
				'pOnBtn'             => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pBtnIconOpt'        => array(
					'type'    => 'object',
					'default' => array(
						'position' => '',
						'size'     => '',
					),
				),
				'pBtnIcon'           => array(
					'type'    => 'object',
					'default' => array(
						'label' => 'fa-book',
						'value' => (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-book' : 'fas fa-book',
						'code'  => 'f108',
					),
				),
				'pBtnLink'           => array(
					'type'    => 'object',
					'default' => array(
						'openInNewTab' => '',
						'rel'          => '',
					),
				),
				'pBtnText'           => array(
					'type'    => 'string',
					'default' => __( 'Read More' ),
				),
				'pBtnCName'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'pImgOnLink'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pImgOpenNewTab'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pImgLinkRel'        => array(
					'type'    => 'string',
					'default' => 'noopener noreferrer',
				),
				'pFImgOColor'        => array(
					'type'    => 'object',
					'default' => array(
						'enable' => false,
						'normal' => '',
						'hover'  => '',
					),
				),
				'pOnImgDisplayOpt'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pImgDisplayOpt'     => array(
					'type'    => 'string',
					'default' => 'normal-image',
				),
				'pOnFeaturedCat'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pBadgeType'         => array(
					'type'    => 'string',
					'default' => 'category',
				),
				'pPostCatPos'        => array(
					'type'    => 'string',
					'default' => 'gutentor-cat-pos-img-top-left',
				),
				'pOnPostFormatOpt'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pPostFormatPos'     => array(
					'type'    => 'string',
					'default' => 'gutentor-pf-pos-img-top-right',
				),
				'pFImgSize'          => array(
					'type'    => 'string',
					'default' => 'full',
				),
				'q1OnBtn'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'q1BtnIconOpt'       => array(
					'type'    => 'object',
					'default' => array(
						'position' => '',
						'size'     => '',
					),
				),
				'q1BtnIcon'          => array(
					'type'    => 'object',
					'default' => array(
						'label' => 'fa-book',
						'value' => (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-book' : 'fas fa-book',
						'code'  => 'f108',
					),
				),
				'q1BtnLink'          => array(
					'type'    => 'object',
					'default' => array(
						'openInNewTab' => '',
						'rel'          => '',
					),
				),
				'q1BtnTxt'           => array(
					'type'    => 'string',
					'default' => __( 'Preview' ),
				),
				'q1BtnCName'         => array(
					'type'    => 'string',
					'default' => '',
				),
				/*column*/
				'pBxAlign'           => array(
					'type'    => 'object',
					'default' => array(
						'desktop' => '',
						'tablet'  => '',
						'mobile'  => '',
					),
				),
				'wooOnRating'        => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'wooOnPrice'         => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'wooOnFreeTxt'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'wooFreeTxt'         => array(
					'type'    => 'string',
					'default' => __( 'Free' ),
				),
				'eOnWl'              => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pAccessAvatar'      => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnAvatar'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pAvatarPos'         => array(
					'type'    => 'string',
					'default' => 'g-avatar-b-title-l',
				),
				'pAvatarSize'        => array(
					'type'    => 'string',
					'default' => '48',
				),
				'pAvatarOColor'      => array(
					'type'    => 'object',
					'default' => array(
						'enable' => false,
						'normal' => '',
						'hover'  => '',
					),
				),
				'pOnByAuthor'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
			);
		}

		/**
		 * Module Featured Post Common attributes
		 * eg Title, Description, Button etc
		 *
		 * @access public
		 * @return array
		 * @since 1.0.1
		 */
		public function get_module_featured_post_query_elements_common_attrs() {
			return array(
				'blockFPSortableItems' => array(
					'type'    => 'object',
					'default' => array(
						array(
							'itemValue' => 'title',
							'itemLabel' => __( 'Title' ),
						),
						array(
							'itemValue' => 'primary-entry-meta',
							'itemLabel' => __( 'Primary Entry Meta' ),
						),
						array(
							'itemValue' => 'description',
							'itemLabel' => __( 'Description/Excerpt' ),
						),
						array(
							'itemValue' => 'button',
							'itemLabel' => __( 'Button' ),
						),
						array(
							'itemValue' => 'secondary-entry-meta',
							'itemLabel' => __( 'Secondary Entry Meta' ),
						),
					),
				),
				'pFPMeta1Sorting'      => array(
					'type'    => 'object',
					'default' => array(
						array(
							'itemValue' => 'meta-author',
							'itemLabel' => __( 'Author' ),
						),
						array(
							'itemValue' => 'meta-date',
							'itemLabel' => __( 'Date' ),
						),
						array(
							'itemValue' => 'meta-category',
							'itemLabel' => __( 'Category' ),
						),
						array(
							'itemValue' => 'meta-comment',
							'itemLabel' => __( 'Comments' ),
						),
						array(
							'itemValue' => 'meta-tag',
							'itemLabel' => __( 'Tag' ),
						),
					),
				),
				'pFPMeta2Sorting'      => array(
					'type'    => 'object',
					'default' => array(
						array(
							'itemValue' => 'meta-author',
							'itemLabel' => __( 'Author' ),
						),
						array(
							'itemValue' => 'meta-date',
							'itemLabel' => __( 'Date' ),
						),
						array(
							'itemValue' => 'meta-category',
							'itemLabel' => __( 'Category' ),
						),
						array(
							'itemValue' => 'meta-comment',
							'itemLabel' => __( 'Comments' ),
						),
						array(
							'itemValue' => 'meta-tag',
							'itemLabel' => __( 'Tag' ),
						),
					),
				),
				'pOnFPTitle'           => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pFPTitleTag'          => array(
					'type'    => 'string',
					'default' => 'h3',
				),
				'pOnFPDesc'            => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pFPExcerptLen'        => array(
					'type'    => 'number',
					'default' => 100,
				),
				'pFPExcerptLenInWords' => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pOnFPMeta1'           => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnFPIconMeta1'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnFPMeta2'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pOnFPIconMeta2'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnFPAuthorMeta1'     => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnFPAuthorMeta2'     => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnFPDateMeta1'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnFPDateMeta2'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnFPCatMeta1'        => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnFPCatMeta2'        => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnFPCommentMeta1'    => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pOnFPCommentMeta2'    => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'categories'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'pOnFPFImg'            => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pFFPopupData'         => array(
					'type'    => 'object',
					'default' => array(
						'icon' => array(
							'label' => 'fa-book',
							'value' => (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-play' : 'fas fa-play',
							'code'  => 'f108',
						),
						'iPos' => 'g-v-popup-c',
					),
				),
				'pFFType'              => array(
					'type'    => 'string',
					'default' => 'img',
				),
				'pFFVType'             => array(
					'type'    => 'string',
					'default' => 'disable',
				),
				'pOnFPBtn'             => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pFPBtnIconOpt'        => array(
					'type'    => 'object',
					'default' => array(
						'position' => '',
						'size'     => '',
					),
				),
				'pFPBtnIcon'           => array(
					'type'    => 'object',
					'default' => array(
						'label' => 'fa-book',
						'value' => (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-book' : 'fas fa-book',
						'code'  => '',
					),
				),
				'pFPBtnLink'           => array(
					'type'    => 'object',
					'default' => array(
						'openInNewTab' => '',
						'rel'          => '',
					),
				),
				'pFPBtnText'           => array(
					'type'    => 'string',
					'default' => __( 'Read More' ),
				),
				'pFPImgOnLink'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pFPImgOpenNewTab'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pFPImgLinkRel'        => array(
					'type'    => 'string',
					'default' => 'noopener noreferrer',
				),
				'pFPFImgOColor'        => array(
					'type'    => 'object',
					'default' => array(
						'enable' => false,
						'normal' => '',
						'hover'  => '',
					),
				),
				'pOnFPImgDisplayOpt'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pFPImgDisplayOpt'     => array(
					'type'    => 'string',
					'default' => 'normal-image',
				),
				'pOnFPFeaturedCat'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pFPBadgeType'         => array(
					'type'    => 'string',
					'default' => 'category',
				),
				'pFPCatPos'            => array(
					'type'    => 'string',
					'default' => 'gutentor-fp-cat-pos-img-top-left',
				),
				'pOnFPPostFormatOpt'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pFPPostFormatOpt'     => array(
					'type'    => 'string',
					'default' => 'gutentor-fp-pf-pos-img-top-right',
				),
				'pFPPostFormatPos'     => array(
					'type'    => 'string',
					'default' => 'gutentor-fp-pf-pos-img-top-right',
				),
				'pFPFImgSize'          => array(
					'type'    => 'string',
					'default' => 'full',
				),
				/*column*/
				'pFPBxAlign'           => array(
					'type'    => 'object',
					'default' => array(
						'desktop' => '',
						'tablet'  => '',
						'mobile'  => '',
					),
				),
			);
		}

		/**
		 * Gutentor Common Attr Default Value
		 * Default Values
		 *
		 * @since      1.0.0
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 */
		public function get_term_common_attrs() {

			return array(

				/*Title*/
				'tOnTitle'           => array(
					'type'    => 'boolean',
					'default' => true,

				),
				'tTitleTag'          => array(
					'type'    => 'string',
					'default' => 'h3',
				),
				'tTitleColor'        => array(
					'type' => 'object',
				),
				'tTitleTypography'   => array(
					'type' => 'object',
				),
				'tTitleMargin'       => array(
					'type' => 'object',
				),
				'tTitlePadding'      => array(
					'type' => 'object',
				),
				/*count*/
				'tOnCount'           => array(
					'type'    => 'boolean',
					'default' => true,

				),
				'tCountOpt'          => array(
					'type'    => 'object',
					'default' => array(
						'display' => 'bottom',
						'prefix'  => '',
						'suffix'  => __( 'Items', 'gutentor' ),
					),
				),
				/*Desc*/
				'tOnDesc'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'tDescColor'         => array(
					'type' => 'object',
				),
				'tDescTypography'    => array(
					'type' => 'object',
				),
				'tDescMargin'        => array(
					'type' => 'object',
				),
				'tDescPadding'       => array(
					'type' => 'object',
				),
				'tExcerptLen'        => array(
					'type'    => 'number',
					'default' => 100,
				),
				'tExcerptLenInWords' => array(
					'type' => 'boolean',
				),
				/*Feature Image*/
				'tOnFImg'            => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'tFImgSize'          => array(
					'type'    => 'string',
					'default' => 'full',
				),
				'tFImgOC'            => array(
					'type' => 'object',
				),
				'tOnImgW'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'tImgW'              => array(
					'type' => 'object',
				),
				'tOnImgH'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'tImgH'              => array(
					'type' => 'object',
				),
				'tImgBgOpt'          => array(
					'type'    => 'object',
					'default' => array(
						'backgroundImage'      => '',
						'desktopHeight'        => '',
						'tabletHeight'         => '',
						'mobileHeight'         => '',
						'type'                 => 'px',
						'backgroundSize'       => '',
						'backgroundPosition'   => '',
						'backgroundRepeat'     => '',
						'backgroundAttachment' => '',
					),
				),
				'tOnImgDisplayOpt'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'tImgDisplayOpt'     => array(
					'type'    => 'string',
					'default' => 'normal-image',
				),
				'tOnImgBorder'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'tImgMargin'         => array(
					'type' => 'object',
				),
				'tImgPadding'        => array(
					'type' => 'object',
				),
				'tImgOnLink'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'tImgOpenNewTab'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'tImgLinkRel'        => array(
					'type'    => 'string',
					'default' => 'noopener noreferrer',
				),
				'tImgClass'          => array(
					'type' => 'string',
				),
				'tOnImgBxWidth'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'tImgBxHeight'       => array(
					'type' => 'object',
				),
				'tImgBorder'         => array(
					'type' => 'object',
				),
				/*button*/
				'tBtnIconOpt'        => array(
					'type'    => 'object',
					'default' => array(
						'position' => 'hide',
						'size'     => '',
					),
				),
				'tBtnIcon'           => array(
					'type'    => 'object',
					'default' => array(
						'label' => 'fa-book',
						'value' => (int) gutentor_get_options( 'fa-version' ) === 4 ? 'fa fa-book' : 'fas fa-book',
						'code'  => 'f108',
					),
				),
				'tBtnTxt'            => array(
					'type'    => 'string',
					'default' => __( 'Read More' ),
				),
				'tBtnLink'           => array(
					'type'    => 'object',
					'default' => array(
						'openInNewTab' => '',
						'rel'          => '',
					),
				),
				'tBtnBxShad'         => array(
					'type' => 'object',
				),
				'tOnBtn'             => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'tBtnColor'          => array(
					'type'    => 'object',
					'default' => array(
						'enable' => true,
						'normal' => '#275cf6',
						'hover'  => '#1949d4',
					),
				),
				'tBtnTxtColor'       => array(
					'type'    => 'object',
					'default' => array(
						'enable' => true,
						'normal' => '#fff',
						'hover'  => '#fff',
					),
				),
				'tBtnMargin'         => array(
					'type' => 'object',
				),
				'tBtnPadding'        => array(
					'type'    => 'object',
					'default' => array(
						'type'    => 'px',
						'dTop'    => '10',
						'dRight'  => '15',
						'dBottom' => '10',
						'dLeft'   => '15',

						'tTop'    => '10',
						'tRight'  => '15',
						'tBottom' => '10',
						'tLeft'   => '15',

						'mTop'    => '10',
						'mRight'  => '15',
						'mBottom' => '10',
						'mLeft'   => '15',
					),
				),
				'tBtnIconMargin'     => array(
					'type' => 'object',
				),
				'tBtnIconPadding'    => array(
					'type' => 'object',
				),
				'tBtnBorder'         => array(
					'type'    => 'object',
					'default' => array(
						'rTypeN'   => 'px',
						'rTypeH'   => 'px',
						'rTopN'    => '3',
						'rTopH'    => '3',
						'rRightN'  => '3',
						'rRightH'  => '3',
						'rBottomN' => '3',
						'rBottomH' => '3',
						'rLeftN'   => '3',
						'rLeftH'   => '3',
					),
				),
				'tBtnTypography'     => array(
					'type'    => 'object',
					'default' => array(
						'fontType'        => 'default',
						'desktopFontSize' => '16',
						'tabletFontSize'  => '16',
						'mobileFontSize'  => '16',
						'textTransform'   => '3',
					),
				),
				'tBtnCName'          => array(
					'type' => 'string',
				),
				/*content Box*/
				'tContPos'           => array(
					'type'    => 'object',
					'default' => array(
						'desktop' => 'g-pos-center',
						'tablet'  => 'g-pos-center',
						'mobile'  => 'g-pos-center',
					),
				),
				/*item box*/
				'tBxAlign'           => array(
					'type' => 'object',
				),
				'tBxColor'           => array(
					'type' => 'object',
				),
				'tBxBorder'          => array(
					'type' => 'object',
				),
				'tBxShadow'          => array(
					'type' => 'object',
				),
				'tBxMargin'          => array(
					'type'    => 'object',
					'default' => array(
						'type'    => 'default',
						'mTop'    => '15',
						'mBottom' => '15',
					),

				),
				'tBxPadding'         => array(
					'type' => 'object',
				),
			);

		}

		/**
		 * Repeater Common Attr Default Values
		 * Default Values
		 *
		 * @access public
		 * @return array
		 * @since 1.0.1
		 */
		public function get_single_item_common_attrs_default_values() {
			return array(

				/*single item title*/
				'blockSingleItemTitleEnable'           => true,
				'blockSingleItemTitleTag'              => 'h3',
				'blockSingleItemTitleColor'            => array(
					'enable' => false,
					'normal' => array(
						'hex' => '#111111',
					),
					'hover'  => '',
				),
				'blockSingleItemTitleTypography'       => array(
					'fontType'             => '',
					'systemFont'           => '',
					'googleFont'           => '',
					'customFont'           => '',

					'desktopFontSize'      => '',
					'tabletFontSize'       => '',
					'mobileFontSize'       => '',

					'fontWeight'           => '',
					'textTransform'        => '',
					'fontStyle'            => '',
					'textDecoration'       => '',
					'desktopLineHeight'    => '',
					'tabletLineHeight'     => '',
					'mobileLineHeight'     => '',

					'desktopLetterSpacing' => '',
					'tabletLetterSpacing'  => '',
					'mobileLetterSpacing'  => '',

				),
				'blockSingleItemTitleMargin'           => array(

					'type'          => 'px',
					'desktopTop'    => '',
					'desktopRight'  => '',
					'desktopBottom' => '',
					'desktopLeft'   => '',

					'tabletTop'     => '',
					'tabletRight'   => '',
					'tabletBottom'  => '',
					'tabletLeft'    => '',

					'mobileTop'     => '',
					'mobileRight'   => '',
					'mobileBottom'  => '',
					'mobileLeft'    => '',

				),
				'blockSingleItemTitlePadding'          => array(

					'type'          => 'px',
					'desktopTop'    => '',
					'desktopRight'  => '',
					'desktopBottom' => '',
					'desktopLeft'   => '',

					'tabletTop'     => '',
					'tabletRight'   => '',
					'tabletBottom'  => '',
					'tabletLeft'    => '',

					'mobileTop'     => '',
					'mobileRight'   => '',
					'mobileBottom'  => '',
					'mobileLeft'    => '',

				),

				/*single item description*/
				'blockSingleItemDescriptionEnable'     => true,
				'blockSingleItemDescriptionTag'        => 'p',
				'blockSingleItemDescriptionColor'      => array(
					'enable' => false,
					'normal' => '',
					'hover'  => '',
				),
				'blockSingleItemDescriptionTypography' => array(

					'fontType'             => '',
					'systemFont'           => '',
					'googleFont'           => '',
					'customFont'           => '',

					'desktopFontSize'      => '',
					'tabletFontSize'       => '',
					'mobileFontSize'       => '',

					'fontWeight'           => '',
					'textTransform'        => '',
					'fontStyle'            => '',
					'textDecoration'       => '',
					'desktopLineHeight'    => '',
					'tabletLineHeight'     => '',
					'mobileLineHeight'     => '',

					'desktopLetterSpacing' => '',
					'tabletLetterSpacing'  => '',
					'mobileLetterSpacing'  => '',

				),
				'blockSingleItemDescriptionMargin'     => array(

					'type'          => 'px',
					'desktopTop'    => '',
					'desktopRight'  => '',
					'desktopBottom' => '',
					'desktopLeft'   => '',

					'tabletTop'     => '',
					'tabletRight'   => '',
					'tabletBottom'  => '',
					'tabletLeft'    => '',

					'mobileTop'     => '',
					'mobileRight'   => '',
					'mobileBottom'  => '',
					'mobileLeft'    => '',
				),
				'blockSingleItemDescriptionPadding'    => array(

					'type'          => 'px',
					'desktopTop'    => '',
					'desktopRight'  => '',
					'desktopBottom' => '',
					'desktopLeft'   => '',

					'tabletTop'     => '',
					'tabletRight'   => '',
					'tabletBottom'  => '',
					'tabletLeft'    => '',

					'mobileTop'     => '',
					'mobileRight'   => '',
					'mobileBottom'  => '',
					'mobileLeft'    => '',
				),

				/*single item button*/
				'blockSingleItemButtonEnable'          => false,
				'blockSingleItemButtonLinkOptions'     => array(
					'openInNewTab' => false,
					'rel'          => '',
				),
				'blockSingleItemButtonColor'           => array(
					'enable' => true,
					'normal' => array(
						'hex' => '#275cf6',
						'rgb' => array(
							'r' => '39',
							'g' => '92',
							'b' => '246',
							'a' => '1',
						),
					),
					'hover'  => array(
						'hex' => '#1949d4',
						'rgb' => array(
							'r' => '25',
							'g' => '73',
							'b' => '212',
							'a' => '1',
						),
					),
				),
				'blockSingleItemButtonTextColor'       => array(
					'enable' => true,
					'normal' => array(
						'hex' => '#fff',
					),
					'hover'  => '',
				),
				'blockSingleItemButtonMargin'          => array(
					'type'          => 'px',
					'desktopTop'    => '',
					'desktopRight'  => '',
					'desktopBottom' => '',
					'desktopLeft'   => '',
					'tabletTop'     => '',
					'tabletRight'   => '',
					'tabletBottom'  => '',
					'tabletLeft'    => '',
					'mobileTop'     => '',
					'mobileRight'   => '',
					'mobileBottom'  => '',
					'mobileLeft'    => '',
				),
				'blockSingleItemButtonPadding'         => array(
					'type'          => 'px',
					'desktopTop'    => '10',
					'desktopRight'  => '15',
					'desktopBottom' => '10',
					'desktopLeft'   => '15',
					'tabletTop'     => '10',
					'tabletRight'   => '15',
					'tabletBottom'  => '10',
					'tabletLeft'    => '15',
					'mobileTop'     => '10',
					'mobileRight'   => '15',
					'mobileBottom'  => '10',
					'mobileLeft'    => '15',
				),
				'blockSingleItemButtonIconOptions'     => array(

					'position' => 'hide',
					'size'     => '',
				),
				'blockSingleItemButtonIconMargin'      => array(
					'type'          => 'px',
					'desktopTop'    => '',
					'desktopRight'  => '',
					'desktopBottom' => '',
					'desktopLeft'   => '',
					'tabletTop'     => '',
					'tabletRight'   => '',
					'tabletBottom'  => '',
					'tabletLeft'    => '',
					'mobileTop'     => '',
					'mobileRight'   => '',
					'mobileBottom'  => '',
					'mobileLeft'    => '',
				),
				'blockSingleItemButtonIconPadding'     => array(
					'type'          => 'px',
					'desktopTop'    => '',
					'desktopRight'  => '',
					'desktopBottom' => '',
					'desktopLeft'   => '',
					'tabletTop'     => '',
					'tabletRight'   => '',
					'tabletBottom'  => '',
					'tabletLeft'    => '',
					'mobileTop'     => '',
					'mobileRight'   => '',
					'mobileBottom'  => '',
					'mobileLeft'    => '',
				),
				'blockSingleItemButtonBorder'          => array(
					'borderStyle'        => '',
					'borderTop'          => '',
					'borderRight'        => '',
					'borderBottom'       => '',
					'borderLeft'         => '',
					'borderColorNormal'  => '',
					'borderColorHover'   => '',
					'borderRadiusType'   => 'px',
					'borderRadiusTop'    => '3',
					'borderRadiusRight'  => '3',
					'borderRadiusBottom' => '3',
					'borderRadiusLeft'   => '3',
				),
				'blockSingleItemButtonBoxShadow'       => array(
					'boxShadowColor'    => '',
					'boxShadowX'        => '',
					'boxShadowY'        => '',
					'boxShadowBlur'     => '',
					'boxShadowSpread'   => '',
					'boxShadowPosition' => '',
				),
				'blockSingleItemButtonTypography'      => array(
					'fontType'             => 'system',
					'systemFont'           => '',
					'googleFont'           => '',
					'customFont'           => '',

					'desktopFontSize'      => '14',
					'tabletFontSize'       => '14',
					'mobileFontSize'       => '14',

					'fontWeight'           => '',
					'textTransform'        => 'normal',
					'fontStyle'            => '',
					'textDecoration'       => '',

					'desktopLineHeight'    => '',
					'tabletLineHeight'     => '',
					'mobileLineHeight'     => '',

					'desktopLetterSpacing' => '',
					'tabletLetterSpacing'  => '',
					'mobileLetterSpacing'  => '',
				),

				/*single item box*/
				'blockSingleItemBoxColor'              => array(
					'enable' => true,
					'normal' => '',
					'hover'  => '',
				),
				'blockSingleItemBoxBorder'             => array(
					'borderStyle'        => 'none',
					'borderTop'          => '',
					'borderRight'        => '',
					'borderBottom'       => '',
					'borderLeft'         => '',
					'borderColorNormal'  => '',
					'borderColorHover'   => '',
					'borderRadiusType'   => 'px',
					'borderRadiusTop'    => '',
					'borderRadiusRight'  => '',
					'borderRadiusBottom' => '',
					'borderRadiusLeft'   => '',
				),
				'blockSingleItemBoxShadowOptions'      => array(
					'boxShadowColor'    => '',
					'boxShadowX'        => '',
					'boxShadowY'        => '',
					'boxShadowBlur'     => '',
					'boxShadowSpread'   => '',
					'boxShadowPosition' => '',
				),
				'blockSingleItemBoxMargin'             => array(
					'type'          => 'px',
					'desktopTop'    => '',
					'desktopRight'  => '',
					'desktopBottom' => '',
					'desktopLeft'   => '',
					'tabletTop'     => '',
					'tabletRight'   => '',
					'tabletBottom'  => '',
					'tabletLeft'    => '',
					'mobileTop'     => '',
					'mobileRight'   => '',
					'mobileBottom'  => '',
					'mobileLeft'    => '',
				),
				'blockSingleItemBoxPadding'            => array(
					'type'          => 'px',
					'desktopTop'    => '',
					'desktopRight'  => '',
					'desktopBottom' => '',
					'desktopLeft'   => '',
					'tabletTop'     => '',
					'tabletRight'   => '',
					'tabletBottom'  => '',
					'tabletLeft'    => '',
					'mobileTop'     => '',
					'mobileRight'   => '',
					'mobileBottom'  => '',
					'mobileLeft'    => '',
				),
			);
		}

		/**
		 * Repeater Attributes
		 *
		 * @access public
		 * @return array
		 * @since 1.0.1
		 */

		public function add_single_item_common_attrs_default_values( $attr ) {
			return array_merge_recursive( $attr, $this->get_single_item_common_attrs_default_values() );
		}
	}
}

/**
 * Return instance of  Gutentor_Block_Base class
 *
 * @since    1.0.0
 */
if ( ! function_exists( 'gutentor_block_base' ) ) {

	function gutentor_block_base() {
		return Gutentor_Block_Base::get_base_instance();
	}
}
