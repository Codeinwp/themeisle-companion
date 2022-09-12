<?php
/**
 * Elementor Widgets Core Orbit Fox Module.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Elementor_Widgets_OBFX_Module
 */

define( 'OBFX_MODULE_URL', __FILE__ );

use \ThemeIsle\ContentForms\Form_Manager;

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Elementor_Widgets_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 * @codeCoverageIgnore
 */
class Elementor_Widgets_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * Elementor_Widgets_OBFX_Module  constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name        = __( 'Page builder widgets', 'themeisle-companion' );
		$this->description = __( 'Adds widgets to the most popular builders: Elementor or Beaver. More to come!', 'themeisle-companion' );

		if ( self::should_add_placeholders() ) {
			$this->description .=
				'<div class="neve-pro-notice">
					<p>' .
						sprintf( /* translators: %1$s is the features, %2$s is the plugin name  */
							__( 'You can get access to %1$s, including Instagram integration, display conditions and more in %2$s.', 'themeisle-companion' ),
							sprintf(
								'<b>%1$s</b>',
								__( '10+ more Elementor and Gutenberg widgets', 'themeisle-companion' )
							),
							'<b>Neve PRO</b>'
						) .
					'</p>
					<a class="notice-cta" target="_blank" href="' . esc_url_raw(
						add_query_arg(
							array(
								'utm_source'   => 'wpadmin',
								'utm_medium'   => 'orbitfox',
								'utm_content'  => 'neve',
								'utm_campaign' => 'elementorwidgets',
							),
							'https://themeisle.com/themes/neve/pricing/'
						)
					) . '">
						<b>' . __( 'Learn more', 'themeisle-companion' ) . '</b>
					</a>
				</div>';
		}

		$this->active_default = true;
	}

	/**
	 * Determine if module should be loaded.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return bool
	 */
	public function enable_module() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		return is_plugin_active( 'elementor/elementor.php' );
	}

	/**
	 * The loading logic for the module.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function load() {}

	/**
	 * Check for current license.
	 *
	 * @return bool
	 */
	private static function has_valid_addons() {
		if ( ! defined( 'NEVE_PRO_BASEFILE' ) ) {
			return false;
		}

		$option_name = basename( dirname( NEVE_PRO_BASEFILE ) );
		$option_name = str_replace( '-', '_', strtolower( trim( $option_name ) ) );
		$status      = get_option( $option_name . '_license_data' );

		if ( ! $status ) {
			return false;
		}

		if ( ! isset( $status->license ) ) {
			return false;
		}

		if ( $status->license === 'not_active' || $status->license === 'invalid' ) {
			return false;
		}

		return true;
	}

	/**
	 * Decide if placeholders are needed.
	 *
	 * @return bool
	 */
	public static function should_add_placeholders() {
		return wp_get_theme()->get( 'Name' ) === 'Neve' && ! self::has_valid_addons();
	}

	/**
	 * Method to define hooks needed.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return mixed | array
	 */
	public function hooks() {
		$this->loader->add_action( 'init', $this, 'load_content_forms' );
		$this->loader->add_action( 'plugins_loaded', $this, 'load_elementor_extra_widgets' );
		$this->loader->add_filter( 'elementor/editor/localize_settings', $this, 'localization_filter', PHP_INT_MAX );
		$this->loader->add_action( 'elementor/editor/before_enqueue_scripts', $this, 'load_fa_styles' );
		if ( self::should_add_placeholders() ) {
			$this->loader->add_action( 'elementor/editor/after_enqueue_scripts', $this, 'enqueue_editor_scripts' );
			$this->loader->add_action( 'elementor/editor/after_enqueue_styles', $this, 'enqueue_editor_styles' );
		}
	}

	/**
	 * Filter the localization settings.
	 *
	 * @param array $config Config array.
	 *
	 * @return array
	 */
	public function localization_filter( $config ) {
		if ( ! array_key_exists( 'initial_document', $config ) ) {
			return $config;
		}
		if ( ! array_key_exists( 'panel', $config['initial_document'] ) ) {
			return $config;
		}
		if ( ! array_key_exists( 'elements_categories', $config['initial_document']['panel'] ) ) {
			return $config;
		}
		if ( ! array_key_exists( 'pro-elements', $config['initial_document']['panel']['elements_categories'] ) ) {
			return $config;
		}
		if ( ! array_key_exists( 'obfx-elementor-widgets', $config['initial_document']['panel']['elements_categories'] ) ) {
			return $config;
		}

		$elements_categories = $config['initial_document']['panel']['elements_categories'];
		$obfx_cat            = array( 'obfx-elementor-widgets' => $elements_categories['obfx-elementor-widgets'] );

		unset( $elements_categories['obfx-elementor-widgets'] );

		$config['initial_document']['panel']['elements_categories'] = $this->insert_before_element( $elements_categories, 'pro-elements', $obfx_cat );

		$elements_categories = $config['initial_document']['panel']['elements_categories'];
		if ( $this->should_add_placeholders() && array_key_exists( 'obfx-elementor-widgets-pro', $elements_categories ) ) {
			$placeholders_cat = array( 'obfx-elementor-widgets-pro' => $elements_categories['obfx-elementor-widgets-pro'] );
			unset( $elements_categories['obfx-elementor-widgets-pro'] );
			$config['initial_document']['panel']['elements_categories'] = $this->insert_before_element( $elements_categories, 'pro-elements', $placeholders_cat );
		}
		return $config;
	}

	/**
	 * Insert element after specific key.
	 *
	 * @param array  $array Destination array.
	 * @param string $key   Where to insert.
	 * @param array  $el    What to insert.
	 * @return array
	 */
	private function insert_before_element( $array, $key, $el ) {
		$keys  = array_keys( $array );
		$index = array_search( $key, $keys );
		$pos   = false === $index ? count( $array ) : $index;

		return array_merge( array_slice( $array, 0, $pos ), $el, array_slice( $array, $pos ) );
	}

	/**
	 * Load font awesome scripts in admin.
	 */
	public function load_fa_styles() {
		wp_enqueue_style( 'font-awesome-5-all', ELEMENTOR_ASSETS_URL . '/lib/font-awesome/css/all.min.css', array(), '2.10.9' );
	}

	/**
	 * Load required scripts in admin.
	 */
	public function enqueue_editor_scripts() {
		wp_add_inline_script(
			'elementor-editor',
			'           
            $e.routes.on(\'run\', function(){
                setTimeout(
                    function() {
                        jQuery( \'#elementor-panel-category-obfx-elementor-widgets-pro .elementor-element-wrapper\' ).on( \'click mousedown drop\', function(e) {
                            e.preventDefault();
                        });
                        
                        jQuery( \'#elementor-panel-category-obfx-elementor-widgets-pro .elementor-element-wrapper\' ).on( \'click drop\', function(e) {
                            window.open( \'' . tsdk_utmify( 'https://themeisle.com/themes/neve/upgrade/', 'elementorwidgetsdrag', 'orbitfox' ) . '\',\'_blank\');
                        });
                    }, 1000
                );
            });
        ' 
		);
	}

	/**
	 * Load required styles in admin.
	 */
	public function enqueue_editor_styles() {
		wp_add_inline_style(
			'elementor-editor', 
			'
            #elementor-panel-category-obfx-elementor-widgets-pro .elementor-element-wrapper {
                position:relative;
            }
            
            #elementor-panel-category-obfx-elementor-widgets-pro .elementor-element {
                cursor: pointer;
            }
            #elementor-panel-category-obfx-elementor-widgets-pro .elementor-element-wrapper:before{
                content: \'\e96f\';
                position: absolute;
                top: 5px;
                right: 5px;
                color: #64666A;
                font-family: eicons;
                z-index: 1;
            }
        ' 
		);
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
		return array();
	}

	/**
	 * Method to define the options fields for the module
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function options() {
		$options = array();

		return $options;
	}

	/**
	 * If the content-forms library is available we should make the forms available for elementor
	 */
	public function load_content_forms() {
		if ( ! class_exists( '\ThemeIsle\ContentForms\Form_Manager' ) ) {
			return false;
		}
		$content_forms = new Form_Manager();
		$content_forms->instance();

		return true;
	}

	/**
	 * Call the ElementorExtraWidgets Library which will register its own actions.
	 */
	public function load_elementor_extra_widgets() {
		if ( class_exists( '\ThemeIsle\ElementorExtraWidgets' ) ) {
			\ThemeIsle\ElementorExtraWidgets::instance();
		}
	}

}
