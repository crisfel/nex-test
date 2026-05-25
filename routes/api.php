<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Client\GetAllController as GetAllClientsController;
use App\Http\Controllers\Client\GetByIdController as GetClientByIdController;
use App\Http\Controllers\Client\StoreController as StoreClientController;
use App\Http\Controllers\Client\UpdateController as UpdateClientController;
use App\Http\Controllers\Client\DeleteController as DeleteClientController;
use App\Http\Controllers\Contact\GetByClientController as GetContactsByClientController;
use App\Http\Controllers\Contact\StoreController as StoreContactController;
use App\Http\Controllers\Contact\UpdateController as UpdateContactController;
use App\Http\Controllers\Contact\DeleteController as DeleteContactController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', LogoutController::class);
    Route::get('/user', UserController::class);

    Route::get('/clients', GetAllClientsController::class);
    Route::get('/clients/{id}', GetClientByIdController::class);
    Route::post('/clients', StoreClientController::class);
    Route::put('/clients', UpdateClientController::class);
    Route::delete('/clients/{id}', DeleteClientController::class);

    Route::get('/clients/{clientId}/contacts', GetContactsByClientController::class);
    Route::post('/clients/{clientId}/contacts', StoreContactController::class);
    Route::put('/contacts', UpdateContactController::class);
    Route::delete('/contacts/{id}', DeleteContactController::class);
});
