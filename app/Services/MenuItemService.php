<?php

namespace App\Services;

use App\Models\MenuItem;
use App\Repositories\Contracts\MenuItemRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Capa de servicio: concentra la lógica de negocio relacionada con el menú.
 *
 * El controlador nunca habla directamente con el repositorio ni con
 * Eloquent; siempre pasa por este servicio, que es el lugar indicado
 * para futuras reglas de negocio (por ejemplo: no permitir eliminar
 * un platillo con pedidos activos, registrar auditoría de cambios de
 * precio, invalidar caché, etc.) sin tocar el controlador ni el
 * repositorio.
 */
class MenuItemService
{
    public function __construct(
        protected MenuItemRepositoryInterface $repository
    ) {}

    public function listAll(): Collection
    {
        return $this->repository->all();
    }

    public function findOrFail(int $id): MenuItem
    {
        $menuItem = $this->repository->find($id);

        if (! $menuItem) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException(
                "El elemento del menú con id {$id} no existe."
            );
        }

        return $menuItem;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): MenuItem
    {
        return $this->repository->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): MenuItem
    {
        $menuItem = $this->repository->update($id, $data);

        if (! $menuItem) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException(
                "El elemento del menú con id {$id} no existe."
            );
        }

        return $menuItem;
    }

    public function delete(int $id): void
    {
        $deleted = $this->repository->delete($id);

        if (! $deleted) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException(
                "El elemento del menú con id {$id} no existe."
            );
        }
    }

    public function listByCategory(string $category): Collection
    {
        return $this->repository->findByCategory($category);
    }
}
