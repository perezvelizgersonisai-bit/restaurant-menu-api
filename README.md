# La Buena Mesa — API RESTful de Gestión de Menú

API RESTful construida con **Laravel 12** y **Eloquent ORM** para centralizar la gestión del menú del restaurante *"La Buena Mesa"*. Permite que distintos sistemas (app de meseros, sistema de cocina, plataforma web) consulten y actualicen la información del menú de forma consistente y en tiempo real, mediante operaciones CRUD completas.

## Tabla de contenido

- [Arquitectura del proyecto](#arquitectura-del-proyecto)
- [Estructura de carpetas](#estructura-de-carpetas)
- [Requisitos previos](#requisitos-previos)
- [Instrucciones de instalación](#instrucciones-de-instalación)
- [Documentación de endpoints](#documentación-de-endpoints)
- [Ejemplos de uso de la API](#ejemplos-de-uso-de-la-api)
- [Pruebas automatizadas](#pruebas-automatizadas)

## Arquitectura del proyecto

El proyecto sigue **arquitectura limpia por capas**, separando responsabilidades para que cada clase tenga un único motivo de cambio:

```
Petición HTTP
    │
    ▼
Controller (App\Http\Controllers\Api\MenuItemController)
    │   — Solo traduce HTTP ⇄ datos. No conoce Eloquent.
    ▼
Service (App\Services\MenuItemService)
    │   — Reglas de negocio. No conoce HTTP ni el ORM directamente.
    ▼
Repository Interface (App\Repositories\Contracts\MenuItemRepositoryInterface)
    │   — Contrato de acceso a datos (principio de inversión de dependencias).
    ▼
Repository Eloquent (App\Repositories\Eloquent\MenuItemRepository)
    │   — Única clase que habla con Eloquent / la base de datos.
    ▼
Model (App\Models\MenuItem)
```

**Ventajas de este diseño:**
- El `Controller` solo orquesta la petición/respuesta HTTP y delega todo al `Service`.
- El `Service` concentra la lógica de negocio (por ejemplo, lanzar `ModelNotFoundException` cuando corresponde) sin acoplarse a Eloquent.
- El `Repository` es la única capa que sabe cómo se guardan los datos; se podría reemplazar Eloquent por otra fuente de datos sin tocar el resto del sistema.
- El binding `MenuItemRepositoryInterface → MenuItemRepository` se declara en `RepositoryServiceProvider`, permitiendo inyección de dependencias y facilitando pruebas con mocks.
- Los `Form Requests` (`StoreMenuItemRequest`, `UpdateMenuItemRequest`) separan la validación del controlador.
- El `API Resource` (`MenuItemResource`) separa la forma en que se guardan los datos de la forma en que se exponen al consumidor de la API.

## Estructura de carpetas

```
app/
├── Http/
│   ├── Controllers/Api/MenuItemController.php
│   ├── Requests/StoreMenuItemRequest.php
│   ├── Requests/UpdateMenuItemRequest.php
│   └── Resources/MenuItemResource.php
├── Models/MenuItem.php
├── Repositories/
│   ├── Contracts/MenuItemRepositoryInterface.php
│   └── Eloquent/MenuItemRepository.php
├── Services/MenuItemService.php
└── Providers/RepositoryServiceProvider.php

database/
├── factories/MenuItemFactory.php
├── migrations/2026_08_12_000001_create_menu_items_table.php
└── seeders/{DatabaseSeeder.php, MenuItemSeeder.php}

routes/api.php
tests/Feature/MenuItemApiTest.php
```

## Requisitos previos

- PHP >= 8.2
- Composer 2
- Extensión `sqlite3` de PHP habilitada (o MySQL/PostgreSQL si prefieres esa opción)

## Instrucciones de instalación

Este repositorio contiene el **código fuente propio** de la aplicación (modelos, controladores, rutas, migraciones, etc.). Las dependencias de terceros de Laravel (carpeta `vendor/`) **no se versionan** en Git, tal como indica `.gitignore`, y se instalan con Composer.

```bash
# 1. Clonar el repositorio
git clone <URL-del-repositorio>
cd restaurant-menu-api

# 2. Instalar dependencias de PHP
composer install

# 3. Crear el archivo de entorno a partir del ejemplo
cp .env.example .env

# 4. Generar la clave de la aplicación
php artisan key:generate

# 5. Crear la base de datos SQLite (opción por defecto, la más rápida para probar)
touch database/database.sqlite

# 6. Ejecutar las migraciones y cargar datos de ejemplo
php artisan migrate --seed

# 7. Levantar el servidor de desarrollo
php artisan serve
```

La API quedará disponible en `http://localhost:8000/api`.

> **¿Prefieres MySQL o PostgreSQL?** Edita en `.env` las variables `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD` (las líneas de ejemplo ya están comentadas en `.env.example`), y luego ejecuta el paso 6 normalmente.

## Documentación de endpoints

Prefijo base: **`/api/menu-items`**

| Método | Endpoint | Descripción |
|---|---|---|
| `GET` | `/api/menu-items` | Lista todos los elementos del menú |
| `GET` | `/api/menu-items/{id}` | Obtiene un elemento específico por su id |
| `POST` | `/api/menu-items` | Crea un nuevo elemento del menú |
| `PUT` / `PATCH` | `/api/menu-items/{id}` | Actualiza un elemento existente |
| `DELETE` | `/api/menu-items/{id}` | Elimina un elemento del menú |
| `GET` | `/api/menu-items/category/{category}` | Filtra los elementos por categoría |

**Campos del recurso `menu-item`:**

| Campo | Tipo | Obligatorio | Notas |
|---|---|---|---|
| `name` | string | Sí | Máx. 150 caracteres |
| `description` | string | No | Máx. 2000 caracteres |
| `price` | decimal | Sí | No puede ser negativo |
| `category` | string | Sí | Ej. "Entradas", "Platos Fuertes", "Postres", "Bebidas" |
| `available` | boolean | No | Por defecto `true` |
| `image_url` | string (URL) | No | — |

**Formato de respuesta estándar (éxito):**

```json
{
  "success": true,
  "data": { "...": "..." }
}
```

**Formato de respuesta estándar (error de validación, HTTP 422):**

```json
{
  "success": false,
  "message": "Los datos enviados no son válidos.",
  "errors": {
    "name": ["El nombre del platillo es obligatorio."]
  }
}
```

## Ejemplos de uso de la API

### 1. Listar todos los elementos del menú

```bash
curl -X GET http://localhost:8000/api/menu-items
```

### 2. Obtener un elemento específico

```bash
curl -X GET http://localhost:8000/api/menu-items/1
```

### 3. Crear un nuevo elemento

```bash
curl -X POST http://localhost:8000/api/menu-items \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
        "name": "Enchiladas Verdes",
        "description": "Tortillas rellenas de pollo bañadas en salsa verde con crema y queso.",
        "price": 9.50,
        "category": "Platos Fuertes",
        "available": true
      }'
```

Respuesta (`201 Created`):

```json
{
  "success": true,
  "message": "Elemento del menú creado correctamente.",
  "data": {
    "id": 10,
    "name": "Enchiladas Verdes",
    "description": "Tortillas rellenas de pollo bañadas en salsa verde con crema y queso.",
    "price": 9.5,
    "category": "Platos Fuertes",
    "available": true,
    "image_url": null,
    "created_at": "2026-08-12T18:00:00+00:00",
    "updated_at": "2026-08-12T18:00:00+00:00"
  }
}
```

### 4. Actualizar el precio de un elemento (actualización parcial)

```bash
curl -X PATCH http://localhost:8000/api/menu-items/10 \
  -H "Content-Type: application/json" \
  -d '{ "price": 10.25 }'
```

### 5. Eliminar un elemento

```bash
curl -X DELETE http://localhost:8000/api/menu-items/10
```

### 6. Filtrar por categoría

```bash
curl -X GET http://localhost:8000/api/menu-items/category/Postres
```

## Pruebas automatizadas

El repositorio incluye pruebas de feature (`tests/Feature/MenuItemApiTest.php`) que cubren los seis endpoints, casos de éxito, validación fallida (422) y recurso no encontrado (404):

```bash
php artisan test
```
