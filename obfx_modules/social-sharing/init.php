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

	/**
	 * Social_Sharing_OBFX_Module  constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {
	    parent::__construct();
		$this->name = __( 'Social Sharing Module', 'obfx' );
		$this->description = __( 'Add basic social sharing to your posts and pages.', 'obfx' );
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
			    'wp_footer' => 'social_sharing_function',
		    )
	    );
    }

    /**
     * Display method for the Social Sharing.
     *
     * @since   1.0.0
     * @access  public
     */
    public function social_sharing_function() {
        if ( is_single() || is_page() ) {
        	$class = 'obfx-core-social-sharing-icons-right';
        	if( $this->get_option( 'socials_position' ) == '0' ) {
        		$class = 'obfx-core-social-sharing-icons-left';
	        }
		    $data = array(
			    'position_class'     => $class,
			    'social_links' => $this->social_links_array(),
			    'show_name'    => $this->get_option( 'network_name' ),
		    );

		    return $this->render_view( 'social-sharing', $data );
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
    	$post_categories = strip_tags( get_the_category_list( ',' ) );
		$post_title = get_the_title();

    	if( $this->get_option( 'facebook') ) {
		    $link = 'https://www.facebook.com/sharer.php?u=' . get_post_permalink();
		    $social_links['Facebook'] = $this->set_social_icon_array( $link, 'facebook' );
	    }

    	if( $this->get_option( 'twitter') ) {
		    $link = 'https://twitter.com/intent/tweet?url=' . get_post_permalink() . '&text=' . $post_title . '&hashtags=' . $post_categories;
		    $social_links['Twitter'] = $this->set_social_icon_array( $link, 'twitter');
	    }

    	if( $this->get_option( 'g-plus') ) {
		    $link = 'https://plus.google.com/share?url=' . get_post_permalink();
		    $social_links['Google Plus'] = $this->set_social_icon_array( $link, 'google-googleplus' );
	    }

		if( $this->get_option('pinterest' ) ) {
    		$link = 'https://pinterest.com/pin/create/bookmarklet/?media='. get_the_post_thumbnail_url() .'&url='. get_post_permalink() .'&description=' . $post_title;
    		$social_links['Pinterest'] = $this->set_social_icon_array( $link, 'pinterest' );
		}

		if( $this->get_option('linkedin' ) ) {
    		$link = 'https://www.linkedin.com/shareArticle?url='. get_post_permalink() .'&title='. $post_title;
    		$social_links['LinkedIn'] = $this->set_social_icon_array( $link, 'linkedin' );
		}

		if( $this->get_option('tumblr' ) ) {
    		$link = 'https://www.tumblr.com/widgets/share/tool?canonicalUrl=' . get_post_permalink() . '&title=' . $post_title;
    		$social_links['Tumblr'] = $this->set_social_icon_array( $link, 'tumblr' );
		}

		if( $this->get_option('reddit' ) ) {
    		$link = 'https://reddit.com/submit?url=' . get_post_permalink() . '&title=' . $post_title;
    		$social_links['Reddit'] = $this->set_social_icon_array( $link, 'reddit' );
		}

		if( $this->get_option('vk' ) ) {
    		$link = 'http://vk.com/share.php?url={url}';
    		$social_links['VKontakte'] = $this->set_social_icon_array( $link, 'vkontakte' );
		}

		if( $this->get_option('okru' ) ) {
    		$link = 'https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&st.shareUrl={url}&title={title}';
    		$social_links['OK.ru'] = $this->set_social_icon_array( $link, 'sharethis' );
		}

		if( $this->get_option('douban' ) ) {
    		$link = 'http://www.douban.com/recommend/?url={url}&title={title}';
    		$social_links['Douban'] = $this->set_social_icon_array( $link, 'douban' );
		}

		if( $this->get_option('baidu' ) ) {
    		$link = 'http://cang.baidu.com/do/add?it={title}&iu={url}';
    		$social_links['Baidu'] = $this->set_social_icon_array( $link, 'baidu' );
		}

		if( $this->get_option('xing' ) ) {
    		$link = 'https://www.xing.com/app/user?op=share&url={url}';
    		$social_links['Xing'] = $this->set_social_icon_array( $link, 'xing' );
		}

		if( $this->get_option('renren' ) ) {
    		$link = 'http://widget.renren.com/dialog/share?resourceUrl={url}&srcUrl={url}&title={title}';
    		$social_links['RenRen'] = $this->set_social_icon_array( $link, 'renren' );
		}

		if( $this->get_option('weibo' ) ) {
    		$link = 'http://service.weibo.com/share/share.php?url={url}&appkey=&title={text}&pic=&ralateUid=';
    		$social_links['Weibo'] = $this->set_social_icon_array( $link, 'weibo' );
		}

	    return $social_links;
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
				'title'   => 'Alignment',
				'name'    => 'socials_position',
				'type'    => 'radio',
				'options' => array(
					'0' => 'Left',
					'1' => 'Right',
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
				'label'   => '<i class="socicon-sharethis"></i>  - OKru',
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