<?php

/**
 * SVG Uploads Core Orbit Fox Module.
 *
 * @link       https://themeisle.com
 * @since      3.0.0
 *
 * @package    Svg_Uploads_OBFX_Module
 */

use \enshrined\svgSanitize\Sanitizer;

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Svg_Uploads_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 * @codeCoverageIgnore
 */
class Svg_Uploads_OBFX_Module extends Orbit_Fox_Module_Abstract {
	/**
	 * The sanitizer instance.
	 *
	 * @var Sanitizer
	 */
	protected $sanitizer = null;

	/**
	 * Setup module strings
	 *
	 * @access  public
	 */
	public function set_module_strings() {
		$this->name        = __( 'SVG Support', 'themeisle-companion' );
		$this->description = __( 'Enable SVG file uploads and media library support. By default, WordPress doesn\'t allow uploading SVG files for security reasons. This module safely enables SVG support with proper sanitization.', 'themeisle-companion' );
		$this->documentation_url = 'https://docs.themeisle.com/article/951-orbit-fox-documentation#svg-support';
		$this->notices     = array(
			array(
				'message' => __( 'Only enable this if you trust all users who can upload files to your site. SVG files can contain malicious code if not properly sanitized.', 'themeisle-companion' ),
				'type'    => 'warning',
			),
		);
	}

	/**
	 * Determine if module should be loaded.
	 * 
	 * @return bool
	 */
	public function enable_module() {
		return true;
	}

	public function load() {
	}

	public function options() {
		return array();
	}

	public function admin_enqueue() {
		return array();
	}

	public function public_enqueue() {
		return array();
	}

	/**
	 * Method to define hooks needed.
	 *
	 * @since   3.0.0
	 * @access  public
	 * @return mixed | array
	 */
	public function hooks() {
		$this->load_sanitizer();

		$this->loader->add_filter( 'upload_mimes', $this, 'add_svg_mime_type' );
		$this->loader->add_filter( 'wp_prepare_attachment_for_js', $this, 'prepare_attachment_for_js', 10, 3 );
		$this->loader->add_filter( 'wp_handle_upload_prefilter', $this, 'sanitize_uploaded_file' );
		$this->loader->add_filter( 'wp_check_filetype_and_ext', $this, 'adjust_filetype_extension', 100, 4 );
		$this->loader->add_filter( 'wp_generate_attachment_metadata', $this, 'generate_attachment_metadata', 10, 2 );
	}

	/**
	 * Load the SVG sanitizer and its required classes.
	 * 
	 * @return void
	 */
	private function load_sanitizer() {
		$this->sanitizer = new Sanitizer();
		$this->sanitizer->removeXMLTag( true );
		$this->sanitizer->minify( true );
	}

	/**
	 * Sanitize uploaded file.
	 * 
	 * @param array $file The file array.
	 * 
	 * @return array The sanitized file array.
	 */
	public function sanitize_uploaded_file( $file ) {
		if ( ! is_file( $file['tmp_name'] ) ) {
			return $file;
		}

		$file_info  = wp_check_filetype( basename( $file['tmp_name'] ) );
		$file_type  = $file_info['type'];
		$mime_types = array( 'image/svg+xml', 'image/svg' );

		if ( ! in_array( $file_type, $mime_types, true ) ) {
			return $file;
		}

		if ( ! current_user_can( 'upload_files' ) ) {
			$file['error'] = __( 'You are not allowed to upload files.', 'themeisle-companion' );
			return $file;
		}

		if ( ! $this->sanitize( $file['tmp_name'] ) ) {
			$file['error'] = __( 'This SVG can not be sanitized!', 'themeisle-companion' );
		}
		return $file;
	}

	/**
	 * Sanitize SVG file.
	 * 
	 * Sanitizes the SVG, removes XML tags and minifies the code and replaces the original file.
	 * 
	 * @param string $file The SVG file path.
	 * 
	 * @return bool True if the SVG file is sanitized, false otherwise.
	 */
	private function sanitize( $file ) {
		if ( ! is_file( $file ) ) {
			return false;
		}

		$svg_code      = file_get_contents( $file );
		$is_compressed = $this->is_gzipped( $svg_code );

		if ( $is_compressed && ( ! function_exists( 'gzdecode' ) || ! function_exists( 'gzencode' ) ) ) {
			return false;
		}

		if ( $is_compressed ) {
			$svg_code = gzdecode( $svg_code );

			if ( $svg_code === false ) {
				return false;
			}
		}

		$clean_svg_code = $this->sanitizer->sanitize( $svg_code );

		if ( ! $clean_svg_code ) {
			return false;
		}

		if ( $is_compressed ) {
			$clean_svg_code = gzencode( $clean_svg_code );
		}

		file_put_contents( $file, $clean_svg_code );

		return true;
	}

