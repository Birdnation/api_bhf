<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//ruta login con google por defecto /oauth/token

/**
 * Rutas en el dominio api/v1
 */
Route::group([
   'prefix' => 'v1'
], function () {
    //ruta para login sin google (api/v1/login)
    Route::post('login',[\App\Http\Controllers\AuthController::class, 'login']);
    //nota no existe en el sistema un registro de usuario sin google (solo el adm)

    /**
     * Rutas con autorizacion (se requiere token)
     */
    Route::group([
        'middleware' => 'auth:api'
    ], function () {

        //ruta para logout (api/v1/logout)
       Route::get('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
       Route::get('user', [\App\Http\Controllers\AuthController::class, 'user']);

        /**
         * Rutas con autorizacion para solicitudes
         */
       Route::group([
          'prefix' => 'solicitud'
       ], function (){
           //ruta para creacion de solicitud (api/v1/solicitud/create)
           Route::post('create', [\App\Http\Controllers\SolicitudController::class, 'create']);
       });
    });
});


//rutas api/v1 group (
    //ruta login /oauth/token
    //ruta para ver usuario logeado (GET) ->rol, token /user
    //ruta creacion de solicitud funcionario (POST) /solicitud-funcionario
    //ruta creacion de solicitud DPE (POST) /solicitud-dpe
//)
