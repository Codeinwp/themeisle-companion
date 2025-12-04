<?php
/**
 * Clients Widget
 *
 * @since 1.0.0
 *
 * @package themeisle-companion
 */


/**
 * Class Zerif_Clients_Widget
 */
if ( ! class_exists( 'Zerif_Clients_Widget' ) ) {

	class Zerif_Clients_Widget extends WP_Widget {

		/**
		 * Zerif_Clients_Widget constructor.
		 */
		public function __construct() {
			parent::__construct(
				'zerif_clients-widget',
				__( 'Zerif - Clients widget', 'themeisle-companion' ),
				array(
					'customize_selective_refresh' => true,
				)
			);
			add_action( 'admin_enqueue_scripts', array( $this, 'widget_scripts' ) );
		}

		/**
		 * Enqueue Widget Scripts
		 *
		 * @param $hook
		 */
		public function widget_scripts() {
			wp_enqueue_media();
			wp_enqueue_script( 'zerif_widget_media_script', THEMEISLE_COMPANION_URL . 'assets/js/widget-media.js', false, '1.1', true );
		}

		/**
		 * Display Widget
		 *
		 * @param $args
		 * @param $instance
		 */
		public function widget( $args, $instance ) {

			extract( $args );

			echo wp_kses_post( $before_widget );

			?>

			<a href="
			<?php 
			if ( ! empty( $instance['link'] ) ) :
				echo esc_url( apply_filters( 'widget_title', $instance['link'] ) );
endif; 
			?>
			">
				<?php
				if ( ! empty( $instance['image_uri'] ) && ( preg_match( '/(\.jpg|\.png|\.jpeg|\.gif|\.bmp)$/', $instance['image_uri'] ) ) ) {

					echo '<img src="' . esc_url( $instance['image_uri'] ) . '" alt="' . esc_attr__( 'Client', 'themeisle-companion' ) . '">';

				} elseif ( ! empty( $instance['custom_media_id'] ) ) {

					$zerif_clients_custom_media_id = wp_get_attachment_image_src( $instance['custom_media_id'] );
					if ( ! empty( $zerif_clients_custom_media_id ) && ! empty( $zerif_clients_custom_media_id[0] ) ) {

						echo '<img src="' . esc_url( $zerif_clients_custom_media_id[0] ) . '" alt="' . esc_attr__( 'Client', 'themeisle-companion' ) . '">';

					}
				}
				?>
			</a>

			<?php

			echo wp_kses_post( $after_widget );

		}

		/**
		 * Update Widget
		 *
		 * @param $new_instance
		 * @param $old_instance
		 *
		 * @return mixed
		 */
		public function update( $new_instance, $old_instance ) {

			$instance = $old_instance;

			$instance['link'] = strip_tags( $new_instance['link'] );

			$instance['image_uri'] = strip_tags( $new_instance['image_uri'] );

			$instance['image_in_customizer'] = strip_tags( $new_instance['image_in_customizer'] );

			$instance['custom_media_id'] = strip_tags( $new_instance['custom_media_id'] );

			return $instance;

		}

		/**
		 * Widget controls
		 *
		 * @param $instance
		 */
		public function form( $instance ) {
			?>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php _e( 'Link', 'themeisle-companion' ); ?></label><br/>
				<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>"
					   id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"
					   value="
					   <?php 
						if ( ! empty( $instance['link'] ) ) :
							echo esc_url( $instance['link'] );
endif; 
						?>
						"
					   class="widefat">
			</p>
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'image_uri' ) ); ?>"><?php _e( 'Image', 'themeisle-companion' ); ?></label><br/>

				<?php
				$image_in_customizer = '';
				$display             = 'none';
				if ( ! empty( $instance['image_in_customizer'] ) && ! empty( $instance['image_uri'] ) ) {
					$image_in_customizer = esc_url( $instance['image_in_customizer'] );
					$display             = 'inline-block';
				} else {
					if ( ! empty( $instance['image_uri'] ) ) {
						$image_in_customizer = esc_url( $instance['image_uri'] );
						$display             = 'inline-block';
					}
				}
				$zerif_image_in_customizer = $this->get_field_name( 'image_in_customizer' );
				?>
				<input type="hidden" class="custom_media_display_in_customizer"
					   name="
					   <?php 
						if ( ! empty( $zerif_image_in_customizer ) ) {
							echo esc_attr( $zerif_image_in_customizer );
						} 
						?>
						"
					   value="
					   <?php 
						if ( ! empty( $instance['image_in_customizer'] ) ) :
							echo esc_attr( $instance['image_in_customizer'] );
endif; 
						?>
						">
				<img class="custom_media_image" src="<?php echo esc_url( $image_in_customizer ); ?>"
					 style="margin:0;padding:0;max-width:100px;float:left;display:<?php echo esc_attr( $display ); ?>"
					 alt="<?php echo esc_attr__( 'Uploaded image', 'themeisle-companion' ); ?>"/><br/>

				<input type="text" class="widefat custom_media_url"
					   name="<?php echo esc_attr( $this->get_field_name( 'image_uri' ) ); ?>"
					   id="<?php echo esc_attr( $this->get_field_id( 'image_uri' ) ); ?>"
					   value="
					   <?php 
						if ( ! empty( $instance['image_uri'] ) ) :
							echo esc_attr( $instance['image_uri'] );
endif; 
						?>
						"
					   style="margin-top:5px;">

				<input type="button" class="button button-primary custom_media_button" id="custom_media_button"
					   name="<?php echo esc_attr( $this->get_field_name( 'image_uri' ) ); ?>"
					   value="<?php _e( 'Upload Image', 'themeisle-companion' ); ?>" style="margin-top:5px;">
			</p>

			<input class="custom_media_id" id="<?php echo esc_attr( $this->get_field_id( 'custom_media_id' ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( 'custom_media_id' ) ); ?>" type="hidden"
				   value="
				   <?php 
					if ( ! empty( $instance['custom_media_id'] ) ) :
						echo esc_attr( $instance['custom_media_id'] );
endif; 
					?>
					"/>
			<?php

		}

	}
}// End if().

