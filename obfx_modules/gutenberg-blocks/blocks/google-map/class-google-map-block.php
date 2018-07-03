<?php

namespace OrbitFox\Gutenberg_Blocks;

class Google_Map_Block extends Base_Block {

	public function __construct() {
		parent::__construct();
		add_action( 'init', array( $this, 'registerSettings' ) );
	}

	function set_block_slug() {
		$this->block_slug = 'google-map';
	}

	function set_attributes() {
		$this->attributes = array(
			'location'    => array(
				'type'    => 'string',
				'default' => '',
			),
			'mapType'     => array(
				'type'    => 'string',
				'default' => 'roadmap',
			),
			'zoom'        => array(
				'type'    => 'number',
				'default' => 13,
			),
			'maxWidth'    => array(
				'type'    => 'number',
				'default' => 1920,
			),
			'maxHeight'   => array(
				'type'    => 'number',
				'default' => 1329,
			),
			'interactive' => array(
				'type'    => 'boolean',
				'default' => true,
			),
			'aspectRatio' => array(
				'type'    => 'string',
				'default' => '2_1',
			),
		);
	}

	/**
	 * @TODO look for a semantic HTML markup and build a nice About Author box.
	 *
	 * @param $attributes
	 *
	 * @return mixed|string
	 */
	function render( $attributes ) {

		// Get the API key
		$APIkey = get_option( 'orbitfox_google_map_block_api_key' );

		// Don't output anything if there is no API key
		if ( null === $APIkey || empty( $APIkey ) ) {
			return;
		}

		// Exapnd all the atributes into separate variables
		foreach ( $attributes as $key => $value ) {
			${$key} = $value;
		}

		// URL encode the location for Google Maps
		$location = urlencode( $location );

		// Set the API url based to embed or static maps based on the interactive setting
		$apiURL = ( $interactive ) ? "https://www.google.com/maps/embed/v1/place?key=${APIkey}&q=${location}&zoom=${zoom}&maptype=${mapType}" : "https://maps.googleapis.com/maps/api/staticmap?center=${location}&zoom=${zoom}&size=${maxWidth}x${maxHeight}&maptype=${mapType}&key=${APIkey}";

		// Check status code of apiURL
		$ch = curl_init( $apiURL );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLOPT_NOBODY, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
		$output   = curl_exec( $ch );
		$httpcode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );

		// Don't output anything if the response from Google Maps isn't a 200
		if ( $httpcode !== 200 ) {
			return;
		}

		// Set the appropriate CSS class names
		$classNames = ( $interactive ) ? "wp-block-orbitfox-google-map interactive ratio$aspectRatio" : "wp-block-orbitfox-google-map";

		// Create the output
		$output = "<div class='$classNames'><div class='map'>";
		// If the map is interactive show the iframe
		if ( $interactive ) {
			$output .= "<iframe width='100%' height='100%' frameborder='0' style='border:0' src='$apiURL' allowfullscreen></iframe>";
			// Otherwise use the static API
		} else {
			$output .= "<img src='$apiURL' />";
		}
		$output .= '</div></div>';

		// Return the output
		return $output;
	}

	function registerSettings() {
		register_setting(
			'orbitfox_google_map_block_api_key',
			'orbitfox_google_map_block_api_key',
			array(
				'type'              => 'string',
				'description'       => __( 'Google Map API key for the Gutenberg block plugin.' ),
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => true,
				'default'           => ''
			)
		);
	}
}