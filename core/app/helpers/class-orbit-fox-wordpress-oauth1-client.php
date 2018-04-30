<?php

namespace Orbitfox\Connector;

use Exception;
use League\OAuth1\Client\Server\Server;
use League\OAuth1\Client\Server\User;
use League\OAuth1\Client\Credentials\TokenCredentials;

class OauthClient extends Server {
	protected $baseUri;
	protected $broker_url = 'https://connect.orbitfox.com/broker/connect/';
	protected $authURLs = array();

	/**
	 * {@inheritDoc}
	 */
	public function __construct( $clientCredentials, SignatureInterface $signature = null ) {
		parent::__construct( $clientCredentials, $signature );
		if ( is_array( $clientCredentials ) ) {
			$this->parseConfigurationArray( $clientCredentials );
		}
	}

	/**
	 * Generate the OAuth protocol header for a temporary credentials
	 * request, based on the URI.
	 *
	 * @param string $uri
	 *
	 * @return string
	 */
	protected function brokerCredentialsProtocolHeader($uri, $site, $user_id) {
		$parameters = array_merge($this->baseProtocolParameters(), array(
			'server_url' => $site,
			'wp_url' => site_url(),
			'user_id' => $user_id,
		));
		$parameters['oauth_signature'] = $this->signature->sign($uri, $parameters, 'POST');
		return $this->normalizeProtocolParameters($parameters);
	}

	/**
	 * Gets temporary credentials by performing a request to the server.
	 *
	 * @param $site
	 *
	 * @return array|null|void
	 * @throws Exception
	 * @throws \League\OAuth1\Client\Credentials\CredentialsException
	 */
	public function requestCredentialsForService( $site, $user_id ) {
		$uri = $this->broker_url;
		$client = new \GuzzleHttp\Client;
		$header = $this->brokerCredentialsProtocolHeader( $uri, $site, $user_id );

		$fparams = array(
			'server_url' => $site,
			'wp_url' => site_url(),
			'user_id' => $user_id,
		);

		$authorizationHeader = array('Authorization' => $header);
		$headers = $this->buildHttpClientHeaders($authorizationHeader);
		$options = array(
			'debug'       => false,
			'headers'     => $headers,
			'form_params' => $fparams,
			'stream'      => true,
		);
		$response = $client->post($uri, $options);
		$credentials = array();
		try {
			$body = $response->getBody()->getContents();
			$data = json_decode( $body, true );
			if ( isset( $data['client_token'] ) && isset( $data['client_secret'] ) ) {
				$credentials = $data;
			} else {
				throw new Exception( sprintf( 'Error returned from broker: %s', $body ) );
			}
		} catch (BadResponseException $e) {
			return $this->handleTemporaryCredentialsBadResponse($e);
		}
		if ( empty( $credentials ) ) {
			// WTF?
			return null;
		}

		$our_keys = array( 'client_token', 'client_secret', 'api_root' );

		$filtered = array_filter(
			$credentials,
			function ($key) use ($our_keys) {
				return in_array($key, $our_keys);
			},
			ARRAY_FILTER_USE_KEY
		);
		return $filtered;
	}

	/**
	 * Get the user data from the remote service.
	 * @param $tokenCredentials
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function get_user_data( $tokenCredentials ){
		$url = $this->urlUserDetails();

		$data = $this->_fetch( $url, $tokenCredentials );
		$our_keys = array( 'id', 'username', 'name',  'email' );
		if ( ! empty( $data ) ){
			$filtered = array_filter(
				$data,
				function ($key) use ($our_keys) {
					return in_array($key, $our_keys);
				},
				ARRAY_FILTER_USE_KEY
			);

			return $filtered;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function urlUserDetails() {
		return rtrim( $this->baseUri, '/' ) . '/wp-json/wp/v2/users/me?_envelope=1&context=edit';
	}

	/**
	 * A primitive fetch method to avoid repeating code.
	 *
	 * @param $url
	 * @param string $method
	 * @param TokenCredentials $tokenCredentials
	 *
	 * @return bool
	 * @throws Exception
	 */
	protected function _fetch( $url, TokenCredentials $tokenCredentials, $method = 'get' ) {
		$client = $this->createHttpClient();
		$data = false;
		$headers = $this->getHeaders( $tokenCredentials, strtoupper($method), $url );
		$headers['Accept'] = 'application/json';

		$header_params = explode( ', ', str_replace( 'OAuth ', '', $headers['Authorization'] ) );

		foreach ( $header_params as $k => $v ) {
			$url = $url . '&' . str_replace( '"', '', $v );
		}

		try {
			$response = $client->$method( $url, $headers, array(
				'allow_redirects' => false
			) );

			$data = $response->getBody()->getContents();

			$data = json_decode( $data, true );

			if ( ! empty( $data['body'] ) ) {
				$data = $data['body'];
			}

		} catch ( BadResponseException $e ) {
			$response   = $e->getResponse();
			$body       = $response->getBody();
			$statusCode = $response->getStatusCode();

			throw new \Exception(
				"Received error [$body] with status code [$statusCode] when retrieving token credentials."
			);
		}

		return $data;
	}

	/**
	 * {@inheritDoc}
	 */
	public function urlTemporaryCredentials() {
		return $this->authURLs->request;
	}

	/**
	 * {@inheritDoc}
	 */
	public function urlAuthorization() {
		return $this->authURLs->authorize;
	}

	/**
	 * {@inheritDoc}
	 */
	public function urlTokenCredentials() {
		return $this->authURLs->access;
	}

	/**
	 * {@inheritDoc}
	 */
	public function userDetails( $data, TokenCredentials $tokenCredentials ) {
		$user = new User();

		$user->uid = $data['id'];
		$user->nickname = $data['slug'];
		$user->name = $data['name'];
		$user->firstName = $data['first_name'];
		$user->lastName = $data['last_name'];
		$user->email = $data['email'];
		$user->description = $data['description'];
		$user->imageUrl = $data['avatar_urls']['96'];
		$user->urls['permalink'] = $data['link'];
		if ( ! empty( $data['url'] ) ) {
			$user->urls['website'] = $data['url'];
		}

		$used = array('id', 'slug', 'name', 'first_name', 'last_name', 'email', 'avatar_urls', 'link', 'url');

		// Save all extra data
		$user->extra = array_diff_key($data, array_flip($used));

		return $data;
	}

	/**
	 * {@inheritDoc}
	 */
	public function userUid( $data, TokenCredentials $tokenCredentials ) {
		return $data['id'];
	}

	/**
	 * {@inheritDoc}
	 */
	public function userEmail( $data, TokenCredentials $tokenCredentials ) {
		return $data['email'];
	}

	/**
	 * {@inheritDoc}
	 */
	public function userScreenName( $data, TokenCredentials $tokenCredentials ) {
		return $data['slug'];
	}

	/**
	 * Parse configuration array to set attributes.
	 *
	 * @param array $configuration
	 *
	 * @throws Exception
	 */
	private function parseConfigurationArray( array $configuration = array() ) {
		if ( ! isset( $configuration['api_root'] ) ) {
			throw new Exception( 'Missing WordPress API index URL' );
		}
		$this->baseUri = $configuration['api_root'];

		if ( ! isset( $configuration['auth_urls'] ) ) {
			throw new Exception( 'Missing authorization URLs from API index' );
		}
		$this->authURLs = $configuration['auth_urls'];
	}
}