	/**
	 * Check if the SVG is gzipped.
	 * 
	 * @param string $svg_code The SVG code.
	 * 
	 * @return bool True if the SVG is gzipped, false otherwise.
	 */
	public function is_gzipped( $svg_code ) {
		// phpcs:ignore Generic.Strings.UnnecessaryStringConcat.Found
		return 0 === strpos( $svg_code, "\x1f" . "\x8b" . "\x08" );
	}

	/**
	 * Add SVG mime type to the allowed mime types.
	 *
	 * @param array $mimes The mime types array.
	 * 
	 * @return array The modified mime types array.
	 */
	public function add_svg_mime_type( $mimes ) {
		$mimes['svg']  = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';

		return $mimes;
	}

	/**
	 * Check file type and extension.
	 *
	 * @param array $filetype_ext_data The file type and extension data. 
	 * @param string $file The file path.
	 * @param string $filename The file name.
	 * @param array $mimes The mime types array.
	 * 
	 * @return array The modified file type and extension data.
	 */
	public function adjust_filetype_extension( $filetype_ext_data, $file, $filename, $mimes ) {
		if ( substr( $filename, -4 ) === '.svg' ) {
			$filetype_ext_data['ext']  = 'svg';
			$filetype_ext_data['type'] = 'image/svg+xml';

			return $filetype_ext_data;
		}

		if ( substr( $filename, -5 ) === '.svgz' ) {
			$filetype_ext_data['ext']  = 'svgz';
			$filetype_ext_data['type'] = 'image/svg+xml';

			return $filetype_ext_data;
		}

		return $filetype_ext_data;
	}

	/**
	 * Generate attachment metadata.
	 * 
	 * @param array $metadata The attachment metadata.
	 * @param int $attachment_id The attachment ID.
	 * 
	 * @return array The modified attachment metadata.
	 */
	public function generate_attachment_metadata( $metadata, $attachment_id ) {
		$mime_type = get_post_mime_type( $attachment_id );

		if ( $mime_type !== 'image/svg+xml' ) {
			return $metadata;
		}

		$svg_path           = get_attached_file( $attachment_id );
		$dimensions         = $this->get_svg_dimensions( $svg_path );
		$metadata['width']  = $dimensions['width'];
		$metadata['height'] = $dimensions['height'];

		return $metadata;
	}

	/**
	 * Prepare attachment for JS.
	 * 
	 * @param array $response The response array.
	 * @param \WP_Post $attachment The attachment object.
	 * @param array $meta The attachment metadata.
	 * 
	 * @return array The modified response array.
	 */
	public function prepare_attachment_for_js( $response, $attachment, $meta ) {
		if ( $response['mime'] !== 'image/svg+xml' ) {
			return $response;
		}

		if ( ! empty( $response['sizes'] ) ) {
			return $response;
		}

		$svg_path = get_attached_file( $attachment->ID );
		if ( ! file_exists( $svg_path ) ) {
			$svg_path = $response['url'];
		}
		$dimensions        = $this->get_svg_dimensions( $svg_path );
		$response['sizes'] = array(
			'full' => array(
				'url'         => $response['url'],
				'width'       => $dimensions['width'],
				'height'      => $dimensions['height'],
				'orientation' => $dimensions['width'] > $dimensions['height'] ? 'landscape' : 'portrait',
			),
		);

		return $response;
	}

	/**
	 * Get SVG dimensions.
	 * 
	 * Infers the dimensions from the SVG file either from the width and height attributes or from the viewBox attribute.
	 * 
	 * @param string $svg The SVG file path.
	 * 
	 * @return array {
	 *  'width' => int,
	 *  'height' => int,
	 * }
	 */
	private function get_svg_dimensions( $svg ) {
		$defaults = array(
			'width'  => 0,
			'height' => 0,
		);

		if ( ! is_file( $svg ) ) {
			return $defaults;
		}

		$svg = simplexml_load_file( $svg );
		if ( ! $svg ) {
			return $defaults;
		}
		$width  = 0;
		$height = 0;

		$attributes = $svg->attributes();

		if ( isset( $attributes->width, $attributes->height ) ) {
			if ( substr( trim( $attributes->width ), -1 ) !== '%' ) {
				$width = floatval( $attributes->width );
			}
			if ( substr( trim( $attributes->height ), -1 ) !== '%' ) {
				$height = floatval( $attributes->height );
			}
		}
		// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		if ( ( ! $width || ! $height ) && isset( $attributes->viewBox ) ) {
			$sizes = explode( ' ', $attributes->viewBox );
			if ( isset( $sizes[2], $sizes[3] ) ) {
				$width  = floatval( $sizes[2] );
				$height = floatval( $sizes[3] );
			}
		}
		// phpcs:enable

		return array(
			'width'  => $width,
			'height' => $height,
		);
	}
}
