/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 * 
 */
/**
 * =======================================================================
 * Register Admin ajax
 * =======================================================================
 */
$( function ()
{
    $( '#register_form' ).on( 'submit', function ( e )
    {
        e.preventDefault();
        var formData = new FormData( $( '#register_form' )[0] );
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
/**
 * =======================================================================
 *  Admin login ajax
 * =======================================================================
 */
$( function ()
{
    $( '#login_form' ).on( 'submit', function ( e )
    {
        e.preventDefault();
        alert( 'hello' );
        var formData = new FormData( $( '#login_form' )[0] );
        console.log( formData );

        url = sports.config.login;
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
                    toastr['success']( data.message );
                    if ( data.error )
                    {
                        console.log( data.error );
                        toastr['error']( data.error );
                    }
                    setTimeout( function ()
                    {
                        window.location.href = sports.config.base_url;
                    }, 5000 );
                } else
                {
                    if ( data.message != null )
                    {
                        toastr['error']( data.message );
                    }

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


$( function ()
{
    $( window ).on( "load", function ()
    {
        var url = sports.config.countries
        jQuery.ajax( {
            url: url,
            type: 'GET',
            dataType: "json",
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function ( result )
            {
                console.log( result );
                result.forEach( function ( data, index )
                {
                    //id
                    //name
                    $( '#inputcountry' ).append( "<option value=" + data.id + ">" + data.name + "</option>" )
                } );
            }
        } );
    } );
} );

$( function ()
{
    $( '#inputcountry' ).on( "change", function ()
    {
        var countryid = $( this ).val();
        var url = sports.config.state
        jQuery.ajax( {
            url: url,
            type: 'POST',
            dataType: "json",
            data: { id: $( this ).val() },
            success: function ( result )
            {
                $( '#inputState' ).empty();
                $( '#inputState' ).append( "<option selected>Choose State</option>" );
                result.forEach( function ( data, index )
                {

                    $( '#inputState' ).append( "<option value=" + data.id + ">" + data.name + "</option>" )
                } );
            },
            error: function ( jqXHR, textStatus, errorThrown ) { console.log( textStatus ) }

        } );
    } );
} );
$( function ()
{
    $( '#inputState' ).on( "change", function ()
    {

        var url = sports.config.city
        jQuery.ajax( {
            url: url,
            type: 'POST',
            dataType: "json",
            data: { id: $( this ).val() },
            success: function ( result )
            {
                $( '#inputcity' ).empty();
                $( '#inputcity' ).append( "<option selected>Choose City</option>" );
                result.forEach( function ( data, index )
                {

                    $( '#inputcity' ).append( "<option value=" + data.id + ">" + data.name + "</option>" )
                } );
            },
            error: function ( jqXHR, textStatus, errorThrown ) { console.log( textStatus ) }

        } );
    } );
} );