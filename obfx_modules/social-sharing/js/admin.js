/**
 * Social Sharing Module Admin Script
 *
 * @since    1.0.0
 * @package obfx_modules/social-sharing/js
 *
 * @author    ThemeIsle
 */

jQuery(document).ready(function () {
    jQuery('.network-toggle input:checkbox:not(:checked)').each(function(){
        jQuery(this).parents('.obfx-row').find('.show input').attr("disabled", true).parent().addClass('obfxHiddenOption');
    });

    jQuery('.network-toggle input').on('change', function(){
        if(jQuery(this).is(':checked')) {
            jQuery(this).parents('.obfx-row').find('.show input').attr("disabled", false).parent().removeClass('obfxHiddenOption');
        } else {
            jQuery(this).parents('.obfx-row').find('.show input').attr("disabled", true).parent().addClass('obfxHiddenOption');
        }
    });
});
