<?php
/**
 * Registers the Orbit Fox abilities with the WordPress Abilities API.
 *
 * @link       https://themeisle.com
 * @since      3.0.10
 *
 * @package    Orbit_Fox
 * @subpackage Orbit_Fox/app
 */

/**
 * Class Orbit_Fox_Abilities
 *
 * Thin wrappers over the module dashboard: list the available modules and
 * change their state or settings, the same way the dashboard REST routes do.
 *
 * @package    Orbit_Fox
 * @subpackage Orbit_Fox/app
 * @author     Themeisle <friends@themeisle.com>
 */
class Orbit_Fox_Abilities {

	/**
	 * The ability category slug.
	 */
	const CATEGORY = 'orbit-fox';

	/**
	 * The maximum number of settings accepted in one call.
	 */
	const MAX_SETTINGS = 100;

	/**
	 * The admin class, used for the activate/deactivate triggers.
	 *
	 * @since   3.0.10
	 * @access  private
	 * @var     Orbit_Fox_Admin $admin The admin instance.
	 */
	private $admin;

	/**
	 * The option types that hold a value which can be edited from the dashboard.
	 *
	 * @since   3.0.10
	 * @access  private
	 * @var     array $editable_types The editable types.
	 */
	private $editable_types = array( 'toggle', 'checkbox', 'radio', 'select', 'text', 'number', 'color' );

	/**
	 * Orbit_Fox_Abilities constructor.
	 *
	 * @param Orbit_Fox_Admin $admin The admin instance.
	 *
	 * @since   3.0.10
	 * @access  public
	 */
	public function __construct( Orbit_Fox_Admin $admin ) {
		$this->admin = $admin;
	}

	/**
	 * Register the ability category.
	 *
	 * @since   3.0.10
	 * @access  public
	 */
	public function register_category() {
		if ( ! function_exists( 'wp_register_ability_category' ) ) {
			return;
		}

		wp_register_ability_category(
			self::CATEGORY,
			array(
				'label'       => __( 'Orbit Fox', 'themeisle-companion' ),
				'description' => __( 'Abilities for managing the Orbit Fox modules.', 'themeisle-companion' ),
			)
		);
	}

