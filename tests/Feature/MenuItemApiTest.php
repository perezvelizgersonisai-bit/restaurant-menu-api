<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuItemApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_puede_listar_los_elementos_del_menu(): void
    {
        MenuItem::factory()->count(3)->create();

        $response = $this->getJson('/api/menu-items');

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_puede_crear_un_elemento_del_menu(): void
    {
        $payload = [
            'name' => 'Sopa de Tortilla',
            'description' => 'Sopa tradicional con tiras de tortilla, aguacate y queso.',
            'price' => 4.75,
            'category' => 'Entradas',
            'available' => true,
        ];

        $response = $this->postJson('/api/menu-items', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Sopa de Tortilla');

        $this->assertDatabaseHas('menu_items', ['name' => 'Sopa de Tortilla']);
    }

    public function test_falla_al_crear_sin_campos_obligatorios(): void
    {
        $response = $this->postJson('/api/menu-items', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'category']);
    }

    public function test_puede_mostrar_un_elemento_especifico(): void
    {
        $item = MenuItem::factory()->create();

        $response = $this->getJson("/api/menu-items/{$item->id}");

        $response->assertOk()->assertJsonPath('data.id', $item->id);
    }

    public function test_devuelve_404_si_el_elemento_no_existe(): void
    {
        $response = $this->getJson('/api/menu-items/999999');

        $response->assertNotFound();
    }

    public function test_puede_actualizar_un_elemento(): void
    {
        $item = MenuItem::factory()->create(['price' => 10.00]);

        $response = $this->putJson("/api/menu-items/{$item->id}", ['price' => 12.50]);

        $response->assertOk()->assertJsonPath('data.price', 12.5);
    }

    public function test_puede_eliminar_un_elemento(): void
    {
        $item = MenuItem::factory()->create();

        $response = $this->deleteJson("/api/menu-items/{$item->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('menu_items', ['id' => $item->id]);
    }

    public function test_puede_filtrar_por_categoria(): void
    {
        MenuItem::factory()->count(2)->create(['category' => 'Postres']);
        MenuItem::factory()->count(1)->create(['category' => 'Bebidas']);

        $response = $this->getJson('/api/menu-items/category/Postres');

        $response->assertOk()->assertJsonCount(2, 'data');
    }
}
