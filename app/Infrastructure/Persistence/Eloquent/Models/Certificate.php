<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'certificates';

    protected $fillable = [
        'candidate_id',
        'name',
        'issue_date',
        'issuer_entity',
        'expiry_date',
        'link'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
