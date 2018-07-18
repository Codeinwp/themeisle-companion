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
			'displayReadMore'      => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'displayExcerpt'      => array(
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
			'readMoreLabel'        => array(
				'type' => 'string',
				'default' => esc_html__( 'Read More ...', 'textdomain' )
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

		$list_items_markup = $featured_image_markup = '';

		foreach ( $recent_posts as $post ) {
			$post_id = $post['ID'];
			$excerpt = $readMoreMarkup = '';

			if ( isset( $attributes['displayFeaturedImage'] ) && $attributes['displayFeaturedImage'] ) {
				$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(  $post['ID'] ) );

				$featured_image_markup = sprintf(
					'<div class="post-thumbnail"><img src="%1$s" alt="" /></div><!-- .post-thumbnail -->',
					esc_url( $thumbnail[0] )
				);
			}

			if ( isset( $attributes['displayExcerpt'] ) && $attributes['displayExcerpt'] ) {
				$excerpt = $this->get_gutenberg_excerpt( $post );
			}

			$title = get_the_title( $post_id );

			if ( ! $title ) {
				$title = __( '(Untitled)', 'gutenberg' );
			}

			if ( isset( $attributes['displayReadMore'] ) && $attributes['displayReadMore'] ) {
				$readMoreMarkup = sprintf(
					'<a href="%1$s">%2$s</a>',
					get_the_permalink(),
					$attributes['readMoreLabel']
				);
			}

			$list_items_markup .= sprintf(
				'<li><a href="%1$s">%2$s</a>%3$s %4$s',
				esc_url( get_permalink( $post_id ) ),
				esc_html( $title ),
				$featured_image_markup,
				$excerpt,
				$readMoreMarkup
			);

			if ( isset( $attributes['displayPostDate'] ) && $attributes['displayPostDate'] ) {
				$list_items_markup .= sprintf(
					'<time datetime="%1$s" class="wp-block-posts-grid__post-date">%2$s</time>',
					esc_attr( get_the_date( 'c', $post_id ) ),
					esc_html( get_the_date( '', $post_id ) )
				);
			}

			$list_items_markup .= "</li>\n";
		}

		$class = "wp-block-posts-grid align{$attributes['align']}";
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

	function get_gutenberg_excerpt( $post, $words_length = 24 ){

		if ( has_excerpt( $post ) ) {
			return get_the_excerpt( $post['ID'] );
		}

		$blocks = gutenberg_parse_blocks( $post['post_content'] );

		$output = '';

		foreach ( $blocks as $block ) {
			if ( isset( $block['blockName'] )
			     && ( $block['blockName'] === 'core/paragraph' || $block['blockName'] === 'core/heading' ) ) {
				$output .= $block['innerHTML'];
			}
		}

		return wp_trim_words( $output, $words_length );
	}
}