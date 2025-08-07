<?php
/**
 * WordPress unit test plugin.
 *
 * @package     Orbit_Fox
 * @subpackage  Orbit_Fox/tests
 * @copyright   Copyright (c) 2017, Bogdan Preda
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since 1.0.0
 */
class Test_Module_Svg_Uploads extends WP_UnitTestCase {

	protected $module;
	protected $test_svg_file;
	protected $test_svgz_file;
	protected $test_malicious_svg_file;

	public function tearDown(): void {
		// Clean up test files
		if ( file_exists( $this->test_svg_file ) ) {
			unlink( $this->test_svg_file );
		}
		if ( file_exists( $this->test_svgz_file ) ) {
			unlink( $this->test_svgz_file );
		}
		if ( file_exists( $this->test_malicious_svg_file ) ) {
			unlink( $this->test_malicious_svg_file );
		}
	}

	public function setUp(): void {
		parent::setUp();
		
		// Create test SVG files
		$this->test_svg_file = sys_get_temp_dir() . '/test.svg';
		$this->test_svgz_file = sys_get_temp_dir() . '/test.svgz';
		$this->test_malicious_svg_file = sys_get_temp_dir() . '/malicious.svg';
		
		// Create a simple test SVG
		$simple_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"><rect width="100" height="100" fill="red"/></svg>';
		file_put_contents( $this->test_svg_file, $simple_svg );
		
		// Create a compressed SVG
		$compressed_svg = gzencode( $simple_svg );
		file_put_contents( $this->test_svgz_file, $compressed_svg );
		
		// Create a malicious SVG with script tag
		$malicious_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"><script>alert("xss")</script><rect width="100" height="100" fill="red"/></svg>';
		file_put_contents( $this->test_malicious_svg_file, $malicious_svg );
		
		// Initialize the module
		$this->module = new Svg_Uploads_OBFX_Module();
	}

	/**
	 * Test adding SVG mime types.
	 *
	 * @covers Svg_Uploads_OBFX_Module::add_svg_mime_type
	 */
	public function test_add_svg_mime_type() {
		$mimes = array( 'jpg' => 'image/jpeg' );
		$result = $this->module->add_svg_mime_type( $mimes );
		
		$this->assertArrayHasKey( 'svg', $result );
		$this->assertArrayHasKey( 'svgz', $result );
		$this->assertEquals( 'image/svg+xml', $result['svg'] );
		$this->assertEquals( 'image/svg+xml', $result['svgz'] );
		$this->assertArrayHasKey( 'jpg', $result );
	}

	/**
	 * Test file type and extension adjustment.
	 *
	 * @covers Svg_Uploads_OBFX_Module::adjust_filetype_extension
	 */
	public function test_adjust_filetype_extension() {
		$filetype_data = array( 'ext' => '', 'type' => '' );
		$mimes = array();
		
		// Test SVG file
		$result = $this->module->adjust_filetype_extension( $filetype_data, '', 'test.svg', $mimes );
		$this->assertEquals( 'svg', $result['ext'] );
		$this->assertEquals( 'image/svg+xml', $result['type'] );
		
		// Test SVGZ file
		$result = $this->module->adjust_filetype_extension( $filetype_data, '', 'test.svgz', $mimes );
		$this->assertEquals( 'svgz', $result['ext'] );
		$this->assertEquals( 'image/svg+xml', $result['type'] );
		
		// Test non-SVG file
		$result = $this->module->adjust_filetype_extension( $filetype_data, '', 'test.jpg', $mimes );
		$this->assertEquals( '', $result['ext'] );
		$this->assertEquals( '', $result['type'] );
	}

	/**
	 * Test gzipped detection.
	 *
	 * @covers Svg_Uploads_OBFX_Module::is_gzipped
	 */
	public function test_is_gzipped() {
		$normal_svg = '<svg></svg>';
		$gzipped_svg = gzencode( $normal_svg );
		
		$this->assertFalse( $this->module->is_gzipped( $normal_svg ) );
		$this->assertTrue( $this->module->is_gzipped( $gzipped_svg ) );
	}

