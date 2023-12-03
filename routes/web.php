<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ItemController;

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

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/clients', [ClientController::class, 'index'])->name('clients');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients/store', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/fetchAllClients', [ClientController::class, 'fetchAllClients'])->name('clients.fetchAll');
    Route::post('/clients/search', [ClientController::class, 'search'])->name('clients.search');
    Route::delete('/clients/bulk-delete/selected', [ClientController::class, 'bulkDelete'])->name('clients.bulkDelete');
    Route::put('/client/{id}', [ClientController::class, 'update'])->name('client.update');
    Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');
    Route::get('/client/{slug}/show', [ClientController::class, 'show'])->name('client.show');
    Route::get('/client/{slug}/edit', [ClientController::class, 'edit'])->name('client.edit');

    Route::get('/pets', [PetController::class, 'index'])->name('pets');

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');

    Route::get('/inventory', [ItemController::class, 'index'])->name('inventory');
});
