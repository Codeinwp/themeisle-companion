<?php
/**
 * Testimonial Widget
 *
 * @since 1.0.0
 *
 * @package themeisle-companion
 */


/**
 * Class Zerif_Testimonial_Widget
 */
if ( ! class_exists( 'Zerif_Testimonial_Widget' ) ) {

	class Zerif_Testimonial_Widget extends WP_Widget {

		/**
		 * Zerif_Testimonial_Widget constructor.
		 */
		public function __construct() {
			parent::__construct(
				'zerif_testim-widget',
				__( 'Zerif - Testimonial widget', 'themeisle-companion' ),
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

			$zerif_accessibility = get_theme_mod( 'zerif_accessibility' );
			// open link in a new tab when checkbox "accessibility" is not ticked
			$attribut_new_tab = ( isset( $zerif_accessibility ) && ( $zerif_accessibility != 1 ) ? ' target="_blank"' : '' );

			echo wp_kses_post( $before_widget );

			?>


			<!-- MESSAGE OF THE CLIENT -->

			<?php if ( ! empty( $instance['text'] ) ) : ?>
				<div class="message">
					<?php echo wp_kses_post( htmlspecialchars_decode( apply_filters( 'widget_title', $instance['text'] ) ) ); ?>
				</div>
			<?php endif; ?>

			<!-- CLIENT INFORMATION -->

			<div class="client">

				<div class="quote red-text">

					<i class="fa fa-quote-left"></i>

				</div>

				<div class="client-info">

					<a <?php echo esc_attr( $attribut_new_tab ); ?>
						class="client-name" 
						<?php 
						if ( ! empty( $instance['link'] ) ) :
							echo 'href="' . esc_url( $instance['link'] ) . '"';
endif; 
						?>
						>
			<?php 
			if ( ! empty( $instance['title'] ) ) :
				echo wp_kses_post( apply_filters( 'widget_title', $instance['title'] ) );
endif; 
			?>
</a>


					<?php if ( ! empty( $instance['details'] ) ) : ?>
						<div class="client-company">

							<?php echo wp_kses_post( apply_filters( 'widget_title', $instance['details'] ) ); ?>

						</div>
					<?php endif; ?>

				</div>

				<?php

				if ( ! empty( $instance['image_uri'] ) && ( preg_match( '/(\.jpg|\.png|\.jpeg|\.gif|\.bmp)$/', $instance['image_uri'] ) ) ) {

					echo '<div class="client-image hidden-xs">';

					echo '<img src="' . esc_url( $instance['image_uri'] ) . '" alt="" />';

					echo '</div>';

				} elseif ( ! empty( $instance['custom_media_id'] ) ) {

					$zerif_testimonials_custom_media_id = wp_get_attachment_image_src( $instance['custom_media_id'] );
					$alt                                = get_post_meta( $instance['custom_media_id'], '_wp_attachment_image_alt', true );

					if ( ! empty( $zerif_testimonials_custom_media_id ) && ! empty( $zerif_testimonials_custom_media_id[0] ) ) {

						echo '<div class="client-image hidden-xs">';

						echo '<img src="' . esc_url( $zerif_testimonials_custom_media_id[0] ) . '" alt="' . esc_attr( $alt ) . '" />';

						echo '</div>';

					}
				}

				?>

			</div>
			<!-- / END CLIENT INFORMATION-->


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

			$instance                        = $old_instance;
			$instance['text']                = stripslashes( wp_filter_post_kses( $new_instance['text'] ) );
			$instance['title']               = strip_tags( $new_instance['title'] );
			$instance['details']             = strip_tags( $new_instance['details'] );
			$instance['image_uri']           = strip_tags( $new_instance['image_uri'] );
			$instance['link']                = strip_tags( $new_instance['link'] );
			$instance['custom_media_id']     = strip_tags( $new_instance['custom_media_id'] );
			$instance['image_in_customizer'] = strip_tags( $new_instance['image_in_customizer'] );

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
					for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Author', 'themeisle-companion' ); ?></label><br/>
				<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
					   id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
					   value="
					   <?php 
						if ( ! empty( $instance['title'] ) ) :
							echo esc_attr( $instance['title'] );
endif; 
						?>
						"
					   class="widefat">
			</p>
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php _e( 'Author link', 'themeisle-companion' ); ?></label><br/>
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
					for="<?php echo esc_attr( $this->get_field_id( 'details' ) ); ?>"><?php _e( 'Author details', 'themeisle-companion' ); ?></label><br/>
				<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'details' ) ); ?>"
					   id="<?php echo esc_attr( $this->get_field_id( 'details' ) ); ?>"
					   value="
					   <?php 
						if ( ! empty( $instance['details'] ) ) :
							echo esc_attr( $instance['details'] );
endif; 
						?>
						"
					   class="widefat">
			</p>
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php _e( 'Text', 'themeisle-companion' ); ?></label><br/>
				<textarea class="widefat" rows="8" cols="20" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"
						  id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>">
										 <?php 
											if ( ! empty( $instance['text'] ) ) :
												echo esc_html( htmlspecialchars_decode( $instance['text'] ) );
endif; 
											?>
							</textarea>
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
