<?php

use Illuminate\Support\Facades\Route;

// Giao diện do project React riêng đảm nhận; backend chỉ phục vụ API tại /api
Route::get('/', fn () => response()->json([
    'app' => config('app.name'),
    'api' => url('/api'),
]));
