/*exported obfxHandleChange */

/**
 * Template Directory Customizer Public Script
 *
 * This handles the customizer.
 *
 * @since	1.0.0
 * @package obfx_modules/template-directory/js
 *
 * @author	ThemeIsle
 */

var obfx_template_directory_previewer = function( $ ) {
	'use strict';

	$(
		function () {
				$( 'head title' ).html( 'Orbit Fox Template Preview' );
				$( '.wp-full-overlay-sidebar' ).addClass( 'obfx-custom-customizer' );
				var importBtn = '<span class="obfx-import-template button button-primary" href="#">Import</span>';
				$( '.customize-controls-preview-toggle .controls' ).html( 'Templates' );
				// Remove Save Button
				$( 'input.save, .customize-info, #accordion-panel-widgets, .customize-save-button-wrapper, .customize-control-notifications-container' ).remove();
				$( '.wp-full-overlay-sidebar-content' ).removeAttr( 'style' );
				$( '#customize-header-actions' ).prepend( importBtn ).append( '<div class="obfx-next-prev"><span onclick="obfxHandleChange(\'prev\');" class="previous-template"></span><span onclick="obfxHandleChange(\'next\');" class="next-template"></span></div>' );
				$( '#customize-preview' ).remove();
				var previewUrl = $( '.obfx-template.active' ).data( 'demo-url' );
				var newFrame = '<div id="customize-preview" class="wp-full-overlay-main"><iframe src="' + previewUrl + '" title="OBFX Template Preview" name="customize-preview-obfx-template"></iframe></div>';
				$( '.obfx-custom-customizer' ).after( newFrame );
				changeButtonProps();
		}
	);
};

obfx_template_directory_previewer( jQuery );

function obfxHandleChange(direction) {
	'use strict';
	var active = jQuery( '.obfx-template.active' ).removeClass( 'active' );
	direction  = direction || 'next';
	if (direction === 'next') {
		if (active.next() && active.next().length) {
			active.next().addClass( 'active' );
		} else {
			active.siblings( ':first' ).addClass( 'active' );
		}
	}
	if (direction === 'prev') {
		if (active.prev() && active.prev().length) {
			active.prev().addClass( 'active' );
		} else {
			active.siblings( ':last' ).addClass( 'active' );
		}
	}
	changePreviewSource();
}

function changePreviewSource() {
	var previewUrl = jQuery( '.obfx-template.active' ).data( 'demo-url' );
	jQuery( '#customize-preview iframe' ).attr( 'src', previewUrl );
	changeButtonProps();
}

function changeButtonProps() {
	var templateFileUrl = jQuery( '.obfx-template.active' ).data( 'template-file' );
	var importBtn       = '<span class="obfx-import-template button button-primary" href="#">Import</span>';

	jQuery( '.obfx-done-import' ).replaceWith( importBtn );
	jQuery( '.obfx-import-template.button' ).attr( 'data-template-file', templateFileUrl );
}
