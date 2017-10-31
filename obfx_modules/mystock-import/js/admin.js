jQuery(document).ready(function ($) {
    $.mspimport = {
        'init': function () {
            this.selectItem();
            this.deselectItem();
            this.displayDetails();
            this.infiniteScroll();
            this.handleRequest();
        },

        'selectItem':function(){
            $('#obfx_mystock').on('click', '.obfx_mystock_photo', function () {
                $('.obfx_mystock_photo').removeClass('selected details');
                $(this).addClass('selected details');
            });
        },

        'deselectItem':function () {
            $('#obfx_mystock').on('click', '.obfx_check', function (e) {
                e.stopPropagation();
                $(this).parent().removeClass('selected details');
            });
        },

        'displayDetails': function () {
            $('#obfx_mystock').on('click', '.obfx_mystock_photo', function () {
                var th = $(this);

                $.ajax({
                    type : 'POST',
                    data : {
                        'action': obfx_mystock.slug,
                        'pid' : $(this).data('pid')
                    },
                    url : obfx_mystock.ajaxurl,
                    beforeSend : function () {
                        th.parent().next().html('Fetching data');
                    },
                    success : function(response) {
                        // alert( response );
                        console.log(response);
                        th.parent().next().html(response);
                    }

                });
            });
        },
        
        'infiniteScroll': function () {
            $('.obfx_mystock_photos').on('scroll', function() {
                if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                    $.ajax({
                        type : 'POST',
                        data : {
                            'action': 'infinite-' + obfx_mystock.slug,
                            'page' : $('.obfx_mystock').data('pagenb'),
                        },
                        url : obfx_mystock.ajaxurl,
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
        
        'handleRequest' : function () {

            $('#obfx_mystock').on('submit','#importmsp', function (e) {
                var mediaContainer = $('#obfx_mystock').find('.media-sidebar');
                $.ajax({
                    type : 'POST',
                    data : {
                        'action': 'handle-request-' + obfx_mystock.slug,
                        'formdata' : $('#importmsp').serialize()
                    },
                    url : obfx_mystock.ajaxurl,
                    beforeSend : function () {
                        mediaContainer.html('Uploading image. Please wait...');
                    },
                    success : function() {
                        var wp = parent.wp;
                        // switch tabs (required for the code below)
                        wp.media.frame.setState('insert');
                        // refresh
                        if( wp.media.frame.content.get() !== null) {
                            wp.media.frame.content.get().collection.props.set({ignore: (+ new Date())});
                            wp.media.frame.content.get().options.selection.reset();
                        } else {
                            wp.media.frame.library.props.set ({ignore: (+ new Date())});
                        }
                    }
                });
                e.preventDefault(); // avoid to execute the actual submit of the form.
            })
        }
};

    $.mspimport.init();

});