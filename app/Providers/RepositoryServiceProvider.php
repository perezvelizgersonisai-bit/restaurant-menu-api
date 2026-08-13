<?php

namespace App\Providers;

use App\Repositories\Contracts\MenuItemRepositoryInterface;
use App\Repositories\Eloquent\MenuItemRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Vincula cada contrato de repositorio con su implementación concreta.
 *
 * Gracias a este binding, cualquier clase puede tipar-hintear
 * MenuItemRepositoryInterface en su constructor (inyección de
 * dependencias) sin conocer ni acoplarse a MenuItemRepository ni a
 * Eloquent. Esto facilita, por ejemplo, sustituir la implementación
 * por un mock en las pruebas automatizadas.
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            MenuItemRepositoryInterface::class,
            MenuItemRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
