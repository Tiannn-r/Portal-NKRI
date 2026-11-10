<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Memastikan hanya role Super Admin yang aktif yang diperkenankan masuk ke panel CMS.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active && $this->hasRole('Super Admin');
    }

    /**
     * Atribut yang dapat diisi secara massal (mass-assignment protection).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'is_active',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Konversi tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relasi langsung ke Sistem Digital yang diizinkan untuk diakses pengguna.
     */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Application::class, 'application_user')
            ->withPivotValue('type', 'allow')
            ->withTimestamps();
    }

    /**
     * Relasi ke Departemen pengguna.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relasi ke pengecualian hak akses aplikasi (allow/deny).
     */
    public function applicationExceptions(): BelongsToMany
    {
        return $this->belongsToMany(Application::class, 'application_user')
            ->withPivot('type', 'notes')
            ->withTimestamps();
    }

    /**
     * Relasi ke daftar favorit pengguna.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(UserFavorite::class);
    }

    /**
     * Relasi langsung ke aplikasi yang difavoritkan.
     */
    public function favoriteApplications(): BelongsToMany
    {
        return $this->belongsToMany(Application::class, 'user_favorites')
            ->withTimestamps();
    }

    /**
     * Relasi ke riwayat peluncuran aplikasi pengguna.
     */
    public function launchLogs(): HasMany
    {
        return $this->hasMany(AppLaunchLog::class);
    }
}
