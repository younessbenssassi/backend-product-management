<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Modules\DashboardController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
