<?php
/**
 * The Mock-up to demonstrate and test module use.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Test_OBFX_Module
 */

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Test_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 */
class Stats_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * Test_OBFX_Module constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name        = __( 'Reports Module', 'themeisle-companion' );
		$this->description = __( 'A simple module for your WordPress data.', 'themeisle-companion' );

	}

	/**
	 * Determine if module should be loaded.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return bool
	 */
	public function enable_module() {
		return true;
	}

	/**
	 * The loading logic for the module.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function load() {

	}

	/**
	 * Method to define hooks needed.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function hooks() {
		$this->loader->add_action( 'wp_dashboard_setup', $this, 'add_dashboard_widgets' );
	}

	/**
	 * Module method to add a dashboard widget
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_dashboard_widgets() {
		wp_add_dashboard_widget(
			'obfx_dashboard_widget',    // Widget slug.
			'Site Reports <small><i>by Orbit Fox</i></small>',         // Title.
			array( $this, 'dashboard_widget_function' ) // Display function.
		);
	}

	/**
	 * Display method for the Dashboard widget.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function dashboard_widget_function() {
		$display_year   = $this->get_option( 'display_year' );
		$posts_count    = $this->get_posts_count( $display_year );
		$comments_count = $this->get_comments_count( $display_year );

		$data = array(
			'display_year'   => $display_year,
			'title'          => $this->get_option( 'dashboard_title' ),
			'desc'           => $this->get_option( 'dashboard_desc' ),
			'graph_shows'    => $this->get_option( 'graph_shows' ),
			'posts_count'    => $posts_count,
			'comments_count' => $comments_count,
		);

		echo $this->render_view( 'dashboard-widget', $data );
	}

	/**
	 * Utility method to return posts count,
	 *
	 * @since   1.0.0
	 * @access  public
	 *
	 * @param   string $display_year The year to query for.
	 *
	 * @return array
	 */
	public function get_posts_count( $display_year ) {
		$posts_count = array();
		for ( $cnt = 0; $cnt < 12; $cnt ++ ) {
			$posts_count[] = rand( 13, 766 );
		}

		if ( ! $this->get_option( 'mock_data' ) ) {
			global $wpdb;
			$results     = $wpdb->get_results( $this->build_graph_query( $display_year, $wpdb->posts, true ), ARRAY_A );
			$posts_count = $this->build_graph_array( $results, true );
		}

		return $posts_count;
	}

	/**
	 * Creates a query for graph data.
	 *
	 * @since   1.0.0
	 * @access  private
	 *
	 * @param   string $display_year The year to look for.
	 * @param   string $table The table name
	 * @param   bool   $posts_or_comments Counts posts if `true` or comments if `false`.
	 *
	 * @return string
	 */
	private function build_graph_query( $display_year, $table, $posts_or_comments ) {
		$count = "SUM(`comment_count`) as `comment_count`";
		if ( $posts_or_comments ) {
			$count = "COUNT( `ID` ) AS `post_count`";
		}
		$query = "
        SELECT
        MONTH( `post_date` ) AS `post_month`, {$count}
        FROM {$table}
        WHERE `post_type` = 'post' AND `post_status` = 'publish' AND YEAR( `post_date` ) = '{$display_year}'
        GROUP BY `post_month`
        ";

		return $query;
	}

	/**
	 * Utility method to build result array.
	 *
	 * @since   1.0.0
	 * @access  private
	 *
	 * @param   array $results Query results as associative array.
	 * @param   bool  $posts_or_comments Counts posts if `true` or comments if `false`.
	 *
	 * @return array
	 */
	private function build_graph_array( $results, $posts_or_comments ) {
		$data_count = array( 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 );
		foreach ( $results as $posts_published ) {
			$pos = $posts_published['post_month'] - 1;
			$key = 'comment_count';
			if ( $posts_or_comments ) {
				$key = 'post_count';
			}
			$data_count[ $pos ] = (int) $posts_published[ $key ];
		}

		return $data_count;
	}

	/**
	 * Utility method to return comments count,
	 *
	 * @since   1.0.0
	 * @access  public
	 *
	 * @param   string $display_year The year to query for.
	 *
	 * @return array
	 */
	public function get_comments_count( $display_year ) {
		$comments_count = array();
		for ( $cnt = 0; $cnt < 12; $cnt ++ ) {
			$comments_count[] = rand( 7, 484 );
		}

		if ( ! $this->get_option( 'mock_data' ) ) {
			global $wpdb;
			$results        = $wpdb->get_results( $this->build_graph_query( $display_year, $wpdb->posts, false ), ARRAY_A );
			$comments_count = $this->build_graph_array( $results, false );
		}

		return $comments_count;
	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the front end part.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function public_enqueue() {
		return array();
	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the admin part.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array|boolean
	 */
	public function admin_enqueue() {
		$current_screen = get_current_screen();

		if ( ! isset( $current_screen->id ) ) {
			return array();
		}
		if ( $current_screen->id != 'dashboard' ) {
			return array();
		}

		return array(
			'js'  => array(
				'vendor/chart.min' => array( 'jquery' ),
				'stats'            => array( 'jquery' ),
			),
			'css' => array(
				'stats' => false,
			),
		);
	}

	/**
	 * Method to define the options fields for the module
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function options() {
		return array(
			array(
				'id'          => 'dashboard_title',
				'name'        => 'dashboard_title',
				'title'       => 'Dashboard Title',
				'description' => 'This title is displayed on the Dasboard widget.',
				'type'        => 'text',
				'default'     => '',
				'placeholder' => 'Add some text',
			),
			array(
				'id'          => 'dashboard_desc',
				'name'        => 'dashboard_desc',
				'title'       => 'Dashboard Graph Description (*optional)',
				'description' => 'This will be displayed inside the Dashboard widget.',
				'type'        => 'textarea',
				'default'     => '',
				'placeholder' => 'Add some text here ...',
			),
			array(
				'id'          => 'display_year',
				'name'        => 'display_year',
				'title'       => 'The year to use for graph plotting.',
				'description' => 'Based on the selected year here will update the graph on the Dashboard widget.',
				'type'        => 'select',
				'default'     => '2017',
				'placeholder' => 'Select an option',
				'options'     => array(
					'2017' => '2017',
					'2016' => '2016',
					'2015' => '2015',
					'2014' => '2014',
					'2013' => '2013',
					'2012' => '2012',
					'2011' => '2011',
					'2010' => '2010',
				),
			),
			array(
				'id'          => 'graph_shows',
				'name'        => 'graph_shows',
				'title'       => 'What does the graph display?',
				'description' => 'Select what is displayed on the graph.',
				'type'        => 'radio',
				'default'     => '2',
				'options'     => array(
					'0' => 'Posts Count',
					'1' => 'Comments Count',
					'2' => 'Posts & Comments Count',
				),
			),
			array(
				'id'          => 'mock_data',
				'name'        => 'mock_data',
				'label'       => 'Disable/Enable',
				'title'       => 'Use mock data for graph?',
				'description' => 'If enabled this plots mock data on graph.',
				'type'        => 'toggle',
				'default'     => '1',
			),
		);
	}
}