<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'job_listing_id', 'candidate_id', 'resume_id',
        'cover_letter', 'status', 'employer_notes', 'applied_at',
    ];

    protected function casts(): array
    {
        return [
            'applied_at' => 'datetime',
        ];
    }

    public function jobListing()
    {
        return $this->belongsTo(JobListing::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }

    public function interview()
    {
        return $this->hasOne(Interview::class);
    }
}
