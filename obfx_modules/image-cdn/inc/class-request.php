<?php
namespace OrbitFox;
/**
 * A class for building an OAuth header for http requests.
 *
 * @TODO Please open source this so another poor soul won't lose 3 days of his life in this hell
 */

class OAuth1_Request {
	protected $extra_params = null;
	protected $connect_data = null;
	protected $oauth_token = null;
	protected $access_token = null;
	protected $access_token_secret = null;
	protected $header_string = null;
	protected $base_string = null;
	protected $signature = null;
	protected $headers = null;
	protected $nonce = null;
	protected $url = null;
	protected $consumer_key = 'hw6wnM5ONi3P';
	protected $consumer_secret = 'MMJGxBWMhhYMtxIroMWCecGHcGzywDhupwpGy7bFax9urTbI';
	protected $method = null;

	public function __construct( $url, $method = 'GET', $extra_params = array(), $pass_callback = false ) {

		// The url for our custom endpoint, which returns network settings.
		$this->url          = esc_url( $url );
		$this->extra_params = $extra_params;
		$this->connect_data = get_option( 'obfx_connect_data' );


		$temp_token = get_transient( 'obfx_temp_token' );

		if ( ! empty( $temp_token ) ) {
			$this->access_token_secret = $temp_token;
		}

		$connect_data = get_option( 'obfx_connect_data' );

		if ( isset( $connect_data['connect'] ) ) {
			$this->access_token = $connect_data['connect']['oauth_token'];
			$this->access_token_secret = $connect_data['connect']['oauth_token_secret'];
		}

		// All we really care about here is GET requests.
		$this->method = $method;

		// Combine some semi-random, semi-unique stuff into a nonce for our request.
		$this->set_nonce();

		// Since we already have the consumer_secret and the access_token_secret, we can set the signature_key.
		$this->set_signature_key();

		// We can set some of the headers now, but we'll have to revisit them later to set one of them in particular.
		$this->set_headers( $pass_callback );

		// Now that we have the url, method, and some of the headers, we can set the base string.
		$this->set_base_string();

		// Now that we have the base string and the signature key, we can set the signature.
		$this->set_signature();

		// Now that we have the signature, we can revisit the headers and set the final one.
		$this->set_headers();

		// Now that we have the headers, we can combine them into a string for passing along in our http requests to the control install.
		$this->set_header_string();

	}

	/**
	 * Combine some semi-random, semi-unique stuff into a nonce.
	 */
	private function set_nonce() {
		$mt = microtime();
		$rand = mt_rand();
		$this->nonce = md5($mt . $rand);
	}

	/**
	 * Combine the consumer_secret and the access_token_secret into a signature key.
	 *
	 * @see http://oauth1.wp-api.org/docs/basics/Signing.html#signature-key
	 */
	private function set_signature_key() {
		$this->signature_key = rawurldecode( $this->consumer_secret ) . '&';
		if ( ! empty( $this->access_token_secret ) ) {
			$this->signature_key .= rawurldecode( $this->access_token_secret );
		}

	}

	/**
	 * Combine the values from postman, the oauth1 plugin, and this class iteslf, into the headers array.
	 *
	 * This function gets run twice.  First, it sets most of the headers.  Later, it sets the final header, the oauth_signature.
	 * You can't do them all at once because you need the base string in order to set the signature, and the base string needs the first few headers!
	 *
	 * @param bool $pass_callback
	 */
	private function set_headers( $pass_callback = false ) {
		// If we've not yet the headers, set the first few ones, which are easy.
		if ( ! isset( $this->headers ) ) {

			// These need to be in alphabetical order, although we will sort them later, automatically.
			$this->headers = array(
				'oauth_consumer_key'     => $this->consumer_key,
				'oauth_nonce'            => $this->nonce,
				'oauth_signature_method' => 'HMAC-SHA1',
				'oauth_timestamp'        => time(),
				'oauth_version'          => '1.0',
			);
			
			if ( $pass_callback ) {
				$this->headers['oauth_callback'] = self::urlencode_rfc3986( admin_url( 'admin.php?page=obfx_companion') );
			}

			if ( isset( $this->extra_params['oauth_token'] ) && isset( $this->extra_params['oauth_verifier'] ) ) {
				$this->headers = array_merge( $this->headers, $this->extra_params );
			}

			if ( ! empty( $this->access_token ) ) {
				$this->headers['oauth_token'] = $this->access_token;
			}
			
			// If we've already set some of the headers and we have the signature now, add the signature to the headers.
		} elseif ( isset( $this->signature ) ) {
			$this->headers['oauth_signature'] = $this->signature;
		}
		uksort( $this->headers, 'strcmp' );
	}

