<?php

namespace App\Repositories\Contracts;

use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Collection;

/**
 * Contrato del repositorio de elementos del menú.
 *
 * Definir la capa de acceso a datos detrás de una interfaz permite
 * desacoplar la lógica de negocio (Services) de la fuente de datos
 * concreta (Eloquent, otra ORM, un mock de pruebas, etc.), siguiendo
 * el principio de inversión de dependencias de la arquitectura limpia.
 */
interface MenuItemRepositoryInterface
{
    /**
     * Obtiene todos los elementos del menú.
     */
    public function all(): Collection;

    /**
     * Busca un elemento por su identificador. Devuelve null si no existe.
     */
    public function find(int $id): ?MenuItem;

    /**
     * Crea un nuevo elemento del menú.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): MenuItem;

    /**
     * Actualiza un elemento existente. Devuelve null si no existe.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): ?MenuItem;

    /**
     * Elimina un elemento por su identificador. Devuelve false si no existía.
     */
    public function delete(int $id): bool;

    /**
     * Obtiene los elementos que pertenecen a una categoría específica.
     */
    public function findByCategory(string $category): Collection;
}
