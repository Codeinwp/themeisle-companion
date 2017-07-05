/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    Orbit_Fox
 * @subpackage Orbit_Fox/admin/js
 */

(function( $ ) {
	'use strict';
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$( function() {
		var obfx_menu = $( '#toplevel_page_obfx_menu' ).clone().wrap( '<p/>' ).parent().html();
		$( '#toplevel_page_obfx_menu' ).remove();
		$( '#toplevel_page_jetpack' ).before( obfx_menu );
	} );

	$( function() {
		$( '.obfx-toast-dismiss' ).on( 'click', function() {
			$( this ).closest( '.obfx-mod-toast' ).slideUp( 400, function() {
				$( this ).removeClass( 'toast-success' );
				$( this ).removeClass( 'toast-error' );
				$( this ).removeClass( 'toast-warning' );
			} );
		} );

		$( '.obfx-module-form' ).on( 'submit', function (e) {
			e.preventDefault();
		} );

		$( '.obfx-module-form' ).on( 'keyup change', 'input, select, textarea', function () {
			$( this ).closest( 'form' ).find( '[class*="obfx-mod-btn"]:disabled' ).removeAttr( 'disabled' );
		} );

		$( '.obfx-mod-btn-cancel' ).on( 'click', function () {
			$( this ).closest( 'form' ).trigger( 'reset' );
			$( this ).closest( 'form' ).find( '[class*="obfx-mod-btn"]' ).attr( 'disabled', true );
		} );

		$( '.obfx-mod-btn-save' ).on( 'click', function () {
			var module_form = $( this ).closest( 'form' );
			module_form.find( '[class*="obfx-mod-btn"]' ).attr( 'disabled', true );
			module_form.find( '.obfx-mod-btn-save' ).addClass( 'loading' );
			module_form.find( $( 'input:checkbox:not(:checked)' ) ).each( function() {
				var input = $( '<input />' );
				input.attr( 'type', 'hidden' );
				input.attr( 'name', $( this ).attr( 'name' ) );
				input.attr( 'value', '0' );
				var form = $( this )[0].form;
				$( form ).append( input );
			} );
			var form_data = module_form.serializeArray();
			var maped_array = {};
			$.each( form_data, function( i, elem ) {
				maped_array[ elem['name'] ] = elem['value'];
			} );

			form_data = JSON.stringify( maped_array );

			var ajax_data = {
				'action': 'obfx_update_module_options',
				'data': form_data
			};

			$.post( 'admin-ajax.php', ajax_data, function( response ) {
				module_form.find( '.obfx-mod-btn-save' ).removeClass( 'loading' );
				if ( response.type ) {
					module_form.closest( '.panel' ).find( '.obfx-mod-toast' ).addClass( 'toast-' + response.type );
					module_form.closest( '.panel' ).find( '.obfx-mod-toast span' ).html( response.message );
					module_form.closest( '.panel' ).find( '.obfx-mod-toast' ).show();
					setTimeout( function() {
						module_form.closest( '.panel' ).find( '.obfx-toast-dismiss' ).trigger( 'click' );
					}, 2000 );
				}
			}, 'json');

		} );

	} );

})( jQuery );