	/**
	 * Register the abilities.
	 *
	 * @since   3.0.10
	 * @access  public
	 */
	public function register_abilities() {
		if ( ! function_exists( 'wp_register_ability' ) ) {
			return;
		}

		$module_schema = $this->get_module_schema();

		wp_register_ability(
			'orbit-fox/list-modules',
			array(
				'label'               => __( 'List Orbit Fox modules', 'themeisle-companion' ),
				'description'         => __( 'Lists the Orbit Fox modules available on this site, whether each one is active, and the settings that can be changed with their current values. Pass module_id to get a single module.', 'themeisle-companion' ),
				'category'            => self::CATEGORY,
				'input_schema'        => array(
					'type'                 => 'object',
					'properties'           => array(
						'module_id' => array(
							'type'        => 'string',
							'description' => __( 'Optional module slug, e.g. social-sharing. When set, only that module is returned.', 'themeisle-companion' ),
						),
					),
					'additionalProperties' => false,
				),
				'output_schema'       => array(
					'type'       => 'object',
					'properties' => array(
						'modules' => array(
							'type'  => 'array',
							'items' => $module_schema,
						),
						'total'   => array( 'type' => 'integer' ),
					),
				),
				'execute_callback'    => array( $this, 'list_modules' ),
				'permission_callback' => array( $this, 'check_permission' ),
				'meta'                => array(
					'annotations'  => array(
						'readonly'    => true,
						'destructive' => false,
						'idempotent'  => true,
					),
					'show_in_rest' => true,
				),
			)
		);

		wp_register_ability(
			'orbit-fox/configure-module',
			array(
				'label'               => __( 'Configure Orbit Fox module', 'themeisle-companion' ),
				'description'         => __( 'Activates or deactivates an Orbit Fox module and/or changes its settings. Use orbit-fox/list-modules first to discover the module slugs, setting names, types and allowed values. Set dry_run to validate without saving.', 'themeisle-companion' ),
				'category'            => self::CATEGORY,
				'input_schema'        => array(
					'type'                 => 'object',
					'properties'           => array(
						'module_id' => array(
							'type'        => 'string',
							'description' => __( 'The module slug, e.g. social-sharing.', 'themeisle-companion' ),
						),
						'active'    => array(
							'type'        => 'boolean',
							'description' => __( 'Optional. True to activate the module, false to deactivate it.', 'themeisle-companion' ),
						),
						'settings'  => array(
							'type'        => 'array',
							'description' => __( 'Optional. The settings to change. Settings that are not listed keep their current value.', 'themeisle-companion' ),
							'maxItems'    => self::MAX_SETTINGS,
							'items'       => array(
								'type'                 => 'object',
								'properties'           => array(
									'name'  => array(
										'type'        => 'string',
										'description' => __( 'The setting name as returned by orbit-fox/list-modules.', 'themeisle-companion' ),
									),
									'value' => array(
										'type'        => 'string',
										'description' => __( 'The new value, as a string. Use "1" or "0" for toggle and checkbox settings, one of the listed choice values for radio and select settings, a number for number settings and a hex or rgb(a) color for color settings.', 'themeisle-companion' ),
									),
								),
								'required'             => array( 'name', 'value' ),
								'additionalProperties' => false,
							),
						),
						'dry_run'   => array(
							'type'        => 'boolean',
							'description' => __( 'When true, the input is validated and the resulting changes are returned, but nothing is saved.', 'themeisle-companion' ),
							'default'     => false,
						),
					),
					'required'             => array( 'module_id' ),
					'additionalProperties' => false,
				),
				'output_schema'       => array(
					'type'       => 'object',
					'properties' => array(
						'dry_run' => array( 'type' => 'boolean' ),
						'changed' => array(
							'type'        => 'boolean',
							'description' => __( 'Whether the stored data changed (or would change, on a dry run).', 'themeisle-companion' ),
						),
						'changes' => array(
							'type'  => 'array',
							'items' => array(
								'type'       => 'object',
								'properties' => array(
									'field' => array( 'type' => 'string' ),
									'from'  => array( 'type' => 'string' ),
									'to'    => array( 'type' => 'string' ),
								),
							),
						),
						'module'  => $module_schema,
					),
				),
				'execute_callback'    => array( $this, 'configure_module' ),
				'permission_callback' => array( $this, 'check_permission' ),
				'meta'                => array(
					'annotations'  => array(
						'readonly'    => false,
						'destructive' => false,
						'idempotent'  => true,
					),
					'show_in_rest' => true,
				),
			)
		);
	}

	/**
	 * Permission check. Mirrors the dashboard page and its REST routes.
	 *
	 * @see Orbit_Fox_Admin::init_dashboard_routes()
	 * @see Orbit_Fox_Admin::menu_pages()
	 *
	 * @since   3.0.10
	 * @access  public
	 * @return bool
	 */
	public function check_permission() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Execute callback for orbit-fox/list-modules.
	 *
	 * @param array $input The ability input.
	 *
	 * @since   3.0.10
	 * @access  public
	 * @return array|WP_Error
	 */
	public function list_modules( $input = array() ) {
		$input     = is_array( $input ) ? $input : array();
		$module_id = isset( $input['module_id'] ) ? sanitize_key( $input['module_id'] ) : '';
		$modules   = $this->get_available_modules();

		if ( $module_id !== '' ) {
			if ( ! isset( $modules[ $module_id ] ) ) {
				return $this->module_not_found( $module_id );
			}
			$modules = array( $module_id => $modules[ $module_id ] );
		}

		$data = array();
		foreach ( $modules as $module ) {
			$data[] = $this->format_module( $module );
		}

		return array(
			'modules' => $data,
			'total'   => count( $data ),
		);
	}

