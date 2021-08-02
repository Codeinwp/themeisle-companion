/* global obfxAnalyticsObj, console */

/**
 * Analytics Module Admin Script
 *
 * This handles the analytics interaction.
 *
 * @since    1.0.0
 * @package obfx_modules/google-analytics/js
 *
 * @author    ThemeIsle
 */

var obfx_analytics = function ( $ ) {
	'use strict';

	$(
		function () {
				$( '#unregister-analytics' ).on(
					'click', function ( event ) {
						event.preventDefault();
						$.ajax(
							{
								url: obfxAnalyticsObj.url,
								beforeSend: function ( xhr ) {
									$( '#unregister-analytics' ).addClass( 'loading' );
									xhr.setRequestHeader( 'X-WP-Nonce', obfxAnalyticsObj.nonce );
								},
								data: {
									deactivate: 'unregister'
								},
								type: 'POST',
								error: function ( error ) {
									console.error( error );
								},
								complete: function () {
									$( '#unregister-analytics' ).removeClass( 'loading' );
									location.reload();
								}
							}, 'json'
						);
						return false;
					}
				);
		}
	);
};

obfx_analytics( jQuery );
