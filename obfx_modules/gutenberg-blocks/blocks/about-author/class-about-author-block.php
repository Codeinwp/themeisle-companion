<?php
namespace OrbitFox\Gutenberg_Blocks;

class About_Author_Block extends Base_Block {

	public function __construct() {
		parent::__construct();
	}

	function set_block_slug() {
		$this->block_slug = 'about-author';
	}

	function set_attributes(){
		$this->attributes = array();
	}

	/**
	 * @TODO look for a semantic HTML markup and build a nice About Author box.
	 *
	 * @param $attributes
	 *
	 * @return mixed|string
	 */
	function render( $attributes ) {
		$img_markup = sprintf(
			'<img src="%1$s" class="%2$s" />',
			esc_attr( get_avatar_url( get_the_author_meta( 'ID' ) ) ),
			'author_image'
		);

		$title_markup = sprintf(
			'<h2>%1$s</h2>',
			esc_html( get_the_author_meta( 'display_name' ) )
		);

		$content_markup = sprintf(
			'<p>%1$s%2$s</p>',
			$img_markup,
			esc_html( strip_tags( get_the_author_meta( 'description' ) ) )
		);

		$class     = "blocks-single-author";
		return sprintf(
			'<section class="%1$s">%2$s%3$s</section>',
			esc_attr( $class ),
			$title_markup,
			$content_markup
		);

	}
}