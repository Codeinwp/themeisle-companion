<?php

/**
 * Policy notice module tests.
 *
 * @package     Orbit_Fox
 * @subpackage  Orbit_Fox/tests
 */
class Test_Module_Policy_Notice extends WP_UnitTestCase {

  /**
   * @var Policy_Notice_OBFX_Module
   */
  private $module;

  /**
   * @var int
   */
  private $module_page;

  /**
   * @var int
   */
  private $other_page;

  public function setUp(): void {
    parent::setUp();

    $this->module = new Policy_Notice_OBFX_Module();
    $this->module->register_model( new Orbit_Fox_Model() );
    $this->module->set_option( 'notice_link_label', 'View Policy' );

    $this->module_page = self::factory()->post->create( array( 'post_type' => 'page', 'post_title' => 'Cookie Policy' ) );
    $this->other_page  = self::factory()->post->create( array( 'post_type' => 'page', 'post_title' => 'Other Page' ) );
  }

  public function tearDown(): void {
    unset( $GLOBALS['post'] );
    parent::tearDown();
  }

  private function render_notice(): string {
    ob_start();
    $this->module->display_cookie_notice();
    return (string) ob_get_clean();
  }

  private function assert_policy_href( string $expected, string $output ): void {
    $this->assertStringContainsString( 'href="' . $expected . '"', $output );
  }

  public function test_policy_link_uses_module_page_when_core_page_is_unset(): void {
    update_option( 'wp_page_for_privacy_policy', 0 );
    $this->module->set_option( 'policy_page', (string) $this->module_page );
    $GLOBALS['post'] = get_post( $this->other_page );

    $output = $this->render_notice();

    $this->assert_policy_href( get_permalink( $this->module_page ), $output );
    $this->assertStringNotContainsString( get_permalink( $this->other_page ), $output );
  }

  public function test_policy_link_prefers_module_page_over_core_page(): void {
    update_option( 'wp_page_for_privacy_policy', $this->other_page );
    $this->module->set_option( 'policy_page', (string) $this->module_page );

    $output = $this->render_notice();

    $this->assert_policy_href( get_permalink( $this->module_page ), $output );
    $this->assertStringNotContainsString( get_permalink( $this->other_page ), $output );
  }

  public function test_default_policy_option_follows_core_privacy_page(): void {
    update_option( 'wp_page_for_privacy_policy', $this->other_page );
    $this->module->set_option( 'policy_page', '0' );

    $this->assert_policy_href( get_permalink( $this->other_page ), $this->render_notice() );
  }

  public function test_policy_link_is_hash_when_no_page_is_set(): void {
    update_option( 'wp_page_for_privacy_policy', 0 );
    $this->module->set_option( 'policy_page', '0' );
    $GLOBALS['post'] = get_post( $this->other_page );

    $this->assert_policy_href( '#', $this->render_notice() );
  }
}
