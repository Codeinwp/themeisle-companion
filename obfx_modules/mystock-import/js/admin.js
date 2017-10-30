jQuery(document).ready(function ($) {
    $.mspimport = {
        'init': function () {
            this.selectItem();
            this.deselectItem();
            this.displayDetails();
            this.infiniteScroll();
        },

        'selectItem':function(){
            $('.obfx_mystock_photo').on('click', function () {
                $('.obfx_mystock_photo').removeClass('selected details');
                $(this).addClass('selected details');
            });
        },

        'deselectItem':function () {
            $('.obfx_check').on('click', function (e) {
                e.stopPropagation();
                $(this).parent().removeClass('selected details');
            });
        },

        'displayDetails': function () {
            $('.obfx_mystock_photo').on('click',function () {
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

                            alert( response );
                            $('.obfx-mystock-wrapper').replaceWith( response );
                        }

                    });
                }
            })
        }
    };

    $.mspimport.init();

});
