<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;
    use LogsActivity {
        shouldLogEvent as traitShouldLogEvent;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected function shouldLogEvent(string $eventName): bool
    {
        // Only log if the USER being modified has role_id 1 (Owner) or 2 (Admin)
        if (!in_array($this->role_id, [1, 2])) {
            return false;
        }

        return $this->traitShouldLogEvent($eventName);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'role_id',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
        ];
    }
    protected static function booted(): void
    {
        static::creating(function ($user) {
            $user->role_id ??= 3; // Default to 'customer' role
        });
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($roleName)
    {
        // Owner (role_id 1) has access to everything
        if ($this->role_id === 1) {
            return true;
        }

        if ($this->role) {
            return strtolower($this->role->name) === strtolower($roleName);
        }

        return false;
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
