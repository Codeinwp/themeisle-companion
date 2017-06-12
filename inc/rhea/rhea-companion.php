<?php

$rhea_features_widget = trailingslashit( THEMEISLE_COMPANION_PATH ) . 'inc/rhea/widgets/features.widget.php';
if ( file_exists( $rhea_features_widget ) ) {
	require_once( $rhea_features_widget );
}
$rhea_about_widget = trailingslashit( THEMEISLE_COMPANION_PATH ) . 'inc/rhea/widgets/about.widget.php';
if ( file_exists( $rhea_about_widget ) ) {
	require_once( $rhea_about_widget );
}

$rhea_hours_widget = trailingslashit( THEMEISLE_COMPANION_PATH ) . 'inc/rhea/widgets/hours.widget.php';
if ( file_exists( $rhea_hours_widget ) ) {
	require_once( $rhea_hours_widget );
}

$rhea_contact_widget = trailingslashit( THEMEISLE_COMPANION_PATH ) . 'inc/rhea/widgets/contact.widget.php';
if ( file_exists( $rhea_contact_widget ) ) {
	require_once( $rhea_contact_widget );
}

$rhea_progress_bar_widget = trailingslashit( THEMEISLE_COMPANION_PATH ) . 'inc/rhea/widgets/progress-bar.widget.php';
if ( file_exists( $rhea_progress_bar_widget ) ) {
	require_once( $rhea_progress_bar_widget );
}

$rhea_icon_box_widget = trailingslashit( THEMEISLE_COMPANION_PATH ) . 'inc/rhea/widgets/icon-box.widget.php';
if ( file_exists( $rhea_icon_box_widget ) ) {
	require_once( $rhea_icon_box_widget );
}

add_action('widgets_init', 'rhea_register_widgets');

function rhea_register_widgets() {

	register_widget('rhea_features_block');
	register_widget('Rhea_Progress_Bar');
	register_widget('Rhea_Icon_Box');
	register_widget('Rhea_About_Company');
	register_widget('Rhea_Hours');
	register_widget('Rhea_Contact_Company');

}
function rhea_load_custom_wp_admin_style() {

	wp_enqueue_style( 'fontawesome-style',  trailingslashit( THEMEISLE_COMPANION_URL ) . 'inc/rhea/assets/css/font-awesome.min.css' );
	wp_enqueue_style( 'rhea-admin-style',  trailingslashit( THEMEISLE_COMPANION_URL ) . 'inc/rhea/assets/css/admin-style.css' );
	wp_enqueue_script( 'fontawesome-icons',  trailingslashit( THEMEISLE_COMPANION_URL ) . 'inc/rhea/assets/js/icons.js', false, '1.0.0' );
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_script( 'fontawesome-script',  trailingslashit( THEMEISLE_COMPANION_URL ) . 'inc/rhea/assets/js/fontawesome.jquery.js', false, '1.0.0' );
}

add_action( 'admin_enqueue_scripts', 'rhea_load_custom_wp_admin_style' );

add_action('admin_footer', 'rhea_add_html_to_admin_footer');

add_action('customize_controls_print_footer_scripts', 'rhea_add_html_to_admin_footer');

function rhea_add_html_to_admin_footer() { ?>
	<div id="fontawesome-popup">
		<div class="left-side">
			<label for="fontawesome-live-search"><?php esc_html_e( 'Search icon', 'rhea' ) ?>:</label>
			<ul class="filter-icons">
				<li data-filter="all" class="active"><?php esc_html_e( 'All Icons', 'rhea' ) ?></li>
			</ul>
		</div>
		<div class="right-side">
		</div>
	</div>
<?php }