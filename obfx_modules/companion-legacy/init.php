<?php
/**
 * ThemeIsle Companion Legacy Core Orbit Fox Module.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Companion_Legacy_OBFX_Module
 */

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Companion_Legacy_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 * @codeCoverageIgnore
 */
class Companion_Legacy_OBFX_Module extends Orbit_Fox_Module_Abstract {

    private $inc_dir;

    /**
     * Companion_Legacy_OBFX_Module constructor.
     *
     * @since   1.0.0
     * @access  public
     */
    public function __construct() {
        parent::__construct();
        $this->name = __( 'Companion Legacy', 'obfx' );
        $this->description = __( 'Module containing legacy functionality from ThemeIsle Companion.', 'obfx' );

        $this->auto = true;

        $this->inc_dir = $this->get_dir() . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR;

        define( 'THEMEISLE_COMPANION_PATH', $this->inc_dir );
        define( 'THEMEISLE_COMPANION_URL',  plugin_dir_url( $this->inc_dir ) );

        require_once $this->inc_dir . 'hestia' . DIRECTORY_SEPARATOR . 'functions.php';

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

    private function is_zerif() {
        if( function_exists( 'zerif_setup' ) ) {
            return true;
        }
        return false;
    }

    private function is_rhea() {
        if( function_exists( 'rhea_lite_setup' ) ) {
            return true;
        }
        return false;
    }

    private function is_hestia() {
        if( function_exists( 'hestia_setup_theme' ) ) {
            return true;
        }
        return false;
    }


    public function zerif_register_widgets() {
        if ( $this->is_zerif() ) {
            require_once $this->inc_dir . 'zerif-lite' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'widget-focus.php';
            require_once $this->inc_dir . 'zerif-lite' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'widget-testimonial.php';
            require_once $this->inc_dir . 'zerif-lite' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'widget-clients.php';
            require_once $this->inc_dir . 'zerif-lite' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'widget-team.php';

            register_widget( 'zerif_ourfocus' );
            register_widget( 'zerif_testimonial_widget' );
            register_widget( 'zerif_clients_widget' );
            register_widget( 'zerif_team_widget' );

            $theme = wp_get_theme();

            /* Populate the sidebar only for Zerif Lite */
            if ( 'Zerif Lite' == $theme->name || 'Zerif Lite' == $theme->parent_theme ) {

                $themeisle_companion_flag = get_option( 'themeisle_companion_flag' );
                if ( empty( $themeisle_companion_flag ) && function_exists( 'themeisle_populate_with_default_widgets' ) ) {
                    themeisle_populate_with_default_widgets();
                }
            }
        }
    }

    public function rhea_register_widgets() {
        if ( $this->is_rhea() ) {
            require_once $this->inc_dir . 'rhea' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'features.widget.php';
            require_once $this->inc_dir . 'rhea' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'about.widget.php';
            require_once $this->inc_dir . 'rhea' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'hours.widget.php';
            require_once $this->inc_dir . 'rhea' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'contact.widget.php';
            require_once $this->inc_dir . 'rhea' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'progress-bar.widget.php';
            require_once $this->inc_dir . 'rhea' . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'icon-box.widget.php';

            register_widget('rhea_features_block');
            register_widget('Rhea_Progress_Bar');
            register_widget('Rhea_Icon_Box');
            register_widget('Rhea_About_Company');
            register_widget('Rhea_Hours');
            register_widget('Rhea_Contact_Company');
        }
    }

    function rhea_load_custom_wp_admin_style() {
        if ( $this->is_rhea() ) {
            wp_enqueue_style('fontawesome-style', get_template_directory_uri() . '/css/font-awesome.min.css');
            wp_enqueue_style('rhea-admin-style', trailingslashit(THEMEISLE_COMPANION_URL) . 'inc/rhea/assets/css/admin-style.css');
            wp_enqueue_script('fontawesome-icons', trailingslashit(THEMEISLE_COMPANION_URL) . 'inc/rhea/assets/js/icons.js', false, '1.0.0');
            wp_enqueue_script('jquery-ui-dialog');
            wp_enqueue_script('fontawesome-script', trailingslashit(THEMEISLE_COMPANION_URL) . 'inc/rhea/assets/js/fontawesome.jquery.js', false, '1.0.0');
        }
    }

    public function rhea_add_html_to_admin_footer() {
        $output = '
        <div id="fontawesome-popup">
            <div class="left-side">
                <label for="fontawesome-live-search">' . esc_html_e( 'Search icon', 'rhea' ) . ':</label>
                <ul class="filter-icons">
                    <li data-filter="all" class="active">' . esc_html_e( 'All Icons', 'rhea' ) . '</li>
                </ul>
            </div>
            <div class="right-side">
            </div>
        </div>
        ';

        echo $output;
    }

    /**
     * The loading logic for the module.
     *
     * @since   1.0.0
     * @access  public
     */
    public function load() {
        if ( $this->is_zerif() ) {
            require_once $this->inc_dir . 'zerif-lite' . DIRECTORY_SEPARATOR . 'functions.php';
        }
    }

    /**
     * Method to define hooks needed.
     *
     * @since   1.0.0
     * @access  public
     */
    public function hooks() {
        $this->loader->add_action( 'widgets_init', $this, 'zerif_register_widgets' );

        $this->loader->add_action( 'widgets_init', $this, 'rhea_register_widgets' );
        $this->loader->add_action( 'admin_enqueue_scripts',  $this, 'rhea_load_custom_wp_admin_style' );
        $this->loader->add_action( 'admin_footer',  $this, 'rhea_add_html_to_admin_footer' );

        $this->loader->add_action( 'customize_controls_print_footer_scripts',  $this, 'rhea_add_html_to_admin_footer' );

        add_action( 'after_setup_theme', 'themeisle_hestia_require' );
        add_action( 'after_switch_theme', 'themeisle_hestia_set_frontpage' );
    }

    /**
     * Method that returns an array of scripts and styles to be loaded
     * for the front end part.
     *
     * @since   1.0.0
     * @access  public
     * @return array
     */
    public function public_enqueue() {}

    /**
     * Method that returns an array of scripts and styles to be loaded
     * for the admin part.
     *
     * @since   1.0.0
     * @access  public
     * @return array
     */
    public function admin_enqueue() {}

    /**
     * Method to define the options fields for the module
     *
     * @since   1.0.0
     * @access  public
     * @return array
     */
    public function options() {
        return array();
    }
}