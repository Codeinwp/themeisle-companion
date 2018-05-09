/**
 * Social Sharing Module Admin Script
 *
 * @since    1.0.0
 * @package obfx_modules/theme-update-check/js
 *
 * @author    ThemeIsle
 */

var obfx_theme_check = function( $ ) {
	'use strict';

	$(function() {
		var slugToCheck = theme_update_check.slug;

		console.log( slugToCheck );

		var theme_box = $('div.theme.active .update-message');
		//var hestia_box = $(" .update-message");

		console.log( theme_update_check );

		if ( theme_box ) {
			theme_box.html(  theme_box.html() + '<div>' + theme_update_check.check_msg + '</div>' );
			console.log( theme_box.html() );
		}
	} );

};

obfx_theme_check( jQuery );