	/**
	 * Test SVG sanitization.
	 *
	 * @covers Svg_Uploads_OBFX_Module::sanitize
	 */
	public function test_sanitize() {
		// Use reflection to access private methods
		$reflection = new ReflectionClass( $this->module );
		
		// Load the sanitizer first
		$load_sanitizer_method = $reflection->getMethod( 'load_sanitizer' );
		$load_sanitizer_method->setAccessible( true );
		$load_sanitizer_method->invoke( $this->module );
		
		// Now test the sanitize method
		$sanitize_method = $reflection->getMethod( 'sanitize' );
		$sanitize_method->setAccessible( true );
		
		// Test normal SVG
		$result = $sanitize_method->invoke( $this->module, $this->test_svg_file );
		$this->assertTrue( $result );
		
		// Test compressed SVG
		$result = $sanitize_method->invoke( $this->module, $this->test_svgz_file );
		$this->assertTrue( $result );
		
		// Test malicious SVG (should be sanitized)
		$result = $sanitize_method->invoke( $this->module, $this->test_malicious_svg_file );
		$this->assertTrue( $result );
		
		// Verify malicious content was removed
		$sanitized_content = file_get_contents( $this->test_malicious_svg_file );
		$this->assertStringNotContainsString( '<script>', $sanitized_content );
		$this->assertStringNotContainsString( 'alert("xss")', $sanitized_content );
		
		// Test error handling - non-existent file
		$result = $sanitize_method->invoke( $this->module, 'non_existent_file.svg' );
		$this->assertFalse( $result );
		
		// Test error handling - empty file
		$empty_file = sys_get_temp_dir() . '/empty.svg';
		file_put_contents( $empty_file, '' );
		
		$result = $sanitize_method->invoke( $this->module, $empty_file );
		$this->assertFalse( $result );
		
		// Clean up
		unlink( $empty_file );
	}

	/**
	 * Test sanitize uploaded file.
	 *
	 * @covers Svg_Uploads_OBFX_Module::sanitize_uploaded_file
	 */
	public function test_sanitize_uploaded_file() {
    // Use reflection to access private methods
		$reflection = new ReflectionClass( $this->module );
		
		// Load the sanitizer first
		$load_sanitizer_method = $reflection->getMethod( 'load_sanitizer' );
		$load_sanitizer_method->setAccessible( true );
		$load_sanitizer_method->invoke( $this->module );
		
		// Test SVG file
		$file = array(
			'tmp_name' => $this->test_svg_file,
			'error' => 0,
		);
		
		$result = $this->module->sanitize_uploaded_file( $file );
		$this->assertEquals( 0, $result['error'] );
		
		// Test non-SVG file
		$non_svg_file = array(
			'tmp_name' => sys_get_temp_dir() . '/test.jpg',
			'error' => 0,
		);
		file_put_contents( $non_svg_file['tmp_name'], 'fake jpg content' );
		
		$result = $this->module->sanitize_uploaded_file( $non_svg_file );
		$this->assertEquals( 0, $result['error'] );
		
		// Clean up
		unlink( $non_svg_file['tmp_name'] );
	}

	/**
	 * Test SVG dimensions extraction.
	 *
	 * @covers Svg_Uploads_OBFX_Module::get_svg_dimensions
	 */
	public function test_svg_dimensions() {
		// Create test SVG with width and height attributes
		$svg_with_dimensions = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="150"><rect width="200" height="150" fill="blue"/></svg>';
		$temp_file = sys_get_temp_dir() . '/test_dimensions.svg';
		file_put_contents( $temp_file, $svg_with_dimensions );
		
		// Use reflection to access private method
		$reflection = new ReflectionClass( $this->module );
		$method = $reflection->getMethod( 'get_svg_dimensions' );
		$method->setAccessible( true );
		
		$dimensions = $method->invoke( $this->module, $temp_file );
		$this->assertEquals( 200, $dimensions['width'] );
		$this->assertEquals( 150, $dimensions['height'] );
		
		// Test SVG with viewBox
		$svg_with_viewbox = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 250"><rect width="300" height="250" fill="green"/></svg>';
		file_put_contents( $temp_file, $svg_with_viewbox );
		
		$dimensions = $method->invoke( $this->module, $temp_file );
		$this->assertEquals( 300, $dimensions['width'] );
		$this->assertEquals( 250, $dimensions['height'] );
		
		// Test SVG with percentage dimensions
		$svg_with_percentages = '<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><rect width="100" height="100" fill="yellow"/></svg>';
		file_put_contents( $temp_file, $svg_with_percentages );
		
		$dimensions = $method->invoke( $this->module, $temp_file );
		$this->assertEquals( 0, $dimensions['width'] );
		$this->assertEquals( 0, $dimensions['height'] );
		
		// Test invalid SVG file
		$dimensions = $method->invoke( $this->module, 'non_existent_file.svg' );
		$this->assertEquals( 0, $dimensions['width'] );
		$this->assertEquals( 0, $dimensions['height'] );
		
		// Clean up
		unlink( $temp_file );
	}

