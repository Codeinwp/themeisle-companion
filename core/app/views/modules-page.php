<?php
/**
 * The View Page for Orbit Fox Modules.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Orbit_Fox
 * @subpackage Orbit_Fox/app/views
 * @codeCoverageIgnore
 */

if ( ! isset( $no_modules ) ) {
	$no_modules = true;
}

if ( ! isset( $empty_tpl ) ) {
	$empty_tpl = '';
}

if ( ! isset( $count_modules ) ) {
	$count_modules = 0;
}

if ( ! isset( $tiles ) ) {
	$tiles = '';
}

if ( ! isset( $toasts ) ) {
	$toasts = '';
}

if ( ! isset( $panels ) ) {
	$panels = '';
}
// let's check if this user needs to connect with orbitfox service
$obfx_user_data = get_option( 'obfx_connect_data' );
$obfx_connect_url = apply_filters( 'obfx_connector_url', '' );
?>
<div class="obfx-wrapper obfx-header">
	<div class="obfx-header-content">
		<img src="<?php echo OBFX_URL; ?>/images/orbit-fox.png" title="Orbit Fox" class="obfx-logo"/>
		<h1><?php echo __( 'Orbit Fox Companion', 'themeisle-companion' ); ?></h1><span class="powered"> by <a
					href="https://themeisle.com" target="_blank"><b>ThemeIsle</b></a></span>
		<?php

		if ( empty( $obfx_user_data ) && ! empty( $obfx_connect_url ) ) { ?>
			<a id="connect" class="btn btn-success" href="<?php echo $obfx_connect_url; ?>">
				<span class="dashicons dashicons-share"></span>
				<?php esc_html_e( 'Connect', 'themeisle-companion' ); ?>
			</a>
			<?php
		} elseif ( ! empty( $obfx_user_data ) ) { ?>
			<a id="connect" class="btn btn-success" href="<?php echo admin_url('admin.php?page=obfx_companion&action=disconnect_obfx&nonce=' . wp_create_nonce( 'disconnect_obfx' )); ?>">
				<?php esc_html_e( 'Disconnect', 'themeisle-companion' ); ?>
			</a>
			<span><?php
				esc_html_e( 'You are connected as: ' );

				//$data['imgcdn'] = $connector->server->requestCredentialsForService( 'https://i.orbitfox.com/' );
//				$data = get_option( 'obfx_connect_data' );
//				$data['imgcdn'] = $testt;
//				update_option( 'obfx_connect_data', $data );
				var_dump($obfx_user_data);

				?></span>
		<?php } ?>
	</div>
</div>
<div id="obfx-wrapper" style="padding: 0; margin-top: 10px; margin-bottom: 5px;">
	<?php
	echo $toasts;
	?>
</div>
<div class="obfx-wrapper" id="obfx-modules-wrapper">
	<?php
	if ( $no_modules ) {
		echo $empty_tpl;
	} else {
		?>
		<div class="panel">
			<div class="panel-header text-center">
				<div class="panel-title mt-10"><?php echo __( 'Available Modules', 'themeisle-companion' ); ?></div>
			</div>
			<div class="panel-body">
				<?php echo $tiles; ?>
			</div>
			<div class="panel-footer">
				<!-- buttons or inputs -->
			</div>
		</div>
		<div class="panel">
			<div class="panel-header text-center">
				<div class="panel-title mt-10"><?php echo __( 'Activated Modules Options', 'themeisle-companion' ); ?></div>
			</div>
			<?php echo ( $panels == '' ) ? '<p class="text-center">' . __( 'No modules activated.', 'themeisle-companion' ) . '</p>' : $panels; ?>
		</div>
		<?php
	}
	?>
</div>
