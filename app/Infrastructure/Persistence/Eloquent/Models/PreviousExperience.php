<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreviousExperience extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'candidate_id',
        'company_name',
        'role',
        'start_date',
        'end_date',
        'description',
        'technologies',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
