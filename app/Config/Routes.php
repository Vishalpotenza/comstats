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
 * Season 
 */
$routes->add('/api/user-season-detail', 'ApiController::user_season_info');
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
$routes->add('/api/get-players-detail', 'PlayerApiController::get_players_detail');
$routes->add('/api/get-coach-detail', 'PlayerApiController::get_coach_detail');
/**
 * Tournament Api
 */
$routes->add('/api/match-lists', 'TournamentApiController::match_lists');
$routes->add('/api/schedule-match', 'TournamentApiController::schedule_match');
$routes->add('/api/add-player-to-match', 'TournamentApiController::add_player_to_match');
$routes->add('/api/get-match-players', 'TournamentApiController::get_match_players');
$routes->add('/api/update-kit-color-formation', 'TournamentApiController::update_kit_color_formation');
$routes->add('/api/update-score', 'TournamentApiController::update_score');
$routes->add('/api/finish-match', 'TournamentApiController::finish_match');
/**
 * Traning api
 */
$routes->add('/api/player-attendance', 'TraningApiController::players_attendance');
/**
 * Admin
 */

$routes->add('/admin/dashboard', 'admin/Dashboard::index');
$routes->add('/admin/clubs', 'admin/Clubs::index');
$routes->add('/admin/clubs/add_club', 'admin/Clubs::add_club');
$routes->add('/admin/clubs/delete_club', 'admin/Clubs::delete_club');
$routes->add('/admin/clubs/get_club_details', 'admin/Clubs::get_club_details');
$routes->add('/admin/clubs/edit_club', 'admin/Clubs::edit_club');
$routes->add('/admin/clubs/join-request', 'admin/Clubs::join_request');
$routes->add('/admin/clubs/view_members', 'admin/Clubs::view_members');

$routes->add('/admin/league', 'admin/League::index');
$routes->add('/admin/league/add_league', 'admin/League::add_league');
$routes->add('/admin/league/delete_league', 'admin/League::delete_league');
$routes->add('/admin/league/get_league_details', 'admin/League::get_league_details');
$routes->add('/admin/league/edit_league', 'admin/League::edit_league');

$routes->add('/admin/team', 'admin/Team::index');
$routes->add('/admin/team/add_team', 'admin/Team::add_team');
$routes->add('/admin/team/delete_team', 'admin/Team::delete_team');
$routes->add('/admin/team/get_team_details', 'admin/Team::get_team_details');
$routes->add('/admin/team/edit_team', 'admin/Team::edit_team');
/**
 * Country
 */
$routes->add('/admin/locationscontroller/getallcountry', 'admin/LocationsController::getallcountry');
$routes->add('/admin/locationscontroller/getallstate', 'admin/LocationsController::getallstate');
$routes->add('/admin/locationscontroller/getallcity', 'admin/LocationsController::getallcity');
/*
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
