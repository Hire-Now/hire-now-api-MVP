<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

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
        'role',
        'status',
        'last_activity',
        'email_verified_at',
        'birth_date'
    ];

    public $timestamps = true;

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }
}
