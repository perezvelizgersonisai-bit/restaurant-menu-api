<?php

namespace App\Models;

use Database\Factories\MenuItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent que representa un elemento del menú del restaurante.
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property string $category
 * @property bool $available
 * @property string|null $image_url
 */
class MenuItem extends Model
{
    /** @use HasFactory<MenuItemFactory> */
    use HasFactory;

    /**
     * Atributos asignables en masa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'available',
        'image_url',
    ];

    /**
     * Casteo de atributos a tipos nativos de PHP.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'available' => 'boolean',
        ];
    }

    /**
     * Scope de consulta: filtra los elementos de una categoría específica.
     */
    public function scopeOfCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