	/**
	 * Execute callback for orbit-fox/configure-module.
	 *
	 * @param array $input The ability input.
	 *
	 * @since   3.0.10
	 * @access  public
	 * @return array|WP_Error
	 */
	public function configure_module( $input = array() ) {
		$input     = is_array( $input ) ? $input : array();
		$module_id = isset( $input['module_id'] ) ? sanitize_key( $input['module_id'] ) : '';
		$dry_run   = ! empty( $input['dry_run'] );
		$modules   = $this->get_available_modules();

		if ( $module_id === '' || ! isset( $modules[ $module_id ] ) ) {
			return $this->module_not_found( $module_id );
		}

		$module       = $modules[ $module_id ];
		$has_active   = array_key_exists( 'active', $input ) && $input['active'] !== null;
		$has_settings = isset( $input['settings'] ) && is_array( $input['settings'] ) && ! empty( $input['settings'] );

		if ( ! $has_active && ! $has_settings ) {
			return new WP_Error(
				'orbit_fox_nothing_to_update',
				__( 'Provide "active", "settings" or both.', 'themeisle-companion' ),
				array( 'status' => 400 )
			);
		}

		$changes = array();

		$active = null;
		if ( $has_active ) {
			$active = (bool) $input['active'];
			if ( $module->auto === true ) {
				return new WP_Error(
					'orbit_fox_module_always_active',
					__( 'This module is loaded automatically and cannot be activated or deactivated.', 'themeisle-companion' ),
					array( 'status' => 400 )
				);
			}
			$current_active = (bool) $module->get_is_active();
			if ( $current_active !== $active ) {
				$changes[] = array(
					'field' => 'active',
					'from'  => $current_active ? '1' : '0',
					'to'    => $active ? '1' : '0',
				);
			} else {
				$active = null;
			}
		}

		$new_options      = array();
		$settings_changed = false;
		if ( $has_settings ) {
			if ( count( $input['settings'] ) > self::MAX_SETTINGS ) {
				return new WP_Error(
					'orbit_fox_too_many_settings',
					/* translators: %d is the maximum number of settings */
					sprintf( __( 'At most %d settings can be changed in one call.', 'themeisle-companion' ), self::MAX_SETTINGS ),
					array( 'status' => 400 )
				);
			}

			$fields  = $this->get_editable_fields( $module );
			$current = array();
			foreach ( $fields as $name => $field ) {
				$current[ $name ] = $module->get_option( $name );
			}

			// The dashboard sends the stored settings of the module plus the edited ones.
			$stored      = get_option( 'obfx_data' );
			$new_options = isset( $stored['module_settings'][ $module_id ] ) && is_array( $stored['module_settings'][ $module_id ] ) ? $stored['module_settings'][ $module_id ] : array();
			$edited      = array();
			$errors      = array();
			foreach ( $input['settings'] as $setting ) {
				$name = is_array( $setting ) && isset( $setting['name'] ) && is_scalar( $setting['name'] ) ? (string) $setting['name'] : '';
				if ( $name === '' || ! isset( $fields[ $name ] ) ) {
					$errors[] = array(
						'name'    => $name,
						'code'    => 'orbit_fox_unknown_setting',
						'message' => __( 'This setting does not exist for the module or cannot be edited.', 'themeisle-companion' ),
					);
					continue;
				}
				if ( ! isset( $setting['value'] ) || ! is_scalar( $setting['value'] ) ) {
					$errors[] = array(
						'name'    => $name,
						'code'    => 'orbit_fox_invalid_value',
						'message' => __( 'A scalar value is required.', 'themeisle-companion' ),
					);
					continue;
				}
				$value = $this->sanitize_value( $fields[ $name ], $setting['value'] );
				if ( is_wp_error( $value ) ) {
					$errors[] = array(
						'name'    => $name,
						'code'    => $value->get_error_code(),
						'message' => $value->get_error_message(),
					);
					continue;
				}
				$edited[ $name ] = $value;
			}

			if ( ! empty( $errors ) ) {
				return new WP_Error(
					'orbit_fox_invalid_settings',
					__( 'Some settings are not valid. Nothing was saved.', 'themeisle-companion' ),
					array(
						'status' => 400,
						'errors' => $errors,
					)
				);
			}

			foreach ( $edited as $name => $value ) {
				$new_options[ $name ] = $value;
				if ( $this->to_string( $current[ $name ] ) !== $this->to_string( $value ) ) {
					$settings_changed = true;
					$changes[]        = array(
						'field' => $name,
						'from'  => $this->to_string( $current[ $name ] ),
						'to'    => $this->to_string( $value ),
					);
				}
			}
		}

		if ( ! $dry_run && ! empty( $changes ) ) {
			// Same calls as Orbit_Fox_Admin::update_module_callback().
			if ( $active !== null ) {
				$module->set_status( 'active', $active );
				$this->admin->trigger_activate_deactivate( $active, $module );
			}
			if ( $settings_changed ) {
				$module->set_options( $new_options );
			}
		}

		$formatted = $this->format_module( $module );
		if ( $dry_run ) {
			// Reflect the would-be state without persisting it.
			foreach ( $changes as $change ) {
				if ( $change['field'] === 'active' ) {
					$formatted['active'] = $change['to'] === '1';
					continue;
				}
				foreach ( $formatted['settings'] as $index => $field ) {
					if ( $field['name'] === $change['field'] ) {
						$formatted['settings'][ $index ]['value'] = $change['to'];
					}
				}
			}
		}

		return array(
			'dry_run' => $dry_run,
			'changed' => ! empty( $changes ),
			'changes' => $changes,
			'module'  => $formatted,
		);
	}

