<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('/', [AppController::class, 'index']);
Route::post('/book', [AppController::class, 'book'])->name('book');
Route::post('/reset', [AppController::class, 'reset'])->name('reset');
Route::post('/random', [AppController::class, 'random'])->name('random');