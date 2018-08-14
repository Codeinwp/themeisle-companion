<?php

namespace OrbitFox\Gutenberg_Blocks;

class Sharing_Icons_Block extends Base_Block {

	protected $social_attributes = array();

	public function __construct() {
		parent::__construct();
	}

	function set_block_slug() {
		$this->block_slug = 'sharing-icons';
	}

	function set_attributes() {
		$this->social_attributes = array(
			'facebook' => array(
				'label'   => esc_html__( 'Facebook' ),
				'icon' => 'facebook-f',
				'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . get_the_permalink() . '&title=' . get_the_title(),
			),

			'twitter' => array(
				'label'   => esc_html__( 'Twitter' ),
				'icon' => 'twitter',
				'url' => 'http://twitter.com/share?url=' . get_the_permalink() . '&text=' . get_the_title(),
			),

			'googleplus' => array(
				'label'   => esc_html__( 'Google Plus' ),
				'icon' => 'google-plus-g',
				'url' => 'https://plus.google.com/share?url=' . get_the_permalink() . '&text=' . get_the_title(),
			),

			'linkedin' => array(
				'label'   => esc_html__( 'Linkedin' ),
				'icon' => 'linkedin-in',
				'url' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . get_the_permalink() . '&title=' . get_the_title(),
			),

			'pinterest' => array(
				'label'   => esc_html__( 'Pinterest' ),
				'icon' => 'pinterest-p',
				'url' => 'https://pinterest.com/pin/create/button/?url=' . get_the_permalink() . '&description=' . get_the_title(),
			),

			'tumblr' => array(
				'label'   => esc_html__( 'Tumblr' ),
				'icon' => 'tumblr',
				'url' => 'https://tumblr.com/share/link?url=' . get_the_permalink() . '&name=' . get_the_title(),
			),

			'reddit' => array(
				'label'   => esc_html__( 'Reddit' ),
				'icon' => 'reddit-alien',
				'url' => 'https://www.reddit.com/submit?url=' . get_the_permalink(),
			),
		);

		$this->attributes = array(
			'facebook'  => array(
				'type'    => 'boolean',
				'default' => 1
			),
			'twitter'  => array(
				'type'    => 'boolean',
				'default' => 1
			),
			'googleplus'  => array(
				'type'    => 'boolean',
				'default' => 1
			),
			'linkedin'  => array(
				'type'    => 'boolean',
				'default' => 1
			),
			'pinterest'  => array(
				'type'    => 'boolean',
				'default' => 0
			),
			'tumblr'  => array(
				'type'    => 'boolean',
				'default' => 0
			),
			'reddit'  => array(
				'type'    => 'boolean',
				'default' => 0
			),
			'className'  => array(
				'type'    => 'string',
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
		if ( strpos( $attributes[ 'className' ], 'is-style-icons' ) ) {
			$class = "obfx-social-icons";
		} else {
			$class = "obfx-social-icons has-label";
		}
		$html = '<div class="'. $class .'">';
		foreach( $this->attributes as $key => $icon ) {
			if ( $key !== 'className' && $attributes[ $key ] == 1 ) {
				$html .= '<a class="social-icon is-'. $key .'" href="'. $this->social_attributes[$key]['url'] .'" target="_blank">';
				$html .= '<i class="fab fa-'. $this->social_attributes[$key]['icon'] .'"></i>';
				if ( ! strpos( $attributes[ 'className' ], 'is-style-icons' ) ) {
					$html .= $this->social_attributes[$key]['label'];
				}
				$html .= '</a>';
			}
		}
		$html .= '</div>';
		return $html;
	}
}