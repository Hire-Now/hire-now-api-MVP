<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiConsumer extends Model
{
    use SoftDeletes, HasUuids;

    protected $table = 'api_consumers';

    protected $fillable = [
        'name',
        'client_id',
        'client_secret',
        'description',
        'is_active',
        'last_access_at',
    ];

    protected $hidden = [
        'client_secret',
    ];

    public function jwtTokens()
    {
        return $this->hasMany(JwtToken::class);
    }
}
