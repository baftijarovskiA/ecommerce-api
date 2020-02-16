<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

/*
|--------------------------------------------------------------------------
| PUBLIC API Routes
|--------------------------------------------------------------------------
 */

Route::post('register', 'AccountController@register');
Route::post('login', 'AccountController@authenticate');

/*
|--------------------------------------------------------------------------
| Authenticated User API Routes
|--------------------------------------------------------------------------
 */

Route::group(['middleware' => ['jwt.verify:false']], function() {

    Route::get('user', 'AccountController@getAuthenticatedUser'); // get authenticated user details
    Route::post('logout', 'AccountController@logout'); // logout current user

});

/*
|--------------------------------------------------------------------------
| ADMIN User API Routes
|--------------------------------------------------------------------------
 */

Route::group(['middleware' => ['jwt.verify:true,admin,full-access']], function() {

    Route::get('role','GenericRolePermController@allRoles'); // get all roles
    Route::post('role', 'GenericRolePermController@createRole'); // create a new role
    Route::get('role/{id}','GenericRolePermController@findRole'); // find role by id
    Route::post('role/{id}','GenericRolePermController@editRole'); // edit role by id
    Route::post('assign-role', 'GenericRolePermController@assignRole'); // assign role to user
    Route::get('permission','GenericRolePermController@allPermissions'); // get all permissions
    Route::post('permission', 'GenericRolePermController@createPermission'); // create a new permission
    Route::get('permission/{id}','GenericRolePermController@findPermission'); // find permission by id
    Route::post('permission/{id}','GenericRolePermController@editPermission'); // edit permission by id
    Route::post('attach-permission', 'GenericRolePermController@attachPermission'); // attach permission to role
    Route::get('role-details', 'GenericRolePermController@roleDetails'); // get roles and its permissions

});

/*
|--------------------------------------------------------------------------
| MANAGER User API Routes
|--------------------------------------------------------------------------
 */

Route::group(['middleware' => ['jwt.verify:true,manager,half-access']], function() {



});

/*
|--------------------------------------------------------------------------
| EMPLOYEE User API Routes
|--------------------------------------------------------------------------
 */

Route::group(['middleware' => ['jwt.verify:true,employee,employee-access']], function() {



});

/*
|--------------------------------------------------------------------------
| USER User API Routes
|--------------------------------------------------------------------------
 */

Route::group(['middleware' => ['jwt.verify:true,user,user-access']], function() {



});

/*
|--------------------------------------------------------------------------
 */
