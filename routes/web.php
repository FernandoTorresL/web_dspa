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
Route::get('/home', 'PagesController@home');

Route::get('/messages/{message}', 'MessagesController@show');

Route::get('/verify-user/{code}', 'Auth\RegisterController@activateUser')->name('activate.user');

Auth::routes();

Route::group(['middleware' => 'auth', 'checkstatus'], function () {
    //    Route::get('/inicio', 'HomeController@home');

    Route::post('/messages/create', 'MessagesController@create');

    Route::get('/ctas/valijasNC', 'ValijasController@homeNC');
    Route::post('/ctas/valijas/createNC', 'ValijasController@createNC');
    Route::get('/ctas/valijas/{valija}', 'ValijasController@show');
    Route::get('/ctas/valijas/editNC/{valija}', 'ValijasController@show_for_edit');
    Route::post('/ctas/valijas/editNC/{valija}', 'ValijasController@editNC');
    Route::get('/ctas/solicitudes', 'SolicitudesController@home');
    Route::get('/ctas/solicitudesNC', 'SolicitudesController@homeNC');
    Route::post('/ctas/solicitudes/create', 'SolicitudesController@create');
    Route::post('/ctas/solicitudes/createNC', 'SolicitudesController@createNC');
    Route::get('/ctas/solicitudes/{solicitud}', 'SolicitudesController@show');

    Route::get('/ctas/solicitudes/edit/{solicitud}', 'SolicitudesController@show_for_edit');
    Route::post('/ctas/solicitudes/edit/{solicitud}', 'SolicitudesController@edit');

    Route::get('/ctas/solicitudes/editNC/{solicitud}', 'SolicitudesController@show_for_editNC');
    Route::post('/ctas/solicitudes/editNC/{solicitud}', 'SolicitudesController@editNC');

    Route::get('/ctas/status/solicitudes', 'SolicitudesController@solicitudes_status');

    //Route to view table for solicitudes using pagination
    Route::get('/ctas/solicitudes/view/status', 'SolicitudesDelController@view_status');
    Route::get('/ctas/solicitudes/view/detail_status', 'SolicitudesDelController@view_detail_status');

    Route::get('/ctas', 'CuentasController@home');
    Route::get('/ctas/inventario', 'InventarioController@home');

    Route::get('/ctas/admin/resumen', 'CuentasController@show_resume');
    Route::get('/ctas/admin/generatablas', 'CuentasController@show_admin_tabla');

}

);
