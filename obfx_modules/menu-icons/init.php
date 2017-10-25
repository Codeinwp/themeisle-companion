<?php
/**
 * The module for menu icons.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Menu_Icons_OBFX_Module
 */

/**
 * The class for menu icons.
 *
 * @package    Menu_Icons_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 * @codeCoverageIgnore
 */
class Menu_Icons_OBFX_Module extends Orbit_Fox_Module_Abstract {


	/**
	 * Menu_Icons_OBFX_Module constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name        = __( 'Menu Icons', 'themeisle-companion' );
		$this->description = __( 'Module to define menu icons for navigation.', 'themeisle-companion' );
	}


	/**
	 * Determine if module should be loaded.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return bool	
	 */
	public function enable_module() {
		return true;
	}

	/**
	 * The loading logic for the module.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function load() {
	}

	/**
	 * Method to define hooks needed.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function hooks() {
		$this->loader->add_action( 'wp_update_nav_menu_item', $this, 'save_fields', 10, 3 );
		$this->loader->add_filter( 'wp_edit_nav_menu_walker', $this, 'custom_walker', 99 );
		$this->loader->add_filter( 'wp_setup_nav_menu_item', $this, 'show_menu', 10, 1 );

	}

	public function show_menu( $menu ) {
		$icon	= get_post_meta( $menu->ID, 'obfx_menu_icon', true );
		if ( ! empty( $icon ) ) {
			$menu->icon = $icon;
			if ( ! is_admin() ) {
				// usually, icons are of the format fa-x or dashicons-x and when displayed they are displayed with classes 'fa fa-x' or 'dashicons dashicons-x'.
				// so let's determine the prefix class.
				$array			= explode( '-', $icon );
				$prefix			= reset( $array );
				$prefix			= apply_filters( 'obfx_menu_icons_icon_class', $prefix, $icon );
				$menu->title	= sprintf( '<i class="obfx-menu-icon %s %s"></i>%s', $prefix, $icon, $menu->title );
			}
		}
		return $menu;
	}

	public function custom_walker( $walker ) {
		if ( ! class_exists( 'Menu_Icons_OBFX_Walker' ) ) {
			require_once $this->get_dir() . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'class-nav-menu-edit-walker.php';
		}
		$walker = 'Menu_Icons_OBFX_Walker';
		return $walker;
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
		return array(
			'css' => array(
				'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' => false,
			),
		);
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
		$current_screen = get_current_screen();

		if ( ! isset( $current_screen->id ) ) {
			return array();
		}
		if ( $current_screen->id != 'nav-menus' ) {
			return array();
		}

		$this->localized	= array(
			'admin'		=> array(
				'icons'	=> apply_filters( 'obfx_menu_icons_icon_list', array() ),
			),
		);

		return array(
			'css' => array(
				'vendor/bootstrap.min' => false,
				'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' => array( 'vendor/bootstrap.min' ),
				'vendor/fontawesome-iconpicker.min' => array( 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' ),
				'admin' => array( 'vendor/fontawesome-iconpicker.min' ),
			),
			'js' => array(
				'vendor/bootstrap.min' => array( 'jquery' ),
				'vendor/fontawesome-iconpicker.min' => array( 'vendor/bootstrap.min' ),
				'admin' => array( 'vendor/fontawesome-iconpicker.min' ),
			),
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
		return array();
	}


	/**
	 * Save menu item's icon.
	 *
	 * @access  public
	 *
	 * @param int   $menu_id         Nav menu ID.
	 * @param int   $menu_item_db_id Menu item ID.
	 * @param array $menu_item_args  Menu item data.
	 */
	public static function save_fields( $menu_id, $menu_item_db_id, $menu_item_args ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		$screen = get_current_screen();
		if ( ! $screen instanceof WP_Screen || 'nav-menus' !== $screen->id ) {
			return;
		}

		check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );

		if ( isset( $_POST['menu-item-icon'][ $menu_item_db_id ] ) ) {
			update_post_meta( $menu_item_db_id, 'obfx_menu_icon', $_POST['menu-item-icon'][ $menu_item_db_id ] );
		}

	}
}