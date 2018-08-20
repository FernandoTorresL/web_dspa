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

Route::post('/messages/create', 'MessagesController@create')->middleware('auth');

Auth::routes();

Route::get('/inicio', 'HomeController@home')->middleware('auth');

Route::get('/ctas', 'CuentasController@home')->middleware('auth');

Route::get('/ctas/inventario', 'InventarioController@home')->middleware('auth');

Route::get('/ctas/solicitudes', 'SolicitudesController@home')->middleware('auth');

Route::post('/ctas/solicitudes/create', 'SolicitudesController@create')->middleware('auth');
