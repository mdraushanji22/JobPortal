<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'user_id', 'phone', 'skills', 'education', 'experience',
        'portfolio_url', 'linkedin_url', 'github_url',
        'profile_picture', 'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resumes()
    {
        return $this->hasMany(Resume::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function savedJobs()
    {
        return $this->hasMany(SavedJob::class);
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }
}
