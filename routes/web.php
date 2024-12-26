<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\CropController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\HarvestController;
use App\Http\Controllers\ProfileController;

// Page d'accueil
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

// Routes accessibles uniquement après authentification
Route::middleware(['auth'])->group(function () {
    // Dashboard accessible à tous les utilisateurs authentifiés
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Routes du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes pour les admins
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
});

// Routes pour les farmers
Route::middleware(['auth', 'role:farmer'])->group(function () {
    Route::get('/farmer', [FarmerController::class, 'index'])->name('farmer.dashboard');
    Route::get('/crops', [CropController::class, 'index'])->name('crops.index');
    Route::post('/crops', [CropController::class, 'store'])->name('crops.store');
    Route::put('/crops/{crop}', [CropController::class, 'update'])->name('crops.update');
    Route::delete('/crops/{crop}', [CropController::class, 'destroy'])->name('crops.destroy');
    Route::get('/crops/{crop}/harvests', [HarvestController::class, 'index'])->name('harvests.index');
    Route::post('/crops/{crop}/harvests', [HarvestController::class, 'store'])->name('harvests.store');
    Route::put('/crops/{crop}/harvests/{harvest}', [HarvestController::class, 'update'])->name('harvests.update');
    Route::delete('/crops/{crop}/harvests/{harvest}', [HarvestController::class, 'destroy'])->name('harvests.destroy');
});

// Auth routes
require __DIR__ . '/auth.php';
