/* global obfx_mystock */
// jQuery(document).ready(function ($) {
//     $.mspimport = {
//         'init': function () {
//             this.selectItem();
//             this.deselectItem();
//             this.displayDetails();
//             this.infiniteScroll();
//             this.handleRequest();
//         },
//
//         'selectItem':function(){
//             $('#obfx_mystock').on('click', '.obfx_mystock_photo', function () {
//                 $('.obfx_mystock_photo').removeClass('selected details');
//                 $(this).addClass('selected details');
//             });
//         },
//
//         'deselectItem':function () {
//             $('#obfx_mystock').on('click', '.obfx_check', function (e) {
//                 e.stopPropagation();
//                 $(this).parent().removeClass('selected details');
//             });
//         },
//
//         'displayDetails': function () {
//             $('#obfx_mystock').on('click', '.obfx_mystock_photo', function () {
//                 var th = $(this);
//
//                 $.ajax({
//                     type : 'POST',
//                     data : {
//                         'action': obfx_mystock.slug,
//                         'pid' : $(this).data('pid'),
//                         'security' : obfx_mystock.nonce
//                     },
//                     url : obfx_mystock.ajaxurl,
//                     beforeSend : function () {
//                         var text = obfx_mystock.l10n.fetch_image_sizes;
//                         var data = '<div class="attachement-loading"><h2>'+ text +'</h2><div class="spinner is-active"></div></div>';
//                         th.parent().next().html(data);
//                     },
//                     success : function(response) {
//                         th.parent().next().html(response);
//                     }
//
//                 });
//             });
//         },
//
//         'infiniteScroll': function () {
//             $('.obfx_mystock_photos').on('scroll', function() {
//                 if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
//                     $.ajax({
//                         type : 'POST',
//                         data : {
//                             'action': 'infinite-' + obfx_mystock.slug,
//                             'page' : $('.obfx_mystock').data('pagenb'),
//                             'security' : obfx_mystock.nonce
//                         },
//                         url : obfx_mystock.ajaxurl,
//                         success : function(response) {
//                             if( response ) {
//                                 var imageList = $('.obfx_mystock_photos');
//                                 var listWrapper = $('#obfx_mystock');
//                                 var page = listWrapper.data('pagenb');
//                                 var nextPage = parseInt(page) + 1;
//                                 listWrapper.data('pagenb', nextPage);
//                                 imageList.append(response);
//                             }
//                         }
//
//                     });
//                 }
//             })
//         },
//
//         'handleRequest' : function () {
//
//             $('#obfx_mystock').on('submit','#importmsp', function (e) {
//                 var mediaContainer = $('#obfx_mystock').find('.media-sidebar');
//                 $.ajax({
//                     type : 'POST',
//                     data : {
//                         'action': 'handle-request-' + obfx_mystock.slug,
//                         'formdata' : $('#importmsp').serialize(),
//                         'security' : obfx_mystock.nonce
//                     },
//                     url : obfx_mystock.ajaxurl,
//                     beforeSend : function () {
//                         var text = obfx_mystock.l10n.upload_image;
//                         var data = '<div class="attachement-loading"><h2>'+ text +'</h2><div class="spinner is-active"></div></div>';
//                         mediaContainer.html(data);
//                     },
//                     success : function() {
//                         // get wp outside iframe
//
//                         var wp = parent.wp;
//
//                         // switch tabs (required for the code below)
//
//                         wp.media.frame.setState('insert');
//
//                         // refresh
//
//                         if( wp.media.frame.content.get() !== null) {
//                             wp.media.frame.content.get().collection.props.set({ignore: (+ new Date())});
//                             wp.media.frame.content.get().options.selection.reset();
//                         } else {
//                             wp.media.frame.library.props.set ({ignore: (+ new Date())});
//                         }
//                     }
//                 });
//                 e.preventDefault(); // avoid to execute the actual submit of the form.
//             })
//         }
//     };
//
//     $.mspimport.init();
//
// });

