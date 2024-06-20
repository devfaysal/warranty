<?php

use App\Livewire\RegisterProduct;
use Illuminate\Support\Facades\Route;

Route::get('/', RegisterProduct::class);
Route::redirect('/filament', '/admin/login')->name('login');
