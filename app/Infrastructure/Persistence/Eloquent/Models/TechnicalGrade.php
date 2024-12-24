<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TechnicalGrade extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'candidate_id',
        'technology',
        'grade',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
