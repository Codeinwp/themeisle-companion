<?php

/**
 * Reading Progress Bar Core Orbit Fox Module.
 *
 * @link       https://themeisle.com
 * @since      3.0.0
 *
 * @package    Reading_Progress_Bar_OBFX_Module
 */

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Reading_Progress_Bar_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 */
class Reading_Progress_Bar_OBFX_Module extends Orbit_Fox_Module_Abstract {
	/**
	 * Setup module strings
	 *
	 * @access  public
	 */
	public function set_module_strings() {
		$this->name        = __( 'Reading Progress Bar', 'themeisle-companion' );
		$this->description = __( 'Display a progress bar that shows readers how far they\'ve scrolled through your posts and pages. Perfect for long-form content and improving user engagement.', 'themeisle-companion' );
		$this->documentation_url = 'https://docs.themeisle.com/article/951-orbit-fox-documentation#progress-bar';
	}

	/**
	 * Determine if module should be loaded.
	 *
	 * @since   3.0.0
	 * @access  public
	 * @return bool
	 */
	public function enable_module() {
		return true;
	}

	/**
	 * The loading logic for the module.
	 *
	 * @since   3.0.0
	 * @access  public
	 */
	public function load() {
	}

	/**
	 * Method to define hooks needed.
	 *
	 * @since   3.0.0
	 * @access  public
	 * @return mixed | array
	 */
	public function hooks() {
		$this->loader->add_action( 'wp_footer', $this, 'render_progress_bar' );
	}

	public function render_progress_bar() {

		if ( ! $this->should_display_reading_progress_bar() ) {
			return;
		}

		$this->render_inline_styles();
		$this->render_inline_script();

		echo '<div class="obfx-rpb"><div class="obfx-rpb-inner"></div></div>';
	}

	/**
	 * Output inline styles for the progress bar.
	 *
	 * @return void
	 */
	private function render_inline_styles() {
		$color          = $this->get_option( 'color' );
		$mobile_size    = absint( $this->get_option( 'mobile_size' ) );
		$size           = absint( $this->get_option( 'size' ) );
		$position       = absint( $this->get_option( 'position' ) );
		$show_on_mobile = absint( $this->get_option( 'show_on_mobile' ) );

		$prop_map      = array( 'top', 'bottom', 'right', 'left' );
		$is_horizontal = ( $position < 2 );

		$start_prop     = $is_horizontal ? 'left' : 'top';          // start alignment of the bar
		$extent_prop    = $is_horizontal ? 'width' : 'height';      // axis the bar spans 100% across
		$thickness_prop = $is_horizontal ? 'height' : 'width';      // thickness of the bar
		$fixed_side     = $prop_map[ $position ];                   // side where the bar is fixed (top/bottom/left/right)
		$z_index        = ( is_admin_bar_showing() && $position === 0 ) ? 9999999 : 9999;

		$css = sprintf(
			'
.obfx-rpb {
  pointer-events: none;
  position: fixed;
  --obfx-rpb-size: %1$dpx;
  z-index: %2$d;
  %3$s: 0;
  %4$s: 100%%;
  %5$s: 0;
}
.obfx-rpb-inner {
  %6$s: var(--obfx-rpb-size);
  %4$s: var(--obfx-rpb-progress, 0%%);
  transition: %4$s 150ms linear;
  max-%4$s: 100%%;
  background-color: %7$s;
}
@media (max-width: 768px) {
  .obfx-rpb { display: %8$s; }
  .obfx-rpb-inner { --obfx-rpb-size: %9$dpx; }
}',
			$size,                // 1$d
			$z_index,             // 2$d
			$fixed_side,          // 3$s
			$extent_prop,         // 4$s
			$start_prop,          // 5$s
			$thickness_prop,      // 6$s
			$color,               // 7$s
			$show_on_mobile ? 'block' : 'none', // 8$s
			$mobile_size          // 9$d
		);
		?>

	<style>
		<?php echo wp_kses_post( $css ); ?>
	</style>

		<?php
	}

	/**
	 * Output inline script for the progress bar behavior.
	 *
	 * @param int $position
	 * @return void
	 */
	private function render_inline_script() {
		?>
	<script>
	  document.addEventListener('DOMContentLoaded', function() {
		const rpb = document.querySelector('.obfx-rpb');
		if (!rpb) {
		  return;
		}

		const updateProgress = function() {
		  const scrollPosition = window.scrollY;
		  const documentHeight = document.documentElement.scrollHeight;
		  const windowHeight = window.innerHeight;
		  const totalScrollable = documentHeight - windowHeight;
		  const progress = totalScrollable > 0 ? (scrollPosition / totalScrollable) * 100 : 0;
		  rpb.style.setProperty('--obfx-rpb-progress', `${progress.toFixed(2)}%`);
		};

		updateProgress();
		document.addEventListener('scroll', updateProgress, {
		  passive: true
		});
		window.addEventListener('resize', updateProgress);
	  });
	</script>
		<?php
	}

