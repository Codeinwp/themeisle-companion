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
	 * Holds the module slug.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     string $slug The module slug.
	 */
	protected $slug;

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
	 * Has an instance of the Orbit_Fox_Model class used for interacting with DB data.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     Orbit_Fox_Model $model A instance of Orbit_Fox_Model.
	 */
	protected $model;

	/**
	 * Stores the curent version of Orbit fox for use during the enqueue.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     string $version The current version of Orbit Fox.
	 */
	protected $version;

	/**
	 * Orbit_Fox_Module_Abstract constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
	    $this->slug = str_replace( '_', '-',strtolower( str_replace( '_OBFX_Module', '', get_class( $this ) ) ) );
	}

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
	 * Registers the loader.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param Orbit_Fox_Model $model The loader class used to register action hooks and filters.
	 */
	public function register_model( Orbit_Fox_Model $model ) {
		$this->model = $model;
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
	 * Method to define actions and filters needed for the module.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public abstract function hooks();

	/**
	 * Utility method to register `add_action` and `add_filter` for
	 * defined actions and filters returned by the `hooks()` method.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	final public function register_hooks() {
	    $hooks = $this->hooks();
	    if ( isset( $hooks['actions'] ) && ! empty( $hooks['actions'] ) ) {
			foreach ( $hooks['actions'] as $hook => $method ) {
					$this->loader->add_action( $hook, $this, $method );
			}
		}
		if ( isset( $hooks['filters'] ) && ! empty( $hooks['filters'] ) ) {
			foreach ( $hooks['actions'] as $hook => $method ) {
				$this->loader->add_filter( $hook, $this, $method );
			}
		}

	}

	/**
	 * Method to check if module status is active.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return bool
	 */
	final public function is_active() {
	    return $this->model->get_is_module_active( $this->slug );
	}

	/**
	 * Method to retrieve from model the module status for
	 * the provided key.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param   string $key Key to look for.
	 * @return bool
	 */
	final public function get_status( $key ) {
		return $this->model->get_module_status( $this->slug, $key );
	}

	/**
	 * Method to update in model the module status for
	 * the provided key value pair.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param   string $key Key to update.
	 * @param   string $value The new value.
	 * @return mixed
	 */
	final public function set_status( $key, $value ) {
		return $this->model->set_module_status( $this->slug, $key, $value );
	}

	/**
	 * Method to retrieve an option value from model.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param   string $key The option key to retrieve.
	 * @return bool
	 */
	final public function get_option( $key ) {
	    $default_options = $this->get_options_defaults();
		$db_option = $this->model->get_module_option( $this->slug, $key );
		$value = $db_option;
		if ( $db_option === false ) {
			$value = $default_options[ $key ];
		}
		return $value;
	}

	/**
	 * Method to update an option key value pair.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param   string $key The key name.
	 * @param   string $value The new value.
	 * @return mixed
	 */
	final public function set_option( $key, $value ) {
		return $this->model->set_module_option( $this->slug, $key, $value );
	}

	/**
	 * Method to update a set of options.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param   array $options An associative array of options to be
	 *                         updated. Eg. ( 'key' => 'new_value' ).
	 * @return mixed
	 */
	final public function set_options( $options ) {
	    return $this->model->set_module_options( $this->slug, $options );
	}

	/**
	 * Method to define the default model value for options, based on
	 * the options array if not set DB.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	final public function get_options_defaults() {
	    $options = $this->options();
	    $defaults = array();
	    foreach ( $options as $opt ) {
	        if ( ! isset( $opt['default'] ) ) {
				$opt['default'] = '';
			}
			$defaults[ $opt['name'] ] = $opt['default'];
		}
		return $defaults;
	}

	/**
	 * Method to retrieve the options for the module.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	final public function get_options() {
	    $model_options = $this->options();
		$options = array();
		$index = 0;
		foreach ( $model_options as $opt ) {
			$options[ $index ] = $opt;
			$options[ $index ]['value'] = $this->get_option( $opt['name'] );
			$index++;
		}
		return $options;
	}

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
		$module_dir = $this->slug;
		if ( ! empty( $enqueue ) ) {
			if ( isset( $enqueue['css'] ) && ! empty( $enqueue['css'] ) ) {
				$order = 0;
				foreach ( $enqueue['css'] as $file_name => $dependencies ) {
					if ( $dependencies == false ) {
						$dependencies = array();
					}
					wp_enqueue_style(
						'obfx-module-css-' . str_replace( ' ', '-', strtolower( $this->name ) ) . '-' . $order,
						plugin_dir_url( $this->get_dir() ) . $module_dir . '/css/' . $file_name . '.css',
						$dependencies,
						$this->version,
						'all'
					);
					$order++;
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
		$module_dir = $this->slug;
		if ( ! empty( $enqueue ) ) {
			if ( isset( $enqueue['js'] ) && ! empty( $enqueue['js'] ) ) {
			    $order = 0;
				foreach ( $enqueue['js'] as $file_name => $dependencies ) {
					if ( $dependencies == false ) {
						$dependencies = array();
					}
					wp_enqueue_script(
						'obfx-module-js-' . str_replace( ' ', '-', strtolower( $this->name ) ) . '-' . $order,
						plugin_dir_url( $this->get_dir() ) . $module_dir . '/js/' . $file_name . '.js',
						$dependencies,
						$this->version,
						false
					);
					$order++;
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
		$module_dir = $this->slug;
		if ( ! empty( $enqueue ) ) {
			if ( isset( $enqueue['css'] ) && ! empty( $enqueue['css'] ) ) {
				$order = 0;
				foreach ( $enqueue['css'] as $file_name => $dependencies ) {
					if ( $dependencies == false ) {
						$dependencies = array();
					}
					wp_enqueue_style(
						'obfx-module-pub-css-' . str_replace( ' ', '-', strtolower( $this->name ) ) . '-' . $order,
						plugin_dir_url( $this->get_dir() ) . $module_dir . '/css/' . $file_name . '.css',
						$dependencies,
						$this->version,
						'all'
					);
					$order++;
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
		$module_dir = $this->slug;
		if ( ! empty( $enqueue ) ) {
			if ( isset( $enqueue['js'] ) && ! empty( $enqueue['js'] ) ) {
				$order = 0;
				foreach ( $enqueue['js'] as $file_name => $dependencies ) {
					if ( $dependencies == false ) {
						$dependencies = array();
					}
					wp_enqueue_script(
						'obfx-module-pub-js-' . str_replace( ' ', '-', strtolower( $this->name ) ) . '-' . $order,
						plugin_dir_url( $this->get_dir() ) . $module_dir . '/js/' . $file_name . '.js',
						$dependencies,
						$this->version,
						false
					);
					$order++;
				}
			}
		}
	}

	/**
	 * Utility method to render a view from module.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @param   string $view_name The view name w/o the `-tpl.php` part.
	 * @param   array  $args An array of arguments to be passed to the view.
	 * @return string
	 */
	protected function render_view( $view_name, $args = array() ) {
		ob_start();
		$file = $this->get_dir() . '/views/' . $view_name . '-tpl.php';
		if ( ! empty( $args ) ) {
			foreach ( $args as $obfx_rh_name => $obfx_rh_value ) {
				$$obfx_rh_name = $obfx_rh_value;
			}
		}
		if ( file_exists( $file ) ) {
			include $file;
		}
		return ob_get_clean();
	}
}
