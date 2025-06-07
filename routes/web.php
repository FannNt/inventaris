<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

Route::view('/', 'dashboard')->name('dashboard');

Route::view('items', 'items' )->name('items');
Route::get('items/{item}', [ItemController::class, 'show'])->name('items.show');

Route::middleware('auth')->group(function (){
    Route::view('profile', 'profile')
        ->name('profile');
});



require __DIR__.'/auth.php';
