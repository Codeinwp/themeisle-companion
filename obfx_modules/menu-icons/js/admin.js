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

	var default_icon = {
		title: menu_icons.icon_default,
		searchTerms: ['cancel', 'none']
	};

	var custom_icons = menu_icons.icons.map( function( icon ) {
		return {
			title: icon,
			searchTerms: icon.split( '-' )
		};
	});

	var all_icons = $.merge( [default_icon], $.merge( custom_icons, $.iconpicker.defaultOptions.icons ) );

	function get_prefix(icon, includeFA){
		if (typeof icon === 'undefined') {
			return '';
		}

		if ( includeFA === true ) {
			if (icon.match( /^fas fa-/ )) {
				return 'fas ';
			} else if (icon.match( /^far fa-/ )) {
				return 'far ';
			} else if (icon.match( /^fab fa-/ )) {
				return 'fab ';
			} else if (icon.match( /^fa-/ )) {
				return 'fa ';
			} else if (icon.match( /^dashicons-/ )) {
				return 'dashicons ';
			} else if (icon.match( /glyphicon-/ )) {
				return 'glyphicon ';
			} else {
				return '';
			}
		} else {
			if (icon.match( /^dashicons-/ )) {
				return 'dashicons ';
			} else if (icon.match( /glyphicon-/ )) {
				return 'glyphicon ';
			} else {
				return '';
			}
		}
	}

	// lets observe for new li tags added to the ul for when items are added to the menu.
	function listen_for_new_items(){
		var mutateObserver = new MutationObserver(
			function(records) {
				records.forEach(
					function(record) {
						$( record.addedNodes ).each(
							function(i, x){
								// process only the li elements.
								if ($( x ).prop( 'tagName' ) === 'LI') {
									add_icon( x );
								}
							}
						);
					}
				);
			}
		);

		mutateObserver.observe( $( 'ul#menu-to-edit' ).get( 0 ), {childList: true} );
	}

	function add_icon(el){
		var item_id = $( el ).find( 'input.menu-item-data-db-id' ).val();
		var icon    = $( '#menu-item-icon-' + item_id ).val();
		var no_icon_class = '';
		if ('' === icon) {
			icon    = default_icon['title'];
			no_icon_class = 'obfx-menu-icon-none';
		}
		var prefix  = get_prefix( icon, true );

		if ( ! $( el ).find( '.menu-item-bar .menu-item-handle .item-title div' ).hasClass( 'obfx-menu-icon-container' ) ) {
			$( el ).find( '.menu-item-bar .menu-item-handle .item-title' ).prepend(
				$(
					'<div class="input-group obfx-menu-icon-container" style="display: inline-block"><input class="form-control obfx-menu-icon ' + no_icon_class + '" value="' + icon + '" style="display: none" type="text" data-menu-item-id="' + item_id + '"><span class="input-group-addon" style="cursor: pointer"><i class="fa-fw ' + prefix + icon + '"></i></span></div>'
				)
			);
		}

		// ensure the popover comes over the menu bar.
		$( el ).find( '.menu-item-bar .menu-item-handle' ).css( 'overflow', 'initial' );

		$( el ).find( '.obfx-menu-icon ~ span' ).on(
			'hover', function(){
				$( this ).parent().find( '.obfx-menu-icon' ).iconpicker(
					{
						icons: all_icons,
						fullClassFormatter: function(val){
							return get_prefix( val, false ) + val;
						},
						hideOnSelect: true,
						placement: 'bottomLeft',
						selectedCustomClass: 'obfx-menu-icon-selected',
						showFooter: true,
						templates: {
							footer: '<div class="obfx-menu-icon-footer">' + menu_icons.i10n.powered_by + '</div>'
						}
					}
				).on(
					'iconpickerSelected', function(e) {
						var icon = e.iconpickerValue;
						var id = $( this ).attr( 'data-menu-item-id' );
						$( '#menu-item-icon-' + id ).val( icon );
					}
				);
			}
		);

	}

	$(
		function() {
				// add the existing menu item id to the dropdown as an attribute.
				$( 'li.menu-item' ).each(
					function(i, x){
						add_icon( x );
					}
				);

				listen_for_new_items();
		}
	);

};

obfx_menuicons_module_admin( jQuery, menu_icons );
