<?php

namespace OrbitFox\Gutenberg_Blocks;

class Chart_Block extends Base_Block {

	public function __construct() {
		parent::__construct();
	}

	function set_block_slug() {
		$this->block_slug = 'chart-pie';
	}

	function set_attributes() {
		$this->attributes = array(
			'blockID' => array(
				'type' => 'string'
			),
			'chartName'       => array(
				'type'    => 'string',
				'default' => 'Pie Chart',
			),
			'backgroundColor' => array(
				'type'    => 'string',
				'default' => '#e4e7e1'
			),

			// @TODO these arrays must have defaults. I'll update them when the core bug with validation schema is fixed.
			'dataLabels'      => array(
				'type'    => 'array'
			),

			'dataValues' => array(
				'type'    => 'array'
			),

			'dataColors' => array(
				'type'    => 'array'
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
		$id = $attributes['blockID'];

		// @TODO keep this as long as the array attributes will trigger a php warning when they have a default.
		$dataLabels = array( 'Red', 'Blue' );
		$dataValues = array( 22, 78 );
		$dataColors = array( 'red', 'blue' );

		if ( ! empty( $attributes['dataLabels'] ) ) {
			$dataLabels = $attributes['dataLabels'];
		}

		if ( ! empty( $attributes['dataValues'] ) ) {
			$dataValues = $attributes['dataValues'];
		}

		if ( ! empty( $attributes['dataColors'] ) ) {
			$dataColors = $attributes['dataColors'];
		}

		// this is the mark-up of the canvas
		$chart_markup = sprintf(
			'<canvas id="obfx-chart-pie-%1$s" style="background-color: %2$s" data-chartName=\'%3$s\'></canvas>',
			$id,
			$attributes['backgroundColor'],
			$attributes['chartName']
		);

		// the script of the chart
		$script = '<script>
		(function() {
			var el = document.getElementById(\'obfx-chart-pie-'. $id .'\');
			
			var chart = new Chart( el, {
				type: \'pie\',
				data: {
					labels: ' . json_encode( $dataLabels ) . ',
					datasets: [{
						label:\'' . $attributes['chartName'] . '\',
						data: ' . json_encode( $dataValues ) . ',
						backgroundColor: ' . json_encode( $dataColors ) . '
					}]
				}
			});
		})()
		</script>';
		return $chart_markup . $script;
	}
}