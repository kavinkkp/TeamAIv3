<?php
/**
 * Xolo Theme Customizer.
 *
 * @package Xolo
 */

 if ( ! class_exists( 'Xolo_Customizer' ) ) {

	/**
	 * Customizer Loader
	 *
	 * @since 1.0.0
	 */
	class Xolo_Customizer {

		/**
		 * Instance
		 *
		 * @access private
		 * @var object
		 */
		private static $instance;

		/**
		 * Initiator
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
			/**
			 * Customizer
			 */
			add_action( 'customize_preview_init',                  array( $this, 'xolo_customize_preview_js' ) );
			add_action( 'customize_controls_enqueue_scripts',      array( $this, 'xolo_controls_scripts' ) );
			add_action( 'customize_register',                      array( $this, 'xolo_customizer_register' ) );
			add_action( 'after_setup_theme',                       array( $this, 'xolo_customizer_settings' ) );
		}
		
		/**
		 * Add postMessage support for site title and description for the Theme Customizer.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function xolo_customizer_register( $wp_customize ) {
			
			$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
			$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
			$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
			$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
			$wp_customize->get_setting('custom_logo')->transport = 'refresh';			
			
			/**
			 * Register controls
			 */
			$wp_customize->register_control_type( 'Xolo_Control_Sortable' );
			$wp_customize->register_control_type( 'Xolo_Customizer_Range_Control' );
			$wp_customize->register_control_type( 'Xolo_Color_Control' );
			
			/**
			 * Helper files
			 */
			require XOLO_PARENT_INC_DIR . '/customizer/customizer-controls.php';
			require XOLO_PARENT_INC_DIR . '/customizer/sanitization.php';
		}
		
		/**
		 * Customizer Controls
		 *
		 * @since 1.0.0
		 * @return void
		 */
		function xolo_controls_scripts() {
				global $wp_version;
				$js_prefix  = '.js';
				$css_prefix = '.css';
			
			/**
			 * Localize wp-color-picker & wpColorPickerL10n.
			 *
			 * This is only needed in WordPress version >= 5.5 because wpColorPickerL10n has been removed.
			 *
			 * @see https://github.com/WordPress/WordPress/commit/7e7b70cd1ae5772229abb769d0823411112c748b
			 *
			 * This is should be removed once the issue is fixed from wp-color-picker-alpha repo.
			 * @see https://github.com/kallookoo/wp-color-picker-alpha/issues/35
			 *
			 * @since 1.0.54
			 */
			if ( version_compare( $wp_version, '5.4.99', '>=' ) ) {
				wp_localize_script(
					'wp-color-picker',
					'wpColorPickerL10n',
					array(
						'clear'            => __( 'Clear', 'xolo' ),
						'clearAriaLabel'   => __( 'Clear color', 'xolo' ),
						'defaultString'    => __( 'Default', 'xolo' ),
						'defaultAriaLabel' => __( 'Select default color', 'xolo' ),
						'pick'             => __( 'Select Color', 'xolo' ),
						'defaultLabel'     => __( 'Color value', 'xolo' ),
					)
				);
			}
			
			// Customizer Core.
			wp_enqueue_script( 'xolo-customizer-controls-toggle-js', XOLO_PARENT_INC_URI . '/customizer/assets/js/customizer-toggle-control' . $js_prefix, array(), XOLO_THEME_VERSION, true );

			// Customizer Controls.
			wp_enqueue_script( 'xolo-customizer-controls-js', XOLO_PARENT_INC_URI . '/customizer/assets/js/customizer-control' . $js_prefix, array( 'xolo-customizer-controls-toggle-js' ), XOLO_THEME_VERSION, true );
		}

		/**
		 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
		 */
		function xolo_customize_preview_js() {
			wp_enqueue_script( 'xolo-customizer', XOLO_PARENT_INC_URI . '/customizer/assets/js/customizer-preview.js', array( 'customize-preview' ), '20151215', true );
		}

		// Include customizer customizer settings.
			
		function xolo_customizer_settings() {	
			require XOLO_PARENT_INC_DIR . '/customizer/customizer-options/xolo-header.php';
			require XOLO_PARENT_INC_DIR . '/customizer/customizer-options/xolo-global-settings.php';
			require XOLO_PARENT_INC_DIR . '/customizer/customizer-options/xolo-blog.php';
			require XOLO_PARENT_INC_DIR . '/customizer/customizer-options/xolo-sidebar.php';
			require XOLO_PARENT_INC_DIR . '/customizer/customizer-options/xolo-footer.php';
		    require XOLO_PARENT_INC_DIR . '/customizer/customizer-options/xolo-typography.php';
			require XOLO_PARENT_INC_DIR . '/customizer/customizer-pro/class-customize.php';
		}

	}
}// End if().

/**
 *  Kicking this off by calling 'get_instance()' method
 */
Xolo_Customizer::get_instance();