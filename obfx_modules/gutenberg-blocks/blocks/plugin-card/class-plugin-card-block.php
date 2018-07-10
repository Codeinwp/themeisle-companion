<?php
namespace OrbitFox\Gutenberg_Blocks;

class Plugin_Card_Block extends Base_Block {

	public function __construct() {
		parent::__construct();
	}

	function set_block_slug() {
		$this->block_slug = 'plugin-card';
	}

	function set_attributes(){
		$this->attributes = array(
			'slug' => array(
				'type' => 'string'
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

		require_once( ABSPATH . "wp-admin" . '/includes/plugin-install.php' );

		if( ! isset( $attributes['slug'] ) ) {
			return;
		}

		$request = array(
			'slug' => $attributes['slug'],
			'fields' => array(
				'short_description' => true,
				'active_installs' => true,
				'icons' => true,
				'sections' => false,
			)
		);

		// Get datas from API
		$result = plugins_api('plugin_information', $request);

var_dump( $result );

return '';
	}
}