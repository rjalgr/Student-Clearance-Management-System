<?php

use CodeIgniter\Router\RouteCollection;

// Public routes
$routes->get('/',         'Auth\AuthController::login');
$routes->get('login',     'Auth\AuthController::login');
$routes->post('login',    'Auth\AuthController::loginPost');
$routes->get('logout',    'Auth\AuthController::logout');
$routes->get('register',  'Auth\AuthController::register');
$routes->post('register', 'Auth\AuthController::registerPost');

// Student routes (role: student)
$routes->group('student', ['filter' => 'auth:student'], function($routes) {
    $routes->get('dashboard',                'Student\DashboardController::index');
    $routes->get('clearance',                'Student\ClearanceController::index');
    $routes->post('clearance/submit',        'Student\ClearanceController::submit');
    $routes->get('clearance/track',          'Student\ClearanceController::track');
    $routes->get('clearance/track/(:num)',   'Student\ClearanceController::track/$1');
    $routes->get('clearance/download/(:num)','Student\ClearanceController::download/$1');
    $routes->get('profile',                  'Student\ProfileController::index');
    $routes->post('profile/update',          'Student\ProfileController::update');
    $routes->get('notifications',            'Student\NotificationController::index');
});

    // Staff routes (role: staff)
    $routes->group('staff', ['filter' => 'auth:staff'], function($routes) {
    $routes->get('dashboard',                'Staff\DashboardController::index');
    $routes->get('clearance',                'Staff\ClearanceController::index');
    $routes->post('clearance/review/(:num)', 'Staff\ClearanceController::review/$1');
});

    // Admin routes (role: admin)
    $routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');
    $routes->get('reports/export', 'Admin\ReportController::export'); 
    
    // Users CRUD
    $routes->post('users/store',                    'Admin\UserController::store');
    $routes->post('users/update/(:num)',             'Admin\UserController::update/$1');
    $routes->post('users/delete/(:num)',             'Admin\UserController::delete/$1');

    // Departments CRUD
    $routes->post('departments/store',               'Admin\DepartmentController::store');
    $routes->post('departments/update/(:num)',        'Admin\DepartmentController::update/$1');
    $routes->post('departments/delete/(:num)',        'Admin\DepartmentController::delete/$1');

    // Clearance CRUD
    $routes->get('clearance/(:num)',                 'Admin\ClearanceController::view/$1');
    $routes->post('clearance/update/(:num)',          'Admin\ClearanceController::update/$1');
    $routes->post('clearance/delete/(:num)',          'Admin\ClearanceController::delete/$1');

    // Reports
    $routes->get('reports/export',                   'Admin\ReportController::export');
});

    // REST API (internal)

    $routes->group('api/v1', ['filter' => 'apiauth'], function($routes) {
    $routes->get('clearance/(:num)',    'Api\ClearanceApiController::show/$1');
    $routes->get('stats',               'Api\ClearanceApiController::stats');
    $routes->get('progress',            'Api\ClearanceApiController::progress');
});

