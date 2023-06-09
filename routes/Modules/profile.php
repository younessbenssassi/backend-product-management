<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Modules\ProfileController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('/update-profile', [ProfileController::class, 'update'])->name('profile.update');
});
