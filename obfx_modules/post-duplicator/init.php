<?php

/**
 * Post Duplicator Core Orbit Fox Module.
 *
 * @link       https://themeisle.com
 * @since      3.0.0
 *
 * @package    Post_Duplicator_OBFX_Module
 */

/**
 * The class defines a new module to be used by Orbit Fox plugin.
 *
 * @package    Post_Duplicator_OBFX_Module
 * @author     Themeisle <friends@themeisle.com>
 * @codeCoverageIgnore
 */
class Post_Duplicator_OBFX_Module extends Orbit_Fox_Module_Abstract {

	/**
	 * Setup module strings
	 *
	 * @access  public
	 */
	public function set_module_strings() {
		$this->name              = __( 'Post Duplicator', 'themeisle-companion' );
		$this->description       = __( 'Adds "Clone" option to posts and pages in the WordPress admin list. Creates new drafts copying all content and settings - a huge time-saver for managing content with complex layouts.', 'themeisle-companion' );
		$this->documentation_url = 'https://docs.themeisle.com/article/951-orbit-fox-documentation#post-duplicator';
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
		// Add duplicate links to post lists
		$this->loader->add_filter( 'post_row_actions', $this, 'add_duplicate_link', 10, 2 );
		$this->loader->add_filter( 'page_row_actions', $this, 'add_duplicate_link', 10, 2 );

		// Handle duplicate action
		$this->loader->add_action( 'admin_action_duplicate_post', $this, 'handle_duplicate_post' );

		// Add duplicate links for all post types
		$this->loader->add_action( 'admin_init', $this, 'add_all_post_type_duplicate_links' );
	}

	/**
	 * Add duplicate link to post/page row actions
	 *
	 * @param array    $actions Array of row action links
	 * @param WP_Post  $post   The post object
	 * @return array
	 */
	public function add_duplicate_link( $actions, $post ) {
		// Check if user can edit this post type
		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return $actions;
		}

		$duplicate_url = wp_nonce_url(
			admin_url( 'admin.php?action=duplicate_post&post=' . $post->ID ),
			'duplicate_post_' . $post->ID,
			'duplicate_nonce'
		);

		$actions['duplicate'] = sprintf(
			'<a href="%s" class="duplicate-post-link" title="%s">%s</a>',
			esc_url( $duplicate_url ),
			esc_attr__( 'Create a duplicate of this post', 'themeisle-companion' ),
			esc_html__( 'Clone', 'themeisle-companion' )
		);

