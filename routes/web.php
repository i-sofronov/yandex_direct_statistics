<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\YandexAuthController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', [ProjectController::class, 'index'])->name('home');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::post('/projects', [ProjectController::class, 'store'])->name('projects.create');
Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');

Route::get('/projects/{project}/accounts/connect', [ProjectController::class, 'connect'])->name('projects.accounts_connect');

Route::get('/yandex/callback', [YandexAuthController::class, 'callback'])->name('yandex.auth.callback');
Route::delete('/accounts/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');

require __DIR__.'/settings.php';
