<?php

use Illuminate\Support\Facades\Route;

Route::any('{path?}', fn () => response()->json([
    'message' => 'Not Found',
], 404))->where('path', '.*');
