<?php

use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GithubConnectionController;
use App\Http\Controllers\PreferencesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RepositoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/repositories', [RepositoryController::class, 'index'])->name('repositories.index');
    Route::post('/repositories/sync', [RepositoryController::class, 'sync'])->name('repositories.sync');
    Route::get('/repositories/{repository}', [RepositoryController::class, 'show'])->name('repositories.show');
    Route::post('/repositories/{repository}/analyze', [AnalysisController::class, 'store'])->name('repositories.analyze');
    Route::get('/analyses', [AnalysisController::class, 'index'])->name('analyses.index');
    Route::get('/analyses/{analysis}', [AnalysisController::class, 'show'])->name('analyses.show');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/github/connect', [GithubConnectionController::class, 'redirect'])->name('github.connect');
    Route::get('/github/callback', [GithubConnectionController::class, 'callback'])->name('github.callback');
    Route::delete('/github/disconnect', [GithubConnectionController::class, 'destroy'])->name('github.disconnect');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::patch('/profile/preferences/ai', [PreferencesController::class, 'updateAi'])->name('preferences.ai.update');
    Route::patch('/profile/preferences/theme', [PreferencesController::class, 'updateTheme'])->name('preferences.theme.update');
    Route::patch('/profile/preferences/notifications', [PreferencesController::class, 'updateNotifications'])->name('preferences.notifications.update');
});

require __DIR__.'/auth.php';
