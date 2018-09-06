<?php

namespace OrbitFox\Gutenberg_Blocks;

class Google_Map_Block extends Base_Block {

	public function __construct() {
		parent::__construct();
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
				'default' => 10,
			),
			'height'      => array(
				'type'    => 'string',
				'default' => '400px',
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
			${ $key } = $value;
		}

		// URL encode the location for Google Maps
		$location = urlencode( $location );

		// Set the API url based to embed or static maps based on the interactive setting
		$apiURL = "https://www.google.com/maps/embed/v1/place?key=${APIkey}&q=${location}&zoom=${zoom}&maptype=${mapType}";

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

		$output = "<div class='wp-block-orbitfox-google-map'><div class='map'>";
			$output .= "<iframe width='100%' height='100%' frameborder='0' style='border:0; height:${height};' src='$apiURL' allowfullscreen></iframe>";
		$output .= '</div></div>';

		// Return the output
		return $output;
	}
}