(function ($) {

    var media = wp.media,
        l10n = media.view.l10n = typeof _wpMediaViewsL10n === 'undefined' ? {} : _wpMediaViewsL10n;

    media.view.MediaFrame.Select.prototype.browseRouter = function (view) {
        view.set({
            upload: {
                text: l10n.uploadFilesTitle,
                priority: 20
            },
            browse: {
                text: l10n.mediaLibraryTitle,
                priority: 50
            },
            mystock: {
                text: mystock_import.l10n.tab_name,
                priority: 40
            }
        });
        console.log(view);
        // view.state = 'browse';
    };

    var bindHandlers = media.view.MediaFrame.Select.prototype.bindHandlers,
        dataTest, frame;

    media.view.MediaFrame.Select.prototype.bindHandlers = function () {
        bindHandlers.apply(this, arguments);
        this.on('content:create:mystock', this.mystockContent, this);
        // frame = this;
    };
    media.view.MediaFrame.Select.prototype.mystockContent = function (content) {
        var state = this.state();
        this.$el.removeClass('hide-toolbar');
        dataTest = new media.view.dataTest({});
        content.view = dataTest;
    };


    media.view.dataTest = media.View.extend({
        tagName: 'div',
        className: 'attachments-browser',

        initialize: function () {
            // _.defaults(this.options, {});
            var container = this.$el;
            this.loadContent( container,this );
            this.selectItem();
            this.deselectItem();
            this.displayDetails();
            this.handleRequest();
        },

        loadContent: function(container, frame){
            $.ajax({
                type : 'POST',
                data : {
                    action: 'get-tab-' + mystock_import.slug,
                    security : mystock_import.nonce
                },
                url : mystock_import.ajaxurl,
                success : function(response) {
                    container.html(response);

                    frame.infiniteScroll();
                }
            });
        },

        selectItem : function(){
            $(document).on('click', '.obfx_mystock_photo', function () {
                $('.obfx_mystock_photo').removeClass('selected details');
                $(this).addClass('selected details');
            });
        },

        deselectItem :function () {
            $(document).on('click', '.obfx_check', function (e) {
                e.stopPropagation();
                $(this).parent().removeClass('selected details');
            });
        },

        infiniteScroll : function () {
            $('#obfx_mystock .obfx_mystock_photos').on('scroll',function() {
                if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                    $.ajax({
                        type : 'POST',
                        data : {
                            'action': 'infinite-' + mystock_import.slug,
                            'page' : $('.obfx_mystock').data('pagenb'),
                            'security' : mystock_import.nonce
                        },
                        url : mystock_import.ajaxurl,
                        success : function(response) {
                            if( response ) {
                                var imageList = $('.obfx_mystock_photos');
                                var listWrapper = $('#obfx_mystock');
                                var page = listWrapper.data('pagenb');
                                var nextPage = parseInt(page) + 1;
                                listWrapper.data('pagenb', nextPage);
                                imageList.append(response);
                            }
                        }

                    });
                }
            })
        },

        displayDetails : function () {
            $(document).on('click', '.obfx_mystock_photo', function () {
                var th = $(this);

                $.ajax({
                    type : 'POST',
                    data : {
                        'action': mystock_import.slug,
                        'pid' : $(this).data('pid'),
                        'security' : mystock_import.nonce
                    },
                    url : mystock_import.ajaxurl,
                    beforeSend : function () {
                        var text = mystock_import.l10n.fetch_image_sizes;
                        var data = '<div class="attachement-loading"><h2>'+ text +'</h2><div class="spinner is-active"></div></div>';
                        th.parent().next().html(data);
                    },
                    success : function(response) {
                        th.parent().next().html(response);
                    }

                });
            });
        },

        handleRequest : function () {
            $(document).on('submit','#obfx_mystock #importmsp', function (e) {
                var mediaContainer = $('#obfx_mystock').find('.media-sidebar');
                $.ajax({
                    type : 'POST',
                    data : {
                        'action': 'handle-request-' + mystock_import.slug,
                        'formdata' : $('#importmsp').serialize(),
                        'security' : mystock_import.nonce
                    },
                    url : mystock_import.ajaxurl,
                    beforeSend : function () {
                        var text = mystock_import.l10n.upload_image;
                        var data = '<div class="attachement-loading"><h2>'+ text +'</h2><div class="spinner is-active"></div></div>';
                        mediaContainer.html(data);
                    },
                    success : function() {
                    }
                });
                e.preventDefault(); // avoid to execute the actual submit of the form.
            })
        },
    });

})(jQuery);
