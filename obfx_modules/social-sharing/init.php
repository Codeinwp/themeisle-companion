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
	 * Social_Sharing_OBFX_Module  constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		$this->name               = __( 'Social Sharing Module', 'obfx' );
		$this->description        = __( 'Add basic social sharing to your posts and pages.', 'obfx' );
	}

	private function define_networks() {
    	$post_categories = strip_tags( get_the_category_list( ',' ) );
		$post_title = get_the_title();
		$post_link = get_post_permalink();

		$this->social_share_links = array(
			'facebook'  => array(
				'link'     => 'https://www.facebook.com/sharer.php?u=' . $post_link,
				'nicename' => 'Facebook',
				'icon'     => 'facebook',
			),
			'twitter'   => array(
				'link'     => 'https://twitter.com/intent/tweet?url=' . $post_link . '&text=' . $post_title . '&hashtags=' . $post_categories,
				'nicename' => 'Twitter',
				'icon'     => 'twitter',
			),
			'g-plus'    => array(
				'link'     => 'https://plus.google.com/share?url=' . $post_link,
				'nicename' => 'Google Plus',
				'icon'     => 'googleplus',
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
     * @return array
     */
    public function hooks() {
        $this->loader->add_action('wp_footer', $this, 'social_sharing_function' );
    }

    /**
     * Display method for the Social Sharing.
     *
     * @since   1.0.0
     * @access  public
     */
    public function social_sharing_function() {
        if ( is_single() || is_page() && ! is_front_page() ) {
	        $this->define_networks();
        	$class_desktop = 'obfx-core-social-sharing-icons-right ';
        	if( $this->get_option( 'socials_position' ) == '0' ) {
        		$class_desktop = 'obfx-core-social-sharing-icons-left ';
	        }

	        $class_mobile = '';
        	if( $this->get_option( 'mobile_position' ) == '1' ) {
        		$class_mobile = 'obfx-core-social-sharing-icons-bottom ';
	        }

		    $data = array(
		    	'desktop_class' => $class_desktop,
		    	'mobile_class'  => $class_mobile,
		    	'show_name' => $this->get_option( 'network_name' ),
		    	'social_links_array' => $this->social_links_array(),
		    );

		    echo $this->render_view( 'social-sharing', $data );
	    }
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
		foreach ( $this->social_share_links as $network => $array_data ) {
			$network_links = $this->get_network_links( $network );
			if( ! empty( $network_links ) ) {
				$social_links[ $network ] = $network_links;
			}
		}
	    return $social_links;
    }


    private function get_network_links( $network ) {
	    if( $this->get_option( $network ) ) {
		    return $this->social_share_links[$network];
	    }
	    return array();
    }

	/**
	 * Utility method to return an array that contains the link and the icon class.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param string $link The social icon link.
	 * @param string $icon_class The icon class.
	 * @return array
	 */
    private function set_social_icon_array( $link, $icon_class ) {
    	return array(
    		'link' => $link,
	        'icon_class' => $icon_class,
	    );
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
	    return array(
		    'css' => array(
				'public' => false,
				'vendor/socicon/socicon' => false,
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
		return array(
			'css' => array(
				'admin' => false,
				'vendor/socicon/socicon' => false,
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
				'label'   => 'Show network name on hover',
				'type'    => 'radio',
				'options' => array(
					'1' => 'Pinned to bottom',
					'0' => 'Pinned to the side',
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
			array(
				'title'   => 'Enabled for',
				'id'      => 'facebook',
				'name'    => 'facebook',
				'label'   => '<i class="socicon-facebook"></i>  - Facebook',
				'type'    => 'toggle',
				'default' => '1',
			),
			array(
				'id'      => 'twitter',
				'name'    => 'twitter',
				'label'   => '<i class="socicon-twitter"></i>  -  Twitter',
				'type'    => 'toggle',
				'default' => '1',
			),
			array(
				'id'      => 'g-plus',
				'name'    => 'g-plus',
				'label'   => '<i class="socicon-googleplus"></i>  - Google Plus',
				'type'    => 'toggle',
				'default' => '1',
			),
			array(
				'id'      => 'pinterest',
				'name'    => 'pinterest',
				'label'   => '<i class="socicon-pinterest"></i>  - Pinterest',
				'type'    => 'toggle',
				'default' => '0',
			),
			array(
				'id'      => 'linkedin',
				'name'    => 'linkedin',
				'label'   => '<i class="socicon-linkedin"></i>  - LinkedIn',
				'type'    => 'toggle',
				'default' => '0',
			),
			array(
				'id'      => 'tumblr',
				'name'    => 'tumblr',
				'label'   => '<i class="socicon-tumblr"></i>  - Tumblr',
				'type'    => 'toggle',
				'default' => '0',
			),
			array(
				'id'      => 'reddit',
				'name'    => 'reddit',
				'label'   => '<i class="socicon-reddit"></i>  - Reddit',
				'type'    => 'toggle',
				'default' => '0',
			),
			array(
				'id'      => 'vk',
				'name'    => 'vk',
				'label'   => '<i class="socicon-vkontakte"></i>  - vKontakte',
				'type'    => 'toggle',
				'default' => '0',
			),
			array(
				'id'      => 'okru',
				'name'    => 'okru',
				'label'   => '<i class="socicon-odnoklassniki"></i>  - OKru',
				'type'    => 'toggle',
				'default' => '0',
			),
			array(
				'id'      => 'douban',
				'name'    => 'douban',
				'label'   => '<i class="socicon-douban"></i>  - Douban',
				'type'    => 'toggle',
				'default' => '0',
			),
			array(
				'id'      => 'baidu',
				'name'    => 'baidu',
				'label'   => '<i class="socicon-baidu"></i>  - Baidu',
				'type'    => 'toggle',
				'default' => '0',
			),
			array(
				'id'      => 'xing',
				'name'    => 'xing',
				'label'   => '<i class="socicon-xing"></i>  - Xing',
				'type'    => 'toggle',
				'default' => '0',
			),
			array(
				'id'      => 'renren',
				'name'    => 'renren',
				'label'   => '<i class="socicon-renren"></i>  - RenRen',
				'type'    => 'toggle',
				'default' => '0',
			),
			array(
				'id'      => 'weibo',
				'name'    => 'weibo',
				'label'   => '<i class="socicon-weibo"></i>  - Weibo',
				'type'    => 'toggle',
				'default' => '0',
			),
		);

	}
}