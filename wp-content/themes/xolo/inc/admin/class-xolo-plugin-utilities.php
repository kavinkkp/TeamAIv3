<?php
/**
 * Plugin utilities class.
 *
 * This class has functions to install, activate & deactivate plugins.
 *
 * @package Xolo
 * @author  Xolo Software
 * @since   1.0.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin utilities.
 * Class that contains methods for changing plugin status.
 *
 * @since 1.0.0
 */
class Xolo_Plugin_Utilities {

	/**
	 * Singleton instance of the class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	private static $instance;

	/**
	 * Main Xolo Plugin Utilities Instance.
	 *
	 * @since 1.0.0
	 * @return Xolo_Plugin_Utilities
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Xolo_Plugin_Utilities ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
	}

	/**
	 * Activate array of plugins.
	 *
	 * @param array, $plugins Plugins to be activated.
	 * @since 1.0.0
	 */
	public function activate_plugins( $plugins ) {

		$status = array();

		wp_clean_plugins_cache( false );

		// Activate plugins.
		foreach ( $plugins as $plugin ) {
			$status[ $plugin['slug'] ]['activate'] = $this->activate_plugin( $plugin['slug'] );
		}

		return $status;
	}

	/**
	 * Activate individual plugin.
	 *
	 * @param array, $plugin Plugin to be activated.
	 * @return void|WP_Error
	 * @since 1.0.0
	 */
	public function activate_plugin( $plugin ) {

		// Check permissions.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return new WP_Error(
				'plugin_activation_failed',
				esc_html__( 'Current user can\'t activate plugins', 'xolo' )
			);
		}

		// Validate plugin data.
		if ( empty( $plugin ) ) {
			return new WP_Error(
				'plugin_activation_failed',
				esc_html__( 'Missing plugin data.', 'xolo' )
			);
		}

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php'; // phpcs:ignore
		}

		$plugin_data = get_plugins( '/' . $plugin );

		if ( empty( $plugin_data ) ) {
			return new WP_Error(
				'plugin_activation_failed',
				sprintf(
					// translators: %s is plugin name.
					esc_html__( 'Plugin %s is not installed.', 'xolo' ),
					$plugin
				)
			);
		}

		$plugin_file_array  = array_keys( $plugin_data );
		$plugin_file        = $plugin_file_array[0];
		$plugin_to_activate = $plugin . '/' . $plugin_file;
		$activate           = activate_plugin( $plugin_to_activate );

		if ( is_wp_error( $activate ) ) {
			return $activate;
		}

		do_action( 'xolo_plugin_activated_' . $plugin );
	}

	/**
	 * Deactivate individual plugin
	 *
	 * @param array, $plugin Plugin to be deactivated.
	 * @return void|WP_Error
	 * @since 1.0.0
	 */
	public function deactivate_plugin( $plugin ) {

		// Check permissions.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return new WP_Error(
				'plugin_activation_failed',
				esc_html__( 'Current user can\'t activate plugins', 'xolo' )
			);
		}

		// Validate plugin data.
		if ( empty( $plugin ) ) {
			return new WP_Error(
				'plugin_activation_failed',
				esc_html__( 'Missing plugin data.', 'xolo' )
			);
		}

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php'; // phpcs:ignore
		}

		$plugin_data = get_plugins( '/' . $plugin );

		if ( empty( $plugin_data ) ) {
			return new WP_Error(
				'plugin_deactivation_failed',
				sprintf(
					// translators: %s is plugin name.
					esc_html__( 'Plugin %s is not active.', 'xolo' ),
					$plugin
				)
			);
		}

		$plugin_file_array    = array_keys( $plugin_data );
		$plugin_file          = $plugin_file_array[0];
		$plugin_to_deactivate = $plugin . '/' . $plugin_file;

		deactivate_plugins( $plugin_to_deactivate );

		do_action( 'xolo_plugin_deactivated_' . $plugin );
	}

	/**
	 * Check if plugin has a pending update.
	 *
	 * @param array,   $plugin Plugin to be activated.
	 * @param boolean, $strict Force plugin to update. Optional. Default is false.
	 * @since 1.0.0
	 */
	public function has_update( $plugin, $strict = false ) {

		$installed_plugin = $this->is_installed( $plugin['slug'] );

		if ( is_array( $installed_plugin ) && ! empty( $installed_plugin ) ) {

			$plugin_name = array_keys( $installed_plugin );
			$plugin_name = $plugin_name[0];

			$plugin_version = $installed_plugin ? $installed_plugin[ $plugin_name ]['Version'] : null;

			if ( $plugin_name && ! empty( $plugin_version ) ) {
				if ( isset( $plugin['version'] ) ) {
					return version_compare( $plugin_version, $plugin['version'], '<' );
				} elseif ( $strict ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Check if plugin is installed.
	 *
	 * @param array, $plugin Check if plugin is installed.
	 * @since 1.0.0
	 */
	public function is_installed( $plugin ) {

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php'; // phpcs:ignore
		}

		$installed = get_plugins( '/' . $plugin );

		if ( ! empty( $installed ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if plugin is activated.
	 *
	 * @param array, $plugin Check if plugin is activated.
	 * @since 1.0.0
	 */
	public function is_activated( $plugin ) {

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php'; // phpcs:ignore
		}

		$installed_plugin = get_plugins( '/' . $plugin );

		if ( $installed_plugin ) {
			$plugin_name = array_keys( $installed_plugin );
			return is_plugin_active( $plugin . '/' . $plugin_name[0] );
		}

		return false;
	}

	/**
	 * Recommended plugins.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_recommended_plugins() {

		$plugins = array(
			'xolo-websites' => array(
				'name'  => 'Xolo Websites',
				'slug'  => 'xolo-websites',
				'desc'  => 'Install Xolo Websites plugin, It will provide you a complete demo import features. Using this features, you can make website within 5 minutes.',
				'html' => sprintf(wp_kses_post('<a href="'. esc_url( admin_url( 'themes.php?page=xolo-websites' ) ) .'" class="xl-btn xl-btn-fill xl-btn-dark xolo-btn secondary active" >Browse Demos</a>', 'xolo')),
			),
			'elementor'   => array(
				'name'      => 'Elementor Page Builder',
				'slug'      => 'elementor',
				'desc'      => "Elementor plugin is popular page builder plugins. Millions of users loving it. It's a free to download. Install and activate this plugin now.",
				'endurance' => false,
				'html' => '',
			),
		);

		return apply_filters( 'xolo_recommended_plugins', $plugins );
	}

	/**
	 * Get non activated plugins from an array.
	 *
	 * @param array, $plugins Filter non active plugins.
	 * @since 1.0.0
	 */
	public function get_deactivated_plugins( $plugins ) {

		if ( is_array( $plugins ) && ! empty( $plugins ) ) {
			foreach ( $plugins as $slug => $plugin ) {
				if ( $this->is_activated( $slug ) ) {
					unset( $plugins[ $slug ] );
				}
			}
		}

		return $plugins;
	}

	/**
	 * Get plugin object based on slug.
	 *
	 * @since 1.0.0
	 * @param string $slug Plugin slug.
	 * @param array  $plugins Array of available plugins.
	 */
	public function get_plugin_by_slug( $slug, $plugins ) {

		if ( ! empty( $plugins ) ) {
			foreach ( $plugins as $plugin ) {
				if ( $plugin['slug'] === $slug ) {
					return $plugin;
				}
			}
		}

		return false;
	}
}

/**
 * The function which returns the one Sintra_Plugin_Utilities instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $xolo_plugin_utilities = xolo_plugin_utilities(); ?>
 *
 * @since 1.0.0
 * @return object
 */
function xolo_plugin_utilities() {
	return Xolo_Plugin_Utilities::instance();
}
