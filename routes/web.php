<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', ['uses' => 'Controller@index']);
$router->post('api/login', ['uses' => 'UsersController@login']);

$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
    // users
    $router->post('/create-admin', ['uses' => 'UsersController@create_admin']);
    $router->post('/edit-user', ['uses' => 'UsersController@edit']);
    $router->post('/activate-user', ['uses' => 'UsersController@activate']);
    $router->post('/deactivate-user', ['uses' => 'UsersController@deactivate']);
    $router->post('/get-user', ['uses' => 'UsersController@get_user']);
    $router->get('/get-users', ['uses' => 'UsersController@get_users']);
    // facilities
    $router->post('/create-facility', ['uses' => 'FacilityController@create']);
    $router->post('/edit-facility', ['uses' => 'FacilityController@edit']);
    $router->post('/activate-facility', ['uses' => 'FacilityController@activate']);
    $router->post('/deactivate-facility', ['uses' => 'FacilityController@deactivate']);
    $router->post('/get-facility', ['uses' => 'FacilityController@get_facility']);
    $router->get('/get-facilities', ['uses' => 'FacilityController@get_facilities']);
    $router->post('/get-facility-user', ['uses' => 'FacilityController@get_facility_user']);
    // items
    $router->post('/create-item', ['uses' => 'ItemController@create']);
    $router->post('/edit-item', ['uses' => 'ItemController@edit']);
    $router->post('/get-items', ['uses' => 'ItemController@get_items']);
    $router->post('/search-item', ['uses' => 'ItemController@search']);
    // batch inventory
    $router->post('/add-starting-inventory', ['uses' => 'BatchInventoryController@add_starting_inventory']);
    $router->post('/request-inventory', ['uses' => 'BatchInventoryController@request_inventory']);
    $router->post('/view-requests', ['uses' => 'BatchInventoryController@view_requests']);
    $router->post('/transfer-inventory', ['uses' => 'BatchInventoryController@receive_inventory']);
    $router->post('/receive-inventory', ['uses' => 'BatchInventoryController@receive_inventory']);
    // test
    $router->post('/test', ['uses' => 'UsersController@test']);
});
