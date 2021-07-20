<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->add('/api', 'ApiController::index');
$routes->add('/api/get-nationality', 'ApiController::get_nationality');
$routes->add('/api/get-postion', 'ApiController::get_postions');
$routes->add('/api/register', 'ApiController::register');
$routes->add('/api/user-login', 'ApiController::user_login');
$routes->add('/api/user-info', 'ApiController::user_info');
$routes->add('/api/update-user', 'ApiController::update_user');

/**
 * Club Apis
 */
$routes->add('/api/club', 'ClubsApiController::index');
$routes->add('/api/join-team', 'ClubsApiController::join_team');
$routes->add('/api/verify-team', 'ClubsApiController::verify_team');
$routes->add('/api/leave-team', 'ClubsApiController::leave_team');
$routes->add('/api/leave-team-multiple', 'ClubsApiController::leave_team_multiple');
/**
 * Player Apis
 */
$routes->add('/api/players-to-add', 'PlayerApiController::players_to_add');
$routes->add('/api/add-players-team', 'PlayerApiController::add_players_team');
/**
 * Tournament Api
 */
$routes->add('/api/match-lists', 'TournamentApiController::match_lists');
$routes->add('/api/schedule-match', 'TournamentApiController::schedule_match');

/**
 * Admin
 */

$routes->add('/admin/dashboard', 'admin/Dashboard::index');
$routes->add('/admin/clubs', 'admin/Clubs::index');
$routes->add('/admin/clubs/add_club', 'admin/Clubs::add_club');
$routes->add('/admin/clubs/delete_club', 'admin/Clubs::delete_club');
$routes->add('/admin/clubs/get_club_details', 'admin/Clubs::get_club_details');
$routes->add('/admin/clubs/edit_club', 'admin/Clubs::edit_club');
/**
 * Country
 */
$routes->add('/admin/locationscontroller/getallcountry', 'admin/LocationsController::getallcountry');
$routes->add('/admin/locationscontroller/getallstate', 'admin/LocationsController::getallstate');
$routes->add('/admin/locationscontroller/getallcity', 'admin/LocationsController::getallcity');

//players-to-add
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
