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

	$( function() {
        // ensure the popover comes over the menu bar.
        $('.menu-item-bar .menu-item-handle').css('overflow', 'initial');
        // add the menu item id to the dropdown as an attribute.
        $('li.menu-item').each(function(i, x){
            var item_id = $(x).find('input.menu-item-data-db-id').val();
            var icon    = $('#menu-item-icon-' + item_id).val();
            if('' === icon){
                icon    = 'fa-blank'; // default.
            }
            $(x).find('.menu-item-bar .menu-item-handle .item-title').prepend($(
            '<div class="btn-group"><button type="button" class="btn btn-primary iconpicker-component"><i class="fa fa-fw fa-heart"></i></button><button type="button" class="obfx-menu-icon btn btn-primary dropdown-toggle" data-menu-item-id="' + item_id + '" data-selected="' + icon + '" data-toggle="dropdown"><span class="caret"></span></button><div class="dropdown-menu"></div></div>&nbsp;'
            ));
        });
        $('.obfx-menu-icon').iconpicker({
            // added blank icon for deselection.
            icons: $.merge(['fa-blank'], $.merge(menu_icons.icons, $.iconpicker.defaultOptions.icons)),
            fullClassFormatter: function(val){
                if(val.match(/^fa-/)){
                    return 'fa '+ val;
                }else{
                    return 'dashicons '+ val;
                }
            }
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
