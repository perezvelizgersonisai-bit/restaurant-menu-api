<?php

use App\Http\Controllers\Api\MenuItemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — La Buena Mesa
|--------------------------------------------------------------------------
| Todas las rutas quedan expuestas bajo el prefijo /api gracias a la
| configuración por defecto de bootstrap/app.php (withRouting: api:).
*/

Route::prefix('menu-items')->group(function () {
    // La ruta de categoría se declara ANTES de {id} para que "category"
    // no sea interpretado como un identificador numérico.
    Route::get('/category/{category}', [MenuItemController::class, 'byCategory']);

    Route::get('/', [MenuItemController::class, 'index']);
    Route::post('/', [MenuItemController::class, 'store']);
    Route::get('/{id}', [MenuItemController::class, 'show'])->whereNumber('id');
    Route::put('/{id}', [MenuItemController::class, 'update'])->whereNumber('id');
    Route::patch('/{id}', [MenuItemController::class, 'update'])->whereNumber('id');
    Route::delete('/{id}', [MenuItemController::class, 'destroy'])->whereNumber('id');
});
