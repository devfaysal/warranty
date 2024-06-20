<?php

use App\Http\Controllers\Api\ProductSubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/product-submission', [ProductSubmissionController::class, 'store']);
