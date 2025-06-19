<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\RoleMasterController;
use App\Http\Controllers\CastMasterController;
use App\Http\Controllers\GenreMasterController;
use App\Http\Controllers\ContentMasterController;

// Public route for unauthenticated users
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authenticated and verified users routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Video Management (if still needed)
    Route::get('/upload', [VideoController::class, 'create'])->name('videos.create');
    // Route::post(uri: '/upload', [VideoController::class, 'store'])->name('videos.store');

    // Admin Resource Routes
    Route::resource('roles', RoleMasterController::class);
    Route::resource('casts', CastMasterController::class);
    Route::resource('genres', GenreMasterController::class);
    Route::resource('contents', ContentMasterController::class);


    Route::post('/contents/form', [ContentMasterController::class, 'store'])->name('contents.store');
    Route::delete('/contents/{id}', [ContentMasterController::class, 'deleteUsingDB'])->name('contents.delete.db');

});

// Redirect authenticated users to dashboard
Route::middleware('auth')->get('/', function () {
    return redirect()->route('dashboard');
});

require __DIR__.'/auth.php';