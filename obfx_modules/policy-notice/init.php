<?php
/**
 * A module to display a notification bar which will inform users about the website Private Policy.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Policy_Notice_OBFX_Module
 */

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Policy_Notice_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 */
class Policy_Notice_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * Test_OBFX_Module constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();

		$this->name        = __( 'Policy Notice', 'themeisle-companion' );
		$this->description = __( 'A simple notice bar which will help you inform users about your website policy.', 'themeisle-companion' );
	}

	/**
	 * Method to determine if the module is enabled or not.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return bool
	 */
	public function enable_module() {
		return true;
	}

	/**
	 * The method for the module load logic.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return mixed
	 */
	public function load() {
		return;
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

		return array();
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
				'id'      => 'enable_policy_notice',
				'name'    => 'enable_policy_notice',
				'title'   => '',
				'type'    => 'toggle',
				'label'   => sprintf(
					'%s <a href="%s">%s</a>.',
					esc_html__( 'Allow OrbitFox to display a bottom bar with info about the website Private Policy. You can set the Private Policy page ', 'textdomain' ),
					admin_url( 'privacy.php' ),
					esc_html__( 'here', 'textdomain' )
				),
				'default' => '0',
			),

			array(
				'id'      => 'policy_notice_text',
				'name'    => 'policy_notice_text',
				'title'   => esc_html__( 'Policy description', 'textdomain' ),
				'type'    => 'text',
				'default' => esc_html__( 'This website uses cookies to improve your experience. We\'ll assume you accept this policy as long as you are using this website', 'textdomain' ),
			),

//			array(
//				'id'      => 'notice_accept_label',
//				'name'    => 'notice_accept_label',
//				'title'   => esc_html__( 'Policy Link Label', 'textdomain' ),
//				'type'    => 'text',
//				'default' => esc_html__( 'View Policy', 'textdomain' ),
//			),

			array(
				'id'      => 'notice_link_label',
				'name'    => 'notice_link_label',
				'title'   => esc_html__( 'Policy Button Label', 'textdomain' ),
				'type'    => 'text',
				'default' => esc_html__( 'View Policy', 'textdomain' ),
			),
		);
	}

	/**
	 * Method to define actions and filters needed for the module.
	 *
	 * @codeCoverageIgnore
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function hooks() {
		// if the module is enabled
		if ( ! $this->is_policy_notice_active() ) {
			return;
		}

		// if the cookie policy is already accepted we quit.
		if ( isset( $_COOKIE['obfx-policy-consent'] ) && 'accepted' === $_COOKIE['obfx-policy-consent'] ) {
			return;
		}

		$this->loader->add_action( 'wp_print_footer_scripts', $this, 'wp_print_footer_scripts' );
		$this->loader->add_action( 'wp_print_footer_scripts', $this, 'wp_print_footer_style' );
		$this->loader->add_action( 'wp_footer', $this, 'display_cookie_notice' );
	}

	/**
	 * Here we display the cookie bar template based on the given options.
	 */
	public function display_cookie_notice() {
		$policy_link   = get_option( 'wp_page_for_privacy_policy' ) ? get_permalink( (int) get_option( 'wp_page_for_privacy_policy' ) ) : '#';
		$policy_text   = $this->get_option( 'policy_notice_text' );
		$policy_button = $this->get_option( 'notice_link_label' );

		$options = array(
			'policy_link'   => $policy_link,
			'policy_text'   => $policy_text,
			'policy_button' => $policy_button
		);

		// @TODO maybe think at some template system for a further hookable customization.

		// message output will start with a wrapper and an input tag which will decide if the template is visible or not
		$output = '<div class="obfx-cookie-bar-container"><input class="obfx-checkbox-cb" id="obfx-checkbox-cb" type="checkbox" />';

		// we'll add the buttons as a separate var and we'll start with the close button
		$buttons = '<label for="obfx-checkbox-cb" class="obfx-close-cb">X</label>';
		// the "Acceptance" button
		$buttons .= '<a href="#" class="obfx-accept-cookie-policy" >' . esc_html__( 'Agree', 'textdomain' ) . '</a>';
		// the "View Policy button"
		$buttons .= '<a href="' . $policy_link . '" >' . $policy_button . '</a>';

		// combine the buttons with the bar and close the wrapper.
		$output .= '<span class="obfx-cookie-bar">' . $policy_text . $buttons . '</span></div>';

		echo apply_filters( 'obfx_cookie_notice_output', $output, $options );
	}

	/**
	 * This script takes care of the cookie bar handling.
	 * For the moment we'll bind a cookie save to the "Agree" button click.
	 */
	public function wp_print_footer_scripts() { ?>
		<script>
			(function ($) {
				// when an user clicks on
				$(document).on('click', '.obfx-accept-cookie-policy', function (e) {
					e.preventDefault();
					var days = 365;
					var date = new Date();
					// @TODO add an option to select expiry days
					date.setTime(date.getTime() + 24 * days * 60 * 60 * 1e3);

					// save the cookie
					document.cookie = 'obfx-policy-consent=accepted; expires=' + date.toGMTString() + '; path=/';

					// after we get the acceptance cookie we can close the box
					$('.obfx-checkbox-cb').prop('checked', true);
				});

			})(jQuery);
		</script><?php
	}

	/**
	 * This modules needs a few CSS lines so there is no need to load a file for it.
	 */
	public function wp_print_footer_style() { ?>
		<style>
			.obfx-cookie-bar-container {
				height: 0;
			}
			
			.obfx-checkbox-cb {
				display: none;
			}

			.obfx-cookie-bar {
				padding: 12px 25px;
				position: fixed;
				text-align: center;
				bottom: 0;
				left: 0;
				right: 0;
				display: block;
				min-height: 40px;
				background: #fff;
				border: 1px solid #333;
			}

			.obfx-cookie-bar a {
				padding: 0 8px;
				text-decoration: underline;
				font-weight: bold;
			}

			.obfx-checkbox-cb:checked + .obfx-cookie-bar {
				display: none;
			}

			.obfx-close-cb {
				position: absolute;
				right: 5px;
				top: 12px;
				width: 20px;
				cursor: pointer;
			}
		</style>
	<?php }

	/**
	 * Check if safe updates is turned on.
	 *
	 * @return bool Safe updates status.
	 */
	private function is_policy_notice_active() {
		return (bool) $this->get_option( 'enable_policy_notice' );
	}
}