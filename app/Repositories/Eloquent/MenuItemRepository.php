<?php

namespace App\Repositories\Eloquent;

use App\Models\MenuItem;
use App\Repositories\Contracts\MenuItemRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Implementación concreta del repositorio de elementos del menú usando Eloquent ORM.
 *
 * Esta clase es la única parte de la aplicación que conoce el modelo
 * Eloquent MenuItem; el resto del sistema depende únicamente del
 * contrato MenuItemRepositoryInterface.
 */
class MenuItemRepository implements MenuItemRepositoryInterface
{
    public function __construct(
        protected MenuItem $model
    ) {}

    public function all(): Collection
    {
        return $this->model->newQuery()->orderBy('name')->get();
    }

    public function find(int $id): ?MenuItem
    {
        return $this->model->newQuery()->find($id);
    }

    public function create(array $data): MenuItem
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(int $id, array $data): ?MenuItem
    {
        $menuItem = $this->find($id);

        if (! $menuItem) {
            return null;
        }

        $menuItem->update($data);

        return $menuItem->fresh();
    }

    public function delete(int $id): bool
    {
        $menuItem = $this->find($id);

        if (! $menuItem) {
            return false;
        }

        return (bool) $menuItem->delete();
    }

    public function findByCategory(string $category): Collection
    {
        return $this->model->newQuery()
            ->ofCategory($category)
            ->orderBy('name')
            ->get();
    }
}
