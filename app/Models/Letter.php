<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Letter extends Model
{
    protected $fillable = [
        'application_id', 'employer_id', 'candidate_id', 'job_listing_id',
        'letter_type', 'offer_date', 'joining_date', 'salary_ctc',
        'department', 'designation', 'work_location', 'employment_type',
        'reporting_manager', 'hr_name', 'hr_email', 'hr_phone',
        'terms', 'signature_name', 'content', 'pdf_path', 'status', 'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'offer_date' => 'date',
            'joining_date' => 'date',
            'sent_at' => 'datetime',
        ];
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function jobListing()
    {
        return $this->belongsTo(JobListing::class);
    }

    public function isOffer(): bool
    {
        return $this->letter_type === 'offer';
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function pdfExists(): bool
    {
        return $this->pdf_path && Storage::disk('local')->exists($this->pdf_path);
    }
}
