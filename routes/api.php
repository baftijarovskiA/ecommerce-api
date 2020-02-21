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

Route::post('register', 'AccountController@register'); // register
Route::post('login', 'AccountController@authenticate'); // login
Route::post('recover', 'AccountController@recover'); // send email for password reset

Route::get('posts','PostsController@index'); // get all posts
Route::get('posts/{slug}','PostsController@findPost'); // get post form slug
Route::post('address','AddressController@store'); // create address

Route::get('category','CategoryController@getAll'); //get all categories

Route::get('product','ProductController@get'); // get available products
Route::get('product/slug/{id}','ProductController@find'); // find product by slug
Route::get('product/category/{id}','ProductController@getByCategory'); // get products by category id


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

    Route::put('posts/{id}','PostsController@delete'); // delete a post
    Route::get('address','AddressController@index'); // get all addresses
    Route::post('category','CategoryController@create'); //create new category
    Route::put('category/edit/{id}','CategoryController@update'); //edit category by id
    Route::delete('category/delete/{id}','CategoryController@hide'); //delete category by id
    Route::get('category/{id}','CategoryController@find'); // find category by id
    Route::post('product','ProductController@store'); // create new product

});

/*
|--------------------------------------------------------------------------
| EMPLOYEE User API Routes
|--------------------------------------------------------------------------
 */

Route::group(['middleware' => ['jwt.verify:true,employee,employee-access']], function() {

    Route::post('posts','PostsController@store'); // create a post
    Route::put('posts/edit/{id}','PostsController@update'); // edit a post
    Route::get('posts/id/{id}','PostsController@getPost'); // get a post by id
    Route::get('product/all','ProductController@getAll'); // get all products
    Route::put('product/edit/{id}','ProductController@update'); // edit product by id
    Route::get('product/id/{id}','ProductController@findById'); // find product by id

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
