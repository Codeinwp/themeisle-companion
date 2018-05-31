<?php
/**
 * A module to check changes before theme updates.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Theme_Update_Check_OBFX_Module
 */

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Theme_Update_Check_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 */
class Safe_Updates_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * @var string ThemeCheck api endpoint.
	 */
	const API_ENDPOINT = 'https://dashboard.orbitfox.com/api/obfxhq/v1/updates/create/';

	/**
	 * Test_OBFX_Module constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name        = __( 'Safe Updated', 'themeisle-companion' );
		$this->description = __( 'OrbitFox will give you visual feedback on how your newest theme updates will affect your site. For the moment this is available only for wordpress.org themes.', 'themeisle-companion' );
	}

	/**
	 * Method to determine if the module is enabled or not.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return bool
	 */
	public function enable_module() {
		return true;
	}

	/**
	 * The method for the module load logic.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return mixed
	 */
	public function load() {
		return;
	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the front end part.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function public_enqueue() {
		return array();
	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the admin part.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function admin_enqueue() {
		if ( ! $this->is_safe_updates_active() ) {
			return array();
		}
		$current_screen = get_current_screen();
		if ( $current_screen->id != 'themes' && $current_screen->id != 'update-core' ) {
			return array();
		}
		$info = $this->is_update_available();

		if ( empty( $info ) ) {
			return array();
		}
		$request_data = array(
			'theme'           => $info['theme'],
			'current_version' => $info['current_version'],
			'next_version'    => $info['new_version'],
		);

		$data         = $this->get_safe_updates_data( $request_data );
		if ( empty( $data ) ) {
			return array();
		}
		$this->localized = array(
			'theme-update-check' => array(
				'slug' => $this->get_active_theme_dir()
			),
		);

		$changes_info = sprintf(
			'<small>' . __( 'There is a visual difference of %1$s%% between version %2$s and %3$s. 
							<a href="%4$s" target="_blank">View details</a> - according', 'themeisle-companion' ) . ' to OrbitFox</small>',
			$data['global_diff'],
			$request_data['next_version'],
			$request_data['next_version'],
			$data['gallery_url']
		);

		$this->localized['theme-update-check']['check_msg'] = $changes_info;

		return array(
			'js' => array(
				'theme-update-check' => array( 'jquery', 'wp-lists', 'backbone' ),
			),
		);
	}

	/**
	 * Check if safe updates is turned on.
	 *
	 * @return bool Safe updates status.
	 */
	private function is_safe_updates_active() {

		return (bool) $this->get_option( 'auto_update_checks' );
	}

	private function is_update_available( $transient = null ) {

		if ( $transient === null ) {
			$transient = get_site_transient( 'update_themes' );
		}

		$slug = $this->get_active_theme_dir();

		if ( ! isset( $transient->response[ $slug ]['new_version'] ) ) {
			return false;
		}

		if ( version_compare( $transient->response[ $slug ]['new_version'], $transient->checked[ $slug ], '>' ) ) {
			$transient->response[ $slug ]['current_version'] = $transient->checked[ $slug ];

			return $transient->response[ $slug ];
		}

		return false;
	}

	private function get_safe_updates_data( $args = array() ) {

		$payload_sha = $this->get_safe_updates_hash( $args );
		$checks      = $this->get_option( 'checks' );

		if ( ! isset( $checks[ $payload_sha ] ) || empty( $checks[ $payload_sha ] ) || ! is_array( $checks[ $payload_sha ] ) || $checks[ $payload_sha ]['theme'] !== $args['theme'] ) {
			return array();
		}

		return $checks[ $payload_sha ];
	}

	private function get_safe_updates_hash( $args = array() ) {
		$args = ksort( $args );

		$payload_sha = hash_hmac( 'sha256', json_encode( $args ), self::API_ENDPOINT );

		return $payload_sha;
	}

	/**
	 * Method to define the options fields for the module
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function options() {
		return array(
			array(
				'name'    => 'checks',
				'type'    => 'custom',
				'default' => array()
			),
			array(
				'id'      => 'auto_update_checks',
				'title'   => 'Activate safe updates feedback',
				'name'    => 'auto_update_checks',
				'type'    => 'toggle',
				'label'   => 'Allow OrbitFox to get your current theme slug and check how the next updates will affect your site.',
				'default' => '0',
			),
		);
	}

	/**
	 * Method to define actions and filters needed for the module.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function hooks() {
		if ( ! $this->is_safe_updates_active() ) {
			return;
		}

		$this->loader->add_filter( 'pre_set_site_transient_update_themes', $this, 'check_for_update_filter' );
		add_filter( "wp_prepare_themes_for_js", array( $this, 'theme_update_message' ) );
	}

	public function theme_update_message( $themes ) {

		if ( ! $this->is_safe_updates_active() ) {
			return $themes;
		}
		$info = $this->is_update_available();
		if ( empty( $info ) ) {
			return $themes;
		}
		$changes_info = '';
		if ( isset( $info['changes'] ) && ! empty( $info['changes'] ) ) {
			$changes      = $info['changes'];
			$changes_info = sprintf(
				'<p><strong>' .
				__( 'There is a visual difference of %1$s%% between version %2$s and %3$s. 
									<a href="%4$s" target="_blank">View details</a>.', 'themeisle-companion' ) .
				'</strong> -- by OrbitFox</p>',
				$changes['global_diff'],
				$info['new_version'],
				$info['current_version'],
				$changes['gallery_url']
			);
		}
		$themes[ $info['theme'] ]['update'] = $themes[ $info['theme'] ]['update'] . $changes_info;

		return $themes;
	}

	/**
	 * The filter that checks if there are updates to the theme or plugin
	 * using the WP License Manager API.
	 *
	 * @since   1.0.0
	 * @access  public
	 *
	 * @param   mixed $transient The transient used for WordPress
	 *                              theme / plugin updates.
	 *
	 * @return mixed        The transient with our (possible) additions.
	 */
	public function check_for_update_filter( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$info = $this->is_update_available( $transient );
		if ( $info === false ) {
			return $transient;
		}
		$changes = $this->changes_check( $info );
		if ( empty( $changes ) ) {
			return $transient;
		}
		$transient->response[ $this->get_active_theme_dir() ]['changes'] = $changes;

		return $transient;
	}

	private function changes_check( $info ) {

		$request_data = array(
			'theme'           => $info['theme'],
			'current_version' => $info['current_version'],
			'next_version'    => $info['new_version'],
		);
		$data = $this->get_safe_updates_data( $request_data );
		if ( ! empty( $data ) ) {
			return $data;
		}
		$response = wp_remote_post( self::API_ENDPOINT, array(
				'method'  => 'POST',
				'timeout' => 3,
				'body'    => $request_data,
			)
		);
		if ( is_wp_error( $response ) ) {
			return array();
		}
		$response_data = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! is_array( $response_data ) ) {
			return array();
		}

		if ( strval( $response_data['code'] ) !== '200' ) {
			return array();
		}
		$response_data = $response_data['data'];
		if ( ! is_array( $response_data ) ) {
			return array();
		}
		$option_data = array(
			$this->get_safe_updates_hash( $request_data ) => $response_data
		);

		$this->set_option( 'checks', $option_data );

		return $response_data;
	}
}