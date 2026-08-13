<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Http\Resources\MenuItemResource;
use App\Services\MenuItemService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

/**
 * Controlador REST del recurso "menu-items".
 *
 * Responsabilidad única: traducir peticiones/respuestas HTTP.
 * Toda la lógica de negocio vive en MenuItemService; el controlador
 * no consulta Eloquent ni el repositorio directamente.
 */
class MenuItemController extends Controller
{
    public function __construct(
        protected MenuItemService $menuItemService
    ) {}

    /**
     * GET /api/menu-items
     * Lista todos los elementos del menú.
     */
    public function index(): JsonResponse
    {
        $items = $this->menuItemService->listAll();

        return response()->json([
            'success' => true,
            'data' => MenuItemResource::collection($items),
        ]);
    }

    /**
     * GET /api/menu-items/{id}
     * Muestra un elemento específico del menú.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $item = $this->menuItemService->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new MenuItemResource($item),
        ]);
    }

    /**
     * POST /api/menu-items
     * Crea un nuevo elemento del menú.
     */
    public function store(StoreMenuItemRequest $request): JsonResponse
    {
        $item = $this->menuItemService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Elemento del menú creado correctamente.',
            'data' => new MenuItemResource($item),
        ], 201);
    }

    /**
     * PUT/PATCH /api/menu-items/{id}
     * Actualiza un elemento existente del menú.
     */
    public function update(UpdateMenuItemRequest $request, int $id): JsonResponse
    {
        try {
            $item = $this->menuItemService->update($id, $request->validated());
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Elemento del menú actualizado correctamente.',
            'data' => new MenuItemResource($item),
        ]);
    }

    /**
     * DELETE /api/menu-items/{id}
     * Elimina un elemento del menú.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->menuItemService->delete($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Elemento del menú eliminado correctamente.',
        ]);
    }

    /**
     * GET /api/menu-items/category/{category}
     * Filtra los elementos del menú por categoría.
     */
    public function byCategory(string $category): JsonResponse
    {
        $items = $this->menuItemService->listByCategory($category);

        return response()->json([
            'success' => true,
            'category' => $category,
            'data' => MenuItemResource::collection($items),
        ]);
    }
}
