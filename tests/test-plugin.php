<?php
/**
 * Wordpress unit test plugin.
 *
 * @package     Orbit_Fox
 * @subpackage  Orbit_Fox/tests
 * @copyright   Copyright (c) 2017, Bogdan Preda
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since 1.0.0
 */
class Test_Orbit_Fox extends WP_UnitTestCase {

	protected $obfx;
	protected $obfx_admin;
	protected $obfx_public;

	protected $plugin_name;
	protected $plugin_version;

	public function setUp() {
		$this->obfx = new Orbit_Fox();

        $this->plugin_name = $this->obfx->get_plugin_name();
        $this->plugin_version = $this->obfx->get_version();

        $this->obfx_admin = new Orbit_Fox_Admin( $this->plugin_name, $this->plugin_version );
	}

	/**
	 * Test to check PHPUNIT is working.
	 *
	 * @covers Orbit_Fox
	 * @covers Orbit_Fox_Admin
	 * @covers Orbit_Fox_Loader
	 * @covers Orbit_Fox_i18n
	 */
	public function test_default() {
	    $this->obfx->get_loader();
	    $this->obfx->run();

        $this->obfx_admin->menu_pages();
        $this->obfx_admin->load_modules();
        ob_start();
        $this->obfx_admin->page_temp_render();
        $output = ob_get_clean();
        $this->obfx_admin->enqueue_styles();
        $this->obfx_admin->enqueue_scripts();

		$this->assertTrue( true );
	}

    /**
     * Test for global settings.
     *
     * @covers Orbit_Fox_Global_Settings
     */
	public function test_global_settings() {
        $global_settings = new Orbit_Fox_Global_Settings();
        $this->assertTrue( is_array( $global_settings->instance()->get_modules() ) );
        $this->assertTrue( ! empty( $global_settings->instance()->get_modules() ) );
    }

    /**
     * Test for Orbit Fox Modules.
     *
     * @covers Orbit_Fox_Module_Factory
     */
    public function test_module() {
        $modules_to_load = array(
            'test',
            'new-module'
        );

        $module_factory = new Orbit_Fox_Module_Factory();

        echo PHP_EOL;
        foreach ( $modules_to_load as $module_name ) {
            $module = $module_factory::build( $module_name );
            $this->assertTrue( $module->enable_module() );
        }
	}
}
