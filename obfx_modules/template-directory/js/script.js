/* global importer_endpoint, console */

/**
 * Template Directory Customizer Admin Dashboard Script
 *
 * This handles the template directory.
 *
 * @since    1.0.0
 * @package obfx_modules/template-directory/js
 *
 * @author    ThemeIsle
 */

var obfx_template_directory = function ( $ ) {
	'use strict';

	$(
		function () {
			$( '.close-full-overlay' ).on( 'click', function () {
				$( '.obfx-template-preview .obfx-theme-info.active' ).removeClass( 'active' );
				$( '.obfx-template-preview' ).hide();
				$( '.obfx-template-frame' ).attr( 'src', '' );
			} );

			$( '.obfx-preview-template' ).on( 'click', function () {
				var templateSlug = $( this ).data( 'template-slug' );
				var previewUrl = $( this ).data( 'demo-url' );
				$( '.obfx-template-frame' ).attr( 'src', previewUrl );
				$( '.obfx-theme-info.' + templateSlug ).addClass( 'active' );
				setupImportButton();
				$( '.obfx-template-preview' ).fadeIn();
			} );

			$( '.obfx-next-prev .next-theme' ).on( 'click', function () {
				var active = $( '.obfx-theme-info.active' ).removeClass( 'active' );
				if ( active.next() && active.next().length ) {
					active.next().addClass( 'active' );
				} else {
					active.siblings( ':first' ).addClass( 'active' );
				}
				changePreviewSource();
				setupImportButton();
			} );

			$( '.obfx-next-prev .previous-theme' ).on( 'click', function () {
				var active = $( '.obfx-theme-info.active' ).removeClass( 'active' );
				if ( active.prev() && active.prev().length ) {
					active.prev().addClass( 'active' );
				} else {
					active.siblings( ':last' ).addClass( 'active' );
				}
				changePreviewSource();
				setupImportButton();
			} );

			$( '.obfx-template-actions, .wp-full-overlay-header' ).on(
				'click', '.obfx-import-template', function () {
					$( this ).addClass( 'obfx-import-queue' );
					var template_url = $( this ).data( 'template-file' );
					var template_name = $(this).data( 'template-title' );
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
								if ( data !== 'no-elementor' ) {
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

			$('.obfx-template-preview').on('click', '.collapse-sidebar', function() {
                event.preventDefault();
                var overlay = $( '.obfx-template-preview' );
                if ( overlay.hasClass( 'expanded' ) ) {
                    overlay.removeClass( 'expanded' );
                    overlay.addClass( 'collapsed' );
                    return;
                }

                if ( overlay.hasClass( 'collapsed' ) ) {
                    overlay.removeClass( 'collapsed' );
                    overlay.addClass( 'expanded' );
                    return;
                }
            });

			function changePreviewSource() {
				var previewUrl = $( '.obfx-theme-info.active' ).data( 'demo-url' );
				$( '.obfx-template-frame' ).attr( 'src', previewUrl );
			}

			function setupImportButton() {
				$('.wp-full-overlay-header .obfx-import-template').attr('data-template-file', $('.obfx-theme-info.active').data('template-file') );
				$('.wp-full-overlay-header .obfx-import-template').attr('data-template-title', $('.obfx-theme-info.active').data('template-title') );
			}
		}
	);
};

obfx_template_directory( jQuery );