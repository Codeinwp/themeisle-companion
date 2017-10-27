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
		return __( 'Post Type Grid', 'themeisle-companion' );
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
	 * Widget Category.
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'obfx-elementor-widgets' ];
	}

	/**
	 * Get post types.
	 */
	private function grid_get_all_post_types() {
		$options = array();
		$exclude = array( 'attachment', 'elementor_library' ); // excluded post types

		$args = array(
			'public' => true,
		);

		foreach ( get_post_types( $args, 'objects' ) as $post_type ) {
			// Check if post type name exists.
			if ( ! isset( $post_type->name ) ) {
				continue;
			}

			// Check if post type label exists.
			if ( ! isset( $post_type->label ) ) {
				continue;
			}

			// Check if post type is excluded.
			if ( in_array( $post_type->name, $exclude ) === true ) {
				continue;
			}

			$options[$post_type->name] = $post_type->label;
		}

		return $options;
	}

	/**
	 * Register Elementor Controls.
	 */
	protected function _register_controls() {
	    // Content.
		$this->grid_options_section();
		$this->grid_image_section();
		$this->grid_title_section();
		$this->grid_meta_section();
		$this->grid_content_section();
		// Style.
		$this->grid_options_style_section();
		$this->grid_image_style_section();
	}

	/**
	 * Content > Grid.
	 */
	private function grid_options_section() {
		$this->start_controls_section(
			'section_grid',
			[
				'label' => __( 'Grid Options', 'themeisle-companion' ),
			]

		);

		// Post type.
		$this->add_control(
			'grid_post_type',
			[
				'type'    => Controls_Manager::SELECT,
				'label'   => '<i class="fa fa-tag"></i> ' . __( 'Post Type', 'themeisle-companion' ),
				'default' => 'post',
				'options' => $this->grid_get_all_post_types(),
			]
		);

		// Items.
		$this->add_control(
			'grid_items',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => '<i class="fa fa-th-large"></i> ' . __( 'Items', 'themeisle-companion' ),
				'placeholder' => __( 'How many items?', 'themeisle-companion' ),
				'default'     => __( '3', 'themeisle-companion' ),
			]
		);

		// Columns.
		$this->add_responsive_control(
			'grid_columns',
			[
				'type'        => Controls_Manager::SELECT,
				'label'       => '<i class="fa fa-columns"></i> ' . __( 'Columns', 'themeisle-companion' ),
				'default' => 3,
                'tablet_default' => 2,
                'mobile_default'=> 1,
				'options' => [
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
				],
			]
		);

		// Order by.
		$this->add_control(
			'grid_order_by',
			[
				'type'    => Controls_Manager::SELECT,
				'label'   => '<i class="fa fa-sort"></i> ' . __( 'Order by', 'themeisle-companion' ),
				'default' => 'date',
				'options' => [
					'date'          => __( 'Date', 'themeisle-companion' ),
					'title'         => __( 'Title', 'themeisle-companion' ),
					'modified'      => __( 'Modified date', 'themeisle-companion' ),
					'comment_count' => __( 'Comment count', 'themeisle-companion' ),
					'rand'          => __( 'Random', 'themeisle-companion' ),
				],
			]
		);

		// Order.
		$this->add_control(
			'grid_order',
			[
				'type'    => Controls_Manager::SELECT,
				'label'   => '<i class="fa fa-sort-numeric-desc"></i> ' . __( 'Order', 'themeisle-companion' ),
				'default' => 'DESC',
				'options' => [
					'ASC'  => __( 'Ascendent', 'themeisle-companion' ),
					'DESC' => __( 'Descendent', 'themeisle-companion' ),
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content > Image Options.
	 */
	private function grid_image_section() {
		$this->start_controls_section(
			'section_grid_image',
			[
				'label' => __( 'Image', 'themeisle-companion' ),
			]

		);

		// Hide image.
		$this->add_control(
			'grid_image_hide',
			[
				'label' => '<i class="fa fa-minus-circle"></i> ' . __( 'Hide', 'themeisle-companion' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->add_responsive_control(
			'grid_image_ratio',
			[
				'label' => __( 'Image Ratio', 'themeisle-companion' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.66,
				],
				'tablet_default' => [
					'size' => '',
				],
				'mobile_default' => [
					'size' => 0.5,
				],
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 2,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .obfx-grid-item-image' => 'padding-bottom: calc( {{SIZE}} * 100% );',
				],
			]
		);

		$this->add_responsive_control(
			'grid_image_width',
			[
				'label' => __( 'Image Width', 'themeisle-companion' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 10,
						'max' => 600,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => '',
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'size_units' => [ '%', 'px' ],
				'selectors' => [
					'{{WRAPPER}} .obfx-grid-item-image' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Image link.
		$this->add_control(
			'grid_image_link',
			[
				'label' => '<i class="fa fa-link"></i> ' . __( 'Link', 'themeisle-companion' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content > Title Options.
	 */
	private function grid_title_section() {
		$this->start_controls_section(
			'section_grid_title',
			[
				'label'     => __( 'Title', 'themeisle-companion' ),
			]

		);

		// Hide title.
		$this->add_control(
			'grid_title_hide',
			[
				'label' => '<i class="fa fa-minus-circle"></i> ' . __( 'Hide', 'themeisle-companion' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		// Title tag.
		$this->add_control(
			'grid_title_tag',
			[
				'type'    => Controls_Manager::SELECT,
				'label'   => '<i class="fa fa-code"></i> ' . __( 'Tag', 'themeisle-companion' ),
				'default' => 'h2',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'Hh6',
                    'span' => 'span',
					'p' => 'p',
                    'div' => 'div',
				],
			]
		);

		// Title link.
		$this->add_control(
			'grid_title_link',
			[
				'label' => '<i class="fa fa-link"></i> ' . __( 'Link', 'themeisle-companion' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content > Meta Options.
	 */
	private function grid_meta_section() {
		$this->start_controls_section(
			'section_grid_meta',
			[
				'label'     => __( 'Meta', 'themeisle-companion' ),
			]

		);

		// Hide content.
		$this->add_control(
			'grid_meta_hide',
			[
				'label' => '<i class="fa fa-minus-circle"></i> ' . __( 'Hide', 'themeisle-companion' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		// Meta.
		$this->add_control(
			'grid_meta_display',
			[
				'label' => '<i class="fa fa-info-circle"></i> ' . __( 'Display', 'themeisle-companion' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT2,
				'default' => [ 'author', 'date' ],
				'multiple' => true,
				'options' => [
					'author' => __( 'Author', 'themeisle-companion' ),
					'date' => __( 'Date', 'themeisle-companion' ),
					'category' => __( 'Category', 'themeisle-companion' ),
					'tags' => __( 'Tags', 'themeisle-companion' ),
					'comments' => __( 'Comments', 'themeisle-companion' ),
				],
			]
		);

		// No. of Categories.
		$this->add_control(
			'grid_meta_categories_max',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => __( 'No. of Categories', 'themeisle-companion' ),
				'placeholder' => __( 'How many categories to display?', 'themeisle-companion' ),
				'default'     => __( '1', 'themeisle-companion' ),
				'condition' => [
					'grid_meta_display' => 'category',
				],
			]
		);

		// No. of Tags.
		$this->add_control(
			'grid_meta_tags_max',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => __( 'No. of Tags', 'themeisle-companion' ),
				'placeholder' => __( 'How many tags to display?', 'themeisle-companion' ),
				'condition' => [
					'grid_meta_display' => 'tags',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content > Content Options.
	 */
	private function grid_content_section() {
		$this->start_controls_section(
			'section_grid_content',
			[
				'label'     => __( 'Content', 'themeisle-companion' ),
			]

		);

		// Hide content.
		$this->add_control(
			'grid_content_hide',
			[
				'label' => '<i class="fa fa-minus-circle"></i> ' . __( 'Hide', 'themeisle-companion' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		// Length.
		$this->add_control(
			'grid_content_length',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => '<i class="fa fa-arrows-h"></i> ' . __( 'Length (words)', 'themeisle-companion' ),
				'placeholder' => __( 'Length of content (words)', 'themeisle-companion' ),
				'default'     => __( '55', 'themeisle-companion' ),
			]
		);

		// Price.
		$this->add_control(
			'grid_content_price',
			[
				'label' => '<i class="fa fa-usd"></i> ' . __( 'Price', 'themeisle-companion' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'section_grid.grid_post_type' => 'product',
				],
			]
		);

		// Read more button hide.
		$this->add_control(
			'grid_content_default_btn',
			[
				'label' => '<i class="fa fa-check-square"></i> ' . __( 'Button', 'themeisle-companion' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'condition' => [
					'section_grid.grid_post_type!' => 'product',
				],
			]
		);

		// Default button text.
		$this->add_control(
			'grid_content_default_btn_text',
			[
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Button text', 'themeisle-companion' ),
				'placeholder' => __( 'Read more', 'themeisle-companion'),
				'default'     => __( 'Read more', 'themeisle-companion'),
				'condition' => [
                    'grid_content_default_btn!' => '',
					'section_grid.grid_post_type!' => 'product',
				],
			]
		);

		// Add to cart button hide.
		$this->add_control(
			'grid_content_product_btn',
			[
				'label' => '<i class="fa fa-check-square"></i> ' . __( 'Button', 'themeisle-companion' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'section_grid.grid_post_type' => 'product',
				],
			]
		);

		// Button alignment.
		$this->add_responsive_control(
			'grid_content_btn_alignment',
			[
				'label'     => __( 'Button alignment', 'themeisle-companion' ),
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
					'justify' => [
						'title' => __( 'Justified', 'themeisle-companion' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'default'   => 'left',
				'tablet_default'   => 'left',
				'mobile_default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .obfx-grid-item-btn' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'grid_content_btn!' => '',
				],
			]
		);

		// Content alignment.
		$this->add_responsive_control(
			'grid_content_alignment',
			[
				'label'     => '<i class="fa fa-align-right"></i> ' . __( 'Alignment', 'themeisle-companion' ),
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
				'default'   => 'left',
				'tablet_default'   => 'left',
				'mobile_default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .obfx-grid-col' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Grid options.
	 */
	private function grid_options_style_section() {
	    // Tab.
		$this->start_controls_section(
			'section_grid_style',
			[
				'label' => __( 'Grid Options', 'themeisle-companion' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Columns margin.
		$this->add_control(
			'grid_style_columns_margin',
			[
				'label'     => __( 'Columns margin', 'elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 15,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .obfx-grid-col' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .obfx-grid-container' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		// Row margin.
		$this->add_control(
			'grid_style_rows_margin',
			[
				'label'     => __( 'Rows margin', 'elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 30,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .obfx-grid-col' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Background.
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'grid_style_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .obfx-grid',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Image.
	 */
	private function grid_image_style_section() {
		// Tab.
		$this->start_controls_section(
			'section_grid_image_style',
			[
				'label' => __( 'Image', 'themeisle-companion' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'grid_image_style_border_radius',
			[
				'label'      => __( 'Border Radius', 'themeisle-companion' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .obfx-grid-item-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'grid_image_style_spacing',
			[
				'label'     => __( 'Bottom space', 'themeisle-companion' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-posts--thumbnail-left .elementor-post__thumbnail__link'  => 'margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-posts--thumbnail-right .elementor-post__thumbnail__link' => 'margin-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-posts--thumbnail-top .elementor-post__thumbnail__link'   => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'default'   => [
					'size' => 20,
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Display categories in meta section.
	 */
	protected function metaGridCategories() {
		$settings = $this->get_settings();
		$post_type_category = get_the_category();
		$maxCategories = $settings['grid_meta_categories_max'] ? $settings['grid_meta_categories_max'] : '-1';
		$i = 0; // counter

		if ( $post_type_category ) { ?>
			<span class="obfx-grid-col-categories">
                <i class="fa fa-bookmark"></i>
                <?php foreach ( $post_type_category as $category ) {
                    if ( $i == $maxCategories ) break;
                    ?>
                    <span class="obfx-grid-col-categories-item"><a href="<?php echo get_category_link( $category->term_id ); ?>" title="<?php echo $category->name; ?>"><?php echo $category->name; ?></a></span>
                    <?php
                    $i++;
                } ?>
            </span>
		<?php
	    }
    }

	/**
	 * Display tags in meta section.
	 */
	protected function metaGridTags() {
		$settings = $this->get_settings();
		$post_type_tags = get_the_tags();
		$maxTags = $settings['grid_meta_tags_max'] ? $settings['grid_meta_tags_max'] : '-1';
		$i = 0; // counter

		if ( $post_type_tags ) { ?>
            <span class="obfx-grid-col-tags">
                <i class="fa fa-tags"></i>
				<?php foreach ( $post_type_tags as $tag ) {
					if ( $i == $maxTags )  break;
					?>
                    <span class="obfx-grid-col-tags-item"><a href="<?php echo get_tag_link( $tag->term_id ); ?>" title="<?php echo $tag->name; ?>"><?php echo $tag->name; ?></a></span>
					<?php
					$i++;
				} ?>
            </span>
			<?php
		}
	}

	/**
	 * Render image of post type.
	 */
	protected function renderImage() {
		$settings = $this->get_settings();

		if ( $settings['grid_image_hide'] !== 'yes' ) {
			// Check if post type has featured image.
			if ( has_post_thumbnail() ) { ?>

				<?php if ( $settings['grid_image_link'] == 'yes' ) { ?>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="obfx-grid-item-image"><?php the_post_thumbnail(); ?></a>
				<?php } else { ?>
                    <div class="obfx-grid-item-image"><?php the_post_thumbnail(); ?></div>
				<?php }
			}
		}
    }

	/**
	 * Render title of post type.
	 */
	protected function renderTitle() {
		$settings = $this->get_settings();

		if ( $settings['grid_title_hide'] !== 'yes' ) { ?>
            <<?php echo $settings['grid_title_tag']; ?> class="obfx-grid-item-title">
			<?php if ( $settings['grid_title_link'] == 'yes' ) { ?>
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title();
					?></a>
			<?php } else {
				the_title();
			} ?>
            </<?php echo $settings['grid_title_tag']; ?>>
		<?php }
    }

	/**
	 * Render meta of post type.
	 */
	protected function renderMeta() {
		$settings = $this->get_settings();

		if ( $settings['grid_meta_hide'] !== 'yes' ) {
		    if ( ! empty( $settings['grid_meta_display'] ) ) { ?>
                <div class="obfx-grid-col-meta">

		        <?php foreach ( $settings['grid_meta_display'] as $meta ) {

		            switch ( $meta ):
                        // Author
                        case 'author': ?>
                            <span class="obfx-grid-col-author">
                                <i class="fa fa-user"></i>
                                <?php echo get_the_author(); ?>
                            </span>
                        <?php
                        // Date
                        break; case 'date' : ?>
                            <span class="obfx-grid-col-date">
                                <i class="fa fa-calendar"></i>
                                <?php echo get_the_date(); ?>
                            </span>
			            <?php
                        // Category
                        break; case 'category' :
			                $this->metaGridCategories();

                        // Tags
			            break; case 'tags' :
			                $this->metaGridTags();

                        // Comments/Reviews
                        break; case 'comments' : ?>
                            <span class="obfx-grid-col-comments">
                                <i class="fa fa-comment"></i>
                                <?php
                                if ( $settings['grid_post_type'] == 'product' ) {
	                                echo comments_number( __( 'No reviews','themeisle-companion' ), __( '1 review','themeisle-companion' ), __( '% reviews','themeisle-companion' ));
                                } else {
	                                echo comments_number( __( 'No comments','themeisle-companion' ), __( '1 comment','themeisle-companion' ), __( '% comments','themeisle-companion' ));
                                }
                                ?>
                            </span>
                        <?php
                        break; endswitch;
			    } // end foreach ?>

            </div>
            <?php
		    }
		}
	}

	/**
	 * Display price if post type is product.
	 */
    protected function renderPrice() {
        $settings = $this->get_settings();
        $product = wc_get_product( get_the_ID() );

        if ( $settings['grid_post_type'] == 'product' && $settings['grid_content_price'] == 'yes' ) { ?>
            <div class="obfx-grid-col-price">
                <?php
					$price = $product->get_price_html();
					if ( ! empty( $price ) ) {
						echo wp_kses( $price, array(
							'span' => array(
								'class' => array(),
							),
							'del' => array(),
						) );
					}
                ?>
            </div>
        <?php
        }
    }

	/**
	 * Display Add to Cart button.
	 */
	protected function renderAddToCart() {
		$product = wc_get_product( get_the_ID() );

		echo apply_filters( 'woocommerce_loop_add_to_cart_link',
			sprintf( '<a href="%s" title="%s" rel="nofollow" class="button"><i class="fa fa-cart-plus"></i> %s</a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( $product->add_to_cart_text() ),
				esc_html( $product->add_to_cart_text() )
			), $product );
	}

	/**
	 * Render content of post type.
	 */
	protected function renderContent() {
		$settings = $this->get_settings();

		if ( $settings['grid_content_hide'] !== 'yes' ) { ?>
            <div class="obfx-grid-col-content">
                <?php if ( empty ( $settings['grid_content_length'] ) ) {
	                the_excerpt();
                } else {
                    echo wp_trim_words( get_the_excerpt(),$settings['grid_content_length'] );
                }
                ?>
            </div>
		<?php }
    }

	/**
	 * Render button of post type.
	 */
	protected function renderButton() {
		$settings = $this->get_settings();

		if ( $settings['grid_post_type'] == 'product' && $settings['grid_content_product_btn'] == 'yes' && ! empty ( $settings['grid_content_product_btn_text'] ) ) {
            $this->renderAddToCart();
		} else { ?>
            <div class="obfx-grid-col-footer">
                <a href="<?php echo get_the_permalink(); ?>" title="<?php echo $settings['grid_content_default_btn_text']; ?>"><?php echo $settings['grid_content_default_btn_text']; ?></a>
            </div>
        <?php
		}
    }

	/**
	 * Render function to output the post type grid.
	 */
	protected function render() {
        // Get settings.
		$settings = $this->get_settings();

		// Output.
		echo '<div class="obfx-grid">';
		echo '<div class="obfx-grid-container' . ( ! empty( $settings['grid_columns_mobile'] ) ? ' obfx-grid-mobile-' . $settings['grid_columns_mobile'] : '' ) . ( ! empty( $settings['grid_columns_tablet'] ) ? ' obfx-grid-tablet-' . $settings['grid_columns_tablet'] : '' ) . ( ! empty( $settings['grid_columns'] ) ? ' obfx-grid-desktop-' . $settings['grid_columns'] : '' ) .'">';

		// Arguments for query.
		$args = array();

		// Check if post type exists.
		if ( ! empty( $settings['grid_post_type'] ) && post_type_exists( $settings['grid_post_type'] ) ) {
		    $args['post_type'] = $settings['grid_post_type'];
		}

		// Items to display.
		if ( ! empty( $settings['grid_items'] ) &&  intval( $settings['grid_items'] ) == $settings['grid_items']  ) {
			$args['posts_per_page'] = $settings['grid_items'];
		}

		// Order by.
		if ( ! empty( $settings['grid_order_by'] ) ) {
			$args['orderby'] = $settings['grid_order_by'];
		}

		// Order.
		if ( ! empty( $settings['grid_order'] ) ) {
			$args['order'] = $settings['grid_order'];
		}

        // Query.
        $query = new \WP_Query( $args );

        // Query results.
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();

	            echo '<div class="obfx-grid-col">';

                // Image.
	            $this->renderImage();

                // Title.
                $this->renderTitle();

                // Meta.
                $this->renderMeta();

	            // Price.
	            if ( class_exists( 'WooCommerce' ) ) {
		            $this->renderPrice();
	            }

                // Content.
                $this->renderContent();

                // Button.
                $this->renderButton();

                echo '</div>';

            } // end while;
        } // end if;

        // Restore original data.
        wp_reset_postdata();

		echo '</div><!-- .obfx-grid-container -->';
		echo '</div><!-- .obfx-grid -->';
	}
}

