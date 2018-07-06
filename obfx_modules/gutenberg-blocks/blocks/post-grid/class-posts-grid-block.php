<?php

namespace OrbitFox\Gutenberg_Blocks;

class Posts_Grid_Block extends Base_Block {

	public function __construct() {
		parent::__construct();
	}

	function set_block_slug() {
		$this->block_slug = 'posts-grid';
	}

	function set_attributes() {
		$this->attributes = array(
			'categories'           => array(
				'type' => 'string',
			),
			'className'            => array(
				'type' => 'string',
			),
			'postsToShow'          => array(
				'type'    => 'number',
				'default' => 5,
			),
			'displayFeaturedImage' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'displayPostDate'      => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'postLayout'           => array(
				'type'    => 'string',
				'default' => 'list',
			),
			'columns'              => array(
				'type'    => 'number',
				'default' => 3,
			),
			'align'                => array(
				'type'    => 'string',
				'default' => 'center',
			),
			'order'                => array(
				'type'    => 'string',
				'default' => 'desc',
			),
			'orderBy'              => array(
				'type'    => 'string',
				'default' => 'date',
			),
		);
	}

	/**
	 *
	 * @param $attributes
	 *
	 * @return mixed|string
	 */
	function render( $attributes ) {
		$recent_posts = wp_get_recent_posts( array(
			'numberposts' => $attributes['postsToShow'],
			'post_status' => 'publish',
			'order'       => $attributes['order'],
			'orderby'     => $attributes['orderBy'],
			'category'    => $attributes['categories'],
		) );

		$list_items_markup = '';

		foreach ( $recent_posts as $post ) {
			$post_id = $post['ID'];

			$title = get_the_title( $post_id );
			if ( ! $title ) {
				$title = __( '(Untitled)', 'gutenberg' );
			}
			$list_items_markup .= sprintf(
				'<li><a href="%1$s">%2$s</a>',
				esc_url( get_permalink( $post_id ) ),
				esc_html( $title )
			);

			if ( isset( $attributes['displayPostDate'] ) && $attributes['displayPostDate'] ) {
				$list_items_markup .= sprintf(
					'<time datetime="%1$s" class="wp-block-latest-posts__post-date">%2$s</time>',
					esc_attr( get_the_date( 'c', $post_id ) ),
					esc_html( get_the_date( '', $post_id ) )
				);
			}

			$list_items_markup .= "</li>\n";
		}

		$class = "wp-block-latest-posts align{$attributes['align']}";
		if ( isset( $attributes['postLayout'] ) && 'grid' === $attributes['postLayout'] ) {
			$class .= ' is-grid';
		}

		if ( isset( $attributes['columns'] ) && 'grid' === $attributes['postLayout'] ) {
			$class .= ' columns-' . $attributes['columns'];
		}

		if ( isset( $attributes['className'] ) ) {
			$class .= ' ' . $attributes['className'];
		}

		$block_content = sprintf(
			'<ul class="%1$s">%2$s</ul>',
			esc_attr( $class ),
			$list_items_markup
		);

		return $block_content;
	}
}