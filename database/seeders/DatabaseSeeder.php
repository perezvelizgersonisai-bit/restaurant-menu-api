<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Punto de entrada de "php artisan db:seed".
     */
    public function run(): void
    {
        $this->call([
            MenuItemSeeder::class,
        ]);
    }
}
