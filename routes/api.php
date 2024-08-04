<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    //Ruta para crear rutas
    Route::post('createRoute', [RouteController::class, 'create'])->name('createRoute');

    //Ruta para obtener las rutas creadas por el usuario logeado
    Route::get('getCreatedRoutes', [RouteController::class, 'index2'])->name('getCreatedRoutes');

    //Ruta para inscribirse a una ruta
    Route::post('inscribirseRoute', [RouteController::class, 'inscribirse'])->name('inscribirseRoute');

    //Ruta para dar like a una ruta
    Route::post('darLike', [RouteController::class, 'darLike'])->name('darLike');

    //Ruta para quitar like a una ruta
    Route::post('quitarLike', [RouteController::class, 'quitarLike'])->name('quitarLike');

    //Ruta para obtener el usuario logeado
    Route::get('getUser', [HomeController::class, 'getUser'])->name('getUser');

    //Ruta para enviar el correo de inscripcion
    Route::post('confirmar', [RouteController::class, 'confirmar'])->name('confirmar');

    //Ruta para obtener las rutas a las que esta inscrito el usuario
    Route::get('joinedRoutes', [RouteController::class, 'joinedRoutes'])->name('joinedRoutes');

    //Ruta para cambiar la imagen de perfil del usuario
    Route::PUT('/update.profile', [UserController::class, 'updateImage'])->name('update.profile');

    //Ruta para comprobar si el usuario es participante
    Route::get('esParticipante/{ruta_id}', [RouteController::class, 'esParticipante'])->name('esParticipante');

    //Ruta para comprobar si la ruta tiene like
    Route::get('tieneLike/{ruta_id}', [RouteController::class, 'tieneLike'])->name('tieneLike');

    //Ruta para comprobar borrar una ruta
    Route::delete('deleteRoute/{ruta_id}', [RouteController::class, 'deleteRoute'])->name('deleteRoute');

    //Ruta para comprobar borrar una ruta
    Route::PUT('editRoute', [RouteController::class, 'editRoute'])->name('editRoute');

    //Ruta para obtener todas las rutas que le ha dado like el usuario
    Route::get('likedRoutes', [RouteController::class, 'likedRoutes'])->name('likedRoutes');
});

//Rutas de login y registro
Route::post('register', [AuthController::class, 'createUser'])->name('register');
Route::post('login', [AuthController::class, 'loginUser'])->name('login');

//Ruta para obtener todas las rutas creadas
Route::get('getRoutes', [RouteController::class, 'index'])->name('getRoutes');

//Ruta para obtener el numero de participantes de una ruta
Route::get('numberParticipant/{ruta_id}', [RouteController::class, 'numberParticipant'])->name('numberParticipant');

//Ruta para obtener los detalles de la ruta seleccionada
Route::get('getRoute/{ruta_id}', [RouteController::class, 'getRoute'])->name('getRoute');

//Ruta para comprobar borrar una ruta
Route::get('getUser/{user_id}', [AuthController::class, 'getUser'])->name('getUser');
