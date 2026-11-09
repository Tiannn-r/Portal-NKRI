<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    /**
     * Relasi ke seluruh pengguna di departemen ini.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relasi ke aplikasi yang diberikan izin akses untuk departemen ini.
     */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Application::class, 'application_department')
            ->withTimestamps();
    }
}

