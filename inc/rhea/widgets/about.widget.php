<?php
class Rhea_About_Company extends WP_Widget {

    public function __construct() {

        $widget_args = array(
            'description' => esc_html__( 'This widget is designed for footer area', 'rhea' )
        );

        parent::__construct( 'rhea-about-company', __( '[Rhea] About Company', 'rhea' ), $widget_args );
        add_action( 'admin_enqueue_scripts', array( $this, 'widget_scripts' ) );
    }

    function widget_scripts( $hook ) {
        if ( $hook != 'widgets.php' ) {
            return;
        }
        wp_enqueue_media();
        wp_enqueue_script( 'rhea_widget_media_script', THEMEISLE_COMPANION_URL . 'assets/js/widget-media.js', false, '1.1', true );
    }

    function widget($args, $instance) {

        extract($args);

        echo $before_widget;

        $logo_url = '';
        if ( isset( $instance['use_logo'] ) && $instance['use_logo'] != '' ) {
            $custom_logo_id = get_theme_mod( 'custom_logo' );
            if ( $custom_logo_id ) {
                $image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
                $logo_url = $image[0];
            }
        }elseif ( ! empty( $instance['image_uri'] ) ) {
            $logo_url = $instance['image_uri'];
        }

        ?>

        <div class="rhea-about-company">
            <?php if ( $logo_url != '' ) { ?>
                <div class="rhea-company-logo">
                    <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo('title') ) ?>">
                </div>
            <?php } ?>
            <div class="rhea-company-description">
                <?php
                if ( ! empty( $instance['text'] ) ) {
                    echo $instance['text'];
                }
                ?>
            </div>
        </div>

        <?php

        echo $after_widget;

    }

    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['image_uri'] = esc_url($new_instance['image_uri']);
        $instance['use_logo'] = strip_tags( $new_instance['use_logo'] );
        $instance['text'] = strip_tags($new_instance['text']);

        return $instance;

    }

    function form($instance) {

        $image_in_customizer = '';
        $display             = 'none';
        if ( ! empty( $instance['image_uri'] ) ) {
            $image_in_customizer = esc_url( $instance['image_uri'] );
            $display             = 'inline-block';
        }

        ?>
        <p>
            <input type="checkbox" name="<?php echo $this->get_field_name('use_logo'); ?>" id="<?php echo $this->get_field_id('use_logo'); ?>" value="use_logo" <?php if( isset( $instance['use_logo'] ) ) { checked( $instance['use_logo'], 'use_logo' ); } ?>>
            <label for="<?php echo $this->get_field_id('use_logo'); ?>"><?php _e('Use website logo','rhea'); ?></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('image_uri'); ?>"><?php _e('Logo', 'rhea'); ?></label><br/>
            <img class="custom_media_image" src="<?php echo $image_in_customizer; ?>" style="margin:0;padding:0;max-width:100px;float:left;display:<?php echo $display; ?>" alt="<?php echo __( 'Uploaded image', 'zerif-lite' ); ?>"/><br/>
            <input type="text" class="widefat custom_media_url" name="<?php echo $this->get_field_name('image_uri'); ?>" id="<?php echo $this->get_field_id('image_uri'); ?>" value="<?php if( !empty($instance['image_uri']) ): echo $instance['image_uri']; endif; ?>" style="margin-top:5px;">
            <input type="button" class="button button-primary custom_media_button" id="custom_media_button" name="<?php echo $this->get_field_name('image_uri'); ?>" value="<?php _e('Upload Image','rhea'); ?>" style="margin-top:5px;">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Company Description', 'rhea'); ?></label><br/>
            <textarea class="widefat" rows="8" cols="20" name="<?php echo $this->get_field_name('text'); ?>" id="<?php echo $this->get_field_id('text'); ?>"><?php if( !empty($instance['text']) ): echo htmlspecialchars_decode($instance['text']); endif; ?></textarea>
        </p>

        <?php

    }

}