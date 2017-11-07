var obfx_plugin_install_helper = function( $ ) {
	'use strict';
	$(
		function () {

				$( 'body' ).on(
					'click', ' .obfx-install-plugin ', function () {
						var slug = $( this ).attr( 'data-slug' );
						wp.updates.installPlugin(
							{
								slug: slug
							}
						);
						return false;
					}
				);

				$( '.obfx-close-modal' ).on(
					'click', function () {
						$( '.obfx-no-elementor-modal-wrapper' ).fadeOut();
					}
				);
		}
	);

	// Remove activate button and replace with activation in progress button.
	$( document ).on(
		'DOMNodeInserted','.activate-now', function () {
			var activateButton = $( '.obfx-no-elementor-modal-wrapper .activate-now' );
			if (activateButton.length) {
				var url = $( activateButton ).attr( 'href' );
				if (typeof url !== 'undefined') {
					// Request plugin activation.
					$.ajax(
						{
							beforeSend: function () {
								$( activateButton ).replaceWith( '<a class="button updating-message">Activating...</a>' );
							},
							async: true,
							type: 'GET',
							url: url,
							success: function () {
								$( '.obfx-no-elementor-modal-wrapper' ).fadeOut();
								$( '.obfx-import-queue' ).trigger( 'click' );

							}
						}
					);
				}
			}
		}
	);
};

obfx_plugin_install_helper( jQuery );