	/**
	 * Get the modules shown in the dashboard, i.e. the ones that are enabled
	 * for the current environment.
	 *
	 * @see Orbit_Fox_Admin::enqueue_scripts()
	 *
	 * @since   3.0.10
	 * @access  private
	 * @return Orbit_Fox_Module_Abstract[]
	 */
	private function get_available_modules() {
		$modules = Orbit_Fox_Global_Settings::instance()->module_objects;
		if ( ! is_array( $modules ) ) {
			return array();
		}

		return array_filter(
			$modules,
			function ( $module ) {
				return $module instanceof Orbit_Fox_Module_Abstract && $module->enable_module();
			}
		);
	}

	/**
	 * Get the editable option definitions of a module, keyed by name.
	 *
	 * @param Orbit_Fox_Module_Abstract $module The module.
	 *
	 * @since   3.0.10
	 * @access  private
	 * @return array
	 */
	private function get_editable_fields( Orbit_Fox_Module_Abstract $module ) {
		$fields  = array();
		$options = $module->options();
		if ( ! is_array( $options ) ) {
			return $fields;
		}
		foreach ( $options as $option ) {
			if ( ! is_array( $option ) || empty( $option['name'] ) || empty( $option['type'] ) ) {
				continue;
			}
			if ( ! in_array( $option['type'], $this->editable_types, true ) ) {
				continue;
			}
			$fields[ (string) $option['name'] ] = $option;
		}

		return $fields;
	}

	/**
	 * Validate and sanitize a value against its option definition. The stored
	 * format matches what the dashboard controls save.
	 *
	 * @param array  $field The option definition.
	 * @param scalar $value The raw value.
	 *
	 * @since   3.0.10
	 * @access  private
	 * @return mixed|WP_Error
	 */
	private function sanitize_value( $field, $value ) {
		if ( is_bool( $value ) ) {
			$value = $value ? '1' : '0';
		}
		$value = trim( (string) $value );

		switch ( $field['type'] ) {
			case 'toggle':
			case 'checkbox':
				if ( in_array( strtolower( $value ), array( '1', 'true', 'on', 'yes' ), true ) ) {
					return '1';
				}
				if ( in_array( strtolower( $value ), array( '0', 'false', 'off', 'no', '' ), true ) ) {
					return '0';
				}

				return new WP_Error( 'orbit_fox_invalid_value', __( 'Use "1" or "0".', 'themeisle-companion' ) );
			case 'radio':
			case 'select':
				$choices = isset( $field['options'] ) && is_array( $field['options'] ) ? $field['options'] : array();
				foreach ( array_keys( $choices ) as $choice ) {
					if ( (string) $choice === $value ) {
						// The dashboard select control stores numeric choices as integers.
						return ( $field['type'] === 'select' && is_int( $choice ) ) ? $choice : (string) $choice;
					}
				}

				return new WP_Error( 'orbit_fox_invalid_value', __( 'The value is not one of the allowed choices.', 'themeisle-companion' ) );
			case 'number':
				if ( ! is_numeric( $value ) ) {
					return new WP_Error( 'orbit_fox_invalid_value', __( 'A numeric value is required.', 'themeisle-companion' ) );
				}

				return $value;
			case 'color':
				if ( $value === '' ) {
					return '';
				}
				$hex = sanitize_hex_color( $value );
				if ( ! empty( $hex ) ) {
					return $hex;
				}
				if ( preg_match( '/^(rgb|hsl)a?\(\s*[0-9.]+%?(\s*[, ]\s*[0-9.]+%?){2}(\s*[,\/]\s*[0-9.]+%?)?\s*\)$/i', $value ) ) {
					return $value;
				}

				return new WP_Error( 'orbit_fox_invalid_value', __( 'A hex, rgb(a) or hsl(a) color is required.', 'themeisle-companion' ) );
			default:
				return sanitize_text_field( $value );
		}
	}

