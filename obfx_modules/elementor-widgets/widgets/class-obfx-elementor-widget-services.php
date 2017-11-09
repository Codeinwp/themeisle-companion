<?php
/**
 * Orbit Fox Elementor Services Widget
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
 * Class OBFX_Elementor_Widget_Services
 *
 * @package Elementor_Widgets_OBFX_Module
 */
class OBFX_Elementor_Widget_Services extends Widget_Base {

	/**
	 * Set the widget ID
	 *
	 * @return string
	 */
	public function get_id() {
		return 'obfx-widget-services';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Services', 'themeisle-companion' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-themeisle';
	}

	/**
	 * Widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'obfx-services';
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
		$this->services_content();
	}
	private function services_content() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Services', 'themeisle-companion' ),
			]
		);

		$this->add_control(
			'services_list',
			[
				'label'       => __( 'Services', 'themeisle-companion' ),
				'type'        => Controls_Manager::REPEATER,
				'default'     => [
					[
						'title' => __( 'Responsive', 'themeisle-companion' ),
						'text'  => __( 'A lot of text here', 'themeisle-companion' ),
						'icon'  => 'fa fa-star',
						'color'  => '#333333',
					],
				],
				'fields'      => [
					[
						'type'        => Controls_Manager::TEXT,
						'name'        => 'title',
						'label'       => __( 'Service Title', 'themeisle-companion' ),
						'label_block' => true,
						'default'     => __( 'Service Title', 'themeisle-companion' ),
					],
					[
						'type'        => Controls_Manager::TEXT,
						'name'        => 'text',
						'label'       => __( 'Text', 'themeisle-companion' ),
						'label_block' => true,
						'placeholder' => __( 'Plan Features', 'themeisle-companion' ),
						'default'     => __( 'Feature', 'themeisle-companion' ),
					],
					[
						'type'        => Controls_Manager::ICON,
						'name'        => 'icon',
						'label'       => __( 'Icon', 'themeisle-companion' ),
						'label_block' => true,
						'default'     => 'fa fa-star',
					],
					[
						'type'        => Controls_Manager::COLOR,
						'name'        => 'color',
						'label'       => __( 'Icon Color', 'themeisle-companion' ),
						'label_block' => true,
						'default'     => '#5764c6',
					],
				],
				'title_field' => '<i style="color:{{color}}" class="{{icon}}"></i> {{title}}',
			]
		);

		$this->add_responsive_control(
			'features_align',
			[
				'label'     => __( 'Alignment', 'themeisle-companion' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => __( 'Left', 'themeisle-companion' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'themeisle-companion' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'   => [
						'title' => __( 'Right', 'themeisle-companion' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .obfx-feature-list' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section(); // end section-features
	}

	/**
	 * Render function to output the pricing table.
	 */
	protected function render() {
		$settings = $this->get_settings();
		foreach ( $settings['services_list'] as $service ) { ?>
			<div class="obfx-service-box">
				<?php
				if( ! empty ( $service['icon'] ) ) { ?>
					<span class="obfx-icon-wrap"><i class="obfx-icon <?php echo esc_attr( $service['icon'] ); ?>" style="color: <?php echo esc_attr( $service['color'] ); ?>"></i></span>
				<?php }
				if( ! empty ( $service['title'] ) ) { ?>
					<h4 class="obfx-service-title"><?php echo esc_attr( $service['title'] ); ?></h4>
				<?php }
				if( ! empty ( $service['title'] ) ) { ?>
					<p class="obfx-service-text"><?php echo esc_attr( $service['text'] ); ?></p>
				<?php } ?>
			</div>
		<?php }

	}
}

