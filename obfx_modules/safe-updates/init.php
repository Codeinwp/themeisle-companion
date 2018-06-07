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
		$this->beta        = true;
		$this->name        = __( 'Safe Updates', 'themeisle-companion' );
		$this->description = __( 'OrbitFox will give you visual feedback on how your current theme updates will affect your site. For the moment this is available only for wordpress.org themes.', 'themeisle-companion' );
	}

	/**
	 * Method to determine if the module is enabled or not.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return bool
	 */
	public function enable_module() {
		return ( $this->beta ) ? $this->is_lucky_user() : true;
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

		$data = $this->get_safe_updates_data( $request_data );
		if ( empty( $data ) ) {
			return array();
		}
		$this->localized = array(
			'theme-update-check' => array(
				'slug' => $this->get_active_theme_dir(),
			),
		);
		$changes_info    = $this->get_message_notice( array(
			'global_diff'     => $data['global_diff'],
			'current_version' => $info['current_version'],
			'new_version'     => $info['new_version'],
			'gallery_url'     => $data['gallery_url'],
		) );

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

	/**
	 * Check if there is an update available.
	 *
	 * @param null $transient Transient to check.
	 *
	 * @return bool Is update available?
	 */
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
			$this->changes_check( $transient->response[ $slug ] );

			return $transient->response[ $slug ];
		}

		return false;
	}

	/**
	 * Check remote api for safe updates data.
	 *
	 * @param array $info Theme details.
	 *
	 * @return array Remote api message.
	 */
	private function changes_check( $info ) {

		$request_data = array(
			'theme'           => $info['theme'],
			'current_version' => $info['current_version'],
			'next_version'    => $info['new_version'],
		);
		$data         = $this->get_safe_updates_data( $request_data );
		if ( ! empty( $data ) ) {
			return $data;
		}
		$response = wp_remote_post( self::API_ENDPOINT, array(
				'method'  => 'POST',
				'timeout' => 2,
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
			$this->get_safe_updates_hash( $request_data ) => $response_data,
		);

		$this->set_option( 'checks', $option_data );

		return $response_data;
	}

	/**
	 * Get cached safe updates api data.
	 *
	 * @param array $args Args to check.
	 *
	 * @return array Api data.
	 */
	private function get_safe_updates_data( $args = array() ) {

		$payload_sha = $this->get_safe_updates_hash( $args );
		$checks      = $this->get_option( 'checks' );

		if ( ! isset( $checks[ $payload_sha ] ) || empty( $checks[ $payload_sha ] ) || ! is_array( $checks[ $payload_sha ] ) || $checks[ $payload_sha ]['theme'] !== $args['theme'] ) {
			return array();
		}

		return $checks[ $payload_sha ];
	}

	/**
	 * Get hash key based on the request data.
	 *
	 * @param array $args Arguments used to generate hash.
	 *
	 * @return string Hash key.
	 */
	private function get_safe_updates_hash( $args = array() ) {
		$args = ksort( $args );

		$payload_sha = hash_hmac( 'sha256', json_encode( $args ), self::API_ENDPOINT );

		return $payload_sha;
	}

	/**
	 * Return message string for safe updates notice.
	 *
	 * @param array $args Message placeholder.
	 *
	 * @return string Message string.
	 */
	public function get_message_notice( $args ) {
		return sprintf(

			__( 'According to OrbitFox<sup>&copy;</sup> there is a visual difference of %1$s%% between your current version and <b>v%2$s</b>. 
									 <a href="%3$s" target="_blank">View report</a>.', 'themeisle-companion' ),
			$args['global_diff'],
			$args['new_version'],
			$args['gallery_url']
		);
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
				'default' => array(),
			),
			array(
				'id'      => 'auto_update_checks',
				'title'   => '',
				'name'    => 'auto_update_checks',
				'type'    => 'toggle',
				'label'   => 'Allow OrbitFox to get your current theme slug and run a visual comparison report between your current and next version.',
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

		$this->loader->add_filter( 'wp_prepare_themes_for_js', $this, 'theme_update_message' );
	}

	/**
	 * Alter theme update message.
	 *
	 * @param array $themes List of themes.
	 *
	 * @return mixed Altered message.
	 */
	public function theme_update_message( $themes ) {

		if ( ! $this->is_safe_updates_active() ) {
			return $themes;
		}
		$info = $this->is_update_available();
		if ( empty( $info ) ) {
			return $themes;
		}
		$request_data = array(
			'theme'           => $info['theme'],
			'current_version' => $info['current_version'],
			'next_version'    => $info['new_version'],
		);

		$data = $this->get_safe_updates_data( $request_data );
		if ( empty( $data ) ) {
			return $themes;
		}
		$changes_info = $this->get_message_notice( array(
			'global_diff'     => $data['global_diff'],
			'current_version' => $info['current_version'],
			'new_version'     => $info['new_version'],
			'gallery_url'     => $data['gallery_url'],
		) );

		$themes[ $info['theme'] ]['update'] = $themes[ $info['theme'] ]['update'] . $changes_info;

		return $themes;
	}

}