	/**
	 * Format a module for output.
	 *
	 * @param Orbit_Fox_Module_Abstract $module The module.
	 *
	 * @since   3.0.10
	 * @access  private
	 * @return array
	 */
	private function format_module( Orbit_Fox_Module_Abstract $module ) {
		$settings = array();
		foreach ( $this->get_editable_fields( $module ) as $name => $field ) {
			$label = '';
			foreach ( array( 'title', 'label' ) as $key ) {
				if ( $label === '' && ! empty( $field[ $key ] ) && is_string( $field[ $key ] ) ) {
					$label = wp_strip_all_tags( $field[ $key ] );
				}
			}

			$choices = array();
			if ( in_array( $field['type'], array( 'radio', 'select' ), true ) && isset( $field['options'] ) && is_array( $field['options'] ) ) {
				foreach ( $field['options'] as $choice => $choice_label ) {
					$choices[] = array(
						'value' => (string) $choice,
						'label' => wp_strip_all_tags( (string) $choice_label ),
					);
				}
			}

			$settings[] = array(
				'name'    => $name,
				'type'    => $field['type'],
				'label'   => $label,
				'value'   => $this->to_string( $module->get_option( $name ) ),
				'default' => $this->to_string( isset( $field['default'] ) ? $field['default'] : '' ),
				'choices' => $choices,
			);
		}

		$notices = array();
		foreach ( (array) $module->get_notices() as $notice ) {
			if ( is_array( $notice ) && ! empty( $notice['message'] ) ) {
				$notices[] = wp_strip_all_tags( (string) $notice['message'] );
			}
		}

		return array(
			'module_id'         => $module->get_slug(),
			'name'              => (string) $module->name,
			'description'       => wp_strip_all_tags( (string) $module->description ),
			'active'            => (bool) $module->get_is_active(),
			'always_active'     => $module->auto === true,
			'beta'              => (bool) $module->beta,
			'documentation_url' => (string) $module->documentation_url,
			'notices'           => $notices,
			'settings'          => $settings,
		);
	}

	/**
	 * Cast a stored value to a string.
	 *
	 * @param mixed $value The value.
	 *
	 * @since   3.0.10
	 * @access  private
	 * @return string
	 */
	private function to_string( $value ) {
		if ( is_bool( $value ) ) {
			return $value ? '1' : '0';
		}

		return is_scalar( $value ) ? (string) $value : '';
	}

	/**
	 * The module not found error.
	 *
	 * @param string $module_id The requested module.
	 *
	 * @since   3.0.10
	 * @access  private
	 * @return WP_Error
	 */
	private function module_not_found( $module_id ) {
		return new WP_Error(
			'orbit_fox_module_not_found',
			/* translators: %s is the module slug */
			sprintf( __( 'Module "%s" was not found or is not available on this site.', 'themeisle-companion' ), $module_id ),
			array( 'status' => 404 )
		);
	}

	/**
	 * The JSON schema of a module in the output.
	 *
	 * @since   3.0.10
	 * @access  private
	 * @return array
	 */
	private function get_module_schema() {
		return array(
			'type'       => 'object',
			'properties' => array(
				'module_id'         => array( 'type' => 'string' ),
				'name'              => array( 'type' => 'string' ),
				'description'       => array( 'type' => 'string' ),
				'active'            => array( 'type' => 'boolean' ),
				'always_active'     => array( 'type' => 'boolean' ),
				'beta'              => array( 'type' => 'boolean' ),
				'documentation_url' => array( 'type' => 'string' ),
				'notices'           => array(
					'type'  => 'array',
					'items' => array( 'type' => 'string' ),
				),
				'settings'          => array(
					'type'  => 'array',
					'items' => array(
						'type'       => 'object',
						'properties' => array(
							'name'    => array( 'type' => 'string' ),
							'type'    => array( 'type' => 'string' ),
							'label'   => array( 'type' => 'string' ),
							'value'   => array( 'type' => 'string' ),
							'default' => array( 'type' => 'string' ),
							'choices' => array(
								'type'  => 'array',
								'items' => array(
									'type'       => 'object',
									'properties' => array(
										'value' => array( 'type' => 'string' ),
										'label' => array( 'type' => 'string' ),
									),
								),
							),
						),
					),
				),
			),
		);
	}
}
