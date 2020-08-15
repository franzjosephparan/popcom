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
    $router->post('/get-users', ['uses' => 'UsersController@get_users']);
    // facilities
    $router->post('/create-facility', ['uses' => 'FacilityController@create']);
    $router->post('/edit-facility', ['uses' => 'FacilityController@edit']);
    $router->post('/activate-facility', ['uses' => 'FacilityController@activate']);
    $router->post('/deactivate-facility', ['uses' => 'FacilityController@deactivate']);
    $router->post('/get-facility', ['uses' => 'FacilityController@get_facility']);
    $router->get('/get-facilities', ['uses' => 'FacilityController@get_facilities']);
    $router->post('/get-facility-users', ['uses' => 'FacilityController@get_facility_users']);
    $router->post('/add-facility-user', ['uses' => 'FacilityController@add_facility_user']);
    // items
    $router->post('/create-item', ['uses' => 'ItemController@create']);
    $router->post('/edit-item', ['uses' => 'ItemController@edit']);
    $router->post('/get-items', ['uses' => 'ItemController@get_items']);
    $router->post('/search-item', ['uses' => 'ItemController@search']);
    // batch inventory
    $router->post('/add-starting-inventory', ['uses' => 'BatchInventoryController@add_starting_inventory']);
    $router->post('/adjust-inventory', ['uses' => 'BatchInventoryController@adjust_inventory']);
    $router->post('/get-inventory', ['uses' => 'BatchInventoryController@get_inventory']);
    $router->post('/get-batch', ['uses' => 'BatchInventoryController@get_batch']);
    $router->post('/get-facility-batches', ['uses' => 'BatchInventoryController@get_facility_batches']);
    $router->post('/get-item-batches', ['uses' => 'BatchInventoryController@get_item_batches']);
    // supply chain
    $router->post('/dispense-inventory', ['uses' => 'BatchInventoryController@dispense_inventory']);
    $router->post('/request-inventory', ['uses' => 'BatchInventoryController@request_inventory']);
    $router->post('/edit-request', ['uses' => 'BatchInventoryController@edit_request']);
    $router->post('/cancel-request', ['uses' => 'BatchInventoryController@cancel_request']);
    $router->post('/decline-request', ['uses' => 'BatchInventoryController@decline_request']);
    $router->post('/view-requests', ['uses' => 'BatchInventoryController@view_requests']);
    $router->post('/transfer-inventory', ['uses' => 'BatchInventoryController@transfer_inventory']);
    $router->post('/get-transfers', ['uses' => 'BatchInventoryController@get_transfers']);
    $router->post('/update-transfer-status', ['uses' => 'BatchInventoryController@update_transfer_status']);
    $router->post('/receive-inventory', ['uses' => 'BatchInventoryController@receive_inventory']);
    $router->post('/get-to-receive-inventory', ['uses' => 'BatchInventoryController@get_to_receive_inventory']);
    // ledger
    $router->post('/get-facility-ledger', ['uses' => 'LedgerController@get_facility_ledger']);
    $router->post('/get-batch-ledger', ['uses' => 'LedgerController@get_batch_ledger']);
    $router->post('/get-total-dispense-count', ['uses' => 'LedgerController@get_total_dispense_count']);
    // report
    $router->post('/generate-report', ['uses' => 'ReportController@generate_report']);
});
