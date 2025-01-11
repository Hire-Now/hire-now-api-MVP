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
        'phone_number_1',
        'phone_number_2',
        'whatsapp_number',
        'email_1',
        'email_2',
        'generated_platform_cv',
        'social_media'
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function language()
    {
        return $this->hasMany(Language::class);
    }

    public function skill()
    {
        return $this->hasMany(Skill::class);
    }

    public function previousExperience()
    {
        return $this->hasMany(PreviousExperience::class);
    }

    public function education()
    {
        return $this->hasMany(Education::class);
    }

    public function certificate()
    {
        return $this->hasMany(Certificate::class);
    }

    public function preferences()
    {
        return $this->hasOne(Preference::class);
    }

    public function paymentPreferences()
    {
        return $this->hasOne(PaymentPreference::class);
    }

    public function benefitsPreferences()
    {
        return $this->hasOne(BenefitsPreference::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'owner');
    }

    public function updateFilesToCandidate(User $user)
    {
        $user->files->each(function ($file) {
            $file->owner_id = $this->id;
            $file->owner_type = Candidate::class;
            $file->save();
        });
    }

    // public function languagesGrades()
    // {
    //     return $this->hasMany(LanguagesGrade::class);
    // }

    // public function technicalGrades()
    // {
    //     return $this->hasMany(TechnicalGrade::class);
    // }

    // public function completedAssessments()
    // {
    //     return $this->hasMany(CompletedAssessment::class);
    // }

    // public function activeProcesses()
    // {
    //     return $this->hasMany(ActiveProcess::class);
    // }

}
