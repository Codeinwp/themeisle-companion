( function ( $ )  {

    // $( document ).ready ( function () {
        $( 'body' ).delegate( '.form-switch', 'click', function(e) {
            var input = $(this).find('.fancy-checkbox');


            if( input.is(':checked') ){
                input.attr('value','1');
                input.val('1');
            } else {
                input.attr('value','0');
                input.val('0');
            }

        });
    // });

} ) ( jQuery );