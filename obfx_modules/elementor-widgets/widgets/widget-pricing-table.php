<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class OBFX_Elementor_Widget_Pricing_Table extends Widget_Base {

	/**
	 * Set the widget ID
	 *
	 * @return string
	 */
//	public function get_id() {
//		return 'obfx-widget-pricing-table';
//	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Pricing Table', 'themeisle-companion' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-price-table';
	}

	/**
	 * Widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'obfx-pricing-table';
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
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Plan Title', 'themeisle-companion' )
			]
		);

		$this->add_control(
			'title',
			[
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Title', 'themeisle-companion' ),
				'placeholder' => __( 'Title', 'themeisle-companion' ),
				'default'     => __( 'Pricing Plan', 'themeisle-companion' ),
			]
		);

		$this->add_control(
			'title_tag',
			[
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Title HTML tag', 'themeisle-companion' ),
				'default' => 'h3',
				'options' => [
					'h1' => __( 'h1', 'themeisle-companion' ),
					'h2' => __( 'h2', 'themeisle-companion' ),
					'h3' => __( 'h3', 'themeisle-companion' ),
					'h4' => __( 'h4', 'themeisle-companion' ),
					'h5' => __( 'h5', 'themeisle-companion' ),
					'h6' => __( 'h6', 'themeisle-companion' ),
					'p'  => __( 'p', 'themeisle-companion' )
				],
			]
		);


		$this->add_control(
			'subtitle',
			[
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Subtitle', 'themeisle-companion' ),
				'placeholder' => __( 'Subtitle', 'themeisle-companion' ),
				'default'     => __( 'Description', 'themeisle-companion' ),
			]
		);

		$this->add_control(
			'subtitle_tag',
			[
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Subtitle HTML Tag', 'themeisle-companion' ),
				'default' => 'p',
				'options' => [
					'h1' => __( 'h1', 'themeisle-companion' ),
					'h2' => __( 'h2', 'themeisle-companion' ),
					'h3' => __( 'h3', 'themeisle-companion' ),
					'h4' => __( 'h4', 'themeisle-companion' ),
					'h5' => __( 'h5', 'themeisle-companion' ),
					'h6' => __( 'h6', 'themeisle-companion' ),
					'p'  => __( 'p', 'themeisle-companion' )
				],
			]
		);
		$this->end_controls_section(); //end section-title

		$this->start_controls_section(
			'section_price_tag',
			[
				'label' => __( 'Price Tag', 'themeisle-companion' )
			]
		);


		$this->add_control(
			'price_tag_text',
			[
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Price', 'themeisle-companion' ),
				'placeholder' => __( 'Price', 'themeisle-companion' ),
				'default'     => __( '50', 'themeisle-companion' ),
				'separator'   => 'after',
			]
		);

		$this->add_control(
			'price_tag_currency',
			[
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Currency', 'themeisle-companion' ),
				'placeholder' => __( 'Currency', 'themeisle-companion' ),
				'default'     => __( '$', 'themeisle-companion' ),
			]
		);

		$this->add_control(
			'price_tag_currency_position',
			[
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Currency Position', 'themeisle-companion' ),
				'default' => 'left',
				'options' => [
					'left'  => __( 'Before', 'themeisle-companion' ),
					'right' => __( 'After', 'themeisle-companion' ),
				],
			]
		);

		$this->add_control(
			'price_tag_currency_transform',
			[
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Currency Transform', 'themeisle-companion' ),
				'default' => 'sup',
				'options' => [
					'sup'  => __( 'Suprascript', 'themeisle-companion' ),
					'sub'  => __( 'Subscript', 'themeisle-companion' ),
					'none' => __( 'Normal', 'themeisle-companion' ),
				],
			]
		);


		$this->add_control(
			'price_tag_period',
			[
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Period', 'themeisle-companion' ),
				'placeholder' => __( '/month', 'themeisle-companion' ),
				'default'     => __( '/month', 'themeisle-companion' ),
				'separator'   => 'before'
			]
		);
		$this->end_controls_section(); // end section-price-tag

		$this->start_controls_section(
			'section_features',
			[
				'label' => __( 'Features', 'themeisle-companion' )
			]
		);

		$this->add_control(
			'feature_list',
			[
				'label'       => __( 'Plan Features', 'themeisle-companion' ),
				'type'        => Controls_Manager::REPEATER,
				'default'     => [
					[
						'accent' => __( 'First', 'themeisle-companion' ),
						'text'   => __( 'Feature', 'themeisle-companion' ),
					],
					[
						'accent' => __( 'Second', 'themeisle-companion' ),
						'text'   => __( 'Feature', 'themeisle-companion' ),
					],
					[
						'accent' => __( 'Third', 'themeisle-companion' ),
						'text'   => __( 'Feature', 'themeisle-companion' ),
					],
				],
				'fields'      => [
					[
						'type'        => Controls_Manager::TEXT,
						'name'        => 'accent',
						'label'       => __( 'Accented Text', 'themeisle-companion' ),
						'description' => __( 'Appears before feature text', 'themeisle-companion' ),
						'label_block' => true,
						'default'     => __( 'Accent', 'themeisle-companion' ),
					],
					[
						'type'        => Controls_Manager::TEXT,
						'name'        => 'text',
						'label'       => __( 'Text', 'themeisle-companion' ),
						'label_block' => true,
						'placeholder' => __( 'Plan Features', 'themeisle-companion' ),
						'default'     => __( 'Feature', 'themeisle-companion' ),
					]
				],
				'title_field' => '{{ accent + " " + text }}'
			]
		);
		$this->end_controls_section(); // end section-features

		$this->start_controls_section(
			'section_button',
			[
				'label' => __( 'Button', 'themeisle-companion' )
			]
		);

		$this->add_control(
			'button_text',
			[
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Text', 'themeisle-companion' ),
				'placeholder' => __( 'Buy Now', 'themeisle-companion' ),
				'default'     => __( 'Buy Now', 'themeisle-companion' ),
			]
		);

		$this->add_control(
			'button_link',
			[
				'type'        => Controls_Manager::URL,
				'label'       => __( 'Link', 'themeisle-companion' ),
				'placeholder' => __( 'https://example.com', 'themeisle-companion' ),
			]
		);

		$this->add_control(
			'button_icon',
			[
				'type'        => Controls_Manager::ICON,
				'label'       => __( 'Icon', 'themeisle-companion' ),
				'label_block' => true,
				'default'     => '',
			]
		);

		$this->add_control(
			'button_icon_align',
			[
				'type'      => Controls_Manager::SELECT,
				'label'     => __( 'Icon Position', 'themeisle-companion' ),
				'default'   => 'left',
				'options'   => [
					'left'  => __( 'Before', 'themeisle-companion' ),
					'right' => __( 'After', 'themeisle-companion' ),
				],
				'condition' => [
					'button_icon!' => '',
				]
			]
		);

		$this->add_control(
			'button_icon_indent',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => __( 'Icon Spacing', 'themeisle-companion' ),
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'button_icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .obfx-pricing-table-button-wrapper .obfx-button-icon-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .obfx-pricing-table-button-wrapper .obfx-button-icon-align-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section(); // end section-button


		$this->start_controls_section(
			'section-box-style',
			[
				'label' => __( 'Box Style', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'box-color',
			[
				'label'     => __( 'Box Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'default'   => '#93C64F',
				'selectors' => [
					'{{WRAPPER}} .wts-price-box-wrapper' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'box_border',
				'label'       => __( 'Box Border', 'elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .wts-price-box-wrapper',
			]
		);


		$this->add_control(
			'box-border-radius',
			[
				'label'      => __( 'Border Radius', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wts-price-box-wrapper'                   => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wts-price-box-wrapper > div:first-child' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
					'{{WRAPPER}} .wts-price-box-wrapper > div:last-child'  => 'border-radius: 0 0  {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_box_shadow',
				'selector' => '{{WRAPPER}} .wts-price-box-wrapper',
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section-plan-heading-style',
			[
				'label' => __( 'Plan Heading', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'plan_heading_color',
			[
				'label'     => __( 'Heading Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'default'   => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .eae-pt-heading' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'plan_heading_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eae-pt-heading',
			]
		);

		$this->add_control(
			'plan_sub_heading_color',
			[
				'label'     => __( 'Sub Heading Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'default'   => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .eae-pt-sub-heading' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'plan_sub_heading_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eae-pt-sub-heading',
			]
		);


		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'heading_section_bg',
				'label'    => __( 'Section Background', 'elementor' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .heading-wrapper',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section-price-box',
			[
				'label' => __( 'Price Box', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'pb_content_settings',
			[
				'label'     => __( 'Content Settings', 'elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_text_color',
			[
				'label'     => __( 'Price Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'default'   => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .plan-price-shape-inner .price-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_text_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .plan-price-shape-inner .price-text',
			]
		);


		$this->add_control(
			'price_sub_text_color',
			[
				'label'     => __( 'Sub Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'default'   => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .plan-price-shape-inner .price-subtext' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_sub_text_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .plan-price-shape-inner .price-subtext',
			]
		);

		$this->add_control(
			'pb_box_settings',
			[
				'label'     => __( 'Box Settings', 'elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'price_box_border',
				'label'    => __( 'Price Box Border', 'elementor' ),
				'selector' => '{{WRAPPER}} .plan-price-shape',
			]
		);

		$this->add_control(
			'price_box_border_radius',
			[
				'label'      => __( 'Border Radius', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .plan-price-shape' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'price_box_padding',
			[
				'label'      => __( 'Price Box Padding', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .plan-price-shape-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'price_box_section_bg',
				'label'    => __( 'Section Background', 'elementor' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .plan-price-block',
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section-features-style',
			[
				'label' => __( 'Feature List', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'features_text_color',
			[
				'label'     => __( 'Features Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'default'   => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .eae-pt-feature-list li' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'features_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eae-pt-feature-list li',
			]
		);


		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'feature_section_bg',
				'label'    => __( 'Section Background', 'elementor' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .plan-features-wrapper',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section-action-button',
			[
				'label' => __( 'Action Button', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button-section-bg',
			[
				'label'     => __( 'Section Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .eae-pt-button-wrapper' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
						'{{WRAPPER}} .eae-pt-action-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'label'    => __( 'Typography', 'elementor' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .eae-pt-action-button',
			]
		);

		$this->add_control(
			'background_color',
			[
				'label'     => __( 'Background Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'default'   => '#93C64F',
				'selectors' => [
					'{{WRAPPER}} .eae-pt-action-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'action_section_bg',
				'label'    => __( 'Section Background', 'elementor' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eae-pt-button-wrapper',
				'default'  => [
					'background' => 'classic',
					'color'      => '#555'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'border',
				'label'       => __( 'Border', 'elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .eae-pt-action-button',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label'      => __( 'Border Radius', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eae-pt-action-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'text_padding',
			[
				'label'      => __( 'Text Padding', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eae-pt-action-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_hover',
			[
				'label' => __( 'Button Hover', 'elementor' ),
				'type'  => Controls_Manager::SECTION,
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eae-pt-action-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label'     => __( 'Background Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eae-pt-action-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => __( 'Border Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .eae-pt-action-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Render function to output the control on the front end.
	 */
	protected function render() {
		$settings = $this->get_settings();

		$this->add_render_attribute( 'title', 'class', 'obfx-pricing-table-title' );
		$this->add_render_attribute( 'subtitle', 'class', 'obfx-pricing-table-subtitle' );
		$this->add_render_attribute( 'button', 'class', 'obfx-pricing-table-button' );
		$this->add_render_attribute( 'button_icon', 'class', $settings['button_icon'] );
		$this->add_render_attribute( 'button_icon_align', 'class', 'obfx-button-icon-align-' . $settings['button_icon_align'] );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'button', 'href', $settings['link']['url'] );

			if ( ! empty( $settings['link']['is_external'] ) ) {
				$this->add_render_attribute( 'button', 'target', '_blank' );
			}
		}

		$output = '';

		$output .= '<div class="obfx-pricing-table-wrapper">';

		if ( ! empty( $settings['title'] ) ) {
			$output .= '<div class="obfx-title-wrapper">';
			if ( ! empty( $settings['title'] ) ) {
				// Start of title tag.
				$output .= '<' . esc_html( $settings['title_tag'] ) . ' ' . $this->get_render_attribute_string( 'title' ) . '>';

				// Title string.
				$output .= esc_html( $settings['title'] );

				// End of title tag.
				$output .= '</' . esc_html( $settings['title_tag'] ) . '>';
			}
			if ( ! empty( $settings['subtitle'] ) ) {
				// Start of subtitle tag.
				$output .= '<' . esc_html( $settings['subtitle_tag'] ) . ' ' . $this->get_render_attribute_string( 'subtitle' ) . '>';

				// Subtitle string.
				$output .= esc_html( $settings['subtitle'] );

				// End of subtitle tag.
				$output .= '</' . esc_html( $settings['subtitle_tag'] ) . '>';

			}

			$output .= '</div> <!-- /.obfx-title-wrapper -->';
		}

		if ( ! empty( $settings['price_tag_text'] ) || ! empty( $settings['price_tag_currency'] ) || ! empty( $settings['price_tag_period'] ) ) {
			$output .= '<div class="obfx-price-wrapper">';

			if ( ! empty( $settings['price_tag_currency'] ) ) {
				$output .= '<span class="obfx-price-currency">' . esc_html( $settings['price_tag_currency'] ) . '</span>';
			}

			if ( ! empty( $settings['price_tag_text'] ) ) {
				$output .= '<span class="obfx-price">' . esc_html( $settings['price_tag_text'] ). '</span>';
			}

			if ( ! empty( $settings['price_tag_period'] ) ) {
				$output .= '<span class="obfx-pricing-period">' . esc_html( $settings['price_tag_period'] ) . '</span>';
			}

			$output .= '</div> <!-- /.obfx-price-wrapper -->';
		}


		if ( count( $settings['feature_list'] ) ) {
			$output .= '<ul class="obfx-feature-list">';
			foreach ( $settings['feature_list'] as $feature ) {
				$output .= '<li>';
				if( ! empty( $feature['accent'] ) ) {
					$output .=  '<span class="obfx-pricing-table-accented">' . esc_html( $feature['accent'] ) . '</span>';
					$output .= ' ';
				}
				if( ! empty( $feature['text'] ) ) {
					$output .= '<span class="obfx-pricing-table-feature">' . esc_html( $feature['text'] ) . '</span>';
				}
				$output .= '</li>';
			}
			$output .= '</ul>';
		}

		if ( ! empty( $settings['button_text'] ) ) {
			$output .= '<div class="obfx-pricing-table-button-wrapper">';

			$output .= '<a ' . $this->get_render_attribute_string( 'button' ) . '>';
			var_dump($settings['button_icon']);

			if ( ! empty( $settings['button_icon'] ) ) {
				$output .= '<span ' . $this->get_render_attribute_string( 'button_icon_align' ) . ' >';
				$output .= '<i ' . $this->get_render_attribute_string( 'button_icon' ) . '></i>';
			}

			$output .= '<span class="elementor-button-text">' . esc_html( $settings['button_text'] ) . '</span>';
			$output .= '</a>';
			$output .= '</div> <!-- /.obfx-pricing-table-button-wrapper -->';
		}
		$output .= '</div> <!-- close .obfx-pricing-table-wrapper -->';

		echo $output;
	}
}
