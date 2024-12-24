<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActiveProcess extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'candidate_id',
        'offer_id',
        'status',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
