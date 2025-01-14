<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\CityController;

Route::get('/', function () {
    return view('home');
});

// Route pour le tableau de bord avec les données météo actuelles
Route::get('/dashboard', [WeatherController::class, 'currentWeather'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route pour les données météo actuelles (accessible à tous)
Route::get('/weather', [WeatherController::class, 'currentWeather'])->name('weather');

// Route pour les prévisions météo sur 7 jours
Route::get('/forecast', [WeatherController::class, 'forecastWeather'])
    ->name('forecast');

// Routes pour la gestion des villes
Route::middleware('auth')->group(function () {
    // Route pour enregistrer une ville
    Route::post('/cities', [CityController::class, 'store'])->name('cities.store');

    // Route pour afficher les villes enregistrées
    Route::get('/cities', [CityController::class, 'index'])->name('cities.index');

    // Route pour supprimer une ville
    Route::delete('/cities/{id}', [CityController::class, 'destroy'])->name('cities.destroy');

    // Route pour définir une ville comme favorite
    Route::post('/cities/{id}/favorite', [CityController::class, 'setFavorite'])->name('cities.setFavorite');
    
    // Routes de profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/toggle-email-notifications', [ProfileController::class, 'updateEmailNotifications'])->name('toggle.email.notifications');
});

require __DIR__ . '/auth.php';
