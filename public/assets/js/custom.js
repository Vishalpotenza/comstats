/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 * 
 */
$( function ()
{
    $( '#register_form' ).on( 'submit', function ( e )
    {
        e.preventDefault();
        alert( 'hello' );
        var formData = new FormData( $( '#register_form' )[0] );
        console.log( formData );

        url = sports.config.register;
        jQuery.ajax( {
            url: url,
            type: 'POST',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function ( data )
            {
                if ( data.success )
                {
                    toastr['success']( "Added" );
                    if ( data.error )
                    {
                        console.log( data.error );
                        toastr['error']( data.error );
                    }
                    window.location = sports.config.base_url;
                } else
                {
                    console.log( data.error );
                    toastr['error']( data.error );

                }

            }
        } );


    } );
} );
$( function ()
{
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
} );

