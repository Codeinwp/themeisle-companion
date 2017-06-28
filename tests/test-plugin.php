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

    public function setUp() {
        $this->obfx = new Orbit_Fox();
        $this->obfx_admin = new Orbit_Fox_Admin( 'orbit-fox', '1.0.0' );
        new Orbit_Fox_Loader();
        new Orbit_Fox_i18n();
        new Orbit_Fox_Activator();
        new Orbit_Fox_Deactivator();
        new Orbit_Fox_Public( 'orbit-fox', '1.0.0' );
    }

	/**
	 * Test to check PHPUNIT is working.
     *
     * @covers Orbit_Fox
     * @covers Orbit_Fox_Admin
     * @covers Orbit_Fox_Public
     * @covers Orbit_Fox_Loader
     * @covers Orbit_Fox_Activator
     * @covers Orbit_Fox_Deactivator
     * @covers Orbit_Fox_i18n
     */
	public function test_default() {
	    $this->obfx->get_plugin_name();
	    $this->obfx->get_loader();
	    $this->obfx->get_version();
		$this->assertTrue( true );
	}
}
