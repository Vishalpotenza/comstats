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
