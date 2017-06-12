<?php
class rhea_features_block extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
			'rhea-feature-block',
			__( '[Rhea] Our features widget', 'rhea' )
		);
	}

    function widget($args, $instance) {

        extract($args);

        echo $before_widget;
        $link = !empty($instance['link']) ? $instance['link'] : '#';
        ?>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 focus-box" data-scrollreveal="enter bottom after 0.15s over 1s">
        	<div class="service-block">
        		<a href="<?php echo $link ?>" class="service-url">

        		<?php if ( !empty($instance['icon']) ): ?>
        			<span class="icon-holder"><i class="<?php echo $instance['icon'] ?>"></i></span>
        		<?php endif ?>
        		<?php if ( !empty($instance['title']) ): ?>
        			<h3 class="service-title"><?php echo $instance['title'] ?></h3>
        		<?php endif ?>
        		<?php if ( !empty($instance['text']) ): ?>
        			<p class="service-title"><?php echo $instance['text'] ?></p>
        		<?php endif ?>

        		</a>
        	</div>
        </div>

        <?php

        echo $after_widget;

    }

    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['text'] = stripslashes(wp_filter_post_kses($new_instance['text']));
        $instance['title'] = strip_tags($new_instance['title']);
		$instance['link'] = strip_tags( $new_instance['link'] );
        $instance['icon'] = strip_tags($new_instance['icon']);

        return $instance;

    }

    function form($instance) {
        $icon_holder_class = empty($instance['icon']) ? ' empty-icon' : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('icon'); ?>"><?php _e('Icon', 'rhea'); ?></label><br/>
            <div class="fontawesome-icon-container<?php echo $icon_holder_class ?>">
                <input type="hidden" class="widefat" name="<?php echo $this->get_field_name('icon'); ?>" id="<?php echo $this->get_field_id('icon'); ?>" value="<?php if( !empty($instance['icon']) ): echo $instance['icon']; endif; ?>">
                <div class="icon-holder">
                    <p>No icon selected :( ... </p>
                    <i class="<?php if( !empty($instance['icon']) ): echo $instance['icon']; endif; ?>"></i>
                </div>
                <div class="actions">
                    <button type="button" class="button add-icon-button">Select Icon</button>
                    <button type="button" class="button change-icon-button">Change Icon</button>
                    <button type="button" class="button remove-icon-button">Remove</button>
                </div>
            </div>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'rhea'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php if( !empty($instance['title']) ): echo $instance['title']; endif; ?>" class="widefat">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text', 'rhea'); ?></label><br/>
            <textarea class="widefat" rows="8" cols="20" name="<?php echo $this->get_field_name('text'); ?>" id="<?php echo $this->get_field_id('text'); ?>"><?php if( !empty($instance['text']) ): echo htmlspecialchars_decode($instance['text']); endif; ?></textarea>
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link','rhea'); ?></label><br />
			<input type="text" name="<?php echo $this->get_field_name('link'); ?>" id="<?php echo $this->get_field_id('link'); ?>" value="<?php if( !empty($instance['link']) ): echo $instance['link']; endif; ?>" class="widefat">
		</p>
        
		
    <?php

    }

}