<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [UserController::class, 'updatePassword'])->name('password.update');

    // Ticket Routes
    Route::prefix('tickets')->group(function () {
        Route::get('/create', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/store', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('/search', [TicketController::class, 'search'])->name('tickets.search');
        Route::patch('/{id}/update-status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
    });
});
Route::get('/tickets/{id}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
Route::post('/tickets/{id}/update', [TicketController::class, 'update'])->name('tickets.update');

Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');

// Route for problem-only editing
Route::put('/tickets/{ticket}/update_problem', [TicketController::class, 'updateProblem'])->name('tickets.update_problem');

//Route::post('/tickets/update-problem/{id}', [TicketController::class, 'updateProblem']);

// Admin-Only Routes
Route::middleware('admin')->group(function () {
    Route::get('/register', [UserController::class, 'registerPage']);
    Route::post('/register', [UserController::class, 'register']);
    Route::get('/users', [UserController::class, 'index']);
});

// Default Auth Routes
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/dashboard/filter', [DashboardController::class, 'filter'])->name('dashboard.filter');