	/**
	 * Method that returns an array of scripts and styles to be loaded
	 * for the front end part.
	 *
	 * @since   3.0.0
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
	 * @since   3.0.0
	 * @access  public
	 * @return array
	 */
	public function admin_enqueue() {
		return array();
	}

	/**
	 * Method to define the options fields for the module
	 *
	 * @since   3.0.0
	 * @access  public
	 * @return array
	 */
	public function options() {
		$post_type_display_options = $this->get_post_types_display_options();

		$extra_options = array(
			array(
				'id'      => 'position',
				'title'   => __( 'Position', 'themeisle-companion' ),
				'name'    => 'position',
				'type'    => 'radio',
				'options' => array(
					__( 'Top', 'themeisle-companion' ),
					__( 'Bottom', 'themeisle-companion' ),
					__( 'Right', 'themeisle-companion' ),
					__( 'Left', 'themeisle-companion' ),
				),
				'default' => '0',
			),
			array(
				'id'      => 'show_on_mobile',
				'name'    => 'show_on_mobile',
				'type'    => 'toggle',
				'title'   => __( 'Display', 'themeisle-companion' ),
				'label'   => __( 'Show on mobile', 'themeisle-companion' ),
				'default' => '1',
			),
			array(
				'before_wrap' => true,
				'id'          => 'size',
				'name'        => 'size',
				'label'       => __( 'Desktop Size (px)', 'themeisle-companion' ),
				'type'        => 'number',
				'default'     => '4',
			),
			array(
				'id'      => 'mobile_size',
				'name'    => 'mobile_size',
				'label'   => __( 'Mobile Size (px)', 'themeisle-companion' ),
				'type'    => 'number',
				'default' => '2',
			),
			array(
				'after_wrap' => true,
				'id'         => 'color',
				'name'       => 'color',
				'type'       => 'color',
				'label'      => __( 'Color', 'themeisle-companion' ),
				'default'    => '#6366f1',
			),
		);

		$options = array_merge( $post_type_display_options, $extra_options );

		return $options;
	}

	/**
	 * Get the post types display options.
	 *
	 * @return array<array{
	 *  id: string,
	 *  title: string,
	 *  name: string,
	 *  type: string,
	 *  label: string,
	 *  class: string,
	 * }>
	 */
	private function get_post_types_display_options() {
		$options    = array();
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		foreach ( $post_types as $post_type ) {
			if ( ! $post_type instanceof WP_Post_Type ) {
				continue;
			}

			if ( $post_type->name === 'neve_custom_layouts' ) {
				continue;
			}

			$options[] = array(
				'id'      => 'display_on_' . $post_type->name,
				'title'   => $post_type->name === 'post' ? __( 'Display on', 'themeisle-companion' ) : '',
				'name'    => 'display_on_' . $post_type->name,
				'type'    => 'checkbox',
				'label'   => $post_type->labels->name,
				'class'   => 'inline-setting',
				'default' => $post_type->name === 'post' ? '1' : '0',
			);
		}

		return $options;
	}

	/**
	 * Check if social sharing should be displayed.
	 * 
	 * @since 3.0.0
	 * @access private
	 * 
	 * @return bool
	 */
	private function should_display_reading_progress_bar() {
		if ( ! is_singular() ) {
			return false;
		}

		if ( class_exists( 'WooCommerce' ) && ( is_cart() || is_checkout() ) ) {
			return false;
		}


		$option_slugs = array_map(
			function ( $option ) {
				return $option['id'];
			},
			$this->options()
		);

		$used_settings = array_filter(
			$option_slugs,
			function ( $key ) {
				return ( strpos( $key, 'display_on_' ) === 0 );
			}
		);

		if ( ! empty( $used_settings ) ) {
			foreach ( $used_settings as $key ) {
				$the_value = (int) $this->get_option( $key );

				$setting_key = str_replace( 'display_on_', '', $key );

				$post_display[ $setting_key ] = (bool) $the_value;
			}
		}

		$current_post_type = get_post_type();

		return isset( $post_display[ $current_post_type ] ) ? $post_display[ $current_post_type ] : false;
	}
}
