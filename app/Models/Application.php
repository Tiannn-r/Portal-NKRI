<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use InvalidArgumentException;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role;

class Application extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'url',
        'app_category_id',
        'pic_user_id',
        'status',
        'sort_order',
        'open_in_new_tab',
        'login_hint',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'open_in_new_tab' => 'boolean',
        ];
    }

    /**
     * Konfigurasi pencatatan audit log Spatie.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Validasi URL skema http/https sebelum disimpan ke database.
     */
    protected static function booted(): void
    {
        static::saving(function (Application $app) {
            if (!empty($app->url) && !preg_match('/^https?:\/\//i', $app->url)) {
                throw new InvalidArgumentException('URL aplikasi wajib diawali skema http:// atau https://');
            }
        });
    }

    /**
     * Relasi ke Kategori Aplikasi.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(AppCategory::class, 'app_category_id');
    }

    /**
     * Relasi ke PIC (Person in Charge) Pengguna.
     */
    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }

    /**
     * Relasi langsung ke Pengguna yang memiliki izin akses ke sistem ini.
     */
    public function allowedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'application_user')
            ->withPivotValue('type', 'allow')
            ->withTimestamps();
    }

    /**
     * Relasi ke Roles yang berhak mengakses aplikasi ini.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'application_role')
            ->withTimestamps();
    }

    /**
     * Relasi ke Departemen yang berhak mengakses aplikasi ini.
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'application_department')
            ->withTimestamps();
    }

    /**
     * Relasi ke Pengguna dengan izin khusus (allow / deny).
     */
    public function userExceptions(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'application_user')
            ->withPivot('type', 'notes')
            ->withTimestamps();
    }

    /**
     * Relasi ke penanda favorit pengguna.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(UserFavorite::class);
    }

    /**
     * Relasi ke log peluncuran aplikasi.
     */
    public function launchLogs(): HasMany
    {
        return $this->hasMany(AppLaunchLog::class);
    }
}

