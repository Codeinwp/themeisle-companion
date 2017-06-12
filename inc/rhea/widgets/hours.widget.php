<?php
class Rhea_Hours extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
			'rhea-company-hours',
			__( '[Rhea] Company Program', 'rhea' )
		);
	}

    function widget($args, $instance) {

        extract($args);

        echo $before_widget;

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        ?>

        <div class="rhea_program">

            <?php if ( ( isset( $instance['monday_from'] ) && $instance['monday_from'] != '' ) || ( isset( $instance['monday_to'] ) && $instance['monday_to'] != '' ) ) { ?>
                <div class="rhea_program_item">
                    <p><?php _e('Monday', 'rhea'); ?></p>
                    <div class="rhea_program_hours">
                        <?php if ( isset( $instance['monday_from'] ) && $instance['monday_from'] != '' ) { ?>
                            <div class="rhea_program_item_from">
                                <?php echo $instance['monday_from'] ?>
                            </div>
                        <?php } ?>
                        <?php if ( isset( $instance['monday_to'] ) && $instance['monday_to'] != '' ) { ?>
                            <div class="rhea_program_item_to">
                                <?php echo $instance['monday_to'] ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <?php if ( ( isset( $instance['tuesday_from'] ) && $instance['tuesday_from'] != '' ) || ( isset( $instance['tuesday_to'] ) && $instance['tuesday_to'] != '' ) ) { ?>
                <div class="rhea_program_item">
                    <p><?php _e('Tuesday', 'rhea'); ?></p>
                    <div class="rhea_program_hours">
                        <?php if ( isset( $instance['tuesday_from'] ) && $instance['tuesday_from'] != '' ) { ?>
                            <div class="rhea_program_item_from">
                                <?php echo $instance['tuesday_from'] ?>
                            </div>
                        <?php } ?>
                        <?php if ( isset( $instance['tuesday_to'] ) && $instance['tuesday_to'] != '' ) { ?>
                            <div class="rhea_program_item_to">
                                <?php echo $instance['tuesday_to'] ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <?php if ( ( isset( $instance['wednesday_from'] ) && $instance['wednesday_from'] != '' ) || ( isset( $instance['wednesday_to'] ) && $instance['wednesday_to'] != '' ) ) { ?>
                <div class="rhea_program_item">
                    <p><?php _e('Wednesday', 'rhea'); ?></p>
                    <div class="rhea_program_hours">
                        <?php if ( isset( $instance['wednesday_from'] ) && $instance['wednesday_from'] != '' ) { ?>
                            <div class="rhea_program_item_from">
                                <?php echo $instance['wednesday_from'] ?>
                            </div>
                        <?php } ?>
                        <?php if ( isset( $instance['wednesday_to'] ) && $instance['wednesday_to'] != '' ) { ?>
                            <div class="rhea_program_item_to">
                                <?php echo $instance['wednesday_to'] ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <?php if ( ( isset( $instance['thursday_from'] ) && $instance['thursday_from'] != '' ) || ( isset( $instance['thursday_to'] ) && $instance['thursday_to'] != '' ) ) { ?>
                <div class="rhea_program_item">
                    <p><?php _e('Thursday', 'rhea'); ?></p>
                    <div class="rhea_program_hours">
                        <?php if ( isset( $instance['thursday_from'] ) && $instance['thursday_from'] != '' ) { ?>
                            <div class="rhea_program_item_from">
                                <?php echo $instance['thursday_from'] ?>
                            </div>
                        <?php } ?>
                        <?php if ( isset( $instance['thursday_to'] ) && $instance['thursday_to'] != '' ) { ?>
                            <div class="rhea_program_item_to">
                                <?php echo $instance['thursday_to'] ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <?php if ( ( isset( $instance['friday_from'] ) && $instance['friday_from'] != '' ) || ( isset( $instance['friday_to'] ) && $instance['friday_to'] != '' ) ) { ?>
                <div class="rhea_program_item">
                    <p><?php _e('Friday', 'rhea'); ?></p>
                    <div class="rhea_program_hours">
                        <?php if ( isset( $instance['friday_from'] ) && $instance['friday_from'] != '' ) { ?>
                            <div class="rhea_program_item_from">
                                <?php echo $instance['friday_from'] ?>
                            </div>
                        <?php } ?>
                        <?php if ( isset( $instance['friday_to'] ) && $instance['friday_to'] != '' ) { ?>
                            <div class="rhea_program_item_to">
                                <?php echo $instance['friday_to'] ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <?php if ( ( isset( $instance['saturday_from'] ) && $instance['saturday_from'] != '' ) || ( isset( $instance['saturday_to'] ) && $instance['saturday_to'] != '' ) ) { ?>
                <div class="rhea_program_item">
                    <p><?php _e('Saturday', 'rhea'); ?></p>
                    <div class="rhea_program_hours">
                        <?php if ( isset( $instance['saturday_from'] ) && $instance['saturday_from'] != '' ) { ?>
                            <div class="rhea_program_item_from">
                                <?php echo $instance['saturday_from'] ?>
                            </div>
                        <?php } ?>
                        <?php if ( isset( $instance['saturday_to'] ) && $instance['saturday_to'] != '' ) { ?>
                            <div class="rhea_program_item_to">
                                <?php echo $instance['saturday_to'] ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <?php if ( ( isset( $instance['sunday_from'] ) && $instance['sunday_from'] != '' ) || ( isset( $instance['sunday_to'] ) && $instance['sunday_to'] != '' ) ) { ?>
                <div class="rhea_program_item">
                    <p><?php _e('Sunday', 'rhea'); ?></p>
                    <div class="rhea_program_hours">
                        <?php if ( isset( $instance['sunday_from'] ) && $instance['sunday_from'] != '' ) { ?>
                            <div class="rhea_program_item_from">
                                <?php echo $instance['sunday_from'] ?>
                            </div>
                        <?php } ?>
                        <?php if ( isset( $instance['sunday_to'] ) && $instance['sunday_to'] != '' ) { ?>
                            <div class="rhea_program_item_to">
                                <?php echo $instance['sunday_to'] ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            
        </div>

        <?php
        echo $after_widget;

    }

    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        // Monday
        $instance['monday_from'] = strip_tags($new_instance['monday_from']);
        $instance['monday_to'] = strip_tags($new_instance['monday_to']);

        // Tuesday
        $instance['tuesday_from'] = strip_tags($new_instance['tuesday_from']);
        $instance['tuesday_to'] = strip_tags($new_instance['tuesday_to']);

        // Wednesday
        $instance['wednesday_from'] = strip_tags($new_instance['wednesday_from']);
        $instance['wednesday_to'] = strip_tags($new_instance['wednesday_to']);

        // Thursday
        $instance['thursday_from'] = strip_tags($new_instance['thursday_from']);
        $instance['thursday_to'] = strip_tags($new_instance['thursday_to']);

        // Friday
        $instance['friday_from'] = strip_tags($new_instance['friday_from']);
        $instance['friday_to'] = strip_tags($new_instance['friday_to']);

        // Saturday
        $instance['saturday_from'] = strip_tags($new_instance['saturday_from']);
        $instance['saturday_to'] = strip_tags($new_instance['saturday_to']);

        // Sunday
        $instance['sunday_from'] = strip_tags($new_instance['sunday_from']);
        $instance['sunday_to'] = strip_tags($new_instance['sunday_to']);

        return $instance;

    }

    function form($instance) {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'rhea'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php if( !empty($instance['title']) ): echo $instance['title']; endif; ?>" class="widefat">
        </p>
        <p>
            <label><?php _e('Monday', 'rhea'); ?></label><br/>

            <input type="text" name="<?php echo $this->get_field_name('monday_from'); ?>" id="<?php echo $this->get_field_id('monday_from'); ?>" value="<?php if( !empty($instance['monday_from']) ): echo $instance['monday_from']; endif; ?>" placeholder="<?php _e('From', 'rhea'); ?>" style="width:45%;">
            <input type="text" name="<?php echo $this->get_field_name('monday_to'); ?>" id="<?php echo $this->get_field_id('monday_to'); ?>" value="<?php if( !empty($instance['monday_to']) ): echo $instance['monday_to']; endif; ?>" placeholder="<?php _e('To', 'rhea'); ?>" style="width:45%;">
        </p>
        <p>
            <label><?php _e('Tuesday', 'rhea'); ?></label><br/>

            <input type="text" name="<?php echo $this->get_field_name('tuesday_from'); ?>" id="<?php echo $this->get_field_id('tuesday_from'); ?>" value="<?php if( !empty($instance['tuesday_from']) ): echo $instance['tuesday_from']; endif; ?>" placeholder="<?php _e('From', 'rhea'); ?>" style="width:45%;">
            <input type="text" name="<?php echo $this->get_field_name('tuesday_to'); ?>" id="<?php echo $this->get_field_id('tuesday_to'); ?>" value="<?php if( !empty($instance['tuesday_to']) ): echo $instance['tuesday_to']; endif; ?>" placeholder="<?php _e('To', 'rhea'); ?>" style="width:45%;">
        </p>
        <p>
            <label><?php _e('Wednesday', 'rhea'); ?></label><br/>

            <input type="text" name="<?php echo $this->get_field_name('wednesday_from'); ?>" id="<?php echo $this->get_field_id('wednesday_from'); ?>" value="<?php if( !empty($instance['wednesday_from']) ): echo $instance['wednesday_from']; endif; ?>" placeholder="<?php _e('From', 'rhea'); ?>" style="width:45%;">
            <input type="text" name="<?php echo $this->get_field_name('wednesday_to'); ?>" id="<?php echo $this->get_field_id('wednesday_to'); ?>" value="<?php if( !empty($instance['wednesday_to']) ): echo $instance['wednesday_to']; endif; ?>" placeholder="<?php _e('To', 'rhea'); ?>" style="width:45%;">
        </p>
        <p>
            <label><?php _e('Thursday', 'rhea'); ?></label><br/>

            <input type="text" name="<?php echo $this->get_field_name('thursday_from'); ?>" id="<?php echo $this->get_field_id('thursday_from'); ?>" value="<?php if( !empty($instance['thursday_from']) ): echo $instance['thursday_from']; endif; ?>" placeholder="<?php _e('From', 'rhea'); ?>" style="width:45%;">
            <input type="text" name="<?php echo $this->get_field_name('thursday_to'); ?>" id="<?php echo $this->get_field_id('thursday_to'); ?>" value="<?php if( !empty($instance['thursday_to']) ): echo $instance['thursday_to']; endif; ?>" placeholder="<?php _e('To', 'rhea'); ?>" style="width:45%;">
        </p>
        <p>
            <label><?php _e('Friday', 'rhea'); ?></label><br/>

            <input type="text" name="<?php echo $this->get_field_name('friday_from'); ?>" id="<?php echo $this->get_field_id('friday_from'); ?>" value="<?php if( !empty($instance['friday_from']) ): echo $instance['friday_from']; endif; ?>" placeholder="<?php _e('From', 'rhea'); ?>" style="width:45%;">
            <input type="text" name="<?php echo $this->get_field_name('friday_to'); ?>" id="<?php echo $this->get_field_id('friday_to'); ?>" value="<?php if( !empty($instance['friday_to']) ): echo $instance['friday_to']; endif; ?>" placeholder="<?php _e('To', 'rhea'); ?>" style="width:45%;">
        </p>
        <p>
            <label><?php _e('Saturday', 'rhea'); ?></label><br/>

            <input type="text" name="<?php echo $this->get_field_name('saturday_from'); ?>" id="<?php echo $this->get_field_id('saturday_from'); ?>" value="<?php if( !empty($instance['saturday_from']) ): echo $instance['saturday_from']; endif; ?>" placeholder="<?php _e('From', 'rhea'); ?>" style="width:45%;">
            <input type="text" name="<?php echo $this->get_field_name('saturday_to'); ?>" id="<?php echo $this->get_field_id('saturday_to'); ?>" value="<?php if( !empty($instance['saturday_to']) ): echo $instance['saturday_to']; endif; ?>" placeholder="<?php _e('To', 'rhea'); ?>" style="width:45%;">
        </p>
        <p>
            <label><?php _e('Sunday', 'rhea'); ?></label><br/>

            <input type="text" name="<?php echo $this->get_field_name('sunday_from'); ?>" id="<?php echo $this->get_field_id('sunday_from'); ?>" value="<?php if( !empty($instance['sunday_from']) ): echo $instance['sunday_from']; endif; ?>" placeholder="<?php _e('From', 'rhea'); ?>" style="width:45%;">
            <input type="text" name="<?php echo $this->get_field_name('sunday_to'); ?>" id="<?php echo $this->get_field_id('sunday_to'); ?>" value="<?php if( !empty($instance['sunday_to']) ): echo $instance['sunday_to']; endif; ?>" placeholder="<?php _e('To', 'rhea'); ?>" style="width:45%;">
        </p>
		
    <?php

    }

}