<?php

namespace OrbitFox\Gutenberg_Blocks;
	
class Plugin_Card_Block extends Base_Block {

	public function __construct() {
		parent::__construct();
	}

	function set_block_slug() {
		$this->block_slug = 'plugin-cards';
	}

	function set_attributes() {
		$this->attributes = array(
			'slug' => array(
				'type' => 'string',
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
		$results = $this->search( $attributes['slug'] );
			
		if ( ! is_wp_error( $results['data'] ) ) {
			$results = $results['data'];
	
			$icon = '';
			if ( $results->icons['svg'] ) {
				$icon = $results->icons['svg'];
			} if ( $results->icons['2x'] ) {
				$icon = $results->icons['2x'];
			} if ( $results->icons['1x'] ) {
				$icon = $results->icons['1x'];
			} if ( $results->icons['default'] ) {
				$icon = $results->icons['default'];
			}
	
			$markup = '<div class="wp-block-orbitfox-plugin-cards">
				<div class="obfx-plugin-card">
					<div class="card-header">
						<div class="card-main">
							<div class="card-logo">
								<img src="' . $icon . '" alt="' . $results->name . '" title="' . $results->name . '"/>
							</div>
							<div class="card-info">
								<h4>' . $results->name . '</h4>
								<h5>' . $results->author . '</h5>
							</div>
							<div class="card-ratings">
								' . $this->get_ratings( $results->rating ) . '
							</div>
						</div>
					</div>
					<div class="card-details">
						<div class="card-description">' . $results->short_description . '</div>
						<div class="card-stats">
							<h5>' . __( 'Plugin Stats', 'themeisle-companion' ) . '</h5>
							<div class="card-stats-list">
								<div class="card-stat">
									<span class="card-text-large">' . number_format( $results->active_installs ) . '+</span>
									' . __( 'active installs', 'themeisle-companion' ) . '
								</div>
								<div class="card-stat">
									<span class="card-text-large">' . $results->version . '+</span>
									' . __( 'version', 'themeisle-companion' ) . '
								</div>
								<div class="card-stat">
									<span class="card-text-large">' . $results->tested . '+</span>
									' . __( 'tested up to', 'themeisle-companion' ) . '
								</div>
							</div>
						</div>
					</div>
					<div class="card-download">
						<a href="' . $results->download_link . '">' . __( 'Download', 'themeisle-companion' ) . '</a>
					</div>
				</div>
			</div>';
			
			return $markup;
		}

	}

	/**
	 * @param $request
	 *
	 * @return mixed
	 */
	function search( $request ) {
		$return = array(
			'success' => false,
			'data'     => esc_html__( 'Something went wrong', 'themeisle-companion' )
		);

		$slug = $request;

		require_once( ABSPATH . "wp-admin" . '/includes/plugin-install.php' );

		$request = array(
			'per_page' => 12,
			'slug' => $slug,
			'fields' => array(
				'active_installs' => true,
				'added' => false,
				'donate_link' => false,
				'downloadlink' => true,
				'homepage' => true,
				'icons' => true,
				'last_updated' => false,
				'requires' => true,
				'requires_php' => false,
				'screenshots' => false,
				'short_description' => true,
				'slug' => false,
				'sections' => false,
				'requires' => false,
				'rating' => true,
				'ratings' => false,
			)
		);

		$results = plugins_api( 'plugin_information', $request );

		if ( is_wp_error( $request ) ) {
			$return['data'] = 'error';
			return $return;
		}

		$return['success'] = true;

		// Get data from API
		$return['data'] = $results;

		return $return;
	}

	function get_ratings( $rating ) {
		$rating = round( $rating / 10, 0 ) / 2;
		$full_stars = floor( $rating );
		$half_stars = ceil( $rating - $full_stars );
		$empty_stars = 5 - $full_stars - $half_stars;
		$output = str_repeat( '<span class="star-full"></span>', $full_stars );
		$output .= str_repeat( '<span class="star-half"></span>', $half_stars );
		$output .= str_repeat( '<span class="star-empty"></span>', $empty_stars );
		return $output;
	}
}