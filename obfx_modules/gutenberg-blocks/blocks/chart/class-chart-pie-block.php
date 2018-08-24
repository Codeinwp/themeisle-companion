<?php

namespace OrbitFox\Gutenberg_Blocks;

class Chart_Pie_Block extends Base_Block {

	public function __construct() {
		parent::__construct();
	}

	function set_block_slug() {
		$this->block_slug = 'chart-pie';
	}

	function set_attributes() {
		$this->attributes = array(
			'data' => array(
				'type'    => 'array',
				'default' => [
					[ __( 'Label', 'themeisle-companion' ), __( 'Data', 'themeisle-companion' ) ],
					[ __( 'Dogs', 'themeisle-companion' ), 40 ],
					[ __( 'Cats', 'themeisle-companion' ), 30 ],
					[ __( 'Racoons', 'themeisle-companion' ), 20 ],
					[ __( 'Monkeys', 'themeisle-companion' ), 10 ],
				],
			),
			'options' => array(
				'type' => 'object',
				'default' => [
					'title' => __( 'Animals', 'themeisle-companion' ),
					'is3D' => true,
				],
			),
			'id' => array(
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
		$chart_markup = "<div id='" . $attributes['id'] . "' style='width: 100%; height: 500px;'></div>";

		$script = "<script>
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart);
	
			function drawChart() {
				var data = google.visualization.arrayToDataTable(" . json_encode( $attributes['data'] ) . ");
				var options = " . json_encode( $attributes['options'] ) . ";
				var chart = new google.visualization.PieChart(document.getElementById('" . $attributes['id'] . "'));
				chart.draw(data, options);
			}
		</script>";

		return $chart_markup . $script;
	}
}