<?php

namespace OrbitFox\Gutenberg_Blocks;

class Share_Icons_Block extends Base_Block {

	protected $social_attributes = array();

	public function __construct() {
		parent::__construct();
	}

	function set_block_slug() {
		$this->block_slug = 'share-icons';
	}

	function set_attributes() {
		global $post;

		if ( has_post_thumbnail() ) {
			$thumbnail_id = get_post_thumbnail_id( $post->ID );
			$thumbnail    = $thumbnail_id ? current( wp_get_attachment_image_src( $thumbnail_id, 'large', true ) ) : '';
		} else {
			$thumbnail = null;
		}

		$this->social_attributes = array(
			// @TODO try to add the feature image for share. guess it suppose to work with open-graph or smth.
			'facebook' => array(
				'label'   => esc_html__( 'Facebook' ),
				'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . get_the_permalink() . '&title=' . get_the_title(),
			),

			'twitter' => array(
				'label'   => esc_html__( 'Twitter' ),
				'url' => 'http://twitter.com/share?url=' . get_the_permalink() . '&text=' . get_the_title(),
			),

			'pinterest' => array(
				'label'   => esc_html__( 'Pinterest' ),
				'url' => 'https://pinterest.com/pin/create/button/?url=' . get_the_permalink() . '&description=' . get_the_title() . '&media='.esc_url( $thumbnail ),
			),

			'tumblr' => array(
				'label'   => esc_html__( 'Tumblr' ),
				'url' => 'https://tumblr.com/share/link?url=' . get_the_permalink() . '&name=' . get_the_title(),
			),
			'linkedin' => array(
				'label'   => esc_html__( 'Linkedin' ),
				'url' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . get_the_permalink() . '&title=' . get_the_title(),
			),
		);

		$this->attributes = array(
			'layout'         => array(
				'type'    => 'string',
				'default' => 'icons'
			),
			'show_facebook'  => array(
				'type'    => 'boolean',
				'default' => true
			),
			'show_twitter'   => array(
				'type'    => 'boolean',
				'default' => true
			),
			'show_pinterest' => array(
				'type'    => 'boolean',
				'default' => true
			),
			'show_tumblr' => array(
				'type'    => 'boolean',
				'default' => false
			),
			'show_linkedin' => array(
				'type'    => 'boolean',
				'default' => false
			)
		);
	}

	/**
	 *
	 * @param $attributes
	 *
	 * @return mixed|string
	 */
	function render( $attributes ) {
		$layout = $attributes['layout'];

		$html = '<div class="obfx-social-icons">';

		foreach ( $attributes as $key => $attribute ) {
			if ( strpos( $key, 'show_' ) !== 0 ) {
				continue;
			}

			$name = str_replace( 'show_', '', $key );
			$html .= $this->get_social_link( $layout, $name, $this->social_attributes[$name]['label'], $this->social_attributes[$name]['url'] );
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Get the html of a specific sharing icon
	 *
	 * @param $layout The layout of the icon
	 * @param $name string The social platform key
	 * @param $label string The social platform label
	 * @param $url string The url which should be clicked
	 *
	 * @return string
	 */
	function get_social_link( $layout, $name, $label, $url ) {
		$inner_html = '';

		switch ( $layout ) {
			case 'text_and_icons':
				$inner_html = sprintf(
					'<span>%1$s <i class="fa fa-%2$s"></i></span>',
					$label,
					$name
				);
				break;
			case 'icons_and_text':
				$inner_html = sprintf(
					'<span><i class="fa fa-%2$s"></i> %1$s</span>',
					$label,
					$name
				);
				break;
			case 'text':
				$inner_html = sprintf(
					'<span>%1$s</span>',
					$name
				);
				break;
			case 'icon':
			default :
				$inner_html = sprintf(
					'<i class="fa fa-%1$s"></i>',
					$name
				);
				break;
		}

		return sprintf(
			'<a href="%1$s" title="%2$s %3$s">%4$s</a>',
			$url,
			esc_html__( 'Share on', 'textdomain' ),
			$label,
			$inner_html
		);
	}
}