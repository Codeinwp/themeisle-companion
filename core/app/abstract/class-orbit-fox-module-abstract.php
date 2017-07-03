<?php
/**
 * The abstract class for Orbit Fox Modules.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Orbit_Fox
 * @subpackage Orbit_Fox/app/abstract
 */

/**
 * The class that defines the required methods and variables needed by a OBFX_Module.
 *
 * @package    Orbit_Fox
 * @subpackage Orbit_Fox/app/abstract
 * @author     Themeisle <friends@themeisle.com>
 */
abstract class Orbit_Fox_Module_Abstract {

	/**
	 * Holds the name of the module
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     string $name The name of the module.
	 */
	public $name;

	/**
	 * Holds the description of the module
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     string $description The description of the module.
	 */
	public $description;

	/**
	 * Flags if module should autoload.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     bool $auto The flag for automatic activation.
	 */
	public $auto = false;

	/**
	 * Has an instance of the Orbit_Fox_Loader class used for adding actions and filters.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     Orbit_Fox_Loader $loader A instance of Orbit_Fox_Loader.
	 */
	protected $loader;

	/**
	 * Stores the curent version of Orbit fox for use during the enqueue.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     string $version The current version of Orbit Fox.
	 */
	protected $version;

	/**
	 * Method to return path to child class in a Reflective Way.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @return string
	 */
	protected function get_dir() {
		$reflector = new ReflectionClass( get_class( $this ) );
		return dirname( $reflector->getFileName() );
	}

	/**
	 * Registers the loader.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param Orbit_Fox_Loader $loader The loader class used to register action hooks and filters.
	 */
	public function register_loader( Orbit_Fox_Loader $loader ) {
	    $this->loader = $loader;
	}

	/**
	 * Method to determine if the module is enabled or not.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return bool
	 */
	public abstract function enable_module();

	/**
	 * The method for the module load logic.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return mixed
	 */
	public abstract function load();

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the front end part.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public abstract function public_enqueue();

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the admin part.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public abstract function admin_enqueue();

	/**
	 * Method to define the options fields for the module
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public abstract function options();

	/**
	 * Adds the hooks for amdin and public enqueue.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param   string $version The version for the files.
	 */
	final public function enqueue( $version ) {
		$this->version = $version;
		$this->loader->add_action( 'obfx_admin_enqueue_styles', $this, 'set_admin_styles' );
		$this->loader->add_action( 'obfx_admin_enqueue_scripts', $this, 'set_admin_scripts' );

		$this->loader->add_action( 'obfx_public_enqueue_styles', $this, 'set_public_styles' );
		$this->loader->add_action( 'obfx_public_enqueue_scripts', $this, 'set_public_scripts' );
	}

	/**
	 * Sets the styles for admin from the module array.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function set_admin_styles() {
		$enqueue = $this->admin_enqueue();
		if ( ! empty( $enqueue ) ) {
			if ( isset( $enqueue['css'] ) && ! empty( $enqueue['css'] ) ) {
				foreach ( $enqueue['css'] as $file_name => $dependencies ) {
					if ( $dependencies == false ) {
						$dependencies = array();
					}
					wp_enqueue_style(
						'obfx-module-css-' . str_replace( ' ', '-', strtolower( $this->name ) ),
						plugin_dir_url( $this->get_dir() ) . 'css/' . $file_name . '.css',
						$dependencies,
						$this->version,
						'all'
					);
				}
			}
		}
	}

	/**
	 * Sets the scripts for admin from the module array.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function set_admin_scripts() {
		$enqueue = $this->admin_enqueue();
		if ( ! empty( $enqueue ) ) {
			if ( isset( $enqueue['js'] ) && ! empty( $enqueue['js'] ) ) {
				foreach ( $enqueue['js'] as $file_name => $dependencies ) {
					if ( $dependencies == false ) {
						$dependencies = array();
					}
					wp_enqueue_script(
						'obfx-module-js-' . str_replace( ' ', '-', strtolower( $this->name ) ),
						plugin_dir_url( $this->get_dir() ) . 'js/' . $file_name . '.js',
						$dependencies,
						$this->version,
						false
					);
				}
			}
		}
	}

	/**
	 * Sets the styles for public from the module array.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function set_public_styles() {
		$enqueue = $this->public_enqueue();
		if ( ! empty( $enqueue ) ) {
			if ( isset( $enqueue['css'] ) && ! empty( $enqueue['css'] ) ) {
				foreach ( $enqueue['css'] as $file_name => $dependencies ) {
					if ( $dependencies == false ) {
						$dependencies = array();
					}
					wp_enqueue_style(
						'obfx-module-css-' . str_replace( ' ', '-', strtolower( $this->name ) ),
						plugin_dir_url( $this->get_dir() ) . 'css/' . $file_name . '.css',
						$dependencies,
						$this->version,
						'all'
					);
				}
			}
		}
	}

	/**
	 * Sets the scripts for public from the module array.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function set_public_scripts() {
		$enqueue = $this->public_enqueue();
		if ( ! empty( $enqueue ) ) {
			if ( isset( $enqueue['js'] ) && ! empty( $enqueue['js'] ) ) {
				foreach ( $enqueue['js'] as $file_name => $dependencies ) {
					if ( $dependencies == false ) {
						$dependencies = array();
					}
					wp_enqueue_script(
						'obfx-module-js-' . str_replace( ' ', '-', strtolower( $this->name ) ),
						plugin_dir_url( $this->get_dir() ) . 'js/' . $file_name . '.js',
						$dependencies,
						$this->version,
						false
					);
				}
			}
		}
	}
}
