/**
 * Social Sharing Module Public Script
 *
 * @since	1.0.0
 * @package obfx_modules/social-sharing/js
 *
 * @author	ThemeIsle
 */

jQuery(document).ready(function() {

    jQuery('.obfx-core-social-sharing-icons a').click(function(e) {
        e.preventDefault();
        var link = jQuery(this).attr('href');

        window.open(link, 'obfxShareWindow', 'height=450, width=550, top=' + (jQuery(window).height() / 2 - 275) + ', left=' + (jQuery(window).width() / 2 - 225) + ', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
        return false;
    });

});