		return $actions;
	}

	/**
	 * Add duplicate links for all post types
	 */
	public function add_all_post_type_duplicate_links() {
		// Get all post types (built-in and custom)
		$post_types = get_post_types( array(), 'objects' );

		foreach ( $post_types as $post_type ) {
			// Skip attachments and other non-content post types
			if ( in_array( $post_type->name, array( 'attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset' ) ) ) {
				continue;
			}

			// Add filter for this post type's row actions
			$this->loader->add_filter( $post_type->name . '_row_actions', $this, 'add_duplicate_link', 10, 2 );
		}
	}

	/**
	 * Handle the duplicate post action
	 */
	public function handle_duplicate_post() {
		if ( ! isset( $_GET['post'], $_GET['duplicate_nonce'] ) ) {
			wp_die( esc_html__( 'Missing required parameters.', 'themeisle-companion' ) );
		}

		$nonce   = sanitize_text_field( $_GET['duplicate_nonce'] );
		$post_id = intval( sanitize_text_field( $_GET['post'] ) );

		// Verify nonce
		if ( ! wp_verify_nonce( $nonce, 'duplicate_post_' . $post_id ) ) {
			wp_die( esc_html__( 'Security check failed.', 'themeisle-companion' ) );
		}

		$post = get_post( $post_id );

		if ( ! $post ) {
			wp_die( esc_html__( 'Post not found.', 'themeisle-companion' ) );
		}

		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			wp_die( esc_html__( 'You do not have permission to duplicate this post.', 'themeisle-companion' ) );
		}

		$duplicate_id = $this->duplicate_post( $post );

		if ( $duplicate_id ) {
			// Redirect to the edit page of the new post
			$redirect_url = add_query_arg(
				array(
					'post'       => $duplicate_id,
					'action'     => 'edit',
					'duplicated' => '1',
				),
				admin_url( 'post.php' )
			);
			wp_redirect( $redirect_url );
			exit;
		} else {
			wp_die( esc_html__( 'Failed to duplicate post.', 'themeisle-companion' ) );
		}
	}

	/**
	 * Duplicate a post
	 *
	 * @param WP_Post $post The post to duplicate
	 * @return int|false The new post ID or false on failure
	 */
	private function duplicate_post( $post ) {
		$slug = $post->post_name;
		if ( ! empty( $slug ) ) {
			$slug    = $slug . '-copy';
			$counter = 1;
			while ( get_page_by_path( $slug, OBJECT, $post->post_type ) ) {
				$slug = $post->post_name . '-copy-' . $counter;
				$counter++;
			}
		}

		$post_data = array(
			'post_title'     => $post->post_title . ' - ' . __( 'Copy', 'themeisle-companion' ),
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_status'    => 'draft',
			'post_type'      => $post->post_type,
			'post_name'      => $slug,
			'post_parent'    => $post->post_parent,
			'menu_order'     => $post->menu_order,
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => get_current_user_id(),
		);

		$duplicate_id = wp_insert_post( $post_data );

		if ( is_wp_error( $duplicate_id ) ) {
			return false;
		}

		$this->duplicate_post_meta( $post->ID, $duplicate_id );
		$this->duplicate_post_taxonomies( $post->ID, $duplicate_id );
		$this->set_featured_image( $post->ID, $duplicate_id );

		return $duplicate_id;
	}

	/**
	 * Duplicate post meta fields
	 *
	 * @param int $original_id Original post ID
	 * @param int $duplicate_id Duplicate post ID
	 */
	private function duplicate_post_meta( $original_id, $duplicate_id ) {
		$meta_keys = get_post_custom_keys( $original_id );

		if ( ! $meta_keys ) {
			return;
		}

		// Meta keys to skip (these are usually auto-generated or should not be duplicated)
		$skip_keys = apply_filters(
			'obfx_post_duplicator_skip_meta_keys',
			array(
				'_edit_lock',
				'_edit_last',
				'_wp_page_template',
			),
			$original_id
		);

		foreach ( $meta_keys as $meta_key ) {
			if ( in_array( $meta_key, $skip_keys ) ) {
				continue;
			}

			$meta_values = get_post_meta( $original_id, $meta_key, false );

			foreach ( $meta_values as $meta_value ) {
				add_post_meta( $duplicate_id, $meta_key, $meta_value );
			}
		}
	}

	/**
	 * Duplicate post taxonomies
	 *
	 * @param int $original_id Original post ID
	 * @param int $duplicate_id Duplicate post ID
	 */
	private function duplicate_post_taxonomies( $original_id, $duplicate_id ) {
		$taxonomies = get_object_taxonomies( get_post_type( $original_id ) );

		foreach ( $taxonomies as $taxonomy ) {
			$terms = wp_get_object_terms( $original_id, $taxonomy, array( 'fields' => 'slugs' ) );
			wp_set_object_terms( $duplicate_id, $terms, $taxonomy );
		}
	}

	/**
	 * Duplicate featured image
	 *
	 * @param int $original_id Original post ID
	 * @param int $duplicate_id Duplicate post ID
	 */
	private function set_featured_image( $original_id, $duplicate_id ) {
		$thumbnail_id = get_post_thumbnail_id( $original_id );

		if ( $thumbnail_id ) {
			set_post_thumbnail( $duplicate_id, $thumbnail_id );
		}
	}
}
