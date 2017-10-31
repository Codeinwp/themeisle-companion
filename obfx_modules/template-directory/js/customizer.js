/* global wp */

/**
 * Template Directory Customizer Public Script
 *
 * This handles the previewer - left side of the customizer.
 *
 * @since	1.0.0
 * @package obfx_modules/template-directory/js
 *
 * @author	ThemeIsle
 */

var obfx_template_directory_previewer = function( $ ) {
    'use strict';

    $( function () {
        $('.wp-full-overlay-sidebar').addClass('obfx-custom-customizer');
        var importBtn = '<a class="obfx-template-import button button-primary" href="#">Import</a>';
        //Remove Save Button
        $( 'input.save, .customize-info' ).remove();
        $( '#customize-header-actions' ).prepend( importBtn );
        $( '#customize-header-actions' ).append( '<div class="obfx-next-prev"><span class="previous-template"></span><span class="next-template"></span></div>');

        var newFrame = '<iframe src="" title="OBFX Template Preview" name="customize-preview-obfx-template"></iframe>';
        $( '#customize-preview iframe' ).remove();
        $( '#customize-preview' ).prepend( newFrame );

    } );
};

function obfxPreviewTrigger( element ) {
    var previewUrl = jQuery( element ).attr('href');
    jQuery( '#customize-preview iframe' ).attr('src', previewUrl );
    event.preventDefault();
}

obfx_template_directory_previewer( jQuery );
