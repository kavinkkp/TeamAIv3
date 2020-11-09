<?php
/**
 * Meta Boxes class.
 *
 * @package     Xolo
 * @author      Xolo Software
 * @since       1.0.50
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Xolo_Meta_Boxes' ) ) :

	/**
	 * Meta Boxes Class.
	 */
	class Xolo_Meta_Boxes {

		/**
		 * Singleton instance.
		 *
		 * @since 1.0.50
		 * @var object
		 */
		private static $instance;

		/**
		 * Metabox settings array.
		 *
		 * @since 1.0.50
		 * @var array
		 */
		private $metabox = array();

		/**
		 * Whether this is a new post. Once the post is saved and we're
		 * no longer on the `post-new.php` screen, this is going to be
		 * `false`.
		 *
		 * @since 1.0.50
		 * @var   bool
		 */
		public $is_new_post = false;

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.50
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
				self::$instance->actions();
			}

			return self::$instance;
		}

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.50
		 */
		private function __construct() {}

		/**
		 * Sets up initial actions.
		 *
		 * @since  1.0.50
		 */
		private function actions() {

			// Call the register function.
			add_action( 'load-post.php', array( $this, 'register' ) );
			add_action( 'load-post-new.php', array( $this, 'register' ) );
		}

		/**
		 * Registration callback.
		 *
		 * @since  1.0.50
		 */
		public function register() {

			// If this is a new post, set the new post boolean.
			if ( 'load-post-new.php' === current_action() ) {
				$this->is_new_post = true;
			}

			// Add meta boxes.
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 1 );

			// Save settings.
			add_action( 'save_post', array( $this, 'update' ) );

			// Config.
			add_action( 'pre_get_posts', array( $this, 'metabox_config' ) );
		}

		/**
		 * Metabox config array
		 *
		 * @since  1.0.50
		 * @return void
		 */
		public function metabox_config() {

			/**
			 * Layout settings.
			 */
			$layout_settings = array();

			// Sidebar position.
			$layout_settings['xolo_sidebar_position'] = array(
				'id'      => 'xolo_sidebar_position',
				'name'    => esc_html__( 'Sidebar', 'xolo' ),
				'type'    => 'select',
				'default' => '',
				'choices' => array(
					''              => esc_html__( 'Default (from Customizer)', 'xolo' ),
					'xolo_fullwidth'    => esc_html__( 'No Sidebar', 'xolo' ),
					'xolo_lsb'  => esc_html__( 'Left Sidebar', 'xolo' ),
					'xolo_rsb' => esc_html__( 'Right Sidebar', 'xolo' ),
				),
			);
			
			// Site Layout 
			$layout_settings['xolo_site_layout_meta'] = array(
				'id'      => 'xolo_site_layout_meta',
				'name'    => esc_html__( 'Website Layout', 'xolo' ),
				'type'    => 'select',
				'default' => '',
				'choices' => array(
					''              => esc_html__( 'Default (from Customizer)', 'xolo' ),
					'contained'       => esc_html__( 'Full Width: Contained', 'xolo' ),
					'stretched' => esc_html__( 'Full Width: Stretched', 'xolo' ),
					'boxed' => esc_html__( 'Boxed', 'xolo' ),
					),
			);
			
			// Breadcrumb
			$layout_settings['xolo_disable_breadcrumbs'] = array(
				'id'      => 'xolo_disable_breadcrumbs',
				'name'    => esc_html__( 'Breadcrumb', 'xolo' ),
				'type'    => 'select',
				'default' => 'customizer',
				'choices' => array(
					'customizer'              => esc_html__( 'Default (from Customizer)', 'xolo' ),
					'1'    => esc_html__( 'Enable', 'xolo' ),
					'0'  => esc_html__( 'Disable', 'xolo' ),
				),
			);
			// Start checkbox group.
			$layout_settings['xolo_page_sections'] = array(
				'id'     => 'xolo_page_sections',
				'type'   => 'group',
				'fields' => array(),
			);

			// Set metabox options.
			$this->metabox = array(
				'xolo-theme-page-options' => array(
					'id'         => 'xolo-theme-page-options',
					'post_type'  => $this->get_post_types(),
					'capability' => 'edit_posts',
					'label'      => esc_html__( 'Xolo Settings', 'xolo' ),
					'context'    => 'side',
					'priority'   => 'core',
					'settings'   => $layout_settings,
				),
			);

			$this->metabox = apply_filters( 'xolo_metabox_options', $this->metabox );
		}

		/**
		 * Register Metabox.
		 *
		 * @since 1.0.50
		 * @param string $post_type Post Type of the current post page.
		 */
		public function add_meta_boxes( $post_type ) {

			if ( ! is_array( $this->metabox ) ) {
				return;
			}

			foreach ( $this->metabox as $id => $metabox ) {

				// If the manager is registered for the current post type, add a meta box.
				if ( in_array( $post_type, (array) $metabox['post_type'], true ) && $this->check_capability( $metabox['capability'] ) ) {

					add_meta_box(
						$metabox['id'],
						$metabox['label'],
						array( $this, 'render' ),
						$post_type,
						$metabox['context'],
						$metabox['priority'],
						array( 'settings' => $metabox['settings'] )
					);
				}
			}
		}

		/**
		 * Update Metabox value.
		 *
		 * @since 1.0.50
		 * @param int $post_id Post ID.
		 */
		public function update( $post_id ) {

			$do_autosave = defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE;
			$is_autosave = wp_is_post_autosave( $post_id );
			$is_revision = wp_is_post_revision( $post_id );

			if ( $do_autosave || $is_autosave || $is_revision ) {
				return;
			}

			// Loop through all metaboxes.
			foreach ( $this->metabox as $id => $metabox ) {

				// Capability check.
				if ( $this->check_capability( $metabox['capability'] ) && isset( $_POST[ $metabox['id'] ] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $metabox['id'] ] ) ), $metabox['id'] . '_nonce' ) ) { // phpcs:ignore

					// Loop through metabox settings.
					foreach ( $metabox['settings'] as $setting ) {

						// Group has nested settings.
						if ( 'group' === $setting['type'] ) {
							foreach ( $setting['fields'] as $id => $config ) {
								$this->save_value( $post_id, $config );
							}
						} else {
							$this->save_value( $post_id, $setting );
						}
					}
				}
			}
		}

		/**
		 * Save the new value to postmeta database.
		 *
		 * @since  1.0.50
		 * @param  object $post_id Post object ID.
		 * @param  array  $setting Metabox config array.
		 * @return void
		 */
		public function save_value( $post_id, $setting ) {

			// Get stored value.
			$old_value = $this->get_value( $post_id, $setting );

			// Get new value.
			$new_value = $this->get_posted_value( $setting );

			if ( ! $new_value && $old_value ) {
				delete_post_meta( $post_id, $setting['id'] );
			} elseif ( $new_value !== $old_value ) {
				update_post_meta( $post_id, $setting['id'], $new_value );
			}
		}

		/**
		 * Print the meta box HTML Markup.
		 *
		 * @since  1.0.50
		 * @param  object $post    Post object.
		 * @param  array  $metabox Metabox config array.
		 * @return void
		 */
		public function render( $post, $metabox ) {

			if ( empty( $metabox['args']['settings'] ) ) {
				return;
			}

			foreach ( $metabox['args']['settings'] as $setting ) {

				echo '<div class="xolo-meta-box-field">';

				if ( isset( $setting['render_field_callback'] ) && function_exists( $setting['render_field_callback'] ) ) {
					call_user_func( $setting['render_field_callback'], $post, $setting );
				} elseif ( method_exists( $this, 'render_field_' . $setting['type'] ) ) {
					call_user_func( array( $this, 'render_field_' . $setting['type'] ), $post, $setting );
				}

				echo '</div>';
			}

			// Nonce field to validate on save.
			wp_nonce_field( $metabox['id'] . '_nonce', $metabox['id'] );
		}

		/**
		 * Render select field.
		 *
		 * @since 1.0.50
		 * @param object $post    Current post object.
		 * @param array  $setting Array of field settings.
		 */
		public function render_field_select( $post, $setting ) {

			$saved_value = get_post_meta( $post->ID, $setting['id'], true );
			?>

			<div class="components-base-control">
				<div class="components-base-control__field">

					<?php if ( isset( $setting['name'] ) ) { ?>
						<label class="post-attributes-label-wrapper components-base-control__label" for="<?php echo esc_attr( $setting['id'] ); ?>">
							<?php echo esc_html( $setting['name'] ); ?>
						</label></br>
					<?php } ?>

					<select name="<?php echo esc_attr( $setting['id'] ); ?>" id="<?php echo esc_attr( $setting['id'] ); ?>">
						<?php foreach ( $setting['choices'] as $key => $value ) { ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $saved_value, $key ); ?>><?php echo esc_html( $value ); ?></option>
						<?php } ?>
					</select>

				</div>
			</div>

			<?php if ( isset( $setting['desc'] ) ) { ?>
				<p class="post-attributes-description-wrapper components-base-control__help description">
					<?php echo esc_html( $setting['desc'] ); ?>
				</p>
			<?php } ?>
			<?php
		}

		/**
		 * Render group of fields.
		 *
		 * @since 1.0.50
		 * @param object $post    Current post object.
		 * @param array  $setting Array of field settings.
		 */
		public function render_field_group( $post, $setting ) {
			?>

			<?php if ( isset( $setting['name'] ) ) { ?>
				<p class="post-attributes-label-wrapper">
					<strong><?php echo esc_html( $setting['name'] ); ?></strong>
				</p>
			<?php } ?>

			<?php if ( isset( $setting['desc'] ) ) { ?>
				<p class="post-attributes-description-wrapper">
					<?php echo esc_html( $setting['desc'] ); ?>
				</p>
			<?php } ?>

			<?php
			foreach ( $setting['fields'] as $key => $value ) {
				if ( method_exists( $this, 'render_field_' . $value['type'] ) ) {
					call_user_func( array( $this, 'render_field_' . $value['type'] ), $post, $value );
				}
			}
		}

		/**
		 * Render checkbox group field.
		 *
		 * @since 1.0.50
		 * @param object $post    Current post object.
		 * @param array  $setting Array of field settings.
		 */
		public function render_field_checkbox( $post, $setting ) {

			$saved = $this->get_value( $post->ID, $setting );
			?>
			<div class="components-panel__row xolo-checkbox-row">
				<div class="components-base-control">
					<div class="components-base-control__field">
						<input type="checkbox" name="<?php echo esc_attr( $setting['id'] ); ?>" id="<?php echo esc_attr( $setting['id'] ); ?>" <?php checked( $saved, true ); ?>>
						<label class="components-checkbox-control__label" for="<?php echo esc_attr( $setting['id'] ); ?>"><?php echo esc_html( $setting['name'] ); ?></label>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Render textarea field.
		 *
		 * @since 1.0.50
		 * @param object $post    Current post object.
		 * @param array  $setting Array of field settings.
		 */
		public function render_field_textarea( $post, $setting ) {

			$saved = $this->get_value( $post->ID, $setting );
			?>

			<div class="components-base-control">
				<div class="components-base-control__field">

					<?php if ( isset( $setting['name'] ) ) { ?>
						<label class="post-attributes-label-wrapper components-base-control__label" for="<?php echo esc_attr( $setting['id'] ); ?>">
							<?php echo esc_html( $setting['name'] ); ?>
						</label>
					<?php } ?>

					<textarea class="widefat" id="<?php echo esc_attr( $setting['id'] ); ?>" name="<?php echo esc_attr( $setting['id'] ); ?>" rows="5"><?php echo wp_kses_post( $saved ); ?></textarea>

				</div>
			</div>

			<?php if ( isset( $setting['desc'] ) ) { ?>
				<p class="post-attributes-description-wrapper components-base-control__help description">
					<?php echo wp_kses_post( $setting['desc'] ); ?>
				</p>
			<?php } ?>

			<?php
		}

		/**
		 * Render text field.
		 *
		 * @since 1.0.50
		 * @param object $post    Current post object.
		 * @param array  $setting Array of field settings.
		 */
		public function render_field_text( $post, $setting ) {

			$saved = $this->get_value( $post->ID, $setting );
			?>

			<div class="components-base-control">
				<div class="components-base-control__field">

					<?php if ( isset( $setting['name'] ) ) { ?>
						<label class="post-attributes-label-wrapper components-base-control__label" for="<?php echo esc_attr( $setting['id'] ); ?>">
							<?php echo esc_html( $setting['name'] ); ?>
						</label>
					<?php } ?>

					<input type="text" class="widefat" id="<?php echo esc_attr( $setting['id'] ); ?>" name="<?php echo esc_attr( $setting['id'] ); ?>" value="<?php echo wp_kses_post( $saved ); ?>" />

				</div>
			</div>

			<?php if ( isset( $setting['desc'] ) ) { ?>
				<p class="post-attributes-description-wrapper components-base-control__help description">
					<?php echo wp_kses_post( $setting['desc'] ); ?>
				</p>
			<?php } ?>

			<?php
		}

		/**
		 * Gets the value of the setting.
		 *
		 * @since 1.0.50
		 * @param int   $post_id Post ID.
		 * @param array $setting Array of setting data.
		 */
		public function get_value( $post_id, $setting ) {

			$value = get_post_meta( $post_id, $setting['id'], true );

			return ! $value && $this->is_new_post ? $setting['default'] : $value;
		}

		/**
		 * Gets the posted value of the setting.
		 *
		 * @since 1.0.50
		 * @param array $setting Setting config array.
		 */
		public function get_posted_value( $setting ) {

			$value = '';

			if ( isset( $_POST[ $setting['id'] ] ) ) { // phpcs:ignore
				$value = wp_unslash( $_POST[ $setting['id'] ] ); // phpcs:ignore
			}

			return $this->sanitize( $value, $setting );
		}

		/**
		 * Return array of post types for metabox.
		 *
		 * @since 1.0.50
		 * @param array $args Args for get_post_types function.
		 */
		public function get_post_types( $args = false ) {

			if ( ! $args ) {
				$args = array(
					'public' => true,
				);
			}

			$return = get_post_types( $args );

			return $return;
		}

		/**
		 * Sanitizes the value of the setting.
		 *
		 * @since 1.0.50
		 * @param mixed $value   Value of the field to be sanitized.
		 * @param arary $setting Setting config array.
		 */
		public function sanitize( $value, $setting ) {

			$filter = isset( $setting['type'] ) ? $setting['type'] : '';

			switch ( $filter ) {
				case 'select':
					// Ensure input is a slug.
					$value = sanitize_key( $value );

					// Get list of choices from the setting.
					$choices = $setting['choices'];

					// If the input is a valid key, return it; otherwise, return the default.
					$value = array_key_exists( $value, $choices ) ? $value : $setting['default'];
					break;
				case 'checkbox':
					// Ensure boolean value for $value.
					$value = (bool) $value;
					break;
				case 'text':
					$value = sanitize_text_field( $value );
					break;
				case 'textarea':
					$value = wp_kses_post( $value );
					break;
				case 'no_sanitize':
				default:
					break;
			}

			return $value;
		}

		/**
		 * Checks if the control should be allowed at all.
		 *
		 * @since 1.0.50
		 * @param string $cap Capability.
		 */
		public function check_capability( $cap ) {

			if ( ! current_user_can( $cap ) ) {
				return false;
			}

			return true;
		}
	}
endif;

/**
 * The function which returns the one Xolo Meta Boxes instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @since  1.0.50
 * @access public
 * @return object
 */
function xolo_meta_boxes() {
	return Xolo_Meta_Boxes::get_instance();
}

xolo_meta_boxes();
