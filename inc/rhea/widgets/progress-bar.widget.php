<?php
class Rhea_Progress_Bar extends WP_Widget {

	public function __construct() {
		$widget_args = array(
			'description' => esc_html__( 'This widget is designed for Progress Bar Section', 'rhea' )
		);
		parent::__construct( 'rhea-progress-bar', __( '[Rhea] - Progress Bar', 'rhea' ), $widget_args );
	}

	function widget($args, $instance) {

		extract($args);

		echo $before_widget;

		$percentage = !empty($instance['percentage']) ? $instance['percentage'] : '0';

		?>

		<div class="progress-holder">
			<?php if ( !empty($instance['title']) ) { ?>
				<h3><?php echo $instance['title'] ?></h3>
			<?php } ?>

			<?php if ( !empty($instance['info']) ) { ?>
				<span class="completion-rate" style="width: <?php echo $percentage ?>%"><?php echo $instance['info'] ?></span>
			<?php } ?>
			<div class="progress">
				<div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentage ?>%"></div>
			</div>
		</div>

		<?php

		echo $after_widget;

	}

	function update($new_instance, $old_instance) {

		$instance = $old_instance;
		$instance['title'] = stripslashes(wp_filter_post_kses($new_instance['title']));
		$instance['info'] = strip_tags($new_instance['info']);
		$instance['percentage'] = strip_tags( $new_instance['percentage'] );

		return $instance;

	}

	function form($instance) {
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'rhea'); ?></label><br/>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php if( !empty($instance['title']) ): echo $instance['title']; endif; ?>" placeholder="Wordpress" class="widefat">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('info'); ?>"><?php _e('Info', 'rhea'); ?></label><br/>
			<input type="text" name="<?php echo $this->get_field_name('info'); ?>" id="<?php echo $this->get_field_id('info'); ?>" value="<?php if( !empty($instance['info']) ): echo $instance['info']; endif; ?>" placeholder="70%" class="widefat">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('percentage'); ?>"><?php _e('Percentage','rhea'); ?></label><br />
			<input type="text" name="<?php echo $this->get_field_name('percentage'); ?>" id="<?php echo $this->get_field_id('percentage'); ?>" value="<?php if( !empty($instance['percentage']) ): echo $instance['percentage']; endif; ?>"  placeholder="70" class="widefat">
		</p>

		<?php

	}

}