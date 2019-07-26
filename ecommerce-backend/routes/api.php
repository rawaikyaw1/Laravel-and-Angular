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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'admin'], function () {

  Route::post('/login', 'Admin\AdminController@login');
  

  Route::post('/register', 'Admin\AdminController@register');

  Route::group(['middleware'=>'adminApi'],function ($value='')
  { 
  	// Middleware adminApi always merge login_id and login_user to every request.
  	Route::post('/logout', 'Admin\AdminController@logout');

  	Route::resource('sectors', 'SectorController');
  	
  	Route::resource('sgds', 'SgdController');

    Route::resource('social-enterprises', 'SocialEnterpriseController');

    Route::resource('categories', 'CategoryController');
    
    Route::resource('products', 'ProductController');

    Route::resource('new-categories', 'NewsCategoryController');

    Route::resource('news-posts', 'NewsPostController');

  });

});
