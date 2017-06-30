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
}
