<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PenjualanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
//Categories
Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
Route::get('/categories/create', [CategoriesController::class, 'create'])->name('categories.create');
Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
Route::get('/categories/{category}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{category}', [CategoriesController::class, 'update'])->name('categories.update');
Route::delete('/categories/{category}', [CategoriesController::class, 'destroy'])->name('categories.destroy');

//Obats
Route::get('/obats', [ObatController::class, 'index'])->name('obats.index');
Route::get('/obats/create', [ObatController::class, 'create'])->name('obats.create');
Route::post('/obats', [ObatController::class, 'store'])->name('obats.store');
Route::get('/obats/{obat}', [ObatController::class, 'show'])->name('obats.show');
Route::get('/obats/{obat}/edit', [ObatController::class, 'edit'])->name('obats.edit');
Route::put('/obats/{obat}', [ObatController::class, 'update'])->name('obats.update');
Route::delete('/obats/{obat}', [ObatController::class, 'destroy'])->name('obats.destroy');

//Kasir
Route::get('/kasir', [PenjualanController::class, 'index'])->name('kasir.index');
Route::get('/kasir/search', [PenjualanController::class, 'search'])->name('kasir.search');
Route::post('/kasir/store', [PenjualanController::class, 'store'])->name('kasir.store');


require __DIR__.'/auth.php';
