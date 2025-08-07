<?php

/**
 * WordPress unit test plugin.
 *
 * @package     Orbit_Fox
 * @subpackage  Orbit_Fox/tests
 * @copyright   Copyright (c) 2017, Bogdan Preda
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since 3.0.0
 */
class Test_Module_Post_Duplicator extends WP_UnitTestCase {

  /**
   * @var Post_Duplicator_OBFX_Module
   */
  private $module;

  /**
   * @var Orbit_Fox_Loader
   */
  private $loader;

  public function tearDown(): void {
    parent::tearDown();

    // Clean up any posts created during tests
    $posts = get_posts(array(
      'post_type' => 'any',
      'numberposts' => -1,
      'post_status' => 'any',
    ));

    foreach ($posts as $post) {
      wp_delete_post($post->ID, true);
    }
    $this->reset_post_id_increment();
  }

  public function setUp(): void {
    parent::setUp();


    // Create a mock loader
    $this->loader = $this->createMock('Orbit_Fox_Loader');

    // Create the module instance
    $this->module = new Post_Duplicator_OBFX_Module();
    $this->module->register_loader($this->loader);
  }

  /**
   * Test duplicate link is added for posts
   */
  public function test_add_duplicate_link_for_posts() {
    $post = $this->factory->post->create_and_get(array(
      'post_title' => 'Test Post',
      'post_status' => 'publish',
    ));

    $actions = array(
      'edit' => '<a href="edit.php">Edit</a>',
      'trash' => '<a href="trash.php">Trash</a>',
    );

    $result = $this->module->add_duplicate_link($actions, $post);

    $this->assertArrayHasKey('duplicate', $result);
    $this->assertStringContainsString('Clone', $result['duplicate']);
    $this->assertStringContainsString('post=' . $post->ID, $result['duplicate']);
  }

  /**
   * Test duplicate link is added for pages
   */
  public function test_add_duplicate_link_for_pages() {
    $page = $this->factory->post->create_and_get(array(
      'post_title' => 'Test Page',
      'post_type' => 'page',
      'post_status' => 'publish',
    ));

    wp_set_current_user(1);

    $actions = array(
      'edit' => '<a href="edit.php">Edit</a>',
      'trash' => '<a href="trash.php">Trash</a>',
    );

    $result = $this->module->add_duplicate_link($actions, $page);

    $this->assertArrayHasKey('duplicate', $result);
    $this->assertStringContainsString('Clone', $result['duplicate']);
    $this->assertStringContainsString('post=' . $page->ID, $result['duplicate']);
  }

  /**
   * Test basic post duplication functionality
   */
  public function test_duplicate_post_basic() {
    $original_post = $this->factory->post->create_and_get(array(
      'post_title' => 'Original Post',
      'post_content' => 'Original content',
      'post_status' => 'publish',
    ));

    $reflection = new ReflectionClass($this->module);
    $method = $reflection->getMethod('duplicate_post');
    $method->setAccessible(true);

    $duplicate_id = $method->invoke($this->module, $original_post);

    $this->assertNotFalse($duplicate_id);
    $this->assertIsInt($duplicate_id);

    $duplicate_post = get_post($duplicate_id);
    $this->assertNotNull($duplicate_post);
    $this->assertEquals('Original Post - Copy', $duplicate_post->post_title);
    $this->assertEquals('draft', $duplicate_post->post_status);
  }

  private function reset_post_id_increment($start_id = 1) {
    global $wpdb;
    $wpdb->query("ALTER TABLE {$wpdb->posts} AUTO_INCREMENT = {$start_id}");
  }
}
