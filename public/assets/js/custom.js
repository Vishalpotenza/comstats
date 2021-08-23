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
                        window.location.href = sports.config.base_url + "/admin/clubs";
                    }, 2000 );
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
    $( '#btnaddclub' ).on( "click", function ()
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
/**
 * Edit
 */
$( function ()
{
    $( '#inputState1' ).on( "change", function ()
    {

        var url = sports.config.city
        jQuery.ajax( {
            url: url,
            type: 'POST',
            dataType: "json",
            data: { id: $( this ).val() },
            success: function ( result )
            {
                $( '#inputcity1' ).empty();
                $( '#inputcity1' ).append( "<option selected>Choose City</option>" );
                result.forEach( function ( data, index )
                {

                    $( '#inputcity1' ).append( "<option value=" + data.id + ">" + data.name + "</option>" )
                } );
            },
            error: function ( jqXHR, textStatus, errorThrown ) { console.log( textStatus ) }

        } );
    } );
} );
$( function ()
{
    $( '#inputcountry1' ).on( "change", function ()
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
                $( '#inputState1' ).empty();
                $( '#inputState1' ).append( "<option selected>Choose State</option>" );
                result.forEach( function ( data, index )
                {

                    $( '#inputState1' ).append( "<option value=" + data.id + ">" + data.name + "</option>" )
                } );
            },
            error: function ( jqXHR, textStatus, errorThrown ) { console.log( textStatus ) }

        } );
    } );
} );
/**
 * =======================================================================
 *  Add Club ajax
 * =======================================================================
 */
