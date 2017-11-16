/* global importer_endpoint, console */

/**
 * Template Directory Customizer Admin Dashboard Script
 *
 * This handles the template directory.
 *
 * @since	1.0.0
 * @package obfx_modules/template-directory/js
 *
 * @author	ThemeIsle
 */

var obfx_template_directory = function( $ ) {
	'use strict';

	$(
		function () {
				$( '.obfx-template-actions, #customize-header-actions' ).on(
					'click', '.obfx-import-template', function () {
						$( this ).addClass( 'obfx-import-queue' );
						var template_url  = $( this ).data( 'template-file' );
						var template_name = get_the_template_name( this );
						$( this ).hide().after( '<span class="button button-primary obfx-updating updating-message"></span>' );
						$.ajax(
							{
								url: importer_endpoint.url,
								beforeSend: function ( xhr ) {
									xhr.setRequestHeader( 'X-WP-Nonce', importer_endpoint.nonce );
								},
								async: true,
								data: {
									template_url: template_url,
									template_name: template_name
								},
								type: 'POST',
								success: function ( data ) {
									if (data !== 'no-elementor') {
										$( '.obfx-updating' ).replaceWith( '<span class="obfx-done-import" style="float:right"><i class="dashicons-yes dashicons"></i></span>' );
										location.href = data;
									} else {
										$( '.obfx-import-template' ).show();
										$( '.obfx-updating' ).remove();
										$( '.obfx-no-elementor-modal-wrapper' ).fadeIn();
									}
								},
								error: function ( error ) {
									console.error( error );
								}
							}, 'json'
						);
					}
				);
		}
	);
};

obfx_template_directory( jQuery );

function get_the_template_name( button ) {
	if ( jQuery( 'body' ).hasClass( 'tools_page_obfx_template_dir' ) ) {
		return jQuery( button ).parent().prev().prev().text();
	}
	return jQuery( '.obfx-template.active .template-name' ).text();
}
