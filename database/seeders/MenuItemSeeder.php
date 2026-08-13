<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

/**
 * Carga datos de ejemplo realistas para el menú de "La Buena Mesa",
 * cubriendo las cuatro categorías principales del restaurante.
 */
class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Ceviche de Camarón',
                'description' => 'Camarón fresco marinado en limón, cebolla morada, cilantro y un toque de chile.',
                'price' => 8.50,
                'category' => 'Entradas',
                'available' => true,
            ],
            [
                'name' => 'Tostadas de Atún',
                'description' => 'Atún sellado sobre tostada crujiente con aguacate y salsa de soya-jengibre.',
                'price' => 7.25,
                'category' => 'Entradas',
                'available' => true,
            ],
            [
                'name' => 'Risotto de Hongos Silvestres',
                'description' => 'Arroz arbóreo cremoso con mezcla de hongos, parmesano y aceite de trufa.',
                'price' => 13.90,
                'category' => 'Platos Fuertes',
                'available' => true,
            ],
            [
                'name' => 'Salmón a la Parrilla',
                'description' => 'Filete de salmón a la parrilla con puré de camote y espárragos salteados.',
                'price' => 16.75,
                'category' => 'Platos Fuertes',
                'available' => true,
            ],
            [
                'name' => 'Costillas Glaseadas',
                'description' => 'Costillas de cerdo cocidas lentamente con glaseado de tamarindo y especias.',
                'price' => 15.00,
                'category' => 'Platos Fuertes',
                'available' => false,
            ],
            [
                'name' => 'Tiramisú Clásico',
                'description' => 'Capas de bizcocho de café, mascarpone y cacao en polvo.',
                'price' => 5.50,
                'category' => 'Postres',
                'available' => true,
            ],
            [
                'name' => 'Volcán de Chocolate',
                'description' => 'Pastel de chocolate con centro líquido, acompañado de helado de vainilla.',
                'price' => 6.00,
                'category' => 'Postres',
                'available' => true,
            ],
            [
                'name' => 'Limonada de Coco',
                'description' => 'Limonada artesanal con un toque de crema de coco.',
                'price' => 3.25,
                'category' => 'Bebidas',
                'available' => true,
            ],
            [
                'name' => 'Copa de Vino Tinto de la Casa',
                'description' => 'Selección de la casa, ideal para acompañar platos fuertes.',
                'price' => 6.50,
                'category' => 'Bebidas',
                'available' => true,
            ],
        ];

        foreach ($items as $item) {
            MenuItem::query()->create($item);
        }
    }
}