$( function ()
{
    $( '#add_club' ).on( 'submit', function ( e )
    {
        e.preventDefault();
        var formData = new FormData( $( '#add_club' )[0] );
        url = sports.config.add_club;
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
                    window.location = sports.config.base_url + "/admin/clubs";
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
 * ==========================================================
 * Delete Club
 * ================================================================
 */
$( function ()
{
    $( '.deleteclub' ).on( 'click', function ( e )
    {
        e.preventDefault();
        console.log( $( this ).attr( 'id' ) );
        // alert( $( this ).attr( 'id' ) );
        var club_id = $( this ).attr( 'id' );
        url = sports.config.delete_club;
        jQuery.ajax( {
            url: url,
            type: 'POST',
            dataType: "json",
            data: { club_id: club_id },
            success: function ( data )
            {
                if ( data.success )
                {
                    toastr['success']( "Club Deleted Successfully" );
                    if ( data.error )
                    {
                        console.log( data.error );
                        toastr['error']( data.error );
                    }
                    window.location = sports.config.base_url + "/admin/clubs";
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
 * =========================================
 * Edit Club
 * ============================================
 */
$( function ()
{
    $( '.edit_club' ).on( 'click', function ( e )
    {
        e.preventDefault();
        console.log( $( this ).attr( 'id' ) );
        var club_id = $( this ).attr( 'id' );
        url = sports.config.get_club;
        jQuery.ajax( {
            url: url,
            type: 'POST',
            dataType: "json",
            data: { club_id: club_id },
            success: function ( data )
            {
                console.log( data );
                if ( data != null )
                {
                    $( "#clubname1" ).val( data.club_name );
                    $( "#contactno1" ).val( data.contact_no );
                    $( "#inputAddress1" ).val( data.address );
                    $( "#inputcountry1" ).val( data.country_id );
                    $( "#inputState1" ).val( data.state_id );
                    $( "#inputcity1" ).val( data.city_id );
                    $( "#edit_club_id" ).val( club_id );
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
                            result.forEach( function ( country, index )
                            {
                                if ( country.id == data.country_id )
                                {
                                    console.log( country.id );
                                    $( '#inputcountry1' ).append( "<option value=" + country.id + " selected>" + country.name + "</option>" )
                                }
                                else
                                {
                                    $( '#inputcountry1' ).append( "<option value=" + country.id + ">" + country.name + "</option>" )
                                }

                            } );
                        }
                    } );
                    var url = sports.config.state
                    jQuery.ajax( {
                        url: url,
                        type: 'POST',
                        dataType: "json",
                        data: { id: data.country_id },
                        success: function ( result )
                        {
                            $( '#inputState1' ).empty();
                            $( '#inputState1' ).append( "<option selected>Choose State</option>" );
                            result.forEach( function ( state, index )
                            {
                                if ( state.id == data.state_id )
                                {
                                    $( '#inputState1' ).append( "<option value=" + state.id + " selected>" + state.name + "</option>" )
                                }
                                else
                                {
                                    $( '#inputState1' ).append( "<option value=" + state.id + ">" + state.name + "</option>" )
                                }
                            } );
                        },
                        error: function ( jqXHR, textStatus, errorThrown ) { console.log( textStatus ) }

                    } );
                    var url = sports.config.city
                    jQuery.ajax( {
                        url: url,
                        type: 'POST',
                        dataType: "json",
                        data: { id: data.state_id },
                        success: function ( result )
                        {
                            $( '#inputcity1' ).empty();
                            $( '#inputcity1' ).append( "<option selected>Choose City</option>" );
                            result.forEach( function ( city, index )
                            {
                                if ( city.id == data.city_id )
                                {
                                    $( '#inputcity1' ).append( "<option value=" + city.id + " selected>" + city.name + "</option>" )
                                }
                                else
                                {
                                    $( '#inputcity1' ).append( "<option value=" + city.id + ">" + city.name + "</option>" )
                                }

                            } );
                        },
                        error: function ( jqXHR, textStatus, errorThrown ) { console.log( textStatus ) }

                    } );
                    $( '.bd-edit-club-lg' ).modal( 'show' );
                }

            }
        } );
    } );
} );

$( function ()
{
    $( '#edit_club_form' ).on( 'submit', function ( e )
    {
        e.preventDefault();
        var formData = new FormData( $( '#edit_club_form' )[0] );
        console.log( formData );
        url = sports.config.edit_club;
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
                    window.location = sports.config.base_url+'/admin/clubs';
                } else
                {
                    console.log( data.error );
                    toastr['error']( data.error );

                }

            }
        } );


    } );
} );

$( document ).ready(function() {
	var profile_images_path = $( "#profile_images_path" ).val();
	/**
	 * =======================================================================
	 *  Add League ajax
	 * =======================================================================
	 */
	$( '#add_league' ).on( 'submit', function ( e ){
        e.preventDefault();
        var formData = new FormData( $( '#add_league' )[0] );
        url = sports.config.add_league;
		console.log('formData => '+formData);
		console.log('add_league => '+url);
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
                    window.location = sports.config.base_url + "/admin/league";
                } else
                {
                    console.log( data.error );
                    toastr['error']( data.error );
                }
            }
        } );
    } );
	
	/**
	 * ==========================================================
	 * Delete League
	 * ================================================================
	 */
	 
	$( '.deleteleague' ).on( 'click', function ( e ){
        e.preventDefault();
        console.log( $( this ).attr( 'id' ) );
        // alert( $( this ).attr( 'id' ) );
        var id = $( this ).attr( 'id' );
        url = sports.config.delete_league;
        jQuery.ajax( {
            url: url,
            type: 'POST',
            dataType: "json",
            data: { id: id },
            success: function ( data )
            {
                if ( data.success )
                {
                    toastr['success']( "League Deleted Successfully" );
                    if ( data.error )
                    {
                        console.log( data.error );
                        toastr['error']( data.error );
                    }
                    window.location = sports.config.base_url + "/admin/league";
                } else
                {
                    console.log( data.error );
                    toastr['error']( data.error );
                }
            }
        } );
    } );
	
	/**
	 * =========================================
	 * Edit League
	 * ============================================
	 */
	$( '.edit_league' ).on( 'click', function ( e ){
        e.preventDefault();
        // console.log( $( this ).attr( 'id' ) );
        var id = $( this ).attr( 'id' );
        url = sports.config.get_league;
        jQuery.ajax( {
            url: url,
            type: 'POST',
            dataType: "json",
            data: { id: id },
            success: function ( data )
            {
                // console.log( data.name );
				 if ( data != null )
                {
                    $( "#leaguename1" ).val( data.name );
                    $( "#edit_data_id" ).val( data.id );
                }
                $( '.bd-edit-League-lg' ).modal( 'show' );				
                
            }
        } );
    } );
	
	$( '#edit_league_form' ).on( 'submit', function ( e )
    {
        e.preventDefault();
        var formData = new FormData( $( '#edit_league_form' )[0] );
        console.log( "formdata => "+formData );
        url = sports.config.edit_league;
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
                    window.location = sports.config.base_url+'/admin/league';
                } else
                {
                    console.log( data.error );
                    toastr['error']( data.error );
                }
            }
        } );
    } );
	
	
	
	/**
	 * =======================================================================
	 *  Add Team ajax
	 * =======================================================================
	 */
	$( '#add_team' ).on( 'submit', function ( e ){
        e.preventDefault();
        var formData = new FormData( $( '#add_team' )[0] );
        url = sports.config.add_team;
		console.log('formData => '+formData);
		console.log('add_team => '+url);
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
                    window.location = sports.config.base_url + "/admin/team";
                } else
                {
                    console.log( data.error );
                    toastr['error']( data.error );
                }
            }
        } );
    } );
	
	/**
	 * ==========================================================
	 * Delete team
	 * ================================================================
	 */
	 
	$( '.deleteteam' ).on( 'click', function ( e ){
        e.preventDefault();
        console.log( $( this ).attr( 'id' ) );
        // alert( $( this ).attr( 'id' ) );
        var team_id = $( this ).attr( 'id' );
        url = sports.config.delete_team;
		console.log('url = '+url);
        jQuery.ajax( {
            url: url,
            type: 'POST',
            dataType: "json",
            data: { team_id: team_id },
            success: function ( data )
            {
                if ( data.success )
                {
                    toastr['success']( "team Deleted Successfully" );
                    if ( data.error )
                    {
                        console.log( data.error );
                        toastr['error']( data.error );
                    }
                    window.location = sports.config.base_url + "/admin/team";
                } else
                {
                    console.log( data.error );
                    toastr['error']( data.error );
                }
            }
        } );
    } );
	
	/**
	 * =========================================
	 * Edit team
	 * ============================================
	 */
	$( '.edit_team' ).on( 'click', function ( e ){
        e.preventDefault();
        console.log( $( this ).attr( 'id' ) );
        var team_id = $( this ).attr( 'id' );
        url = sports.config.get_team;
		 console.log( 'url = '+ url );
        jQuery.ajax( {
            url: url,
            type: 'POST',
            dataType: "json",
            data: { team_id: team_id },
            success: function ( data )
            {
                console.log( "edit show " );
				 if ( data != null )
                {
                    $( "#team_name1" ).val( data.team_name );
                    $( "#edit_data_id" ).val( data.team_id );
                    $( "#club_id1" ).val( data.club_id );
                }
                $( '.bd-edit-team-lg' ).modal( 'show' );				
                
            }
        } );
	} );
	
	$( '#edit_team_form' ).on( 'submit', function ( e )
    {
        e.preventDefault();
        var formData = new FormData( $( '#edit_team_form' )[0] );
        console.log( "formdata => "+formData );
        url = sports.config.edit_team;
		console.log( 'url = '+ url );
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
                    window.location = sports.config.base_url+'/admin/team';
                } else
                {
                    console.log( data.error );
                    toastr['error']( data.error );
                }
            }
        } );
    } );
	 
	/*
	* Club View members
	*
	*/
	$( '.view_members' ).on( 'click', function ( e ){
        e.preventDefault();
        // console.log( $( this ).attr( 'id' ) );
        var club_id = $( this ).attr( 'id' );
        url = sports.config.view_members;
        jQuery.ajax( {
            url: url,
            type: 'POST',
            dataType: "json",
            data: { club_id: club_id },
            success: function ( data )
            {
                if ( data != null )
                {
					var list='';
					var i=1;
					$.each(data.data, function( index, value ) {
						
					  // list+='<div class="form-group1 col-md-4">'+(i++)+'<label for="clubname1">'+value.team_name+'</label></div>';
					  // list+='<label>'+(i++)+'</label><label class="form-group col-md-12" for="clubname1">'+value.team_name+'</label>';
					  list+='<label class="form-group col-md-12" for="clubname1">'+value.team_name+'</label>';
					});
                    $( "#list_of_memer" ).html( list );
                }
                $( '.bd-view-member-club-lg' ).modal( 'show' );				
                
            }
        } );
    } ); 
	
	/**
	 * =========================================
	 * View user
	 * ============================================
	 */
	$( '.user_detail_view' ).on( 'click', function ( e ){
        e.preventDefault();
        console.log( $( this ).attr( 'id' ) );
        var user_id = $( this ).attr( 'id' );
        url = sports.config.get_user;
		 console.log( 'url = '+ url );
        jQuery.ajax( {
            url: url,
            type: 'POST',
            dataType: "json",
            data: { user_id: user_id },
            success: function ( data )
            {
                console.log( "edit show " );
                console.log( data.result );
				 if ( data.result != null )
                {
					console.log("profile_images_path = "+profile_images_path);
                    $( "#first_name" ).val( data.result.first_name );
                     $( "#last_name" ).val( data.result.last_name );
                     $( "#address" ).val( data.result.address );
                     $( "#nationality" ).val( data.result.nationality );
                     // $( "#flag_image" ).val( data.result.flag_image );
                      // $( "#flag_image" ).attr( 'src', base_url+'/public/uploads/flags/'+data.result.nationality_id+'/'+data.result.flag_image );
                     $( "#flag_image" ).attr( 'src', base_url+'/public/uploads/flags/'+data.result.nationality_id+'/'+data.result.flag_image );
                     $( "#age" ).val( data.result.age );
                     $( "#gender" ).val( data.result.gender );
                     $( "#height" ).val( data.result.height );
                     $( "#weight" ).val( data.result.weight );
					 // $( "#img" ).attr( 'src', data.result.img );
					 if(data.result.image != ''){
						$( "#profile_img" ).attr( 'src', profile_images_path+data.result.user_id+'/'+data.result.image );
					 }else{
						 $( "#profile_img" ).attr( 'src', 'https://kctherapy.com/wp-content/uploads/2019/09/default-user-avatar-300x293.png' );
					 }
                   
                }
                $( '.bd-View-User-lg' ).modal( 'show' ).delay( 2000 );					
                
            }
        } );
	} );
	/*
	* Admin Data Update
	*
	*/
	$( '#edit_admin_form' ).on( 'submit', function ( e )
    {
        e.preventDefault();
        var formData = new FormData( $( '#edit_admin_form' )[0] );
        console.log( "formdata => "+formData );
        url = base_url+'/admin/edit';
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
                    toastr['success']( "Updated" );
                    if ( data.error )
                    {
                        console.log( data.error );
                        toastr['error']( data.error );
                    }
					setTimeout(function () {
						window.location = sports.config.base_url+'/admin/profile';
					   }, 1000);
                    // window.location = sports.config.base_url+'/admin/league';
                } else
                {
                    console.log( data.error );
                    toastr['error']( data.error );
                }
            }
        } );
    } );
	/*
	* Admin  Update password
	*
	*/
	$( '#update_password_form' ).on( 'submit', function ( e )
    {
        e.preventDefault();
        var formData = new FormData( $( '#update_password_form' )[0] );
        console.log( "formdata => "+formData );
        url = base_url+'/admin/pass/update';
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
                    toastr['success']( "Updated" );
                    if ( data.error )
                    {
                        console.log( data.error );
                        toastr['error']( data.error );
                    }
                    setTimeout(function () {
						window.location = sports.config.base_url+'/admin/profile';
					   }, 1000);
                } else
                {
                    console.log( data.error );
                    toastr['error']( data.error );
                }
            }
        } );
    } );
	
	
	/**
	 * =======================================================================
	 *  Add Firebase ajax
	 * =======================================================================
	 */
	$( '#add_firebase' ).on( 'submit', function ( e ){
        e.preventDefault();
        var formData = new FormData( $( '#add_firebase' )[0] );
        url = base_url+'/admin/firebase/add_firebase';
		console.log('formData => ');
		console.log(formData);
		console.log('add_firebase => '+url);
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
					setTimeout(function () {
						window.location = sports.config.base_url + "/admin/firebase";
					   }, 1000);
                } else
                {
                    console.log( data.error );
                    toastr['error']( data.error );
                }
            }
        } );
    } );
	/**
	 * ==========================================================
	 * Delete Firebase
	 * ================================================================
	 */
	 
	$( '.deletefirebase' ).on( 'click', function ( e ){
        e.preventDefault();
        console.log( $( this ).attr( 'id' ) );
        // alert( $( this ).attr( 'id' ) );
        var id = $( this ).attr( 'id' );
        url = base_url+'/admin/firebase/delete_firebase';
		console.log('url = '+url);
        jQuery.ajax( {
            url: url,
            type: 'POST',
            dataType: "json",
            data: { id: id },
            success: function ( data )
            {
                if ( data.success )
                {
                    toastr['success']( "team Deleted Successfully" );
                    if ( data.error )
                    {
                        console.log( data.error );
                        toastr['error']( data.error );
                    }
					setTimeout(function () {
						window.location = sports.config.base_url + "/admin/firebase";
					   }, 1500);
                    
                } else
                {
                    console.log( data.error );
                    toastr['error']( data.error );
                }
            }
        } );
    } );
	
	/**
	 * =========================================
	 * Edit firebase
	 * ============================================
	 */
	$( '.edit_firebase' ).on( 'click', function ( e ){
        e.preventDefault();
        console.log( $( this ).attr( 'id' ) );
        var id = $( this ).attr( 'id' );
        url = base_url+'/admin/firebase/get_firebase_details';
		 console.log( 'url = '+ url );
        jQuery.ajax( {
            url: url,
            type: 'POST',
            dataType: "json",
            data: { id: id },
            success: function ( data )
            {
                console.log( "edit show " );
				 if ( data != null )
                {
                    $( "#f_key1" ).val( data.f_key );
                    $( "#edit_data_id" ).val( data.id );
                    $( "#f_value1" ).val( data.f_value );
                }
                $( '.bd-edit-firebase-lg' ).modal( 'show' );				
                
            }
        } );
	} );
	/**
	 * =========================================
	 * Update firebase
	 * ============================================
	 */
	$( '#edit_firebase_form' ).on( 'submit', function ( e )
    {
        e.preventDefault();
        var formData = new FormData( $( '#edit_firebase_form' )[0] );
        console.log( "formdata => "+formData );
        url = base_url+'/admin/firebase/edit_firebase';
		console.log( 'url = '+ url );
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
                    toastr['success']( "Updated" );
                    if ( data.error )
                    {
                        console.log( data.error );
                        toastr['error']( data.error );
                    }
                    setTimeout(function () {
						window.location = sports.config.base_url + "/admin/firebase";
					   }, 1500);
                } else
                {
                    console.log( data.error );
                    toastr['error']( data.error );
                }
            }
        } );
    } );
	/**
	 * =========================================
	 * Edit firebase admin setting
	 * ============================================
	 */
	$( '.firebase_setting' ).on( 'click', function ( e ){
        e.preventDefault();
        url = base_url+'/admin/firebase/get_firebase_details1';
		 console.log( 'url = '+ url );
        jQuery.ajax( {
            url: url,
            type: 'POST',
            dataType: "json",
            data: {},
            success: function ( data )
            {
                if ( data != null )
                {
                    $( "#f_key2" ).val( data.f_key );
                    $( "#edit_data_id2" ).val( data.id );
                    $( "#f_value2" ).val( data.f_value );
                }
                $( '.bd-edit-firebasesetting-lg' ).modal( 'show' );				
                
            }
        } );
	} );
	/**
	 * =========================================
	 * Update firebase  admin setting
	 * ============================================
	 */
	$( '#edit_firebasesetting_form' ).on( 'submit', function ( e )
    {
        e.preventDefault();
        var formData = new FormData( $( '#edit_firebasesetting_form' )[0] );
        console.log( "formdata => "+formData );
        url = base_url+'/admin/firebase/edit_firebase1';
		console.log( 'url = '+ url );
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
                    toastr['success']( "Updated" );
                    if ( data.error )
                    {
                        console.log( data.error );
                        toastr['error']( data.error );
                    }
                    // setTimeout(function () {
						// window.location = sports.config.base_url + "/admin/firebase";
					   // }, 1500);
                } else
                {
                    console.log( data.error );
                    toastr['error']( data.error );
                }
            }
        } );
    } );
	
	
	
	
	
});