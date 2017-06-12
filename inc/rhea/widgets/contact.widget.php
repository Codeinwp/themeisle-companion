<?php
class Rhea_Contact_Company extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
			'rhea-contact-company',
			__( '[Rhea] Contact', 'rhea' )
		);
	}

    function widget($args, $instance) {

        extract($args);

        echo $before_widget;

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        echo '<div class="rhea_company_contact">';
        if ( isset($instance['adress']) && $instance['adress'] != '' ) {
            
            if ( isset($instance['gmaps_url']) && $instance['gmaps_url'] != '' ) {
                echo '<p><a href="'.$instance['gmaps_url'].'" target="_blank">'.$instance['adress'].'</a></p>';
            }else{
                echo '<p>'.$instance['adress'].'</p>';
            }
        }
        if ( isset($instance['email']) && $instance['email'] != '' ) {
            echo '<p>Email: <a href="mailto:'.$instance['email'].'">'.$instance['email'].'</a></p>';
        }
        if ( isset($instance['phone']) && $instance['phone'] != '' ) {
            echo '<p>Phone: '.$instance['phone'].'</p>';
        }
        echo '</div>';

        echo $after_widget;

    }

    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        
        $instance['title'] = strip_tags( $new_instance['title'] );
		$instance['adress'] = strip_tags( $new_instance['adress'] );
        $instance['gmaps_url'] = esc_url($new_instance['gmaps_url']);
        $instance['email'] = strip_tags($new_instance['email']);
        $instance['phone'] = strip_tags($new_instance['phone']);

        return $instance;

    }

    function form($instance) {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'rhea'); ?></label><br/>
            <input type="text" class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php if( !empty($instance['title']) ): echo $instance['title']; endif; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('adress'); ?>"><?php _e('Company Adress', 'rhea'); ?></label><br/>
            <textarea class="widefat" rows="8" cols="20" name="<?php echo $this->get_field_name('adress'); ?>" id="<?php echo $this->get_field_id('adress'); ?>"><?php if( !empty($instance['adress']) ): echo htmlspecialchars_decode($instance['adress']); endif; ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('gmaps_url'); ?>"><?php _e('Google Maps URL', 'rhea'); ?></label><br/>
            <input type="text" class="widefat" name="<?php echo $this->get_field_name('gmaps_url'); ?>" id="<?php echo $this->get_field_id('gmaps_url'); ?>" value="<?php if( !empty($instance['gmaps_url']) ): echo $instance['gmaps_url']; endif; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Email', 'rhea'); ?></label><br/>
            <input type="text" class="widefat" name="<?php echo $this->get_field_name('email'); ?>" id="<?php echo $this->get_field_id('email'); ?>" value="<?php if( !empty($instance['email']) ): echo $instance['email']; endif; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('phone'); ?>"><?php _e('Phone', 'rhea'); ?></label><br/>
            <input type="text" class="widefat" name="<?php echo $this->get_field_name('phone'); ?>" id="<?php echo $this->get_field_id('phone'); ?>" value="<?php if( !empty($instance['phone']) ): echo $instance['phone']; endif; ?>">
        </p>
		
    <?php

    }

}