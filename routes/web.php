<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'api' => 'La Buena Mesa - Menu API',
        'docs' => 'Ver README.md para la documentación completa de endpoints.',
    ]);
});
