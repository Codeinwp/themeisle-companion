<?php
/**
 * Gutenberg Blocks modules Orbit Fox Module.
 *
 * @link       https://themeisle.com
 * @since      2.2.5
 */

/**
 * Class Gutenberg_Blocks_OBFX_Module
 */
class Gutenberg_Blocks_OBFX_Module extends Orbit_Fox_Module_Abstract {

	protected $blocks_classes = array();

	/**
	 * Gutenberg_Blocks_OBFX_Module constructor.
	 *
	 * @since   2.2.5
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();

		$this->name = __( 'Gutenberg Blocks', 'themeisle-companion' );

		/*
		 * translators: %1$s Start anchor tag, %2$s End anchor tag
		 */
		$this->description  = sprintf( __( 'A set of awesome Gutenberg Blocks provided by %1$sOtter\'s%2$s plugin!', 'themeisle-companion' ), '<a target="_blank" href="https://wordpress.org/plugins/otter-blocks/">', '</a>' );
		$this->description .= '<p class="notice notice-warning">' .
								/*
								 * translators: %s Otter plugin link
								 */
								sprintf( __( 'This module will soon be removed form Orbit Fox. To keep the content you created with this module, please install %s', 'themeisle-companion' ), '<a target="_blank" href="https://wordpress.org/plugins/otter-blocks/">Gutenberg Blocks and Template Library by Otter</a>.' )
								. '</p>';

		if ( $this->check_new_user( 'obfx_remove_gtb_blocks' ) === false ) {
			$this->active_default = true;
		}
	}

	/**
	 * Determine if module should be loaded.
	 *
	 * @return bool
	 * @since   2.2.5
	 * @access  public
	 */
	public function enable_module() {
		global $wp_version;
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( is_plugin_active( 'otter-blocks/otter-blocks.php' ) ) {
			return false;
		}
		if ( ! $this->is_module_active() ) {
			return false;
		}
		if ( $this->check_new_user( 'obfx_remove_gtb_blocks' ) ) {
			return false;
		}
		if ( version_compare( $wp_version, '5.0', '>=' ) ) {
			return true;
		}
		if ( is_plugin_active( 'gutenberg/gutenberg.php' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * The loading logic for the module.
	 *
	 * @since   2.2.5
	 * @access  public
	 */
	public function load() {
	}

	/**
	 * Method to define hooks needed.
	 *
	 * @since   2.2.5
	 * @access  public
	 */
	public function hooks() {
		$this->loader->add_action( 'enqueue_block_assets', $this, 'enqueue_block_assets' );
		$this->loader->add_action( 'init', $this, 'load_gutenberg_blocks' );
		$this->loader->add_action( 'admin_notices', $this, 'add_otter_notice' );
		$this->loader->add_action( 'admin_init', $this, 'dismiss_otter_notice' );
	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the front end part.
	 *
	 * @return array
	 * @since   2.2.5
	 * @access  public
	 */
	public function public_enqueue() {
		return array();
	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the admin part.
	 *
	 * @return array
	 * @since   2.2.5
	 * @access  public
	 */
	public function admin_enqueue() {
		return array();
	}

	/**
	 * Method to define the options fields for the module
	 *
	 * @return array
	 * @since   2.2.5
	 * @access  public
	 */
	public function options() {
		return array();
	}

	/**
	 * Load assets for our blocks.
	 */
	public function enqueue_block_assets() {
		wp_enqueue_style( 'font-awesome-5', plugins_url( 'assets/fontawesome/css/all.min.css', __FILE__ ), [], $this->version );
		wp_enqueue_style( 'font-awesome-4-shims', plugins_url( 'assets/fontawesome/css/v4-shims.min.css', __FILE__ ), [], $this->version );
	}

	/**
	 * If the composer library is present let's try to init.
	 */
	public function load_gutenberg_blocks() {
		$file = OBX_PATH . '/vendor/codeinwp/gutenberg-blocks/load.php';
		if ( is_file( $file ) ) {
			require_once $file;
		}
		\ThemeIsle\GutenbergBlocks\Main::instance( __( 'Blocks by OrbitFox and Otter', 'themeisle-companion' ) );
	}

	/**
	 * If Gutenberg Blocks module is active, we should add a notice to install Otter.
	 */
	public function add_otter_notice() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( empty( $screen ) ) {
			return;
		}

		if ( ! in_array( $screen->id, array( 'toplevel_page_obfx_companion' ), true ) ) {
			return;
		}

		global $current_user;

		$user_id = $current_user->ID;

		if ( get_user_meta( $user_id, 'obfx_dismiss_otter_notice' ) ) {
			return;
		}

		echo '<div class="notice notice-warning" style="position: relative;">';
		echo '<p style="max-width: 90%;">';
		esc_html_e( 'It seems you are using the Gutenberg Blocks module from Orbit Fox.', 'themeisle-companion' );

		/*
		 * translators: %s Otter plugin link
		 */
		printf( esc_html__( 'This module will soon be removed. In order to keep the content you created with this module, please install %s', 'themeisle-companion' ), '<span class="dashicons dashicons-external"></span><a target="_blank" href="https://wordpress.org/plugins/otter-blocks/">Gutenberg Blocks and Template Library by Otter</a>' );
		echo '</p>';
		echo '<a href="' . esc_url( add_query_arg( 'obfx_dismiss_otter_notice', '0', admin_url( 'admin.php?page=obfx_companion&show_plugins=yes' ) ) ) . '" class="notice-dismiss" style="text-decoration: none;">';
		echo '<span class="screen-reader-text">' . esc_html__( 'Dismiss this notice.', 'themeisle-companion' ) . '</span>';
		echo '</a>';
		echo '</div>';
	}

	/**
	 * Dismiss otter notice.
	 */
	public function dismiss_otter_notice() {
		global $current_user;

		$user_id = $current_user->ID;

		if ( isset( $_GET['obfx_dismiss_otter_notice'] ) && '0' == $_GET['obfx_dismiss_otter_notice'] ) {
			add_user_meta( $user_id, 'obfx_dismiss_otter_notice', 'true', true );
		}
	}

}
