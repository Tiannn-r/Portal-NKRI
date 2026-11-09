<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    /**
     * Relasi ke seluruh aplikasi dalam kategori ini.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'app_category_id')->orderBy('sort_order');
    }
}
