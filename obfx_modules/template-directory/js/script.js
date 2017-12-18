/* global importer_endpoint, console */

/**
 * Template Directory Customizer Admin Dashboard Script
 *
 * This handles the template directory.
 *
 * @since    1.0.0
 * @package obfx_modules/template-directory/js
 *
 * @author    ThemeIsle
 */

var obfx_template_directory = function ( $ ) {
    'use strict';

    $(
        function () {

            //Hide preview.
            $( '.close-full-overlay' ).on( 'click', function () {
                $( '.obfx-template-preview .obfx-theme-info.active' ).removeClass( 'active' );
                $( '.obfx-template-preview' ).hide();
                $( '.obfx-template-frame' ).attr( 'src', '' );
            } );

            //Open preview routine.
            $( '.obfx-preview-template' ).on( 'click', function () {
                var templateSlug = $( this ).data( 'template-slug' );
                var previewUrl = $( this ).data( 'demo-url' );
                $( '.obfx-template-frame' ).attr( 'src', previewUrl );
                $( '.obfx-theme-info.' + templateSlug ).addClass( 'active' );
                setupImportButton();
                $( '.obfx-template-preview' ).fadeIn();
            } );

            //Handle left-right navigation between templates.
            $( '.obfx-next-prev .next-theme' ).on( 'click', function () {
                var active = $( '.obfx-theme-info.active' ).removeClass( 'active' );
                if ( active.next() && active.next().length ) {
                    active.next().addClass( 'active' );
                } else {
                    active.siblings( ':first' ).addClass( 'active' );
                }
                changePreviewSource();
                setupImportButton();
            } );
            $( '.obfx-next-prev .previous-theme' ).on( 'click', function () {
                var active = $( '.obfx-theme-info.active' ).removeClass( 'active' );
                if ( active.prev() && active.prev().length ) {
                    active.prev().addClass( 'active' );
                } else {
                    active.siblings( ':last' ).addClass( 'active' );
                }
                changePreviewSource();
                setupImportButton();
            } );

            //Handle import click.
            $( '.wp-full-overlay-header' ).on(
                'click', '.obfx-import-template', function () {
                    $( this ).addClass( 'obfx-import-queue updating-message obfx-updating' ).html( '' );
                    $( '.obfx-template-preview .close-full-overlay, .obfx-next-prev' ).remove();
                    var template_url = $( this ).data( 'template-file' );
                    var template_name = $( this ).data( 'template-title' );
                    if ( $( '.active .obfx-installable' ).length > 0 ) {
                        checkAndInstallPlugins();
                    } else {
                        $.ajax(
                            {
                                url: importer_endpoint.url,
                                beforeSend: function ( xhr ) {
                                    $( '.obfx-import-queue' ).addClass( 'obfx-updating' ).html( '' );
                                    xhr.setRequestHeader( 'X-WP-Nonce', importer_endpoint.nonce );
                                },
                                async: false,
                                data: {
                                    template_url: template_url,
                                    template_name: template_name
                                },
                                type: 'POST',
                                success: function ( data ) {
                                    $( '.obfx-updating' ).replaceWith( '<span class="obfx-done-import"><i class="dashicons-yes dashicons"></i></span>' );
                                    location.href = data;
                                },
                                error: function ( error ) {
                                    console.error( error );
                                }
                            }, 'json'
                        );
                    }
                }
            );

            //Handle sidebar collapse in preview.
            $( '.obfx-template-preview' ).on( 'click', '.collapse-sidebar', function () {
                event.preventDefault();
                var overlay = $( '.obfx-template-preview' );
                if ( overlay.hasClass( 'expanded' ) ) {
                    overlay.removeClass( 'expanded' );
                    overlay.addClass( 'collapsed' );
                    return false;
                }

                if ( overlay.hasClass( 'collapsed' ) ) {
                    overlay.removeClass( 'collapsed' );
                    overlay.addClass( 'expanded' );
                    return false;
                }
            } );

            //Handle responsive buttons.
            $( '.obfx-responsive-preview' ).on( 'click', 'button', function () {
                $( '.obfx-template-preview' ).removeClass( 'preview-mobile preview-tablet preview-desktop' );
                var deviceClass = 'preview-' + $( this ).data( 'device' );
                $( '.obfx-responsive-preview button' ).each( function () {
                    $( this ).attr( 'aria-pressed', 'false' );
                    $( this ).removeClass( 'active' );
                } );

                $( '.obfx-responsive-preview' ).removeClass( $( this ).attr( 'class' ).split( ' ' ).pop() );
                $( '.obfx-template-preview' ).addClass( deviceClass );
                $( this ).addClass( 'active' );
            } );

            //Change preview source.
            function changePreviewSource() {
                var previewUrl = $( '.obfx-theme-info.active' ).data( 'demo-url' );
                $( '.obfx-template-frame' ).attr( 'src', previewUrl );
            }

            function setupImportButton() {
                var installable = $( '.active .obfx-installable' );
                if(installable.length > 0) {
                   $('.wp-full-overlay-header .obfx-import-template').text('Install and Import');
                } else {
                    $('.wp-full-overlay-header .obfx-import-template').text('Import');
                }
                var activeTheme = $( '.obfx-theme-info.active' );
                var button = $( '.wp-full-overlay-header .obfx-import-template' );
                $( button ).attr( 'data-template-file', $( activeTheme ).data( 'template-file' ) );
                $( button ).attr( 'data-template-title', $( activeTheme ).data( 'template-title' ) );
            }

            function checkAndInstallPlugins() {
                var installable = $( '.active .obfx-installable' );
                if ( installable.length ) {
                    $( installable ).each( function () {
                        var slug = $( this ).find( '.obfx-install-plugin' ).attr( 'data-slug' );
                        wp.updates.installPlugin(
                            {
                                slug: slug
                            }
                        );
                        $( this ).removeClass( 'obfx-installable' ).addClass( 'obfx-installed' );
                        $( this ).find( 'span.dashicons' ).replaceWith( '<span class="dashicons dashicons-update" style="-webkit-animation: rotation 2s infinite linear; animation: rotation 2s infinite linear; color: #ffb227 "></span>' );
                    } );
                }
            }

            // Remove activate button and replace with activation in progress button.
            $( document ).on(
                'DOMNodeInserted', '.activate-now', function () {
                    var activateButton = $( '.obfx-installed .activate-now' );
                    var url = $( activateButton ).attr( 'href' );
                    var marker = $( activateButton ).prev();
                    if ( typeof url !== 'undefined' ) {
                        // Request plugin activation.
                        $.ajax(
                            {
                                beforeSend: function () {
                                    $( activateButton ).replaceWith( '<a class="button updating-message">Activating...</a>' );
                                },
                                async: true,
                                type: 'GET',
                                url: url,
                                success: function () {
                                    $( marker ).replaceWith( '<span class="dashicons dashicons-yes" style="color: #34a85e"></span>' )
                                    var installable = $( '.active .obfx-installable' );
                                    if ( !installable.length > 0 && $( '.still-installing' ).length === 0 ) {
                                        $( '.obfx-import-queue' ).trigger( 'click' );
                                    }
                                }
                            }
                        );
                    }
                }
            );
        }
    );
};

obfx_template_directory( jQuery );