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
    Route::get('/clients/{slug}/show', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/clients/{slug}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{id}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');
    
    Route::get('/pets', [PetController::class, 'index'])->name('pets');
    Route::get('/pets/create', [PetController::class, 'create'])->name('pets.create');
    Route::post('/pets/store', [PetController::class, 'store'])->name('pets.store');
    Route::get('/pets/fetchAllPets', [PetController::class, 'fetchAllPets'])->name('pets.fetchAll');
    Route::post('/pets/search', [PetController::class, 'search'])->name('pets.search');
    Route::delete('/pets/bulk-delete/selected', [PetController::class, 'bulkDelete'])->name('pets.bulkDelete');
    Route::get('/pets/fetchAllClients', [PetController::class, 'fetchAllClients'])->name('pets.fetchAllClients');
    Route::get('/pets/fetchAllSpecies', [PetController::class, 'fetchAllSpecies'])->name('pets.fetchAllSpecies');
    Route::get('/pets/fetchAllBreeds', [PetController::class, 'fetchAllBreeds'])->name('pets.fetchAllBreeds');
    Route::get('/pets/users', [PetController::class, 'searchClients'])->name('pets.searchClients');
    Route::get('/pets/species', [PetController::class, 'searchSpecies'])->name('pets.searchSpecies');
    Route::get('/pets/breeds', [PetController::class, 'searchBreeds'])->name('pets.searchBreeds');
    Route::get('/pets/{slug}/show', [PetController::class, 'show'])->name('pets.show');
    Route::get('/pets/{slug}/edit', [PetController::class, 'edit'])->name('pets.edit');
    Route::post('/pets/{id}', [PetController::class, 'update'])->name('pets.update');
    Route::delete('/pets/{id}', [PetController::class, 'destroy'])->name('pets.destroy');

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');

    Route::get('/inventory', [ItemController::class, 'index'])->name('inventory');
});
