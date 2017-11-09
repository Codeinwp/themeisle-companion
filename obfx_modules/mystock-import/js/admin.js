/* global _wpMediaViewsL10n, mystock_import, jQuery */
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
                priority: 30
            },
            mystock: {
                text: mystock_import.l10n.tab_name,
                priority: 40
            }
        });
    };

    var bindHandlers = media.view.MediaFrame.Select.prototype.bindHandlers;

    media.view.MediaFrame.Select.prototype.bindHandlers = function () {
        bindHandlers.apply(this, arguments);
        this.on('content:create:mystock', this.mystockContent, this);
    };

    media.view.MediaFrame.Select.prototype.mystockContent = function ( contentRegion ) {
        var state = this.state();

        this.$el.removeClass('hide-toolbar');

        contentRegion.view = new wp.media.view.RemotePhotos({
            controller: this,
            collection: state.get('library'),
            selection:  state.get('selection'),
            model:      state,
            sortable:   state.get('sortable'),
            search:     state.get('searchable'),
            filters:    state.get('filterable'),
            date:       state.get('date'),
            display:    state.has('display') ? state.get('display') : state.get('displaySettings'),
            dragInfo:   state.get('dragInfo'),

            idealColumnWidth: state.get('idealColumnWidth'),
            suggestedWidth:   state.get('suggestedWidth'),
            suggestedHeight:  state.get('suggestedHeight'),

            AttachmentView: state.get('AttachmentView')
        });
    };

    media.view.RemotePhotos = media.View.extend({
        tagName: 'div',
        className: 'obfx-attachments-browser',

        initialize: function () {
            // _.defaults(this.options, {});
            var container = this.$el;
            $(container).html('<div class="obfx_spinner"></div>');
            this.loadContent( container,this );
            this.selectItem();
            this.deselectItem();
            this.displayDetails();
            this.handleRequest();
        },

        showSpinner: function(container) {
            $(container).find('.obfx_spinner').show();
        },
        hideSpinner: function(container) {
            $(container).find('.obfx_spinner').hide();
        },
        loadContent: function(container, frame){
            this.showSpinner(container);
            $.ajax({
                type : 'POST',
                data : {
                    action: 'get-tab-' + mystock_import.slug,
                    security : mystock_import.nonce
                },
                url : mystock_import.ajaxurl,
                success : function(response) {
                    container.html(response);
                    frame.infiniteScroll(container, frame);
                }
            });
        },

        selectItem : function(){
            $(document).on('click', '.obfx-image', function () {
                $('.obfx-image').removeClass('selected details');
                $(this).addClass('selected details');
            });
        },

        deselectItem :function () {
            $(document).on('click', '.obfx-image-check', function (e) {
                e.stopPropagation();
                $(this).parent().removeClass('selected details');
                $('#obfx-mystock').find('.media-sidebar').html('');
            });
        },

        infiniteScroll : function (container, frame) {
            $('#obfx-mystock .obfx-image-list').on('scroll',function() {
                if($(this).scrollTop() + $(this).innerHeight() + 10 >= $(this)[0].scrollHeight) {
                    frame.showSpinner(container);
                    $.ajax({
                        type : 'POST',
                        data : {
                            'action': 'infinite-' + mystock_import.slug,
                            'page' : $('#obfx-mystock').data('pagenb'),
                            'security' : mystock_import.nonce
                        },
                        url : mystock_import.ajaxurl,
                        success : function(response) {
                            if( response ) {
                                var imageList = $('.obfx-image-list');
                                var listWrapper = $('#obfx-mystock');
                                var page = listWrapper.data('pagenb');
                                var nextPage = parseInt(page) + 1;
                                listWrapper.data('pagenb', nextPage);
                                imageList.append(response);
                            }
                            frame.hideSpinner(container);
                        }

                    });
                }
            });
        },

        displayDetails : function () {
            $(document).on('click', '.obfx-image', function () {
                var th = $(this);

                $.ajax({
                    type : 'POST',
                    data : {
                        'action': mystock_import.slug,
                        'pid' : $(this).data('pid'),
                        'page' : $(this).data('page'),
                        'security' : mystock_import.nonce
                    },
                    url : mystock_import.ajaxurl,
                    beforeSend : function () {
                        var text = mystock_import.l10n.fetch_image_sizes;
                        var data = '<div class="attachement-loading"><h2>'+ text +'</h2><div class="spinner is-active"></div></div>';
                        th.parent().parent().find('.media-sidebar').html(data);
                    },
                    success : function(response) {
                        th.parent().parent().find('.media-sidebar').html(response);
                    }

                });
            });
        },

        handleRequest : function () {
            $(document).on('submit','#obfx-mystock #importmsp', function (e) {
                var mediaContainer = $('#obfx-mystock').find('.media-sidebar');
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
                        var text = mystock_import.l10n.upload_image_complete;
                        var data = '<div class="attachement-loading"><h2>'+ text +'</h2></div>';
                        mediaContainer.html(data);
                        wp.media.frame.content.get('library').collection.props.set({ '__ignore_force_update': (+ new Date()) });
                    }
                });
                e.preventDefault(); // avoid to execute the actual submit of the form.
            });
        }
    });
})(jQuery);
