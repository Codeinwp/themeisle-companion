<?php
namespace OrbitFox\Gutenberg_Blocks;

/**
 * Class Chart_Pie_Block
 */
class Chart_Pie_Block extends Base_Block {

	/**
	 * Constructor function for the module.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Every block needs a slug, so we need to define one and assign it to the `$this->block_slug` property
	 *
	 * @return mixed
	 */
	function set_block_slug() {
		$this->block_slug = 'chart-pie';
	}

	/**
	 * Set the attributes required on the server side.
	 *
	 * @return mixed
	 */
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
			),
		);
	}

	/**
	 * Block render function for server-side.
	 *
	 * This method will pe passed to the render_callback parameter and it will output
	 * the server side output of the block.
	 *
	 * @return mixed|string
	 */
	function render( $attributes ) {
		$chart_markup = "<div id='" . $attributes['id'] . "' style='width: 100%; height: 500px;'></div>";

		$script = "<script>
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart);
	
			function drawChart() {
				var data = google.visualization.arrayToDataTable(" . json_encode( $attributes['data'] ) . ');
				var options = ' . json_encode( $attributes['options'] ) . ";
				var chart = new google.visualization.PieChart(document.getElementById('" . $attributes['id'] . "'));
				chart.draw(data, options);
			}
		</script>";

		return $chart_markup . $script;
	}
}