	/**
	 * Test generate attachment metadata.
	 *
	 * @covers Svg_Uploads_OBFX_Module::generate_attachment_metadata
	 */
	public function test_generate_attachment_metadata() {
		// Create a mock attachment
		$attachment_id = $this->factory->attachment->create( array(
			'file' => $this->test_svg_file,
			'post_mime_type' => 'image/svg+xml',
		) );
		
		$metadata = array();
		$result = $this->module->generate_attachment_metadata( $metadata, $attachment_id );
		
		$this->assertArrayHasKey( 'width', $result );
		$this->assertArrayHasKey( 'height', $result );
		$this->assertEquals( 100, $result['width'] );
		$this->assertEquals( 100, $result['height'] );
		
		// Test non-SVG attachment
		$non_svg_attachment_id = $this->factory->attachment->create( array(
			'post_mime_type' => 'image/jpeg',
		) );
		
		$metadata = array( 'width' => 500, 'height' => 300 );
		$result = $this->module->generate_attachment_metadata( $metadata, $non_svg_attachment_id );
		$this->assertEquals( $metadata, $result );
	}

	/**
	 * Test prepare attachment for JS.
	 *
	 * @covers Svg_Uploads_OBFX_Module::prepare_attachment_for_js
	 */
	public function test_prepare_attachment_for_js() {
		// Create a mock attachment
		$attachment = $this->factory->post->create_and_get( array(
			'post_type' => 'attachment',
			'post_mime_type' => 'image/svg+xml',
		) );


    function filter_get_attached_file( $file, $attachment_id ) {
      return sys_get_temp_dir() . '/test.svg';
    }

    add_filter( 'get_attached_file', 'filter_get_attached_file', 10, 2 );

		
		$response = array(
			'mime' => 'image/svg+xml',
			'url' => 'http://example.com/test.svg',
			'sizes' => array(),
		);
		
		$meta = array();
		$result = $this->module->prepare_attachment_for_js( $response, $attachment, $meta );
		
		$this->assertArrayHasKey( 'sizes', $result );
		$this->assertArrayHasKey( 'full', $result['sizes'] );
		$this->assertEquals( 100, $result['sizes']['full']['width'] );
		$this->assertEquals( 100, $result['sizes']['full']['height'] );
		
		// Test non-SVG response
		$non_svg_response = array(
			'mime' => 'image/jpeg',
			'url' => 'http://example.com/test.jpg',
		);
		
		$result = $this->module->prepare_attachment_for_js( $non_svg_response, $attachment, $meta );
		$this->assertEquals( $non_svg_response, $result );
		
		// Test response with existing sizes
		$response_with_sizes = array(
			'mime' => 'image/svg+xml',
			'url' => 'http://example.com/test.svg',
			'sizes' => array( 'thumbnail' => array() ),
		);
		
		$result = $this->module->prepare_attachment_for_js( $response_with_sizes, $attachment, $meta );
		$this->assertEquals( $response_with_sizes, $result );
	}

	/**
	 * Test sanitizer loading.
	 *
	 * @covers Svg_Uploads_OBFX_Module::load_sanitizer
	 */
	public function test_load_sanitizer() {
		// Use reflection to access private method
		$reflection = new ReflectionClass( $this->module );
		$method = $reflection->getMethod( 'load_sanitizer' );
		$method->setAccessible( true );
		
		// Test that the method runs without errors
		$this->assertNull( $method->invoke( $this->module ) );
		
		// Test that sanitizer property is set
		$property = $reflection->getProperty( 'sanitizer' );
		$property->setAccessible( true );
		$this->assertNotNull( $property->getValue( $this->module ) );
	}

	/**
	 * Test error handling for sanitization failures.
	 */
	public function test_sanitization_error_handling() {
		// Use reflection to access private methods
		$reflection = new ReflectionClass( $this->module );
		
		// Load the sanitizer first
		$load_sanitizer_method = $reflection->getMethod( 'load_sanitizer' );
		$load_sanitizer_method->setAccessible( true );
		$load_sanitizer_method->invoke( $this->module );
		
		// Now test the sanitize method
		$sanitize_method = $reflection->getMethod( 'sanitize' );
		$sanitize_method->setAccessible( true );
		
		// Test with non-existent file
		$result = $sanitize_method->invoke( $this->module, 'non_existent_file.svg' );
		$this->assertFalse( $result );
		
		// Test with empty file
		$empty_file = sys_get_temp_dir() . '/empty.svg';
		file_put_contents( $empty_file, '' );
		
		$result = $sanitize_method->invoke( $this->module, $empty_file );
		$this->assertFalse( $result );
		
		// Clean up
		unlink( $empty_file );
	}

	/**
	 * Test file upload error handling.
	 */
	public function test_upload_error_handling() {
		// Test with non-existent temp file
		$file = array(
			'tmp_name' => 'non_existent_file.svg',
			'error' => 0,
		);
		
		$result = $this->module->sanitize_uploaded_file( $file );
		$this->assertEquals( 0, $result['error'] ); // Should return original file unchanged
	}
}
