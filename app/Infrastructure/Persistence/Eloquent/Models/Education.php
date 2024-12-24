<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Education extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'candidate_id',
        'degree',
        'institution',
        'field_of_study',
        'start_date',
        'end_date',
        'grade',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
