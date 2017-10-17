<?php
/**
 * Orbit Fox Elementor Features Widget
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Elementor_Widgets_OBFX_Module
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class OBFX_Elementor_Widget_Features
 *
 * @package Elementor_Widgets_OBFX_Module
 */
class OBFX_Elementor_Widget_Posts_Grid extends Widget_Base {

	/**
	 * Set the widget ID
	 *
	 * @return string
	 */
	public function get_id() {
		return 'obfx-widget-posts-grid';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Posts Grid', 'themeisle-companion' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-posts-grid';
	}

	/**
	 * Widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'obfx-posts-grid';
	}

	/**
	 * Widget Category
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'obfx-elementor-widgets' ];
	}

	/**
	 * Register Elementor Controls
	 */
	protected function _register_controls() {
		$this->grid_options_section();
		$this->post_options_section();
		$this->product_options_section();
		$this->page_options_section();
	}

	/**
	 * Content > Grid Options
	 */
	private function grid_options_section() {
		$this->start_controls_section(
			'section_grid',
			[
				'label' => __( 'Grid Options', 'themeisle-companion' ),
			]

		);

		// Post type
		$this->add_control(
			'grid_post_type',
			[
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Post Type', 'themeisle-companion' ),
				'default' => 'posts',
				'options' => [
					'posts' => __( 'posts', 'themeisle-companion' ),
					'products' => __( 'products', 'themeisle-companion' ),
					'pages' => __( 'pages', 'themeisle-companion' ),
				],
			]
		);
		
		// Items
		$this->add_control(
			'grid_items',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => __( 'Items', 'themeisle-companion' ),
				'placeholder' => __( 'How many items?', 'themeisle-companion' ),
				'default'     => __( '6', 'themeisle-companion' ),
			]
		);

		// Rows
		$this->add_control(
			'grid_rows',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => __( 'Rows', 'themeisle-companion' ),
				'placeholder' => __( 'How many rows?', 'themeisle-companion' ),
				'default'     => __( '2', 'themeisle-companion' ),
			]
		);

		// Order by
		$this->add_control(
			'grid_order_by',
			[
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Order by', 'themeisle-companion' ),
				'default' => 'date',
				'options' => [
					'date' => __( 'Date', 'themeisle-companion' ),
					'title' => __( 'Title', 'themeisle-companion' ),
					'modified' => __( 'Modified date', 'themeisle-companion' ),
					'comment_count' => __( 'Comment count', 'themeisle-companion' ),
					'rand' => __( 'Random', 'themeisle-companion' ),
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content > Post Options.
	 */
	private function post_options_section() {
		$this->start_controls_section(
			'section_post',
			[
				'label' => __( 'Post Options', 'themeisle-companion' ),
				'condition' => [
					'grid_post_type' => 'posts',
				]
			]

		);

		// Rows
		$this->add_control(
			'post_rows',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => __( 'Rows', 'themeisle-companion' ),
				'placeholder' => __( 'How many rows?', 'themeisle-companion' ),
				'default'     => __( '2', 'themeisle-companion' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content > Product Options.
	 */
	private function product_options_section() {
		$this->start_controls_section(
			'section_product',
			[
				'label' => __( 'Product Options', 'themeisle-companion' ),
				'condition' => [
					'grid_post_type' => 'products',
				]
			]

		);

		// Rows
		$this->add_control(
			'product_rows',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => __( 'Rows', 'themeisle-companion' ),
				'placeholder' => __( 'How many rows?', 'themeisle-companion' ),
				'default'     => __( '2', 'themeisle-companion' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content > Page Options.
	 */
	private function page_options_section() {
		$this->start_controls_section(
			'section_page',
			[
				'label' => __( 'Page Options', 'themeisle-companion' ),
				'condition' => [
					'grid_post_type' => 'pages',
				]
			]

		);

		// Rows
		$this->add_control(
			'page_rows',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => __( 'Rows', 'themeisle-companion' ),
				'placeholder' => __( 'How many rows?', 'themeisle-companion' ),
				'default'     => __( '2', 'themeisle-companion' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render function to output the pricing table.
	 */
	protected function render() {
		$settings = $this->get_settings();
		
		$this->add_render_attribute( 'grid_post_type', 'class', 'obfx-grid-options-title' );
		$this->add_render_attribute( 'grid_items', 'class', 'obfx-grid-options-items' );
		$this->add_render_attribute( 'grid_rows', 'class', 'obfx-grid-options-rows' );

		$output = '';

		$output .= '<div>';

		$output .= esc_html( $settings['grid_post_type'] );

		$output .= esc_html( $settings['grid_items'] );

		$output .= esc_html( $settings['grid_rows'] );

		$output .= '</div> <!-- /.obfx-posts-grid-wrapper -->';

		echo $output;
	}
}

