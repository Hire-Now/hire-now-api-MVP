<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasUuids, SoftDeletes;
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'last_activity',
        'birth_date'
    ];

    public $timestamps = true;

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
            'created_at'    => 'datetime',
            'updated_at'    => 'datetime',
            'birth_date'    => 'datetime',
            'last_activity' => 'datetime',
            'password'      => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }

    public function jwtTokens()
    {
        return $this->hasMany(JwtToken::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'owner');
    }
}
