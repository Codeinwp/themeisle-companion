<?php
/**
 * Should contain:
 *
 * Style:
 * Header:
 *  - Background
 *      - Color / Image / Gradient
 *
 *
 */
/**
 * This is an example module with only the basic
 * setup necessary to get it working.
 *
 * @class PricingTableModule
 */
class PricingTableModule extends FLBuilderModule {

	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct( array(
			'name'          => esc_html__('Pricing table', 'themeisle-companion'),
			'description'   => esc_html__('Pricing Tables are the perfect option when showcasing services you have on offer.', 'themeisle-companion'),
			'category'		=> esc_html__('Orbit Fox Modules', 'themeisle-companion'),
			'dir'           => BEAVER_WIDGETS_PATH . 'modules/pricing-table/',
			'url'           => BEAVER_WIDGETS_URL . 'modules/pricing-table/',
			'editor_export' => true, // Defaults to true and can be omitted.
			'enabled'       => true, // Defaults to true and can be omitted.
		));
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('PricingTableModule', array(
	'content' => array(
		'title' => esc_html__('Content', 'themeisle-companion'), // Tab title
		'sections' => array(
			'header' => array(
				'title'  => esc_html__('Plan Header', 'themeisle-companion'),
				'fields' => array(
					'plan_title' => array(
						'type'    => 'text',
						'label'   => esc_html__('Title', 'themeisle-companion'),
						'default' => esc_html__('Plan title', 'themeisle-companion'),
						'preview' => array(
							'type'     => 'text',
							'selector' => '.obfx-plan-title',
						)
					),
					'plan_title_tag' => array(
						'type'    => 'select',
						'label'   => esc_html__('Title tag', 'themeisle-companion'),
						'default' => 'h2',
						'options' => array(
							'h1' => esc_html__('h1', 'themeisle-companion'),
							'h2' => esc_html__('h2', 'themeisle-companion'),
							'h3' => esc_html__('h3', 'themeisle-companion'),
							'h4' => esc_html__('h4', 'themeisle-companion'),
							'h5' => esc_html__('h5', 'themeisle-companion'),
							'h6' => esc_html__('h6', 'themeisle-companion'),
							'p'  => esc_html__('p', 'themeisle-companion'),
						)
					),
					'plan_subtitle' => array(
						'type'    => 'text',
						'label'   => esc_html__('Subtitle', 'themeisle-companion'),
						'default' => esc_html__('Plan subtitle', 'themeisle-companion'),
						'preview' => array(
							'type'     => 'text',
							'selector' => '.obfx-plan-subtitle',
						)
					),
					'plan_subtitle_tag' => array(
						'type'    => 'select',
						'label'   => esc_html__('Subtitle tag', 'themeisle-companion'),
						'default' => 'p',
						'options' => array(
							'h1' => esc_html__('h1', 'themeisle-companion'),
							'h2' => esc_html__('h2', 'themeisle-companion'),
							'h3' => esc_html__('h3', 'themeisle-companion'),
							'h4' => esc_html__('h4', 'themeisle-companion'),
							'h5' => esc_html__('h5', 'themeisle-companion'),
							'h6' => esc_html__('h6', 'themeisle-companion'),
							'p'  => esc_html__('p', 'themeisle-companion'),
						)
					),
				)
			),
			'price' => array(
				'title'  => esc_html__('Price Tag', 'themeisle-companion'),
				'fields' => array(
					'price' => array(
						'type'    => 'text',
						'label'   => esc_html__('Price', 'themeisle-companion'),
						'default' => '50',
						'preview' => array(
							'type'     => 'text',
							'selector' => '.obfx-plan-price',
						)
					),
					'currency' => array(
						'type'    => 'text',
						'label'   => esc_html__('Currency', 'themeisle-companion'),
						'default' => '$',
						'preview' => array(
							'type'     => 'text',
							'selector' => '.obfx-plan-currency',
						)
					),
					'currency_position' => array(
						'type'    => 'select',
						'label'   => esc_html__('Currency position', 'themeisle-companion'),
						'default' => 'after',
						'options' => array(
							'before' => esc_html__('Before', 'themeisle-companion'),
							'after'  => esc_html__('After', 'themeisle-companion'),
						)
					),
					'period' => array(
						'type'    => 'text',
						'label'   => esc_html__('Period', 'themeisle-companion'),
						'default' => esc_html__( '/month', 'themeisle-companion'),
						'preview' => array(
							'type'     => 'text',
							'selector' => '.obfx-plan-period',
						)
					)
				)
			),
			'features' => array(
				'title' => esc_html__('Features list','themeisle-companion'),
				'fields' => array(
					'features' => array(
						'multiple' => true,
						'type'          => 'form',
						'label'         => esc_html__('Feature', 'themeisle-companion'),
						'form'          => 'feature_field', // ID of a registered form.
						'preview_text'  => 'text', // ID of a field to use for the preview text.
					)
				)
			),
			'button' => array(
				'title' => esc_html__('Button', 'themeisle-companion'),
				'fields' => array(
					'text' => array(
						'type'    => 'text',
						'label'   => esc_html__('Button text', 'themeisle-companion'),
						'default' => esc_html__( 'Button', 'themeisle-companion'),
						'preview' => array(
							'type'     => 'text',
							'selector' => '.obfx-plan-button',
						)
					),
					'link' => array(
						'type' => 'link',
						'label' => esc_html__('Button link', 'themeisle-companion'),
					)
				)
			)
		)
	),
	'header_style' => array(
		'title' => esc_html__('Header Style', 'themeisle-companion'),
		'sections' => array(
			'header_padding' => array(
				'title'  => esc_html__('Padding', 'themeisle-companion'),
				'fields' => array(
					'top' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Top', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 15,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-header',
									'property'     => 'padding-top',
									'unit' => 'px',
								)
							)
						)
					),
					'bottom' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Bottom', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 30,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-header',
									'property'     => 'padding-bottom',
									'unit' => 'px',
								)
							)
						)
					),
					'left' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Left', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 0,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-header',
									'property'     => 'padding-left',
									'unit' => 'px',
								)
							)
						)
					),
					'right' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Right', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 0,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-header',
									'property'     => 'padding-right',
									'unit' => 'px',
								)
							)
						)
					)
				)
			),
			'colors' => array(
				'title'  => esc_html__('Colors', 'themeisle-companion'),
				'fields' => array(
					'title_color' => array(
						'type' => 'color',
						'label' => esc_html__('Title color', 'themeisle-companion'),
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-header *:first-child',
									'property'     => 'color'
								)
							)
						),
					),
					'subtitle_color' => array(
						'type' => 'color',
						'label' => esc_html__('Subtitle color', 'themeisle-companion'),
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-header *:last-child',
									'property'     => 'color'
								)
							)
						),
					)
				)
			),
			'title_typography' => array(
				'title' => esc_html__('Title typography', 'themeisle-companion'),
				'fields' => array(
					'title_font_size' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Font size', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-header *:first-child',
									'property'     => 'font-size',
									'unit' => 'px',
								)
							)
						)
					),
					'title_font_family' => array(
						'type'          => 'font',
						'label'         => esc_html__( 'Font family', 'themeisle-companion' ),
						'default'       => array(
							'family'        => 'Roboto',
							'weight'        => 300
						),
						'preview' => array(
							'type' => 'none'
						)
					),
					'title_transform' => array(
						'type'    => 'select',
						'label'   => esc_html__('Transform', 'themeisle-companion'),
						'default' => 'none',
						'options' => array(
							'none' => esc_html__('None', 'themeisle-companion'),
							'capitalize' => esc_html__('Capitalize', 'themeisle-companion'),
							'uppercase' => esc_html__('Uppercase', 'themeisle-companion'),
							'lowercase' => esc_html__('Lowercase', 'themeisle-companion'),
						)
					),
					'title_font_style' => array(
						'type'    => 'select',
						'label'   => esc_html__('Font style', 'themeisle-companion'),
						'default' => 'normal',
						'options' => array(
							'normal' => esc_html__('Normal', 'themeisle-companion'),
							'italic' => esc_html__('Italic', 'themeisle-companion'),
							'oblique' => esc_html__('Oblique', 'themeisle-companion'),
						)
					),
					'title_line_height' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Line height', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-header *:first-child',
									'property'     => 'line-height',
									'unit' => 'px',
								)
							)
						)
					),
					'title_letter_spacing' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Letter spacing', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-header *:first-child',
									'property'     => 'letter-spacing',
									'unit' => 'px',
								)
							)
						)
					),
				),

			),
			'subtitle_typography' => array(
				'title' => esc_html__('Subtitle typography', 'themeisle-companion'),
				'fields' => array(
					'subtitle_font_size' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Font size', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-header *:last-child',
									'property'     => 'font-size',
									'unit' => 'px',
								)
							)
						)
					),
					'subtitle_font_family' => array(
						'type'          => 'font',
						'label'         => __( 'Font family', 'themeisle-companion' ),
						'default'       => array(
							'family'        => 'Roboto',
							'weight'        => 300
						)
					),
					'subtitle_transform' => array(
						'type'    => 'select',
						'label'   => esc_html__('Transform', 'themeisle-companion'),
						'default' => 'none',
						'options' => array(
							'none' => esc_html__('None', 'themeisle-companion'),
							'capitalize' => esc_html__('Capitalize', 'themeisle-companion'),
							'uppercase' => esc_html__('Uppercase', 'themeisle-companion'),
							'lowercase' => esc_html__('Lowercase', 'themeisle-companion'),
						)
					),
					'subtitle_font_style' => array(
						'type'    => 'select',
						'label'   => esc_html__('Font style', 'themeisle-companion'),
						'default' => 'normal',
						'options' => array(
							'normal' => esc_html__('Normal', 'themeisle-companion'),
							'italic' => esc_html__('Italic', 'themeisle-companion'),
							'oblique' => esc_html__('Oblique', 'themeisle-companion'),
						)
					),
					'subtitle_line_height' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Line height', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-header *:last-child',
									'property'     => 'line-height',
									'unit' => 'px',
								)
							)
						)
					),
					'subtitle_letter_spacing' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Letter spacing', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-header *:last-child',
									'property'     => 'letter-spacing',
									'unit' => 'px',
								)
							)
						)
					),
				),
			),
			'header_background' => array(
				'title' => esc_html__('Background', 'themeisle-companion'),
				'fields' => array(
					'bg_type' => array(
						'type'    => 'select',
						'label'   => esc_html__('Type', 'themeisle-companion'),
						'default' => 'color',
						'options' => array(
							'color' => esc_html__('Color', 'themeisle-companion'),
							'image' => esc_html__('Background', 'themeisle-companion'),
							'gradient' => esc_html__('Gradient', 'themeisle-companion'),
						),
						'toggle' => array(
							'color' => array(
								'fields' => array('header_bg_color'),
							),
							'image' =>  array(
								'fields' => array('header_bg_image'),
							),
							'gradient' => array(
								'fields' => array('gradient_color1', 'gradient_color2', 'gradient_orientation'),
							)
						)
					),
					'header_bg_color' => array(
						'type' => 'color',
						'label' => esc_html__('Background color', 'themeisle-companion'),
						'show_reset'    => true,
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-header',
									'property'     => 'background-color'
								)
							)
						)
					),
					'header_bg_image' => array(
						'type'          => 'photo',
						'label'         => esc_html__('Photo Field', 'themeisle-companion'),
						'show_remove'    => true,
					),
					'gradient_color1' => array(
						'type' => 'color',
						'label' => esc_html__('Gradient color 1', 'themeisle-companion'),
						'show_reset'    => true,
					),
					'gradient_color2' => array(
						'type' => 'color',
						'label' => esc_html__('Gradient color 2', 'themeisle-companion'),
						'show_reset'    => true,
					),
					'gradient_orientation' => array(
						'type'    => 'select',
						'label'   => esc_html__('Orientation', 'themeisle-companion'),
						'default' => 'horizontal',
						'options' => array(
							'horizontal' => esc_html__('Horizontal', 'themeisle-companion'),
							'vertical' => esc_html__('Vertical', 'themeisle-companion'),
							'diagonal_bottom' => esc_html__('Diagonal bottom', 'themeisle-companion'),
							'diagonal_top' => esc_html__('Diagonal top', 'themeisle-companion'),
							'radial' => esc_html__('Radial', 'themeisle-companion'),
						),
					)
				)
			)
		)
	),
	'price_style' => array(
		'title' => esc_html__('Price Style', 'themeisle-companion'),
		'sections' => array(
			'price_padding' => array(
				'title' => esc_html__('Padding', 'themeisle-companion'),
				'fields' => array(
					'price_top' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Top', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 30,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-price',
									'property'     => 'padding-top',
									'unit' => 'px',
								)
							)
						)
					),
					'price_bottom' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Bottom', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 30,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-price',
									'property'     => 'padding-bottom',
									'unit' => 'px',
								)
							)
						)
					),
					'price_left' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Left', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 0,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-price',
									'property'     => 'padding-left',
									'unit' => 'px',
								)
							)
						)
					),
					'price_right' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Right', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 0,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-price',
									'property'     => 'padding-right',
									'unit' => 'px',
								)
							)
						)
					)
				)
			),
			'price_colors' => array(
				'title' => esc_html__('Colors', 'themeisle-companion'),
				'fields' => array(
					'price_color' => array(
						'type'          => 'color',
						'label'         => esc_html__('Price color', 'themeisle-companion'),
						'preview'       => array(
							'type'          => 'css',
							'rules'           => array(
								array(
									'selector'     => '.obfx-price',
									'property'     => 'color'
								),
							)
						)
					),
					'currency_color' => array(
						'type'          => 'color',
						'label'         => esc_html__('Currency color', 'themeisle-companion'),
						'preview'       => array(
							'type'          => 'css',
							'rules'           => array(
								array(
									'selector'     => '.obfx-pricing-price sup',
									'property'     => 'color'
								),
							)
						)
					),
					'period_color' => array(
						'type'          => 'color',
						'label'         => esc_html__('Period color', 'themeisle-companion'),
						'preview'       => array(
							'type'          => 'css',
							'rules'           => array(
								array(
									'selector'     => '.obfx-period',
									'property'     => 'color'
								),
							)
						)
					),
				)
			),
			'price_typography' => array(
				'title' => esc_html__('Typography', 'themeisle-companion'),
				'fields' => array(
					'price_font_size' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Font size', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'maxlength'     => '3',
						'size'          => '4',
						'default' => 40,
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-price',
									'property'     => 'font-size',
									'unit' => 'px',
								)
							)
						)
					),
					'price_font_family' => array(
						'type'          => 'font',
						'label'         => esc_html__( 'Font family', 'themeisle-companion' ),
						'default'       => array(
							'family'        => 'Roboto',
							'weight'        => 300
						)
					),
					'price_transform' => array(
						'type'    => 'select',
						'label'   => esc_html__('Transform', 'themeisle-companion'),
						'default' => 'none',
						'options' => array(
							'none' => esc_html__('None', 'themeisle-companion'),
							'capitalize' => esc_html__('Capitalize', 'themeisle-companion'),
							'uppercase' => esc_html__('Uppercase', 'themeisle-companion'),
							'lowercase' => esc_html__('Lowercase', 'themeisle-companion'),
						)
					),
					'price_font_style' => array(
						'type'    => 'select',
						'label'   => esc_html__('Font style', 'themeisle-companion'),
						'default' => 'normal',
						'options' => array(
							'normal' => esc_html__('Normal', 'themeisle-companion'),
							'italic' => esc_html__('Italic', 'themeisle-companion'),
							'oblique' => esc_html__('Oblique', 'themeisle-companion'),
						)
					),
					'price_line_height' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Line height', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-price',
									'property'     => 'line-height',
									'unit' => 'px',
								)
							)
						)
					),
					'price_letter_spacing' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Letter spacing', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-price',
									'property'     => 'letter-spacing',
									'unit' => 'px',
								)
							)
						)
					),
				),
			),
		)
	),
	'features_style' => array(
		'title' => esc_html__('Features Style', 'themeisle-companion'),
		'sections' => array(
			'features_padding' => array(
				'title' => esc_html__('Padding', 'themeisle-companion'),
				'fields' => array(
					'features_top' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Top', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 15,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-features .obfx-pricing-feature-content',
									'property'     => 'padding-top',
									'unit' => 'px',
								)
							)
						)
					),
					'features_bottom' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Bottom', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 15,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-features .obfx-pricing-feature-content',
									'property'     => 'padding-bottom',
									'unit' => 'px',
								)
							)
						)
					),
					'features_left' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Left', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 0,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-features .obfx-pricing-feature-content',
									'property'     => 'padding-left',
									'unit' => 'px',
								)
							)
						)
					),
					'features_right' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Right', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 0,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-features .obfx-pricing-feature-content',
									'property'     => 'padding-right',
									'unit' => 'px',
								)
							)
						)
					)
				)
			),
			'features_colors' => array(
				'title' => esc_html__('Colors', 'themeisle-companion'),
				'fields' => array(
					'icon_color' => array(
						'type'          => 'color',
						'label'         => esc_html__('Icon color', 'themeisle-companion'),
						'preview'       => array(
							'type'          => 'css',
							'rules'           => array(
								array(
									'selector'     => '.obfx-pricing-feature-content i',
									'property'     => 'color'
								),
							)
						)
					),
					'bold_color' => array(
						'type'          => 'color',
						'label'         => esc_html__('Bold text color', 'themeisle-companion'),
						'preview'       => array(
							'type'          => 'css',
							'rules'           => array(
								array(
									'selector'     => '.obfx-pricing-feature-content strong',
									'property'     => 'color'
								),
							)
						)
					),
					'feature_color' => array(
						'type'          => 'color',
						'label'         => esc_html__('Text color', 'themeisle-companion'),
						'preview'       => array(
							'type'          => 'css',
							'rules'           => array(
								array(
									'selector'     => '.obfx-pricing-feature-content:not(i):not(strong)',
									'property'     => 'color'
								),
							)
						)
					),
				)
			),
			'feature_typography' => array(
				'title' => esc_html__('Typography', 'themeisle-companion'),
				'fields' => array(
					'feature_font_size' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Font size', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'maxlength'     => '3',
						'size'          => '4',
						'default' => 17,
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-feature-content *',
									'property'     => 'font-size',
									'unit' => 'px',
								)
							)
						)
					),
					'feature_font_family' => array(
						'type'          => 'font',
						'label'         => esc_html__( 'Font family', 'themeisle-companion' ),
						'default'       => array(
							'family'        => 'Roboto',
							'weight'        => 300
						)
					),
					'feature_transform' => array(
						'type'    => 'select',
						'label'   => esc_html__('Transform', 'themeisle-companion'),
						'default' => 'none',
						'options' => array(
							'none' => esc_html__('None', 'themeisle-companion'),
							'capitalize' => esc_html__('Capitalize', 'themeisle-companion'),
							'uppercase' => esc_html__('Uppercase', 'themeisle-companion'),
							'lowercase' => esc_html__('Lowercase', 'themeisle-companion'),
						)
					),
					'feature_font_style' => array(
						'type'    => 'select',
						'label'   => esc_html__('Font style', 'themeisle-companion'),
						'default' => 'normal',
						'options' => array(
							'normal' => esc_html__('Normal', 'themeisle-companion'),
							'italic' => esc_html__('Italic', 'themeisle-companion'),
							'oblique' => esc_html__('Oblique', 'themeisle-companion'),
						)
					),
					'feature_line_height' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Line height', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-feature-content *',
									'property'     => 'line-height',
									'unit' => 'px',
								)
							)
						)
					),
					'feature_letter_spacing' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Letter spacing', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-pricing-feature-content *',
									'property'     => 'letter-spacing',
									'unit' => 'px',
								)
							)
						)
					),
				),
			),
		)
	),
	'button_style' => array(
		'title' => esc_html__('Button Style', 'themeisle-companion'),
		'sections' => array(
			'button_margins' => array(
				'title' => esc_html__('Margins', 'themeisle-companion'),
				'fields' => array(
					'button_margin_top' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Top', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 15,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-plan-bottom',
									'property'     => 'margin-top',
									'unit' => 'px',
								)
							)
						)
					),
					'button_margin_bottom' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Bottom', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 15,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-plan-bottom',
									'property'     => 'margin-bottom',
									'unit' => 'px',
								)
							)
						)
					),
					'button_margin_left' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Left', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 0,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-plan-bottom',
									'property'     => 'margin-left',
									'unit' => 'px',
								)
							)
						)
					),
					'button_margin_right' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Right', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 0,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-plan-bottom',
									'property'     => 'margin-right',
									'unit' => 'px',
								)
							)
						)
					)
				)
			),
			'button_padding' => array(
				'title' => esc_html__('Padding', 'themeisle-companion'),
				'fields' => array(
					'button_padding_top' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Top', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 6,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-plan-button',
									'property'     => 'padding-top',
									'unit' => 'px',
								)
							)
						)
					),
					'button_padding_bottom' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Bottom', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 6,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-plan-button',
									'property'     => 'padding-bottom',
									'unit' => 'px',
								)
							)
						)
					),
					'button_padding_left' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Left', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 12,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-plan-button',
									'property'     => 'padding-left',
									'unit' => 'px',
								)
							)
						)
					),
					'button_padding_right' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Right', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'default' => 12,
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-plan-button',
									'property'     => 'padding-right',
									'unit' => 'px',
								)
							)
						)
					)
				)
			),
			'button_colors' => array(
				'title' => esc_html__('Colors', 'themeisle-companion'),
				'fields' => array(
					'button_text_color' => array(
						'type'          => 'color',
						'label'         => esc_html__('Text', 'themeisle-companion'),
						'preview'       => array(
							'type'          => 'css',
							'rules'           => array(
								array(
									'selector'     => '.obfx-plan-button',
									'property'     => 'color'
								),
							)
						)
					),
					'button_text_color_hover' => array(
						'type'          => 'color',
						'label'         => esc_html__('Text on hover', 'themeisle-companion'),
						'preview'       => array(
							'type'          => 'css',
							'rules'           => array(
								array(
									'selector'     => '.obfx-plan-button:hover',
									'property'     => 'color'
								),
							)
						)
					),
					'button_bg_color' => array(
						'type'          => 'color',
						'label'         => esc_html__('Button background', 'themeisle-companion'),
						'preview'       => array(
							'type'          => 'css',
							'rules'           => array(
								array(
									'selector'     => '.obfx-plan-button',
									'property'     => 'background-color'
								),
							)
						)
					),
					'button_bg_color_hover' => array(
						'type'          => 'color',
						'label'         => esc_html__('Button background on hover', 'themeisle-companion'),
						'preview'       => array(
							'type'          => 'css',
							'rules'           => array(
								array(
									'selector'     => '.obfx-plan-button:hover',
									'property'     => 'background-color'
								),
							)
						)
					),
				)
			),
			'button_typography' => array(
				'title' => esc_html__('Typography', 'themeisle-companion'),
				'fields' => array(
					'button_font_size' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Font size', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'maxlength'     => '3',
						'size'          => '4',
						'default' => 12,
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-plan-button',
									'property'     => 'font-size',
									'unit' => 'px',
								)
							)
						)
					),
					'button_font_family' => array(
						'type'          => 'font',
						'label'         => esc_html__( 'Font family', 'themeisle-companion' ),
						'default'       => array(
							'family'        => 'Roboto',
							'weight'        => 300
						)
					),
					'button_transform' => array(
						'type'    => 'select',
						'label'   => esc_html__('Transform', 'themeisle-companion'),
						'default' => 'none',
						'options' => array(
							'none' => esc_html__('None', 'themeisle-companion'),
							'capitalize' => esc_html__('Capitalize', 'themeisle-companion'),
							'uppercase' => esc_html__('Uppercase', 'themeisle-companion'),
							'lowercase' => esc_html__('Lowercase', 'themeisle-companion'),
						)
					),
					'button_font_style' => array(
						'type'    => 'select',
						'label'   => esc_html__('Font style', 'themeisle-companion'),
						'default' => 'normal',
						'options' => array(
							'normal' => esc_html__('Normal', 'themeisle-companion'),
							'italic' => esc_html__('Italic', 'themeisle-companion'),
							'oblique' => esc_html__('Oblique', 'themeisle-companion'),
						)
					),
					'button_line_height' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Line height', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-plan-button',
									'property'     => 'line-height',
									'unit' => 'px',
								)
							)
						)
					),
					'button_letter_spacing' => array(
						'type'        => 'text',
						'label' => esc_html__( 'Letter spacing', 'themeisle-companion' ),
						'description' => esc_html__('px','themeisle-companion'),
						'maxlength'     => '3',
						'size'          => '4',
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '.obfx-plan-button',
									'property'     => 'letter-spacing',
									'unit' => 'px',
								)
							)
						)
					),
				),
			),
		)
	)
));


FLBuilder::register_settings_form('feature_field', array(
	'title' => __('Feature', 'themeisle-companion'),
	'tabs'  => array(
		'general'      => array(
			'title'         => esc_html__('General', 'themeisle-companion'),
			'sections'      => array(
				'general'       => array(
					'title'         => '',
					'fields'        => array(
						'bold_text' => array(
							'type'  => 'text',
							'label' => esc_html__('Bold text', 'themeisle-companion')
						),
						'text' => array(
							'type'  => 'text',
							'label' => esc_html__('Text', 'themeisle-companion')
						),
						'icon' => array(
							'type'          => 'icon',
							'label'         => esc_html__( 'Icon', 'themeisle-companion' ),
							'show_remove'   => true
						),
					)
				),
			)
		)
	)
));