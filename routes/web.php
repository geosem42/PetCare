<?php

use App\Http\Controllers\SurgicalHistoryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\VaccinationController;
use App\Http\Controllers\MedicalHistoryController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\GalleryController;

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

    // Vaccinations
    Route::post('/pets/{pet}/vaccinations', [VaccinationController::class, 'storeVaccination'])->name('pets.vaccinations.store');
    Route::delete('/pets/{pet}/vaccinations/{vaccination}', [VaccinationController::class, 'destroyVaccination'])->name('pets.vaccinations.delete');
    Route::get('/pets/{pet}/vaccinations', [VaccinationController::class, 'fetchVaccinations'])->name('pets.vaccinations.fetch');

    // Medical History
    Route::get('/pets/{pet}/histories', [MedicalHistoryController::class, 'fetchHistories'])->name('pets.histories.fetch');
    Route::post('/pets/{pet}/histories', [MedicalHistoryController::class, 'storeHistory'])->name('pets.histories.store');
    Route::delete('/pets/{pet}/histories/{history}', [MedicalHistoryController::class, 'destroyHistory'])->name('pets.histories.delete');

    // Medications
    Route::get('/pets/{pet}/medications', [MedicationController::class, 'fetchMedications'])->name('pets.medications.fetch');
    Route::post('/pets/{pet}/medications', [MedicationController::class, 'storeMedication'])->name('pets.medications.store');
    Route::delete('/pets/{pet}/medications/{medication}', [MedicationController::class, 'destroyMedication'])->name('pets.medications.delete');

    // Surgical History
    Route::get('/pets/{pet}/surgeries', [SurgicalHistoryController::class, 'fetchSurgeries'])->name('pets.surgeries.fetch');
    Route::post('/pets/{pet}/surgeries', [SurgicalHistoryController::class, 'storeSurgery'])->name('pets.surgeries.store');
    Route::delete('/pets/{pet}/surgeries/{surgery}', [SurgicalHistoryController::class, 'destroySurgery'])->name('pets.surgeries.delete');

    // Gallery
    Route::get('/pets/{pet}/gallery', [GalleryController::class, 'fetchAllImages'])->name('pets.gallery.fetch');
    Route::post('/pets/{pet}/gallery', [GalleryController::class, 'storeGallery'])->name('pets.gallery.store');
    Route::delete('/pets/{pet}/gallery/{image}', [GalleryController::class, 'destroy'])->name('pets.gallery.delete');

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');
    Route::post('/appointments/create', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/fetchAllAppointments', [AppointmentController::class, 'fetchAllAppointments'])->name('appointments.fetchAll');
    Route::get('/appointments/fetchAllClients', [AppointmentController::class, 'fetchAllClients'])->name('appointments.fetchAllClients');
    Route::get('/appointments/{id}', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    Route::get('/inventory', [ItemController::class, 'index'])->name('inventory');
});
