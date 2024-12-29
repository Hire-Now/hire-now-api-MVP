<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'permissions';

    protected $fillable = [ 'name', 'description', 'status' ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}
