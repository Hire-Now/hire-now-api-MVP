<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'candidates';

    protected $fillable = [
        'user_id',
        'years_of_experience',
        'uploaded_cv',
        'uploaded_pitch',
        'generated_platform_cv',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function languages()
    {
        return $this->hasMany(Language::class);
    }

    public function previousExperiences()
    {
        return $this->hasMany(PreviousExperience::class);
    }

    public function education()
    {
        return $this->hasMany(Education::class);
    }

    public function languagesGrades()
    {
        return $this->hasMany(LanguagesGrade::class);
    }

    public function technicalGrades()
    {
        return $this->hasMany(TechnicalGrade::class);
    }

    public function completedAssessments()
    {
        return $this->hasMany(CompletedAssessment::class);
    }

    public function activeProcesses()
    {
        return $this->hasMany(ActiveProcess::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'owner');
    }
}
