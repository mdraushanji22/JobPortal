<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedJob extends Model
{
    protected $fillable = ['candidate_id', 'job_listing_id'];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function jobListing()
    {
        return $this->belongsTo(JobListing::class);
    }
}
