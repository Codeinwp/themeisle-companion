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

    $(function () {
        $.templateDir = {
            'init': function () {
                this.setupCustomizer();
                this.setupPreview();
                changePreviewSource();
            },

            'setupCustomizer': function () {
                $('head title').html('Orbit Fox Template Preview');
                $('.wp-full-overlay-sidebar').addClass('obfx-custom-customizer');
                var importBtn = '<a class="obfx-template-import button button-primary" href="#">Import</a>';
                //Remove Save Button
                $('input.save, .customize-info, #accordion-panel-widgets').remove();
                $('#customize-header-actions').prepend(importBtn).append('<div class="obfx-next-prev"><span onclick="obfxHandleChange(\'prev\');" class="previous-template"></span><span onclick="obfxHandleChange(\'next\');" class="next-template"></span></div>');
                $('#customize-preview').remove();
            },
            'setupPreview': function () {
                var newFrame = '<div id="customize-preview" class="wp-full-overlay-main"><iframe src="" title="OBFX Template Preview" name="customize-preview-obfx-template"></iframe></div>';
                $('.obfx-custom-customizer').after(newFrame);
            }
        };
        $.templateDir.init();
    });
};

obfx_template_directory_previewer( jQuery );

function obfxHandleChange(direction) {
    var active = jQuery('.obfx-template.active').removeClass('active');
    direction = direction || 'next';
    if (direction === 'next') {
        if (active.next() && active.next().length) {
            active.next().addClass('active');
        } else {
            active.siblings(":first").addClass('active');
        }
    }
    if (direction === 'prev') {
        if (active.prev() && active.prev().length) {
            active.prev().addClass('active');
        } else {
            active.siblings(":last").addClass('active');
        }
    }
    changePreviewSource();
}

function changePreviewSource() {
    var activeTemplate = jQuery('.obfx-template.active').attr('data-demo-url');
    jQuery('#customize-preview iframe').attr('src', activeTemplate);
}