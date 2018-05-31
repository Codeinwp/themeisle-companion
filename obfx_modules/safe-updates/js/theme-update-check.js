/**
 * Social Sharing Module Admin Script
 *
 * @since    1.0.0
 * @package obfx_modules/theme-update-check/js
 *
 * @author    ThemeIsle
 */

var obfx_theme_check = function ($) {
	'use strict';

	$(
		function () {
			if (typeof  safe_updated === 'undefined') {
				return;
			}
			var slugToCheck = safe_updated.slug;

			if (safe_updated.check_msg !== undefined) {
				var theme_box = $('div.theme.active .update-message');
				var oldHTML = theme_box.html();
				var newHTML = oldHTML;
				checkUpdateThemeUpdateCore();
				setInterval(checkUpdateTheme, 500);
			}

			function checkUpdateTheme() {
				theme_box = $('div.theme.active .update-message');

				if (theme_box) {
					newHTML = oldHTML + '<div>' + safe_updated.check_msg + '</div>';
				}
				theme_box.html(newHTML);
			}

			function checkUpdateThemeUpdateCore() {

				$('#update-themes-table input[value=' + slugToCheck + ']').parent().next().find('p').append('<p>'+ safe_updated.check_msg + '</p>');

			}

		}
	);

};

obfx_theme_check(jQuery);
