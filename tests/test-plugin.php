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

    public function tearDown() {
        Orbit_Fox_Global_Settings::distroy_instance();

        $obfx_model = new Orbit_Fox_Model();
        $obfx_model->distroy_model();
    }

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
        $instance = $global_settings->instance();
        $this->assertTrue( is_array( $global_settings->instance()->get_modules() ) );
        $this->assertTrue( ! empty( $global_settings->instance()->get_modules() ) );
        $global_settings->distroy_instance();
    }

    /**
     * Test for Orbit Fox Modules.
     *
     * @covers Orbit_Fox_Module_Factory
     */
    public function test_module() {
        Autoloader::set_plugins_path( plugin_dir_path( __DIR__ ) );
        $modules_to_load = array(
            'test',
        );

        $module_factory = new Orbit_Fox_Module_Factory();

        echo PHP_EOL;
        foreach ( $modules_to_load as $module_name ) {
            $module = $module_factory::build( $module_name );
            $this->assertTrue( $module->enable_module() );
            $module->register_loader( $this->obfx->get_loader() );
            $module->enqueue( $this->plugin_version );
            $module->set_admin_styles();
            $module->set_admin_scripts();
            $module->set_public_styles();
            $module->set_public_scripts();
        }
	}

	/**
     * Test for Orbit Fox Render Helper.
     *
     * @covers Orbit_Fox_Render_Helper
     */
	public function test_render_helper() {
	    $rdh = new Orbit_Fox_Render_Helper();

	    $rdh->get_partial( 'empty', array( 'title' => 'Test Title' ) );
	    $rdh->get_view( 'modules', array( 'no_modules' => true ) );
	    $rdh->render_option( array( 'type' => 'text', 'value' => 'Value', 'default' => 'Bla' ) );
	    $rdh->render_option( array( 'type' => 'textarea', 'value' => 'Value', 'default' => 'Bla' ) );
	    $rdh->render_option( array( 'type' => 'select', 'value' => '1', 'default' => '0', 'options' => array( '0' => 'label 1', '1' => 'label 2' ) ) );
	    $rdh->render_option( array( 'type' => 'radio', 'value' => '1', 'default' => '0', 'options' => array( '0' => 'label 1', '1' => 'label 2' ) ) );
	    $rdh->render_option( array( 'type' => 'checkbox', 'value' => '1', 'default' => '0', 'options' => array( '0' => 'label 1', '1' => 'label 2' ) ) );
	    $rdh->render_option( array( 'type' => 'toggle', 'value' => '0' ) );
	    $rdh->render_option( array( 'type' => 'unknown' ) );

        $this->invokeMethod( $rdh, 'sanitize_option', array( 'type' => 'text' ) );
    }

    public function test_model() {
	    $obfx_model = new Orbit_Fox_Model();

        $global_settings = new Orbit_Fox_Global_Settings();

        $modules = $global_settings::$instance->module_objects;

        $obfx_model->register_modules_data( $modules );

        var_dump( $obfx_model->get() );

        foreach ( $modules as $module ) {
            $module->register_model( $obfx_model );
            var_dump( $module->get_status( 'active' ) );
            var_dump( $module->get_option( 'test_checkbox_name' ) );

            $module->set_status( 'active', true );
            var_dump( $module->get_status( 'active' ) );
            $module->set_option( 'test_checkbox_name', '2' );
        }

	    var_dump( $obfx_model->get() );
    }


    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod( &$object, $methodName, array $parameters = array() ) {
        $reflection = new \ReflectionClass( get_class( $object ) );
        $method = $reflection->getMethod( $methodName );
        $method->setAccessible( true );

        return $method->invokeArgs( $object, $parameters );
    }
}
