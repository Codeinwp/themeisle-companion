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
		$this->name = __( 'Orbit Fox Stats Module', 'obfx' );
		$this->description = __( 'A stats module for Orbit Fox.', 'obfx' );
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
     * @return array
     */
    public function hooks() {
	    return array(
	        'actions' => array(
	            'wp_dashboard_setup' => 'add_dashboard_widgets'
            )
        );
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
            'Orbit Fox Stats',         // Title.
            array( $this, 'dashboard_widget_function' ) // Display function.
        );
    }

    /**
     * Utility method to return posts count,
     *
     * @since   1.0.0
     * @access  public
     * @param   string $display_year The year to query for.
     * @return array
     */
    public function get_posts_count( $display_year ) {
	    if ( ! $this->get_option( 'mock_data' ) ) {
            global $wpdb;
            $results = $wpdb->get_results( "
            SELECT
            MONTH( `post_date` ) AS `post_month`, COUNT( `ID` ) AS `post_count`
            FROM {$wpdb->posts}
            WHERE `post_type` = 'post' AND `post_status` = 'publish' AND YEAR( `post_date` ) = '{$display_year}'
            GROUP BY `post_month`
            ", ARRAY_A );
            $posts_count = array( 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 );
            foreach ( $results as $posts_published ) {
                $pos = $posts_published['post_month']-1;
                $posts_count[$pos] = (int) $posts_published['post_count'];
            }
        } else {
	        $posts_count = array();
	        for( $cnt = 0; $cnt < 12; $cnt++ ) {
                $posts_count[] = rand( 13, 766 );
            }
        }

        return $posts_count;
    }

    /**
     * Utility method to return comments count,
     *
     * @since   1.0.0
     * @access  public
     * @param   string $display_year The year to query for.
     * @return array
     */
    public function get_comments_count( $display_year ) {
        if ( ! $this->get_option( 'mock_data' ) ) {
            global $wpdb;
            $results = $wpdb->get_results( "
            SELECT
            MONTH( `post_date` ) AS `post_month`, SUM(`comment_count`) as `comment_count`
            FROM {$wpdb->posts}
            WHERE `post_type` = 'post' AND `post_status` = 'publish' AND YEAR( `post_date` ) = '{$display_year}'
            GROUP BY `post_month`
            ", ARRAY_A );
            $comments_count = array( 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 );
            foreach ( $results as $posts_published ) {
                $pos = $posts_published['post_month']-1;
                $comments_count[$pos] = (int) $posts_published['comment_count'];
            }
        } else {
            $comments_count = array();
            for( $cnt = 0; $cnt < 12; $cnt++ ) {
                $comments_count[] = rand( 7, 484 );
            }
        }

        return $comments_count;
    }

    /**
     * Display method for the Dashboard widget.
     *
     * @since   1.0.0
     * @access  public
     */
    public function dashboard_widget_function() {
	    $display_year = $this->get_option( 'display_year' );
	    $posts_count = $this->get_posts_count( $display_year );
        $comments_count = $this->get_comments_count( $display_year );

        $title = $this->get_option( 'dashboard_title' );
        $desc = $this->get_option( 'dashboard_desc' );
        $html_title = '';
        if( trim( $title ) != '' ) {
            $html_title = '<h3>' . $title . ' <small><i> for ' . $display_year . '</i></small></h3>';
        }

        $html_desc = '';
        if( trim( $desc ) != '' ) {
            $html_desc = '<hr/><small>' . $desc . '</small>';
        }

        $html_foot = '';
        if( $this->get_option( 'dev_link' ) ) {
            $html_foot = '<hr/><small style="text-align: right">Learn more at <a href="themeisle.com">ThemeIsle</a></small>';
        }

        $posts_data = '';
        $comments_data = '';
        $graph_shows = $this->get_option( 'graph_shows' );
        if( $graph_shows == 0 || $graph_shows == 2 ) {
            $posts_data = 'data-posts="' . json_encode( $posts_count ) . '"';
        }
        if( $graph_shows == 1 || $graph_shows == 2 ) {
            $comments_data = 'data-comments="' . json_encode( $comments_count ) . '"';
        }

        $data = array(
            'html_title' => $html_title,
            'html_desc' => $html_desc,
            'html_foot' => $html_foot,
            'posts_data' => $posts_data,
            'comments_data' => $comments_data,
        );

        echo $this->render_view( 'dashboard-widget', $data );
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
	 * @return array
	 */
	public function admin_enqueue() {
		return array(
		    'js' => array(
		        'vendor/chart.min' => array( 'jquery' ),
		        'stats' => array( 'jquery' ),
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
		        'id' => 'dashboard_title',
		        'name' => 'dashboard_title',
		        'title' => 'Dashboard Title',
		        'description' => 'This title is displayed on the Dasboard widget.',
		        'type' => 'text',
		        'default' => '',
		        'placeholder' => 'Add some text',
            ),
            array(
                'id' => 'dashboard_desc',
                'name' => 'dashboard_desc',
                'title' => 'Dashboard Graph Description (*optional)',
                'description' => 'This will be displayed inside the Dashboard widget.',
                'type' => 'textarea',
                'default' => '',
                'placeholder' => 'Add some text here ...',
            ),
            array(
                'id' => 'display_year',
                'name' => 'display_year',
                'title' => 'The year to use for graph plotting.',
                'description' => 'Based on the selected year here will update the graph on the Dashboard widget.',
                'type' => 'select',
                'default' => '2017',
                'placeholder' => 'Select an option',
                'options' => array(
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
                'id' => 'graph_shows',
                'name' => 'graph_shows',
                'title' => 'What does the graph display?',
                'description' => 'Select what is displayed on the graph.',
                'type' => 'radio',
                'default' => '2',
                'options' => array(
                    '0' => 'Posts Count',
                    '1' => 'Comments Count',
                    '2' => 'Posts & Comments Count',
                ),
            ),
            array(
                'id' => 'dev_link',
                'name' => 'dev_link',
                'label' => 'Yes, I want to show the ThemeIsle Link in footer.',
                'title' => 'Display ThemeIsle Link?',
                'description' => 'Adds a powered by link inside the footer.',
                'type' => 'checkbox',
                'default' => '1'
            ),
            array(
                'id' => 'mock_data',
                'name' => 'mock_data',
                'label' => 'Disable/Enable',
                'title' => 'Use mock data for graph?',
                'description' => 'If enabled this plots mock data on graph.',
                'type' => 'toggle',
                'default' => '1',
            ),
        );
	}
}
