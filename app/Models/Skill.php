<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ['name'];

    public function jobListings()
    {
        return $this->belongsToMany(JobListing::class, 'job_skills');
    }
}
