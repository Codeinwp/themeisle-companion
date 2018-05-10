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

	$(
		function() {

			var slugToCheck = theme_update_check.slug;
			var theme_box = $( 'div.theme.active .update-message' );
			var oldHTML = theme_box.html();
			var newHTML = oldHTML;

			setInterval( checkUpdateTheme, 500 );

			function checkUpdateTheme() {
				theme_box = $( 'div.theme.active .update-message' );

				if ( theme_box ) {
					newHTML = oldHTML + '<div>' + theme_update_check.check_msg + '</div>';
				}
				theme_box.html( newHTML );
			}

		}
	);

};

obfx_theme_check( jQuery );
