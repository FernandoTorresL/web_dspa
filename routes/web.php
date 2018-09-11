<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PagesController@home');

Route::get('/messages/{message}', 'MessagesController@show');

Route::get('/verify-user/{code}', 'Auth\RegisterController@activateUser')->name('activate.user');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    //    Route::get('/inicio', 'HomeController@home');

    Route::post('/messages/create', 'MessagesController@create');

    Route::get('/ctas/solicitudes', 'SolicitudesController@home');
    Route::get('/ctas/solicitudesNC', 'SolicitudesController@homeNC');
    Route::post('/ctas/solicitudes/create', 'SolicitudesController@create');
    Route::post('/ctas/solicitudes/createNC', 'SolicitudesController@createNC');
    Route::get('/ctas/solicitudes/{solicitud}', 'SolicitudesController@show');

    Route::get('/ctas', 'CuentasController@home');
    Route::get('/ctas/inventario', 'InventarioController@home');

});