	/**
	 * Combine the first few headers and any url vars into the base string.
	 *
	 * @see http://oauth1.wp-api.org/docs/basics/Signing.html#base-string
	 */
	private function set_base_string() {
		// Start by grabbing the oauth headers.
		$headers = $this->headers;
		$headers_and_params_string= '';
		if ( ! empty( $this->extra_params ) ) {
			array_walk_recursive( $this->extra_params, array( $this, 'normalize_parameters' ) );
			// Grab the url parameters.
			$headers_and_params = $headers;

			if ( ! empty( $this->extra_params ) ) {
				// Combine the two arrays.
				$headers_and_params = array_merge( $headers, $this->extra_params );
			}

			// They need to be alphabetical.
			//ksort( $headers_and_params );
			uksort( $headers_and_params, 'strcmp' );
			// This will hold each key/value pair of the array, as a string.
			$headers_and_params_string = '';

			// For each header and url param...
			foreach ( $headers_and_params as $key => $value ) {

				// Combine them into a string.
				$headers_and_params_string .= "$key=$value&";

			}

			// Remove the trailing ampersand.
			$headers_and_params_string = rtrim( $headers_and_params_string, '&' );
		}

		if ( empty($headers_and_params_string)){
			$query_string = $this->create_signature_string( $this->headers );
			$out = $this->method . '&' . rawurlencode( $this->url ) . '&' . $query_string;
		} else {
			$out = $this->method . '&' . rawurlencode( $this->url ) . '&' . self::urlencode_rfc3986( $headers_and_params_string );
		}

		$this->base_string = $out;
	}

	/**
	 * Combine the base_string and the signature_key into the signature.
	 *
	 * @see http://oauth1.wp-api.org/docs/basics/Signing.html#signature
	 */
	private function set_signature() {
		$signature = hash_hmac( 'sha1', $this->base_string, $this->signature_key, true );
		$this->signature = base64_encode( $signature );
	}

	/**
	 * Combine the header array into a string.
	 */
	private function set_header_string() {
		$out = '';
		// For each of the headers, which at this point does now include the signature...
		foreach ( $this->headers as $key => $value ) {

			$value = rawurlencode( $value );

			// Yes, it's mandatory to do double quotes aroung each value.
			$out .= $key . '=' . '"' . $value . '"' . ', ';

		}

		// Trim off the trailing comma/space.
		$out = rtrim( $out, ', ' );

		// Kind of similar to basic auth, you have to prepend this little string to declare what sort of auth you're sending.
		$out = 'OAuth ' . $out;

		$this->header_string = $out;
	}

	/**
	 * Make an oauth'd http request.
	 *
	 * @return string|object The result of an oauth'd http request.
	 */
	public function get_response() {
		// Grab the url to which we'll be making the request.
		$url = $this->url;

		// If there is a extra, add that as a url var.
		if (  'GET' === $this->method && ! empty( $this->extra_params ) ) {
			foreach ( $this->extra_params as $key => $val ) {
				$url = add_query_arg( array( $key => $val ), $url );
			}
		}


		// Args for wp_remote_*().
		$args = array(
			'method'  => $this->method,
			'timeout' => 45,
			'httpversion' => '1.0',
			'body' => $this->extra_params,
			'sslverify'   => false,
			'headers' => array(
				'Authorization' => $this->header_string,
			),
		);
		//	print_r($url);
		//	print_r($args);
		$out = wp_remote_request( $url, $args );
		echo wp_remote_retrieve_body( $out );
		return wp_remote_retrieve_body( $out );
	}

	/**
	 * Creates a signature string from all query parameters
	 *
	 * @since  0.1
	 *
	 * @param  array $params Array of query parameters
	 *
	 * @return string         Signature string
	 */
	public function create_signature_string( $params ) {
		return implode( '%26', $this->join_with_equals_sign( $params ) ); // join with ampersand
	}

	/**
	 * Creates an array of urlencoded strings out of each array key/value pairs
	 *
	 * @since  0.1.0
	 *
	 * @param  array $params Array of parameters to convert.
	 * @param  array $query_params Array to extend.
	 * @param  string $key Optional Array key to append
	 *
	 * @return string               Array of urlencoded strings
	 */
	public function join_with_equals_sign( $params, $query_params = array(), $key = '' ) {
		foreach ( $params as $param_key => $param_value ) {
			if ( $key ) {
				$param_key = $key . '%5B' . $param_key . '%5D'; // Handle multi-dimensional array
			}
			if ( is_array( $param_value ) ) {
				$query_params = $this->join_with_equals_sign( $param_value, $query_params, $param_key );
			} else {
				$string         = $param_key . '=' . $param_value; // join with equals sign
				$query_params[] = self::urlencode_rfc3986( $string );
			}
		}

		return $query_params;
	}

	/**
	 * Normalize each parameter by assuming each parameter may have already been encoded, so attempt to decode, and then
	 * re-encode according to RFC 3986
	 *
	 * @since 2.1
	 * @see rawurlencode()
	 *
	 * @param string $key
	 * @param string $value
	 */
	protected function normalize_parameters( &$key, &$value ) {
		$key   = self::urlencode_rfc3986( rawurldecode( $key ) );
		$value = self::urlencode_rfc3986( rawurldecode( $value ) );
	}

	protected static function urlencode_rfc3986( $value ) {
		return str_replace( array( '+', '%7E' ), array( ' ', '~' ), rawurlencode( $value ) );
	}
}