<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'files';

    protected $fillable = [
        'name',
        'path',
        'type',
        'size',
        'owner_type',
        'owner_id',
        'metadata'
    ];

    public $timestamps = true;

    protected $hidden = [
        'metadata' => 'array',
    ];

    /**
     * Relación polimórfica: el propietario del archivo.
     */
    public function owner()
    {
        return $this->morphTo();
    }
}
