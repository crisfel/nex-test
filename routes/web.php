<?php

use App\Http\Controllers\Web\Client\EditController as EditClientController;
use App\Http\Controllers\Web\Client\ShowController as ShowClientController;
use Illuminate\Support\Facades\Route;

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register');

Route::view('/clients', 'clients.index')->name('clients.index');
Route::view('/clients/create', 'clients.form')->name('clients.create');
Route::get('/clients/{client}', ShowClientController::class)->name('clients.detail');
Route::get('/clients/{client}/edit', EditClientController::class)->name('clients.edit');

Route::get('/', function () {
    return redirect()->route('clients.index');
});
