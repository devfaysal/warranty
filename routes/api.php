<?php

use App\Http\Controllers\Api\ProductSubmissionController;
use Illuminate\Support\Facades\Route;

Route::post('/product-submission', [ProductSubmissionController::class, 'store']);
