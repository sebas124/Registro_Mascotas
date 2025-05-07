<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pets\PetsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\People\PeopleController;

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

Route::group(['middleware' => 'cors'], function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register-user', [LoginController::class, 'register']);
});


Route::group(['middleware' => ['strict', 'authentication']], function () {
    Route::apiResource('/people', PeopleController::class);
    Route::apiResource('/pets', PetsController::class);
    Route::get('/people-with-pets', [PeopleController::class, 'getDataPeopleWithPets']);
});


