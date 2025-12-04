<?php

/**
 * Disable Comments Core Orbit Fox Module.
 *
 * @link       https://themeisle.com
 * @since      3.0.0
 *
 * @package    Disable_Comments_OBFX_Module
 */

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Disable_Comments_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 */
class Disable_Comments_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * Setup module strings
	 *
	 * @access  public
	 */
	public function set_module_strings() {
		$this->name              = __( 'Disable Comments', 'themeisle-companion' );
		$this->description       = __( 'A global kill switch for comments. Instantly closes comments on all existing posts and pages, removes the Comments section from admin menu, completely hides comment forms from your website frontend, and removes comment blocks from the editor.', 'themeisle-companion' );
		$this->documentation_url = 'https://docs.themeisle.com/article/951-orbit-fox-documentation#disable-comments';
	}

	/**
	 * Enable module by default
	 *
	 * @return bool
	 */
	public function enable_module() {
		return true;
	}

	/**
	 * Load module
	 */
	public function load() {
	}

	/**
	 * Define module options
	 *
	 * @return array
	 */
	public function options() {
		return array();
	}

	/**
	 * Admin enqueue scripts and styles
	 *
	 * @return array
	 */
	public function admin_enqueue() {
		return array();
	}

	/**
	 * Public enqueue scripts and styles
	 *
	 * @return array
	 */
	public function public_enqueue() {
		return array();
	}

	/**
	 * Register hooks
	 */
	public function hooks() {
		// Redirect to home page if trying to access comments admin page.
		$this->loader->add_action( 'admin_init', $this, 'admin_redirect' );
		// Remove comments menu from admin.
		$this->loader->add_action( 'admin_menu', $this, 'remove_admin_menus', 9999 );
		// Remove comments from admin bar.
		$this->loader->add_action( 'wp_before_admin_bar_render', $this, 'remove_admin_bar_comments' );
		// Remove comment support from post types.
		$this->loader->add_action( 'init', $this, 'remove_post_types_support', 100 );
		// Remove recent comments widget
		$this->loader->add_action( 'widgets_init', $this, 'remove_recent_comments_widget' );
		// Remove comment blocks.
		$this->loader->add_action( 'allowed_block_types_all', $this, 'remove_comment_blocks', PHP_INT_MAX );
		// Disable comments pre query.
		$this->loader->add_filter( 'comments_pre_query', $this, 'disable_comments_pre_query', 10, 2 );

		// Disable comments on frontend.
		add_filter( 'comments_open', '__return_false', 20, 2 );
		// Disable pings on frontend.
		add_filter( 'pings_open', '__return_false', 20, 2 );
		// Hide existing comments.
		add_filter( 'comments_array', '__return_empty_array', 10, 2 );
		// Also show 0 comments count.
		add_filter( 'get_comments_number', '__return_zero', 10, 2 );
	}

	/**
	 * Admin initialization
	 */
	public function admin_redirect() {
		global $pagenow;
		if ( $pagenow === 'edit-comments.php' || $pagenow === 'options-discussion.php' ) {
			wp_redirect( admin_url() );
			exit;
		}
	}

	/**
	 * Remove comment-related menus from admin
	 */
	public function remove_admin_menus() {
		// Remove main Comments menu
		remove_menu_page( 'edit-comments.php' );
		// Remove Comments submenu from Settings
		remove_submenu_page( 'options-general.php', 'options-discussion.php' );
	}

	/**
	 * Remove comments from admin bar
	 */
	public function remove_admin_bar_comments() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
	}

	/**
	 * Remove comment support from all post types
	 */
	public function remove_post_types_support() {
		$post_types = get_post_types();
		foreach ( $post_types as $post_type ) {
			remove_post_type_support( $post_type, 'comments' );
			remove_post_type_support( $post_type, 'trackbacks' );
		}
	}

	/**
	 * Remove recent comments widget
	 */
	public function remove_recent_comments_widget() {
		unregister_widget( 'WP_Widget_Recent_Comments' );

		// Remove recent comments style from head
		add_filter( 'show_recent_comments_widget_style', '__return_false' );
	}
  
	/**
	 * Remove the comment blocks
	 *
	 * @param array $allowed_block_types Array of allowed block types.
	 *
	 * @return array
	 */
	public function remove_comment_blocks( $allowed_block_types ) {
		$disallowed_blocks = array(
			'core/comment-author-name',
			'core/comment-content',
			'core/comment-date',
			'core/comment-edit-link',
			'core/comment-reply-link',
			'core/comment-template',
			'core/comments',
			'core/comments-pagination',
			'core/comments-pagination-next',
			'core/comments-pagination-numbers',
			'core/comments-pagination-previous',
			'core/comments-title',
			'core/post-comments',
			'core/post-comments-form',
			'core/latest-comments',
		);

		$disallowed_blocks = apply_filters( 'obfx/disable-comments/disallowed-blocks', $disallowed_blocks );

		if ( ! is_array( $allowed_block_types ) || empty( $allowed_block_types ) ) {
			$registered_blocks   = WP_Block_Type_Registry::get_instance()->get_all_registered();
			$allowed_block_types = array_keys( $registered_blocks );
		}

		$filtered_blocks = array();

		foreach ( $allowed_block_types as $block ) {
			if ( ! in_array( $block, $disallowed_blocks, true ) ) {
				$filtered_blocks[] = $block;
			}
		}

		return $filtered_blocks;
	}

	/**
	 * Disable comments pre query
	 *
	 * @param array $comments Array of comments.
	 * @param WP_Comment_Query $query Query object.
	 *
	 * @return array|int
	 */
	public function disable_comments_pre_query( $comments, $query ) {
		if ( is_a( $query, '\WP_Comment_Query' ) && $query->query_vars['count'] ) {
			return 0;
		}

		return array();
	}
}
