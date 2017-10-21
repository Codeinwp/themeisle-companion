/**
 * Menu Icons Module Admin Script
 *
 * @since    1.0.0
 * @package obfx_modules/menu-icons/js
 *
 * @author    ThemeIsle
 */

var obfx_menuicons_module_admin = function( $ ) {
	'use strict';

	$( function() {
        $('.item-title .menu-item-title').prepend($('<a href="#" class="dashicons dashicons-share obfx-menu-icon"></a>'));
        $('.obfx-menu-icon').on("click", function(e){
            e.preventDefault();
        });
	} );

};

obfx_menuicons_module_admin( jQuery );
