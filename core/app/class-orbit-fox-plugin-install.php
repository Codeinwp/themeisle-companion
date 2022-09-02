<?php
/**
 * Class that handles plugins installation.
 */

/**
 * Class Orbit_Fox_Plugin_Install
 */
class Orbit_Fox_Plugin_Install {

	/**
	 * Get info from wordpress.org api.
	 *
	 * @param string $slug Plugin slug.
	 *
	 * @return array|mixed|object|WP_Error
	 */
	public function call_plugin_api( $slug ) {
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$call_api = get_transient( 'ti_plugin_info_' . $slug );

		if ( false === $call_api ) {
			$call_api = plugins_api(
				'plugin_information',
				array(
					'slug'   => $slug,
					'fields' => array(
						'downloaded'        => false,
						'rating'            => false,
						'description'       => false,
						'short_description' => true,
						'donate_link'       => false,
						'tags'              => false,
						'sections'          => true,
						'homepage'          => true,
						'added'             => false,
						'last_updated'      => false,
						'compatibility'     => false,
						'tested'            => false,
						'requires'          => false,
						'downloadlink'      => false,
						'icons'             => true,
						'banners'           => true,
					),
				)
			);
			set_transient( 'ti_plugin_info_' . $slug, $call_api, 1 * DAY_IN_SECONDS );
		}

		return $call_api;
	}

	/**
	 * Check plugin state.
	 *
	 * @param string $slug plugin slug.
	 *
	 * @return bool
	 */
	public function check_plugin_state( $slug ) {

		$plugin_link_suffix = self::get_plugin_path( $slug );

		if ( file_exists( ABSPATH . 'wp-content/plugins/' . $plugin_link_suffix ) ) {
			return is_plugin_active( $plugin_link_suffix ) ? 'deactivate' : 'activate';
		}

		return 'install';
	}


	/**
	 * Get plugin path based on plugin slug.
	 *
	 * @param string $slug Plugin slug.
	 *
	 * @return string
	 */
	public static function get_plugin_path( $slug ) {

		switch ( $slug ) {
			case 'translatepress-multilingual':
				return $slug . '/index.php';
			case 'feedzy-rss-feeds':
				return $slug . '/feedzy-rss-feed.php';
			case 'wp-cloudflare-page-cache':
				return $slug . '/wp-cloudflare-super-page-cache.php';
			case 'multiple-pages-generator-by-porthas':
				return $slug . '/porthas-multi-pages-generator.php';
			default:
				return $slug . '/' . $slug . '.php';
		}
	}

	/**
	 * Get Plugin Action link.
	 *
	 * @param string $slug plugin slug.
	 * @param string $action action [activate, deactivate].
	 * @return string
	 */
	public function get_plugin_action_link( $slug, $action = 'activate' ) {
		if ( ! in_array( $action, array( 'activate', 'deactivate' ) ) ) {
			return '';
		}

		return add_query_arg(
			array(
				'action'        => $action,
				'plugin'        => rawurlencode( $this->get_plugin_path( $slug ) ),
				'plugin_status' => 'all',
				'paged'         => '1',
				'_wpnonce'      => wp_create_nonce( $action . '-plugin_' . $this->get_plugin_path( $slug ) ),
			),
			esc_url( network_admin_url( 'plugins.php' ) )
		);
	}
}
