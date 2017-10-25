/**
 * Menu Icons Module Admin Script
 *
 * @since    1.0.0
 * @package obfx_modules/menu-icons/js
 *
 * @author    ThemeIsle
 */

 /* global menu_icons */

var obfx_menuicons_module_admin = function( $, menu_icons ) {
	'use strict';

    var default_icon = menu_icons.icon_default;

    function get_prefix(icon){
        if(icon.match(/^fa-/)){
            return 'fa ';
        }else if(icon.match(/^dashicons-/)){
            return 'dashicons ';
        }else if(icon.match(/glyphicon-/)){
            return 'glyphicon ';
        }
    }

	$( function() {
        // ensure the popover comes over the menu bar.
        $('.menu-item-bar .menu-item-handle').css('overflow', 'initial');
        // add the menu item id to the dropdown as an attribute.
        $('li.menu-item').each(function(i, x){
            var item_id = $(x).find('input.menu-item-data-db-id').val();
            var icon    = $('#menu-item-icon-' + item_id).val();
            if('' === icon){
                icon    = default_icon;
            }
            var prefix  = get_prefix(icon);

            $(x).find('.menu-item-bar .menu-item-handle .item-title').prepend($(
            '<div class="input-group" style="display: inline-block"><input class="form-control obfx-menu-icon" value="' + icon + '" style="display: none" type="text" data-menu-item-id="' + item_id + '"><span class="input-group-addon" style="cursor: pointer"><i class="' + prefix + icon + '"></i></span></div>'
            ));
        });
        $('.obfx-menu-icon').iconpicker({
            // added blank icon for deselection.
            icons: $.merge([default_icon], $.merge(menu_icons.icons, $.iconpicker.defaultOptions.icons)),
            fullClassFormatter: function(val){
                return get_prefix(val) + val;
            },
            hideOnSelect: true,
            placement: 'bottomLeft'
        });
        // add the selected icon to the hidden element.
        $('.obfx-menu-icon').on('iconpickerSelected', function(e) {
            var icon = e.iconpickerValue;
            var id = $(this).attr('data-menu-item-id');
            $('#menu-item-icon-' + id).val(icon);
        });
	} );

};

obfx_menuicons_module_admin( jQuery, menu_icons );
