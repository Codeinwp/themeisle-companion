<?php
/**
 * Social Sharing Core Orbit Fox Module.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Social_Sharing_OBFX_Module
 */

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Social_Sharing_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 * @codeCoverageIgnore
 */
class Social_Sharing_OBFX_Module extends Orbit_Fox_Module_Abstract {

	private $social_share_links = array();

	/**
	 * Setup module strings
	 *
	 * @access  public
	 */
	public function set_module_strings() {
		$this->name = __( 'Social Sharing Module', 'themeisle-companion' );
		/*
		 * translators: %s Document anchor link.
		 */
		$this->description       = __( 'Add basic social sharing to your posts and pages.', 'themeisle-companion' );
		$this->documentation_url = 'https://docs.themeisle.com/article/951-orbit-fox-documentation#social-sharing';
	}

	/**
	 * Define the array that contains the social networks.
	 */
	private function define_networks() {
		$post_categories = wp_strip_all_tags( get_the_category_list( ',' ) );
		$post_title      = get_the_title();
		$post_link       = get_the_permalink();

		$this->social_share_links = array(
			'facebook'  => array(
				'link'     => 'https://www.facebook.com/sharer.php?u=' . $post_link,
				'nicename' => 'Facebook',
				'icon'     => 'facebook',
			),
			'twitter'   => array(
				'link'     => 'https://twitter.com/intent/tweet?url=' . $post_link . '&text=' . $post_title . '&hashtags=' . $post_categories,
				'nicename' => 'X',
				'icon'     => 'twitter',
			),
			'pinterest' => array(
				'link'     => 'https://pinterest.com/pin/create/bookmarklet/?media=' . get_the_post_thumbnail_url() . '&url=' . $post_link . '&description=' . $post_title,
				'nicename' => 'Pinterest',
				'icon'     => 'pinterest',
			),
			'linkedin'  => array(
				'link'     => 'https://www.linkedin.com/shareArticle?url=' . $post_link . '&title=' . $post_title,
				'nicename' => 'LinkedIn',
				'icon'     => 'linkedin',
			),
			'tumblr'    => array(
				'link'     => 'https://www.tumblr.com/widgets/share/tool?canonicalUrl=' . $post_link . '&title=' . $post_title,
				'nicename' => 'Tumblr',
				'icon'     => 'tumblr',
			),
			'reddit'    => array(
				'link'     => 'https://reddit.com/submit?url=' . $post_link . '&title=' . $post_title,
				'nicename' => 'Reddit',
				'icon'     => 'reddit',
			),
			'whatsapp'  => array(
				'link'     => 'whatsapp://send?text=' . $post_link,
				'nicename' => 'WhatsApp',
				'icon'     => 'whatsapp',
				'target'   => '0',
			),
			'mail'      => array(
				'link'     => 'mailto:?&subject=' . $post_title . '&body=' . $post_link,
				'nicename' => 'Email',
				'icon'     => 'mail',
				'target'   => '0',
			),
			'sms'       => array(
				'link'     => 'sms://?&body=' . $post_title . ' - ' . $post_link,
				'nicename' => 'SMS',
				'icon'     => 'sms',
				'target'   => '0',
			),
			'vk'        => array(
				'link'     => 'http://vk.com/share.php?url=' . $post_link,
				'nicename' => 'VKontakte',
				'icon'     => 'vkontakte',
			),
			'okru'      => array(
				'link'     => 'https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&st.shareUrl=' . $post_link . '&title=' . $post_title,
				'nicename' => 'OK.ru',
				'icon'     => 'odnoklassniki',
			),
			'douban'    => array(
				'link'     => 'http://www.douban.com/recommend/?url=' . $post_link . '&title=' . $post_title,
				'nicename' => 'Douban',
				'icon'     => 'douban',
			),
			'baidu'     => array(
				'link'     => 'http://cang.baidu.com/do/add?it=' . $post_title . '&iu=' . $post_link,
				'nicename' => 'Baidu',
				'icon'     => 'baidu',
			),
			'xing'      => array(
				'link'     => 'https://www.xing.com/app/user?op=share&url=' . $post_link,
				'nicename' => 'Xing',
				'icon'     => 'xing',
			),
			'renren'    => array(
				'link'     => 'http://widget.renren.com/dialog/share?resourceUrl=' . $post_link . '&srcUrl=' . $post_link . '&title=' . $post_title,
				'nicename' => 'RenRen',
				'icon'     => 'renren',
			),
			'weibo'     => array(
				'link'     => 'http://service.weibo.com/share/share.php?url=' . $post_link . '&appkey=&title=' . $post_title . '&pic=&ralateUid=',
				'nicename' => 'Weibo',
				'icon'     => 'weibo',
			),
			'telegram'  => array(
				'link'     => 'https://t.me/share/url?url=' . $post_link . '&text=' . $post_title,
				'nicename' => 'Telegram',
				'icon'     => 'telegram',
			),
			'mastodon'  => array(
				'link'     => 'https://mastodonshare.com/?text=' . $post_title . '&url=' . $post_link,
				'nicename' => 'Mastodon',
				'icon'     => 'mastodon',
			),
			'bluesky'   => array(
				'link'     => 'https://bsky.app/intent/compose?text=' . urlencode( $post_title . ' ' . $post_link ),
				'nicename' => 'Bluesky',
				'icon'     => 'bluesky',
			),
			'threads'   => array(
				'link'     => 'https://threads.net/intent/post?text=' . urlencode( $post_title . ' ' . $post_link ),
				'nicename' => 'Threads',
				'icon'     => 'threads',
			),
		);
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
	 * @return mixed | array
	 */
	public function hooks() {
		$this->loader->add_filter( 'kses_allowed_protocols', $this, 'custom_allowed_protocols', 1000 );

		if ( $this->get_option( 'socials_position' ) == 2 ) {
			$this->loader->add_filter( 'hestia_filter_blog_social_icons', $this, 'social_sharing_function' );
			return true;
		}
		$this->loader->add_action( 'wp_footer', $this, 'social_sharing_function' );
	}

	/**
	 * Display method for the Social Sharing.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function social_sharing_function() {
		if ( ! $this->should_display_social_sharing() ) {
			return;
		}

		$class_desktop = 'obfx-sharing-left ';
		switch ( $this->get_option( 'socials_position' ) ) {
			case '1':
				$class_desktop = 'obfx-sharing-right ';
				break;
			case '2':
				$class_desktop = 'obfx-sharing-inline ';
		}

		$class_mobile = '';
		if ( $this->get_option( 'mobile_position' ) == '0' ) {
			$class_mobile = 'obfx-sharing-bottom';
		}

		$data = array(
			'desktop_class'      => $class_desktop,
			'mobile_class'       => $class_mobile,
			'show_name'          => $this->get_option( 'network_name' ),
			'social_links_array' => $this->social_links_array(),
		);

		if ( ! class_exists( 'OBFX_Social_Icons' ) ) {
			require_once OBX_PATH . '/obfx_modules/social-sharing/social-icons.php';
		}

		if ( $this->get_option( 'socials_position' ) == 2 ) {
			return $this->render_view( 'hestia-social-sharing', $data );
		}
		echo $this->render_view( 'social-sharing', $data ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Create the social links array to be passed to the front end view.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @return array
	 */
	private function social_links_array() {
		$social_links = array();
		foreach ( $this->social_share_links as $network => $network_links ) {
			if ( $this->get_option( $network ) ) {
				$social_links[ $network ]                 = $network_links;
				$social_links[ $network ]['show_mobile']  = $this->get_option( $network . '-mobile-show' );
				$social_links[ $network ]['show_desktop'] = $this->get_option( $network . '-desktop-show' );
			}
		}
		return $social_links;
	}

	/**
	 * Add extra protocols to list of allowed protocols.
	 *
	 * @param array $protocols List of protocols from core.
	 *
	 * @return array Updated list including extra protocols added.
	 */
	public function custom_allowed_protocols( $protocols ) {
		$protocols[] = 'whatsapp';
		$protocols[] = 'sms';
		return $protocols;
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
		if ( ! $this->should_display_social_sharing() ) {
			return array();
		}

		return array(
			'css' => array(
				'public' => false,
			),
			'js'  => array(
				'public' => false,
			),
		);
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
		$post_type_display_options = $this->get_post_types_display_options();

		$extra_options = array(
			array(
				'id'      => 'socials_position',
				'title'   => 'Desktop Position',
				'name'    => 'socials_position',
				'type'    => 'radio',
				'options' => array(
					'0' => 'Left',
					'1' => 'Right',
				),
				'default' => '0',
			),
			array(
				'id'      => 'mobile_position',
				'name'    => 'mobile_position',
				'title'   => 'Mobile Position',
				'type'    => 'radio',
				'options' => array(
					'0' => 'Pinned to bottom',
					'1' => 'Same as desktop',
				),
				'default' => '1',
			),
			array(
				'id'      => 'network_name',
				'name'    => 'network_name',
				'title'   => 'Show name',
				'type'    => 'toggle',
				'label'   => 'Show network name on hover',
				'default' => '0',
			),
		);

		$options = array_merge( $post_type_display_options, $extra_options );

		$this->define_networks();

		if ( ! class_exists( 'OBFX_Social_Icons' ) ) {
			require_once OBX_PATH . '/obfx_modules/social-sharing/social-icons.php';
		}

		$icons = new OBFX_Social_Icons();

		foreach ( $this->social_share_links as $network => $data_array ) {
			$options[] = array(
				'before_wrap' => '<div class="obfx-row">',
				'title'       => ( $network == 'facebook' ) ? 'Networks' : '',
				'id'          => $network,
				'name'        => $network,
				'label'       => $data_array['nicename'],
				'icon'        => '<i class="obfx-toggle-icon ' . esc_attr( $data_array['icon'] ) . '">' . $icons->get_icon( $data_array['icon'] ) . '</i>',
				'type'        => 'toggle',
				'default'     => ( $network == 'facebook' ) ? '1' : '',
				'class'       => 'inline-setting network-toggle',
			);

			$options[] = array(
				'id'      => $network . '-desktop-show',
				'name'    => $network . '-desktop-show',
				'label'   => 'desktop',
				'type'    => 'checkbox',
				'default' => '1',
				'class'   => 'inline-setting show',
			);

			$options[] = array(
				'id'         => $network . '-mobile-show',
				'name'       => $network . '-mobile-show',
				'label'      => 'mobile',
				'type'       => 'checkbox',
				'default'    => '1',
				'class'      => 'inline-setting show last',
				'after_wrap' => '</div>',
			);
		}

		$options = $this->add_hestia_options( $options );

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
		$options = array(
			array(
				'id'      => 'display_on_posts',
				'title'   => 'Display On',
				'name'    => 'display_on_posts',
				'type'    => 'checkbox',
				'label'   => 'Posts',
				'class'   => 'inline-setting',
				'default' => '1',
			),
			array(
				'id'      => 'display_on_pages',
				'title'   => '',
				'name'    => 'display_on_pages',
				'type'    => 'checkbox',
				'label'   => 'Pages',
				'class'   => 'inline-setting',
				'default' => '0',
			),
		);


		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		foreach ( $post_types as $post_type ) {
			if ( ! $post_type instanceof WP_Post_Type ) {
				continue;
			}

			if ( in_array( $post_type->name, array( 'post', 'page' ) ) ) {
				continue;
			}

			if ( $post_type->name === 'neve_custom_layouts' ) {
				continue;
			}


			$options[] = array(
				'id'      => 'display_on_' . $post_type->name,
				'title'   => '',
				'name'    => 'display_on_' . $post_type->name,
				'type'    => 'checkbox',
				'label'   => $post_type->labels->name,
				'class'   => 'inline-setting',
				'default' => '1',
			);
		}

		return $options;
	}

	/**
	 * Add hestia options.
	 */
	private function add_hestia_options( $options ) {
		if ( defined( 'HESTIA_VERSION' ) ) {
			$option_id                             = $this->search_for_id( 'socials_position', $options );
			$options[ $option_id ]['options']['2'] = 'Inline after content';
		}
		return $options;
	}

	/**
	 * Search for module option by id.
	 *
	 * @param $index
	 *
	 * @return int|null|string
	 */
	private function search_for_id( $index, $options ) {
		foreach ( $options as $key => $val ) {
			if ( $val['id'] === $index ) {
				return $key;
			}
		}
		return null;
	}

	/**
	 * Check if social sharing should be displayed.
	 * 
	 * @since 3.0.0
	 * @access private
	 * 
	 * @return bool
	 */
	private function should_display_social_sharing() {
		if ( ! is_singular() ) {
			return false;
		}

		if ( class_exists( 'WooCommerce' ) && ( is_cart() || is_checkout() ) ) {
			return false;
		}

		$data = get_option( 'obfx_data' );

		$post_display = array(
			'post' => true,
			'page' => false,
		);

		if ( isset( $data['module_settings'], $data['module_settings']['social-sharing'] ) ) {
			$used_settings = array_filter(
				$data['module_settings']['social-sharing'],
				function ( $value, $key ) {
					return ( strpos( $key, 'display_on_' ) === 0 );
				},
				ARRAY_FILTER_USE_BOTH
			);

			if ( ! empty( $used_settings ) ) {
				foreach ( $used_settings as $key => $value ) {
					if ( $key === 'display_on_posts' ) {
						$post_display['post'] = (bool) $value;

						continue;
					}

					if ( $key === 'display_on_pages' ) {
						$post_display['page'] = (bool) $value;

						continue;
					}

					$setting_key = str_replace( 'display_on_', '', $key );

					$post_display[ $setting_key ] = (bool) $value;
				}
			}
		}

		$current_post_type = get_post_type();

		// We default to post as previously everything inherited from posts.
		if ( ! isset( $post_display[ $current_post_type ] ) ) {
			return $post_display['post'];
		}

		return $post_display[ $current_post_type ];
	}
}
