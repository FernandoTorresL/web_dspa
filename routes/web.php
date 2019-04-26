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

    //Create solicitud by admin members
    //Route::get('/ctas/solicitudesNC', 'SolicitudesController@homeNC');

    //Solicitudes
        //Create 'solicitudes'
        Route::get('/ctas/solicitudes', 'SolicitudesController@home');
        Route::post('/ctas/solicitudes/create', 'SolicitudesController@create');
        Route::post('/ctas/solicitudes/createNC', 'SolicitudesController@createNC');

        //Route to view one solicitud
        Route::get('/ctas/solicitudes/{solicitud}', 'SolicitudesController@show');

        //Route to edit solicitud
        Route::get('/ctas/solicitudes/edit/{solicitud}', 'SolicitudesController@show_for_edit');
        Route::post('/ctas/solicitudes/edit/{solicitud}', 'SolicitudesController@edit');

        //Route to edit solicitudes at 'Nivel Central'
        Route::get('/ctas/solicitudes/editNC/{solicitud}', 'SolicitudesController@show_for_edit');
        Route::post('/ctas/solicitudes/editNC/{solicitud}', 'SolicitudesController@editNC');

        //En desuso
        Route::get('/ctas/status/solicitudes', 'SolicitudesController@solicitudes_status');
        Route::get('/ctas/solicitudes/view/detail_status', 'SolicitudesDelController@view_detail_status');

        //Route to view table for solicitudes using pagination
        Route::get('/ctas/solicitudes/view/status', 'SolicitudesDelController@view_status');

        //Route to view timeline details for solicitudes
        Route::get('/ctas/solicitudes/timeline/{solicitud_id}', 'SolicitudesDelController@view_timeline');

        //Route to view tables and graphs on Reto DSPA
        Route::get('/reto_dspa', 'Reto_DSPA@home');


    Route::get('/ctas', 'CuentasController@home');
    Route::get('/ctas/inventario', 'InventarioController@home');

    //Routes to admin options
    Route::get('/ctas/admin/resumen', 'CuentasController@show_resume');
    Route::get('/ctas/admin/generatabla', 'CuentasController@show_admin_tabla');

    Route::get('/ctas/admin/show_create_file_valijas', 'CuentasAdminController@show_create_file_valijas');
    Route::post('/ctas/admin/create_file_valijas', 'CuentasAdminController@create_file_valijas');

    Route::get('/ctas/admin/preview_valijas/{file}', 'CuentasAdminController@preview_valijas');


    Route::get('/ctas/admin', 'CuentasAdminController@home');
}

);
