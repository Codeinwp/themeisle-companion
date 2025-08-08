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

	private static $known_consent_scripts = array(
		"statistics" => array(
			"google-analytics" => array(
				"label" => "Google Analytics",
				"scripts" => array(
					"google-analytics.com/analytics.js",
					"googletagmanager.com/gtag/js",
					"google-analytics.com/ga.js",
					"gtag" // Also an inline keyword
				)
			),
			"matomo" => array(
				"label" => "Matomo",
				"scripts" => array(
					"matomo.php"
				)
			),
			"clarity" => array(
				"label" => "Clarity",
				"scripts" => array(
					"clarity.ms"
				)
			),
			"clicky" => array(
				"label" => "Clicky",
				"scripts" => array(
					"static.getclicky.com"
				)
			),
			"convert-insights" => array(
				"label" => "Convert Insights",
				"scripts" => array(
					"convertexperiments.com/v1/js"
				)
			),
			"woocommerce-sourcebuster" => array(
				"label" => "WooCommerce Sourcebuster",
				"scripts" => array(
					"woocommerce/assets/js/frontend/order-attribution"
				)
			)
		),
		"marketing" => array(
			"facebook-pixel" => array(
				"label" => "Facebook Pixel",
				"scripts" => array(
					"connect.facebook.net/en_US/fbevents.js",
					"fbq" // Also an inline keyword
				),
				"iframes" => array(),
				"blocked_elements" => array()
			),
			"google-ads" => array(
				"label" => "Google Ads",
				"scripts" => array(
					"googleads.g.doubleclick.net"
				),
				"iframes" => array(),
				"blocked_elements" => array()
			),
			"linkedin-insight" => array(
				"label" => "LinkedIn Insight",
				"scripts" => array(
					"snap.licdn.com/li.lms-analytics/insight.min.js"
				),
				"iframes" => array(),
				"blocked_elements" => array()
			),
			"twitter-pixel" => array(
				"label" => "X (formerly Twitter) Pixel",
				"scripts" => array(
					"static.ads-twitter.com/uwt.js",
					"platform.twitter.com/widgets.js",
					"analytics.twitter.com/i/adsct",
					"static.ads-x.com/uwt.js"
				),
				"iframes" => array(),
				"blocked_elements" => array()
			),
			"pinterest-tag" => array(
				"label" => "Pinterest Tag",
				"scripts" => array(
					"assets.pinterest.com/js/pinit.js",
					"s.pinimg.com/ct/core.js",
					"pintrk" // Also an inline keyword
				),
				"iframes" => array(),
				"blocked_elements" => array()
			),
			"snapchat-pixel" => array(
				"label" => "Snapchat Pixel",
				"scripts" => array(
					"sc-static.net/scevent.min.js",
					"snaptr" // Also an inline keyword
				),
				"iframes" => array(),
				"blocked_elements" => array()
			),
			"tiktok-pixel" => array(
				"label" => "TikTok Pixel",
				"scripts" => array(
					"analytics.tiktok.com/i18n/pixel/events.js",
					"ttq" // Also an inline keyword
				),
				"iframes" => array(),
				"blocked_elements" => array()
			),
			"optinmonster" => array(
				"label" => "OptinMonster",
				"scripts" => array(
					"omappapi.com/app/js/api.min.js"
				),
				"iframes" => array(),
				"blocked_elements" => array()
			),
			"thrive-leads" => array(
				"label" => "Thrive Leads",
				"scripts" => array(
					"plugins/thrive-leads/js/frontend.js",
					"plugins/thrive-leads/js/frontend.min.js",
					"thrive-leads/thrive-dashboard/js/dist/frontend.min.js",
					"ThriveGlobal", // Inline
					"TCB_Front" // Inline
				),
				"iframes" => array(),
				"blocked_elements" => array()
			),
			"youtube" => array(
				"label" => "YouTube",
				"scripts" => array(),
				"iframes" => array(
					"//www.youtube.com/embed/",
					"//youtube.com/embed/",
					"//youtube-nocookie.com/embed/",
					"//www.youtube-nocookie.com/embed/"
				),
				"blocked_elements" => array()
			),
			"recaptcha" => array(
				"label" => "Recaptcha",
				"scripts" => array(
					"//www.google.com/recaptcha/",
					"//www.gstatic.com/recaptcha/",
					"//www.recaptcha.net/recaptcha/"
				),
				"iframes" => array(),
				"blocked_elements" => array(
					".g-recaptcha"
				)
			),
			"google-maps" => array(
				"label" => "Google Maps",
				"scripts" => array(),
				"iframes" => array(
					"google.com/maps/embed",
					"//www.google.com/maps/embed"
				),
				"blocked_elements" => array()
			),
			"vimeo" => array(
				"label" => "Vimeo",
				"scripts" => array(),
				"iframes" => array(
					"//player.vimeo.com/video/",
					"//vimeo.com/"
				),
				"blocked_elements" => array()
			),
			"dailymotion" => array(
				"label" => "DailyMotion",
				"scripts" => array(),
				"iframes" => array(
					"dailymotion.com/player.html",
					"//www.dailymotion.com/embed/video/"
				),
				"blocked_elements" => array()
			)
		)
	);

	/**
	 * Module optios strings.
	 *
	 * @var array
	 */
	private $option_strings = array();

	/**
	 * Module options.
	 *
	 * @var array
	 */
	private $option = array();

	/**
	 * Setup module strings
	 *
	 * @access  public
	 */
	public function set_module_strings() {
		$this->name              = __( 'Cookie Notice', 'themeisle-companion' );
		$this->description       = __( 'A simple notice bar which will help you inform users about your website policy.', 'themeisle-companion' );
		$this->documentation_url = 'https://docs.themeisle.com/article/951-orbit-fox-documentation#policy-notice';
		$this->set_options_strings();
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

		$this->option = array(
			array(
				'id'      => 'enable_policy_notice',
				'name'    => 'enable_policy_notice',
				'title'   => '',
				'type'    => 'toggle',
				'default' => '0',
			),
			array(
				'id'      => 'policy_notice_template',
				'name'    => 'policy_notice_template',
				'title'   => esc_html__( 'Notice Template', 'themeisle-companion' ),
				'type'    => 'select',
				'default' => 'default',
				'options' => array(
					'default'            => esc_html__( 'Default (Text with buttons on the side)', 'themeisle-companion' ),
					'buttons_after_text' => esc_html__( 'Text with Accept/Reject buttons below', 'themeisle-companion' ),
				),
			),
			array(
				'id'      => 'policy_notice_text',
				'name'    => 'policy_notice_text',
				'title'   => esc_html__( 'Policy description', 'themeisle-companion' ),
				'type'    => 'text',
				'default' => esc_html__( 'This website uses cookies to improve your experience. We\\\'ll assume you accept this policy as long as you are using this website', 'themeisle-companion' ),
			),
			array(
				'id'      => 'policy_page',
				'name'    => 'policy_page',
				'type'    => 'select',
				'default' => 0,
			),
			array(
				'id'   => 'notice_link_label',
				'name' => 'notice_link_label',
				'type' => 'text',
			),
			array(
				'id'   => 'notice_accept_label',
				'name' => 'notice_accept_label',
				'type' => 'text',
			),
			array(
				'id'      => 'notice_reject_label',
				'name'    => 'notice_reject_label',
				'title'   => esc_html__( 'Reject Cookie Button Label', 'themeisle-companion' ),
				'type'    => 'text',
				'default' => esc_html__( 'Reject', 'themeisle-companion' ),
			),
			array(
				'id'      => 'policy_notice_position',
				'name'    => 'policy_notice_position',
				'title'   => esc_html__( 'Notice Position', 'themeisle-companion' ),
				'type'    => 'select',
				'default' => 'bottom_banner',
				'options' => array(
					'bottom_banner'           => esc_html__( 'Bottom Banner (Full Width)', 'themeisle-companion' ),
					'floating_bottom_left'  => esc_html__( 'Floating Bottom Left', 'themeisle-companion' ),
					'floating_bottom_right' => esc_html__( 'Floating Bottom Right', 'themeisle-companion' ),
					'floating_top_left'     => esc_html__( 'Floating Top Left', 'themeisle-companion' ),
					'floating_top_right'    => esc_html__( 'Floating Top Right', 'themeisle-companion' ),
					'floating_center'       => esc_html__( 'Floating Center Modal', 'themeisle-companion' ),
				),
			),
			array(
				'id'      => 'enable_cookie_blocking',
				'name'    => 'enable_cookie_blocking',
				'title'   => esc_html__( 'Enable Automatic Cookie Blocking', 'themeisle-companion' ),
				'type'    => 'toggle',
				'label'   => esc_html__( 'Attempt to block scripts from loading before consent is given. This is an advanced feature and might require specific configuration or cause issues with some themes/plugins.', 'themeisle-companion' ),
				'default' => '0',
			),
		);
		$this->get_updated_options();

		return $this->option;
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

		$this->loader->add_action( 'update_option_wp_page_for_privacy_policy', $this, 'on_page_for_privacy_policy_save', 10, 3 );
		$this->loader->add_action( $this->get_slug() . '_before_options_save', $this, 'before_options_save', 10, 1 );

		$consent_given = isset( $_COOKIE['obfx-policy-consent'] ) && 'accepted' === $_COOKIE['obfx-policy-consent'];
		$blocking_enabled = (bool) $this->get_option( 'enable_cookie_blocking', '0' );

		if ( $blocking_enabled && ! $consent_given && ! is_admin() ) {
			// Start output buffering before the main template is included.
			// $this->loader->add_filter( 'template_include', array( $this, 'start_buffering_before_template' ), 9999 );
			// Use direct WordPress add_filter instead of the loader
			add_filter( 'template_include', array( $this, 'start_buffering_before_template' ), 9999 );
		}

		// if the cookie policy is already accepted we quit further front-end display hooks.
		if ( $consent_given ) { 
			return;
		}

		// only front-end hooks from now on for displaying the notice
		$this->loader->add_action( 'wp_print_footer_scripts', $this, 'wp_print_footer_scripts' );
		$this->loader->add_action( 'wp_print_footer_scripts', $this, 'wp_print_footer_style' );
		$this->loader->add_action( 'wp_footer', $this, 'display_cookie_notice' );
	}

	/**
	 * Starts HTML output buffering before the template file is included.
	 * Attached to 'template_include' filter.
	 *
	 * @param string $template The path of the template to include.
	 * @return string The path of the template to include.
	 */
	public function start_buffering_before_template( $template ) {
		// Check conditions again, as this is a filter callback.
		$consent_given = isset( $_COOKIE['obfx-policy-consent'] ) && 'accepted' === $_COOKIE['obfx-policy-consent'];
		$blocking_enabled = (bool) $this->get_option( 'enable_cookie_blocking', '0' );

		if ( !is_admin() && $blocking_enabled && !$consent_given ) {
			// Add error handling to prevent fatal errors if ob_start fails
			try {
				ob_start( array( $this, 'filter_scripts_for_consent' ) );
			} catch (Exception $e) {
				// Log error or handle gracefully - prevent fatal errors
				error_log('Policy Notice module: Error starting output buffer: ' . $e->getMessage());
			}
		}
		return $template; // IMPORTANT: Always return the template file in this filter
	}

	/**
	 * Callback for output buffering to filter script tags.
	 *
	 * @param string $buffer The HTML buffer.
	 * @return string The modified HTML buffer.
	 */
	public function filter_scripts_for_consent( $buffer ) {
		// Add top-level try-catch to prevent fatal errors during HTML manipulation
		try {
			// If there are no predefined consent scripts, return as-is.
			if ( empty( self::$known_consent_scripts ) ) {
				return $buffer;
			}

			libxml_use_internal_errors( true );
			$dom = new DOMDocument();
			if ( ! @$dom->loadHTML( mb_convert_encoding( $buffer, 'HTML-ENTITIES', 'UTF-8' ), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD ) ) {
				libxml_clear_errors();
				return $buffer;
			}
			libxml_clear_errors();

			$modified = false;

			// Process SCRIPT tags
			$scripts = $dom->getElementsByTagName( 'script' );
			foreach ( $scripts as $script_node ) {
				if ( $script_node instanceof DOMElement ) { // Ensure it's an element
					$src = $script_node->getAttribute( 'src' );
					$type = strtolower( $script_node->getAttribute( 'type' ) );
					$content = $script_node->nodeValue;

					if ( $type === 'text/plain-blocked-by-obfx-consent' ) continue;
					if ( ! empty( $type ) && $type !== 'text/javascript' && $type !== 'application/javascript' && $type !== 'module' && $type !== '' ) { // Allow empty type
						continue;
					}

					$block_reason_category = null;
					$matched_service_label = '';

					// Check against predefined scripts
					foreach ( self::$known_consent_scripts as $category_name => $services ) {
						foreach ( $services as $service_key => $service_details ) {
							if ( ! empty( $service_details['scripts'] ) ) {
								foreach ( $service_details['scripts'] as $pattern ) {
									if ( ! empty( $src ) && stripos( $src, $pattern ) !== false ) {
										$block_reason_category = $category_name;
										$matched_service_label = $service_details['label'];
										break 3;
									} elseif ( empty( $src ) && ! empty( $content ) && stripos( $content, $pattern ) !== false ) {
										// This is a basic check for inline scripts.
										// More sophisticated inline script identification might be needed.
										$block_reason_category = $category_name;
										$matched_service_label = $service_details['label'];
										break 3;
									}
								}
							}
						}
					}

					if ( $block_reason_category && !$this->is_script_whitelisted( $src ) ) {
						$original_type = !empty($type) ? $type : 'text/javascript';
						$script_node->setAttribute( 'data-obfx-original-type', $original_type );
						$script_node->setAttribute( 'type', 'text/plain-blocked-by-obfx-consent' );
						$script_node->setAttribute( 'data-obfx-consent-category', $block_reason_category );
						$script_node->setAttribute( 'data-obfx-service-label', $matched_service_label );

						if ( ! empty( $src ) ) {
							$script_node->setAttribute( 'data-obfx-original-src', $src );
							$script_node->removeAttribute( 'src' );
						} else {
							// Inline script
							$script_node->setAttribute( 'data-obfx-original-content', $content );
							$script_node->nodeValue = ''; // Clear inline script content
						}
						$modified = true;
					}
				}
			}

			// Process IFRAME tags
			$iframes = $dom->getElementsByTagName('iframe');
			foreach ($iframes as $iframe_node) {
				if ($iframe_node instanceof DOMElement) {
					$src = $iframe_node->getAttribute('src');
					if (empty($src)) continue;

					$block_reason_category = null;
					$matched_service_label = '';

					foreach (self::$known_consent_scripts as $category_name => $services) {
						foreach ($services as $service_key => $service_details) {
							if (!empty($service_details['iframes'])) {
								foreach ($service_details['iframes'] as $pattern) {
									if (stripos($src, $pattern) !== false) {
										$block_reason_category = $category_name;
										$matched_service_label = $service_details['label'];
										break 3;
									}
								}
							}
						}
					}
					
					// Check against user-defined keywords for iframes (if not already matched by predefined)
					// Note: User keywords are primarily for scripts, but could be extended if needed.
					// For now, we only block iframes based on the predefined list.

					if ( $block_reason_category && !$this->is_script_whitelisted( $src ) ) { // Re-use whitelist for iframe src
						$iframe_node->setAttribute('data-obfx-original-src', $src);
						$iframe_node->setAttribute('data-obfx-blocked-iframe', 'true');
						$iframe_node->setAttribute('data-obfx-consent-category', $block_reason_category);
						$iframe_node->setAttribute('data-obfx-service-label', $matched_service_label);
						$iframe_node->setAttribute('src', 'about:blank'); // Or remove src attribute
						// $iframe_node->removeAttribute('src');
						$modified = true;
					}
				}
			}

			// At the end
			if ( $modified ) {
				try {
					$filtered_buffer = $dom->saveHTML();
					return $filtered_buffer;
				} catch (Exception $e) {
					error_log('Policy Notice module: Error saving HTML: ' . $e->getMessage());
					return $buffer; // Return original buffer on error
				}
			} else {
				return $buffer;
			}
		} catch (Exception $e) {
			error_log('Policy Notice module: Error processing HTML for consent: ' . $e->getMessage());
			return $buffer; // Return original buffer on any error
		}
	}

	/**
	 * Checks if a script src should be whitelisted from blocking.
	 *
	 * @param string $src The script src.
	 * @return bool True if whitelisted, false otherwise.
	 */
	private function is_script_whitelisted( $src ) {
		if ( empty( $src ) ) return true; // Should not happen if called with src, but safety.

		// Whitelist WordPress core scripts, jQuery, and potentially our own plugin's scripts.
		$wp_includes_url = includes_url();
		$wp_content_url = content_url();
		// Get this plugin's directory to whitelist its own scripts if any are loaded this way
		// This might need adjustment based on how Orbit Fox structures plugin URLs
		$this_plugin_url_segment = plugin_basename( dirname( __FILE__, 3 ) ); //  e.g. 'themeisle-companion/obfx_modules'

		if ( stripos( $src, $wp_includes_url ) === 0 ) return true;
		if ( stripos( $src, 'jquery.min.js' ) !== false ) return true;
		if ( stripos( $src, 'jquery.js' ) !== false ) return true;
		
		// Whitelist scripts from Orbit Fox itself or this specific module, if identifiable by URL
		// This is a basic check; might need to be more specific if OBFX has a known script path
		if ( defined( 'ORBIT_FOX_URL' ) && stripos( $src, ORBIT_FOX_URL ) === 0 ) return true;
		if ( stripos( $src, $this_plugin_url_segment ) !== false && stripos( $src, '/obfx_modules/policy-notice/' ) !== false ) return true;

		// Avoid blocking essential scripts for the consent bar itself (if any were loaded via src)
		// Our current JS is inline, but good to keep in mind.

		return false;
	}

	/**
	 * Here we display the cookie bar template based on the given options.
	 */
	public function display_cookie_notice() {
		// $policy_link = get_option( 'wp_page_for_privacy_policy' ) ? get_permalink( (int) get_option( 'wp_page_for_privacy_policy' ) ) : '#';

		$policy_page_id = $this->get_option( 'policy_page' );
		if ( ! empty( $policy_page_id ) && '0' !== $policy_page_id ) { // '0' is the 'Default Core Policy' value
			$policy_link = get_permalink( (int) $policy_page_id );
		} else {
			// Fallback to WordPress core privacy policy page if module setting is 'Default Core Policy' or not set
			$core_policy_page_id = get_option( 'wp_page_for_privacy_policy' );
			if ( ! empty( $core_policy_page_id ) ) {
				$policy_link = get_permalink( (int) $core_policy_page_id );
			} else {
				$policy_link = '#'; // Default if no policy page is set anywhere
			}
		}

		$policy_text         = $this->get_option( 'policy_notice_text' );
		$policy_button       = $this->get_option( 'notice_link_label' );
		$accept_button_label = $this->get_option( 'notice_accept_label' );
		$reject_button_label = $this->get_option( 'notice_reject_label' );
		$template            = $this->get_option( 'policy_notice_template' );
		// Position is handled by CSS, but we add a class to the container for targeting.
		// $position         = $this->get_option( 'policy_notice_position' );

		$options = array(
			'policy_link'         => $policy_link,
			'policy_text'         => $policy_text,
			'policy_button_label' => $policy_button, // Renamed for clarity if used in template
			'accept_button_label' => $accept_button_label,
			'reject_button_label' => $reject_button_label,
			'template'            => $template,
		);

		// message output will start with a wrapper and an input tag which will decide if the template is visible or not
		$output = '<div class="obfx-cookie-bar-container" style="display: none" id="obfx-cookie-bar"><input class="obfx-checkbox-cb" id="obfx-checkbox-cb" type="checkbox" />';

		$buttons_html = '';
		// Common close button for all templates, can be styled per template if needed.
		$buttons_html .= '<label for="obfx-checkbox-cb" class="obfx-close-cb">X</label>';

		if ( 'buttons_after_text' === $template ) {
			// Buttons are rendered after the main text span for this template
			$main_content = '<span class="obfx-cookie-bar-text">' . esc_html( $policy_text ) . '</span>';
			
			$buttons_actions = '<div class="obfx-buttons-group">';
			// The "Acceptance" button
			$buttons_actions .= '<a href="#" id="obfx-accept-cookie-policy" class="obfx-button obfx-button-accept">' . esc_html( $accept_button_label ) . '</a>';
			// The "Reject" button
			if ( ! empty( $reject_button_label ) ) {
				$buttons_actions .= '<a href="#" id="obfx-reject-cookie-policy" class="obfx-button obfx-button-reject">' . esc_html( $reject_button_label ) . '</a>';
			}
			// The "View Policy button"
			if ( ! empty( $policy_button ) && ! empty( $policy_link ) && $policy_link !== '#' ) {
				$buttons_actions .= '<a href="' . esc_url( $policy_link ) . '" class="obfx-button obfx-button-policy">' . esc_html( $policy_button ) . '</a>';
			}
			$buttons_actions .= '</div>'; // End obfx-buttons-group

			$output .= '<span class="obfx-cookie-bar obfx-template-buttons-after-text">' . $buttons_html . $main_content . $buttons_actions . '</span>';

		} else { // Default template
			// The "Acceptance" button
			$buttons_html .= '<a href="#" id="obfx-accept-cookie-policy">' . esc_html( $accept_button_label ) . '</a>';
			// The "View Policy button"
			if ( ! empty( $policy_button ) && ! empty( $policy_link ) && $policy_link !== '#' ) {
				$buttons_html .= '<a href="' . esc_url( $policy_link ) . '">' . esc_html( $policy_button ) . '</a>';
			}
			// combine the buttons with the bar and close the wrapper.
			$output .= '<span class="obfx-cookie-bar obfx-template-default">' . esc_html( $policy_text ) . $buttons_html . '</span>';
		}
		
		$output .= '</div>'; // Close obfx-cookie-bar-container

		$allowed_html          = wp_kses_allowed_html( 'post' );
		$allowed_html['input'] = array(
			'class' => array(),
			'id'    => array(),
			'type'  => array(),
		);
		$allowed_html['label'] = array(
			'for'   => array(),
			'class' => array(),
		);
		// Allow divs and spans with classes for the new structure
		$allowed_html['div'] = array(
			'class' => array(),
			'id'    => array(),
			'style' => array(),
		);
		$allowed_html['span'] = array(
			'class' => array(),
			'id'    => array(),
		);


		echo wp_kses( apply_filters( 'obfx_cookie_notice_output', $output, $options ), $allowed_html );
	}

	/**
	 * This script takes care of the cookie bar handling.
	 * For the moment we'll bind a cookie save to the "Agree" button click.
	 */
	public function wp_print_footer_scripts() { ?>
		<script>
			(function (window) {

				function getCookie(cname) {
					var name = cname + "=";
					var ca = document.cookie.split(';');
					for(var i = 0; i < ca.length; i++) {
						var c = ca[i];
						while (c.charAt(0) == ' ') {
							c = c.substring(1);
						}
						if (c.indexOf(name) == 0) {
							return c.substring(name.length, c.length);
						}
					}
					return "";
				}

				var noticeBar = document.getElementById('obfx-cookie-bar');
				var acceptButton = document.getElementById('obfx-accept-cookie-policy');
				var rejectButton = document.getElementById('obfx-reject-cookie-policy'); // New reject button
				var checkbox = document.getElementById('obfx-checkbox-cb');

				if(noticeBar){
					let cookie = getCookie('obfx-policy-consent');
					// Also consider a 'rejected' state if we implement that later
					if(cookie !== 'accepted'){
						noticeBar.style.display = 'block';
					}
				}

				if(acceptButton) {
					acceptButton.addEventListener('click', function (e) {
						e.preventDefault();
						var days = 365;
						var date = new Date();
						date.setTime(date.getTime() + 24 * days * 60 * 60 * 1e3);
						document.cookie = 'obfx-policy-consent=accepted; expires=' + date.toGMTString() + '; path=/';

						if(checkbox) checkbox.checked = true; // Hide the notice

						// Activate blocked scripts
						var blockedScripts = document.querySelectorAll('script[type="text/plain-blocked-by-obfx-consent"]');
						blockedScripts.forEach(function(blockedScript) {
							var newScript = document.createElement('script');
							var originalSrc = blockedScript.getAttribute('data-obfx-original-src');
							var originalContent = blockedScript.getAttribute('data-obfx-original-content');
							var originalType = blockedScript.getAttribute('data-obfx-original-type');

							if (originalType) {
								newScript.type = originalType;
							} else {
								newScript.type = 'text/javascript'; // Default if not specified
							}

							// Copy all attributes from the blocked script to the new script
							// (except type and our data- attributes)
							for (var i = 0; i < blockedScript.attributes.length; i++) {
								var attr = blockedScript.attributes[i];
								if (attr.name !== 'type' && !attr.name.startsWith('data-obfx-')) {
									newScript.setAttribute(attr.name, attr.value);
								}
							}

							if (originalSrc) {
								newScript.src = originalSrc;
							} else if (originalContent) {
								newScript.textContent = originalContent;
							} else {
								// Fallback if somehow both are missing, try to use original content if any
								newScript.textContent = blockedScript.textContent;
							}

							if (blockedScript.parentNode) {
								blockedScript.parentNode.insertBefore(newScript, blockedScript);
								blockedScript.remove();
							} else {
								// If for some reason it no longer has a parent, append to <head>
								document.head.appendChild(newScript);
							}
						});

						// Activate blocked iframes
						var blockedIframes = document.querySelectorAll('iframe[data-obfx-blocked-iframe="true"]');
						blockedIframes.forEach(function(blockedIframe) {
							var originalSrc = blockedIframe.getAttribute('data-obfx-original-src');
							if (originalSrc) {
								blockedIframe.src = originalSrc;
								blockedIframe.removeAttribute('data-obfx-blocked-iframe');
								// Consider removing other data-obfx- attributes for cleanup if needed
							}
						});

						// Optionally, trigger a custom event that other parts of the page can listen to, indicating consent given
						var consentAcceptedEvent = new Event('obfxPolicyConsentAccepted');
						document.dispatchEvent(consentAcceptedEvent);

					}, false);
				}

				// Event listener for the new reject button
				if(rejectButton) {
					rejectButton.addEventListener('click', function (e) {
						e.preventDefault();
						// For now, rejecting also just closes the notice. 
						// We could set a different cookie like 'obfx-policy-consent=rejected' if needed for other logic.
						if(checkbox) checkbox.checked = true;
						// Optionally, to prevent it from showing again in the same session, even if not 'accepted':
						// document.cookie = 'obfx-policy-consent=dismissed; path=/'; 
					}, false);
				}

			})(window);
		</script>
		<?php
	}

	/**
	 * This modules needs a few CSS lines so there is no need to load a file for it.
	 */
	public function wp_print_footer_style() {
		$position = $this->get_option( 'policy_notice_position', 'bottom_banner' ); // Default to bottom_banner
		$template = $this->get_option( 'policy_notice_template', 'default' );
		?>
		<style>
			.obfx-cookie-bar-container {
				/* height: 0; Ensures it doesn't take space when visibility is controlled by display */
				display: none; /* Hidden by default, JS will show it */
				position: fixed; /* All positions are fixed */
				z-index: 99999; /* Increased z-index */
			}

			.obfx-checkbox-cb {
				display: none;
			}

			.obfx-cookie-bar {
				padding: 15px 25px;
				background: #fff;
				border: 1px solid #ddd;
				box-shadow: 0 2px 10px rgba(0,0,0,0.1);
				color: #333;
				/* display:block; Will be flex for more control */
				display: flex;
				align-items: center;
				justify-content: space-between;
				flex-wrap: wrap; /* Allow wrapping for smaller screens */
			}

			.obfx-cookie-bar a,
			.obfx-cookie-bar .obfx-button {
				margin-left: 10px;
				text-decoration: none;
				padding: 8px 15px;
				border-radius: 4px;
				font-weight: bold;
				cursor: pointer;
				transition: background-color 0.2s ease, color 0.2s ease;
				border: 1px solid transparent; /* Base for all buttons */
			}

			/* Default template specific link styling (View Policy) */
			.obfx-template-default > a:not(.obfx-button) {
				text-decoration: underline;
				padding: 0 8px; /* Original padding */
				border: none;
				background-color: transparent;
				color: inherit; /* Inherit theme link color */
				margin-left: 0; /* Restore legacy spacing */
			}
			.obfx-template-default > a:not(.obfx-button):hover {
				color: #23527c;
			}

			/* General Button Styling */
			.obfx-button-accept {
				background-color: #4CAF50; /* Green */
				color: white;
				border-color: #4CAF50;
			}
			.obfx-button-accept:hover {
				background-color: #45a049;
			}

			.obfx-button-reject {
				background-color: #f1f1f1; /* Light Grey / White */
				color: #333;
				border: 1px solid #ccc;
			}
			.obfx-button-reject:hover {
				background-color: #e0e0e0;
			}

			.obfx-button-policy {
				background-color: #5bc0de; /* Info blue */
				color: white;
				border-color: #46b8da;
			}
			.obfx-button-policy:hover {
				background-color: #31b0d5;
			}

			.obfx-checkbox-cb:checked ~ .obfx-cookie-bar {
				display: none !important; /* Ensure it hides */
			}

			.obfx-close-cb {
				/* position: absolute; No longer absolute, part of flex flow for default template */
				/* right: 5px; */
				/* top: 12px; */ 
				width: 20px;
				cursor: pointer;
				font-size: 20px;
				line-height: 1;
				color: #888;
				margin-left: 15px; /* Spacing from other buttons if on the right */
				order: 3; /* Pushes to the end in default template */
			}
			.obfx-close-cb:hover {
				color: #555;
			}

			/* Template: Buttons After Text */
			.obfx-template-buttons-after-text {
				flex-direction: column;
				align-items: center; /* Center content vertically */
				text-align: center;
			}
			.obfx-template-buttons-after-text .obfx-cookie-bar-text {
				margin-bottom: 15px;
				width: 100%;
			}
			.obfx-template-buttons-after-text .obfx-buttons-group {
				display: flex;
				justify-content: center;
				flex-wrap: wrap; /* Allow buttons to wrap on small screens */
			}
			.obfx-template-buttons-after-text .obfx-buttons-group .obfx-button {
				margin: 5px;
			}
			.obfx-template-buttons-after-text .obfx-close-cb {
				position: absolute; /* Keep X close button at top right for this template */
				right: 10px;
				top: 10px;
				order: initial; /* Reset order */
			}

			/* Positioning */
			<?php if ( 'bottom_banner' === $position ) : ?>
			.obfx-cookie-bar-container {
				bottom: 0;
				left: 0;
				right: 0;
			}
			.obfx-cookie-bar-container .obfx-cookie-bar {
				border-radius: 0;
				border-left: none;
				border-right: none;
				border-bottom: none;
			}
			<?php elseif ( 'floating_bottom_left' === $position ) : ?>
			.obfx-cookie-bar-container {
				bottom: 20px;
				left: 20px;
				max-width: 380px; /* Max width for floating */
			}
			.obfx-cookie-bar-container .obfx-cookie-bar {
				border-radius: 5px;
			}
			<?php elseif ( 'floating_bottom_right' === $position ) : ?>
			.obfx-cookie-bar-container {
				bottom: 20px;
				right: 20px;
				max-width: 380px;
			}
			.obfx-cookie-bar-container .obfx-cookie-bar {
				border-radius: 5px;
			}
			<?php elseif ( 'floating_top_left' === $position ) : ?>
			.obfx-cookie-bar-container {
				top: 20px;
				left: 20px;
				max-width: 380px;
			}
			.obfx-cookie-bar-container .obfx-cookie-bar {
				border-radius: 5px;
			}
			<?php elseif ( 'floating_top_right' === $position ) : ?>
			.obfx-cookie-bar-container {
				top: 20px;
				right: 20px;
				max-width: 380px;
			}
			.obfx-cookie-bar-container .obfx-cookie-bar {
				border-radius: 5px;
			}
			<?php elseif ( 'floating_center' === $position ) : ?>
			.obfx-cookie-bar-container {
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				max-width: 500px; /* Wider for modal */
				width: 90%; /* Responsive width */
			}
			.obfx-cookie-bar-container .obfx-cookie-bar {
				border-radius: 5px;
				<?php if ( 'buttons_after_text' !== $template ) : ?>
				/* If not buttons_after_text, ensure text and buttons are spaced nicely */
				/* This might need more specific styling depending on final look */
				flex-direction: column; 
				align-items: stretch; /* Stretch items for a modal look */
				text-align: center;
				padding: 25px;
				<?php endif; ?>
			}
			<?php if ( 'buttons_after_text' === $template && 'floating_center' === $position) : ?>
			.obfx-template-buttons-after-text .obfx-cookie-bar-text {
				margin-bottom: 20px;
			}
			<?php elseif ( 'floating_center' === $position ) : ?> 
			/* For default template in floating_center */
			.obfx-cookie-bar.obfx-template-default {
				align-items: center; /* Center items horizontally */
			}
			.obfx-cookie-bar.obfx-template-default > span:first-of-type { /* The text */
				margin-bottom: 15px; /* Space between text and buttons */
				width: 100%;
			}
			.obfx-cookie-bar.obfx-template-default .obfx-button,
			.obfx-cookie-bar.obfx-template-default > a:not(.obfx-button) {
				margin: 5px; /* Add some margin around buttons */
			}
			.obfx-cookie-bar.obfx-template-default .obfx-close-cb {
				position: absolute;
				top: 10px;
				right: 10px;
			}

			/* Restore legacy default template layout */
			.obfx-cookie-bar.obfx-template-default {
				display: block;
				text-align: center;
				padding: 12px 25px;
				border: 1px solid #333;
				box-shadow: none;
			}
			.obfx-template-default .obfx-close-cb {
				position: absolute;
				right: 5px;
				top: 12px;
				margin-left: 0;
				order: initial; /* reset flex ordering */
			}

			<?php endif; ?>
			<?php else : // Fallback for bottom_banner or if position is not recognized (should not happen with default)
				// Default is bottom_banner, covered by the first condition for clarity
				if($position !== 'bottom_banner') { // Only apply if it's an unknown value, to avoid duplication
			?>
			.obfx-cookie-bar-container {
				bottom: 0;
				left: 0;
				right: 0;
			}
			.obfx-cookie-bar-container .obfx-cookie-bar {
				border-radius: 0;
				border-left: none;
				border-right: none;
				border-bottom: none;
			}
			<?php } ?>
			<?php endif; ?>

			/* Responsive adjustments */
			@media (max-width: 768px) {
				.obfx-cookie-bar {
					flex-direction: column; /* Stack items vertically */
					align-items: stretch; /* Stretch items to full width */
					text-align: center;
				}
				.obfx-cookie-bar > *:not(:last-child) {
					margin-bottom: 10px;
				}
				.obfx-cookie-bar .obfx-button,
				.obfx-cookie-bar a {
					margin-left: 0; /* Remove left margin when stacked */
					width: 100%; /* Make buttons full width */
					box-sizing: border-box;
				}
				.obfx-template-buttons-after-text .obfx-buttons-group .obfx-button {
					width: auto; /* Allow buttons to be side-by-side if space allows, then wrap */
					min-width: 120px; /* Minimum width for readability */
				}
				.obfx-close-cb {
					margin-left: 0;
					order: -1; /* Put close button at the top on mobile for default template */
					align-self: flex-end; /* Align to the right */
					position: static; /* Override absolute for mobile if needed */
					margin-bottom: 10px;
				}
				.obfx-template-buttons-after-text .obfx-close-cb {
					position: absolute; /* Keep X close button at top right for this template on mobile too */
					right: 10px;
				top: 10px;
					order: initial; 
					align-self: initial;
					margin-bottom: 0;
				}
				.obfx-cookie-bar-container {
					/* For floating types on mobile, make them full width at bottom */
					<?php if ( strpos($position, 'floating_') === 0 && $position !== 'floating_center' ) : ?>
					top: auto;
					bottom: 0;
					left: 0;
					right: 0;
					max-width: 100%;
					<?php endif; ?>
					<?php if ( $position === 'floating_center' ) : ?>
					max-width: 90%;
					<?php endif; ?>
				}
				.obfx-cookie-bar-container .obfx-cookie-bar {
					<?php if ( strpos($position, 'floating_') === 0 && $position !== 'floating_center' ) : ?>
					border-radius: 0;
					border-left: none;
					border-right: none;
					border-bottom: none;
					<?php endif; ?>
				}
			}

		</style>
		<?php
	}

	/**
	 * When the core privacy page is changed, we'll also change the option within our module.
	 *
	 * @param $old_value
	 * @param $value
	 * @param $option
	 *
	 * @return mixed
	 */
	public function on_page_for_privacy_policy_save( $old_value, $value, $option ) {

		// if this action comes from our dashboard we need to stop and avoid a save loop.
		if ( doing_action( $this->get_slug() . '_before_options_save' ) ) {
			return $value;
		}

		$this->set_option( 'policy_page', $value );

		return $value;
	}

	/**
	 * When the OrbitFox Module changes it's value, we also need to change the core version.
	 *
	 * @param $options
	 */
	public function before_options_save( $options ) {

		// the default option doesn't need a a change.
		if ( empty( $options ) ) {
			return;
		}

		// there is no need to change something to it's own value.
		if ( $options['policy_page'] === get_option( 'wp_page_for_privacy_policy' ) ) {
			return;
		}

		update_option( 'wp_page_for_privacy_policy', $options['policy_page'] );

	}

	/**
	 * Check if safe updates is turned on.
	 *
	 * @return bool Safe updates status.
	 */
	private function is_policy_notice_active() {
		return (bool) $this->get_option( 'enable_policy_notice' );
	}

	/**
	 * Return an array with all the pages but the first entry is an indicator to the policy selected in core.
	 *
	 * @return array
	 */
	private function get_policy_pages_array() {
		$core_policy_suffix = '';
		$url                = get_option( 'wp_page_for_privacy_policy' );
		if ( empty( $url ) ) {
			$core_policy_suffix = ' (' . esc_html__( 'Not Set', 'themeisle-companion' ) . ')';
		}
		$options = array(
			'0' => esc_html__( 'Default Core Policy', 'themeisle-companion' ) . $core_policy_suffix,
		);

		$pages = get_pages(
			array(
				'echo'        => '0',
				'post_status' => array( 'draft', 'publish' ),
				'depth'       => 0,
				'child_of'    => 0,
				'selected'    => 0,
				'value_field' => 'ID',
			)
		);

		if ( empty( $pages ) ) {
			return $options;
		}

		foreach ( $pages as $page ) {
			$options[ $page->ID ] = $page->post_title;
		}

		return $options;
	}

	/**
	 * Set the options strings for the module.
	 */
	private function set_options_strings() {
		$this->option_strings = array(
			'enable_policy_notice' => array(
				'label' => esc_html__( 'Allow OrbitFox to display a bottom bar with info about the website Private Policy.', 'themeisle-companion' ),
			),
			'policy_notice_text'   => array(
				'title'   => esc_html__( 'Policy description', 'themeisle-companion' ),
				'default' => esc_html__( 'This website uses cookies to improve your experience. We\'ll assume you accept this policy as long as you are using this website', 'themeisle-companion' ),
			),
			'policy_page'          => array(
				'title'   => esc_html__( 'Policy Page', 'themeisle-companion' ),
				'options' => $this->get_policy_pages_array(),
			),
			'notice_link_label'    => array(
				'title'   => esc_html__( 'Policy Button Label', 'themeisle-companion' ),
				'default' => esc_html__( 'View Policy', 'themeisle-companion' ),
			),
			'notice_accept_label'  => array(
				'title'   => esc_html__( 'Accept Cookie Button Label', 'themeisle-companion' ),
				'default' => esc_html__( 'Accept', 'themeisle-companion' ),
			),
		);
	}

	/**
	 * Update the options with the strings defined in the set_options_strings method.
	 */
	private function get_updated_options() {
		foreach ( $this->option as $key => $opt ) {
			if ( isset( $this->option_strings[ $opt['id'] ] ) ) {
				$this->option[ $key ] = array_merge( $opt, $this->option_strings[ $opt['id'] ] );
			}
		}
	}
}

