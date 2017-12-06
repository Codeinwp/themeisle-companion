<?php
var_dump($settings);
$query = FLBuilderLoop::query( $settings );

if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();
		the_title();
	}
}

wp_reset_postdata();